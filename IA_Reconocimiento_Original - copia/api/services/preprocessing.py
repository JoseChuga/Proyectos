# api/services/preprocessing.py

import numpy as np
from PIL import Image
import io

# Configuración base
TARGET_SIZE = (512, 512)        # Tamaño final esperado por el modelo
HALF_HEIGHT = TARGET_SIZE[1] // 2  # 512 / 2 = 256
INTENSIDAD_UMBRAL = 0.02       # Ajustable: varianza mínima para considerar “imagen con contenido”

def leer_ppm_bytes(ppm_bytes: bytes) -> np.ndarray:
    """
    Convierte bytes de un archivo .ppm (ASCII/P3) a un arreglo normalizado (512×512×3).
    Esta función NO divide en mitades; simplemente arroja la imagen completa redimensionada.
    """
    contenido = ppm_bytes.decode("ascii")
    # Filtrar líneas vacías y comentarios
    lineas = [line.strip() for line in contenido.splitlines() if line.strip() and not line.startswith('#')]

    if lineas[0] != 'P3':
        raise ValueError("Formato PPM inválido: se esperaba encabezado 'P3'")

    ancho, alto = map(int, lineas[1].split())
    max_valor = int(lineas[2])
    pixeles_str = ' '.join(lineas[3:]).split()

    if len(pixeles_str) != ancho * alto * 3:
        raise ValueError("Número de valores RGB no coincide con las dimensiones declaradas")

    pixeles = list(map(int, pixeles_str))
    arr = np.array(pixeles, dtype=np.uint8).reshape((alto, ancho, 3)) / max_valor

    # Redimensionar a 512×512 (si no viene ya en ese tamaño)
    img = Image.fromarray((arr * 255).astype(np.uint8))
    img = img.resize(TARGET_SIZE, resample=Image.LANCZOS)
    arr_resized = np.array(img) / 255.0

    return arr_resized


def dividir_por_mitad_vertical(ppm_bytes: bytes, umbral_varianza: float = INTENSIDAD_UMBRAL) -> list:
    """
    Divide la imagen en dos mitades (izquierda y derecha), cada una reconstruida a 512×512
    mediante un efecto espejo horizontal.
    Devuelve lista de vectores de características (longitud: 3072) para cada mitad “válida”.
    Si una mitad tiene varianza por debajo de umbral_varianza, se descarta.

    Retorna:
        list de np.ndarray, donde cada array es un vector (3072,) normalizado en [0,1].
    """
    # 1) Leer PPM completo y redimensionar a 512×512
    arr_completo = leer_ppm_bytes(ppm_bytes)  # Shape: (512, 512, 3)

    HALF_WIDTH = 256  # División vertical en columnas
    vectores = []

    # 2) Extraer dos mitades verticales:
    #    - Mitad izquierda: columnas [0:256]
    #    - Mitad derecha: columnas [256:512]
    mitad_izq = arr_completo[:, 0:HALF_WIDTH, :]    # Shape: (512, 256, 3)
    mitad_der = arr_completo[:, HALF_WIDTH:512, :]  # Shape: (512, 256, 3)

    for idx, mitad in enumerate([mitad_izq, mitad_der], start=1):
        # 3) Calcular varianza de la mitad:
        var = float(np.var(mitad))
        if var < umbral_varianza:
            continue  # Descartar mitades sin contenido relevante

        # 4) Crear imagen espejo horizontal para reconstruir 512×512:
        #    - Si idx == 1 (izquierda), original a la izquierda, espejo a la derecha
        #    - Si idx == 2 (derecha), espejo a la izquierda, original a la derecha
        espejo = np.flip(mitad, axis=1)  # Flip horizontal

        if idx == 1:
            reconstruida = np.hstack([mitad, espejo])  # Shape: (512, 512, 3)
        else:
            reconstruida = np.hstack([espejo, mitad])

        # 5) Extraer vector de características
        vector = extraer_vector(reconstruida)
        vectores.append(vector)

    return vectores



def extraer_vector(arr: np.ndarray) -> np.ndarray:
    """
    Extrae un vector de 3072 características de un arreglo (512×512×3),
    calculando la media de bloques 16×16. 
    """
    BLOCK_SIZE = 16
    h, w, c = arr.shape           # (512, 512, 3)
    nb_h = h // BLOCK_SIZE        # 512/16 = 32
    nb_w = w // BLOCK_SIZE        # 512/16 = 32

    features = np.zeros((nb_h, nb_w, c), dtype=np.float32)
    for i in range(nb_h):
        for j in range(nb_w):
            y0, y1 = i * BLOCK_SIZE, (i + 1) * BLOCK_SIZE
            x0, x1 = j * BLOCK_SIZE, (j + 1) * BLOCK_SIZE
            block = arr[y0:y1, x0:x1, :]
            features[i, j, :] = block.mean(axis=(0, 1))

    return features.reshape(-1)

# convert_to_ppm.py
import os
from PIL import Image

# Carpeta original con imágenes reales
ORIGEN_DIR = 'dataset/Papas y Hamburguesas'
# Carpeta destino para archivos .ppm
DESTINO_DIR = 'Papas_y_Hamburguesas_ppm'

def convertir_a_ppm(origen_path, destino_path):
    imagen = Image.open(origen_path).convert('RGB')
    ancho, alto = imagen.size
    pixeles = list(imagen.getdata())

    with open(destino_path, 'w') as f:
        f.write("P3\n")
        f.write(f"{ancho} {alto}\n")
        f.write("255\n")
        for r, g, b in pixeles:
            f.write(f"{r} {g} {b} ")

def procesar_directorio(origen, destino):
    print(f"Buscando imágenes en: {os.path.abspath(origen)}")
    imagenes_encontradas = 0

    for raiz, _, archivos in os.walk(origen):
        for archivo in archivos:
            if archivo.lower().endswith(('.jpg', '.jpeg', '.png')):
                imagenes_encontradas += 1
                origen_img = os.path.join(raiz, archivo)

                rel_path = os.path.relpath(origen_img, origen)
                destino_img = os.path.join(destino, os.path.splitext(rel_path)[0] + '.ppm')

                os.makedirs(os.path.dirname(destino_img), exist_ok=True)
                try:
                    convertir_a_ppm(origen_img, destino_img)
                    print(f"[✔] Convertido: {origen_img} → {destino_img}")
                except Exception as e:
                    print(f"[✘] Error con {origen_img}: {e}")

    if imagenes_encontradas == 0:
        print("⚠️ No se encontraron imágenes compatibles en la carpeta datasets/")
    else:
        print(f"✅ Conversión finalizada. Total de imágenes procesadas: {imagenes_encontradas}")

if __name__ == "__main__":
    procesar_directorio(ORIGEN_DIR, DESTINO_DIR)

    

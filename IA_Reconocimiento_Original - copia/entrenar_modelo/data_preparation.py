# data_preparation.py

import csv
import numpy as np
from sklearn.preprocessing import LabelEncoder
from utils import dividir_datos

def cargar_datos_desde_csv(path_csv):
    """
    Carga y prepara los datos desde un CSV. Convierte los vectores de color y etiqueta
    en matrices NumPy, codifica las etiquetas y divide en sets de entrenamiento/prueba.

    Args:
        path_csv (str): Ruta al archivo CSV.

    Returns:
        tuple: (X_train, X_test, y_train, y_test), encoder
    """
    X = []
    y = []
    filas_invalidas = []

    with open(path_csv, newline='', encoding='utf-8') as archivo:
        lector = csv.reader(archivo)
        for index, fila in enumerate(lector, start=1):
            if not fila:
                continue  # saltar líneas vacías

            *vector, etiqueta = fila
            try:
                vector = list(map(float, vector))
                X.append(vector)
                y.append(etiqueta)
            except ValueError:
                filas_invalidas.append((index, fila))
                continue

    if not X:
        raise ValueError("No se pudieron cargar datos válidos del CSV.")

    # Validar longitud de vectores
    longitud_objetivo = len(X[0])
    X_filtrado, y_filtrado = [], []
    for v, label in zip(X, y):
        if len(v) == longitud_objetivo:
            X_filtrado.append(v)
            y_filtrado.append(label)
        else:
            filas_invalidas.append(("longitud_invalida", v))

    X = np.array(X_filtrado, dtype=np.float32)
    y = np.array(y_filtrado)

    # Reportar inconsistencias
    if filas_invalidas:
        print("\n[ADVERTENCIA] Se detectaron filas conflictivas:")
        for detalle in filas_invalidas:
            print(f"  - Fila {detalle[0]}: {detalle[1]}")
        print(f"[INFO] Total de filas inválidas descartadas: {len(filas_invalidas)}")

    # ✅ Codificar etiquetas como enteros para sparse_categorical_crossentropy
    encoder = LabelEncoder()
    y_codificado = encoder.fit_transform(y).astype(np.int32)

    # Dividir en entrenamiento y prueba
    return dividir_datos(X, y_codificado), encoder


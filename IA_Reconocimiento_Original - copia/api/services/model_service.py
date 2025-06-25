import time
import numpy as np
from tensorflow.keras.models import load_model
from api.services.utils import convertir_a_ppm_bytes
import joblib
from collections import defaultdict

from api.services.calorias import (
    obtener_info_nutricional_multiple,
    obtener_info_combo,
    NOMBRE_LEGIBLE,
    INFO_NUTRICIONAL_ALIMENTOS
)
from api.services.preprocessing import dividir_por_mitad_vertical

_model = load_model("entrenar_modelo/modelo_entrenado.h5")
_encoder = joblib.load("entrenar_modelo/encoder.pkl")

def predecir_por_mitad(imagen_bytes: bytes) -> dict:
    # Detectar si es PPM o debe convertirse
    if imagen_bytes.startswith(b'P3'):
        ppm_bytes = imagen_bytes
    else:
        ppm_bytes = convertir_a_ppm_bytes(imagen_bytes)

    mitades_vectores = dividir_por_mitad_vertical(ppm_bytes)

    if len(mitades_vectores) == 0:
        raise ValueError("La imagen está demasiado oscura o no contiene información relevante.")

    start = time.time()
    resultados_raw = []

    for vector in mitades_vectores:
        preds = _model.predict(np.expand_dims(vector, axis=0))[0]
        idx = np.argmax(preds)
        clase = _encoder.inverse_transform([idx])[0]
        prob = float(preds[idx])
        resultados_raw.append((clase, prob))

    elapsed = (time.time() - start) * 1000

    resumen = defaultdict(lambda: {"max_prob": 0.0, "count": 0})
    for clase, prob in resultados_raw:
        if prob > resumen[clase]["max_prob"]:
            resumen[clase]["max_prob"] = prob
        resumen[clase]["count"] += 1

    clases_detectadas = list(resumen.keys())
    combo_info = obtener_info_combo(clases_detectadas)

    detalles = []
    calorias_totales = 0

    if combo_info:
        calorias_por_item = {
            NOMBRE_LEGIBLE["hambuerguesas"]: INFO_NUTRICIONAL_ALIMENTOS["hambuerguesas"]["calorias"],
            NOMBRE_LEGIBLE["papas_fritas"]: INFO_NUTRICIONAL_ALIMENTOS["papas_fritas"]["calorias"]
        }

        detalles.append({
            "alimento": combo_info.get("nombre_legible", combo_info["alimento"]),
            "clave_interna": combo_info["alimento"],
            "probabilidad_maxima": max(d["max_prob"] for d in resumen.values()),
            "coincidencias": sum(d["count"] for d in resumen.values()),
            "calorias_por_item": calorias_por_item,
            "calorias_totales_categoria": combo_info["calorias"],
            "informacion_nutricional": combo_info
        })
        calorias_totales = combo_info["calorias"]
    else:
        info_nutricional_total = obtener_info_nutricional_multiple(clases_detectadas)
        alimentos_detectados = info_nutricional_total.get("alimentos_detectados", [])

        for datos in alimentos_detectados:
            nombre = datos["alimento"]
            calorias = datos.get("calorias", 0)
            calorias_totales += calorias

            detalles.append({
                "alimento": datos.get("nombre_legible", nombre),
                "clave_interna": nombre,
                "probabilidad_maxima": resumen[nombre]["max_prob"],
                "coincidencias": resumen[nombre]["count"],
                "calorias_por_item": calorias,
                "calorias_totales_categoria": calorias,
                "informacion_nutricional": datos
            })

    return {
        "detalles": detalles,
        "calorias_totales": calorias_totales,
        "tiempo_total_ms": elapsed
    }


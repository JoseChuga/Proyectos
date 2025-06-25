# api/services/calorias.py

INFO_NUTRICIONAL_ALIMENTOS = {
    "hambuerguesas": {
        "calorias": 300,
        "proteinas": 17,
        "grasas": 22,
        "carbohidratos": 25,
        "fibra": 1.5,
        "azucares": 5,
        "sodio": 700,
        "colesterol": 55,
        "riesgos": ["Alta en grasas saturadas", "Contiene colesterol elevado"],
        "restricciones": ["No apto para veganos", "Puede contener gluten"],
        "recomendaciones": ["Consumir con moderación", "Mejor acompañada de ensalada"]
    },
    "papas_fritas": {
        "calorias": 320,
        "proteinas": 3,
        "grasas": 15,
        "carbohidratos": 30,
        "fibra": 2.5,
        "azucares": 0,
        "sodio": 280,
        "colesterol": 0,
        "riesgos": [
            "Fritura puede generar compuestos cancerígenos si se recalienta el aceite"
        ],
        "restricciones": ["Apto para vegetarianos", "No apto para dietas bajas en sodio"],
        "recomendaciones": ["Ideal como acompañamiento ocasional"]
    }
}

NOMBRE_LEGIBLE = {
    "hambuerguesas": "hamburguesa",
    "papas_fritas": "papas fritas",
    "combo_hamburguesa_papas": "combo hamburguesa con papas"
}

COBERTURA_COMBOS = {
    "combo_hamburguesa_papas": {
        "elementos": {"hambuerguesas", "papas_fritas"},
        "umbral": 0.8
    }
}

def obtener_info_combo(alimentos: list) -> dict:
    claves = set(alimento.strip().lower() for alimento in alimentos)

    if "hambuerguesas" in claves and "papas_fritas" in claves:
        h = INFO_NUTRICIONAL_ALIMENTOS["hambuerguesas"]
        p = INFO_NUTRICIONAL_ALIMENTOS["papas_fritas"]

        return {
            "alimento": "combo_hamburguesa_papas",
            "nombre_legible": NOMBRE_LEGIBLE.get("combo_hamburguesa_papas", "combo"),
            "calorias": h["calorias"] + p["calorias"],
            "proteinas": h["proteinas"] + p["proteinas"],
            "grasas": h["grasas"] + p["grasas"],
            "carbohidratos": h["carbohidratos"] + p["carbohidratos"],
            "fibra": h["fibra"] + p["fibra"],
            "azucares": h["azucares"] + p["azucares"],
            "sodio": h["sodio"] + p["sodio"],
            "colesterol": h["colesterol"] + p["colesterol"],
            "riesgos": list(set(h["riesgos"] + p["riesgos"] + [
                "Consumido frecuentemente puede elevar el riesgo cardiovascular"
            ])),
            "restricciones": list(set(h["restricciones"] + p["restricciones"])),
            "recomendaciones": list(set(h["recomendaciones"] + p["recomendaciones"] + [
                "Evitar bebidas azucaradas junto al combo",
                "Complementar con vegetales frescos o fruta"
            ]))
        }
    return None

def obtener_info_nutricional(alimento: str) -> dict:
    key = alimento.strip().lower()

    if key in INFO_NUTRICIONAL_ALIMENTOS:
        info = INFO_NUTRICIONAL_ALIMENTOS[key]
        return {
            "alimento": key,
            "nombre_legible": NOMBRE_LEGIBLE.get(key, key),
            **info
        }

    return {
        "alimento": key,
        "nombre_legible": NOMBRE_LEGIBLE.get(key, key),
        "calorias": 0,
        "proteinas": 0,
        "grasas": 0,
        "carbohidratos": 0,
        "fibra": 0,
        "azucares": 0,
        "sodio": 0,
        "colesterol": 0,
        "riesgos": ["Información no disponible"],
        "restricciones": [],
        "recomendaciones": []
    }

def obtener_info_nutricional_multiple(alimentos: list) -> dict:
    combo = obtener_info_combo(alimentos)
    if combo:
        return {
            "alimentos_detectados": [combo]
        }

    resultados = []
    for alimento in alimentos:
        info = obtener_info_nutricional(alimento)
        resultados.append(info)

    return {
        "alimentos_detectados": resultados
    }

from fastapi import APIRouter, UploadFile, File, HTTPException
from api.preprocesamiento.lector import leer_ppm
from api.preprocesamiento.bloques import dividir_en_bloques
import os
import shutil

router = APIRouter()

@router.post("/analizar-imagen/")
async def analizar_imagen(file: UploadFile = File(...)):
    if not file.filename.endswith(".ppm"):
        raise HTTPException(status_code=400, detail="Solo se permiten archivos .ppm")

    ruta_temporal = f"data/{file.filename}"
    with open(ruta_temporal, "wb") as buffer:
        shutil.copyfileobj(file.file, buffer)

    try:
        matriz, ancho, alto = leer_ppm(ruta_temporal)
        bloques = dividir_en_bloques(matriz, ancho, alto, 2)

        resultado = {
            "dimensiones": f"{ancho}x{alto}",
            "total_bloques_2x2": len(bloques),
            "primeros_5_pixeles": matriz[0][:5],
            "primer_bloque": bloques[0]
        }

        return resultado

    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

    finally:
        os.remove(ruta_temporal)

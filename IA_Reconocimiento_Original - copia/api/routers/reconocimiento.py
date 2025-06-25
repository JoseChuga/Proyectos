from fastapi import APIRouter, UploadFile, File, HTTPException
from api.services.model_service import predecir_por_mitad
from api.schemas import ResultadoCuadrantes

router = APIRouter()

@router.post("/", response_model=ResultadoCuadrantes)
async def analizar_imagen(file: UploadFile = File(...)):
    if not any(file.filename.lower().endswith(ext) for ext in [".ppm", ".jpg", ".jpeg", ".png"]):
        raise HTTPException(status_code=400, detail="Formato de imagen no soportado.")
    
    data = await file.read()
    try:
        resultado = predecir_por_mitad(data)
        resultado["dimensiones"] = "512x512"
        return resultado

    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al procesar imagen: {e}")

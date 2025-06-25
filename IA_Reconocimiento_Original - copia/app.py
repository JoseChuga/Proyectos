from fastapi import FastAPI
from api.routers.reconocimiento import router as reconocimiento_router

app = FastAPI(
    title="API Reconocimiento de Alimentos",
    description="Servicio para analizar imágenes .ppm",
    version="1.0.0"
)

# Incluir rutas
app.include_router(reconocimiento_router, prefix="/analizar", tags=["Reconocimiento"])

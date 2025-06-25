from pydantic import BaseModel
from typing import List, Optional, Dict, Union


class DetalleAlimentoResumen(BaseModel):
    alimento: str  # nombre legible
    clave_interna: Optional[str] = None  # nombre interno como 'combo_hamburguesa_papas'
    probabilidad_maxima: float
    coincidencias: int
    calorias_por_item: Optional[Union[float, Dict[str, float]]] = None
    calorias_totales_categoria: Optional[float]
    informacion_nutricional: Optional[Dict] = None


class ResultadoCuadrantes(BaseModel):
    detalles: List[DetalleAlimentoResumen]
    calorias_totales: Optional[float]
    tiempo_total_ms: float

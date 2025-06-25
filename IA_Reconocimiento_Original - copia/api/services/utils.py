# api/services/utils.py

from PIL import Image
import io

def convertir_a_ppm_bytes(imagen_bytes: bytes) -> bytes:
    """
    Convierte una imagen JPG, PNG, etc. a formato PPM (P3) en memoria.
    Retorna los bytes del contenido .ppm.
    """
    imagen = Image.open(io.BytesIO(imagen_bytes)).convert("RGB")
    ancho, alto = imagen.size
    pixeles = list(imagen.getdata())

    ppm_data = f"P3\n{ancho} {alto}\n255\n"
    for r, g, b in pixeles:
        ppm_data += f"{r} {g} {b} "

    return ppm_data.encode('utf-8')

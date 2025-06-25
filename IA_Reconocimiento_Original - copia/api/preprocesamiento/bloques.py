
def dividir_en_bloques(matriz, ancho, alto, tamaño_bloque):
    bloques = []

    for y in range(0, alto, tamaño_bloque):
        for x in range(0, ancho, tamaño_bloque):
            bloque = []
            for dy in range(tamaño_bloque):
                for dx in range(tamaño_bloque):
                    ny = y + dy
                    nx = x + dx
                    if ny < alto and nx < ancho:
                        bloque.append(matriz[ny][nx])
            bloques.append(bloque)

    return bloques

def color_promedio(bloque):
    total_r, total_g, total_b = 0, 0, 0

    for r, g, b in bloque:
        total_r += r
        total_g += g
        total_b += b

    n = len(bloque)

    return (total_r // n, total_g // n, total_b // n)
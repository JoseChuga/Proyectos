def leer_ppm(path):
    with open(path, 'r') as f:
        lines = f.readlines()

    # Elimina comentarios y líneas vacías
    lines = [line.strip() for line in lines if line.strip() and not line.startswith('#')]

    if lines[0] != 'P3':
        raise ValueError("Formato no compatible. Solo se admite P3 (ASCII).")

    # Leer dimensiones
    dimensiones = lines[1].split()
    ancho, alto = int(dimensiones[0]), int(dimensiones[1])

    # Leer valor máximo del color (por lo general 255)
    max_color = int(lines[2])

    # Leer todos los valores RGB en una lista plana
    valores_rgb = list(map(int, ' '.join(lines[3:]).split()))

    # Convertir la lista plana a matriz [alto][ancho] con tuplas RGB
    pixels = []
    idx = 0
    for y in range(alto):
        fila = []
        for x in range(ancho):
            r = valores_rgb[idx]
            g = valores_rgb[idx + 1]
            b = valores_rgb[idx + 2]
            fila.append((r, g, b))
            idx += 3
        pixels.append(fila)

    return pixels, ancho, alto
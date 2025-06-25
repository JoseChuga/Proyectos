from collections import Counter
import matplotlib.pyplot as plt

def detectar_desbalance(y_true, umbral_bajo=0.2, umbral_alto=0.5, plot=True):
    total = len(y_true)
    conteo = Counter(y_true)
    desbalanceadas = []

    print("Distribución de clases:")
    for clase, cantidad in conteo.items():
        proporcion = cantidad / total
        print(f"  Clase '{clase}': {cantidad} muestras ({proporcion:.2%})")
        if proporcion < umbral_bajo or proporcion > umbral_alto:
            desbalanceadas.append((clase, proporcion))

    if desbalanceadas:
        print("\n⚠️ Clases potencialmente desbalanceadas:")
        for clase, proporcion in desbalanceadas:
            print(f"  - '{clase}' con {proporcion:.2%} del total")
    else:
        print("\n✅ No se detectaron desbalances significativos.")

    if plot:
        plt.bar(conteo.keys(), conteo.values(), color='skyblue')
        plt.title("Distribución de clases")
        plt.xlabel("Clases")
        plt.ylabel("Cantidad")
        plt.axhline(y=total * umbral_bajo, color='red', linestyle='--', label='Umbral inferior')
        plt.axhline(y=total * umbral_alto, color='orange', linestyle='--', label='Umbral superior')
        plt.legend()
        plt.show()

# Ejemplo de uso
y_true = ['alitas'] * 113 + ['carne'] * 104 + ['waffles'] * 29  # simulación a partir de la matriz
detectar_desbalance(y_true)

from collections import Counter
import matplotlib.pyplot as plt

# Simula tu dataset completo
y_all = ['alitas'] * 113 + ['carne'] * 104 + ['waffles'] * 29 + ['donas'] * 10 + ['tacos'] * 15 + \
        ['hamburguesas'] * 12 + ['frijoles'] * 8 + ['Hot Dogs'] * 14 + ['papas fritas'] * 9 + \
        ['pizza'] * 10 + ['pollo frito'] * 13 + ['taquito'] * 11

# Contar las ocurrencias de cada clase
conteo = Counter(y_all)

# Graficar la distribución
plt.figure(figsize=(12,6))
plt.bar(conteo.keys(), conteo.values(), color='skyblue')
plt.title("Distribución completa de clases")
plt.xlabel("Clases")
plt.ylabel("Cantidad de muestras")
plt.xticks(rotation=45)
plt.tight_layout()
plt.show()

import numpy as np
import matplotlib.pyplot as plt
from sklearn.decomposition import PCA
from sklearn.metrics import confusion_matrix, classification_report
import seaborn as sns
from tensorflow.keras.models import load_model
from data_preparation import cargar_datos_desde_csv

# Cargar modelo y datos
modelo = load_model("modelo_entrenado.h5")
(_, X_test, _, y_test), encoder = cargar_datos_desde_csv("features.csv")

# Verificar formas
print(f"✔ Etiquetas de prueba (y_test): {y_test[:10]}")
print(f"✔ Forma de X_test: {X_test.shape}")
print(f"✔ Forma de y_test: {y_test.shape}")

# Evaluación del modelo
loss, acc = modelo.evaluate(X_test, y_test)
print(f"✅ Precisión en datos de prueba: {acc:.2%}")

# Predicciones
y_pred_probs = modelo.predict(X_test)
y_pred = np.argmax(y_pred_probs, axis=1)

# Matriz de confusión
cm = confusion_matrix(y_test, y_pred)
plt.figure(figsize=(10, 8))
sns.heatmap(cm, annot=True, fmt='d', cmap='Blues',
            xticklabels=encoder.classes_,
            yticklabels=encoder.classes_)
plt.title("Matriz de Confusión")
plt.xlabel("Predicción")
plt.ylabel("Real")
plt.xticks(rotation=45)
plt.yticks(rotation=0)
plt.tight_layout()
plt.show()

# Reporte de clasificación
print("\n📊 Reporte de Clasificación:\n")
print(classification_report(y_test, y_pred, target_names=encoder.classes_))

# Visualización 3D con PCA
pca = PCA(n_components=3)
X_reducido = pca.fit_transform(X_test)

fig = plt.figure(figsize=(10, 6))
ax = fig.add_subplot(111, projection='3d')

scatter = ax.scatter(X_reducido[:, 0], X_reducido[:, 1], X_reducido[:, 2],
                     c=y_pred, cmap='tab20', s=30)

legend_labels = encoder.classes_
legend_handles = [plt.Line2D([0], [0], marker='o', color='w',
                             markerfacecolor=plt.cm.tab20(i / len(legend_labels)),
                             label=label, markersize=8)
                  for i, label in enumerate(legend_labels)]

ax.legend(handles=legend_handles, bbox_to_anchor=(1.05, 1), loc='upper left')
ax.set_title("Visualización 3D de Clasificaciones")
ax.set_xlabel("Componente 1")
ax.set_ylabel("Componente 2")
ax.set_zlabel("Componente 3")
plt.tight_layout()
plt.show()

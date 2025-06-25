import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.metrics import confusion_matrix, classification_report
from tensorflow.keras.models import load_model
from data_preparation import cargar_datos_desde_csv

CSV_PATH = "features.csv"
MODEL_PATH = "modelo_entrenado.h5"

def mostrar_matriz_confusion(modelo, X_test, y_test, encoder):
    y_pred = modelo.predict(X_test)
    y_pred_classes = np.argmax(y_pred, axis=1)

    cm = confusion_matrix(y_test, y_pred_classes)
    etiquetas = encoder.classes_

    plt.figure(figsize=(12, 8))
    sns.heatmap(cm, annot=True, fmt='d', xticklabels=etiquetas, yticklabels=etiquetas, cmap='Blues')
    plt.xlabel("Predicción")
    plt.ylabel("Real")
    plt.title("Matriz de Confusión")
    plt.tight_layout()
    plt.show()

    print("\n📋 Reporte de clasificación:")
    print(classification_report(y_test, y_pred_classes, target_names=etiquetas))


if __name__ == "__main__":
    (X_train, X_test, y_train, y_test), encoder = cargar_datos_desde_csv(CSV_PATH)
    modelo = load_model(MODEL_PATH)
    mostrar_matriz_confusion(modelo, X_test, y_test, encoder)

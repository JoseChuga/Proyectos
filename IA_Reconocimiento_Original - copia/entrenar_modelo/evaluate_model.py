from tensorflow.keras.models import load_model
from data_preparation import cargar_datos_desde_csv
import numpy as np

try:
    model = load_model("modelo_entrenado.h5")
except OSError:
    print("❌ Error: No se pudo cargar el modelo. Verifica la ruta.")
    exit(1)

(_, X_test, _, y_test), _ = cargar_datos_desde_csv("features.csv")

y_test = y_test.reshape(-1)

print("📊 Evaluando modelo...")
loss, acc = model.evaluate(X_test, y_test)
print(f"✅ Precisión en datos de prueba: {acc:.2%}")

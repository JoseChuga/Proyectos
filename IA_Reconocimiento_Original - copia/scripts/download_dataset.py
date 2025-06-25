import os
import requests
from tqdm import tqdm

# Configuración
DATASET_DIR = "dataset"
URLS_FILE = "urls.txt"
HEADERS = {
    "User-Agent": "Mozilla/5.0 (compatible; DatasetDownloader/1.0)"
}

def create_folder(folder_path):
    os.makedirs(folder_path, exist_ok=True)

def download_image(url, save_path):
    try:
        response = requests.get(url, headers=HEADERS, timeout=10)
        response.raise_for_status()

        with open(save_path, "wb") as f:
            f.write(response.content)
        return True
    except Exception as e:
        print(f"[ERROR] No se pudo descargar {url}. Motivo: {e}")
        return False

def read_urls(file_path):
    with open(file_path, "r", encoding="utf-8") as file:
        lines = file.readlines()
    
    dataset = {}
    current_category = None

    for line in lines:
        line = line.strip()
        if not line:
            continue
        if line.startswith("#"):
            current_category = line[1:].strip()
            dataset[current_category] = []
        elif current_category:
            dataset[current_category].append(line)
    return dataset

def main():
    dataset = read_urls(URLS_FILE)

    for category, urls in dataset.items():
        print(f"\n[INFO] Descargando imágenes de la categoría: {category}")
        category_path = os.path.join(DATASET_DIR, category)
        create_folder(category_path)

        for idx, url in enumerate(tqdm(urls, desc=f"Descargando {category}", unit="img")):
            extension = os.path.splitext(url)[-1].lower()
            if extension not in [".jpg", ".jpeg", ".png"]:
                extension = ".jpg"
            image_path = os.path.join(category_path, f"{category}_{idx+1}{extension}")
            download_image(url, image_path)

    print("\n✅ Descarga finalizada.")

if __name__ == "__main__":
    main()

import sys
import json
import os
import base64
import joblib
import pandas as pd
import warnings

warnings.filterwarnings('ignore')

try:
    # 1. Ambil data Base64
    if len(sys.argv) < 2:
        raise Exception("Tidak ada argumen Base64 yang dikirim.")

    input_base64 = sys.argv[1].strip()
    
    # Tambah padding jika kurang
    missing_padding = len(input_base64) % 4
    if missing_padding:
        input_base64 += '=' * (4 - missing_padding)

    # 2. Decode Base64 ke JSON
    json_bytes = base64.b64decode(input_base64)
    json_str = json_bytes.decode('utf-8', errors='ignore').strip()

    if json_str.startswith("{'") or "' :" in json_str or "':" in json_str:
        json_str = json_str.replace("'", '"')

    data_input = json.loads(json_str)
    
    # 3. Urutan Fitur ML
    feature_order = [
        'jenis_komoditas',
        'ras_varian',
        'jenis_kelamin',
        'bobot_kg',
        'tinggi_badan_cm',
        'bcs',
        'skor_kesehatan',
        'kualitas_pakan',
        'status_vaksinasi'
    ]
    
    df_input = pd.DataFrame([data_input])[feature_order]

    # 4. Path Absolut File Model (.pkl)
    base_dir = os.path.dirname(os.path.realpath(__file__))
    path_kelayakan = os.path.join(base_dir, 'model_kelayakan.pkl')
    path_harga = os.path.join(base_dir, 'model_harga.pkl')

    if not os.path.exists(path_kelayakan):
        raise Exception(f"File model kelayakan tidak ditemukan di: {path_kelayakan}")
    if not os.path.exists(path_harga):
        raise Exception(f"File model harga tidak ditemukan di: {path_harga}")

    
    clf_kelayakan = joblib.load(path_kelayakan)
    reg_harga = joblib.load(path_harga)

    pred_kelayakan = int(clf_kelayakan.predict(df_input)[0])
    pred_harga = float(reg_harga.predict(df_input)[0])

    response = {
        'status': 'success',
        'is_layak': pred_kelayakan,
        'kelayakan': pred_kelayakan,
        'harga_jual': round(pred_harga, 2)
    }
    print(json.dumps(response))

except Exception as e:
    print(json.dumps({
        'status': 'error',
        'message': str(e)
    }))
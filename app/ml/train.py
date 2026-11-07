# ==============================================================================
# TAHAP 1: IMPORT LIBRARY
# Mengimpor modul dasar sistem (os), pengolahan data (pandas), 
# penyimpan model (joblib), serta algoritma & metrik evaluasi dari scikit-learn.
# ==============================================================================
import os
import pandas as pd
import joblib
from sklearn.ensemble import RandomForestClassifier, RandomForestRegressor
from sklearn.metrics import accuracy_score, mean_absolute_error, r2_score
from sklearn.model_selection import train_test_split


# Menentukan lokasi file Excel 
base_dir = os.path.dirname(__file__)
path_latih = os.path.join(base_dir, 'Datalatih.xlsx')
path_uji = os.path.join(base_dir, 'Datauji.xlsx')

print("1. Membaca file data latih dan data uji...")
df_train = pd.read_excel(path_latih)


# TAHAP 3: PREPROCESSING & CLEANING RINGAN
df_train.columns = df_train.columns.astype(str)
df_train = df_train.loc[:, ~df_train.columns.str.contains('^Unnamed')]

# Menentukan nama kolom target yang ingin diprediksi
target_kelayakan = 'status_kelayakan'
target_harga = 'harga_jual'

# Validasi keamanan: Memastikan kolom target benar-benar ada di dalam file Excel
if target_kelayakan not in df_train.columns or target_harga not in df_train.columns:
    print(f"\n[ERROR] Kolom '{target_kelayakan}' atau '{target_harga}' tidak ditemukan di Datalatih.xlsx!")
    print("Daftar kolom yang ada saat ini:", df_train.columns.tolist())
    exit()


# TAHAP 4: SELEKSI FITUR (X) DAN TARGET (Y)
y_kelayakan = df_train[target_kelayakan]
y_harga = df_train[target_harga]

# Memisahkan Fitur Mentah X (Membuang kolom target dari input)
X_raw = df_train.drop(columns=[target_kelayakan, target_harga])

# Mengambil hanya atribut yang bertipe numerik (angka) untuk diproses algoritma
X_numeric = X_raw.select_dtypes(include=['int64', 'float64', 'int32', 'float32', 'number'])

print(f"   Jumlah fitur numerik yang digunakan: {X_numeric.shape[1]} kolom")
print(f"   Kolom fitur: {list(X_numeric.columns)}")


# TAHAP 5: PEMBAGIAN DATASET (DATA SPLITTING)
# Mengecek ketersediaan Datauji.xlsx. Jika tidak ada/lengkap, otomatis melakukan
# Train-Test Split sebesar 80% Data Latih : 20% Data Uji.

use_separate_test = False
if os.path.exists(path_uji):
    df_test = pd.read_excel(path_uji)
    df_test.columns = df_test.columns.astype(str)
    df_test = df_test.loc[:, ~df_test.columns.str.contains('^Unnamed')]
    
    if target_kelayakan in df_test.columns and target_harga in df_test.columns:
        use_separate_test = True

if use_separate_test:
    # Menggunakan file Datauji.xlsx jika kolom targetnya lengkap
    print("   Menggunakan Datauji.xlsx untuk pengujian.")
    X_train = X_numeric
    y_train_kelayakan = y_kelayakan
    y_train_harga = y_harga

    X_test_raw = df_test.drop(columns=[target_kelayakan, target_harga])
    X_test = X_test_raw[X_numeric.columns] # Menyelaraskan urutan kolom dengan X_train
    y_test_kelayakan = df_test[target_kelayakan]
    y_test_harga = df_test[target_harga]
else:
    # Membagi dataset otomatis (80:20) dari Datalatih.xlsx
    print("   [INFO] Datauji.xlsx tidak memiliki kolom target lengkap.")
    print("   Membagi Datalatih.xlsx otomatis (80% latih, 20% uji)...")
    
    X_train, X_test, y_train_kelayakan, y_test_kelayakan, y_train_harga, y_test_harga = train_test_split(
        X_numeric, y_kelayakan, y_harga, test_size=0.2, random_state=42
    )

print(f"   Data Latih : {X_train.shape[0]} baris")
print(f"   Data Uji   : {X_test.shape[0]} baris")



# Melatih Random Forest Classifier (100 pohon) lalu menguji nilai akurasinya.
# (Catatan: Bootstrap Sampling & Feature Selection 

print("\n2. Melatih Model Kelayakan Jual (Random Forest Classifier)...")
clf_kelayakan = RandomForestClassifier(n_estimators=100, random_state=42) 
clf_kelayakan.fit(X_train, y_train_kelayakan) # Memulai proses pelatihan model

# Melakukan prediksi pada data uji dan mengukur akurasi
y_pred_kelayakan = clf_kelayakan.predict(X_test)
acc_kelayakan = accuracy_score(y_test_kelayakan, y_pred_kelayakan)

print("--- HASIL EVALUASI KELAYAKAN ---")
print(f"Akurasi Kelayakan: {acc_kelayakan * 100:.2f}%")


# TAHAP 7: PELATIHAN & EVALUASI MODEL REGRESI (HARGA JUAL)
# Melatih Random Forest Regressor (100 pohon) lalu menguji R2 Score & MAE.

print("\n3. Melatih Model Harga Jual (Random Forest Regressor)...")
reg_harga = RandomForestRegressor(n_estimators=100, random_state=42)
reg_harga.fit(X_train, y_train_harga) # Memulai proses pelatihan model regresi

# Melakukan prediksi nominal harga pada data uji dan mengukur eror
y_pred_harga = reg_harga.predict(X_test)
r2_harga = r2_score(y_test_harga, y_pred_harga)
mae_harga = mean_absolute_error(y_test_harga, y_pred_harga)

print("--- HASIL EVALUASI HARGA JUAL ---")
print(f"R2 Score (Akurasi Harga) : {r2_harga:.4f}")
print(f"Mean Absolute Error (MAE): Rp {mae_harga:,.2f}")



# Menyimpan kedua model hasil pelatihan menjadi file .pkl menggunakan joblib

path_model_kelayakan = os.path.join(base_dir, 'model_kelayakan.pkl')
path_model_harga = os.path.join(base_dir, 'model_harga.pkl')

joblib.dump(clf_kelayakan, path_model_kelayakan) # Simpan model klasifikasi
joblib.dump(reg_harga, path_model_harga)         # Simpan model regresi

print("\n=========================================================")
print("BERHASIL! Kedua model tersimpan otomatis di folder app/ml/:")
print(f"1. model_kelayakan.pkl")
print(f"2. model_harga.pkl")
print("=========================================================")
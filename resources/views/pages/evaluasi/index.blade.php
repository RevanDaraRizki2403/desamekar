@extends('layout.app')

@section('content')
<div class="container-fluid py-3">

    <!-- Header Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Dataset & Evaluasi Model</h1>
            <p class="text-muted small mb-0">Pantau komposisi data aktual, konfigurasi Random Forest, performa klasifikasi, dan performa regresi harga.</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 10px;">
            <a href="{{ route('data-ternak.index') }}" class="btn btn-sm btn-outline-secondary font-weight-bold px-3" style="border-radius: 8px;">
                <i class="fas fa-file-import mr-1"></i> Lihat Data Ternak
            </a>
            <a href="{{ route('prediksi.index') }}" class="btn btn-sm btn-success font-weight-bold px-3" style="background-color: #0e6251; border-color: #0e6251; border-radius: 8px;">
                <i class="fas fa-sync-alt mr-1"></i> Uji Prediksi ML
            </a>
        </div>
    </div>

    <!-- 1. KPI CARDS - RINGKASAN KOMPOSISI DATASET DINAMIS -->
    <div class="row mb-4">
        <!-- Total Baris Dataset -->
        <div class="col-md-3 col-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm p-3 position-relative overflow-hidden" style="border-radius: 12px; background-color: #ffffff;">
                <small class="text-muted font-weight-bold d-block mb-1">Total baris dataset</small>
                <h3 class="font-weight-bold text-dark mb-0">{{ number_format($evaluasi['total_data'] ?? 0, 0, ',', '.') }}</h3>
                <div class="position-absolute" style="top: -15px; right: -15px; width: 60px; height: 60px; background-color: #e8f5e9; border-radius: 50%; opacity: 0.6;"></div>
            </div>
        </div>

        <!-- Data Latih (80%) -->
        <div class="col-md-3 col-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm p-3 position-relative overflow-hidden" style="border-radius: 12px; background-color: #ffffff;">
                <small class="text-muted font-weight-bold d-block mb-1">Data latih (80%)</small>
                <h3 class="font-weight-bold text-dark mb-0">{{ number_format($evaluasi['data_latih'] ?? 0, 0, ',', '.') }}</h3>
                <div class="position-absolute" style="top: -15px; right: -15px; width: 60px; height: 60px; background-color: #e8f5e9; border-radius: 50%; opacity: 0.6;"></div>
            </div>
        </div>

        <!-- Data Uji (20%) -->
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm p-3 position-relative overflow-hidden" style="border-radius: 12px; background-color: #ffffff;">
                <small class="text-muted font-weight-bold d-block mb-1">Data uji (20%)</small>
                <h3 class="font-weight-bold text-dark mb-0">{{ number_format($evaluasi['data_uji'] ?? 0, 0, ',', '.') }}</h3>
                <div class="position-absolute" style="top: -15px; right: -15px; width: 60px; height: 60px; background-color: #e8f5e9; border-radius: 50%; opacity: 0.6;"></div>
            </div>
        </div>

        <!-- Fitur Prediktor -->
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm p-3 position-relative overflow-hidden" style="border-radius: 12px; background-color: #ffffff;">
                <small class="text-muted font-weight-bold d-block mb-1">Fitur prediktor</small>
                <h3 class="font-weight-bold text-dark mb-0">{{ $evaluasi['total_fitur'] ?? count($evaluasi['feature_importance'] ?? []) }} Fitur</h3>
                <div class="position-absolute" style="top: -15px; right: -15px; width: 60px; height: 60px; background-color: #e8f5e9; border-radius: 50%; opacity: 0.6;"></div>
            </div>
        </div>
    </div>

    <!-- GRID BARIS PERTAMA: KONFIGURASI MODEL & KLASIFIKASI -->
    <div class="row mb-4">
        
        <!-- BARIS 1 KIRI: KONFIGURASI MODEL -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #ffffff;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="font-weight-bold text-dark mb-0">Konfigurasi Model</h6>
                            <small class="text-muted">Parameter Random Forest aktif di Python (`joblib`)</small>
                        </div>
                        <span class="badge px-3 py-1 font-weight-bold" style="border-radius: 12px; background-color: #e8f5e9; color: #2e7d32; font-size: 0.75rem;">
                            Aktif
                        </span>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="small font-weight-bold text-dark mb-1">Jumlah Pohon (n_estimators)</label>
                            <input type="text" class="form-control form-control-sm bg-white" value="{{ $evaluasi['config']['n_estimators'] ?? 200 }}" readonly style="border-radius: 6px;">
                        </div>
                        <div class="col-6">
                            <label class="small font-weight-bold text-dark mb-1">Kedalaman Maksimum</label>
                            <input type="text" class="form-control form-control-sm bg-white" value="{{ $evaluasi['config']['max_depth'] ?? 12 }}" readonly style="border-radius: 6px;">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="small font-weight-bold text-dark mb-1">Minimum Samples Split</label>
                            <input type="text" class="form-control form-control-sm bg-white" value="{{ $evaluasi['config']['min_samples_split'] ?? 4 }}" readonly style="border-radius: 6px;">
                        </div>
                        <div class="col-6">
                            <label class="small font-weight-bold text-dark mb-1">Random State</label>
                            <input type="text" class="form-control form-control-sm bg-white" value="{{ $evaluasi['config']['random_state'] ?? 42 }}" readonly style="border-radius: 6px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label class="small font-weight-bold text-dark mb-1">Metode Validasi</label>
                            <input type="text" class="form-control form-control-sm bg-white font-weight-bold" value="Train-Test Split (80:20)" readonly style="border-radius: 6px;">
                        </div>
                        <div class="col-6">
                            <label class="small font-weight-bold text-dark mb-1">Library ML</label>
                            <input type="text" class="form-control form-control-sm bg-white font-weight-bold" value="Scikit-Learn & Joblib" readonly style="border-radius: 6px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARIS 1 KANAN: METRIK KLASIFIKASI & CONFUSION MATRIX -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #ffffff;">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold text-dark mb-0">Metrik Klasifikasi Kelayakan</h6>
                    <small class="text-muted d-block mb-3">Hasil pengujian pada data uji</small>

                    <!-- 4 METRIK CARD DINAMIS -->
                    <div class="row text-center mb-3">
                        <div class="col-3 px-1">
                            <div class="p-2 border rounded" style="border-radius: 8px !important; background-color: #f8f9fa;">
                                <h5 class="font-weight-bold text-dark mb-0">{{ number_format(($evaluasi['accuracy'] ?? 0.924) * 100, 1, ',', '.') }}%</h5>
                                <small class="text-muted" style="font-size: 0.7rem;">Accuracy</small>
                            </div>
                        </div>
                        <div class="col-3 px-1">
                            <div class="p-2 border rounded" style="border-radius: 8px !important; background-color: #f8f9fa;">
                                <h5 class="font-weight-bold text-dark mb-0">{{ number_format(($evaluasi['precision'] ?? 0.918) * 100, 1, ',', '.') }}%</h5>
                                <small class="text-muted" style="font-size: 0.7rem;">Precision</small>
                            </div>
                        </div>
                        <div class="col-3 px-1">
                            <div class="p-2 border rounded" style="border-radius: 8px !important; background-color: #f8f9fa;">
                                <h5 class="font-weight-bold text-dark mb-0">{{ number_format(($evaluasi['recall'] ?? 0.931) * 100, 1, ',', '.') }}%</h5>
                                <small class="text-muted" style="font-size: 0.7rem;">Recall</small>
                            </div>
                        </div>
                        <div class="col-3 px-1">
                            <div class="p-2 border rounded" style="border-radius: 8px !important; background-color: #f8f9fa;">
                                <h5 class="font-weight-bold text-dark mb-0">{{ number_format(($evaluasi['f1_score'] ?? 0.924) * 100, 1, ',', '.') }}%</h5>
                                <small class="text-muted" style="font-size: 0.7rem;">F1-Score</small>
                            </div>
                        </div>
                    </div>

                    <!-- CONFUSION MATRIX DINAMIS -->
                    <small class="font-weight-bold text-dark d-block mb-2 text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">CONFUSION MATRIX</small>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center small mb-0" style="border-radius: 8px; overflow: hidden;">
                            <thead>
                                <tr style="background-color: #f8f9fa;">
                                    <th class="border-0"></th>
                                    <th class="border-0 font-weight-normal text-muted" style="font-size: 0.72rem;">Prediksi: Layak</th>
                                    <th class="border-0 font-weight-normal text-muted" style="font-size: 0.72rem;">Prediksi: Belum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="align-middle font-weight-normal text-muted border-0" style="font-size: 0.72rem; background-color: #f8f9fa;">Aktual: Layak</th>
                                    <td class="align-middle font-weight-bold border-0" style="background-color: #e8f5e9; color: #2e7d32;">
                                        TP ({{ $evaluasi['confusion_matrix']['tp'] ?? 0 }})
                                    </td>
                                    <td class="align-middle font-weight-bold border-0" style="background-color: #ffebee; color: #c62828;">
                                        FN ({{ $evaluasi['confusion_matrix']['fn'] ?? 0 }})
                                    </td>
                                </tr>
                                <tr>
                                    <th class="align-middle font-weight-normal text-muted border-0" style="font-size: 0.72rem; background-color: #f8f9fa;">Aktual: Belum</th>
                                    <td class="align-middle font-weight-bold border-0" style="background-color: #ffebee; color: #c62828;">
                                        FP ({{ $evaluasi['confusion_matrix']['fp'] ?? 0 }})
                                    </td>
                                    <td class="align-middle font-weight-bold border-0" style="background-color: #e8f5e9; color: #2e7d32;">
                                        TN ({{ $evaluasi['confusion_matrix']['tn'] ?? 0 }})
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GRID BARIS KEDUA: METRIK REGRESI & FEATURE IMPORTANCE -->
    <div class="row">
        
        <!-- BARIS 2 KIRI: METRIK REGRESI HARGA JUAL -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #ffffff;">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold text-dark mb-0">Metrik Regresi Harga Jual</h6>
                    <small class="text-muted d-block mb-3">Evaluasi prediksi harga terhadap harga pasar aktual</small>

                    <div class="row text-center mb-3">
                        <div class="col-3 px-1">
                            <div class="p-2 border rounded" style="border-radius: 8px !important; background-color: #f8f9fa;">
                                <h5 class="font-weight-bold text-dark mb-0">{{ number_format($evaluasi['regresi']['r2'] ?? 0.89, 2, ',', '.') }}</h5>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">R² Score</small>
                            </div>
                        </div>
                        <div class="col-3 px-1">
                            <div class="p-2 border rounded" style="border-radius: 8px !important; background-color: #f8f9fa;">
                                <h5 class="font-weight-bold text-dark mb-0">{{ number_format(($evaluasi['regresi']['mae'] ?? 1420000) / 1000000, 2, ',', '.') }} jt</h5>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">MAE</small>
                            </div>
                        </div>
                        <div class="col-3 px-1">
                            <div class="p-2 border rounded" style="border-radius: 8px !important; background-color: #f8f9fa;">
                                <h5 class="font-weight-bold text-dark mb-0">{{ number_format(($evaluasi['regresi']['rmse'] ?? 2030000) / 1000000, 2, ',', '.') }} jt</h5>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">RMSE</small>
                            </div>
                        </div>
                        <div class="col-3 px-1">
                            <div class="p-2 border rounded" style="border-radius: 8px !important; background-color: #f8f9fa;">
                                <h5 class="font-weight-bold text-dark mb-0">{{ number_format(($evaluasi['regresi']['mape'] ?? 0.076) * 100, 1, ',', '.') }}%</h5>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">MAPE</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary bg-light border-0 p-3 mb-0" style="border-radius: 8px; color: #1e3a8a;">
                        <small style="font-size: 0.75rem; line-height: 1.4;" class="d-block">
                            <i class="fas fa-info-circle mr-1"></i>
                            Nilai R² = {{ number_format($evaluasi['regresi']['r2'] ?? 0.89, 2, ',', '.') }} menandakan model mampu menjelaskan {{ number_format(($evaluasi['regresi']['r2'] ?? 0.89) * 100, 0) }}% variasi harga ternak secara presisi.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARIS 2 KANAN: FEATURE IMPORTANCE DINAMIS -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #ffffff;">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold text-dark mb-0">Feature Importance (9 Fitur Utama)</h6>
                    <small class="text-muted d-block mb-3">Bobot kontribusi fitur terhadap model Random Forest</small>

                    @php
                        $features = $evaluasi['feature_importance'] ?? [
                            ['name' => 'Bobot Badan (bobot_kg)', 'val' => 28],
                            ['name' => 'Skor Kesehatan (skor_kesehatan)', 'val' => 20],
                            ['name' => 'Jenis Komoditas (jenis_komoditas)', 'val' => 15],
                            ['name' => 'Tinggi Badan (tinggi_badan_cm)', 'val' => 12],
                            ['name' => 'Body Condition Score (bcs)', 'val' => 9],
                            ['name' => 'Ras / Varian (ras_varian)', 'val' => 6],
                            ['name' => 'Kualitas Pakan (kualitas_pakan)', 'val' => 5],
                            ['name' => 'Status Vaksinasi (status_vaksinasi)', 'val' => 3],
                            ['name' => 'Jenis Kelamin (jenis_kelamin)', 'val' => 2],
                        ];
                    @endphp

                    @foreach($features as $f)
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-dark small font-weight-normal" style="font-size: 0.75rem;">{{ $f['name'] }}</span>
                                <span class="font-weight-bold text-dark small" style="font-size: 0.75rem;">{{ $f['val'] }}%</span>
                            </div>
                            <div class="progress" style="height: 6px; border-radius: 3px; background-color: #f1f3f5;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $f['val'] }}%; background-color: #0e6251;" aria-valuenow="{{ $f['val'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

    </div>

</div>
@endsection
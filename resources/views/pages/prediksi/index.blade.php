@extends('layout.app')

@section('content')
<div class="container-fluid py-3">

    <!-- Header Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Prediksi Kelayakan & Harga Jual</h1>
            <p class="text-muted small mb-0">Analisis prediksi kelayakan ternak dan estimasi harga jual berbasis algoritma Random Forest.</p>
        </div>
        <a href="{{ route('data-ternak.index') }}" class="btn btn-sm btn-outline-secondary font-weight-bold px-3" style="border-radius: 8px;">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Tabel Ternak
        </a>
    </div>

    <!-- PANEL PILIH HEWAN TERNAK -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #ffffff;">
        <div class="card-body p-3">
            <form action="{{ route('prediksi.proses') }}" method="POST" class="row align-items-center">
                @csrf
                <div class="col-md-9 mb-2 mb-md-0">
                    <label class="font-weight-bold text-dark mb-1 small">Pilih ID Ternak yang Dianalisis:</label>
                    <select name="selected_id" class="form-control font-weight-bold" onchange="this.form.submit()" style="border-radius: 8px;">
                        @forelse($allTernak ?? [] as $t)
                            @php 
                                $jenisLower = strtolower(trim($t->jenis ?? ''));
                                $isSapi = ($jenisLower === 'sapi' || $jenisLower === '0' || str_contains($jenisLower, 'sapi'));
                                $isSelected = isset($selectedTernak) && $selectedTernak ? ($selectedTernak->id == $t->id) : $loop->first;
                            @endphp
                            <option value="{{ $t->id }}" {{ $isSelected ? 'selected' : '' }}>
                                {{ $t->kode_ternak ?? 'TRK-'.sprintf('%03d', $t->id) }} | {{ $isSapi ? 'Sapi' : 'Domba' }} - {{ $t->ras ?? '-' }} ({{ number_format($t->berat ?? 0, 0, ',', '.') }} kg)
                            </option>
                        @empty
                            <option value="">Belum ada data ternak di sistem</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-3 text-md-right mt-2 mt-md-0">
                    <button type="submit" class="btn btn-primary font-weight-bold btn-block" style="border-radius: 8px; background-color: #0e6251; border-color: #0e6251;">
                        <i class="fas fa-sync-alt mr-1"></i> Muat Analisis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- KARTU HASIL ANALISIS MODEL -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background-color: #ffffff;">
        <div class="card-body p-4">
            
            <!-- Top Header Card -->
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="font-weight-bold text-dark mb-1">Hasil Analisis Model</h5>
                    <small class="text-muted">RF-v1.4 • {{ \Carbon\Carbon::now()->format('d M Y, H.i') }}</small>
                </div>
                <span class="badge badge-light text-primary font-weight-bold px-3 py-2" style="border-radius: 20px; background-color: #eef2ff;">
                    Random Forest
                </span>
            </div>

            @php 
                $isLayak = ($hasilPrediksi['kelayakan'] ?? 0) == 1; 
                $persentase = $hasilPrediksi['persentase'] ?? 0;
                $hargaJual = $hasilPrediksi['harga_jual'] ?? 0;
                $batasBawah = $hasilPrediksi['batas_bawah'] ?? 0;
                $batasAtas = $hasilPrediksi['batas_atas'] ?? 0;
            @endphp

            <!-- Visualisasi Donut Chart & Skor Persentase -->
            <div class="text-center my-4">
                <div class="position-relative d-inline-block" style="width: 140px; height: 140px;">
                    <svg width="140" height="140" viewBox="0 0 36 36" class="circular-chart">
                        <path class="circle-bg"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            fill="none" stroke="#e9ecef" stroke-width="3" />
                        <path class="circle"
                            stroke-dasharray="{{ $persentase }}, 100"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            fill="none" stroke="{{ $isLayak ? '#0e6251' : '#dc3545' }}" stroke-width="3.5" stroke-linecap="round" />
                    </svg>
                    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0;">
                        <h2 class="font-weight-bold text-dark mb-0">{{ $persentase }}%</h2>
                    </div>
                </div>

                <div class="mt-2">
                    <span class="badge px-3 py-2 font-weight-bold" style="border-radius: 20px; background-color: {{ $isLayak ? '#d1e7dd' : '#f8d7da' }}; color: {{ $isLayak ? '#0f5132' : '#842029' }}; font-size: 0.85rem;">
                        <i class="fas fa-circle mr-1" style="font-size: 0.6rem;"></i> {{ $isLayak ? 'Layak Dijual' : 'Belum Layak' }}
                    </span>
                </div>

                <h1 class="font-weight-bold my-2" style="color: {{ $isLayak ? '#0e6251' : '#dc3545' }}; font-size: 2.2rem;">
                    Rp {{ number_format($hargaJual, 0, ',', '.') }}
                </h1>
                <small class="text-muted d-block">Estimasi harga jual yang disarankan</small>
            </div>

            <!-- Rentang Harga (Batas Bawah, Rekomendasi, Batas Atas) -->
            <div class="row text-center mb-4">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="p-3 border rounded-lg bg-light" style="border-radius: 12px !important;">
                        <h6 class="font-weight-bold text-dark mb-1">Rp {{ number_format($batasBawah, 0, ',', '.') }}</h6>
                        <small class="text-muted">Batas bawah</small>
                    </div>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="p-3 border rounded-lg bg-light" style="border-radius: 12px !important;">
                        <h6 class="font-weight-bold text-dark mb-1">Rp {{ number_format($hargaJual, 0, ',', '.') }}</h6>
                        <small class="text-muted">Harga rekomendasi</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-lg bg-light" style="border-radius: 12px !important;">
                        <h6 class="font-weight-bold text-dark mb-1">Rp {{ number_format($batasAtas, 0, ',', '.') }}</h6>
                        <small class="text-muted">Batas atas</small>
                    </div>
                </div>
            </div>

            <!-- Voting 10 Pohon Sampel (SESUAI DESAIN GAMBAR CONTOH) -->
            <div class="mb-4">
                <small class="font-weight-bold text-dark d-block mb-2" style="font-size: 0.85rem;">Voting 10 pohon sampel</small>
                <div class="d-flex flex-wrap" style="gap: 10px;">
                    @php
                        $votes = $hasilPrediksi['voting_pohon'] ?? ($isLayak ? [1, 1, 1, 1, 1, 1, 1, 1, 1, 0] : [0, 0, 0, 1, 0, 0, 0, 0, 1, 0]);
                    @endphp
                    @foreach($votes as $vote)
                        <div class="d-flex align-items-center justify-content-center rounded" 
                             style="flex: 1; min-width: 45px; height: 50px; border-radius: 10px !important; background-color: {{ $vote ? '#e8f5e9' : '#ffebee' }}; color: {{ $vote ? '#2e7d32' : '#c62828' }};">
                            <i class="fas {{ $vote ? 'fa-check' : 'fa-times' }}" style="font-size: 1rem;"></i>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Alert Box Rekomendasi Sistem Dinamis -->
            <div class="alert {{ $isLayak ? 'alert-success' : 'alert-warning' }} border-0 p-3 mb-4" role="alert" style="border-radius: 10px; background-color: {{ $isLayak ? '#e8f5e9' : '#fff3cd' }}; color: {{ $isLayak ? '#1b5e20' : '#664d03' }};">
                <small class="d-block" style="line-height: 1.5;">
                    @if($isLayak)
                        <strong>Rekomendasi:</strong> Hewan memenuhi ambang kelayakan jual. Harga transaksi disarankan berada pada rentang 
                        <strong>Rp {{ number_format($batasBawah, 0, ',', '.') }}</strong> sampai 
                        <strong>Rp {{ number_format($batasAtas, 0, ',', '.') }}</strong>. 
                        Lakukan pemeriksaan kesehatan akhir dan konfirmasi harga pasar sebelum transaksi.
                    @else
                        <strong>Rekomendasi:</strong> Hewan <strong>belum memenuhi</strong> ambang kelayakan jual. Disarankan untuk meningkatkan perawatan nutrisi pakan, menaikkan bobot badan (minimal 400 kg untuk Sapi / 40 kg untuk Domba), dan melengkapi status vaksinasi serta skor kesehatan sebelum dijual.
                    @endif
                </small>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-end align-items-center">
                <a href="{{ route('data-ternak.index') }}" class="btn btn-success font-weight-bold px-4" style="background-color: #0e6251; border-color: #0e6251; border-radius: 8px;">
                    <i class="fas fa-check-circle mr-1"></i> Selesai & Kembali
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
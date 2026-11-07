@extends('layout.app')

@section('content')
<div class="container-fluid">

    <!-- Title Halaman -->
    <div class="mb-4">
        <h1 class="h3 text-gray-800 font-weight-bold">Dashboard Utama</h1>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="row">

        <!-- Total Ternak -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Populasi Ternak</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTernak ?? $totalPopulasi ?? 0 }} Ekor</div>
                </div>
            </div>
        </div>

        <!-- Ternak Layak Jual -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ternak Layak Jual</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLayak ?? $layakJual ?? 0 }} Ekor</div>
                </div>
            </div>
        </div>

        <!-- Komoditas Ternak -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Komoditas Ternak</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $totalSapi ?? 0 }} Sapi | {{ $totalDomba ?? 0 }} Domba
                    </div>
                </div>
            </div>
        </div>

        <!-- Estimasi Nilai Aset -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Estimasi Nilai Aset</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        Rp {{ number_format($totalAset ?? $nilaiAset ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Tabel Data Ternak Terbaru -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Data Ternak Terbaru</h6>
            <a href="{{ route('data-ternak.index') }}" class="text-primary small font-weight-bold">Lihat Semua Data &rarr;</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                    <thead class="thead-light">
                        <tr>
                            <th>ID Ternak</th>
                            <th>Komoditas</th>
                            <th>Ras / Varian</th>
                            <th>Berat Panen</th>
                            <th>Status Kelayakan</th>
                            <th>Estimasi Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $dataList = $latestTernak ?? $ternaksTerbaru ?? [];
                        @endphp

                        @forelse($dataList as $item)
                        @php
                            // Cek Status Kelayakan
                            $isLayak = ($item->kelayakan ?? '') == 'Layak' || ($item->kelayakan ?? 0) == 1;

                            // Cek Jenis Komoditas
                            $jenis = $item->jenis ?? $item->jenis_komoditas ?? 0;
                            $isSapi = ($jenis == 0 || $jenis === '0' || strtolower($jenis) === 'sapi');
                        @endphp
                        <tr>
                            <!-- ID Ternak -->
                            <td class="font-weight-bold text-primary">
                                {{ $item->kode_ternak ?? 'TRK-'.sprintf('%03d', $item->id) }}
                            </td>

                            <!-- Komoditas Teks -->
                            <td>{{ $isSapi ? 'Sapi' : 'Domba' }}</td>

                            <!-- Ras / Varian -->
                            <td>{{ $item->ras ?? $item->ras_varian ?? '-' }}</td>

                            <!-- Berat Panen -->
                            <td>{{ $item->berat ?? $item->berat_panen ?? 0 }} kg</td>

                            <!-- Status Kelayakan -->
                            <td>
                                @if($isLayak)
                                    <span class="badge badge-success px-2 py-1">LAYAK JUAL</span>
                                @else
                                    <span class="badge badge-danger px-2 py-1">BELUM LAYAK</span>
                                @endif
                            </td>

                            <!-- Estimasi Harga (Kondisi Belum Layak = Strip) -->
                            <td class="font-weight-bold">
                                @if($isLayak && ($item->harga_jual ?? 0) > 0)
                                    <span class="text-success">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data ternak terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
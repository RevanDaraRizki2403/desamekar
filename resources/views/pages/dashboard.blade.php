@extends('layout.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h2 mb-0 text-gray-800 font-weight-bold">
        <i class="fas fa-tachometer-alt mr-2 text-primary"></i>
        Dashboard {{ auth()->user()->role_id == 1 ? 'Administrator' : 'Warga Desa' }}
    </h1>
</div>

@if(auth()->user()->role_id == 1)
    {{-- ============================ DASHBOARD ADMIN (DIREVISI) ============================ --}}
    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 px-3">
                            <div class="text-sm font-weight-bold text-primary text-uppercase mb-2">Total Penduduk Terdaftar</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $total_penduduk }}</div>
                        </div>
                        <div class="col-auto pr-3">
                            <i class="fas fa-users fa-4x text-gray-200"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 px-3">
                            <div class="text-sm font-weight-bold text-success text-uppercase mb-2">Aduan Warga Selesai</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $aduan_selesai }}</div>
                        </div>
                        <div class="col-auto pr-3">
                            <i class="fas fa-check-circle fa-4x text-gray-200"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h5 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>Aduan Warga Terbaru
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0" style="font-size: 1.1rem;">
                    <thead class="thead-light font-weight-bold">
                        <tr>
                            <th width="25%">Pelapor</th>
                            <th>Isi Aduan</th>
                            <th width="15%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_aduan as $aduan)
                        <tr>
                            <td class="font-weight-bold">{{ $aduan->resident->nama ?? 'Anonim' }}</td>
                            <td>{{ Str::limit($aduan->description, 80) }}</td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-warning px-3 py-2 text-uppercase">{{ $aduan->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-5">Belum ada aduan masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@else
    {{-- ============================ DASHBOARD WARGA (DIREVISI) ============================ --}}
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card bg-gradient-primary shadow h-100 py-3 border-0">
                <div class="card-body px-4 py-4 text-white">
                    <h2 class="font-weight-bold">Selamat Datang, {{ auth()->user()->name }}!</h2>
                    <p class="lead mb-0 text-white-50">Pantau status layanan dan pengaduan Anda secara langsung dari sini.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-bottom-info shadow h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-sm font-weight-bold text-info text-uppercase mb-2">Pengajuan Saya</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $total_pengajuan_saya ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-bottom-danger shadow h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-sm font-weight-bold text-danger text-uppercase mb-2">Aduan Anda</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $total_aduan_saya ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullhorn fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-bottom-success shadow h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-sm font-weight-bold text-success text-uppercase mb-2">Layanan Selesai</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $layanan_selesai ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-5">
                <div class="card-header py-4 d-flex flex-row align-items-center justify-content-between bg-white border-bottom">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-history mr-2"></i>Status Pengajuan Terakhir</h5>
                    <a href="/Ajuan" class="btn btn-primary px-4 shadow-sm">Lihat Semua Data</a>
                </div>
                <div class="card-body px-0"> {{-- px-0 agar tabel mengisi lebar penuh --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0" style="font-size: 1.1rem;">
                            <thead class="bg-light">
                                <tr class="py-3">
                                    <th class="px-4">Jenis Dokumen</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th class="text-center">Status Layanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Ganti dengan data asli nanti --}}
                                <tr class="py-3">
                                    <td class="px-4 font-weight-bold">Surat Keterangan Tidak Mampu (SKTM)</td>
                                    <td>28 Januari 2026</td>
                                    <td class="text-center">
                                        <span class="badge badge-warning px-4 py-2 text-uppercase" style="font-size: 0.9rem;">Diproses</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
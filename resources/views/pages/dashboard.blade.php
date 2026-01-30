@extends('layout.app')

@section('content')
{{-- Header Dashboard yang Lebih Ringkas --}}
<div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0 text-gray-800 font-weight-bold">
        <i class="fas fa-tachometer-alt mr-2 text-primary"></i>
        Dashboard {{ auth()->user()->role_id == 1 ? 'Administrator' : 'Warga Desa' }}
    </h1>
</div>

@if(auth()->user()->role_id == 1)
    {{-- ============================ DASHBOARD ADMIN (OPTIMAL) ============================ --}}
    <div class="row">
        <div class="col-xl-6 col-md-6 mb-3">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body py-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 px-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Penduduk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_penduduk }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-3">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body py-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 px-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aduan Selesai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $aduan_selesai }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>Aduan Warga Terbaru
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                    <thead class="bg-light text-gray-700">
                        <tr>
                            <th class="px-4 border-0">Pelapor</th>
                            <th class="border-0">Isi Aduan</th>
                            <th class="text-center border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_aduan as $aduan)
                        <tr>
                            <td class="px-4 font-weight-bold text-gray-800">{{ $aduan->resident->nama ?? 'Anonim' }}</td>
                            <td class="text-muted">{{ Str::limit($aduan->description, 60) }}</td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-warning px-3 py-1 text-uppercase" style="font-size: 0.7rem;">{{ $aduan->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted small">Belum ada aduan masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@else
    {{-- ============================ DASHBOARD WARGA (OPTIMAL) ============================ --}}
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="card bg-gradient-primary shadow-sm border-0">
                <div class="card-body py-3 px-4 text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="font-weight-bold mb-1">Halo, {{ auth()->user()->name }}!</h5>
                            <p class="small mb-0 text-white-50">Pantau status layanan dan pengaduan Anda di sini.</p>
                        </div>
                        <div class="col-auto d-none d-md-block">
                            <i class="fas fa-user-circle fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body py-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 px-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pengajuan Saya</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_pengajuan_saya ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-left-danger shadow-sm h-100 py-2">
                <div class="card-body py-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 px-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Aduan Anda</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_aduan_saya ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 mb-3">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body py-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 px-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Layanan Selesai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $layanan_selesai ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history mr-2"></i>Status Pengajuan Terakhir</h6>
            <a href="/Ajuan" class="btn btn-sm btn-primary px-3 shadow-sm">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                    <thead class="bg-light text-gray-700">
                        <tr>
                            <th class="px-4 border-0">Jenis Dokumen</th>
                            <th class="border-0">Tanggal</th>
                            <th class="text-center border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_pengajuan ?? [] as $item)
                        <tr>
                            <td class="px-4 font-weight-bold text-gray-800">{{ $item->jenis_dokumen }}</td>
                            <td class="text-muted">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-{{ $item->status == 'selesai' ? 'success' : 'warning' }} px-3 py-1 text-uppercase" style="font-size: 0.7rem;">{{ $item->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted small">Belum ada pengajuan terbaru.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
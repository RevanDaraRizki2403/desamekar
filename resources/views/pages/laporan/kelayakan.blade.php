@extends('layout.app')

@section('content')
<div class="container-fluid py-3">

    <!-- Header Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 print-hide">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Laporan Peternakan & Prediksi</h1>
            <p class="text-muted small mb-0">Rekap data populasi, kelayakan, dan estimasi nilai jual untuk kebutuhan pimpinan.</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 10px;">
            <a href="{{ route('laporan.pdf', request()->query()) }}" class="btn btn-sm btn-danger font-weight-bold px-3" style="border-radius: 8px;">
                <i class="fas fa-file-pdf mr-1"></i> Unduh PDF
            </a>
        </div>
    </div>

    <!-- Container Utama -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #ffffff;">
        <div class="card-body p-4">

            <!-- FILTER JENIS TERNAK (INPUT TANGGAL TELAH DIHAPUS) -->
            <form action="{{ Request::url() }}" method="GET" class="row align-items-center mb-4 print-hide">
                <div class="col-md-12 d-flex align-items-center flex-wrap" style="gap: 10px;">
                    <div>
                        <select name="jenis" class="form-control form-control-sm font-weight-bold text-dark" style="border-radius: 8px; height: 38px; min-width: 140px;">
                            <option value="">Semua Jenis</option>
                            <option value="Sapi" {{ strtolower(request('jenis')) == 'sapi' ? 'selected' : '' }}>Sapi</option>
                            <option value="Domba" {{ strtolower(request('jenis')) == 'domba' ? 'selected' : '' }}>Domba</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-success font-weight-bold px-3" style="border-radius: 8px; height: 38px; color: #0e6251; border-color: #0e6251;">
                        Terapkan
                    </button>
                    @if(request()->has('jenis'))
                        <a href="{{ Request::url() }}" class="btn btn-sm btn-link text-danger">Reset Filter</a>
                    @endif
                </div>
            </form>

            <!-- JUDUL DOKUMEN LAPORAN -->
            <div class="text-center my-4">
                <h4 class="font-weight-bold text-dark text-uppercase mb-1" style="letter-spacing: 0.5px;">LAPORAN KONDISI DAN NILAI JUAL HEWAN TERNAK</h4>
                <p class="text-muted small mb-0">PT. SUMBER MAKANAN SEHAT | Periode Berjalan</p>
            </div>

            <!-- RINGKASAN REKAP DATA (4 BOXES) -->
            <div class="row text-center mb-4">
                <div class="col-md-3 col-6 mb-2 mb-md-0">
                    <div class="p-3 border rounded-lg bg-light text-left" style="border-radius: 10px !important;">
                        <small class="text-muted font-weight-bold d-block" style="font-size: 0.7rem;">Total Ternak</small>
                        <h5 class="font-weight-bold text-dark mb-0 mt-1">{{ $totalTernak ?? 0 }} ekor</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2 mb-md-0">
                    <div class="p-3 border rounded-lg bg-light text-left" style="border-radius: 10px !important;">
                        <small class="text-muted font-weight-bold d-block" style="font-size: 0.7rem;">Layak Jual</small>
                        <h5 class="font-weight-bold text-dark mb-0 mt-1">{{ $totalLayak ?? 0 }} ekor</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 border rounded-lg bg-light text-left" style="border-radius: 10px !important;">
                        <small class="text-muted font-weight-bold d-block" style="font-size: 0.7rem;">Belum Layak</small>
                        <h5 class="font-weight-bold text-dark mb-0 mt-1">{{ $totalTidakLayak ?? 0 }} ekor</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 border rounded-lg bg-light text-left" style="border-radius: 10px !important;">
                        <small class="text-muted font-weight-bold d-block" style="font-size: 0.7rem;">Estimasi Nilai Total</small>
                        <h5 class="font-weight-bold text-dark mb-0 mt-1">Rp {{ number_format($totalNilaiAset ?? 0, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>

            <!-- TABEL DETAIL LAPORAN -->
            <div class="table-responsive border rounded-lg" style="border-radius: 10px; overflow: auto; max-height: 500px; border-color: #eef2f5 !important;">
                <table class="table align-middle text-center small mb-0" style="border-collapse: separate; border-spacing: 0;">
                    <thead style="background-color: #f8f9fa; position: sticky; top: 0; z-index: 1;">
                        <tr class="text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            <th class="py-3 font-weight-bold border-0">No</th>
                            <th class="py-3 font-weight-bold border-0">ID Ternak</th>
                            <th class="py-3 font-weight-bold border-0">Nama</th>
                            <th class="py-3 font-weight-bold border-0">Jenis</th>
                            <th class="py-3 font-weight-bold border-0">Umur</th>
                            <th class="py-3 font-weight-bold border-0">Bobot</th>
                            <th class="py-3 font-weight-bold border-0">Kelayakan</th>
                            <th class="py-3 font-weight-bold border-0">Probabilitas</th>
                            <th class="py-3 font-weight-bold border-0">Estimasi Harga</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($data as $index => $item)
                            @php 
                                $isLayak = ($item->kelayakan == 1 || strtolower($item->kelayakan ?? '') == 'layak');
                            @endphp
                            <tr style="border-top: 1px solid #f1f3f5;">
                                <td class="align-middle font-weight-bold text-dark py-3">
                                    {{ method_exists($data, 'firstItem') ? $data->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="align-middle font-weight-bold text-dark py-3">{{ $item->kode_ternak ?? $item->id_ternak ?? '-' }}</td>
                                <td class="align-middle text-dark py-3">{{ $item->nama_ternak ?? $item->nama ?? '-' }}</td>
                                <td class="align-middle text-dark py-3">{{ $item->jenis }}</td>
                                <td class="align-middle text-dark py-3">{{ $item->umur ?? '-' }} bulan</td>
                                <td class="align-middle text-dark py-3">{{ $item->bobot ?? $item->berat ?? '-' }} kg</td>
                                <td class="align-middle py-3">
                                    <span class="badge {{ $isLayak ? 'badge-success' : 'badge-danger' }} px-2 py-1">
                                        {{ $isLayak ? 'Layak' : 'Belum Layak' }}
                                    </span>
                                </td>
                                <td class="align-middle font-weight-bold text-dark py-3">
                                    {{ $item->probabilitas ?? $item->persentase ?? '0' }}%
                                </td>
                                <td class="align-middle font-weight-bold text-dark py-3">
                                    Rp {{ number_format($item->estimasi_harga ?? $item->harga_jual ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    Belum ada data laporan ternak.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINASI HALAMAN -->
            @if(method_exists($data, 'hasPages') && $data->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3 print-hide">
                    <div class="small text-muted">
                        Menampilkan {{ $data->firstItem() }} sampai {{ $data->lastItem() }} dari {{ $data->total() }} entri
                    </div>
                    <div>
                        {{ $data->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif

            <!-- TANDA TANGAN MANAJER -->
            <div class="row mt-5 pt-4">
                <div class="col-md-4 offset-md-8 text-center">
                    <p class="mb-5 small text-dark">Cianjur,</p>
                    <p class="font-weight-bold text-dark mb-0" style="margin-top: 40px;">Manajer Peternakan</p>
                    <p class="text-muted small">( ____________________ )</p>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- CSS KHUSUS PRINT -->
<style>
@media print {
    .print-hide, .sidebar, .navbar, footer, #accordionSidebar, .pagination {
        display: none !important;
    }
    .container-fluid {
        padding: 0 !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .table-responsive {
        max-height: none !important;
        overflow: visible !important;
    }
}
</style>
@endsection
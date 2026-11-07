@extends('layout.app')

@section('content')
<div class="container-fluid py-3">

    <!-- Header Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Riwayat Prediksi</h1>
            <p class="text-muted small mb-0">Daftar hasil klasifikasi kelayakan dan estimasi harga jual yang pernah disimpan.</p>
        </div>
        <a href="{{ route('prediksi.index') }}" class="btn btn-sm btn-success font-weight-bold px-3" style="background-color: #0e6251; border-color: #0e6251; border-radius: 8px;">
            <i class="fas fa-plus mr-1"></i> Prediksi Baru
        </a>
    </div>

    <!-- Container Utama -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #ffffff;">
        <div class="card-body p-4">
            
            <!-- PANEL FILTER STATUS & PENCARIAN -->
            <form action="{{ Request::url() }}" method="GET" class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 12px;">
                <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                    <!-- Input Pencarian -->
                    <div style="min-width: 220px;">
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" class="form-control bg-white text-dark small" placeholder="Cari hewan atau ID..." style="border-radius: 8px; height: 38px; border: 1px solid #ced4da;">
                    </div>
                    
                    <!-- Dropdown Filter Jenis Komoditas -->
                    <div>
                        <select name="jenis" id="filterJenis" class="form-control font-weight-bold text-dark small" style="border-radius: 8px; height: 38px; min-width: 140px; border: 1px solid #0e6251;" onchange="this.form.submit()">
                            <option value="">Semua Jenis</option>
                            <option value="Sapi" {{ strtolower(request('jenis')) == 'sapi' ? 'selected' : '' }}>Sapi</option>
                            <option value="Domba" {{ strtolower(request('jenis')) == 'domba' ? 'selected' : '' }}>Domba</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-sm btn-outline-success font-weight-bold px-3" style="border-radius: 8px; height: 38px; color: #0e6251; border-color: #0e6251;">
                        Terapkan
                    </button>

                    @if(request()->hasAny(['search', 'jenis']))
                        <a href="{{ Request::url() }}" class="btn btn-sm btn-link text-danger">Reset Filter</a>
                    @endif
                </div>

                <!-- Info Jumlah Hasil -->
                <div class="text-muted small">
                    <span id="totalHasil" class="font-weight-bold text-dark">{{ method_exists($riwayatList, 'total') ? $riwayatList->total() : count($riwayatList ?? []) }}</span> hasil ditemukan
                </div>
            </form>

            <!-- TABEL DENGAN STYLING RAPI & SCROLL INTERNAL -->
            <div class="table-responsive border rounded-lg" style="border-radius: 10px; overflow: auto; max-height: 500px; border-color: #eef2f5 !important;">
                <table class="table align-middle text-center small mb-0" id="tableRiwayat" style="border-collapse: separate; border-spacing: 0;">
                    <thead style="background-color: #f8f9fa; position: sticky; top: 0; z-index: 1;">
                        <tr class="text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            <th class="py-3 font-weight-bold border-0">No</th>
                            <th class="py-3 font-weight-bold border-0">Tanggal Prediksi</th>
                            <th class="py-3 font-weight-bold border-0">ID Ternak</th>
                            <th class="py-3 font-weight-bold border-0">Komoditas & Ras</th>
                            <th class="py-3 font-weight-bold border-0">Bobot</th>
                            <th class="py-3 font-weight-bold border-0">Skor Kelayakan</th>
                            <th class="py-3 font-weight-bold border-0">Status Kelayakan</th>
                            <th class="py-3 font-weight-bold border-0">Estimasi Harga Jual</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($riwayatList ?? [] as $index => $item)
                            @php 
                                $isLayak = ($item->kelayakan == 1 || strtolower($item->kelayakan ?? '') == 'layak'); 
                            @endphp
                            <tr class="data-row" data-status="{{ $isLayak ? 'layak' : 'tidak layak' }}" style="border-top: 1px solid #f1f3f5;">
                                <!-- Penomoran otomatis berbasis Pagination -->
                                <td class="align-middle font-weight-bold text-dark py-3">
                                    {{ method_exists($riwayatList, 'firstItem') ? $riwayatList->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="align-middle text-muted py-3">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H.i') }}
                                </td>
                                <td class="align-middle font-weight-bold text-dark py-3">
                                    {{ $item->kode_ternak ?? $item->id_ternak ?? '-' }}
                                </td>
                                <td class="align-middle font-weight-bold text-dark py-3">
                                    {{ $item->jenis }} {{ $item->ras ? '('.$item->ras.')' : '' }}
                                </td>
                                <td class="align-middle text-dark py-3">
                                    {{ $item->berat ?? $item->bobot ?? '-' }} kg
                                </td>
                                <td class="align-middle font-weight-bold text-dark py-3">
                                    {{ $item->persentase ?? $item->probabilitas ?? '0' }}%
                                </td>
                                <td class="align-middle py-3">
                                    <span class="badge px-3 py-1 font-weight-bold" style="border-radius: 20px; background-color: {{ $isLayak ? '#e8f5e9' : '#ffebee' }}; color: {{ $isLayak ? '#2e7d32' : '#c62828' }}; font-size: 0.75rem;">
                                        <i class="fas fa-circle mr-1" style="font-size: 0.45rem;"></i> {{ $isLayak ? 'Layak' : 'Tidak Layak' }}
                                    </span>
                                </td>
                                <td class="align-middle font-weight-bold text-dark py-3">
                                    Rp {{ number_format($item->harga_jual ?? $item->estimasi_harga ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-history fa-2x mb-2 text-gray-300 d-block"></i>
                                    Belum ada riwayat prediksi yang tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINASI HALAMAN (Mencegah Halaman Loading Berat) -->
            @if(method_exists($riwayatList, 'hasPages') && $riwayatList->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="small text-muted">
                        Menampilkan {{ $riwayatList->firstItem() }} sampai {{ $riwayatList->lastItem() }} dari {{ $riwayatList->total() }} data
                    </div>
                    <div>
                        {{ $riwayatList->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
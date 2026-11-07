@extends('layout.app')

@section('content')
<div class="container-fluid py-3">

    <!-- Header Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-1 text-gray-800 font-weight-bold">Data Hewan Ternak</h1>
            <p class="text-muted small mb-0">Kelola identitas, karakteristik fisik, kesehatan, dan status prediksi setiap hewan.</p>
        </div>
        <div class="d-flex align-items-center">
            <!-- Tombol Tambah Ternak -->
            <a href="{{ route('data-ternak.create') }}" class="btn btn-sm btn-success font-weight-bold px-3 py-2 shadow-sm" style="background-color: #0e6251; border-color: #0e6251; border-radius: 8px;">
                <i class="fas fa-plus mr-1"></i> Tambah Ternak
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3 py-2 px-3 small" role="alert" style="border-radius: 8px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close py-2" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- TABEL MASTER DATA TERNAK -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; background-color: #ffffff;">
        <div class="card-body p-3">
            
            <!-- Form Filter & Pencarian -->
            <form action="{{ route('data-ternak.index') }}" method="GET" class="d-flex flex-wrap align-items-center justify-content-between mb-3" style="gap: 10px;">
                <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                    <div>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari ID, nama, RFID..." value="{{ request('search') }}" style="border-radius: 8px; width: 180px;">
                    </div>
                    <div>
                        <select name="komoditas" class="form-control form-control-sm" onchange="this.form.submit()" style="border-radius: 8px;">
                            <option value="">Semua jenis</option>
                            <option value="Sapi" {{ request('komoditas') == 'Sapi' ? 'selected' : '' }}>Sapi</option>
                            <option value="Domba" {{ request('komoditas') == 'Domba' ? 'selected' : '' }}>Domba</option>
                        </select>
                    </div>
                    <div>
                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()" style="border-radius: 8px;">
                            <option value="">Semua status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Layak</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Belum Layak</option>
                        </select>
                    </div>
                </div>
                <div class="text-muted small" style="font-size: 0.78rem;">
                    {{ $ternaks->count() }} dari {{ $ternaks->total() }} data
                </div>
            </form>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.8rem; white-space: nowrap;">
                    <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                        <tr>
                            <th class="py-2 pl-3 border-top-0" style="width: 15%;">ID / HEWAN</th>
                            <th class="py-2 border-top-0" style="width: 18%;">JENIS & RAS</th>
                            <th class="py-2 border-top-0 text-center" style="width: 10%;">UMUR</th>
                            <th class="py-2 border-top-0 text-center" style="width: 10%;">BOBOT</th>
                            <th class="py-2 border-top-0 text-center" style="width: 12%;">KESEHATAN</th>
                            <th class="py-2 border-top-0 text-center" style="width: 12%;">STATUS</th>
                            <th class="py-2 border-top-0 text-right" style="width: 13%;">ESTIMASI HARGA</th>
                            <th class="py-2 pr-3 text-center border-top-0" style="width: 10%;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ternaks as $item)
                        <tr class="align-middle">
                            
                            <!-- 1. ID / HEWAN -->
                            <td class="pl-3 py-2 align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px; min-width: 32px;">
                                        <i class="fas fa-paw text-muted" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-0">{{ $item->kode_ternak }}</div>
                                        <small class="text-muted d-block" style="font-size: 0.72rem; margin-top: -2px;">{{ $item->no_ear_tag ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>

                            <!-- 2. JENIS & RAS -->
                            <td class="py-2 align-middle">
                                <div class="font-weight-bold text-dark mb-0">{{ $item->jenis }}</div>
                                <small class="text-muted d-block" style="font-size: 0.72rem; margin-top: -2px;">{{ $item->ras ?? '-' }}</small>
                            </td>

                            <!-- 3. UMUR -->
                            <td class="py-2 align-middle text-center text-dark font-weight-bold">
                                @if($item->tanggal_lahir)
                                    {{ (int) \Carbon\Carbon::parse($item->tanggal_lahir)->diffInMonths(now()) }} bulan
                                @else
                                    -
                                @endif
                            </td>

                            <!-- 4. BOBOT -->
                            <td class="py-2 align-middle text-center text-dark font-weight-bold">
                                {{ number_format($item->berat, 0, ',', '.') }} kg
                            </td>

                            <!-- 5. KESEHATAN -->
                            <td class="py-2 align-middle text-center">
                                <div class="font-weight-bold text-dark mb-0">{{ $item->skor_kesehatan ?? '-' }}/100</div>
                                <small class="text-muted d-block" style="font-size: 0.72rem; margin-top: -2px;">BCS {{ $item->bcs ?? '-' }}</small>
                            </td>

                            <!-- 6. STATUS -->
                            <td class="py-2 align-middle text-center">
                                @if($item->kelayakan == 1)
                                    <span class="badge badge-light text-success px-2 py-1 font-weight-bold" style="border-radius: 12px; background-color: #e8f8f5; font-size: 0.72rem;">
                                        <span style="font-size: 8px; margin-right: 3px;">●</span> Layak
                                    </span>
                                @else
                                    <span class="badge badge-light text-danger px-2 py-1 font-weight-bold" style="border-radius: 12px; background-color: #fde2e2; font-size: 0.72rem;">
                                        <span style="font-size: 8px; margin-right: 3px;">●</span> Belum Layak
                                    </span>
                                @endif
                            </td>

                            <!-- 7. ESTIMASI HARGA -->
                            <td class="py-2 align-middle text-right font-weight-bold text-dark">
                                @if($item->harga_jual)
                                    Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>

                            <!-- 8. AKSI (3 TOMBOL) -->
                            <td class="pr-3 py-2 text-center align-middle">
                                <div class="d-flex align-items-center justify-content-center" style="gap: 4px;">
                                    
                                    <!-- Tombol 1: Detail (Memicu Pop-up Modal) -->
                                    <button type="button" class="btn btn-xs btn-light font-weight-bold text-dark border-0 px-2 py-1" data-toggle="modal" data-target="#modalDetail{{ $item->id }}" style="border-radius: 6px; background-color: #f1f3f5; font-size: 0.72rem;">
                                        Detail
                                    </button>

                                    <!-- Tombol 2: Prediksi -->
                                    <a href="{{ route('prediksi.detail', $item->id) }}" class="btn btn-xs btn-light font-weight-bold text-success border-0 px-2 py-1" style="border-radius: 6px; background-color: #e8f8f5; font-size: 0.72rem;">
                                        Prediksi
                                    </a>

                                    <!-- Tombol 3: Hapus -->
                                    <form action="{{ route('data-ternak.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ternak ini?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-light font-weight-bold text-danger border-0 px-2 py-1" style="border-radius: 6px; background-color: #fde2e2; font-size: 0.72rem;">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-box-open fa-2x mb-2 d-block text-secondary"></i>
                                Belum ada data ternak.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($ternaks->hasPages())
                <div class="d-flex justify-content-end mt-3">
                    {{ $ternaks->links() }}
                </div>
            @endif

        </div>
    </div>

</div>

<!-- MODAL DETAIL HEWAN TERNAK -->
@foreach($ternaks as $item)
    <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                
                <!-- Modal Header -->
                <div class="modal-header border-0 pb-0 pt-4 px-4 align-items-center">
                    <h5 class="modal-title font-weight-bold text-dark" id="modalDetailLabel{{ $item->id }}">Detail Hewan Ternak</h5>
                    <button type="button" class="close rounded-circle p-2" data-dismiss="modal" aria-label="Close" style="background-color: #f8f9fa; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-4">
                    
                    <!-- Info Highlight Banner -->
                    <div class="alert alert-primary border-0 d-flex align-items-center p-3 mb-4" style="background-color: #eff6ff; border-radius: 12px;">
                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mr-3" style="width: 36px; height: 36px; min-width: 36px;">
                            <i class="fas fa-paw text-primary"></i>
                        </div>
                        <div class="small text-primary">
                            <strong>{{ $item->kode_ternak }}</strong> merupakan {{ strtolower($item->jenis) }} ras <strong>{{ $item->ras ?? '-' }}</strong>.
                        </div>
                    </div>

                    <!-- Grid Informasi Detail -->
                    <div class="row" style="row-gap: 12px;">
                        
                        <!-- ID Ternak -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">ID Ternak</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->kode_ternak }}</span>
                            </div>
                        </div>

                        <!-- RFID -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">RFID / Ear Tag</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->no_ear_tag ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Lokasi Kandang -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Lokasi Kandang</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->lokasi_kandang ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Jenis Komoditas -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Jenis Komoditas</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->jenis }}</span>
                            </div>
                        </div>

                        <!-- Ras / Varian -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Ras / Varian</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->ras ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Jenis Kelamin</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->jenis_kelamin }}</span>
                            </div>
                        </div>

                        <!-- Umur -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Umur</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">
                                    @if($item->tanggal_lahir)
                                        {{ (int) \Carbon\Carbon::parse($item->tanggal_lahir)->diffInMonths(now()) }} bulan
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Bobot -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Bobot Badan</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ number_format($item->berat, 0, ',', '.') }} kg</span>
                            </div>
                        </div>

                        <!-- Tinggi Badan -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Tinggi Badan</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->tinggi_badan ? $item->tinggi_badan . ' cm' : '-' }}</span>
                            </div>
                        </div>

                        <!-- Kualitas Pakan -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Kualitas Pakan</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->kualitas_pakan ? $item->kualitas_pakan . '/5' : '-' }}</span>
                            </div>
                        </div>

                        <!-- BCS -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Body Condition Score (BCS)</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->bcs ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Skor Kesehatan -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Skor Kesehatan</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->skor_kesehatan ?? '-' }}/100</span>
                            </div>
                        </div>

                        <!-- Status Vaksinasi -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Status Vaksinasi</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->status_vaksinasi ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Riwayat Penyakit -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Riwayat Penyakit</small>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item->riwayat_penyakit ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Status Kelayakan -->
                        <div class="col-md-4">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Status Kelayakan</small>
                                @if($item->kelayakan == 1)
                                    <span class="badge badge-success px-2 py-1" style="border-radius: 8px;">Layak</span>
                                @else
                                    <span class="badge badge-danger px-2 py-1" style="border-radius: 8px;">Belum Layak</span>
                                @endif
                            </div>
                        </div>

                        <!-- Estimasi Harga -->
                        <div class="col-md-12">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Estimasi Harga Jual Pasar</small>
                                <span class="font-weight-bold text-success h5 mb-0 d-block">
                                    @if($item->harga_jual)
                                        Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                                    @else
                                        Belum Ada Estimasi
                                    @endif
                                </span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endforeach

@endsection
@extends('layout.app')

@section('content')
<div class="container-fluid py-3">

    <!-- Title Page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Data Pemeriksaan Ternak</h1>
            <p class="text-muted small mb-0">Kelola riwayat medis, pengukuran fisik berkala, dan status kesehatan ternak.</p>
        </div>
        <button type="button" class="btn btn-success font-weight-bold px-3 shadow-sm" data-toggle="modal" data-target="#modalTambahPemeriksaan" style="background-color: #0e6251; border-color: #0e6251; border-radius: 8px;">
            <i class="fas fa-notes-medical mr-1"></i> Tambah Pemeriksaan
        </button>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 8px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- DataTales Card -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-success" style="color: #0e6251 !important;"><i class="fas fa-notes-medical mr-1"></i> Daftar Riwayat Pemeriksaan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle" id="dataTable" width="100%" cellspacing="0" style="font-size: 0.9rem;">
                    <thead class="bg-light">
                        <tr class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            <th>No</th>
                            <th>ID Ternak</th>
                            <th>Pemeriksa</th>
                            <th>Tgl Periksa</th>
                            <th>Bobot (Kg)</th>
                            <th>BCS</th>
                            <th>Skor Kesehatan</th>
                            <th>Status Vaksin</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemeriksaans as $index => $item)
                            <tr>
                                <td class="align-middle font-weight-bold">{{ $pemeriksaans->firstItem() + $index }}</td>
                                <td class="align-middle font-weight-bold text-primary">{{ $item->ternak->kode_ternak ?? 'TRK-'.$item->ternak_id }}</td>
                                <td class="align-middle">{{ $item->pemeriksa->name ?? $item->pemeriksa->nama ?? '-' }}</td>
                                <td class="align-middle">{{ \Carbon\Carbon::parse($item->tanggal_pemeriksaan)->format('d/m/Y') }}</td>
                                <td class="align-middle font-weight-bold">{{ $item->bobot ? number_format($item->bobot, 2) . ' Kg' : '-' }}</td>
                                <td class="align-middle">{{ $item->bcs ?? $item->ternak->bcs ?? '-' }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-success px-2 py-1" style="border-radius: 6px;">{{ $item->skor_kesehatan ?? $item->status_kesehatan ?? 'Sehat' }}</span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-primary px-2 py-1" style="border-radius: 6px;">{{ $item->status_vaksinasi ?? $item->status_vaksin ?? 'Lengkap' }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm text-white" data-toggle="modal" data-target="#modalEditPemeriksaan{{ $item->id }}" title="Edit Data" style="border-radius: 6px 0 0 6px;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('pemeriksaan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pemeriksaan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Data" style="border-radius: 0 6px 6px 0;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                    Belum ada data pemeriksaan ternak.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $pemeriksaans->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

<!-- LOOP MODAL EDIT PEMERIKSAAN -->
@foreach($pemeriksaans as $item)
<div class="modal fade text-left" id="modalEditPemeriksaan{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header text-white" style="background-color: #0e6251; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-notes-medical mr-1"></i> Edit Data Pemeriksaan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pemeriksaan.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    
                    <!-- INFORMASI UTAMA -->
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">ID Ternak <span class="text-danger">*</span></label>
                            <select name="ternak_id" class="form-control" required style="border-radius: 8px;">
                                @foreach($ternaks as $t)
                                    <option value="{{ $t->id }}" {{ $item->ternak_id == $t->id ? 'selected' : '' }}>
                                        {{ $t->kode_ternak }} - {{ $t->jenis }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Pemeriksa (Petugas) <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-control" required style="border-radius: 8px;">
                                @foreach($petugas as $p)
                                    <option value="{{ $p->id }}" {{ $item->user_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->name ?? $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pemeriksaan" class="form-control" value="{{ old('tanggal_pemeriksaan', $item->tanggal_pemeriksaan) }}" required style="border-radius: 8px;">
                        </div>
                    </div>

                    <!-- KONDISI FISIK -->
                    <h6 class="font-weight-bold text-success mb-3" style="color: #0e6251 !important;">B. KONDISI FISIK</h6>
                    <div class="row mb-3">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Bobot Badan (kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="bobot" class="form-control" value="{{ old('bobot', $item->bobot) }}" required style="border-radius: 8px;">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Tinggi Badan (cm)</label>
                            <input type="number" step="0.01" name="tinggi_badan" class="form-control" value="{{ old('tinggi_badan', $item->tinggi_badan ?? $item->ternak->tinggi_badan ?? '') }}" style="border-radius: 8px;">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Body Condition Score (1-5)</label>
                            <input type="number" step="0.1" min="1" max="5" name="bcs" class="form-control" value="{{ old('bcs', $item->bcs ?? $item->ternak->bcs ?? '') }}" style="border-radius: 8px;">
                        </div>
                    </div>

                    <!-- KESEHATAN & PEMELIHARAAN -->
                    <h6 class="font-weight-bold text-success mb-3" style="color: #0e6251 !important;">C. KESEHATAN & PEMELIHARAAN</h6>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Skor Kesehatan (0-100)</label>
                            <input type="number" min="0" max="100" name="skor_kesehatan" class="form-control" value="{{ old('skor_kesehatan', $item->skor_kesehatan ?? $item->status_kesehatan ?? '') }}" style="border-radius: 8px;">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Kualitas Pakan (1-5)</label>
                            <input type="number" min="1" max="5" name="kualitas_pakan" class="form-control" value="{{ old('kualitas_pakan', $item->kualitas_pakan ?? $item->ternak->kualitas_pakan ?? '') }}" style="border-radius: 8px;">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Status Vaksinasi</label>
                            <select name="status_vaksin" class="form-control" style="border-radius: 8px;">
                                <option value="Lengkap" {{ in_array(strtolower($item->status_vaksin ?? $item->status_vaksinasi), ['lengkap', 'sudah', 'tervaksin', '1']) ? 'selected' : '' }}>Lengkap</option>
                                <option value="Sebagian" {{ strtolower($item->status_vaksin ?? $item->status_vaksinasi) == 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                                <option value="Belum" {{ in_array(strtolower($item->status_vaksin ?? $item->status_vaksinasi), ['belum', '0']) ? 'selected' : '' }}>Belum</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Riwayat Penyakit</label>
                            <select name="riwayat_penyakit" class="form-control" style="border-radius: 8px;">
                                <option value="Tidak Ada" {{ old('riwayat_penyakit', $item->riwayat_penyakit) == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                <option value="Ada" {{ old('riwayat_penyakit', $item->riwayat_penyakit) == 'Ada' ? 'selected' : '' }}>Ada</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark small mb-1">Catatan Petugas</label>
                        <textarea name="catatan_petugas" class="form-control" rows="3" style="border-radius: 8px;">{{ old('catatan_petugas', $item->catatan_petugas ?? $item->catatan) }}</textarea>
                    </div>

                </div>
                <div class="modal-footer bg-light" style="border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-light font-weight-bold text-secondary px-4 border" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold px-4" style="background-color: #0e6251; border-color: #0e6251; border-radius: 8px;">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- MODAL FORM TAMBAH PEMERIKSAAN -->
<div class="modal fade" id="modalTambahPemeriksaan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header text-white" style="background-color: #0e6251; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-notes-medical mr-1"></i> Input Pemeriksaan Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pemeriksaan.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">

                    <!-- INFORMASI UTAMA -->
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">ID Ternak <span class="text-danger">*</span></label>
                            <select name="ternak_id" id="selectTernakModal" class="form-control" required style="border-radius: 8px;">
                                <option value="" disabled selected>Pilih ID / Kode Ternak</option>
                                @foreach($ternaks as $t)
                                    <option value="{{ $t->id }}"
                                            data-bobot="{{ $t->berat ?? $t->bobot ?? '' }}"
                                            data-tinggi="{{ $t->tinggi_badan ?? '' }}"
                                            data-bcs="{{ $t->bcs ?? '' }}"
                                            data-skor="{{ $t->skor_kesehatan ?? $t->status_kesehatan ?? '' }}"
                                            data-pakan="{{ $t->kualitas_pakan ?? '' }}"
                                            data-vaksin="{{ $t->status_vaksinasi ?? $t->status_vaksin ?? 'Lengkap' }}"
                                            data-penyakit="{{ $t->riwayat_penyakit ?? 'Tidak Ada' }}"
                                            data-catatan="{{ $t->catatan_petugas ?? '' }}">
                                        {{ $t->kode_ternak }} - {{ $t->jenis }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Pemeriksa (Petugas) <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-control" required style="border-radius: 8px;">
                                <option value="" disabled>Pilih Nama Petugas</option>
                                @foreach($petugas as $p)
                                    <option value="{{ $p->id }}" {{ auth()->id() == $p->id ? 'selected' : '' }}>
                                        {{ $p->name ?? $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pemeriksaan" class="form-control" value="{{ date('Y-m-d') }}" required style="border-radius: 8px;">
                        </div>
                    </div>

                    <!-- B. KONDISI FISIK -->
                    <h6 class="font-weight-bold text-success mb-3" style="color: #0e6251 !important;">B. KONDISI FISIK</h6>
                    <div class="row mb-3">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Bobot Badan (kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="bobot" id="inputBobot" class="form-control" placeholder="0" required style="border-radius: 8px;">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Tinggi Badan (cm)</label>
                            <input type="number" step="0.01" name="tinggi_badan" id="inputTinggi" class="form-control" placeholder="0" style="border-radius: 8px;">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Body Condition Score (1-5)</label>
                            <input type="number" step="0.1" min="1" max="5" name="bcs" id="inputBcs" class="form-control" placeholder="Contoh: 3.5" style="border-radius: 8px;">
                        </div>
                    </div>

                    <!-- C. KESEHATAN & PEMELIHARAAN -->
                    <h6 class="font-weight-bold text-success mb-3" style="color: #0e6251 !important;">C. KESEHATAN & PEMELIHARAAN</h6>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Skor Kesehatan (0-100)</label>
                            <input type="number" min="0" max="100" name="skor_kesehatan" id="inputSkor" class="form-control" placeholder="0" style="border-radius: 8px;">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Kualitas Pakan (1-5)</label>
                            <input type="number" min="1" max="5" name="kualitas_pakan" id="inputPakan" class="form-control" placeholder="0" style="border-radius: 8px;">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Status Vaksinasi</label>
                            <select name="status_vaksin" id="selectVaksin" class="form-control" style="border-radius: 8px;">
                                <option value="Lengkap">Lengkap</option>
                                <option value="Sebagian">Sebagian</option>
                                <option value="Belum">Belum</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark small mb-1">Riwayat Penyakit</label>
                            <select name="riwayat_penyakit" id="selectPenyakit" class="form-control" style="border-radius: 8px;">
                                <option value="Tidak Ada">Tidak Ada</option>
                                <option value="Ada">Ada</option>
                            </select>
                        </div>
                    </div>

                    <!-- CATATAN PETUGAS -->
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark small mb-1">Catatan Petugas</label>
                        <textarea name="catatan_petugas" id="inputCatatan" class="form-control" rows="3" placeholder="Tambahkan kondisi khusus atau hasil pemeriksaan terakhir..." style="border-radius: 8px;"></textarea>
                    </div>

                </div>
                <div class="modal-footer bg-light" style="border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-light font-weight-bold text-secondary px-4 border" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold px-4" style="background-color: #0e6251; border-color: #0e6251; border-radius: 8px;">
                        <i class="fas fa-save mr-1"></i> Simpan Data Pemeriksaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT AUTO-FILL SAAT PILIH ID TERNAK -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const selectTernak = document.getElementById("selectTernakModal");

    if (selectTernak) {
        selectTernak.addEventListener("change", function() {
            const selected = this.options[this.selectedIndex];

            // Ambil data dari atribut option
            const bobot    = selected.getAttribute("data-bobot") || "";
            const tinggi   = selected.getAttribute("data-tinggi") || "";
            const bcs      = selected.getAttribute("data-bcs") || "";
            const skor     = selected.getAttribute("data-skor") || "";
            const pakan    = selected.getAttribute("data-pakan") || "";
            const vaksin   = selected.getAttribute("data-vaksin") || "Lengkap";
            const penyakit = selected.getAttribute("data-penyakit") || "Tidak Ada";
            const catatan  = selected.getAttribute("data-catatan") || "";

            // Auto-fill ke elemen input di form
            document.getElementById("inputBobot").value = bobot;
            document.getElementById("inputTinggi").value = tinggi;
            document.getElementById("inputBcs").value = bcs;
            document.getElementById("inputSkor").value = skor;
            document.getElementById("inputPakan").value = pakan;
            document.getElementById("selectVaksin").value = vaksin;
            document.getElementById("selectPenyakit").value = penyakit;
            document.getElementById("inputCatatan").value = catatan;
        });
    }
});
</script>
@endsection
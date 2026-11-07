@extends('layout.app')

@section('content')
<div class="container-fluid py-3">

    <!-- Title Page -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Tambah Data Ternak</h1>
                   </div>
        <a href="{{ route('data-ternak.index') }}" class="btn btn-sm btn-outline-secondary font-weight-bold px-3" style="border-radius: 8px;">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Tabel Ternak
        </a>
    </div>

    <!-- Alert Notifikasi Error dari Validation Exception ($errors) -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 8px;">
            <i class="fas fa-exclamation-circle mr-2"></i> <strong>Terjadi Kesalahan:</strong>
            <ul class="mb-0 mt-1 pl-4 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Alert Notifikasi Error Custom (session error) -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 8px;">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- CARD FITUR IMPORT EXCEL -->
    <div class="card shadow-sm border-0 p-4 mb-4" style="border-radius: 12px; background-color: #f8f9fc; border-left: 5px solid #0e6251 !important;">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="font-weight-bold text-dark mb-0">
                <i class="fas fa-file-excel text-success mr-2"></i> Import Data Massal via Excel
            </h6>
            <!-- Tombol Unduh Template Excel di Header Card -->
            <a href="{{ route('data-ternak.download-template') }}" class="btn btn-sm btn-outline-success font-weight-bold px-3 py-1 shadow-sm" style="border-radius: 6px; font-size: 0.8rem;">
                <i class="fas fa-download mr-1"></i> Unduh Template Excel
            </a>
        </div>
        <p class="text-muted small mb-3">Upload file Excel (.xlsx / .csv) untuk memasukkan banyak data ternak secara otomatis sekaligus.</p>

        <form action="{{ route('data-ternak.import') }}" method="POST" enctype="multipart/form-data" class="row align-items-center">
            @csrf
            <div class="col-md-7 mb-2 mb-md-0">
                <input type="file" name="file_excel" class="form-control-file p-2 bg-white border" required style="border-radius: 8px;">
            </div>
            <div class="col-md-5 d-flex align-items-center justify-content-end" style="gap: 8px;">
                <button type="submit" class="btn btn-success font-weight-bold px-3 w-100" style="background-color: #0e6251; border-color: #0e6251; border-radius: 8px;">
                    <i class="fas fa-upload mr-1"></i> Upload & Import
                </button>
            </div>
        </form>
    </div>

    <!-- PEMBATAS / SEPARATOR -->
    <div class="text-center my-4 position-relative">
        <hr>
        <span class="position-absolute bg-white px-3 text-muted small font-weight-bold" style="top: -12px; left: 50%; transform: translateX(-50%);">ATAU ISI MANUAL</span>
    </div>

    <!-- FORM MANUAL -->
    <form action="{{ route('data-ternak.store') }}" method="POST" id="formTernak">
        @csrf
        
        <div class="card shadow-sm border-0 p-4 mb-4" style="border-radius: 12px; background-color: #ffffff;">
            
            <!-- ================= A. IDENTITAS TERNAK ================= -->
            <h6 class="font-weight-bold text-success mb-4" style="letter-spacing: 0.5px; color: #0e6251 !important;">A. IDENTITAS TERNAK</h6>
            
            <div class="row mb-3">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Jenis Komoditas <span class="text-danger">*</span></label>
                    <select name="jenis" id="selectJenis" class="form-control" required style="border-radius: 8px;">
                        <option value="" disabled {{ old('jenis') ? '' : 'selected' }}>Pilih jenis komoditas</option>
                        <option value="Sapi" {{ old('jenis') == 'Sapi' ? 'selected' : '' }}>Sapi</option>
                        <option value="Domba" {{ old('jenis') == 'Domba' ? 'selected' : '' }}>Domba</option>
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">ID Ternak <span class="text-danger">*</span></label>
                    <input type="text" name="kode_ternak" id="inputKodeTernak" class="form-control" placeholder="Pilih Jenis Komoditas Dulu" value="{{ old('kode_ternak') }}" required style="border-radius: 8px;">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Nomor RFID / Eartag</label>
                    <input type="text" name="no_ear_tag" id="inputEarTag" class="form-control" placeholder="Pilih Jenis Komoditas Dulu" value="{{ old('no_ear_tag') }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" style="border-radius: 8px;">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Ras / Varian</label>
                    <select name="ras" id="selectRas" class="form-control" style="border-radius: 8px;">
                        <option value="" disabled {{ old('ras') ? '' : 'selected' }}>Pilih Ras / Varian</option>
                        <option value="Sapi Brahman Cross" data-jenis="Sapi" {{ old('ras') == 'Sapi Brahman Cross' ? 'selected' : '' }}>Sapi Brahman Cross</option>
                        <option value="Domba Garut Semi" data-jenis="Domba" {{ old('ras') == 'Domba Garut Semi' ? 'selected' : '' }}>Domba Garut Semi</option>
                        <option value="Domba Garut Murni" data-jenis="Domba" {{ old('ras') == 'Domba Garut Murni' ? 'selected' : '' }}>Domba Garut Murni</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-control" required style="border-radius: 8px;">
                        <option value="Jantan" {{ old('jenis_kelamin') == 'Jantan' ? 'selected' : '' }}>Jantan</option>
                        <option value="Betina" {{ old('jenis_kelamin') == 'Betina' ? 'selected' : '' }}>Betina</option>
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Lokasi Kandang</label>
                    <input type="text" name="lokasi_kandang" class="form-control" placeholder="Contoh: Kandang A-02" value="{{ old('lokasi_kandang') }}" style="border-radius: 8px;">
                </div>
            </div>

            <!-- ================= B. KONDISI FISIK ================= -->
            <h6 class="font-weight-bold text-success mb-4" style="letter-spacing: 0.5px; color: #0e6251 !important;">B. KONDISI FISIK</h6>
            
            <div class="row mb-3">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Bobot Badan (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="berat" class="form-control" placeholder="0" value="{{ old('berat') }}" required style="border-radius: 8px;">
                </div>
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Tinggi Badan (cm)</label>
                    <input type="number" step="0.01" name="tinggi_badan" class="form-control" placeholder="0" value="{{ old('tinggi_badan') }}" style="border-radius: 8px;">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Body Condition Score (1-5)</label>
                    <input type="number" step="0.1" min="1" max="5" name="bcs" class="form-control" placeholder="Contoh: 3.5" value="{{ old('bcs') }}" style="border-radius: 8px;">
                </div>
            </div>

            <!-- ================= C. KESEHATAN & PEMELIHARAAN ================= -->
            <h6 class="font-weight-bold text-success mb-4" style="letter-spacing: 0.5px; color: #0e6251 !important;">C. KESEHATAN & PEMELIHARAAN</h6>
            
            <div class="row mb-3">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Skor Kesehatan (0-100)</label>
                    <input type="number" min="0" max="100" name="skor_kesehatan" class="form-control" placeholder="0" value="{{ old('skor_kesehatan') }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Kualitas Pakan (1-5)</label>
                    <input type="number" min="1" max="5" name="kualitas_pakan" class="form-control" placeholder="0" value="{{ old('kualitas_pakan') }}" style="border-radius: 8px;">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Status Vaksinasi</label>
                    <select name="status_vaksinasi" class="form-control" style="border-radius: 8px;">
                        <option value="Lengkap" {{ old('status_vaksinasi') == 'Lengkap' ? 'selected' : '' }}>Lengkap</option>
                        <option value="Sebagian" {{ old('status_vaksinasi') == 'Sebagian' ? 'selected' : '' }}>Sebagian</option>
                        <option value="Belum" {{ old('status_vaksinasi') == 'Belum' ? 'selected' : '' }}>Belum</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-dark small mb-1">Riwayat Penyakit</label>
                    <select name="riwayat_penyakit" class="form-control" style="border-radius: 8px;">
                        <option value="Tidak Ada" {{ old('riwayat_penyakit') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                        <option value="Ada" {{ old('riwayat_penyakit') == 'Ada' ? 'selected' : '' }}>Ada</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center" style="gap: 12px;">
                <button type="reset" class="btn btn-light font-weight-bold text-secondary px-4 border" style="border-radius: 8px;">Reset</button>
                <button type="submit" class="btn btn-success font-weight-bold px-4" style="background-color: #0e6251; border-color: #0e6251; border-radius: 8px;">
                    <i class="fas fa-save mr-1"></i> Simpan Data Ternak
                </button>
            </div>

        </div>
    </form>
</div>

<!-- SCRIPT OTOMATISISASI AUTO INCREMENT & FILTER RAS -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const selectJenis = document.getElementById("selectJenis");
    const selectRas = document.getElementById("selectRas");
    const inputKodeTernak = document.getElementById("inputKodeTernak");
    const inputEarTag = document.getElementById("inputEarTag");
    const rasOptions = selectRas.querySelectorAll("option");

    // Tangkap kode auto-increment resmi dari Controller PHP
    const autoCodes = {
        Sapi: {
            id: "{{ $autoSapiId ?? 'SPI-001' }}",
            tag: "{{ $autoSapiTag ?? 'S26-001' }}"
        },
        Domba: {
            id: "{{ $autoDombaId ?? 'DMB-001' }}",
            tag: "{{ $autoDombaTag ?? 'D26-001' }}"
        }
    };

    function updateKodeAndRas() {
        const selectedJenis = selectJenis.value;

        // 1. Auto Fill ID & Ear Tag Otomatis Resmi Berurutan
        if (selectedJenis && autoCodes[selectedJenis]) {
            if (!inputKodeTernak.value || inputKodeTernak.value.startsWith("SPI-") || inputKodeTernak.value.startsWith("DMB-") || inputKodeTernak.value.startsWith("TRK-")) {
                inputKodeTernak.value = autoCodes[selectedJenis].id;
            }
            if (!inputEarTag.value || inputEarTag.value.startsWith("S") || inputEarTag.value.startsWith("D") || inputEarTag.value.startsWith("RFID-")) {
                inputEarTag.value = autoCodes[selectedJenis].tag;
            }

            inputKodeTernak.placeholder = "Contoh: " + autoCodes[selectedJenis].id;
            inputEarTag.placeholder = "Contoh: " + autoCodes[selectedJenis].tag;
        }

        // 2. Filter Opsi Ras Sesuai Komoditas
        rasOptions.forEach(option => {
            const optJenis = option.getAttribute("data-jenis");
            if (!optJenis) return;

            if (selectedJenis && optJenis !== selectedJenis) {
                option.style.display = "none";
                option.disabled = true;
            } else {
                option.style.display = "block";
                option.disabled = false;
            }
        });

        const currentSelected = selectRas.options[selectRas.selectedIndex];
        if (currentSelected && currentSelected.getAttribute("data-jenis") && currentSelected.getAttribute("data-jenis") !== selectedJenis) {
            selectRas.value = "";
        }
    }

    selectJenis.addEventListener("change", updateKodeAndRas);

    selectRas.addEventListener("change", function() {
        const selectedOption = selectRas.options[selectRas.selectedIndex];
        const optJenis = selectedOption.getAttribute("data-jenis");

        if (optJenis && !selectJenis.value) {
            selectJenis.value = optJenis;
            updateKodeAndRas();
        }
    });

    if (selectJenis.value) {
        updateKodeAndRas();
    }
});
</script>
@endsection
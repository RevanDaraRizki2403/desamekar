@extends('layout.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 text-center">
                <h5 class="m-0 font-weight-bold text-primary">Form Pengajuan Dokumen</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="font-weight-bold">Jenis Dokumen</label>
                        <select name="jenis_dokumen" id="jenis_dokumen" class="form-control" onchange="tampilkanSyarat()" required>
                            <option value="" disabled selected>-- Pilih Jenis Dokumen --</option>
                            <option value="Surat Domisili">Surat Domisili</option>
                            <option value="SKTM">SKTM</option>
                            <option value="SKU">SKU</option>
                            <option value="Surat Pengantar KTP">Surat Pengantar KTP</option>
                            <option value="Surat Keterangan Kelahiran">Surat Keterangan Kelahiran</option>
                        </select>
                    </div>

                    <div id="box-syarat" class="alert alert-info border-0 shadow-sm" style="display: none;">
                        <h6 class="font-weight-bold small"><i class="fas fa-info-circle mr-2"></i>Persyaratan:</h6>
                        <ul id="list-syarat" class="mb-0 small"></ul>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Keperluan</label>
                        <textarea name="keperluan" class="form-control" rows="3" required placeholder="Contoh: Persyaratan beasiswa"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">File Pendukung (Gabungkan PDF/JPG)</label>
                        <input type="file" name="file_pendukung" class="form-control-file border p-2 rounded w-100">
                        <small class="text-muted">Maksimal 2MB</small>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary rounded-pill px-4">Batal</a>
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow">Kirim Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function tampilkanSyarat() {
    const jenis = document.getElementById('jenis_dokumen').value;
    const box = document.getElementById('box-syarat');
    const list = document.getElementById('list-syarat');
    box.style.display = 'block';
    list.innerHTML = "";
    let syarat = [];
    if (jenis === "Surat Domisili") syarat = ["Fotokopi KTP & KK", "Surat Pengantar RT/RW", "Pas Foto 3x4"];
    else if (jenis === "SKTM") syarat = ["Surat Pengantar RT/RW", "Foto Rumah"];
    else syarat = ["Kartu Keluarga/KTP"];
    syarat.forEach(item => {
        let li = document.createElement('li');
        li.innerText = item;
        list.appendChild(li);
    });
}
</script>
@endsection
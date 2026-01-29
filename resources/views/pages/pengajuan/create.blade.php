@extends('layout.app')

@section('content')
<h4>Form Pengajuan Dokumen</h4>

<form action="/pengajuan-dokumen" method="POST" enctype="multipart/form-data">
@csrf

<div class="mb-2">
    <label>Jenis Dokumen</label>
    <select name="jenis_dokumen" class="form-control">
        <option>Surat Domisili</option>
        <option>SKTM</option>
        <option>SKU</option>
        <option>Surat Usaha</option>
        <option>Surat Pengantar KTP</option>
        <option>Surat Pengantar KK</option>
        <option>Surat Pengantar Nikah</option>
        <option>Surat Keterangan Kelahiran</option>
    </select>
</div>

<div class="mb-2">
    <label>Keperluan</label>
    <textarea name="keperluan" class="form-control"></textarea>
</div>

<div class="mb-2">
    <label>File Pendukung</label>
    <input type="file" name="file_pendukung" class="form-control">
</div>

<button class="btn btn-success">Kirim</button>
</form>
@endsection

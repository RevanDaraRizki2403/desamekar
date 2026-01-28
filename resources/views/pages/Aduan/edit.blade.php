@extends('layout.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Ubah Aduan</h1>
</div>

<div class="row">
    <div class="col">
        {{-- Perbaikan URL: Tambahkan ID aduan pada action --}}
        <form action="{{ route('Aduan.update', $aduan->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">

                    {{-- Judul --}}
                    <div class="form-group mb-3">
                        <label for="title">Judul</label>
                        <input type="text" name="title" id="title"
                            class="form-control @error('title') is-invalid @enderror" 
                            value="{{ old('title', $aduan->title) }}">
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Isi Aduan --}}
                    <div class="form-group mb-3">
                        <label for="content">Isi Aduan</label>
                        {{-- Perbaikan: Tambahkan data lama di dalam textarea --}}
                        <textarea name="content" id="content" rows="3" 
                            class="form-control @error('content') is-invalid @enderror">{{ old('content', $aduan->content) }}</textarea>
                        @error('content')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Bukti Foto --}}
                    <div class="form-group mb-3">
                        <label for="photo_proof">Bukti Foto (Kosongkan jika tidak ingin diubah)</label>
                        @if($aduan->photo_proof)
                            <div class="mb-2">
                                <small class="text-muted">Foto saat ini:</small><br>
                                <img src="{{ asset('storage/' . $aduan->photo_proof) }}" width="100" class="img-thumbnail mt-1">
                            </div>
                        @endif
                        <input type="file" name="photo_proof" id="photo_proof"
                            class="form-control @error('photo_proof') is-invalid @enderror">
                        @error('photo_proof')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-end" style="gap:10px;">
                        <a href="{{ route('Aduan.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update Aduan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
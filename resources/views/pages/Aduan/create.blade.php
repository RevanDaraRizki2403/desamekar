@extends('layout.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Aduan</h1>
    </div>

    <div class="row">
        <div class="col">
            <form action="/Aduan" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card">
                    <div class="card-body">

                        {{-- NIK --}}
                        <div class="form-group mb-3">
                            <label for="title">Judul</label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group mb-3">
                            <label for="content">Isi Aduan</label>
                            <textarea name="content" id="content" rows="3" class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                            @error('content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Nama --}}
                        <div class="form-group mb-3">
    <label for="photo_proof">Bukti Foto</label>
    <input type="file" name="photo_proof" id="photo_proof"
        class="form-control @error('photo_proof') is-invalid @enderror">
    @error('photo_proof')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

                    </div> {{-- End Card Body --}}

                    <div class="card-footer">
                        <div class="d-flex justify-content-end" style="gap:10px;">
                            <a href="/Aduan" class="btn btn-outline-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

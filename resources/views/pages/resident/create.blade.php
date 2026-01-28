@extends('layout.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Penduduk</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="/resident" method="post">
                @csrf
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">

                        {{-- NIK --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-bold" for="nik">NIK</label>
                            <input type="number" inputmode="numeric" name="nik" id="nik"
                                class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" placeholder="Masukkan 16 digit NIK">
                            @error('nik')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Nama --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-bold" for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama sesuai KTP">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Jenis Kelamin --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold" for="gender">Jenis Kelamin</label>
                                    <select name="gender" id="gender"
                                        class="form-control @error('gender') is-invalid @enderror">
                                        <option value="">-- Pilih --</option>
                                        <option value="male" @selected(old('gender') == 'male')>Laki-laki</option>
                                        <option value="female" @selected(old('gender') == 'female')>Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status Penduduk --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold" for="status">Status Penduduk</label>
                                    <select name="status" id="status"
                                        class="form-control @error('status') is-invalid @enderror">
                                        <option value="active" @selected(old('status') == 'active')>Aktif</option>
                                        <option value="moved" @selected(old('status') == 'moved')>Pindah</option>
                                        <option value="deceased" @selected(old('status') == 'deceased')>Almarhum</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div> {{-- End Card Body --}}

                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-end" style="gap:10px;">
                            <a href="/resident" class="btn btn-outline-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
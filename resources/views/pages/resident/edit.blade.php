@extends('layout.app')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Ubah Data Penduduk</h1>
    <a href="/resident" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('resident.update', $resident->id) }}" method="post">
            @csrf
            @method('PUT')
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- NIK --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold" for="nik">NIK</label>
                                <input type="number" name="nik" id="nik" 
                                    class="form-control @error('nik') is-invalid @enderror" 
                                    value="{{ old('nik', $resident->nik) }}">
                                @error('nik')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Nama --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold" for="name">Nama Lengkap</label>
                                <input type="text" name="name" id="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $resident->name) }}">
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                {{-- Jenis Kelamin --}}
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold" for="gender">Jenis Kelamin</label>
                                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                            <option value="male" @selected(old('gender', $resident->gender) == 'male')>Laki-laki</option>
                                            <option value="female" @selected(old('gender', $resident->gender) == 'female')>Perempuan</option>
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
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="active" @selected(old('status', $resident->status) == 'active')>Aktif</option>
                                            <option value="moved" @selected(old('status', $resident->status) == 'moved')>Pindah</option>
                                            <option value="deceased" @selected(old('status', $resident->status) == 'deceased')>Almarhum</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light p-3">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning px-4 shadow-sm font-weight-bold text-dark">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
    
@endsection
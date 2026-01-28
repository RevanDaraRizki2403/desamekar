@extends('layout.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Penduduk</h1>
    <a href="/resident/create" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Penduduk
    </a>
</div>

<div class="row">
    <div class="col">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light text-dark">
                            <tr class="text-center">
                                <th width="50">No</th>
                                <th>NIK</th>
                                <th class="text-left">Nama</th>
                                <th>JK</th>
                                <th>Status</th>
                                <th width="160">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse ($residents as $item)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-weight-bold text-dark">{{ $item->nik }}</td>
                                <td class="text-left">{{ $item->name }}</td>
                                <td>
                                    {{-- PERBAIKAN LOGIKA JENIS KELAMIN --}}
                                    <span class="badge badge-light border">
                                        @if($item->gender == 'L' || $item->gender == 'male')
                                            Laki-laki
                                        @else
                                            Perempuan
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-success px-3 shadow-sm">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('resident.edit', $item->id) }}"
                                           class="btn btn-sm btn-outline-warning mx-1 shadow-sm"
                                           title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger mx-1 shadow-sm"
                                            data-toggle="modal"
                                            data-target="#confirmationDelete{{ $item->id }}"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- Tombol Detail Akun --}}
                                        @if ($item->user)
                                            <button type="button"
                                                class="btn btn-sm btn-outline-info mx-1 shadow-sm"
                                                data-toggle="modal"
                                                data-target="#detailAccount{{ $item->id }}"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary mx-1 shadow-sm" disabled title="Tidak ada akun">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-users-slash fa-3x mb-3 text-gray-300"></i><br>
                                    Tidak ada data penduduk
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL AREA --}}
@foreach ($residents as $item)
    {{-- Modal Delete --}}
    <div class="modal fade" id="confirmationDelete{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                    <p class="mb-0">Yakin ingin menghapus <strong>{{ $item->name }}</strong>?<br>
                    <small class="text-muted">Data ini tidak dapat dikembalikan.</small></p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-dismiss="modal">Batal</button>
                    <form action="/resident/{{ $item->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Akun --}}
    @if ($item->user)
    <div class="modal fade" id="detailAccount{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold">Detail Akun</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body px-4 py-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Nama Akun</label>
                        <input type="text" class="form-control bg-light" value="{{ $item->user->name }}" readonly>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Email</label>
                        <input type="email" class="form-control bg-light" value="{{ $item->user->email }}" readonly>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection
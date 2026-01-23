@extends('layout.app')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Penduduk</h1>
    <a href="/resident/create" class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
    </a>
</div>

<div class="row">
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>JK</th>
                                <th>TTL</th>
                                <th>Alamat</th>
                                <th>Agama</th>
                                <th>Status</th>
                                <th>Pekerjaan</th>
                                <th>Telepon</th>
                                <th>Status Penduduk</th>
                                <th width="160">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse ($residents as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->gender }}</td>
                                <td>{{ $item->birth_place }}, {{ $item->birth_date }}</td>
                                <td>{{ $item->address }}</td>
                                <td>{{ $item->religion }}</td>
                                <td>{{ $item->marital_status }}</td>
                                <td>{{ $item->occupation }}</td>
                                <td>{{ $item->phone }}</td>
                                <td class="text-center">
                                    <span class="badge badge-success">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('resident.edit', $item->id) }}"
                                           class="btn btn-sm btn-outline-warning"
                                           title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-toggle="modal"
                                            data-target="#confirmationDelete{{ $item->id }}"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        @if ($item->user)
                                            <button type="button"
                                                class="btn btn-sm btn-outline-info"
                                                data-toggle="modal"
                                                data-target="#detailAccount{{ $item->id }}"
                                                title="Lihat Akun">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 text-muted">
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

{{-- MODAL DELETE & DETAIL AKUN --}}
@foreach ($residents as $item)

{{-- Modal Delete --}}
<div class="modal fade" id="confirmationDelete{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle text-danger fa-2x mb-3"></i>
                <p>
                    Apakah Anda yakin ingin menghapus data penduduk ini?
                    <br>
                    <small class="text-muted">Data tidak dapat dikembalikan.</small>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    Batal
                </button>

                <form action="/resident/{{ $item->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt"></i> Ya, Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Modal Detail Akun --}}
@if ($item->user)
<div class="modal fade" id="detailAccount{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Akun</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Akun</label>
                    <input type="text" class="form-control" value="{{ $item->user->name }}" readonly>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" value="{{ $item->user->email }}" readonly>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
@endif

@endforeach
@endsection

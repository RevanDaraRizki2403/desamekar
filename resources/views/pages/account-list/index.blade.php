@extends('layout.app')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Akun Penduduk</h1>
</div>

{{-- SweetAlert2 untuk notifikasi sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        title: "Berhasil!",
        text: "{{ session('success') }}",
        icon: "success"
    });
</script>
@endif

<div class="card shadow border-0">
    <div class="card-body p-0"> {{-- p-0 agar tabel terlihat lebih modern --}}
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr class="text-center text-dark">
                        <th width="50px">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th width="200px">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($users as $item)
                    <tr>
                        {{-- PERBAIKAN: Penomoran otomatis yang mendukung pagination --}}
                        <td class="text-center font-weight-bold">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td class="text-center">
                            @if ($item->status == 'approved')
                                <span class="badge badge-success px-3 py-2">Aktif</span>
                            @else
                                <span class="badge badge-danger px-3 py-2">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center" style="gap:10px;">
                                @if ($item->status == 'approved')
                                    <button type="button" 
                                        class="btn btn-sm btn-outline-danger shadow-sm rounded-pill px-3" 
                                        data-toggle="modal" 
                                        data-target="#reject{{ $item->id }}">
                                        Non-Aktifkan
                                    </button>
                                @else
                                    <button type="button" 
                                        class="btn btn-sm btn-outline-success shadow-sm rounded-pill px-3" 
                                        data-toggle="modal" 
                                        data-target="#approve{{ $item->id }}">
                                        Aktifkan
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted small">Tidak ada data akun penduduk.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- TOMBOL PAGINATION: Muncul di bagian bawah kartu --}}
        <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- --- AREA MODAL --- --}}
@foreach ($users as $item)
    {{-- Modal Mengaktifkan Akun --}}
    <div class="modal fade" id="approve{{ $item->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('account.aktifkan', $item->id) }}" class="w-100">
                @csrf
                <input type="hidden" name="for" value="activate">

                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Aktifkan Akun</h5>
                        <button class="close text-white" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <p class="mb-0">Aktifkan kembali akun <strong>{{ $item->name }}</strong>?</p>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm px-4" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-sm px-4">Aktifkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Menonaktifkan akun --}}
    <div class="modal fade" id="reject{{ $item->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('account.nonaktif', $item->id) }}" class="w-100">
                @csrf
                <input type="hidden" name="for" value="deactivate">

                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Nonaktifkan Akun</h5>
                        <button class="close text-white" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <p class="mb-0 text-danger font-weight-bold">Perhatian!</p>
                        <p class="mb-0 small">Nonaktifkan akun <strong>{{ $item->name }}</strong>?</p>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm px-4" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-sm px-4">Nonaktifkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

@endsection
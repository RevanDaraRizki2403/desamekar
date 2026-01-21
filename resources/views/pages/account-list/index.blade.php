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

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center">
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
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td class="text-center">
                            @if ($item->status == 'approved')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            {{-- Tombol diletakkan di dalam <td> aksi agar sejajar --}}
                            <div class="d-flex justify-content-center" style="gap:10px;">
                                        @if ($item->status == 'approved')
                                        <button type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    data-toggle="modal" 
                                    data-target="#reject{{ $item->id }}">
                                    Non-Aktifkan
                                </button>
                                    @else
                                        <button type="button" 
                                    class="btn btn-sm btn-outline-success" 
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
                        <td colspan="5" class="text-center pt-3">Tidak ada permintaan akun</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- --- AREA MODAL --- --}}
@foreach ($users as $item)

    {{-- Meaktifkan --}}
    <div class="modal fade" id="approve{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('account.aktifkan', $item->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aktifkan Akun</h5>
                    <button class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body text-center">
                    Aktifkan kembali akun <strong>{{ $item->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-success">Aktifkan</button>
                </div>
            </div>
        </form>
    </div>
</div>


    {{-- Menonaktifkan akun --}}
    <div class="modal fade" id="reject{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('account.nonaktif', $item->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nonaktifkan Akun</h5>
                    <button class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body text-center">
                    Nonaktifkan akun <strong>{{ $item->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-danger">Nonaktifkan</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endforeach

@endsection
@extends('layout.app')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Permintaan Akun</h1>
</div>

{{-- SweetAlert2 Notifikasi --}}
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
                        <th width="200px">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                {{-- Tombol Aksi berjejer ke samping --}}
                                <div class="d-flex justify-content-center" style="gap:10px;">
                                    <button class="btn btn-success btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#approve{{ $item->id }}">
                                        Setujui
                                    </button>

                                    <button class="btn btn-danger btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#reject{{ $item->id }}">
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center pt-3">Tidak ada permintaan akun</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- --- AREA MODAL --- --}}
@foreach ($users as $item)

    {{-- MODAL APPROVE --}}
    <div class="modal fade" id="approve{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('account.approval', $item->id) }}">
                @csrf
                <input type="hidden" name="for" value="approve">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Setujui Akun</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        Apakah Anda yakin ingin menyetujui akun <strong>{{ $item->name }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ya, Setujui!</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL REJECT --}}
    <div class="modal fade" id="reject{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('account.approval', $item->id) }}">
                @csrf
                <input type="hidden" name="for" value="reject">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Akun</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        Apakah Anda yakin ingin menolak akun <strong>{{ $item->name }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Tolak!</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endforeach

@endsection
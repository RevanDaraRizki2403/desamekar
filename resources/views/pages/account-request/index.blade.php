@extends('layout.app')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Permintaan Akun</h1>
</div>

{{-- SweetAlert2 Notifikasi --}}
@if (session('success'))
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
                        <th width="50">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td class="text-center">
                                <button type="button"
                                    class="btn btn-success btn-sm"
                                    data-toggle="modal"
                                    data-target="#approve{{ $item->id }}">
                                    Setujui
                                </button>

                                <button type="button"
                                    class="btn btn-danger btn-sm"
                                    data-toggle="modal"
                                    data-target="#reject{{ $item->id }}">
                                    Tolak
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada permintaan akun</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= MODAL ================= --}}
@foreach ($users as $item)

{{-- MODAL APPROVE --}}
<div class="modal fade" id="approve{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('account.approval', $item->id) }}">
            @csrf
            <input type="hidden" name="for" value="approve">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Akun</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
    <p>
        Apakah Anda yakin akan menyetujui akun ini?
    </p>

    <div class="form-group mt-3">
        <label for="resident_id_{{ $item->id }}">
            Pilih Penduduk
        </label>

        <select name="resident_id"
                id="resident_id_{{ $item->id }}"
                class="form-control">
            <option value="">
                -- Pilih Penduduk --
            </option>

            @foreach ($residents as $res)
                <option value="{{ $res->id }}">
                    {{ $res->name }} ({{ $res->nik }})
                </option>
            @endforeach
        </select>
    </div>
</div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        Ya, Setujui!
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL REJECT --}}
<div class="modal fade" id="reject{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('account.approval', $item->id) }}">
            @csrf
            <input type="hidden" name="for" value="reject">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Akun</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    Apakah Anda yakin ingin menolak akun
                    <strong>{{ $item->name }}</strong>?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Ya, Tolak!
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endforeach

@endsection

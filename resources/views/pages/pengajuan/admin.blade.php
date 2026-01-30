@extends('layout.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Pengajuan Dokumen</h1>
</div>

<div class="card shadow mb-4 border-0">
    <div class="card-header py-3 bg-white">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Berkas Masuk</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Nama Warga</th>
                        <th>Dokumen</th>
                        <th>Status</th>
                        <th>Catatan Admin</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td class="font-weight-bold">{{ $item->resident->nama ?? 'N/A' }}</td>
                        <td>{{ $item->jenis_dokumen }}</td>
                        <td>
                            <span class="badge badge-{{ $item->status == 'diajukan' ? 'primary' : ($item->status == 'diproses' ? 'warning' : 'success') }} px-3 py-2">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td><small>{{ $item->catatan_admin ?? '-' }}</small></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                {{-- Tombol Respon --}}
                                <button class="btn btn-sm btn-primary rounded-pill px-3 mr-2" data-toggle="modal" data-target="#updateModal{{ $item->id }}">
                                    <i class="fas fa-reply mr-1"></i> Respon
                                </button>

                                {{-- Fitur Delete --}}
                                <form action="{{ route('pengajuan.destroy', $item->id) }}" method="POST" class="form-hapus">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 btn-delete">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $data->links() }}</div>
    </div>
</div>

{{-- MODAL RESPON ADMIN --}}
@foreach ($data as $item)
<div class="modal fade" id="updateModal{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('pengajuan.update_status', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Update Status: {{ $item->resident->nama ?? '' }}</h5>
                    <button class="close text-white" type="button" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Ubah Status</label>
                        <select name="status" class="form-control">
                            <option value="diajukan" {{ $item->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                            <option value="diproses" {{ $item->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Balas / Catatan untuk Warga</label>
                        <textarea name="catatan_admin" class="form-control" rows="3">{{ $item->catatan_admin }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Upload Dokumen Hasil</label>
                        <input type="file" name="file_hasil" class="form-control-file">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary btn-sm rounded-pill" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4">Kirim Balasan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- Script SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.form-hapus');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pengajuan ini akan dihapus permanen dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif
</script>
@endsection
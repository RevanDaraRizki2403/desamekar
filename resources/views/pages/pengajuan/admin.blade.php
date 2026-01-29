@extends('layout.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Pengajuan Dokumen</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Pengajuan Masuk</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Warga</th>
                        <th>Jenis Dokumen</th>
                        <th>Keperluan</th>
                        <th>File Pendukung</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td>
                            @if($item->resident)
                                <strong>{{ $item->resident->nama }}</strong>
                            @else
                                <span class="text-danger italic small">ID Penduduk: {{ $item->resident_id }} (Tidak ditemukan)</span>
                            @endif
                        </td>
                        <td>{{ $item->jenis_dokumen }}</td>
                        <td>{{ $item->keperluan }}</td>
                        <td class="text-center">
                            @if($item->file_pendukung)
                                <a href="{{ asset('storage/' . $item->file_pendukung) }}" target="_blank" class="btn btn-info btn-circle btn-sm">
                                    <i class="fas fa-file-download"></i>
                                </a>
                            @else
                                <span class="badge badge-secondary">Tidak ada file</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'diajukan')
                                <span class="badge badge-primary">Diajukan</span>
                            @elseif($item->status == 'diproses')
                                <span class="badge badge-warning">Diproses</span>
                            @else
                                <span class="badge badge-success">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#updateModal{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form action="{{ route('pengajuan.destroy', $item->id) }}" method="POST" class="form-hapus">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL SECTION - Ditaruh di luar tabel agar tidak berkedip/conflict saat render --}}
@foreach ($data as $item)
<div class="modal fade" id="updateModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitle{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('pengajuan.update_status', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Update: {{ $item->resident->nama ?? 'Warga' }}</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Status Berkas</label>
                        <select name="status" class="form-control">
                            <option value="diajukan" {{ $item->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                            <option value="diproses" {{ $item->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Catatan Admin</label>
                        <textarea name="catatan_admin" class="form-control" rows="3" placeholder="Tambahkan keterangan...">{{ $item->catatan_admin }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Upload Dokumen Hasil (PDF/JPG)</label>
                        <input type="file" name="file_hasil" class="form-control-file">
                        @if($item->file_hasil)
                            <div class="mt-2 py-1 px-2 bg-light rounded">
                                <small class="text-success"><i class="fas fa-check-circle"></i> Dokumen saat ini sudah tersedia.</small>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- SCRIPT SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfirmasi Hapus
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.form-hapus');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pengajuan ini akan dihapus permanen!",
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

    // Notifikasi Sukses
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
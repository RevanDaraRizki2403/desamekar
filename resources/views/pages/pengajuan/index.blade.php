@extends('layout.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Riwayat Pengajuan Saya</h1>
    <a href="{{ route('pengajuan.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="fas fa-plus fa-sm mr-1"></i> Ajukan Dokumen
    </a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis Dokumen</th>
                        <th>Status</th>
                        <th>Hasil / Unduh</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                    <tr>
                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>{{ $item->jenis_dokumen }}</td>
                        <td>
                            <span class="badge badge-{{ $item->status == 'diajukan' ? 'primary' : ($item->status == 'diproses' ? 'warning' : 'success') }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td>
                            @if($item->file_hasil)
                            <a href="{{ asset('storage/' . $item->file_hasil) }}" target="_blank" class="btn btn-success btn-sm rounded-pill">
                                <i class="fas fa-download mr-1"></i> Unduh
                            </a>
                            @else
                            <span class="text-muted small italic">Menunggu respon admin...</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- Hanya izinkan hapus jika status masih 'diajukan' (opsional) --}}
                            <form action="{{ route('pengajuan.destroy', $item->id) }}" method="POST" class="form-hapus-warga">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-circle btn-delete-warga" title="Hapus Pengajuan">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat pengajuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $data->links() }}</div>
    </div>
</div>

{{-- Script SweetAlert2 untuk Konfirmasi --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-delete-warga').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.form-hapus-warga');
            Swal.fire({
                title: 'Hapus riwayat?',
                text: "Data pengajuan Anda akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    {{-- Alert Berhasil Simpan/Hapus --}}
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    @endif
</script>
@endsection
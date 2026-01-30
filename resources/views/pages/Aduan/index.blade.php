@extends('layout.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ auth()->user()->role_id == 1 ? 'Manajemen Pengaduan Warga' : 'Daftar Pengaduan Saya' }}</h1>
    
    @if (isset(auth()->user()->resident))
    <a href="{{ route('Aduan.create') }}" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Buat Aduan 
    </a>
    @endif
</div>

<div class="row">
    <div class="col">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list-alt mr-2"></i>Laporan Masuk
                </h6>
            </div>
            <div class="card-body p-0"> {{-- p-0 agar tabel rapat ke pinggir --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-dark">
                            <tr class="text-center">
                                <th width="50">No</th>
                                <th class="text-left">Nama Pelapor</th>
                                <th class="text-left">Detail Laporan</th>
                                <th width="150">Tanggal</th>
                                <th width="100">Foto</th>
                                <th width="250">Status & Respon</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($aduan as $item)
                            <tr>
                                {{-- Menggunakan firstItem agar nomor urut berlanjut di halaman berikutnya --}}
                                <td class="text-center">{{ $loop->iteration + ($aduan->firstItem() - 1) }}</td>
                                
                                <td class="text-left small">
                                    <div class="font-weight-bold text-dark">{{ $item->resident->name ?? 'Anonim' }}</div>
                                    <div class="text-muted">NIK: {{ $item->resident->nik ?? '-' }}</div>
                                </td>

                                <td class="text-left small">
                                    <div class="font-weight-bold text-dark">{{ $item->title }}</div>
                                    <div class="text-muted text-truncate" style="max-width: 200px;">{{ $item->content }}</div>
                                </td>

                                <td class="text-center small text-muted">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::parse($item->report_date)->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    @if($item->photo_proof)
                                        <a href="{{ asset('storage/' . $item->photo_proof) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $item->photo_proof) }}" width="40" class="rounded shadow-sm">
                                        </a>
                                    @else
                                        <span class="text-muted small">No Image</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if (auth()->user()->role_id == 1)
                                        <form action="/aduan/update-status/{{ $item->id }}" method="post" class="px-2">
                                            @csrf
                                            <textarea name="response_admin" class="form-control form-control-sm mb-1" placeholder="Respon..." rows="1">{{ $item->response_admin }}</textarea>
                                            <div class="input-group input-group-sm">
                                                <select name="status" class="custom-select custom-select-sm">
                                                    <option value="new" @selected($item->status == 'new')>Baru</option>
                                                    <option value="processing" @selected($item->status == 'processing')>Proses</option>
                                                    <option value="completed" @selected($item->status == 'completed')>Selesai</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <span class="badge badge-{{ $item->status == 'completed' ? 'success' : ($item->status == 'processing' ? 'info' : 'warning') }} px-3 py-2">
                                            {{ $item->status_label }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <form action="{{ route('Aduan.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm px-3 shadow-sm" onclick="confirmDelete({{ $item->id }}, '{{ $item->title }}')">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted small">Belum ada pengaduan masuk.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION LINKS --}}
                <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                    {{ $aduan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT SWEETALERT & DELETE LOGIC --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id, title) {
    Swal.fire({
        title: 'Hapus Laporan?',
        text: "Anda akan menghapus laporan '" + title + "'.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74a3b',
        cancelButtonColor: '#858796',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 2500,
        showConfirmButton: false
    });
</script>
@endif
@endsection
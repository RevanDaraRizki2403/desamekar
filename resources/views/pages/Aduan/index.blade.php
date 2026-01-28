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

{{-- Alert Berhasil --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-lg mr-3"></i>
            <div>
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <div class="col">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list-alt mr-2"></i>Laporan Masuk
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-bottom">
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
                                <td class="text-center">{{ $loop->iteration + ($aduan->firstItem() - 1) }}</td>
                                
                                {{-- NAMA PELAPOR --}}
                                <td class="text-left">
                                    <div class="font-weight-bold text-dark">{{ $item->resident->name ?? 'Anonim' }}</div>
                                    <small class="text-muted">NIK: {{ $item->resident->nik ?? '-' }}</small>
                                </td>

                                {{-- DETAIL LAPORAN --}}
                                <td class="text-left">
                                    <div class="font-weight-bold text-dark">{{ $item->title }}</div>
                                    <small class="text-muted">{{ Str::limit($item->content, 50) }}</small>
                                </td>

                                {{-- TANGGAL --}}
                                <td class="text-center small text-muted">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::parse($item->report_date)->format('d M Y') }}
                                </td>

                                {{-- FOTO --}}
                                <td class="text-center">
                                    @if($item->photo_proof)
                                        <a href="{{ asset('storage/' . $item->photo_proof) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $item->photo_proof) }}" width="50" class="rounded shadow-sm img-thumbnail">
                                        </a>
                                    @else
                                        <span class="text-muted small">No Image</span>
                                    @endif
                                </td>

                                {{-- STATUS & RESPON ADMIN --}}
                                <td class="text-center">
                                    @if (auth()->user()->role_id == 1)
                                        {{-- KHUSUS ADMIN: Bisa isi respon dan ganti status --}}
                                        <form action="/aduan/update-status/{{ $item->id }}" method="post">
                                            @csrf
                                            <textarea name="response_admin" class="form-control form-control-sm mb-2 shadow-sm" placeholder="Berikan tanggapan..." rows="2">{{ $item->response_admin }}</textarea>
                                            
                                            <select name="status" class="form-control form-control-sm font-weight-bold border-primary shadow-sm mb-2">
                                                @foreach ([
                                                    (object) ['label'=> 'Baru', 'value'=> 'new'],
                                                    (object) ['label'=> 'Sedang Diproses', 'value'=> 'processing'],
                                                    (object) ['label'=> 'Selesai', 'value'=> 'completed']
                                                ] as $status)
                                                    <option value="{{ $status->value }}" @selected($item->status == $status->value)>
                                                        {{ $status->label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm btn-block shadow-sm">Simpan</button>
                                        </form>
                                    @else
                                        {{-- KHUSUS WARGA: Hanya lihat badge dan tanggapan admin --}}
                                        @php
                                            $badgeColor = match ($item->status) {
                                                'new'        => 'warning',
                                                'processing' => 'info',
                                                'completed'  => 'success',
                                                default      => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $badgeColor }} px-3 py-2 shadow-sm mb-2">
                                            {{ $item->status_label }}
                                        </span>

                                        @if($item->response_admin)
                                            <div class="mt-2 p-2 bg-light border-left-info rounded small text-left shadow-sm">
                                                <strong class="text-info"><i class="fas fa-reply"></i> Respon:</strong><br>
                                                {{ $item->response_admin }}
                                            </div>
                                        @endif
                                    @endif
                                </td>

                                {{-- AKSI HAPUS --}}
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger shadow-sm rounded-circle" data-toggle="modal" data-target="#deleteModal{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <h6 class="text-muted">Belum ada pengaduan masuk.</h6>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 d-flex justify-content-center">
                    {{ $aduan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DELETE --}}
@foreach($aduan as $item)
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold">Konfirmasi Hapus</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                <p class="mb-0">Yakin ingin menghapus laporan <strong>"{{ $item->title }}"</strong>?</p>
                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary rounded-pill" data-dismiss="modal">Batal</button>
                <form action="{{ route('Aduan.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
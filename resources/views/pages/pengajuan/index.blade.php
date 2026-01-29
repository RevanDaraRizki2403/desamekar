@extends('layout.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Riwayat Pengajuan Dokumen</h1>
    <a href="{{ route('pengajuan.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Ajukan Dokumen Baru
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Jenis Dokumen</th>
                        <th>Keperluan</th>
                        <th>Status</th>
                        <th>Catatan Admin</th>
                        <th>Dokumen Hasil</th> {{-- Kolom Baru --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td>{{ $item->jenis_dokumen }}</td>
                        <td>{{ $item->keperluan }}</td>
                        <td>
                            @if($item->status == 'diajukan')
                                <span class="badge badge-primary">Diajukan</span>
                            @elseif($item->status == 'diproses')
                                <span class="badge badge-warning">Diproses</span>
                            @else
                                <span class="badge badge-success">Selesai</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $item->catatan_admin ?? '-' }}</small></td>
                        <td class="text-center">
                            {{-- PENAMBAHAN: Logika untuk memunculkan tombol download --}}
                            @if($item->file_hasil)
                                <a href="{{ asset('storage/' . $item->file_hasil) }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> Unduh Dokumen
                                </a>
                            @else
                                <span class="text-muted small">Belum tersedia</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
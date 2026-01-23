@foreach ($residents as $item)

<tr>
    <td>{{ $item->name }}</td>
    <td>
        <!-- Tombol -->
        <button type="button"
                class="btn btn-sm btn-info"
                data-toggle="modal"
                data-target="#detailAccount{{ $item->id }}">
            <i class="fas fa-eye"></i> Lihat Akun
        </button>
    </td>
</tr>

{{-- Modal --}}
@if ($item->user)
<div class="modal fade" id="detailAccount{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Akun</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Akun</label>
                    <input type="text" class="form-control"
                           value="{{ $item->user->name }}" readonly>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control"
                           value="{{ $item->user->email }}" readonly>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary btn-sm"
                        data-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
@endif

@endforeach

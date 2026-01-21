<div class="modal fade" id="confirmationReject-{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('account.approval', $item->id) }}" method="POST">
            @csrf
            <input type="hidden" name="for" value="reject">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Ditolak</h5>
                    <button class="close" type="button" data-dismiss="modal">
                        <span>×</span>
                    </button>
                </div>

                <div class="modal-body">
                    Apakah anda yakin akan menolak akun
                    <strong>{{ $item->name }}</strong>?
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Ya, Tolak!</button>
                </div>
            </div>
        </form>
    </div>
</div>

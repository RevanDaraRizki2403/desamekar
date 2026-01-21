<div class="modal fade" id="confirmationApprove-{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('account.approval', $item->id) }}" method="POST">
            @csrf
            <input type="hidden" name="for" value="approve">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Setujui</h5>
                    <button class="close" type="button" data-dismiss="modal">
                        <span>×</span>
                    </button>
                </div>

                <div class="modal-body">
                    Apakah anda yakin akan menyetujui akun
                    <strong>{{ $item->name }}</strong>?
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Ya, Setujui!</button>
                </div>
            </div>
        </form>
    </div>
</div>

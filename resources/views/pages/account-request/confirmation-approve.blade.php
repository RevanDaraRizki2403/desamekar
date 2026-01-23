{{-- MODAL APPROVE --}}
<div class="modal fade" id="approve{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('account.approval', $item->id) }}">
            @csrf
            <input type="hidden" name="for" value="approve">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Akun</h5>
                    <button class="close" type="button" data-dismiss="modal">
                        <span>×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>
                        Apakah Anda yakin ingin menyetujui akun
                        <strong>{{ $item->name }}</strong>?
                    </p>

                    {{-- DROPDOWN PENDUDUK --}}
                    <div class="form-group mt-3 text-left">
                        <label for="resident_id_{{ $item->id }}">
                            Hubungkan Data Penduduk (Opsional)
                        </label>

                        <select name="resident_id"
                                id="resident_id_{{ $item->id }}"
                                class="form-control">
                            <option value="">
                                -- Tidak Ada / Jangan Hubungkan --
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

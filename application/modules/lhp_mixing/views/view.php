<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_divisi">ID Divisi</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_divisi) ? $id_divisi : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_packaging">Nama Divisi</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($divisi) ? $divisi->divisi : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
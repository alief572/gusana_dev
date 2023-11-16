<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_proses">ID Proses</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_proses) ? $id_proses : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_packaging">Nama Proses</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($proses) ? $proses->nm_proses : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
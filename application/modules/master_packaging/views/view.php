<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_packaging">ID Packaging</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_packaging) ? $id_packaging : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_packaging">Packaging Name </label>
                </div>
                <div class="col-md-8">:
                    <?= isset($packaging) ? $packaging->nm_packaging : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
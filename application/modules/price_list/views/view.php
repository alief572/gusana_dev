<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_unit">ID Unit</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_unit) ? $id_unit : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_packaging">Unit</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($unit) ? $unit->nm_unit : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
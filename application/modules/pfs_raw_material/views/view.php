<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_departemen">ID Departemen</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_departemen) ? $id_departemen : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_departemen">Departemen </label>
                </div>
                <div class="col-md-8">:
                    <?= isset($departemen) ? $departemen->nm_departemen : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
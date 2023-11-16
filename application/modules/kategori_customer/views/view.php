<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_kategori_customer">ID Kategori Customer</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_kategori_customer) ? $id_kategori_customer : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_packaging">Kategori Customer</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($kategori_customer) ? $kategori_customer->nm_kategori_customer : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_product_type">ID Product Type </label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_product_type) ? $id_product_type : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nama_product_tpye">Product Type</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($product_type) ? $product_type->nama : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nama_product_tpye">Status</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($status) ? $status : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
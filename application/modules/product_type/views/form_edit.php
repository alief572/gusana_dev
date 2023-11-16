<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="id_product_type">ID Product Type</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="id_product_type" name="id_product_type" readonly placeholder="Auto" value="<?= isset($id_product_type) ? $id_product_type : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="nama_product_type">Product Type</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="nama_product_type" name="nama_product_type" placeholder="Product Type" value="<?= isset($product_type) ? $product_type->nama : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="aktif">Status</label>
                </div>
                <div class="col-md-8">
                    <input type="radio" name="aktif" class="ml-2" id="" value="1" <?= ($product_type->aktif == "1") ? 'checked' : null ?>> Aktif
                    <input type="radio" name="aktif" class="ml-2" id="" value="0" <?= ($product_type->aktif == "0") ? 'checked' : null ?>> Non Aktif
                </div>
            </div>
        </div>
    </div>
</div>
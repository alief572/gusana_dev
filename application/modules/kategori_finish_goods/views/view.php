<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_kategori_customer">ID Kategori Finish Goods</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_kategori_finish_goods) ? $id_kategori_finish_goods : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_packaging">Kategori Finish Goods</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($kategori_finish_goods) ? $kategori_finish_goods->nm_kategori_finish_goods : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
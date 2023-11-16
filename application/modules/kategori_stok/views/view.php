<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_kategori_stok">ID Kategori Stok</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_kategori_stok) ? $id_kategori_stok : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_kategori_stok">Kategori Stok </label>
                </div>
                <div class="col-md-8">:
                    <?= isset($kategori_stok) ? $kategori_stok->nm_kategori_stok : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="deskripsi">Deskripsi</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($kategori_stok) ? $kategori_stok->deskripsi : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
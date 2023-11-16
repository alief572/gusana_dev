<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="id">ID Kategori Stok</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="id_kategori_stok" name="id_kategori_stok" readonly placeholder="Auto" value="<?= isset($id_kategori_stok) ? $id_kategori_stok : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="nm_kategori_stok">Kategori Stok</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="nm_kategori_stok" name="nm_kategori_stok" placeholder="Kategori Stok" value="<?= isset($kategori_stok) ? $kategori_stok->nm_kategori_stok : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="nm_kategori_stok">Deskripsi</label>
                </div>
                <div class="col-md-8">
                    <textarea name="deskripsi" id="" cols="30" rows="5" class="form-control form-control-sm"><?= isset($kategori_stok) ? $kategori_stok->deskripsi : null ?></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
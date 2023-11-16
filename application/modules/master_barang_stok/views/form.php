<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <input type="hidden" name="id_barang_stok" value="<?= isset($results['id_barang_stok']) ? $results['id_barang_stok'] : null; ?>">
                <input type="hidden" name="status" value="1">
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="id">Kategori Stok</label>
                </div>
                <div class="col-md-10">
                    <select name="kategori_stok" id="" class="form-control form-control-sm">
                        <option value="">- Kategori Stok -</option>
                        <?php foreach ($results['kategori_stok'] as $kategori_stok) : ?>
                            <option value="<?= $kategori_stok->id_kategori_stok ?>" <?= ($kategori_stok->id_kategori_stok == $results['barang_stok']->id_kategori_stok) ? 'selected' : null ?>> <?= $kategori_stok->nm_kategori_stok ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="id">Nama Barang Stok</label>
                </div>
                <div class="col-md-10">
                    <input type="text" name="nm_barang_stok" id="" class="form-control form-control-sm" value="<?= (isset($results['barang_stok'])) ? $results['barang_stok']->nm_barang_stok : null ?>" placeholder="Nama Barang Stok">
                </div>
            </div>
            <div class="form-group mt-2 row">

                <div class="col-md-2 tx-dark tx-bold">
                    <label for="">Item Code</label>
                </div>
                <div class="col-md-4">
                    <input type="text" name="item_code" id="" class="form-control form-control-sm" value="<?= (isset($results['barang_stok'])) ? $results['barang_stok']->item_code : null ?>" placeholder="Item Code">
                </div>
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="">Trade Name</label>
                </div>
                <div class="col-md-4">
                    <input type="text" name="trade_name" id="" class="form-control form-control-sm" value="<?= (isset($results['barang_stok'])) ? $results['barang_stok']->trade_name : null ?>" placeholder="Trade Name">
                </div>
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="">Brand</label>
                </div>
                <div class="col-md-4">
                    <input type="text" name="brand" id="" class="form-control form-control-sm" value="<?= (isset($results['barang_stok'])) ? $results['barang_stok']->brand : null ?>" placeholder="Brand">
                </div>
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="">Spesification</label>
                </div>
                <div class="col-md-4">
                    <input type="text" name="spek" id="" class="form-control form-control-sm" value="<?= (isset($results['barang_stok'])) ? $results['barang_stok']->spek : null ?>" placeholder="Spesification">
                </div>
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="">Packing Unit / Konversi</label>
                </div>
                <div class="col-md-2">
                    <select name="packaging" id="" class="form-control form-control-sm">
                        <option value="">- Packaging -</option>
                        <?php foreach ($results['packaging'] as $packaging) : ?>
                            <option value="<?= $packaging->id ?>" <?= ($packaging->id == $results['barang_stok']->id_packaging) ? 'selected' : null  ?>><?= $packaging->nm_packaging ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="konversi" id="" class="form-control form-control-sm" value="<?= (isset($results['barang_stok'])) ? $results['barang_stok']->konversi : null ?>" placeholder="Konversi">
                </div>
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="">Unit Measurement</label>
                </div>
                <div class="col-md-4">
                    <select name="unit" id="" class="form-control form-control-sm">
                        <option value="">- Unit -</option>
                        <?php foreach ($results['unit'] as $unit) : ?>
                            <option value="<?= $unit->id_unit ?>" <?= ($unit->id_unit == $results['barang_stok']->unit_id) ? 'selected' : null  ?>><?= $unit->nm_unit ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="">Maksimum Stok</label>
                </div>
                <div class="col-md-4">
                    <input type="number" name="max_stok" id="" class="form-control form-control-sm" value="<?= (isset($results['barang_stok'])) ? $results['barang_stok']->max_stok : null ?>" placeholder="Maksimal Stok">
                </div>
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="">Minimum Stok</label>
                </div>
                <div class="col-md-4">
                    <input type="number" name="min_stok" id="" class="form-control form-control-sm" value="<?= (isset($results['barang_stok'])) ? $results['barang_stok']->min_stok : null ?>" placeholder="Minimum Stok">
                </div>
            </div>
        </div>
    </div>
</div>
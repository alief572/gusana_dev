<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="id_inventory_level_1">ID Inventory Level 1</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="id_inventory_level_1" name="id_inventory_level_1" readonly placeholder="Auto" value="<?= isset($id_inventory_level_1) ? $id_inventory_level_1 : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="nama_inventory_level_1">Nama Inventory</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="nama_inventory_level_1" name="nama_inventory_level_1" placeholder="Nama Inventory" value="<?= isset($inventory) ? $inventory->nama : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="aktif">Aktif / Non Aktif</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control" name="aktif" id="aktif">
                        <option value="">Aktif/Non Aktif</option>
                        <option value="aktif" <?= (isset($inventory) && $inventory->aktif == "aktif") ? 'selected' : null ?>>Aktif</option>
                        <option value="nonaktif" <?= (isset($inventory) && $inventory->aktif == "nonaktif") ? 'selected' : null ?>>Non Aktif</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
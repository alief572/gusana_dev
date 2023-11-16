<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-12">
            <input type="hidden" class="form-control" id="id_inventory_level_1" name="id_inventory_level_1" readonly placeholder="Auto" value="<?= isset($id_inventory_level_1) ? $id_inventory_level_1 : null; ?>">
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="nama_inventory_level_1">Material Type</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="nama_inventory_level_1" name="nama_inventory_level_1" placeholder="Nama Inventory" value="<?= isset($inventory) ? $inventory->nama : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="aktif">Status</label>
                </div>
                <div class="col-md-8">
                    <input type="radio" name="aktif" class="ml-2" id="" value="aktif" <?= (isset($inventory) && $inventory->aktif == "aktif") ? 'checked' : null ?>> Aktif
                    <input type="radio" name="aktif" class="ml-2" id="" value="nonaktif" <?= (isset($inventory) && $inventory->aktif == "nonaktif") ? 'checked' : null ?>> Non-Aktif
                </div>
            </div>
        </div>
    </div>
</div>
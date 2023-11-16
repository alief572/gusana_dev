<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_inventory_level_1">ID Inventory Level 1 </label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_inventory_level_1) ? $id_inventory_level_1 : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nama_inventory_level_1">Nama Inventory Level 1</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($inventory) ? $inventory->nama : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nama_inventory_level_1">Status</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($status) ? $status : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
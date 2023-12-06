<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_warehouse">Warehouse Name </label>
                </div>
                <div class="col-md-8">:
                    <?= isset($warehouse) ? $warehouse->warehouse_nm : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_warehouse">Warehouse Type </label>
                </div>
                <div class="col-md-8">:
                    <?= isset($warehouse_type) ? $warehouse_type : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
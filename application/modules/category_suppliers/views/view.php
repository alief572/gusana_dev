<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_category">ID Kategori Supplier</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_category) ? $id_category : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_packaging">Kategori Supplier</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($category_suppliers) ? $category_suppliers->nm_category_supplier : null; ?>
                </div>
            </div>
        </div>
    </div>
</div>
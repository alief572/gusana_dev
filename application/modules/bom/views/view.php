<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>Product Master</th>
                    <td>:</td>
                    <td><?= $results['bom']->nm_product_master ?></td>
                    <th>Product Code</th>
                    <td>:</td>
                    <td><?= $results['bom']->variant ?></td>
                    <th>qty Hopper (Kg)</th>
                    <td>:</td>
                    <td><?= $results['bom']->qty_hopper; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <b>Detail Material</b>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Proses</th>
                        <th class="text-center">Material Category</th>
                        <th class="text-center">Weight (Kg)</th>
                    </tr>
                </thead>
                <tbody class="list_detail_material">
                    <?php
                    $n = 1;
                    foreach ($results['detail_material'] as $detail_material) :
                    ?>
                        <tr>
                            <td class="text-center"><?= $n ?></td>
                            <td class="text-center"><?= $detail_material->nm_proses ?></td>
                            <td class="text-center"><?= $detail_material->material_category ?></td>
                            <td class="text-center"><?= $detail_material->weight ?></td>
                        </tr>
                        <?php $n++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-2">
                    <label for="waste_product">Waste Product (%)</label>
                </div>
                <div class="col-md-3">
                    <input type="number" name="waste_product" id="waste_product" class="form-control form-control-sm" placeholder="Waste Product (%)" step="0.01" value="<?= $results['bom']->waste_product ?>" readonly>
                </div>
                <div class="col-md-12"></div>
                <div class="col-md-2">
                    <label for="waste_set_clean">Waste Setting/Cleaning (%)</label>
                </div>
                <div class="col-md-3">
                    <input type="number" name="waste_set_clean" id="waste_set_clean" class="form-control form-control-sm" placeholder="Waste Setting & Clean (%)" step="0.01" value="<?= $results['bom']->waste_set_clean ?>" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
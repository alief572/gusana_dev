<div class="card-body" id="dataForm">
    <input type="hidden" name="id_bom" class="id_bom" value="<?= $id_bom; ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-md-2">
                    <label for="product_master" class="tx-dark tx-bold">Product Master <span class="text-danger">(产品名称)</span></label>
                </div>
                <div class="col-md-2">
                    <select name="product_master" id="product_master" class="form-control form-control-sm select get_product_code">
                        <option value="">- Product Master -</option>
                        <?php foreach ($product_master as $item_master) : ?>
                            <option value="<?= $item_master->id_category3 ?>" <?= (isset($bom) && $item_master->id_category3 == $bom->id_product) ? 'selected' : null ?>><?= $item_master->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mt-2">
                    <label for="variant" class="tx-dark tx-bold">Product Code <span class="text-danger">(产品型号)</span></label>
                </div>
                <div class="col-md-2">
                    <input type="text" name="product_code" id="" class="form-control form-control-sm product_code" value="<?= (isset($bom)) ? $bom->variant : null ?>">
                </div>
                <div class="col-md-2 mt-2">
                    <label for="variant" class="tx-dark tx-bold">Lot Size <span class="text-danger">(生产数量)</span></label>
                </div>
                <div class="col-md-2">
                    <input type="number" name="qty_hopper" id="" class="form-control form-control-sm" step="0.01" value="<?= (isset($bom)) ? $bom->qty_hopper : null ?>">
                </div>
            </div>
            <div class="col-md-12">
                <b>Detail Material</b>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No <span class="text-danger">(不)</span></th>
                            <th class="text-center">Proses <span class="text-danger">(过程)</span></th>
                            <th class="text-center">Material Category <span class="text-danger">(材料分类)</span></th>
                            <th class="text-center">Weight (Kg) <span class="text-danger">(重量 (kg))</span></th>
                            <th class="text-center">Action <span class="text-danger">(操作)</span></th>
                        </tr>
                    </thead>
                    <tbody class="list_detail_material">
                        <?php $n = 1;
                        if (isset($detail_material)) : foreach ($detail_material as $list_material) : ?>
                                <tr>
                                    <td class="text-center"><?= $n ?></td>
                                    <td class="text-center"><?= $list_material->nm_proses ?></td>
                                    <td class="text-center"><?= $list_material->material_category ?></td>
                                    <td class="text-center"><?= $list_material->weight ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger del_material_detail" data-id="<?= $list_material->id ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php $n++; ?>
                        <?php endforeach;
                        endif; ?>
                    </tbody>
                    <tbody>
                        <td></td>
                        <td>
                            <select name="" id="" class="form-control form-control-sm add_material_type">
                                <option value="">- Proses -</option>
                                <?php foreach ($proses as $item_proses) : ?>
                                    <option value="<?= $item_proses->id_proses ?>"><?= $item_proses->nm_proses ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="" id="" class="form-control form-control-sm select add_material_category">
                                <option value="">- Material Category -</option>
                                <?php foreach ($material_category as $item_material_category) : ?>
                                    <option value="<?= $item_material_category->id_category1 ?>"><?= $item_material_category->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="" id="" class="form-control form-control-sm add_weight" step="0.01" placeholder="Weight (Kg)">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-success add_detail_material">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-2">
                        <label for="waste_product">Waste Product (%) <span class="text-danger">(废品 (kg))</span></label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="waste_product" id="waste_product" class="form-control form-control-sm" placeholder="Waste Product (%)" step="0.01" value="<?= (isset($bom)) ? $bom->waste_product : null ?>">
                    </div>
                    <div class="col-md-12"></div>
                    <div class="col-md-2">
                        <label for="waste_set_clean">Waste Setting/Cleaning (%) <span class="text-danger">(废物处理/清洁 (kg))</span></label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="waste_set_clean" id="waste_set_clean" class="form-control form-control-sm" placeholder="Waste Product (%)" step="0.01" value="<?= (isset($bom)) ? $bom->waste_set_clean : null ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function() {

        $('.select').select2({
            placeholder: "Choose one",
            allowClear: true,
            width: "100%",
            dropdownParent: $("#dataForm")
            // minimumResultsForSearch: -1
        });
    });
</script>
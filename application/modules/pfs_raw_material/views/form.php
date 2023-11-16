<div class="card-body" id="dataForm">
    <div class="row">
        <div class="col-md-12">
            <input type="hidden" name="id_category3" class="id_category3" value="<?= $results['inventory_4']->id_category3 ?>">
            <table class="table table-striped">
                <tr>
                    <th>Material Master</th>
                    <th colspan="3">
                        <input type="text" name="material_master" id="" class="form-control form-control-sm" value="<?= (isset($results['inventory_4'])) ? $results['inventory_4']->nama : null ?>" readonly>
                    </th>
                </tr>
                <tr>
                    <th>Lower Price Before</th>
                    <th>
                        <input type="number" name="lower_price_before" id="" class="form-control form-control-sm" step="0.01" value="<?= (isset($results['inventory_4'])) ? $results['inventory_4']->lower_price_before : null ?>" readonly>
                    </th>
                    <th>Higher Price Before</th>
                    <th>
                        <input type="number" name="higher_price_before" id="" class="form-control form-control-sm" step="0.01" value="<?= (isset($results['inventory_4'])) ? $results['inventory_4']->higher_price_before : null ?>" readonly>
                    </th>
                </tr>
                <tr>
                    <tr>
                        <th>Lower Price After</th>
                        <th>
                            <input type="number" name="lower_price_after" id="" class="form-control form-control-sm" value="<?= (isset($results['inventory_4'])) ? $results['inventory_4']->lower_price_after : null ?>">
                        </th>
                        <th>Higher Price After</th>
                        <th>
                            <input type="number" name="higher_price_after" id="" class="form-control form-control-sm" step="0.01" value="<?= (isset($results['inventory_4'])) ? $results['inventory_4']->higher_price_after : null ?>">
                        </th>
                    </tr>
                    <tr>
                        <th>Expired</th>
                        <th>
                            <select name="expired_time" id="" class="form-control form-control-sm">
                                <option value="">- Select An Expired -</option>
                                <option value="1" <?= (isset($results['inventory_4']) && $results['inventory_4']->expired_time == 1) ? 'selected' : null ?>>1 Bulan</option>
                                <option value="3" <?= (isset($results['inventory_4']) && $results['inventory_4']->expired_time == 3) ? 'selected' : null ?>>3 Bulan</option>
                                <option value="6" <?= (isset($results['inventory_4']) && $results['inventory_4']->expired_time == 6) ? 'selected' : null ?>>1 Semester</option>
                            </select>
                        </th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>File Evidence</th>
                        <th>
                            <input type="file" name="file_price_ref_evidence" id="" class="form-control form-control-sm">
                        </th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Note</th>
                        <th colspan="3">
                            <textarea name="note_price_ref" id="" cols="30" rows="5" class="form-control form-control-ms"><?= (isset($results['inventory_4'])) ? $results['inventory_4']->note_price_ref : null ?></textarea>
                        </th>
                    </tr>
                </tr>
            </table>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function() {
        $('.select').select2({
            placeholder: "Choose one",
            allowClear: true,
            width: "100%",
            dropdownParent: $("#dataForm"),
            minimumResultsForSearch: -1
        });
    });
</script>
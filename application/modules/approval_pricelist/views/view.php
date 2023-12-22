
<div class="card-body" id="dataForm">
    <input type="hidden" name="id_bom" value="<?= $get_data->id ?>">
    <div class="row">
        <div class="col-6">
            <label for="kode_produk">Kode Produk</label>
            <input type="text" name="" id="" class="form-control form-control-sm" value="<?= $get_data->variant ?>" readonly>
        </div>
        <div class="col-6">
            <label for="kode_produk">Product Costing / Kg</label>
            <input type="text" name="" id="" class="form-control form-control-sm text-right" value="<?= number_format(($get_data->cost_price_final / $get_data->qty_hopper), 2) ?>" readonly>
        </div>
        <div class="col-6">
            <label for="kode_produk">Nama Produk</label>
            <input type="text" name="" id="" class="form-control form-control-sm" value="<?= $get_data->nama ?>" readonly>
        </div>
        <div class="col-6">
            <label for="kode_produk">Propose Price</label>
            <input type="text" name="" id="" class="form-control form-control-sm text-right" value="<?= number_format($get_data->price_list, 2) ?>" readonly>
        </div>
        <div class="col-6">
            <label for="kode_produk">Lot Size</label>
            <input type="text" name="" id="" class="form-control form-control-sm text-right" value="<?= number_format($get_data->qty_hopper, 2) ?>" readonly>
        </div>
        <div class="col-6">
            <label for="kode_produk">Selisih</label>
            <input type="text" name="" id="" class="form-control form-control-sm text-right" value="<?= number_format(($get_data->price_list - ($get_data->cost_price_final / $get_data->qty_hopper)), 2) ?>" readonly>
        </div>
        <div class="col-6">
            <label for="kode_produk">Price Before</label>
            <input type="text" name="" id="" class="form-control form-control-sm text-right" value="<?= number_format($get_data->price_list, 2) ?>" readonly>
        </div>
        <div class="col-6">
            <label for="kode_produk">Persentase</label>
            <input type="text" name="" id="" class="form-control form-control-sm text-right" value="<?= number_format(($get_data->price_list/($get_data->cost_price_final / $get_data->qty_hopper)) * 100,2) ?>%" readonly>
        </div>
        <div class="col-6">
            <label for="">Price Approve</label>
            <input type="text" name="price_approve" id="" class="form-control form-control-sm text-right autonum" value="<?= $get_data->price_list ?>" readonly>
        </div>
        <div class="col-6">
            <label for="">Expired Date</label>
            <input type="date" name="expired_date" id="" class="form-control form-control-sm autonum" value="<?= $get_data->expired_date ?>" readonly>
        </div>
        <div class="col-12">
            <label for="">Action</label>
            <select name="approval_action" id="" class="form-control form-control-sm" disabled>
                <option value="1" <?= ($get_data->sts_price_list == 1) ? 'selected' : null ?>>Approve</option>
                <option value="2" <?= ($get_data->sts_price_list == 2) ? 'selected' : null ?>>Reject</option>
            </select>
        </div>
        <div class="col-12">
            <label for="">Reason</label>
            <textarea name="reason" id="" class="form-control form-control-sm" cols="30" rows="5" readonly><?= $get_data->reason ?></textarea>
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

        $('.autonum').autoNumeric();
    });
</script>
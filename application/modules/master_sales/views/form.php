<div class="card-body" id="dataForm">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="">ID Sales</label>
                <input type="text" name="id_sales" id="" class="form-control form-control-sm" value="<?= $data_sales->id ?>" readonly>
            </div>
            <div class="form-group">
                <label for="">Nama Sales</label>
                <input type="text" name="nama_sales" id="" class="form-control form-control-sm" value="<?= $data_sales->name ?>" readonly>
            </div>
            <div class="form-group">
                <label for="">Kode Sales</label>
                <input type="text" name="kode_sales" id="" class="form-control form-control-sm" value="<?= $data_sales->kode_sales ?>">
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
            dropdownParent: $("#dataForm"),
            minimumResultsForSearch: -1
        });
    });
</script>
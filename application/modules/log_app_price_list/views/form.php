<div class="card-body" id="dataForm">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="id_divisi" class="tx-dark tx-bold">ID Divisi<span class="tx-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" id="id_divisi" readonly name="id_divisi" value="<?= (isset($id_divisi)) ? $id_divisi : null; ?>" maxlength="10" placeholder="ID Divisi">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="nama_divisi" class="tx-dark tx-bold">Nama Divisi</label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" value="<?= (isset($divisi)) ? $divisi->divisi : null; ?>" placeholder="Nama Divisi">
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
            dropdownParent: $("#dataForm"),
            minimumResultsForSearch: -1
        });
    });
</script>
<div class="card-body" id="dataForm">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="id_departemen" class="tx-dark tx-bold">ID Departemen</label>
                </div>
                <div class="col-md-12">
                    <input type="text" class="form-control" id="id" name="id_departemen" value="<?= (isset($id_departemen)) ? $id_departemen : null; ?>" readonly>
                </div>
                <div class="col-md-4 mt-2">
                    <label for="nm_departemen" class="tx-dark tx-bold">Nama Departemen</label>
                </div>
                <div class="col-md-12">
                    <input type="text" class="form-control" id="nm_departemen" name="nm_departemen" value="<?= (isset($departemen->nm_departemen)) ? $departemen->nm_departemen : null; ?>">
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
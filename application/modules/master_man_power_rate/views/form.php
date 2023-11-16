<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="id_komp">ID Komponen</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="id_komp" name="id_komp" readonly placeholder="Auto" value="<?= isset($id_komp) ? $id_komp : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="nm_komp">Nama Komponen</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="nm_komp" name="nm_komp" placeholder="Nama Komponen" value="<?= isset($komp) ? $komp->nm_komp : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="std_val">Nilai Standard</label>
                </div>
                <div class="col-md-8">
                    <input type="number" class="form-control" id="std_val" name="std_val" placeholder="Nilai Standard" step="0.01" value="<?= isset($komp) ? $komp->std_val : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="tipe">Tipe Komponen</label>
                </div>
                <div class="col-md-8">
                    <select name="tipe" id="" class="form-control">
                        <option value="">- Tipe Komponen</option>
                        <option value="1" <?= (isset($komp) && $komp->tipe == "1") ? 'selected' : null ?>>Salary Direct Man Power</option>
                        <option value="2" <?= (isset($komp) && $komp->tipe == "2") ? 'selected' : null ?>>BPJS</option>
                        <option value="3" <?= (isset($komp) && $komp->tipe == "3") ? 'selected' : null ?>>Biaya Lain-lain</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="tipe">Keterangan</label>
                </div>
                <div class="col-md-8">
                    <textarea name="keterangan" class="form-control" id="" cols="30" rows="5"><?= (isset($komp)) ? $komp->keterangan : null ?></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        // $('.select').select2({
        //     // minimumResultsForSearch: -1,
        //     placeholder: 'Choose one',
        //     dropdownParent: $('#dialog-popup'),
        //     width: "100%",
        //     allowClear: true
        // });

        // $('.select.not-search').select2({
        //     minimumResultsForSearch: -1,
        //     placeholder: 'Choose one',
        //     dropdownParent: $('#dialog-popup'),
        //     width: "100%",
        //     allowClear: true
        // });

        // $(document).on('change', '#country_id', function() {
        //     let country_id = $('#country_id').val();
        //     $('#state_id').val('null').trigger('change')
        //     $('#city_id').val('null').trigger('change')
        //     $('#state_id').select2({
        //         ajax: {
        //             url: siteurl + thisController + 'getProvince',
        //             dataType: 'JSON',
        //             type: 'GET',
        //             delay: 100,
        //             data: function(params) {
        //                 return {
        //                     q: params.term, // search term
        //                     country_id: country_id, // search term
        //                 };
        //             },
        //             processResults: function(res) {
        //                 return {
        //                     results: $.map(res, function(item) {
        //                         return {
        //                             id: item.id,
        //                             text: item.name
        //                         }
        //                     })
        //                 };
        //             }
        //         },
        //         cache: true,
        //         placeholder: 'Choose one',
        //         dropdownParent: $('#dialog-popup'),
        //         width: "100%",
        //         allowClear: true
        //     })
        // });

        // $(document).on('change.select2', '#state_id', function() {
        //     let state_id = $('#state_id').val();
        //     $('#city_id').val('null').trigger('change')
        //     $('#city_id').select2({
        //         ajax: {
        //             url: siteurl + thisController + 'getCities',
        //             dataType: 'JSON',
        //             type: 'GET',
        //             delay: 100,
        //             data: function(params) {
        //                 return {
        //                     q: params.term, // search term
        //                     state_id: state_id, // search term
        //                 };
        //             },
        //             processResults: function(res) {
        //                 return {
        //                     results: $.map(res, function(item) {
        //                         return {
        //                             id: item.id,
        //                             text: item.name
        //                         }
        //                     })
        //                 };
        //             }
        //         },
        //         cache: true,
        //         placeholder: 'Choose one',
        //         dropdownParent: $('#dialog-popup'),
        //         width: "100%",
        //         allowClear: true
        //     })


        // });
    })
</script>
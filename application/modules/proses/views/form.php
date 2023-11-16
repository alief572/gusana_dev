<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="id_proses">ID Proses</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="id_proses" name="id_proses" readonly placeholder="Auto" value="<?= isset($id_proses) ? $id_proses : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="nm_proses">Nama Proses</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="nm_proses" name="nm_proses" placeholder="Nama Proses" value="<?= isset($proses) ? $proses->nm_proses : null; ?>">
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
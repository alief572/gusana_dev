<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-12">
            <input type="hidden" class="form-control form-control-sm" id="id" name="id" readonly placeholder="Auto" value="<?= isset($id_warehouse) ? $id_warehouse : null; ?>">
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="warehouse_nm">Warehouse Name</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control form-control-sm" id="warehouse_nm" name="warehouse_nm" placeholder="Warehouse Name" value="<?= isset($warehouse) ? $warehouse->warehouse_nm : null; ?>">
                </div>
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="warehouse_nm">Warehouse Type</label>
                </div>
                <div class="col-md-8">
                    <select name="warehouse_type" id="" class="form-control form-control-sm warehouse_type chosen-select">
                        <option value="">- Warehouse Type -</option>
                        <option value="1" <?= (isset($warehouse) && $warehouse->warehouse_type == 1) ? 'selected' : null ?>>Material</option>
                        <option value="2" <?= (isset($warehouse) && $warehouse->warehouse_type == 2) ? 'selected' : null ?>>Production</option>
                        <option value="3" <?= (isset($warehouse) && $warehouse->warehouse_type == 3) ? 'selected' : null ?>>Finish Goods</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.chosen-select').select2({
            dropdownParent: $('#dialog-popup'),
            selectOnClose: true,
            width: '100%'
        });
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
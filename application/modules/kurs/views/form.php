<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="id_kurs">ID Kurs</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="id_kurs" name="id_kurs" readonly placeholder="Auto" value="<?= isset($id_kurs) ? $id_kurs : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="curr_to_idr">Periode</label>
                </div>
                <div class="col-md-4">
                    <input type="date" name="tgl_periode_awal" id="" class="form-control" value="<?= (isset($ms_kurs)) ? $ms_kurs->tgl_periode_awal : null ?>"> 
                </div>
                <span class="mt-3">S/D</span>
                <div class="col-md-4">
                    <input type="date" name="tgl_periode_akhir" id="" class="form-control" value="<?= (isset($ms_kurs)) ? $ms_kurs->tgl_periode_akhir : null ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="curr_to_idr">IDR To</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control form-control-sm chosen-select" name="curr_idr_to">
                        <option value=""></option>
                        <?php foreach ($mata_uang as $list_mata_uang) : ?>
                            <?php
                            $selected = "";
                            if ($list_mata_uang->kode == $ms_kurs->curr_to_idr) {
                                $selected = "selected";
                            }
                            ?>
                            <option value="<?= $list_mata_uang->kode ?>" <?= $selected ?>><?= $list_mata_uang->kode ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 tx-dark tx-bold">
                    <label for="curr_to_idr">Curr</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control input_nominal" id="curr_to_idr" name="curr_to_idr" placeholder="Curr from IDR" value="<?= isset($ms_kurs) ? $ms_kurs->kurs : null; ?>">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $(".input_nominal").autoNumeric();

        $('.chosen-select').select2({
            // minimumResultsForSearch: -1,
            placeholder: 'Choose one',
            dropdownParent: $('#dialog-popup'),
            width: "100%",
            allowClear: true
        });

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
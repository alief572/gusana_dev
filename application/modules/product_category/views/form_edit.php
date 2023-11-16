<div class="box box-primary">
    <div class="box-body">
        <div class="col-md-12">
            <div class="input_fields_wrap2">
                <input type="hidden" name="id_category1" value="<?= $results['product_category']->id_category1 ?>">
                <div class="row">
                    <div class="form-group row">
                        <div class="col-md-4 mt-2">
                            <label for="customer">Product Type</label>
                        </div>
                        <div class="col-md-8 mt-2">
                            <select name="product_type" id="" class="form-control form-control-sm chosen-select" required>
                                <option value="">- Product Type -</option>
                                <?php foreach ($results['product_type'] as $product_type) : ?>
                                    <option value="<?= $product_type->id_type ?>" <?= ($product_type->id_type == $results['product_category']->id_type) ? 'selected' : null ?>><?= $product_type->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mt-2">
                            <label for="customer">Product Category</label>
                        </div>
                        <div class="col-md-8 mt-2">
                            <input type="text" class="form-control form-control-sm" id="" required name="nm_product_category" placeholder="Product Category" value="<?= $results['product_category']->nama ?>">
                        </div>
                        <div class="col-md-4 mt-2">
                            <label for="customer">Status</label>
                        </div>
                        <div class="col-md-8 mt-2">
                            <input type="radio" name="aktif" id="" class="ml-2" value="1" <?= ($results['product_category']->aktif == 1) ? 'checked' : null ?>> Aktif
                            <input type="radio" name="aktif" id="" class="ml-2" value="0" <?= ($results['product_category']->aktif == 0) ? 'checked' : null ?>> Non Aktif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>





<script type="text/javascript">
    //$('#input-kendaraan').hide();
    $('.chosen-select').select2({
		dropdownParent: $('#dialog-popup'),
		selectOnClose: true,
		width: '100%'
	});
    var base_url = '<?php echo base_url(); ?>';
    var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

    $(document).ready(function() {
        var data_pay = <?php echo json_encode($results['supplier']); ?>;

        ///INPUT PERKIRAAN KIRIM


        var max_fields2 = 10; //maximum input boxes allowed
        var wrapper2 = $(".input_fields_wrap2"); //Fields wrapper
        var add_button2 = $(".add_field_button2"); //Add button ID

        //console.log(persen);

        var x2 = 1; //initlal text box count
        $(add_button2).click(function(e) { //on add input button click
            e.preventDefault();
            if (x2 < max_fields2) { //max input box allowed
                x2++; //text box increment

                $(wrapper2).append('<div class="row">' +
                    '<div class="col-xs-1">' + x2 + '</div>' +
                    '<div class="col-xs-3">' +
                    '<div class="input-group">' +
                    '<input type="text" name="hd' + x2 + '[produk]"  class="form-control input-sm" value="">' +
                    '</div>' +
                    '<div class="input-group">' +
                    '<input type="text" name="hd' + x2 + '[costcenter]"  class="form-control input-sm" value="">' +
                    '</div>' +
                    '<div class="input-group">' +
                    '<input type="text" name="hd' + x2 + '[mesin]"  class="form-control input-sm" value="">' +
                    '</div>' +
                    '<div class="input-group">' +
                    '<input type="text" name="hd' + x2 + '[mold_tools]"  class="form-control input-sm" value="">' +
                    '</div>' +
                    '</div>' +
                    '<a href="#" class="remove_field2">Remove</a>' +
                    '</div>'); //add input box
                $('#datepickerxxr' + x2).datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true
                });
            }
        });

        $(wrapper2).on("click", ".remove_field2", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x2--;
        })



        $('#add-payment').click(function() {
            var jumlah = $('#list_payment').find('tr').length;
            if (jumlah == 0 || jumlah == null) {
                var ada = 0;
                var loop = 1;
            } else {
                var nilai = $('#list_payment tr:last').attr('id');
                var jum1 = nilai.split('_');
                var loop = parseInt(jum1[1]) + 1;
            }
            Template = '<tr id="tr_' + loop + '">';
            Template += '<td align="left">';
            Template += '<input type="text" class="form-control input-sm" name="data1[' + loop + '][name_compotition]" id="data1_' + loop + '_name_compotition" label="FALSE" div="FALSE">';
            Template += '</td>';
            Template += '<td align="center"><button type="button" class="btn btn-sm btn-danger" title="Hapus Data" data-role="qtip" onClick="return DelItem(' + loop + ');">Delete</td>';
            Template += '</tr>';
            $('#list_payment').append(Template);
            $('input[data-role="tglbayar"]').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });
        });
    });

    function DelItem(id) {
        $('#list_payment #tr_' + id).remove();

    }
</script>
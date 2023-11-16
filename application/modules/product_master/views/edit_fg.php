<div class="box box-primary">
    <div class="box-body">
        <form id="edit-form" action="<?= base_url('inventory_4/saveEditInventory'); ?>" method="post" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="input_fields_wrap2">
                    <input type="hidden" name="id_category3" class="id_category3" value="<?= (isset($results['inventory_4'])) ? $results['inventory_4']->id_category3 : null ?>">
                    <table class="table">
                        <tr>
                            <th>Finish Goods Lv 1</th>
                            <th>
                                <select id="inventory_1" name="hd1[1][inventory_1]" class="form-control form-control-sm inventory_1" onchange="get_inv2()" required>
                                    <option value="">-- Pilih Finish Goods Lv 1 --</option>
                                    <?php foreach ($results['inventory_1'] as $inventory_1) {
                                    ?>
                                        <option value="<?= $inventory_1->id_type ?>" <?= ($results['inventory_4']->id_type == $inventory_1->id_type) ? 'selected' : null; ?>><?= ucfirst(strtolower($inventory_1->nama)) ?></option>
                                    <?php } ?>
                                </select>
                            </th>
                            <th>Finish Goods Lv 2</th>
                            <th>
                                <select id="inventory_2" name="hd1[1][inventory_2]" class="form-control form-control-sm inventory_2" onchange="get_inv3()" required>
                                    <option value="">-- Pilih Finish Goods Lv 2 --</option>
                                    <?php foreach ($results['inventory_2'] as $iv_2) : ?>
                                        <option value="<?= $iv_2->id_category1 ?>" <?= ($iv_2->id_category1 == $results['inventory_4']->id_category1) ? 'selected' : null ?>><?= $iv_2->nama ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </th>
                            <th>Finish Goods Lv 3</th>
                            <th>
                                <select id="inventory_3" name="hd1[1][inventory_3]" class="form-control form-control-sm inventory_3" required>
                                    <option value="">-- Pilih Finish Goods Lv 3 --</option>
                                    <?php foreach ($results['inventory_3'] as $iv_3) : ?>
                                        <option value="<?= $iv_3->id_category2 ?>" <?= ($iv_3->id_category2 == $results['inventory_4']->id_category2) ? 'selected' : null ?>><?= $iv_3->nama ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </th>
                        </tr>
                        <tr>
                            <th>Nama Finish Goods (Lv 4)</th>
                            <th>
                                <input type="text" name="hd1[1][nm_lv_4]" id="" class="form-control form-control-sm nm_lv_4" placeholder="Nama Finish Goods Lv 4" value="<?= $results['inventory_4']->nama ?>">
                            </th>
                            <th>Packaging Qty (Kg)</th>
                            <th>
                                <input type="number" name="hd1[1][packaging_qty]" id="" class="form-control form-control-sm packaging_qty" placeholder="Packaging Qty (Kg)" value="<?= $results['inventory_4']->packaging_qty ?>">
                            </th>
                            <th>Kategori Finish Goods</th>
                            <th>
                                <div id="slWrapperSupplier" class="parsley-select">
                                    <select name="hd1[1][kategori_fg]" id="" class="form-control form-control-sm select" data-parsley-inputs data-parsley-class-handler="#slWrapperSupplier" data-parsley-errors-container="#slErrorContainerSupplier">
                                        <option value="">-- kategori Finish Goods --</option>
                                        <?php
                                        foreach ($results['kategori_fg'] as $katfg) {
                                            $selected = "";
                                            if ($katfg->id_kategori_finish_goods == $results['inventory_4']->kategori_fg_id) {
                                                $selected = "selected";
                                            }
                                            echo '<option value="' . $katfg->id_kategori_finish_goods . '" ' . $selected . '>' . $katfg->nm_kategori_finish_goods . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <div id="slErrorContainerSupplier"></div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>Packaging</th>
                            <th>
                                <div id="slWrapperPackaging" class="parsley-select">
                                    <select name="hd1[1][packaging]" id="" class="form-control form-control-sm packaging select" data-parsley-inputs data-parsley-class-handler="#slWrapperPackaging" data-parsley-errors-container="#slErrorContainerPackaging">
                                        <option value="">-- Packaging --</option>
                                        <?php
                                        foreach ($results['packaging'] as $pack) {
                                            $selected = "";
                                            if ($pack->id == $results['inventory_4']->packaging) {
                                                $selected = "selected";
                                            }
                                            echo '<option value="' . $pack->id . '" ' . $selected . '>' . $pack->nm_packaging . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <div id="slErrorContainerPackaging"></div>
                                </div>
                            </th>
                            <th>Konversi</th>
                            <th>
                                <input type="number" name="hd1[1][konversi]" id="" class="form-control form-control-sm konversi" placeholder="Konversi" value="<?= $results['inventory_4']->konversi ?>">
                            </th>
                            <th>Unit</th>
                            <th>
                                <div id="slWrapperUnit" class="parsley-select">
                                    <select name="hd1[1][unit]" id="" class="form-control form-control-sm unit select" data-parsley-inputs data-parsley-class-handler="#slWrapperUnit" data-parsley-errors-container="#slErrorContainerUnit">
                                        <option value="">-- Unit --</option>
                                        <?php
                                        foreach ($results['unit'] as $unit) {
                                            $selected = "";
                                            if ($results['inventory_4']->unit == $unit->id_unit) {
                                                $selected = "selected";
                                            }
                                            echo '<option value="' . $unit->id_unit . '" ' . $selected . '>' . $unit->nm_unit . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <div id="slErrorContainerUnit"></div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>Spesifikasi</th>
                            <th colspan="5">
                                <input type="text" class="form-control form-control-sm spesifikasi" id="spesifikasi" name="hd1[1][spesifikasi]" placeholder="Spesifikasi" value="<?= $results['inventory_4']->spesifikasi ?>">
                            </th>
                        </tr>
                    </table>
                    <div class="row">


                        <div class="col-xs-2">
                            &nbsp;
                        </div>
                    </div>

                </div>
            </div>
            <hr>
            <center>
                <!--<button type="submit" class="btn btn-primary btn-sm add_field_button2" name="save"><i class="fa fa-plus"></i>Add Main Produk</button>
					--><button type="submot" class="btn btn-success btn-sm" name="save" id="edit-pros"><i class="fa fa-save"></i>Simpan</button>
            </center>

        </form>



    </div>
</div>




<script type="text/javascript">
    //$('#input-kendaraan').hide();

    // $(document).on('keyup','#inventory_2','#inventory_3','#nm_inventory','.maker','.hardness', function(){
    // cariNama();
    // });

    var base_url = '<?php echo base_url(); ?>';
    var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

    $(document).on("change", ".inv_lv_1", function() {
        var inv_lv_1 = $(this).val();
        $.ajax({
            type: 'POST',
            url: siteurl + 'inventory_4/addInventory',
            data: {
                "inv_lv_1": inv_lv_1
            },
            success: function(data) {
                $("#dialog-popup").modal();
                $("#ModalView").html(data);

            }
        });
    });

    $('.select').select2({
        placeholder: 'Choose one',
        dropdownParent: $('.box-primary'),
        width: "100%",
        allowClear: true
    });

    $('.select.not-search').select2({
        minimumResultsForSearch: -1,
        placeholder: 'Choose one',
        dropdownParent: $('.box-primary'),
        width: "100%",
        allowClear: true
    });

    $(document).on('keyup', '#dimensi1', function() {
        cariThickness();
    });

    $(document).on('change', '#inventory_3', function() {
        cariAlloy();
    });

    var base_url = '<?php echo base_url(); ?>';
    var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

    $(document).ready(function() {
        var data_pay = <?php echo json_encode($results['suppliers']); ?>;

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
                    '<input type="text" name="hd' + x2 + '[produk]"  class="form-control form-control-sm input-sm" value="">' +
                    '</div>' +
                    '<div class="input-group">' +
                    '<input type="text" name="hd' + x2 + '[costcenter]"  class="form-control form-control-sm input-sm" value="">' +
                    '</div>' +
                    '<div class="input-group">' +
                    '<input type="text" name="hd' + x2 + '[mesin]"  class="form-control form-control-sm input-sm" value="">' +
                    '</div>' +
                    '<div class="input-group">' +
                    '<input type="text" name="hd' + x2 + '[mold_tools]"  class="form-control form-control-sm input-sm" value="">' +
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
            Template += '<select id="id_supplier" name="data1[' + loop + '][id_supplier]" id="data1_' + loop + '_id_supplier" class="form-control form-control-sm select" required>';
            Template += '<option value="">-- Pilih Type --</option>';
            Template += '<?php foreach ($results["id_supplier"] as $id_supplier) { ?>';
            Template += '<option value="<?= $id_supplier->id ?>"><?= ucfirst(strtolower($id_supplier->supplier_name)) ?></option>';
            Template += '<?php } ?>';
            Template += '</select>';
            Template += '</td>';
            Template += '<td align="left">';
            Template += '<input type="text" class="form-control form-control-sm input-sm" name="data1[' + loop + '][lead]" id="data1_' + loop + '_lead" label="FALSE" div="FALSE">';
            Template += '</td>';
            Template += '<td align="left">';
            Template += '<input type="text" class="form-control form-control-sm input-sm" name="data1[' + loop + '][minimum]" id="data1_' + loop + '_minimum" label="FALSE" div="FALSE">';
            Template += '</td>';
            Template += '<td align="center"><button type="button" class="btn btn-sm btn-danger" title="Hapus Data" data-role="qtip" onClick="return DelItem(' + loop + ');">Delete</button></td>';
            Template += '</tr>';
            $('#list_payment').append(Template);
            $('input[data-role="tglbayar"]').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });
        });







    });
    $('#inventory_2').change(function() {
        var inventory_2 = $("#inventory_2").val();
        $.ajax({
            type: "GET",
            url: siteurl + 'inventory_4/get_compotition',
            data: "inventory_2=" + inventory_2,
            success: function(html) {
                $("#list_compotition").html(html);
            }
        });
    });




    function get_inv2() {
        var inventory_1 = $("#inventory_1").val();
        $.ajax({
            type: "GET",
            url: siteurl + 'inventory_4/get_inven2',
            data: "inventory_1=" + inventory_1,
            success: function(html) {
                $("#inventory_2").html(html);
            }
        });
    }

    function cariAlloy() {
        var inventory_1 = $("#inventory_3").val();
        $.ajax({
            type: "POST",
            url: siteurl + 'inventory_4/get_namainven2',
            data: "inventory_1=" + inventory_1,
            success: function(html) {
                $("#alloy").val(html);
            }
        });
    }

    function get_bentuk() {
        var id_bentuk = $("#id_bentuk").val();
        $.ajax({
            type: "GET",
            url: siteurl + 'inventory_4/get_dimensi',
            data: "id_bentuk=" + id_bentuk,
            success: function(html) {
                $("#list_dimensi").html(html);
            }
        });
    }





    function get_olddata() {
        var nm_inventory = $("#nm_inventory").val();
        $.ajax({
            type: "GET",
            url: siteurl + 'inventory_4/get_olddata',
            data: "id_bentuk=" + id_bentuk,
            success: function(html) {
                $("#list_dimensi").html(html);
            }
        });
    }

    function get_inv3() {
        var inventory_2 = $("#inventory_2").val();
        $.ajax({
            type: "GET",
            url: siteurl + 'inventory_4/get_inven3',
            data: "inventory_2=" + inventory_2,
            success: function(html) {
                $("#inventory_3").html(html);
            }
        });
    }


    function get_surface() {
        var idsurface = $(".idsurface").val();
        $.ajax({
            type: "GET",
            url: siteurl + 'inventory_4/get_surface',
            data: "idsurface=" + idsurface,
            success: function(data) {
                $(".surface").val(data);
            }
        });



    }

    function cariThickness() {
        var dimensi = $("#dimensi1").val();
        $("#thickness").val(dimensi);

        cariNama();
    }

    function cariNama() {
        var alloy = $("#alloy").val();

        var spek = $("#nm_inventory").val();
        if (spek != '') {
            var stripspek = '-';
        } else {
            var stripspek = '';
        };
        var hardness = $(".hardness").val();
        if (hardness != '') {
            var striphardness = '-';
        } else {
            var striphardness = '';
        };
        var thickness = $("#thickness").val();
        if (thickness != '') {
            var stripthickness = '-';
        } else {
            var stripthickness = '';
        };
        var maker = $(".maker").val();
        if (maker != '') {
            var stripmaker = '-';
        } else {
            var stripmaker = '';
        };
        var surface = $(".surface").val();
        if (surface != '') {
            var stripsurface = '-';
        } else {
            var stripsurface = '';
        };

        var nama = alloy + stripspek + spek + striphardness + hardness + stripthickness + thickness + stripsurface + surface;
        $(".nama").val(nama);

    }

    function DelItem(id) {
        $('#list_payment #tr_' + id).remove();

    }
</script>
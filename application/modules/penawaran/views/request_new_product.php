<div class="box box-primary">
    <div class="box-body">
        <form action="" id="data-form-request" method="post" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="input_fields_wrap2">
                    <input type="hidden" name="" class="id_category3" value="<?= (isset($results['id_category3'])) ? $results['id_category3'] : null ?>">
                    <h5>Input Sales</h5>
                    <hr>
                    <table class="w-100" border="0" style="border:none !important;">
                        <tbody>
                            <tr style="border:none !important;">
                                <th>Product Type</th>
                                <th colspan="5">
                                    <input type="text" name="nm_type" id="" class="form-control form-control-sm" list="list_type">
                                    <datalist id="list_type">
                                        <?php foreach ($results['product_type'] as $product_type) {

                                        ?>
                                            <option value="<?= ucfirst(strtolower($product_type->nama)) ?>"><?= ucfirst(strtolower($product_type->nama)) ?></option>
                                        <?php } ?>
                                    </datalist>
                                </th>
                            </tr>
                            <tr style="border:none !important;">
                                <th>Product Category</th>
                                <th colspan="5">
                                    <input type="text" name="nm_category1" id="" class="form-control form-control-sm" list="list_category1">
                                    <datalist id="list_category1">
                                        <?php foreach ($results['product_category'] as $product_category) : ?>
                                            <option value="<?= ucfirst(strtolower($product_category->nama)) ?>"><?= ucfirst(strtolower($product_category->nama)) ?></option>
                                        <?php endforeach; ?>
                                    </datalist>
                                </th>
                            </tr>
                            <tr style="border:none !important;">
                                <th>Product Jenis</th>
                                <th colspan="5">
                                    <input type="text" name="nm_category2" id="" class="form-control form-control-sm" list="list_category2">
                                    <datalist id="list_category2">
                                        <?php foreach ($results['product_jenis'] as $product_jenis) : ?>
                                            <option value="<?= ucfirst(strtolower($product_jenis->nama)) ?>"><?= ucfirst(strtolower($product_jenis->nama)) ?></option>
                                        <?php endforeach; ?>
                                    </datalist>
                                </th>
                            </tr>
                            <tr style="border:none !important;">
                                <th>Spesifikasi Packaging</th>
                                <th>
                                    <input type="number" name="spek_packaging" class="form-control " id="">
                                </th>
                                <th>Unit</th>
                                <th>
                                    <select name="unit" id="" class="form-control jenis_packaging chosen-select">
                                        <option value="">- Pilih Unit -</option>
                                        <?php foreach ($results['unit'] as $unit) : ?>
                                            <option value="<?= $unit->nm_unit ?>"><?= $unit->nm_unit ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th>Jenis Packaging</th>
                                <th colspan="5">
                                    <select name="jenis_packaging" id="" class="form-control jenis_packaging chosen-select">
                                        <option value="">- Jenis Packaging -</option>
                                        <?php foreach ($results['packaging'] as $pack) : ?>
                                            <option value="<?= $pack->nm_packaging ?>"><?= $pack->nm_packaging ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </th>
                            </tr>
                            <tr style="border:none !important;">
                                <th>Keterangan</th>
                                <th colspan="5">
                                    <textarea name="keterangan" id="" cols="30" rows="6" class="form-control"></textarea>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table" border="0">
                        <thead>
                            <tr>
                                <th colspan="3">Spesifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <label for="">Aplikasi penggunaan cat dan coating</label>
                                    <select name="aplikasi_penggunaan_cat" id="" class="form-control form-control-sm">
                                        <option value="">- Aplikasi penggunaan cat dan coating -</option>
                                        <option value="1">Steel/Besi</option>
                                        <option value="2">Kayu</option>
                                        <option value="3">Tembok</option>
                                        <option value="4">Lantai</option>
                                        <option value="5">Batu/Bata</option>
                                        <option value="6">Gypsum</option>
                                        <option value="7">Polymer</option>
                                        <option value="8">Beton</option>
                                        <option value="9">Baja</option>
                                        <option value="10">Semen</option>
                                        <option value="11">Keramik dan Kaca</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="">Water Resistance</label>
                                    <select name="water_resistance" id="" class="form-control form-control-sm">
                                        <option value="">- Water Resistance -</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="">Weather & UV resistance</label>
                                    <select name="weather_uv_resistance" id="" class="form-control form-control-sm">
                                        <option value="">- Weather & UV resistance -</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="">Corrosion Resistance</label>
                                    <select name="corrosion_resistance" id="" class="form-control form-control-sm">
                                        <option value="">- Corrosion Resistance -</option>
                                        <option value="1">High</option>
                                        <option value="2">Medium</option>
                                        <option value="3">Low</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="">Heat Resistance</label>
                                    <select name="heat_resistance" id="" class="form-control form-control-sm">
                                        <option value="">- Heat Resistance -</option>
                                        <option value="1">Up to 200 °C</option>
                                        <option value="2">Up to 300 °C</option>
                                        <option value="3">Up to 400 °C</option>
                                        <option value="4">Up to 500 °C</option>
                                        <option value="5">Up to 600 °C</option>
                                        <option value="6">Up to 800 °C</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="">Daya Rekat (Adhesi)</label>
                                    <select name="daya_rekat" id="" class="form-control form-control-sm">
                                        <option value="">- Daya Rekat -</option>
                                        <option value="1">High</option>
                                        <option value="2">Medium</option>
                                        <option value="3">Low</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="">Lama Pengeringan</label>
                                    <select name="lama_pengeringan" id="" class="form-control form-control-sm">
                                        <option value="">- Lama Pengeringan -</option>
                                        <option value="1">Cepat</option>
                                        <option value="2">Lambat</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="">Permukaan</label>
                                    <select name="permukaan" id="" class="form-control form-control-sm">
                                        <option value="">- Permukaan -</option>
                                        <option value="1">Glossy</option>
                                        <option value="2">Matte</option>
                                        <option value="3">Semi Matte</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="">Anti Jamur dan Lumut</label>
                                    <select name="anti_jamur_lumut" id="" class="form-control form-control-sm">
                                        <option value="">- Anti Jamur dan Lumut -</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="">Mudah dibersihkan (Dirt Resistance)</label>
                                    <select name="mudah_dibersihkan" id="" class="form-control form-control-sm">
                                        <option value="">- Mudah dibersihkan (Dirt Resistant) -</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="">Anti Bakteri</label>
                                    <select name="anti_bakteri" id="" class="form-control form-control-sm">
                                        <option value="">- Anti Bakteri -</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="">Daya tahan gesekan</label>
                                    <select name="daya_tahan_gesekan" id="" class="form-control form-control-sm">
                                        <option value="">- Daya tahan gesekan -</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="">Anti Slip</label>
                                    <select name="anti_slip" id="" class="form-control form-control-sm">
                                        <option value="">- Anti Slip -</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="">Fire Resistance</label>
                                    <select name="fire_resistance" id="" class="form-control form-control-sm">
                                        <option value="">- Fire Resistance -</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="">Ketahanan Bahan Kimia</label>
                                    <select name="ketahanan_bahan_kimia" id="" class="form-control form-control-sm">
                                        <option value="">- Ketahanan Bahan Kimia -</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">


                        <div class="col-xs-2">
                            &nbsp;
                        </div>
                    </div>

                </div>
            </div>
            <!-- <hr> -->
            <center>
                <!--<button type="submit" class="btn btn-primary btn-sm add_field_button2" name="save"><i class="fa fa-plus"></i>Add Main Produk</button>
					--><button type="submit" class="btn btn-success btn-sm" name="save" id="simpan-com"><i class="fa fa-save"></i>Simpan</button>
            </center>

        </form>



    </div>
</div>




<script type="text/javascript">
    //$('#input-kendaraan').hide();

    // $(document).on('keyup','#inventory_2','#inventory_3','#nm_inventory','.maker','.hardness', function(){
    // cariNama();
    // });
    $(".cbm_tabung").autoNumeric();
    $(".autonum").autoNumeric();

    $('.chosen-select').select2({
        dropdownParent: $('#request-new-product'),
        width: '100%'
    });


    $('.select').select2({
        placeholder: 'Choose one',
        dropdownParent: $('#ModalView'),
        width: "150px",
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

    $(document).on('change', '.get_lv_1', function() {
        var lv_type = $(this).val();

        $.ajax({
            type: 'post',
            url: siteurl + thisController + 'get_lv_1',
            data: {
                'lv_type': lv_type,
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                $('.product_category1').html(result.hasil);
            }
        });
    });
    $(document).on('change', '.get_lv_2', function() {
        var lv_type1 = $(this).val();

        $.ajax({
            type: 'post',
            url: siteurl + thisController + 'get_lv_2',
            data: {
                'lv_type1': lv_type1,
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                $('.product_category2').html(result.hasil);
            }
        });
    });
</script>
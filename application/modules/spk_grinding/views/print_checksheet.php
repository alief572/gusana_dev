<?php
$ENABLE_ADD     = has_permission('Menu_SPK.Add');
$ENABLE_MANAGE  = has_permission('Menu_SPK.Manage');
$ENABLE_VIEW    = has_permission('Menu_SPK.View');
$ENABLE_DELETE  = has_permission('Menu_SPK.Delete');
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .pd-10 {
        padding-top: 10px;
    }

    .mt-t-20 {
        margin-top: 20px;
    }

    .print {
        display: none;
    }

    .unprint {
        /* display: block; */
    }

    @media print {
        .print {
            display: block;
        }

        .unprint {
            display: none;
        }
    }
</style>

<div class="br-pagebody pd-10">
    <div class="card bd-gray-400 printed">
        <form action="" id="data-form">
            <table class="w-100">
                <thead>
                    <tr>
                        <th rowspan="2" class="" style="width:100px;">
                            <img src="<?= base_url("assets/img/Gusana.png") ?>" alt="" style="width:150px;">
                        </th>
                        <th class="text-center">
                            <h5>Checksheet Grinding</h5>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center">
                            <h5>Bahan Baku</h5>
                        </th>
                    </tr>
                </thead>
            </table>
            <table class="w-60">
                <tr>
                    <th>Nama Produk</th>
                    <th>:</th>
                    <th><?= $results['data_product']->nama ?></th>
                </tr>
                <tr>
                    <th>Kode Produk</th>
                    <th>:</th>
                    <th><?= $results['data_product']->id_category3 ?></th>
                </tr>
                <tr>
                    <th>Lot Size</th>
                    <th>:</th>
                    <th><?= $results['data_bom']->qty_hopper ?></th>
                </tr>
                <tr>
                    <th>No. SPK Material</th>
                    <th>:</th>
                    <th><?= $results['data_spk']->id_spk ?></th>
                </tr>
            </table>
            <table class="w-100" border="1">
                <thead>
                    <tr>
                        <th class="text-center pd-10">No.</th>
                        <th class="text-center pd-10">Nama Material</th>
                        <th class="text-center pd-10">Jumlah (Kg)</th>
                        <th class="text-center pd-10">Aktual (Kg)</th>
                        <th class="text-center pd-10">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $x = 0;
                    foreach ($results['data_detail_bom'] as $detail_bom) : $x++ ?>
                        <tr>
                            <td class="text-center pd-10"><?= $x ?></td>
                            <td class="text-center pd-10"><?= $detail_bom->nama ?></td>
                            <td class="text-center pd-10">
                                <input type="number" name="berat_<?= $detail_bom->id_category1 ?>" class="form-control form-control-sm text-right unprint" id="" step="0.01" value="<?= $detail_bom->berat ?>">
                                <p class="print"><?= $detail_bom->berat ?></p>
                            </td>
                            <td class="text-center pd-10"></td>
                            <td class="text-center pd-10"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <table class="w-100 mt-t-20" border="1">
                <thead>
                    <tr>
                        <th class="text-center pd-10" colspan="5">Tambahan Material</th>
                    </tr>
                    <tr>
                        <th class="text-center pd-10">No.</th>
                        <th class="text-center pd-10">Nama Material</th>
                        <th class="text-center pd-10">Jumlah (Kg)</th>
                        <th class="text-center pd-10">Aktual (Kg)</th>
                        <th class="text-center pd-10">Alasan Penambahan Material</th>
                        <th class="text-center pd-10 unprint">Action</th>
                    </tr>
                </thead>
                <tbody class="list_material_tambahan">
                    <?php $x = 0;
                    foreach ($results['data_material_tambahan'] as $material_tambahan) : $x++; ?>
                        <tr>
                            <td class="text-center"><?= $x ?></td>
                            <td class="text-center"><?= $material_tambahan->nama_material ?></td>
                            <td class="text-center"><?= $material_tambahan->jumlah ?></td>
                            <td class="text-center"></td>
                            <td class="text-center"><?= $material_tambahan->alasan ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger del_material_tambahan" data-id="<?= $material_tambahan->id ?>" data-id_spk="<?= $results['data_spk']->id_spk ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tbody class="unprint">
                    <input type="hidden" name="id_spk" class="id_spk" value="<?= $results['data_spk']->id_spk ?>">
                    <tr>
                        <td class="text-center">

                        </td>
                        <td>
                            <select name="material_tambahan" id="" class="form-control material_tambahan chosen-select">
                                <option value="">- Material Category -</option>

                            </select>
                        </td>
                        <td>
                            <input type="text" name="jumlah_material_tambahan" id="" class="form-control form-control-sm jumlah_material_tambahan autonum">
                        </td>
                        <td>

                        </td>
                        <td>
                            <textarea name="alasan_material_tambahan" class="form-control form-control-sm alasan_material_tambahan" id="" cols="30" rows="2"></textarea>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-success add_material_tambahan">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="text-right" style="margin-top: 25px;">
                <button type="submit" class="btn btn-sm btn-success">Save</button>
                <a class="btn btn-sm btn-info" target="_blank" href="<?= base_url('spk_grinding/print_checksheet_real/' . $results['data_spk']->id_spk) ?>">Print</a>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".chosen-select").select2();
        $(".autonum").autoNumeric();

        function get_free_material(id_spk) {
            $.ajax({
                type: "POST",
                url: siteurl + thisController + 'get_free_material',
                data: {
                    'id_spk': id_spk
                },
                cache: false,
                dataType: 'json',
                success: function(result) {
                    $('.material_tambahan').html(result.hasil);
                }
            });
        }

        $(document).ready(function() {
            get_free_material();
        });

        $(document).on("click", ".add_material_tambahan", function(e) {
            e.preventDefault();

            var id_spk = $(".id_spk").val();
            var material_tambahan = $(".material_tambahan").val();
            var jumlah_material_tambahan = $(".jumlah_material_tambahan").val();
            var alasan_material_tambahan = $(".alasan_material_tambahan").val();

            // let form_material_tambahan = new FormData($(this)[0]);

            if (
                material_tambahan == "" || material_tambahan == null ||
                jumlah_material_tambahan == "" || jumlah_material_tambahan == null || jumlah_material_tambahan == 0 ||
                alasan_material_tambahan == "" || alasan_material_tambahan == null
            ) {
                new swal({
                    text: "Mohon isi lengkap semua kolom material tambahan nya !",
                    icon: "error"
                })
            } else {
                jumlah_material_tambahan = parseInt(jumlah_material_tambahan);
                if (jumlah_material_tambahan <= 0) {
                    new swal({
                        text: "Jumlah material tambahan harus lebih dari 0 !",
                        icon: "error"
                    })
                } else {
                    $.ajax({
                        type: "POST",
                        url: siteurl + thisController + 'add_material_tambahan',
                        data: {
                            'id_spk': id_spk,
                            'material_tambahan': material_tambahan,
                            'jumlah_material_tambahan': jumlah_material_tambahan,
                            'alasan_material_tambahan': alasan_material_tambahan
                        },
                        dataType: 'json',
                        cache: false,
                        success: function(result) {
                            // console.log(result);
                            get_free_material();
                            $('.list_material_tambahan').html(result.hasil);

                            $(".material_tambahan").val('').trigger('change');
                            $(".jumlah_material_tambahan").val('');
                            $(".alasan_material_tambahan").val('');

                        }
                    })
                }
            }

        });

        $(document).on("click", ".del_material_tambahan", function() {
            var id = $(this).data('id');
            var id_spk = $(this).data('id_spk');

            $.ajax({
                type: "POST",
                url: siteurl + thisController + 'del_material_tambahan',
                data: {
                    'id': id,
                    'id_spk': id_spk
                },
                cache: false,
                dataType: 'json',
                success: function(result) {
                    $('.list_material_tambahan').html(result.hasil);

                    get_free_material();
                }
            });
        });

        $(document).on("submit", "#data-form", function(e) {
            e.preventDefault();

            let dataForm = $(this).serialize();
            // console.log(dataForm);

            // alert('awdwad');
            $.ajax({
                type: 'POST',
                url: siteurl + thisController + 'save_data',
                data: dataForm,
                cache: false,
                success: function(result) {
                    location.reload(true);
                }
            });
        });
    });
</script>

<!-- page script -->
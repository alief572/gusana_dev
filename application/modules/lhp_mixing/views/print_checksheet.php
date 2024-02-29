<?php
$ENABLE_ADD     = has_permission('Menu_SPK.Add');
$ENABLE_MANAGE  = has_permission('Menu_SPK.Manage');
$ENABLE_VIEW    = has_permission('Menu_SPK.View');
$ENABLE_DELETE  = has_permission('Menu_SPK.Delete');
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css" rel="stylesheet">
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
            <input type="hidden" name="id_spk" class="id_spk" value="<?= $results['data_spk']->id_spk ?>">
            <input type="hidden" name="id_so" class="id_so" value="<?= $results['data_spk']->id_so ?>">
            <table class="w-100">
                <thead>
                    <tr>
                        <th rowspan="2" class="" style="width:100px;">
                            <img src="<?= base_url("assets/img/Gusana.png") ?>" alt="" style="width:150px;">
                        </th>
                        <th class="text-center">
                            <h5>Checksheet Penimbangan</h5>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center">
                            <h5>Material Mixing</h5>
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
                                <?= $detail_bom->berat ?>
                            </td>
                            <td class="text-center pd-10">
                                <input type="text" name="jumlah_aktual_<?= $detail_bom->id_category1 ?>" id="" class="form-control form-control-sm text-right" value="<?= $detail_bom->aktual_qty ?>" <?= ($results['closing_spk'] == 1) ? 'readonly' : null ?>>
                            </td>
                            <td class="text-center pd-10">
                                <textarea name="keterangan_aktual_<?= $detail_bom->id_category1 ?>" id="" cols="30" rows="3" class="form-control form-control-sm" <?= ($results['closing_spk'] == 1) ? 'readonly' : null ?>><?= $detail_bom->keterangan_aktual ?></textarea>
                            </td>
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
                    </tr>
                </thead>
                <tbody class="list_material_tambahan">
                    <?php $x = 0;
                    foreach ($results['data_material_tambahan'] as $material_tambahan) : $x++; ?>
                        <tr>
                            <td class="text-center"><?= $x ?></td>
                            <td class="text-center"><?= $material_tambahan->nama_material ?></td>
                            <td class="text-center"><?= $material_tambahan->jumlah ?></td>
                            <td class="text-center">
                                <input type="number" name="qty_aktual_material_tambahan_<?= $material_tambahan->id_category1 ?>" id="" class="form-control form-control-sm text-right" step="0.01" value="<?= $material_tambahan->aktual_qty ?>" <?= ($results['closing_spk'] == 1) ? 'readonly' : null ?>>
                            </td>
                            <td class="text-center"><?= $material_tambahan->alasan ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="text-right" style="margin-top: 25px;">
                <button type="button" class="btn btn-sm btn-danger" onclick="window.close()">Back</button>
                <button type="submit" class="btn btn-sm btn-success <?= ($results['closing_spk'] == 1) ? 'd-none' : null ?>">Save</button>
                <button type="button" class="btn btn-sm btn-primary closing_lhp_spk <?= ($results['closing_spk'] == 1) ? 'd-none' : null ?>">Closing</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.all.min.js"></script>
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

        $(document).on('click', '.closing_lhp_spk', function() {
            var id_spk = $('.id_spk').val();
            var id_so = $('.id_so').val();
            var id_proses = 5;

            swal.fire({
                title: "Are you sure?",
                text: "Are you sure to close this LHP ?.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "<i class='fa fa-check'></i> Yes",
                cancelButtonText: "<i class='fa fa-ban'></i> No",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        type: 'POST',
                        url: siteurl + thisController + 'closing_spk',
                        dataType: "JSON",
                        data: {
                            'id_spk': id_spk,
                            'id_so': id_so,
                            'id_proses': id_proses
                        },
                        cache: false,
                        error: function() {
                            Lobibox.notify('error', {
                                title: 'Error!!!',
                                icon: 'fa fa-times',
                                position: 'top right',
                                showClass: 'zoomIn',
                                hideClass: 'zoomOut',
                                soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                                msg: 'Internal server error. Ajax process failed.'
                            });
                        }
                    })
                },
                allowOutsideClick: true
            }).then((val) => {
                console.log(val);
                if (val.isConfirmed) {
                    if (val.value.status == '1') {
                        Lobibox.notify('success', {
                            icon: 'fa fa-check',
                            msg: val.value.msg,
                            position: 'top right',
                            showClass: 'zoomIn',
                            hideClass: 'zoomOut',
                            soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                        });
                        location.reload(true);
                        // $("#dialog-popup").modal('hide');
                        // loadData()
                        $('.dataTables_length select').select2({
                            minimumResultsForSearch: -1
                        })
                    } else {
                        Lobibox.notify('warning', {
                            icon: 'fa fa-ban',
                            msg: val.value.msg,
                            position: 'top right',
                            showClass: 'zoomIn',
                            hideClass: 'zoomOut',
                            soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                        });
                    };
                }
            })
        });
    });
</script>

<!-- page script -->
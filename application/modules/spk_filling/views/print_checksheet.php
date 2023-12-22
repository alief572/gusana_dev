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
                            <h5>Checksheet Filling</h5>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center">
                            <h5>Product</h5>
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
                        <th class="text-center pd-10">Konversi / pack (Kg)</th>
                        <th class="text-center pd-10">Packaging</th>
                        <th class="text-center pd-10">Total Qty (Kaleng)</th>
                        <th class="text-center pd-10">Aktual Qty (Kaleng)</th>
                        <th class="text-center pd-10">Sisa Produksi Sebelumnya (Kaleng)</th>
                        <th class="text-center pd-10">Total (Kaleng)</th>
                        <th class="text-center pd-10">Sisa Produk (Kaleng)</th>
                    </tr>
                </thead>
                <tbody>
                    <td class="text-center">1</td>
                    <td class="text-center"><?= $results['data_product_so']->konversi . ' ' . $results['data_product_so']->packaging ?></td>
                    <td class="text-center"><?= $results['data_product_so']->packaging ?></td>
                    <td class="text-center"><?= number_format(($results['data_product_so']->propose / $results['data_product_so']->konversi), 2).' '.$results['data_product_so']->packaging ?></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tbody>
            </table>

            <table class="w-100 mt-t-20" border="1">
                <thead>
                    <tr>
                        <th class="text-center pd-10" colspan="8">Aktual pengecekan berat per packaging (Pengecekan Acak)</th>
                    </tr>
                    <tr>
                        <th class="text-center pd-10">No. Packaging</th>
                        <th class="text-center pd-10"></th>
                        <th class="text-center pd-10"></th>
                        <th class="text-center pd-10"></th>
                        <th class="text-center pd-10"></th>
                        <th class="text-center pd-10"></th>
                    </tr>
                    <tr>
                        <th class="text-center pd-10">Berat Aktual (Kg)</th>
                        <th class="text-center pd-10"></th>
                        <th class="text-center pd-10"></th>
                        <th class="text-center pd-10"></th>
                        <th class="text-center pd-10"></th>
                        <th class="text-center pd-10"></th>
                    </tr>
                </thead>

            </table>

            <div class="text-right" style="margin-top: 25px;">
                <!-- <button type="submit" class="btn btn-sm btn-success">Save</button> -->
                <a class="btn btn-sm btn-info" target="_blank" href="<?= base_url('spk_filling/print_checksheet_real/' . $results['data_spk']->id_spk) ?>">Print</a>
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
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
</style>

<div class="br-pagebody pd-10">
    <div class="card bd-gray-400" id="printableDiv">
        <form action="" id="data-form">
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
                            <h5>Material Coloring</h5>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

            <div class="row">
                <div class="col-6">
                    <table class="" style="width:100%; margin-top:15px;">
                        <tr>
                            <th>Dicetak Oleh</th>
                            <th>:</th>
                            <th><?= $results['data_user']->full_name; ?></th>
                        </tr>
                        <tr>
                            <th>Dicetak Tgl</th>
                            <th>:</th>
                            <th><?= date('d-m-Y'); ?></th>
                        </tr>
                        <tr>
                            <th>No Cetak</th>
                            <th>:</th>
                            <th><?= $results['no_cetak']; ?></th>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="" style="width:100%; margin-top:15px;">
                        <tr>
                            <th style="vertical-align:top;">Keterangan</th>
                            <th>
                                <textarea name="" id="" cols="30" rows="3" class="form-control form-control-sm"></textarea>
                            </th>
                        </tr>
                    </table>
                </div>
            </div>

        </form>
    </div>
    <div class="text-right" style="margin-top: 25px;">
        <button type="button" class="btn btn-sm btn-info" onclick="printDiv('printableDiv')">Print</button>
    </div>
</div>

<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
    $(document).ready(function() {
        $(".chosen-select").select2();
        $(".autonum").autoNumeric();


    });
</script>

<!-- page script -->
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

<div class="br-pagebody pd-10" >
    <div class="card bd-gray-400" id="printableDiv">
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
                        <th class="text-center pd-10">Total Qty</th>
                        <th class="text-center pd-10">Aktual Qty (Kaleng)</th>
                        <th class="text-center pd-10">Sisa Produk</th>
                    </tr>
                </thead>
                <tbody>
                    <td class="text-center">1</td>
                    <td class="text-center"><?= $results['data_product_so']->konversi . ' ' . $results['data_product_so']->packaging ?></td>
                    <td class="text-center"><?= $results['data_product_so']->packaging ?></td>
                    <td class="text-center"><?= number_format(($results['data_product_so']->propose / $results['data_product_so']->konversi / ($results['data_product_so']->propose / $results['data_bom']->qty_hopper)), 2).' '.$results['data_product_so']->packaging ?></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tbody>
            </table>

            <table class="w-100 mt-t-20" border="1">
                <thead>
                    <tr>
                        <th class="text-center pd-10" colspan="6">Aktual pengecekan berat per packaging (Pengecekan Acak)</th>
                    </tr>
                    <tr>
                        <th class="text-center pd-10">No. Sample</th>
                        <th class="text-center pd-10">#1</th>
                        <th class="text-center pd-10">#2</th>
                        <th class="text-center pd-10">#3</th>
                        <th class="text-center pd-10">#4</th>
                        <th class="text-center pd-10">#5</th>
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
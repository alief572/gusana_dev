<?php
$ENABLE_ADD     = has_permission('Material_Outgoing.Add');
$ENABLE_MANAGE  = has_permission('Material_Outgoing.Manage');
$ENABLE_VIEW    = has_permission('Material_Outgoing.View');
$ENABLE_DELETE  = has_permission('Material_Outgoing.Delete');

?>
<div class="box box-primary">
    <div class="box-body" id="print_div">
        <div class="col-12 text-center">
            <h2>PT GUNUNG SAGARA BUANA</h2>
        </div>
        <div class="col-6">
            <table class=" w-100" style="border: none !important;">
                <tr>
                    <th>Dari Gudang</th>
                    <th>:</th>
                    <th><?= $results['data_request']->nm_wr_nm_from ?></th>
                    <th>Ke Gudang</th>
                    <th>:</th>
                    <th><?= $results['data_request']->nm_wr_nm_to ?></th>
                </tr>
                <tr>
                    <th>No. Transaksi</th>
                    <th>:</th>
                    <th><?= $results['data_request']->id ?></th>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th>Tanggal Request</th>
                    <th>:</th>
                    <th><?= date('d-m-Y', strtotime($results['data_request']->tgl_request)) ?></th>
                    <th colspan="4"></th>
                </tr>
            </table>
        </div>
        <div class="col-12">
            <table class="table table-bordered mt-2" border="1">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Material Name</th>
                        <th class="text-center">Request</th>
                        <th class="text-center">Pengeluaran</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($results['data_request_material'] as $request_material) {
                        echo '
                            <tr>
                                <td class="text-center">' . $no . '</td>
                                <td class="text-center">' . $request_material->category_nm . '</td>
                                <td class="text-center">' . $request_material->nama . '</td>
                                <td class="text-center">' . number_format($request_material->request_qty) . '</td>
                                <td class="text-center">' . number_format($request_material->request_qty) . '</td>
                                <td class="">' . $request_material->keterangan . '</td>
                            </tr>
                        ';

                        $no++;
                    }
                    ?>
                </tbody>
            </table>
            <table class="w-100">
                <tbody>
                    <tr>
                        <td colspan="4" style="width: 450px;"></td>
                        <td>
                            <span>
                                Disiapkan, <br><br><br> ...............................
                            </span>
                        </td>
                        <td>
                            <span>
                                Penerima, <br><br><br> ...............................
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-right">
        <button type="button" class="btn btn-sm btn-info" onclick="printDiv('print_div')">Print</button>
    </div>
</div>

<script>
    // window.print();
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
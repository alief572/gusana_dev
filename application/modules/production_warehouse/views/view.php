<?php
$ENABLE_ADD     = has_permission('Production_Warehouse.Add');
$ENABLE_MANAGE  = has_permission('Production_Warehouse.Manage');
$ENABLE_VIEW    = has_permission('Production_Warehouse.View');
$ENABLE_DELETE  = has_permission('Production_Warehouse.Delete');

?>
<div class="box box-primary">
    <div class="box-body">
        <form id="data-form" method="post">
            <div class="col-6">
                <table class="w-100">
                    <tr>
                        <th>Material Code</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_stock_material']->category_nm; ?></th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th>Material Name</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_stock_material']->nama; ?></th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th>Packing</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_stock_material']->nm_packaging; ?></th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th>Conversion</th>
                        <th class="text-center">:</th>
                        <th><?= number_format($results['data_stock_material']->konversi); ?></th>
                        <th>Satuan</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_stock_material']->nm_unit; ?></th>
                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="col-3"></div>
            </div>
            <table class="table table-striped mt-15">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">History Date</th>
                        <th class="text-center">By</th>
                        <th class="text-center">Dari Gudang</th>
                        <th class="text-center">Ke Gudang</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Conversion (Kg)</th>
                        <th class="text-center">Stock Awal (Kg)</th>
                        <th class="text-center">Stock Akhir</th>
                        <th class="text-center">No Transaksi</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $no = 1;
                    $stock_awal = 0;
                    // print_r($results['list_history']);
                    // exit;
                    if (count($results['list_history']) > 0) {
                        foreach ($results['list_history'] as $history) {
                            // print_r($history->no_transaksi);
                            // exit;
                            if ($history->no_transaksi !== NULL) {
                                $stock_akhir = ($stock_awal + $history->qty_down);

                                $qty = $history->qty_down;

                                echo '
                                    <tr>
                                        <td class="text-center">' . $no . '</td>
                                        <td class="text-center">' . date('d F Y', strtotime($history->history_date)) . '</td>
                                        <td class="text-center">' . $history->nm_by . '</td>
                                        <td class="text-center">' . $history->dari_gudang . '</td>
                                        <td class="text-center">' . $history->ke_gudang . '</td>
                                        <td class="text-center">' . number_format($qty) . '</td>
                                        <td class="text-center">' . number_format($history->konversi) . '</td>
                                        <td class="text-center">' . number_format($stock_awal * $history->konversi) . '</td>
                                        <td class="text-center">' . number_format($stock_akhir * $history->konversi) . '</td>
                                        <td class="text-center">' . $history->no_transaksi . '</td>
                                        <td class="text-center">' . $history->keterangan . '</td>
                                    </tr>
                                ';
                                $stock_awal += $history->qty_down;
                                $no++;
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
</div>




<script></script>
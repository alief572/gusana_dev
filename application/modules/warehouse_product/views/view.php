<?php
$ENABLE_ADD     = has_permission('Stock_Product.Add');
$ENABLE_MANAGE  = has_permission('Stock_Product.Manage');
$ENABLE_VIEW    = has_permission('Stock_Product.View');
$ENABLE_DELETE  = has_permission('Stock_Product.Delete');

?>
<div class="box box-primary">
    <div class="box-body">
        <form id="data-form" method="post">
            <div class="col-6">
                <table class="w-100">
                    <tr>
                        <th>Material Code</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_stock_product']->category_nm; ?></th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th>Material Name</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_stock_product']->nama; ?></th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th>Packing</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_stock_product']->nm_packaging; ?></th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th>Conversion</th>
                        <th class="text-center">:</th>
                        <th><?= number_format($results['data_stock_product']->konversi); ?></th>
                        <th>Satuan</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_stock_product']->nm_unit; ?></th>
                    </tr>
                </table>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">History Date</th>
                        <th class="text-center">By</th>
                        <th class="text-center">Dari Gudang</th>
                        <th class="text-center">Ke Gudang</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Stock Awal</th>
                        <th class="text-center">Stock Akhir</th>
                        <th class="text-center">No Transaksi</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
            </table>
        </form>
    </div>
</div>




<script></script>
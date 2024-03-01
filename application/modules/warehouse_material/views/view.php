<?php
$ENABLE_ADD     = has_permission('Stock_Material.Add');
$ENABLE_MANAGE  = has_permission('Stock_Material.Manage');
$ENABLE_VIEW    = has_permission('Stock_Material.View');
$ENABLE_DELETE  = has_permission('Stock_Material.Delete');

?>
<div class="box box-primary">
    <div class="box-body">
        <form id="data-form" method="post">
            <div class="col-6">
                <table class="w-100">
                    <tr>
                        <th style="vertical-align: top;">Material Code</th>
                        <th style="vertical-align: top;" class="text-center">:</th>
                        <th style="vertical-align: top;"><?= $results['data_stock_material']->category_nm; ?></th>
                        <th style="vertical-align: top;" colspan="3"></th>
                    </tr>
                    <tr>
                        <th style="vertical-align: top;">Material Name</th>
                        <th style="vertical-align: top;" class="text-center">:</th>
                        <th style="vertical-align: top;"><?= $results['data_stock_material']->nama; ?></th>
                        <th style="vertical-align: top;" colspan="3"></th>
                    </tr>
                    <tr>
                        <th style="vertical-align: top;">Packing</th>
                        <th style="vertical-align: top;" class="text-center">:</th>
                        <th style="vertical-align: top;"><?= $results['data_stock_material']->nm_packaging; ?></th>
                        <th style="vertical-align: top;" colspan="3"></th>
                    </tr>
                    <tr>
                        <th style="vertical-align: top;">Conversion</th>
                        <th style="vertical-align: top;" class="text-center">:</th>
                        <th style="vertical-align: top;"><?= number_format($results['data_stock_material']->konversi); ?></th>
                        <th style="vertical-align: top;">Satuan</th>
                        <th style="vertical-align: top;" class="text-center">:</th>
                        <th style="vertical-align: top;"><?= $results['data_stock_material']->nm_unit; ?></th>
                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="col-1 mt-5">
                    <span>From</span>
                </div>
                <div class="col-3 mt-5">
                    <input type="date" name="" id="" class="form-control form-control-sm from_tgl">
                </div>
                <div class="col-1 mt-5">
                    <span>To</span>
                </div>
                <div class="col-3 mt-5">
                    <input type="date" name="" id="" class="form-control form-control-sm to_tgl">
                </div>
                <div class="col-3 mt-5">
                    <button type="button" class="btn btn-sm btn-primary search_history" data-id="<?= $results['data_stock_material']->id_category1 ?>"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>

            <table class="table table-striped mt-15">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">History Date</th>
                        <th class="text-center">By</th>
                        <th class="text-center">Dari Gudang</th>
                        <th class="text-center">Ke Gudang</th>
                        <th class="text-center">Stock Awal</th>
                        <th class="text-center">Qty (+)</th>
                        <th class="text-center">Qty (-)</th>
                        <th class="text-center">Stock Akhir</th>
                        <th class="text-center">No Transaksi</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="list_history">

                </tbody>
            </table>
        </form>
    </div>
</div>

<script>
    $(document).on('click', '.search_history', function() {
        var id = $(this).data('id');

        var from_tgl = $('.from_tgl').val();
        var to_tgl = $('.to_tgl').val();

        if (from_tgl == '' || to_tgl == '') {
            Swal.fire({
                title: 'Warning !',
                text: 'Please input From and To Date properly !',
                icon: 'warning'
            });
        } else {
            $.ajax({
                type: 'POST',
                url: siteurl + thisController + 'search_history',
                data: {
                    'id_category1': id,
                    'from_tgl': from_tgl,
                    'to_tgl': to_tgl
                },
                cache: false,
                dataType: 'json',
                beforeSend: function(result) {
                    $('.search_history').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                success: function(result) {
                    $('.search_history').html('<i class="fa fa-search"></i> Search');

                    $('.list_history').html(result.hasil);
                },
                error: function(result) {
                    swal.fire({
                        title: 'Warning !',
                        text: 'Sorry, please try again !',
                        icon: 'warning'
                    });
                }
            });
        }
    });
</script>
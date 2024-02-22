<?php
$ENABLE_ADD     = has_permission('Material_Outgoing.Add');
$ENABLE_MANAGE  = has_permission('Material_Outgoing.Manage');
$ENABLE_VIEW    = has_permission('Material_Outgoing.View');
$ENABLE_DELETE  = has_permission('Material_Outgoing.Delete');

?>
<div class="box box-primary">
    <div class="box-body">
        <form id="data-form" method="post">
            <div class="col-6">
                <table class="w-100">
                    <tr>
                        <th>No. Request</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_request']->id ?></th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th>From</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_request']->nm_wr_from; ?></th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th>To</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_request']->nm_wr_to; ?></th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th>Remarks</th>
                        <th class="text-center">:</th>
                        <th><?= $results['data_request']->keterangan; ?></th>
                        <th colspan="3"></th>
                    </tr>
                </table>
            </div>
            <table class="table table-striped mt-15">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Material Name</th>
                        <th class="text-center">Packing</th>
                        <th class="text-center">Request</th>
                        <th class="text-center">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($results['data_request_detail'] as $detail_material) {
                        echo '
                            <tr>
                                <td class="text-center">' . $no . '</td>
                                <td class="text-center">' . $detail_material->category_nm . '</td>
                                <td class="text-center">' . $detail_material->nama . '</td>
                                <td class="text-center">' . $detail_material->nm_packaging . '</td>
                                <td class="text-center">' . number_format($detail_material->request_qty) . '</td>
                                <td class="text-center">' . $detail_material->keterangan . '</td>
                            </tr>
                        ';
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
</div>




<script></script>
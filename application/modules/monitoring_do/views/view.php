<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">No. DO</th>
                        <th class="text-center">No. Print</th>
                        <th class="text-center">Tanggal Pengiriman</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($list_delivery as $delivery) {
                        echo '
                            <tr>
                                <td class="text-center">' . $no . '</td>
                                <td class="text-center">' . $delivery->id_do . '</td>
                                <td class="text-center">' . $delivery->id_print_do . '</td>
                                <td class="text-center">' . date('d F Y', strtotime($delivery->tgl_kirim)) . '</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info choose_do_deliver choose_do_delivery_' . $delivery->id_print_do . '" data-id_print_do="' . $delivery->id_print_do . '" data-id_do="' . $delivery->id_do . '">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        ';

                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container detail_do_delivery">

    </div>
</div>
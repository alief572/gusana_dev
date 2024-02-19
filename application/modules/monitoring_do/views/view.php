<div class="card-body" id="data-form-customer">
    <div class="row">

        <div class="col-12">
            <input type="hidden" name="id_do" value="<?= $get_penawaran->id_do ?>">
            <h3>Delivery Order</h3>
            <table class="w-100">
                <tr>
                    <th>No DO</th>
                    <th>:</th>
                    <td><?= $get_penawaran->id_do ?></td>
                </tr>
                <tr>
                    <th>DO Date</th>
                    <th>:</th>
                    <td><?= date('d F Y', strtotime($get_detail_do[0]->tgl_kirim)) ?></td>
                </tr>
                <tr>
                    <th>Upload Delivery Proof</th>
                    <th>:</th>
                    <td>
                        <?php

                        if ($get_penawaran->sts_close_do !== 'close') {
                            echo '<input type="file" name="upload_bukti_kirim" id="" class="form-control form-control-sm">';
                        }
                        ?>
                        <?= $link_bukti ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-12" style="margin-top:15px;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center" style="width:150px;">Product Name</th>
                        <th class="text-center">Qty Order</th>
                        <th class="text-center">Packing</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Qty Sent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $no = 1;
                    foreach ($get_detail_do as $detail_do) :
                        $penawaran_detail = $this->db->get_where('ms_penawaran_detail', ['id_penawaran' => $get_penawaran->id_penawaran, 'id_product' => $detail_do->id_product])->row();

                    ?>
                        <tr>
                            <td class="text-center"><?= $no ?></td>
                            <td class="text-center"><?= $detail_do->nm_product ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->qty, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->konversi, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->weight, 2) ?></td>
                            <td class="text-center"><?= number_format($detail_do->qty, 2) ?></td>
                        </tr>
                    <?php

                        $no++;
                    endforeach;

                    ?>
                </tbody>
            </table>
        </div>
        ';
    </div>
</div>
</div>
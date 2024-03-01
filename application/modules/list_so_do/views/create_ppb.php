<input type="hidden" name="id_quote" value="<?= $data_penawaran->id_quote; ?>">
<input type="hidden" name="create_ppb" value="1">
<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-12">
            <table class="table">
                <tr>
                    <th class="text-left">Customer Name</th>
                    <th class="text-left">: <?= $data_penawaran->nm_cust ?></th>
                    <th class="text-left">Quote Number</th>
                    <th class="text-left">: <?= $id_quote ?></th>
                </tr>
                <tr>
                    <th class="text-left">Customer Address</th>
                    <th class="text-left">: <?= $data_penawaran->address_cust ?></th>
                    <th class="text-left">Quote Date</th>
                    <th class="text-left">: <?= date('d F Y', strtotime($data_penawaran->tgl_penawaran)) ?></th>
                </tr>
                <tr>
                    <th class="text-left">Contact Person</th>
                    <th class="text-left">: <?= $data_penawaran->nm_pic_cust ?></th>
                    <th class="text-left">Delivery Date</th>
                    <th class="text-left">: <?= date('d F Y', strtotime($data_penawaran->deliver_date)) ?></th>
                </tr>
                <tr>
                    <th class="text-left">Delivery Type</th>
                    <th class="text-left">: <?= ($data_penawaran->deliver_type == 1) ? 'Delivery' : 'Self Pickup' ?></th>
                    <th class="text-left">Sales</th>
                    <th class="text-left">: <?= $data_penawaran->nm_marketing ?></th>
                </tr>
                <tr>
                    <th class="text-left">PPN/Non PPN</th>
                    <th class="text-left">: <?= ($data_penawaran->ppn_type == 1) ? '<div class="badge badge-success">PPN</div>' : '<div class="badge badge-danger">Non PPN</div>' ?></th>
                    <th class="text-left">Delivery Date</th>
                    <th class="text-left">
                        <?= date('d F Y', strtotime($data_penawaran->deliver_date)) ?>
                    </th>
                </tr>
            </table>
        </div>
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">
                            <span>
                                序号 <br>
                                No.
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                产品名称 <br>
                                Product Name
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                数量 <br>
                                Qty
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                重量 <br>
                                Weight (Kg)
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                备注 <br>
                                Keterangan
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                Free Stock
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 0;
                    $ttl_harga = 0;
                    foreach ($data_penawaran_detail as $penawaran_detail) {
                        $x++;

                        $get_product_master = $this->db->get_where('ms_product_category3', ['id_category3' => $penawaran_detail->id_product])->row();

                        if ($get_product_master->curing_agent !== '') {
                            $get_free_stock = $this->db->query('
                                SELECT
                                    (a.qty_asli / b.konversi) AS ttl_all
                                FROM
                                    ms_stock_product a
                                    LEFT JOIN ms_product_category3 b ON b.id_category3 = a.id_product
                                WHERE
                                    a.id_product = "' . $get_product_master->id_product_refer . '"
                            ')->row();
                            $get_booking_stock = $this->db->query('
                                SELECT
                                    SUM(a.qty) AS ttl_booking_stock
                                FROM
                                    ms_penawaran_detail a 
                                    LEFT JOIN ms_penawaran b ON b.id_penawaran = a.id_penawaran
                                WHERE
                                    a.id_product = "' . $penawaran_detail->id_product . '" AND
                                    b.sts = "so_created" AND
                                    b.sts_do IS NULL
                            ')->row();
                        } else {
                            $get_free_stock = $this->db->query('
                                SELECT
                                    (a.qty_asli / b.konversi) AS ttl_all
                                FROM
                                    ms_stock_product a
                                    LEFT JOIN ms_product_category3 b ON b.id_category3 = a.id_product
                                WHERE
                                    a.id_product = "' . $penawaran_detail->id_product . '"
                            ')->row();
                            $get_booking_stock = $this->db->query('
                                SELECT
                                    SUM(a.qty) AS ttl_booking_stock
                                FROM
                                    ms_penawaran_detail a 
                                    LEFT JOIN ms_penawaran b ON b.id_penawaran = a.id_penawaran
                                WHERE
                                    a.id_product = "' . $penawaran_detail->id_product . '" AND
                                    b.sts = "so_created" AND
                                    b.sts_do IS NULL
                            ')->row();
                        }

                        $free_stock = (($get_free_stock->ttl_all - $get_booking_stock->ttl_booking_stock));
                        $request_production = ($penawaran_detail->qty - $free_stock);
                        if ($free_stock > $penawaran_detail->qty) {
                            $request_production = 0;
                        }

                    ?>
                        <tr>
                            <td class="text-center"><?= $x ?></td>
                            <td class="text-center"><?= $penawaran_detail->nm_product ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->qty, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->weight, 2) ?></td>
                            <!-- <td class="text-center"><?= number_format($penawaran_detail->harga_satuan, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->total_harga, 2) ?></td> -->
                            <td class="text-center"><?= $penawaran_detail->keterangan ?></td>
                            <td class="text-center"><?= number_format($free_stock, 2) ?></td>
                        </tr>
                    <?php
                        $ttl_harga += $penawaran_detail->total_harga;
                    };
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
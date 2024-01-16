<input type="hidden" name="id_penawaran" value="<?= $id_penawaran; ?>">
<input type="hidden" name="loss_penawaran" value="1">
<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-12">
            <table class="table">
                <tr>
                    <th class="text-left">Customer Name <span class="text-danger">(客户名称)</span></th>
                    <th class="text-left">: <?= $data_penawaran->nm_cust ?></th>
                    <th class="text-left">Quote Number <span class="text-danger">(报价编号)</span></th>
                    <th class="text-left">: <?= ($data_penawaran->id_quote !== null) ? $data_penawaran->id_quote : $data_penawaran->id_penawaran ?></th>
                </tr>
                <tr>
                    <th class="text-left">Customer Address <span class="text-danger">(客户地址)</span></th>
                    <th class="text-left">: <?= $data_penawaran->address_cust ?></th>
                    <th class="text-left">Quote Date <span class="text-danger">(报价日期)</span></th>
                    <th class="text-left">: <?= date('d F Y', strtotime($data_penawaran->tgl_penawaran)) ?></th>
                </tr>
                <tr>
                    <th class="text-left">Contact Person <span class="text-danger">(联系人)</span></th>
                    <th class="text-left">: <?= $data_penawaran->nm_pic_cust ?></th>
                    <th class="text-left">Delivery Date <span class="text-danger">(交货日期)</span></th>
                    <th class="text-left">: <?= date('d F Y', strtotime($data_penawaran->deliver_date)) ?></th>
                </tr>
                <tr>
                    <th class="text-left">Delivery Type <span class="text-danger">(交货类型)</span></th>
                    <th class="text-left">: <?= ($data_penawaran->deliver_type == 1) ? 'Delivery' : 'Self Pickup' ?></th>
                    <th class="text-left">Sales <span class="text-danger">(销售人员姓名)</span></th>
                    <th class="text-left">: <?= $data_penawaran->nm_marketing ?></th>
                </tr>
                <tr>
                    <th class="text-left">PPN/Non PPN</th>
                    <th class="text-left" colspan="3">: <?= ($data_penawaran->ppn_type == 1) ? '<div class="badge badge-success">PPN</div>' : '<div class="badge badge-danger">Non PPN</div>' ?></th>
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
                                单价 <br>
                                Harga Satuan (Rp/Kg)
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                金额 <br>
                                Total Harga
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                备注 <br>
                                Keterangan
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 0;
                    $ttl_harga = 0;
                    foreach ($data_penawaran_detail as $penawaran_detail) : $x++; ?>
                        <tr>
                            <td class="text-center"><?= $x ?></td>
                            <td class="text-center">
                                <span class="text-danger"><?= $penawaran_detail->nama_mandarin ?></span> <br>
                                <?= $penawaran_detail->nm_product ?>
                            </td>
                            <td class="text-center"><?= number_format($penawaran_detail->qty, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->weight, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->harga_satuan, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->total_harga, 2) ?></td>
                            <td class="text-center"><?= $penawaran_detail->keterangan ?></td>
                        </tr>
                    <?php
                        $ttl_harga += $penawaran_detail->total_harga;
                    endforeach;
                    ?>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="3" class="">Subtotal <span class="text-danger">(小计)</span></td>
                        <td class="text-right"><?= number_format(($ttl_harga), 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="3" class="">Biaya Pengiriman <span class="text-danger">(交付成本)</span></td>
                        <td class="text-right"><?= number_format($data_penawaran->biaya_pengiriman, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="3" class="">Pengiriman (Dari)</td>
                        <td class="text-right"><?= $data_penawaran->dari_tmp ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="3" class="">Pengiriman (Ke)</td>
                        <td class="text-right"><?= $data_penawaran->ke_tmp ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="" colspan="3">Discount <?= ($data_penawaran->disc_persen > 0) ? $data_penawaran->disc_persen . '%' : null ?> <span class="text-danger">(折扣)</span></td>
                        <td class="text-right"><?= number_format($data_penawaran->nilai_disc, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="" colspan="3">Price After Discount <span class="text-danger">(折扣后价格)</span></td>
                        <td class="text-right"><?= number_format($ttl_harga + $data_penawaran->biaya_pengiriman - $data_penawaran->nilai_disc, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="" colspan="3">PPN 11%</td>
                        <td class="text-right"><?= number_format(($ttl_harga - $data_penawaran->nilai_disc + $data_penawaran->biaya_pengiriman) * 11 / 100, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="3" class="">Grand Total <span class="text-danger">(总计)</span></td>
                        <td class="text-right"><?= number_format(($ttl_harga - $data_penawaran->nilai_disc + (($ttl_harga - $data_penawaran->nilai_disc + $data_penawaran->biaya_pengiriman) * 11 / 100) + $data_penawaran->biaya_pengiriman), 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-12">
            <table class="table">
                <tr>
                    <th class="text-left">No. Penawaran</th>
                    <th class="text-left">: <?= $data_penawaran->id_penawaran ?></th>
                    <th class="text-left">Tanggal</th>
                    <th class="text-left">: <?= date('d F Y', strtotime($data_penawaran->tgl_penawaran)) ?></th>
                </tr>
                <tr>
                    <th class="text-left">Nama Customer</th>
                    <th class="text-left">: <?= $data_penawaran->nm_cust ?></th>
                    <th class="text-left">Sales/Marketing</th>
                    <th class="text-left">: <?= $data_penawaran->nm_marketing ?></th>
                </tr>
                <tr>
                    <th class="text-left">Nomor Telepon</th>
                    <th class="text-left">: <?= $data_penawaran->no_telp_cust ?></th>
                    <th class="text-left">PIC Customer</th>
                    <th class="text-left">: <?= $data_penawaran->nm_pic_cust ?></th>
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
                        <th class="text-center">No</th>
                        <th class="text-center">Produk</th>
                        <th class="text-center">Kode Produk</th>
                        <th class="text-center">Spesifikasi Kemasan</th>
                        <th class="text-center">RAL Code</th>
                        <th class="text-center">Qty (Kaleng)</th>
                        <th class="text-center">Weight (Kg)</th>
                        <th class="text-center">Harga Satuan (Rp/Kg)</th>
                        <th class="text-center">Stock Tersedia (Kaleng)</th>
                        <th class="text-center">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 0;
                    $ttl_harga = 0;
                    foreach ($data_penawaran_detail as $penawaran_detail) : $x++; ?>
                        <tr>
                            <td class="text-center"><?= $x ?></td>
                            <td class="text-center"><?= $penawaran_detail->nm_product ?></td>
                            <td class="text-center"><?= $penawaran_detail->kode_product ?></td>
                            <td class="text-center"><?= $penawaran_detail->konversi . ' ' . $penawaran_detail->packaging ?></td>
                            <td class="text-center"><?= $penawaran_detail->ral_code ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->qty, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->weight, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->harga_satuan, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->free_stock, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->total_harga, 2) ?></td>
                        </tr>
                    <?php
                        $ttl_harga += $penawaran_detail->total_harga;
                    endforeach;
                    ?>
                    <tr>
                        <td colspan="6"></td>
                        <td colspan="3">Total</td>
                        <td class="text-right"><?= number_format($ttl_harga, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                        <td>Discount</td>
                        <td class="text-right"><?= number_format($data_penawaran->disc_num, 2) ?></td>
                        <td class="text-right"><?= number_format($data_penawaran->disc_persen, 2) ?>%</td>
                        <td class="text-right"><?= number_format($data_penawaran->nilai_disc, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                        <td colspan="3">Price After Discount</td>
                        <td class="text-right"><?= number_format(($ttl_harga - $data_penawaran->nilai_disc), 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                        <td>PPN</td>
                        <td colspan="2" class="text-right"><?= number_format($data_penawaran->ppn_persen, 2) ?>%</td>
                        <td class="text-right"><?= number_format(($ttl_harga - $data_penawaran->nilai_disc), 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                        <td>Biaya Pengiriman</td>
                        <td colspan="3" class="text-right"><?= number_format($data_penawaran->biaya_pengiriman, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                        <td colspan="3">Grand Total</td>
                        <td class="text-right"><?= number_format(($ttl_harga - $data_penawaran->nilai_disc + $data_penawaran->ppn_num + $data_penawaran->biaya_pengiriman), 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
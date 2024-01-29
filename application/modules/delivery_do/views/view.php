<?php
$ENABLE_ADD     = has_permission('So_Invoice.Add');
$ENABLE_MANAGE  = has_permission('So_Invoice.Manage');
$ENABLE_VIEW    = has_permission('So_Invoice.View');
$ENABLE_DELETE  = has_permission('So_Invoice.Delete');
?>

<style>
    .non-bordered {
        border: none !important;
    }
</style>

<div class="br-pagebody pd-x-20 pd-sm-x-30 mg-y-3">
    <div class="card bd-gray-400" id="card">
        <table class="w-100">
            <tr class="non-bordered">
                <th style="border-bottom: 2px solid black;" class="text-center">
                    <img src="<?= base_url('assets/img/Gusana.png'); ?>" alt="" style="width: 160px;">
                </th>
                <th style="border-bottom: 2px solid black;">
                    <h2>PT GUNUNG SAGARA BUANA</h2>
                </th>
                <th style="border-bottom: 2px solid black;">
                    <span>
                        Jl. Gusana, RT.015/RW.005, Jatiasih, Bekasi, <br>
                        Jawa Barat 17426 <br>
                        Telp.(021)-48675810
                    </span>
                </th>
            </tr>
        </table>
        <table class="w-100">
            <tr class="non-bordered">
                <th class="non-bordered text-center" colspan="6">
                    <h2>
                        <span>
                            销售清单 <br>
                            Delivery Order
                        </span>
                    </h2>
                </th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered">
                    <span>
                        客户名称 <br>
                        Nama Pelanggan
                    </span>
                </th>
                <th class="non-bordered">:</th>
                <th class="non-bordered"><?= $data_penawaran->nm_cust ?></th>
                <th class="non-bordered">
                    <span>
                        订单号 <br>
                        Nomor PO
                    </span>
                </th>
                <th class="non-bordered">:</th>
                <th class="non-bordered" style="min-width: 300px; max-width: 300px;"><?= $data_penawaran->id_quote ?></th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered">
                    <span>
                        收货地址 <br>
                        Alamat Pengiriman
                    </span>
                </th>
                <th class="non-bordered">:</th>
                <th class="non-bordered" style="min-width: 300px; max-width: 300px;"><?= $data_penawaran->address_cust ?></th>
                <th class="non-bordered">
                    <span>
                        订单日期 <br>
                        Tanggal PO
                    </span>
                </th>
                <th class="non-bordered">:</th>
                <th class="non-bordered" style="min-width: 300px; max-width: 300px;"><?= $data_penawaran->tgl_penawaran ?></th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered">
                    <span>
                        收货人 <br>
                        Penerima Barang
                    </span>
                </th>
                <th class="non-bordered">:</th>
                <th class="non-bordered"><?= ($data_penawaran->deliver_type == 1) ? '(送貨 Delivery)' : '(自己提货 Self Pickup)' ?>
                </th>
                <th class="non-bordered">
                    <span>
                        发货日期 <br>
                        Tanggal Pengiriman
                    </span>
                </th>
                <th class="non-bordered">:</th>
                <th class="non-bordered" style="min-width: 300px; max-width: 300px;"><?= $data_penawaran->deliver_date ?></th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered">
                    <span>
                        联系电话 <br>
                        Nomor Kontak
                    </span>
                </th>
                <th class="non-bordered">:</th>
                <th class="non-bordered" style="min-width: 300px; max-width: 300px;"><?= $data_penawaran->nm_pic_cust . ' (' . $pic_phone . ')' ?></th>
                <th class="non-bordered" colspan="3">
                </th>
            </tr>
        </table>
        <table class="w-100" border="1">
            <thead>
                <tr class="non-bordered">
                    <th class="text-center">
                        <span>序号<br>No.</span>
                    </th>
                    <th class="text-center">
                        <span>型号<br>Product Code</span>
                    </th>
                    <th class="text-center">
                        <span>产品名称及型号<br>Nama dan Jenis Barang</span>
                    </th>
                    <th class="text-center">
                        <span>包装规格(kg/桶)<br>Spesifikasi Kemasan</span>
                    </th>
                    <th class="text-center">
                        <span>单位<br>Unit</span>
                    </th>
                    <th class="text-center">
                        <span>数量<br>Quantity</span>
                    </th>
                    <th class="text-center">
                        <span>色号<br>Kode Warna</span>
                    </th>
                    <th class="text-center">
                        <span>备注<br>Keterangan</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $x = 0;
                $ttl_berat = 0;
                foreach ($data_penawaran_detail as $penawaran_detail) :
                    $x++;

                    $this->db->select('SUM(a.qty) AS qty_delivery');
                    $this->db->from('ms_detail_do a');
                    $this->db->where('a.id_do', $data_penawaran->id_do);
                    $this->db->where('a.id_product', $penawaran_detail->id_product);
                    $get_qty_delivery = $this->db->get()->row();


                    echo '
                            <tr class="non-bordered">
                                <td class="text-center">' . $x . '</td>
                                <td class="text-center">' . $penawaran_detail->kode_product . '</td>
                                <td class="text-center"><span>' . $penawaran_detail->nama_mandarin . '<br>' . $penawaran_detail->nm_product . '</span></td>
                                <td class="text-center">' . $penawaran_detail->konversi . '/订单 <br> ' . $penawaran_detail->packaging . '</td>
                                <td class="text-center">' . $penawaran_detail->nm_packaging . '</td>
                                <td class="text-center">' . number_format($get_qty_delivery->qty_delivery, 2) . '</td>
                                <td class="text-center">' . $penawaran_detail->kode_warna . '</td>
                                <td class="text-right"></td>
                            </tr>
                        ';
                    $ttl_berat += ($penawaran_detail->qty * $penawaran_detail->konversi);
                endforeach;
                ?>
                <tr>
                    <th colspan="4" class="text-right"><span>总重量 TOTAL BERAT</span></th>
                    <th colspan="4" class="text-center"><?= number_format($ttl_berat, 2) ?> Kg</th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right"><span>总体积 TOTAL VOLUME </span></th>
                    <th colspan="4" class="text-center">m3</th>
                </tr>
                <tr>
                    <th colspan="2">
                        <span>
                            销售内勤（签字）: <br>
                            Dibuat (TTD):
                        </span>
                    </th>
                    <th>
                        <span>
                            仓库（签字）：<br>
                            Gudang (TTD):
                        </span>
                    </th>
                    <th>
                        <span>
                            审核 （签字）: <br>
                            Diperiksa (TTD):
                        </span>
                    </th>
                    <th colspan="3">
                        <span>
                            收货人（签字）: <br>
                            Penerima (TTD):
                        </span>
                    </th>
                    <th>
                        <span>
                            司机(签字): <br>
                            Sopir (TTD)
                        </span>
                    </th>
                </tr>
                <tr>
                    <th style="height:120px;" colspan="2"></th>
                    <th style="height:120px;"></th>
                    <th style="height:120px;"></th>
                    <th style="height:120px;" colspan="3"></th>
                    <th style="height:120px;"></th>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="text-right">
        <button type="button" class="btn btn-sm btn-info" onclick="printDiv('card')">Print</button>
    </div>
</div>

<script>
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
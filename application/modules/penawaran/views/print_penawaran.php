<?php
$ENABLE_ADD     = has_permission('Penawaran.Add');
$ENABLE_MANAGE  = has_permission('Penawaran.Manage');
$ENABLE_VIEW    = has_permission('Penawaran.View');
$ENABLE_DELETE  = has_permission('Penawaran.Delete');
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
                    <table class="w-100">
                        <tr class="non-bordered">
                            <th class="non-bordered">NAME</th>
                            <th class="non-bordered">:</th>
                            <th class="non-bordered">PT. GUNUNG SAGARA BUANA</th>
                        </tr>
                        <tr class="non-bordered">
                            <th class="non-bordered">AFTER SALES</th>
                            <th class="non-bordered">:</th>
                            <th class="non-bordered">(021)-48675810</th>
                        </tr>
                        <tr class="non-bordered">
                            <th class="non-bordered">SERVICE E-MAIL</th>
                            <th class="non-bordered">:</th>
                            <th class="non-bordered">Gusanaservices@gmail.com</th>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>
        <table class="w-100">
            <tr class="non-bordered">
                <th class="non-bordered text-center" colspan="6">
                    <h2><span>报价单 <br> Quotation</span></h2>
                </th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered">
                    <span>
                        客户名称 <br>
                        Customer Name
                    </span>
                </th>
                <th class="non-bordered">:</th>
                <th class="non-bordered"><?= $data_penawaran->nm_cust ?></th>
                <th class="non-bordered">
                    <span>
                        报价编号 <br>
                        Quote Number
                    </span>
                </th>
                <th class="non-bordered">:</th>
                <th class="non-bordered" style="min-width: 300px; max-width: 300px;"><?= $data_penawaran->id_penawaran ?></th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered">
                    <span>
                        收货地址 <br>
                        Cust Address
                    </span>
                </th>
                <th class="non-bordered">:</th>
                <th class="non-bordered" style="min-width: 300px; max-width: 300px;"><?= $data_penawaran->address_cust ?></th>
                <th class="non-bordered">
                    <span>
                        报价日期 <br>
                        Quote Date
                    </span>
                </th>
                <th class="non-bordered">:</th>
                <th class="non-bordered" style="min-width: 300px; max-width: 300px;"><?= $data_penawaran->tgl_penawaran ?></th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered">联系人</th>
                <th class="non-bordered">:</th>
                <th class="non-bordered"><?= ($data_penawaran->deliver_type == 1) ? '(送貨 Delivery)' : '(自己提货 Self Pickup)' ?>
                </th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered">
                    <span>
                        Contact Person
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
                        <span>产品名称<br>Product Name</span>
                    </th>
                    <th class="text-center">
                        <span>产品型号<br>Product Code</span>
                    </th>
                    <th class="text-center">
                        <span>包装规格<br>Packaging Spec (Kg)</span>
                    </th>
                    <th class="text-center">
                        <span>配套固化剂<br>Supporting Curing Agent</span>
                    </th>
                    <th class="text-center">
                        <span>色标<br>RAL Code</span>
                    </th>
                    <th class="text-center">
                        <span>单位<br>Unit</span>
                    </th>
                    <th class="text-center">
                        <span>数量<br>Qty</span>
                    </th>
                    <th class="text-center">
                        <span>重量<br>Total Weight (Kg)</span>
                    </th>
                    <th class="text-center">
                        <span>单价<br>Unit Price (Rp/kg)</span>
                    </th>
                    <th class="text-center">
                        <span>金额<br>Total Price</span>
                    </th>
                    <th class="text-center">
                        <span>备注<br>Remark</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $x = 0;
                $ttl_harga = 0;
                foreach ($data_penawaran_detail as $penawaran_detail) :
                    $x++;
                    echo '
                            <tr class="non-bordered">
                                <td class="text-center">' . $x . '</td>
                                <td class="text-center"><span>' . $penawaran_detail->nama_mandarin . '<br>' . $penawaran_detail->nm_product . '</span></td>
                                <td class="text-center">' . $penawaran_detail->kode_product . '</td>
                                <td class="text-center">' . $penawaran_detail->konversi . '</td>
                                <td class="text-center">' . $penawaran_detail->package_spec_curing_agent . '</td>
                                <td class="text-center">' . $penawaran_detail->ral_code . '</td>
                                <td class="text-center">' . $penawaran_detail->nm_packaging . '</td>
                                <td class="text-center">' . $penawaran_detail->qty . '</td>
                                <td class="text-center">' . $penawaran_detail->weight . '</td>
                                <td class="text-right">Rp. ' . number_format($penawaran_detail->harga_satuan, 2) . '</td>
                                <td class="text-right">Rp. ' . number_format($penawaran_detail->total_harga, 2) . '</td>
                                <td class="text-right"></td>
                            </tr>
                        ';
                    $ttl_harga += $penawaran_detail->total_harga;
                endforeach;
                ?>
            </tbody>
            <tbody>
                <tr class="non-bordered">
                    <th colspan="10" class="text-right">
                        <span>
                            运输费 <br>
                            Shipping Fee
                        </span>
                    </th>
                    <td class="text-right">Rp. <?= number_format($data_penawaran->biaya_pengiriman, 2) ?></td>
                </tr>
                <tr class="non-bordered">
                    <th colspan="10" class="text-right">
                        <span>打折扣 <br> Discount <?= ($data_penawaran->disc_persen > 0) ? $data_penawaran->disc_persen . '%' : null ?></span>
                    </th>
                    <td class="text-right">Rp. <?= number_format($data_penawaran->nilai_disc, 2) ?></td>
                </tr>
                <tr class="non-bordered">
                    <th colspan="10" class="text-right">
                        <span>
                            合计 <br>
                            Subtotal
                        </span>
                    </th>
                    <td class="text-right">Rp. <?= number_format($ttl_harga + $data_penawaran->biaya_pengiriman - $data_penawaran->nilai_disc, 2) ?></td>
                </tr>
                <tr class="non-bordered">
                    <th colspan="10" class="text-right">
                        增值税PPN11%
                    </th>
                    <td class="text-right">Rp. <?= number_format($data_penawaran->ppn_num, 2) ?></td>
                </tr>
                <tr class="non-bordered">
                    <th colspan="10" class="text-right">
                        <span>
                            总金额 <br>
                            Total Amount
                        </span>
                    </th>
                    <td class="text-right">Rp. <?= number_format($ttl_harga - $data_penawaran->nilai_disc + $data_penawaran->ppn_num + $data_penawaran->biaya_pengiriman, 2) ?></td>
                </tr>
            </tbody>
        </table>
        <table class=" w-100">
            <tr class="non-bordered">
                <th style="border-left: 1px solid black;">公司名称</th>
                <th class="non-bordered">:</th>
                <th class="non-bordered">PT GUNUNG SAGARA BUANA</th>
                <th class="non-bordered">税卡号码</th>
                <th class="non-bordered">:</th>
                <th style="border-right: 1px solid black;">01.392.203.4-432.000</th>
            </tr>
            <tr class="non-bordered">
                <th style="border-left: 1px solid black;">公司地址</th>
                <th class="non-bordered">:</th>
                <th style="border-right: 1px solid black;" colspan="4">JL.WIBAWA MUKTI II,JL.GUSANA RT.015/RW. 005,KEL.JATISARI, KEC.JATIASIH,KOTA BEKASI, JAWA BARAT. 17426</th>
            </tr>
            <tr class="non-bordered">
                <th style="border-left: 1px solid black;">银行名称</th>
                <th class="non-bordered">:</th>
                <th class="non-bordered">BANK CENTRAL ASIA (BCA)</th>
                <th class="non-bordered">账户名称</th>
                <th class="non-bordered">:</th>
                <th style="border-right: 1px solid black;">PT GUNUNG SAGARA BUANA</th>
            </tr>
            <tr class="non-bordered">
                <th style="border-left: 1px solid black;">银行分司</th>
                <th class="non-bordered">:</th>
                <th class="non-bordered">LIPPO CIKARANG</th>
                <th class="non-bordered">银行账号</th>
                <th class="non-bordered">:</th>
                <th style="border-right: 1px solid black;">522-473-9999</th>
            </tr>
        </table>
        <table class=" w-100" border="1">
            <tr class="non-bordered">
                <th class="non-bordered">
                    <span>备注 <br> Keterangan</span>
                </th>
                <th class="non-bordered">
                    1. 以上价格为现金价，含税(增值税 PPN 11%), 1吨以上含雅加达市区内运费。<br>
                    2. 以上产品价格有效期为15天。产品价格可能随时变化, 根据原材料价格。<br>
                    <span class="text-danger">3. 付款方式：出厂前付清。（根据每单修改）</span>
                </th>
            </tr>
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
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

    .font-bold {
        font-weight: bold;
    }

    .top_table th {
        vertical-align: top;
    }
</style>

<div class="br-pagebody pd-x-20 pd-sm-x-30 mg-y-3">
    <div class="card bd-gray-400" id="card">
        <table class="w-100 top_table">
            <tr class="non-bordered">
                <th class="text-center">
                    <img src="<?= base_url('assets/img/Gusana.png'); ?>" alt="" style="width: 160px;">
                </th>
                <th>
                    <table class="w-100 non-bordered">
                        <tr class="non-bordered">
                            <th class="non-bordered">NAME</th>
                            <th class="non-bordered">:</th>
                            <th class="non-bordered">PT. GUNUNG SAGARA BUANA</th>
                        </tr>
                        <tr class="non-bordered">
                            <th class="non-bordered">ADDRESS</th>
                            <th class="non-bordered">:</th>
                            <th class="non-bordered">Jl. Gusana, RT.015/RW.005, Jatiasih, Kota Bekasi, Jawa Barat 17426</th>
                        </tr>
                        <tr class="non-bordered">
                            <th class="non-bordered">AFTER SALES</th>
                            <th class="non-bordered">:</th>
                            <th class="non-bordered">(021)-48675810</th>
                        </tr>
                        <tr class="non-bordered">
                            <th class="non-bordered">WEBSITE</th>
                            <th class="non-bordered">:</th>
                            <th class="non-bordered">https://www.gusanacoating.com</th>
                        </tr>
                        <tr class="non-bordered">
                            <th class="non-bordered">E-MAIL</th>
                            <th class="non-bordered">:</th>
                            <th class="non-bordered">Gusanaservices@gmail.com</th>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered" style="border-bottom: 4px solid black !important;" colspan="3">
                    <img src="<?= base_url('assets/img/strip_gusana.png') ?>" alt="" style="width:100%;">
                </th>
            </tr>
        </table>
        <table class="w-100 top_table">
            <tr class="non-bordered">
                <th class="non-bordered text-center" colspan="6">
                    <h2><span style="font-weight: bold;">订  货  单</span></h2>
                    <h2><span style="font-weight: bold;">Purchase Order</span></h2>
                </th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered">
                    <span>
                        订货单位 <br>
                        Customer Name
                    </span>
                </th>
                <th class="non-bordered text-center">:</th>
                <th class="non-bordered">
                    <?= $data_penawaran->nm_cust ?>
                </th>
            </tr>
        </table>
        <table class=" w-100" border="1">
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
                        <span>重量<br>Weight(Kg)</span>
                    </th>
                    <th class="text-center">
                        <span>单价<br>Unit Price (Rp/kg)</span>
                    </th>
                    <th class="text-center">
                        <span> 金额<br>Total Amount</span>
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
                                <td class="text-center">' . number_format($penawaran_detail->konversi) . '</td>
                                <td class="text-center">' . str_replace('.00', '', $penawaran_detail->package_spec_curing_agent) . '</td>
                                <td class="text-center">' . $penawaran_detail->mandarin_ral_code . ' <br> ' . $penawaran_detail->ral_code . '</td>
                                <td class="text-center">' . $penawaran_detail->nm_packaging . '</td>
                                <td class="text-center">' . $penawaran_detail->qty . '</td>
                                <td class="text-center">' . $penawaran_detail->weight . '</td>
                                <td class="text-right">Rp. ' . number_format($penawaran_detail->harga_satuan, 2) . '</td>
                                <td class="text-right">Rp. ' . number_format($penawaran_detail->total_harga, 2) . '</td>
                                <td class="text-left">' . $penawaran_detail->keterangan . '</td>
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
                        增值税PPN11%<br>Value added tax
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
        <table class=" w-100" border="1">
            <tr class="non-bordered">
                <th class="text-center">
                    <span>备注<br>Remark
                    </span>
                </th>
                <th class="">
                    <span>
                        <?php
                        if ($data_penawaran->deliver_type == '2') {
                            echo '
                            1.以上价格不含税<span style="color: red;">不含运费</span>(增值税 VAT 11%) <br>
                            1. The above prices do not include tax and shipping fees (value-added tax VAT 11%) <br>
                            2.以上产品价格有效期为15天。产品价格可能随时变化，根据原材料价格 <br>
                            2. The above product prices are valid for 15 days. The product price may change at any time, depending on the raw material costs. <br>
                            3.付款方式: 订单盖章确认后付50%，出厂前支付尾款。<br>
                            3. Payment method: 50% will be paid after the order is stamped and confirmed, and the remaining balance is to be paid before the factory delivery.
                        ';
                        } else {
                            echo '
                            1.以上价格含税含运费(增值税 VAT 11%) <br>
                            1. The above prices include tax and shipping fees (value-added tax VAT 11%) <br>
                            2.以上产品价格有效期为15天。产品价格可能随时变化，根据原材料价格 <br>
                            2. The above product prices are valid for 15 days. The product price may change at any time, depending on the raw material costs. <br>
                            3.付款方式：付款方式是在商品发货前需提前支付。<br>
                            3. Payment method: The payment method requires advance payment before the goods are shipped.
                        ';
                        }
                        ?>
                    </span>
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
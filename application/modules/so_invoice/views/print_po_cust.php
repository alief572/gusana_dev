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
                <th class="non-bordered" style="width: 150px;">
                    <span>
                        订货单位 <br>
                        Customer Name
                    </span>
                </th>
                <th class="non-bordered text-center">:</th>
                <th class="non-bordered">
                    <?= $data_penawaran->nm_cust ?>
                </th>
                <th class="non-bordered"  style="width: 150px;">
                    <span>
                        供货单位 <br>
                        Supplier
                    </span>
                </th>
                <th class="non-bordered text-center">:</th>
                <th class="non-bordered">
                    PT GUNUNG SAGARA BUANA
                </th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered" style="width: 150px;">
                    <span>
                        订货人 / 电话 <br>
                        Orderer / Phone
                    </span>
                </th>
                <th class="non-bordered text-center">:</th>
                <th class="non-bordered">
                    <?= $data_penawaran->nm_pic_cust.' / '.$pic_phone ?>
                </th>
                <th class="non-bordered" style="width: 150px;">
                    <span>
                        订货单号 <br>
                        Order Number
                    </span>
                </th>
                <th class="non-bordered text-center">:</th>
                <th class="non-bordered">
                    <?= $data_penawaran->id_penawaran ?>
                </th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered" style="width: 150px;">
                    <span>
                        收货地址 <br>
                        Delivery Address
                    </span>
                </th>
                <th class="non-bordered text-center">:</th>
                <th class="non-bordered">
                    <?= $data_penawaran->address_cust ?>
                </th>
                <th class="non-bordered" style="width: 150px;">
                    <span>
                        订货日期 <br>
                        Order Date
                    </span>
                </th>
                <th class="non-bordered text-center">:</th>
                <th class="non-bordered">
                    <?= date('d F Y', strtotime($data_penawaran->tgl_penawaran)) ?>
                </th>
            </tr>
            <tr class="non-bordered">
                <th class="non-bordered" style="width: 150px;">
                    <span>
                        收货人 / 电话 <br>
                        Consignee / Phone
                    </span>
                </th>
                <th class="non-bordered text-center">:</th>
                <th class="non-bordered">
                    <?= $data_penawaran->nm_pic_cust.' / '.$pic_phone ?>
                </th>
                <th class="non-bordered" style="width: 150px;">
                    <span>
                        发货日期 <br>
                        Delivery Date
                    </span>
                </th>
                <th class="non-bordered text-center">:</th>
                <th class="non-bordered">
                    <?= date('d F Y', strtotime($data_penawaran->deliver_date)) ?>
                </th>
            </tr>
            <tr class="non-bordered">
                <th colspan="3"></th>
                <th class="non-bordered" style="width: 150px;">
                    <span>
                        联系人 / 电话 <br>
                        Contact Person / Phone
                    </span>
                </th>
                <th class="non-bordered text-center">:</th>
                <th class="non-bordered">
                    <?= $data_penawaran->nm_pic_cust.' / '.$pic_phone ?>
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
                        <span> 金额<br>Total Price</span>
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
                                <td class="text-right">Rp. ' . number_format($penawaran_detail->harga_satuan) . '</td>
                                <td class="text-right">Rp. ' . number_format($penawaran_detail->total_harga) . '</td>
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
                    <td class="text-right">Rp. <?= number_format($data_penawaran->biaya_pengiriman) ?></td>
                </tr>
                <tr class="non-bordered">
                    <th colspan="10" class="text-right">
                        <span>打折扣 <br> Discount <?= ($data_penawaran->disc_persen > 0) ? $data_penawaran->disc_persen . '%' : null ?></span>
                    </th>
                    <td class="text-right">Rp. <?= number_format($data_penawaran->nilai_disc) ?></td>
                </tr>
                <tr class="non-bordered">
                    <th colspan="10" class="text-right">
                        <span>
                            合计 <br>
                            Subtotal
                        </span>
                    </th>
                    <td class="text-right">Rp. <?= number_format($ttl_harga + $data_penawaran->biaya_pengiriman - $data_penawaran->nilai_disc) ?></td>
                </tr>
                <tr class="non-bordered">
                    <th colspan="10" class="text-right">
                        增值税 <br> 
                        PPN11%
                    </th>
                    <td class="text-right">Rp. <?= number_format($data_penawaran->ppn_num) ?></td>
                </tr>
                <tr class="non-bordered">
                    <th colspan="10" class="text-right">
                        <span>
                            总金额 <br>
                            Total Amount
                        </span>
                    </th>
                    <td class="text-right">Rp. <?= number_format($ttl_harga - $data_penawaran->nilai_disc + $data_penawaran->ppn_num + $data_penawaran->biaya_pengiriman) ?></td>
                </tr>
            </tbody>
        </table>
        <table class=" w-100" border="1">
            <tr class="non-bordered">
                <th class="text-center" style="width: 25%;">
                    <span>备注<br>Remark
                    </span>
                </th>
                <th class="">
                    <span>
                        <?php
                        echo '
                                1.以上价格含税(增值税 PPN 11%), 订单数量在1000kg以下运费客户自费, 超过1000kg工厂负责送至雅加达指定的码头仓库。 <br>
                                The above prices include tax (Value Added Tax VAT 11%). For orders weighing less than 1000kg, shipping costs are borne by the customer. For orders exceeding 1000kg, the factory is responsible for delivering them to the designated port warehouse in Jakarta. <br>
                                2.付款方式: 订单签字或盖章生效后支付50%的货款定金方可生产, 定金不退, 出厂前支付余款50%。特殊订单面议 <br>
                                Payment terms: Upon the signing or stamping of the order, 50% of the total payment must be made as a deposit before production can commence. The deposit is non-refundable, and the remaining 50% must be paid before delivery. Special orders are subject to negotiation. <br>
                                3.若产品型号、颜色、数量已经确定，中途客户因其他原因临时更改型号、颜色、数量。因为产品是专业的生产工艺流程和原材料体系的不同，所以不予更改，因此造成的经济损失有客户承担。望予以谅解。<br>
                                If the product model, color, or quantity has been confirmed and the customer requests changes during production for reasons unrelated to the production process or materials, such changes will not be accommodated. Any resulting economic losses will be borne by the customer. Your understanding in this matter is appreciated. <br>
                                4.由于产品批次不同, 允许产品所订颜色在行业标准范围内的微色差。产品交货时间如遇不可抗力因素, 可以延迟交货。<br>
                                Due to variations in product batches, slight color differences within industry standards are permissible for ordered products. Delivery times may be subject to delays in cases of force majeure. <br>
                                5.以上所订产品依据工厂实际发货数量清单结算。订货人签字或盖章生效，此订单与工厂销售（经销）合同具有同等的法律效力。<br>
                                Settlement for the products ordered above will be based on the actual quantity delivered by the factory. Once the buyer signs or stamps the order, it holds the same legal effect as a sales contract between the factory and the buyer. <br>
                            ';
                        ?>
                    </span>
                </th>
            </tr>
        </table>
        <table class=" w-100" border="0">
            <tr class="non-bordered">
                <th class="text-center" style="vertical-align:top;">
                    <span style="font-size: 20px;">
                        客户确认签字（章）：<br>
                        Customer confirmation signature (stamp):
                    </span>
                </th>
                <th class="text-center" style="vertical-align:top;">
                    <span style="font-size: 20px;">
                        供货方签字（章）：<br>
                        Supplier's signature (stamp):
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
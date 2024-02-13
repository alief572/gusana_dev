<div class="container-fluid" id="printableDiv">
    <div class="col-12 text-center">
        <h5 style="font-weight: bold;">产品出库申请单</h5> <br>
        <h5 style="font-weight: bold;">PERMINTAAN BARANG KELUAR GUDANG</h5>
    </div>
    <div class="col-12">
        <table class="w-100">
            <tr>
                <th>
                    <span>
                        销售员 <br>
                        Sales
                    </span>
                </th>
                <th style="vertical-align:top;">:</th>
                <th style="vertical-align:top;"><?= $penawaran->nm_marketing ?></th>
                <th>
                    <span>
                        编号 <br>
                        No Seri
                    </span>
                </th>
                <th style="vertical-align:top;">:</th>
                <th style="vertical-align:top;"><?= $penawaran->id_ppb ?></th>
            </tr>
            <?php
            if ($penawaran->tgl_create_do !== null) {
                echo '
                <tr>
                    <th>
                        <span>
                            客户名称 <br>
                            Cust Name
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;">' . $penawaran->nm_cust . '</th>
                    <th>
                        <span>
                            送货单号 <br>
                            No. Surat Jalan
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;">' . $penawaran->id_do . '</th>
                </tr>
                <tr>
                    <th>
                        <span>
                            合同号 <br>
                            No Kontrak
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;">' . $penawaran->id_penawaran . '</th>
                    <th>
                        <span>
                            交货日期 <br>
                            Tgl Pengiriman
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;">' . $penawaran->deliver_date . '</th>
                </tr>
                ';
            } else {
                echo '
                        <tr>
                            <th>
                                <span>
                                    客户名称 <br>
                                    Cust Name
                                </span>
                            </th>
                            <th style="vertical-align:top;">:</th>
                            <th style="vertical-align:top;">' . $penawaran->nm_cust . '</th>
                            <th>
                                <span>
                                    交货日期 <br>
                                    Tgl Pengiriman
                                </span>
                            </th>
                            <th style="vertical-align:top;">:</th>
                            <th style="vertical-align:top;">' . $penawaran->deliver_date . '</th>
                        </tr>
                        <tr>
                            <th>
                                <span>
                                    合同号 <br>
                                    No Kontrak
                                </span>
                            </th>
                            <th style="vertical-align:top;">:</th>
                            <th style="vertical-align:top;">' . $penawaran->id_penawaran . '</th>
                        </tr>
                    ';
            }
            ?>
        </table>
    </div>
    <div class="col-12 mt-15">
        <table class="w-100 mt-15" border="1">
            <thead>
                <th class="text-center">
                    <span>
                        序号 <br>
                        No
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        产品型号 <br>
                        Kode Produk
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        产品名称 <br>
                        Nama Barang
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        包装规格 <br>
                        Spek Kemasan (Kg/Kaleng)
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        色卡 <br>
                        RAL Code
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        单位 <br>
                        Unit
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        数量 <br>
                        Jumlah
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        生产日期 <br>
                        Tgl Produksi
                    </span>
                </th>
            </thead>
            <tbody>
                <?php $x = 0;
                foreach ($list_penawaran_detail as $penawaran_detail) : $x++; ?>
                    <tr>
                        <td class="text-center"><?= $x ?></td>
                        <td class="text-center"><?= $penawaran_detail->kode_product ?></td>
                        <td class="text-center"><?= $penawaran_detail->nm_product ?></td>
                        <td class="text-center"><?= $penawaran_detail->konversi ?> Kg</td>
                        <td class="text-center"><?= $penawaran_detail->ral_code ?></td>
                        <td class="text-center"><?= $penawaran_detail->nm_packaging ?></td>
                        <td class="text-center"><?= $penawaran_detail->qty ?></td>
                        <td class="text-center"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
<div class="col-12 text-right" style="margin-top: 1.5vh;">
    <button type="button" class="btn btn-sm btn-info" onclick="printDiv('printableDiv')">Print</button>
</div>

<script>
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
    $(document).ready(function() {
        $(".chosen-select").select2();
        $(".autonum").autoNumeric();


    });
</script>
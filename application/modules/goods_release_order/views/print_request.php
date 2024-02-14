<style>
    .dn {
        position: fixed;
    }

    .nd {
        margin-top: 65%;
        position: fixed;
    }

    @media print {
        .dn {
            position: fixed;
        }

        .nd {
            margin-top: 65%;
            position: fixed;
        }
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<div class="container-fluid" id="printableDiv">
    <div class="dn">
        <div class="col-12 text-center">
            <h5 style="font-weight: bold;">产品出库申请单</h5> <br>
            <h5 style="font-weight: bold;">PERMINTAAN BARANG KELUAR GUDANG</h5>
        </div>
        <div class="col-12">
            <table class="w-100">
                <tr>
                    <th>
                        <span>
                            Sales
                            (销售员)
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;"><?= $penawaran->nm_marketing ?></th>
                    <th>
                        <span>
                            No Seri
                            (编号)
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
                        Cust Name
                        (客户名称)
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;">' . $penawaran->nm_cust . '</th>
                    <th>
                        <span>
                        No. Surat Jalan
                        (送货单号)
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;">' . $penawaran->id_do . '</th>
                </tr>
                <tr>
                    <th>
                        <span>
                        No Kontrak
                        (合同号)
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;">' . $penawaran->id_penawaran . '</th>
                    <th>
                        <span>
                        Tgl Pengiriman
                        (交货日期)
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
                                Cust Name
                                (客户名称)
                                </span>
                            </th>
                            <th style="vertical-align:top;">:</th>
                            <th style="vertical-align:top;">' . $penawaran->nm_cust . '</th>
                            <th>
                                <span>
                                Tgl Pengiriman
                                (交货日期)
                                </span>
                            </th>
                            <th style="vertical-align:top;">:</th>
                            <th style="vertical-align:top;">' . $penawaran->deliver_date . '</th>
                        </tr>
                        <tr>
                            <th>
                                <span>
                                No Kontrak
                                (合同号)
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
                    <?php $x = 1;
                    foreach ($list_penawaran_detail as $penawaran_detail) :  ?>
                        <?php
                        if ($penawaran_detail->nm_curing_agent !== '') {
                        ?>

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

                            <?php

                            $x++;

                            $get_curing_agent = $this->db->query("SELECT a.*, b.nama as ral_code, c.nm_packaging FROM ms_product_category3 a LEFT JOIN ms_product_category2 b ON b.id_category2 = a.id_category2 LEFT JOIN master_packaging c ON c.id = a.packaging WHERE a.id_category3 = '" . $penawaran_detail->curing_agent . "'")->row();

                            ?>

                            <tr>
                                <td class="text-center"><?= $x ?></td>
                                <td class="text-center"><?= $get_curing_agent->product_code ?></td>
                                <td class="text-center"><?= $get_curing_agent->nama ?></td>
                                <td class="text-center"><?= $get_curing_agent->konversi ?> Kg</td>
                                <td class="text-center"><?= $get_curing_agent->ral_code ?></td>
                                <td class="text-center"><?= $get_curing_agent->nm_packaging ?></td>
                                <td class="text-center"><?= $penawaran_detail->qty ?></td>
                                <td class="text-center"></td>
                            </tr>

                        <?php
                            $x++;
                        } else {
                        ?>

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
                    <?php
                            $x++;
                        }
                    endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="nd">
        <div class="col-12 text-center">
            <h5 style="font-weight: bold;">产品出库申请单</h5> <br>
            <h5 style="font-weight: bold;">PERMINTAAN BARANG KELUAR GUDANG</h5>
        </div>
        <div class="col-12">
            <table class="w-100">
                <tr>
                    <th>
                        <span>
                            Sales
                            (销售员)
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;"><?= $penawaran->nm_marketing ?></th>
                    <th>
                        <span>
                            No Seri
                            (编号)
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
                        Cust Name
                        (客户名称)
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;">' . $penawaran->nm_cust . '</th>
                    <th>
                        <span>
                        No. Surat Jalan
                        (送货单号)
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;">' . $penawaran->id_do . '</th>
                </tr>
                <tr>
                    <th>
                        <span>
                        No Kontrak
                        (合同号)
                        </span>
                    </th>
                    <th style="vertical-align:top;">:</th>
                    <th style="vertical-align:top;">' . $penawaran->id_penawaran . '</th>
                    <th>
                        <span>
                        Tgl Pengiriman
                        (交货日期)
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
                                Cust Name
                                (客户名称)
                                </span>
                            </th>
                            <th style="vertical-align:top;">:</th>
                            <th style="vertical-align:top;">' . $penawaran->nm_cust . '</th>
                            <th>
                                <span>
                                Tgl Pengiriman
                                (交货日期)
                                </span>
                            </th>
                            <th style="vertical-align:top;">:</th>
                            <th style="vertical-align:top;">' . $penawaran->deliver_date . '</th>
                        </tr>
                        <tr>
                            <th>
                                <span>
                                No Kontrak
                                (合同号)
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
                    <?php $x = 1;
                    foreach ($list_penawaran_detail as $penawaran_detail) :  ?>
                        <?php
                        if ($penawaran_detail->nm_curing_agent !== '') {
                        ?>

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

                            <?php

                            $x++;

                            $get_curing_agent = $this->db->query("SELECT a.*, b.nama as ral_code, c.nm_packaging FROM ms_product_category3 a LEFT JOIN ms_product_category2 b ON b.id_category2 = a.id_category2 LEFT JOIN master_packaging c ON c.id = a.packaging WHERE a.id_category3 = '" . $penawaran_detail->curing_agent . "'")->row();

                            ?>

                            <tr>
                                <td class="text-center"><?= $x ?></td>
                                <td class="text-center"><?= $get_curing_agent->product_code ?></td>
                                <td class="text-center"><?= $get_curing_agent->nama ?></td>
                                <td class="text-center"><?= $get_curing_agent->konversi ?> Kg</td>
                                <td class="text-center"><?= $get_curing_agent->ral_code ?></td>
                                <td class="text-center"><?= $get_curing_agent->nm_packaging ?></td>
                                <td class="text-center"><?= $penawaran_detail->qty ?></td>
                                <td class="text-center"></td>
                            </tr>

                        <?php
                            $x++;
                        } else {
                        ?>

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
                    <?php
                            $x++;
                        }
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    window.print();
</script>
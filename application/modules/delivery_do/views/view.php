<?php
$ENABLE_ADD     = has_permission('So_Invoice.Add');
$ENABLE_MANAGE  = has_permission('So_Invoice.Manage');
$ENABLE_VIEW    = has_permission('So_Invoice.View');
$ENABLE_DELETE  = has_permission('So_Invoice.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/dist/sweetalert.css'); ?>">
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="<?= base_url('assets/adminlte/bootstrap/css/bootstrap.min.css'); ?>">
<!-- Font Awesome -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>">
<!-- Ionicons -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/ionicons/css/ionicons.min.css'); ?>">
<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css'); ?>">
<!-- Theme style -->
<link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/AdminLTE.min.css'); ?>">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/iCheck/all.css'); ?>">
<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
<link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/skins/_all-skins.min.css'); ?>">
<!-- Custom CSS -->
<link rel="stylesheet" href="<?= base_url('assets/css/styles.css'); ?>">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/jquery-ui-1.12.1/jquery-ui.css'); ?>">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/timepicker/bootstrap-timepicker.min.css'); ?>">
<!-- bootstrap datetimepicker -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datetimepicker/bootstrap-datetimepicker.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/custom_ddr.css'); ?>">

<!-- jQuery 2.2.3 -->
<script src="<?= base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?= base_url('assets/plugins/daterangepicker/moment.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>

<script src="<?= base_url('assets/jquery-ui-1.12.1/jquery-ui.js'); ?>"></script>

<!-- bootstrap time picker -->
<script src="<?= base_url('assets/plugins/timepicker/bootstrap-timepicker.min.js'); ?>"></script>
<!-- bootstrap datetimepicker -->
<script src="<?= base_url('assets/plugins/datetimepicker/bootstrap-datetimepicker.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datetimepicker/moment-with-locales.js'); ?>"></script>

<!-- Sweet Alert -->
<script src="<?= base_url('assets/dist/sweetalert.min.js'); ?>"></script>
<!-- Form Jquery -->
<script src="<?= base_url('assets/plugins/jqueryform/jquery.form.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/scripts.js'); ?>" type="text/javascript"></script>
<style>
    .non-bordered {
        border: none !important;
        text-align: left;
    }


    @font-face {
        font-family: arial;
    }


    *,
    body {
        font-family: arial;
        letter-spacing: 0.09em;
        margin-bottom: 0px;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    th,
    td {
        font-size: 13px;
    }


    @media print {

        @page {
            size: 8.5in 5.5in;
            margin-top: 0.62in;
            margin-left: 0.22in;
            margin-right: 0.22in;
            margin-bottom: 0.22in;
            font-size: 5px !important;
        }

        footer * {
            display: none;
        }

        .card {
            margin-top: 0;
        }

        .br-header {
            display: none;
        }

        .btn-info {
            display: none;
        }

        html,
        body {
            font-family: arial;
        }

        fieldset {
            border: 1px solid black;
        }

        .pagebreak {
            clear: both;
            page-break-after: always;
        }

    }




    table {
        border-collapse: collapse;
        margin-bottom: 0px;
    }

    .bordered {
        border: 1px solid black;
    }
</style>


<div id="card pagebreak">
    <table class="" border="0" style="width: 100%;">
        <tr class="non-bordered">
            <th style="border-bottom: 2px solid black;border-top:none;" class="text-center">
                <img src="<?= base_url('assets/img/Gusana.png'); ?>" alt="" style="width: 100px;">
            </th>
            <th style="border-bottom: 2px solid black;">
                <h1>PT GUNUNG SAGARA BUANA</h1>
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
    <table class="w-100" style="width: 100%;">
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
            <th class="non-bordered" style="vertical-align:top;min-width: 220px;">
                <span>
                    Nama Pelanggan
                    (客户名称)
                </span>
            </th>
            <th style="width:50px;text-align:center;vertical-align:top;">:</th>
            <th class="non-bordered"><?= $data_penawaran->nm_cust ?></th>
            <th class="non-bordered" style="vertical-align:top;min-width: 220px;">
                <span>
                    Nomor DO (编号)
                </span>
            </th>
            <th style="width:50px;text-align:center;vertical-align:top;">:</th>
            <th class="non-bordered" style="vertical-align:top;min-width: 300px; max-width: 300px;"><?= $data_penawaran->id_do ?></th>
        </tr>
        <tr class="non-bordered">
            <th class="non-bordered" style="vertical-align:top;min-width: 220px;">
                <span>
                    Alamat Pengiriman
                    (收货地址)
                </span>
            </th>
            <th style="width:50px;text-align:center;vertical-align:top;">:</th>
            <th class="non-bordered" style="vertical-align:top;min-width: 300px; max-width: 300px;"><?= $data_penawaran->address_cust ?></th>
            <th class="non-bordered" style="vertical-align:top;min-width: 220px;">
                <span>
                    Nomor PO
                    (订单号)
                </span>
            </th>
            <th style="width:50px;text-align:center;vertical-align:top;">:</th>
            <th class="non-bordered" style="vertical-align:top;min-width: 300px; max-width: 300px;"><?= $data_penawaran->id_quote ?></th>
        </tr>
        <tr class="non-bordered">
            <th class="non-bordered" style="vertical-align:top;min-width: 220px;">
                <span>
                    Penerima Barang
                    (收货人)
                </span>
            </th>
            <th style="width:50px;text-align:center;vertical-align:top;">:</th>
            <th class="non-bordered"><?= $data_penawaran->nm_pic_cust ?>
            </th>
            <th class="non-bordered" style="vertical-align:top;min-width: 220px;">
                <span>
                    Tanggal PO
                    (订单日期)
                </span>
            </th>
            <th style="width:50px;text-align:center;vertical-align:top;">:</th>
            <th class="non-bordered" style="vertical-align:top;min-width: 300px; max-width: 300px;"><?= $data_penawaran->tgl_penawaran ?></th>
        </tr>
        <tr class="non-bordered">
            <th class="non-bordered" style="vertical-align:top;min-width: 220px;">
                <span>
                    Nomor Telp
                    (联系电话)
                </span>
            </th>
            <th style="width:50px;text-align:center;vertical-align:top;">:</th>
            <th class="non-bordered" style="vertical-align:top;min-width: 300px; max-width: 300px;"><?= '(' . $pic_phone . ')' ?></th>
            <th class="non-bordered" style="vertical-align:top;min-width: 220px;">
                <span>
                    Tanggal Pengiriman
                    (发货日期)
                </span>
            </th>
            <th style="width:50px;text-align:center;vertical-align:top;">:</th>
            <th class="non-bordered" style="vertical-align:top;min-width: 300px; max-width: 300px;"><?= $data_penawaran->deliver_date ?></th>
        </tr>
    </table>
    <table class="w-100" style="width: 100%;" border="1">
        <thead>
            <tr class="non-bordered">
                <th style="border: 1px solid black;" class="text-center">
                    <span>序号<br>No.</span>
                </th>
                <th style="border: 1px solid black;" class="text-center">
                    <span>型号<br>Product Code</span>
                </th>
                <th style="border: 1px solid black;" class="text-center">
                    <span>产品名称及型号<br>Nama dan Jenis Barang</span>
                </th>
                <th style="border: 1px solid black;" class="text-center">
                    <span>包装规格(kg/桶)<br>Spesifikasi Kemasan</span>
                </th>
                <th style="border: 1px solid black;" class="text-center">
                    <span>单位<br>Unit</span>
                </th>
                <th style="border: 1px solid black;" class="text-center">
                    <span>数量<br>Quantity</span>
                </th>
                <th style="border: 1px solid black;" class="text-center">
                    <span>色号<br>Kode Warna</span>
                </th>
                <th style="border: 1px solid black;" class="text-center">
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

                $this->db->select('a.keterangan');
                $this->db->from('ms_detail_do a');
                $this->db->where('a.id_do', $data_penawaran->id_do);
                $this->db->where('a.id_product', $penawaran_detail->id_product);
                $this->db->order_by('a.id DESC');
                $this->db->limit(1);
                $get_keterangan = $this->db->get()->row();


                echo '
                            <tr class="non-bordered">
                                <td class="text-center" style="font-weight: bold;border: 1px solid black;font-size: 20px;">' . $x . '</td>
                                <td class="text-center" style="font-weight: bold;border: 1px solid black;font-size: 20px;">' . $penawaran_detail->kode_product . '</td>
                                <td class="text-center" style="font-weight: bold;border: 1px solid black;font-size: 20px;"><span>' . $penawaran_detail->nama_mandarin . '<br>' . $penawaran_detail->nm_product . '</span></td>
                                <td class="text-center" style="font-weight: bold;border: 1px solid black;font-size: 20px;">
                                
                                ';

                if ($penawaran_detail->nm_curing_agent !== '') {
                    $get_product = $this->db->get_where('ms_product_category3', ['id_category3' => $penawaran_detail->id_product])->row();
                    $get_curing_agent = $this->db->get_where('ms_product_category3', ['id_category3' => $get_product->curing_agent])->row();

                    echo 'A : B ' . round($penawaran_detail->konversi - $get_curing_agent->konversi) . ' Kg : ' . $get_curing_agent->konversi . ' Kg <br> ' . '(' . ($penawaran_detail->konversi + $get_curing_agent->konversi - $get_curing_agent->konversi) . ' Kg)';
                } else {
                    echo '' . round($penawaran_detail->konversi) . '/桶 <br> ' . $penawaran_detail->nm_packaging . '';
                }

                echo '          
                                </td>
                                <td class="text-center" style="font-weight: bold;border: 1px solid black;font-size: 20px;">' . $penawaran_detail->nm_packaging . '</td>
                                <td class="text-center" style="font-weight: bold;border: 1px solid black;font-size: 20px;">' . number_format($get_qty_delivery->qty_delivery) . '</td>
                                <td class="text-center" style="font-weight: bold;border: 1px solid black;font-size: 20px;">' . $penawaran_detail->mandarin_ral_code . '<br>' . $penawaran_detail->kode_warna . '</td>
                                <td class="" style="border: 1px solid black;font-size: 20px;font-weight:bold;"></td>
                            </tr>
                        ';
                $ttl_berat += ($penawaran_detail->qty * $penawaran_detail->konversi);
            endforeach;
            ?>
            <tr>
                <th colspan="4" class="text-right" style="border: 1px solid black;"><span>总重量 TOTAL BERAT</span></th>
                <th colspan="4" class="text-center" style="border: 1px solid black;"><?= number_format($ttl_berat) ?> Kg</th>
            </tr>
            <tr>
                <th colspan="4" class="text-right" style="border: 1px solid black;"><span>总体积 TOTAL VOLUME </span></th>
                <th colspan="4" class="text-center" style="border: 1px solid black;">m3</th>
            </tr>
        </tbody>
    </table>
    <table style="width:100%;">
        <tbody>

            <tr>
                <th colspan="2" style="border: 1px solid black;">
                    <span>
                        销售内勤（签字）: <br>
                        Dibuat (TTD):
                    </span>
                </th>
                <th style="border:1px solid black;">
                    <span>
                        仓库（签字）：<br>
                        Gudang (TTD):
                    </span>
                </th>
                <th style="border:1px solid black;">
                    <span>
                        审核 （签字）: <br>
                        Diperiksa (TTD):
                    </span>
                </th>
                <th style="border:1px solid black;" colspan="3">
                    <span>
                        收货人（签字）: <br>
                        Penerima (TTD):
                    </span>
                </th>
                <th style="border:1px solid black;">
                    <span>
                        司机(签字): <br>
                        Sopir (TTD)
                    </span>
                </th>
            </tr>
            <tr>
                <th style="height:80px;border: 1px solid black;" colspan="2"></th>
                <th style="height:80px;border: 1px solid black;"></th>
                <th style="height:80px;border: 1px solid black;"></th>
                <th style="height:80px;border: 1px solid black;" colspan="3"></th>
                <th style="height:80px;border: 1px solid black;"></th>
            </tr>
        </tbody>
    </table>
</div>
<div class="text-right">
    <button type="button" class="btn btn-sm btn-info" onclick="printDiv('card')">Print</button>
</div>




<script>
    // function printDiv(divId) {
    //     var printContents = document.getElementById(divId).innerHTML;
    //     var originalContents = document.body.innerHTML;

    //     document.body.innerHTML = printContents;

    //     window.print();

    //     document.body.innerHTML = originalContents;
    // }
    window.print();

    function printDiv() {
        window.print();
    }
</script>
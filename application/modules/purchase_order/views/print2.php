<html>

<head>
    <style type="text/css">
        .header_style_company {
            padding: 15px;
            color: black;
            font-size: 20px;
            vertical-align: bottom;
        }

        .header_style_company2 {
            padding: 15px;
            color: black;
            font-size: 15px;
            vertical-align: top;
        }

        .header_style_alamat {
            padding: 10px;
            color: black;
            font-size: 10px;
        }

        table.default {
            font-family: arial, sans-serif;
            font-size: 9px;
            padding: 0px;
        }

        p {
            font-family: arial, sans-serif;
            font-size: 14px;
        }

        .font {
            font-family: arial, sans-serif;
            font-size: 14px;
        }

        table.gridtable {
            font-family: arial, sans-serif;
            font-size: 11px;
            color: #333333;
            border: 1px solid #808080;
            border-collapse: collapse;
        }

        table.gridtable th {
            padding: 6px;
            background-color: #f7f7f7;
            color: black;
            border-color: #808080;
            border-style: solid;
            border-width: 1px;
        }

        table.gridtable th.head {
            padding: 6px;
            background-color: #f7f7f7;
            color: black;
            border-color: #808080;
            border-style: solid;
            border-width: 1px;
        }

        table.gridtable td {
            border-width: 1px;
            padding: 6px;
            border-style: solid;
            border-color: #808080;
        }

        table.gridtable td.cols {
            border-width: 1px;
            padding: 6px;
            border-style: solid;
            border-color: #808080;
        }


        table.gridtable2 {
            font-family: arial, sans-serif;
            font-size: 12px;
            color: #333333;
            border-width: 1px;
            border-color: #666666;
            border-collapse: collapse;
        }

        table.gridtable2 td {
            border-width: 1px;
            padding: 1px;
            border-style: none;
            border-color: #666666;
            background-color: #ffffff;
        }

        table.gridtable2 td.cols {
            border-width: 1px;
            padding: 1px;
            border-style: none;
            border-color: #666666;
            background-color: #ffffff;
        }

        table.gridtableX {
            font-family: arial, sans-serif;
            font-size: 12px;
            color: #333333;
            border: none;
            border-collapse: collapse;
        }

        table.gridtableX td {
            border-width: 1px;
            padding: 6px;
        }

        table.gridtableX td.cols {
            border-width: 1px;
            padding: 6px;
        }

        table.gridtableX2 {
            font-family: arial, sans-serif;
            font-size: 12px;
            color: #333333;
            border: none;
            border-collapse: collapse;
        }

        table.gridtableX2 td {
            border-width: 1px;
            padding: 2px;
        }

        table.gridtableX2 td.cols {
            border-width: 1px;
            padding: 2px;
        }

        #testtable {
            width: 100%;
        }
    </style>
</head>

<body>
    <?php
    foreach ($header as $header) {
    }
    $detailsum = $this->db->query("SELECT SUM(width) as sumwidth, SUM(qty) as sumqty, SUM(totalwidth) as sumtotalwidth, SUM(jumlahharga) as sumjumlahharga, SUM(hargasatuan) as sumhargasatuan FROM dt_trans_po WHERE no_po = '" . $header->no_po . "' ")->result();
    $jumlahdetail = $this->db->query("SELECT COUNT(no_po) as no_po FROM dt_trans_po WHERE no_po = '" . $header->no_po . "' ")->result();
    $jumlahdata = $jumlahdetail[0]->no_po;
    $tinggi = 300 / $jumlahdata;
    if (empty($header->negara)) {
        $cou = 'Indonesia';
    } else {
        // $findnegara = $this->db->query("SELECT * FROM negara WHERE id_negara = '" . $header->negara . "' ")->result();
        // $cou = $findnegara[0]->nm_negara;
    }
    // $findpic = $this->db->query("SELECT * FROM child_supplier_pic WHERE id_suplier = '" . $header->id_suplier . "' ")->result();
    // $namapic = $findpic[0]->name_pic;

    // print_r($_SERVER['DOCUMENT_ROOT'] . '/origa_live/assets/images/ori_logo.jpg');
    // exit;
    ?>
    <table class="gridtable2" border="0">
        <tr>
            <td style="text-align:left;">
                <img src='<?= $_SERVER['DOCUMENT_ROOT'] . '/origa_live/assets/images/ori_logo2.png'; ?>' alt="" width="75" height="95">
            </td>
            <td align="right" width="630">
                <br>
                Jl. Pembangunan II <br>
                Kel. Batusari, <br>
                Kec. Batuceper, <br>
                Kota Tangerang Postal <br>
                Code 15122 <br>
                Indonesia

            </td>
        </tr>
    </table>
    <hr>
    <div style='display:block; border-color:none; background-color:#c2c2c2;' align='center'>
        <h3>PURCHASE ORDER</h3>
    </div>
    <br>
    <table class='gridtable2' width='100%' border='1' align='left' cellpadding='0' cellspacing='0'>
        <tr>
            <td width="300" align="center">
                <table width='300' align="center">
                    <tr>
                        <td width='50' align="left">Supplier</td>
                        <td width='10' align="left">:</td>
                        <td width='250' align="left"><?= $header->nm_supp ?></td>
                    </tr>
                    <tr>
                        <td width='50' align="left">Address</td>
                        <td width='10' align="left">:</td>
                        <td width='250' align="left"><?= $header->alamat ?></td>
                    </tr>
                    <tr>
                        <td width='50' align="left">Country</td>
                        <td width='10' align="left">:</td>
                        <td width='250' align="left"><?= $header->country_name ?></td>
                    </tr>
                    <tr>
                        <td width='50' align="left">PIC</td>
                        <td width='10' align="left">:</td>
                        <td width='250' align="left"><?= $this->auth->user_name() ?></td>
                    </tr>
                    <tr>
                        <td width='50' align="left">Phone</td>
                        <td width='10' align="left">:</td>
                        <td width='250' align="left"><?= $header->hp ?></td>
                    </tr>
                    <tr>
                        <td width='50' align="left">Fax</td>
                        <td width='10' align="left">:</td>
                        <td width='250' align="left"><?= $header->fax ?></td>
                    </tr>
                </table>
            </td>
            <td width="300" align="center">
                <table width='300' align="center">
                    <tr>
                        <td width='50' align="left">No</td>
                        <td width='10' align="left">:</td>
                        <td width='250' align="left"><?= $header->no_surat ?></td>
                    </tr>
                    <tr>
                        <td width='50' align="left">Date</td>
                        <td width='10' align="left">:</td>
                        <td width='250' align="left"><?= date('d-F-Y', strtotime($header->tanggal))  ?></td>
                    </tr>
                    <tr>
                        <td width='50' align="left">Revision</td>
                        <td width='10' align="left">:</td>
                        <td width='250' align="left"><?= $header->revisi ?></td>
                    </tr>
                    <tr>
                        <td width='50' align="left">No PO</td>
                        <td width='10' align="left">:</td>
                        <td width='250' align="left"><?= $header->no_surat ?></td>
                    </tr>
                    <tr>
                        <td width='50' align="left">&nbsp;</td>
                        <td width='10' align="left"></td>
                        <td width='250' align="left"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <?php
    $matauang = (!empty($header->matauang)) ? "<br>(" . strtoupper($header->matauang) . ")" : '';

    if (strtolower($header->matauang) == 'usd') {
        $kode = '$';
    }
    if (strtolower($header->matauang) == 'idr') {
        $kode = 'Rp';
    }
    ?>
    <table class='gridtable' cellpadding='0' cellspacing='0' style='vertical-align:top;'>
        <tbody>
            <tr style='vertical-align:middle; background-color:#c2c2c2; font-weight:bold;'>
                <td align="center" width="50">Code</td>
                <td align="center" width="150">Description</td>
                <td align="center" width="55">Unit Measurement</td>
                <td align="center" width="35">Qty Pack</td>
                <td align="center" width="35">Unit Packing</td>
                <td align="center" width="35">Price </td>
                <td align="center" width="35">Qty</td>
                <td align="center" width="35">Total </td>

            </tr>
            <?php
            $CIF = "<br>" . $header->cif . "<br><br><br><br>";
            $TOT_PPH = 0;
            foreach ($detail as $detail) {
                $kategory = $detail->idmaterial;
                $barang  = $this->db->query("SELECT * FROM ms_inventory_category3 WHERE id_category3 ='$kategory' ")->row();

                $TOT_PPH += $detail->jumlahharga * $detail->pajak / 100;
                $HS = number_format($detail->hargasatuan, 2);
                $JH = number_format($detail->jumlahharga, 2);
                if (strtolower($header->loi) == 'lokal') {
                    $HS = number_format($detail->hargasatuan, 2);
                    $JH = number_format($detail->jumlahharga, 2);
                }



                if ($jumlahdata <= '30') {
                    echo "	
                    <tr >
                        <td width='50'>" . $detail->code . "</td>
                        <td width='150'>" . $detail->nama . $detail->nama1 . "</td>
                        <td width='55' align='center'>" . ucfirst($detail->satuan) . "</td>
                        <td width='35' align='right'>" . number_format($detail->qty / $detail->konversi, 2) . "</td>
                        <td width='35'>" . ucfirst($detail->satuan_packing) . "</td>
                        <td width='35' align='right'>" . $HS . "</td>
                        <td width='35' align='right'>" . $detail->qty . "</td>
                        <td width='35' align='right'>" . $JH . "</td>
                    </tr>";
                    $CIF = "";
                } else {
                    echo "	
                    <tr >
                        <td width='50'>" . $detail->code . "</td>
                        <td width='150'>" . $detail->nama . $detail->nama1 . "</td>
                        <td width='55' align='center'>" . ucfirst($detail->satuan) . "</td>
                        <td width='35' align='right'>" . number_format($detail->qty / $detail->konversi, 2) . "</td>
                        <td width='35'>" . ucfirst($detail->satuan_packing) . "</td>
                        <td width='35' align='right'>" . $HS . "</td>
                        <td width='35' align='right'>" . $detail->qty . "</td>
                        <td width='35' align='right'>" . $JH . "</td>
                    </tr>";
                    $CIF = "";
                }
            } ?>

            <?php
            if ($header->loi == 'Lokal') {
            ?>

            <?php
            }
            if ($header->loi == 'Import') {
                $TOT_PPH = 0;
            }

            $TOTHEAD = number_format($detailsum[0]->sumjumlahharga + $TOT_PPH, 2);
            if (strtolower($header->loi) == 'lokal') {
                $TOTHEAD = number_format($detailsum[0]->sumjumlahharga + $TOT_PPH, 2);
            }
            ?>
            <tr>
                <td align="right" colspan='7'>Total </td>
                <td align="right"><?= number_format($header->total_barang, 2) ?></td>

            </tr>
            <tr>
                <td align="right" colspan='7'>Biaya Kirim </td>
                <td align="right"><?= number_format($header->taxtotal, 2) ?></td>

            </tr>

            <tr>
                <td align="right" colspan='7'>PPN </td>
                <td align="right"><?= number_format($header->total_ppn, 2) ?></td>

            </tr>

            <tr>
                <td align="right" colspan='7'>Grand Total </td>
                <td align="right"><?= number_format($header->subtotal, 2) ?></td>

            </tr>

        </tbody>
    </table>
    <br>

    <table border="0" width='100%' align="left">

        <tr>

            <td width="250" align="left"><br><br>
                <table>
                    <tr>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'>(Cost Control)</td>
                    </tr>
                </table>
            </td>
            <td width="250" align="left"><br><br>
                <table>
                    <tr>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='center'>(Dept Head)</td>
                    </tr>
                </table>
            </td>
            <td width="250" align="left"><br><br>
                <table>
                    <tr>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='right'></td>
                    </tr>
                    <tr>
                        <td align='center'>(<?= $this->auth->user_name() ?>)</td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>



</body>

</html>
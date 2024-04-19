<?php

$sroot 		= $_SERVER['DOCUMENT_ROOT'].'/gusana_dev/';
include $sroot."application/libraries/MPDF57/mpdf.php";
$mpdf=new mPDF('utf-8','A4');
$mpdf->defaultheaderline=0;

set_time_limit(0);
ini_set('memory_limit','1024M');
// $HTML_HEADER2 = "<h1>Sample</h1>";
$QTY_PRODUKSI = (!empty($getData[0]['qty_produksi']))?$getData[0]['qty_produksi']:0;


    $HTML_HEADER = "";
    $HTML_HEADER .= "<table class='gridtable2' border='0' width='100%' cellpadding='0' style='margin-left:20px;'>";
        $HTML_HEADER .= "<tr>";
            $HTML_HEADER .= "<td rowspan='2' style='text-align:left; vertical-align:top;'><img src='".$sroot."/assets/img/Gusana.png' style='float:left;'  width='90'></td>";
            $HTML_HEADER .= "<td width='35%'></td>";
        $HTML_HEADER .= "</tr>";
        $HTML_HEADER .= "<tr>";
            $HTML_HEADER .= "<td class='header_style_alamat' style='vertical-align:bottom; font-size:12px;'>Jl. Gusana, RT.015/RW.005<br>Kel. Jatisari, Kec. Jatiasih<br>Kota Bekasi 17426<br>Prov. Jawa Barat <br> Indonesia </td>";
        $HTML_HEADER .= "</tr>";
    $HTML_HEADER .= "</table><hr>";
    

    $HTML_HEADER .= "<table class='gridtable2' border='0' width='100%' cellpadding='2' style='margin-left:20px;margin-right:20px;'>";
        $HTML_HEADER .= "<tr>";
            $HTML_HEADER .= "<td class='header_style_alamat' width='10%'></td>";
            $HTML_HEADER .= "<td class='header_style_alamat' width='1%'></td>";
            $HTML_HEADER .= "<td class='header_style_alamat' ></td>";
            $HTML_HEADER .= "<td class='header_style_alamat' width='10%'></td>";
            $HTML_HEADER .= "<td class='header_style_alamat' width='1%'></td>";
            $HTML_HEADER .= "<td class='header_style_alamat' width='25%'></td>";
        $HTML_HEADER .= "</tr>";
        $HTML_HEADER .= "<tr>";
            $HTML_HEADER .= "<td class='header_style_company2' colspan='6' align='center' style='height:50px;'>Purchase Request</td>";
        $HTML_HEADER .= "</tr>";
        $HTML_HEADER .= "<tr>";
            $HTML_HEADER .= "<td class='header_style_alamat'>Customer</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>:</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>PT. GUNUNG SAGARA BUANA</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'></td>";
            $HTML_HEADER .= "<td class='header_style_alamat'></td>";
            $HTML_HEADER .= "<td class='header_style_alamat'></td>";
        $HTML_HEADER .= "</tr>";
        $HTML_HEADER .= "<tr>";
            $HTML_HEADER .= "<td class='header_style_alamat'>Address</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>:</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>Jl. Gusana, RT.015/RW.005, Jatisari, Kec. Jatiasih, Kota Bks, Jawa Barat 17426</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>No PR</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>:</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>".$getData[0]['no_pr']."</td>";
        $HTML_HEADER .= "</tr>";
        $country = get_name('country_all','nicename','iso3',$getCustomer[0]['country_code']);
        $HTML_HEADER .= "<tr>";
            $HTML_HEADER .= "<td class='header_style_alamat'>Country</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>:</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>Indonesia</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>Date</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>:</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>".date('d F Y',strtotime($getData[0]['tgl_so']))."</td>";
        $HTML_HEADER .= "</tr>";
        $HTML_HEADER .= "<tr>";
            $HTML_HEADER .= "<td class='header_style_alamat'>PIC</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>:</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'></td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>Rev.</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>:</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>".$getData[0]['no_rev']."</td>";
        $HTML_HEADER .= "</tr>";
        $HTML_HEADER .= "<tr>";
            $HTML_HEADER .= "<td class='header_style_alamat'>Phone</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>:</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>02148675810</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'></td>";
            $HTML_HEADER .= "<td class='header_style_alamat'></td>";
            $HTML_HEADER .= "<td class='header_style_alamat'></td>";
        $HTML_HEADER .= "</tr>";
        $HTML_HEADER .= "<tr>";
            $HTML_HEADER .= "<td class='header_style_alamat'>Fax</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>:</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'>02148675810</td>";
            $HTML_HEADER .= "<td class='header_style_alamat'></td>";
            $HTML_HEADER .= "<td class='header_style_alamat'></td>";
            $HTML_HEADER .= "<td class='header_style_alamat'></td>";
        $HTML_HEADER .= "</tr>";
    $HTML_HEADER .= "</table>";

    ?>
    <table class='gridtable' width='100%' border='1' cellpadding='0' cellspacing='0' style='margin-left:20px;margin-right:20px;'>
        <tr>
            <th width='5%' align='center'>#</th>
            <th align='center'>Code</th>
            <th align='center'>Material Name</th>
            <th width='15%' align='center'>Qty</th>
            <th width='15%' align='center'>Note</th>
        </tr>
        <?php
            foreach ($getDataDetail as $key => $value) { $key++;
                $tandaMat = substr($value['id_material'],0,1);
                if($tandaMat == 'M'){
                    $nm_product_code    = (!empty($GET_DET_Lv4[$value['id_material']]['code']))?$GET_DET_Lv4[$value['id_material']]['code']:'';
                    $nm_product         = (!empty($GET_DET_Lv4[$value['id_material']]['nama']))?$GET_DET_Lv4[$value['id_material']]['nama']:'';
                }
                else{
                    $nm_product_code    = (!empty($GET_ACCESSORIES[$value['id_material']]['code']))?$GET_ACCESSORIES[$value['id_material']]['code']:'';
                    $nm_product         = (!empty($GET_ACCESSORIES[$value['id_material']]['nama']))?$GET_ACCESSORIES[$value['id_material']]['nama']:'';
                }
                echo "<tr>";
                    echo "<td align='center'>".$key." </td>";
                    echo "<td>".$nm_product_code."</td>";
                    echo "<td>".$nm_product."</td>";
                    echo "<td align='right'>".number_format($value['propose_rev'],4)."</td>";
                    echo "<td align='left'>".$value['note']."</td>";
                echo "</tr>";
            }
        ?>
    </table><br><br><br>
    <?php
    echo "<table class='gridtable4' width='100%' border='0' cellpadding='2'>";
    echo "<tbody>";
        echo "<tr>";
            echo "<td width='33%' align='center'>Dibuat Oleh,</td>";
            echo "<td width='34%' align='center'>Diperiksa Oleh</td>";
            echo "<td width='33%' align='center'>Diketahui Oleh,</td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td height='70px;'>&nbsp;</td>";
            echo "<td></td>";
            echo "<td></td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td align='center'>____________________</td>";
            echo "<td align='center'>____________________</td>";
            echo "<td align='center'>____________________</td>";
        echo "</tr>";
    echo "</tbody>";
    echo "</table>";

?>

<style type="text/css">
    .text-bold{
        font-weight: bold;
    }
    .bold{
        font-weight: bold;
    }
    .header_style_company{
        padding: 15px;
        color: black;
        font-size: 20px;
    }
    .header_style_company2{
        padding-bottom: 20px;
        color: black;
        font-size: 20px;
        /* vertical-align: bottom; */
    }
    .header_style_alamat{
        padding: 10px;
        color: black;
        font-size: 11px;
        vertical-align: top !important;
    }
    p{
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        padding: 0px;
    }
    
    table.gridtable {
        font-family: verdana,arial,sans-serif;
        font-size:10 px;
        border-collapse: collapse;
    }
    table.gridtable th {
        padding: 3px;
    }
    table.gridtable th.head {
        padding: 3px;
    }
    table.gridtable td {
        padding: 3px;
    }
    table.gridtable td.cols {
        padding: 3px;
    }

    table.gridtable2 {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:#000000;
        border-collapse: collapse;
    }
    table.gridtable2 th {
        padding: 1px;
    }
    table.gridtable2 th.head {
        padding: 1px;
    }
    table.gridtable2 td {
        border-width: 1px;
        padding: 1px;
    }
    table.gridtable2 td.cols {
        padding: 1px;
    }
    
    table.gridtable4 {
        font-family: verdana,arial,sans-serif;
        font-size:12px;
        color:#000000;
    }
    table.gridtable4 td {
        padding: 1px;
        border-color: #dddddd;
    }
    table.gridtable4 td.cols {
        padding: 1px;
    }

    table.gridtable5 {
        font-family: verdana,arial,sans-serif;
        font-size:8px;
        color:#000000;
    }
    table.gridtable5 td {
        padding: 1px;
        border-color: #dddddd;
    }
    table.gridtable5 td.cols {
        padding: 1px;
    }
</style>

<?php
$html = ob_get_contents();
// $footer = "<p style='font-family: verdana,arial,sans-serif; font-size:10px;'><i>Printed by : ".ucfirst(strtolower(get_name('users', 'username', 'id_user', $printby))).", ".date('d-M-Y H:i:s')."</i></p>";
// exit;
ob_end_clean();

// $mpdf->SetWatermarkImage(
//     $sroot.'/assets/images/ori_logo2.png',
//     1,
//     [21,30],
//     [5, 0]);
// $mpdf->showWatermarkImage = true;

$mpdf->SetHeader($HTML_HEADER);
$mpdf->SetTitle($kode);
$mpdf->defaultheaderline = 0;
		
$mpdf->AddPageByArray([
    'orientation' => 'P',
    'margin-top' => 85,
    'margin-bottom' => 15,
    'margin-left' => 0,
    'margin-right' => 0,
    'margin-header' => 0,
    'margin-footer' => 0,
    'line' => 0
]);
// $mpdf->SetFooter($footer);
$mpdf->WriteHTML($html);
$mpdf->Output("spk-material.pdf" ,'I');

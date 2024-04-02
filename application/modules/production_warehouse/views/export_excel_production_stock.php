<?php 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Production Material Stock Report - ('.date('d F Y', strtotime($results['tgl_from'])).' - '.date('d F Y', strtotime($results['tgl_to'])).').xls"');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Excel Stock Production</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div style="text-align: center;">
        <h2>PT. GUNUNG SAGARA BUANA - Production Material Stock Report</h2>
        <h4>Per <?= date('d F Y', strtotime($results['tgl_from'])).' - '.date('d F Y', strtotime($results['tgl_to'])) ?></h4>
    </div>
    <div class="mt-5">
        <table id="dataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Code</th>
                    <th class="text-center" style="width: 200px;">Material</th>
                    <th class="text-center">Packing</th>
                    <th class="text-center">Conversion</th>
                    <th class="text-center">Unit</th>
                    <th class="text-center">Beginning</th>
                    <th class="text-center">In</th>
                    <th class="text-center">Out</th>
                    <th class="text-center">Stock Unit</th>
                    <th class="text-center">Stock Unit (Kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($results['list_material_stock'] as $material_stock) :
                    
                    $get_history = $this->db->query("

                        select
                            'Outgoing' as xx,
                            a.tgl_request AS history_date,
                            b.full_name AS nm_by,
                            e.warehouse_nm AS dari_gudang,
                            c.warehouse_nm AS ke_gudang,
                            '0' AS qty_up,
                            f.konversi,
                            SUM(d.request_qty) AS qty_down,
                            a.id AS no_transaksi,
                            a.keterangan AS keterangan,
                            d.id_category1 AS id_category1,
                            a.status AS status
                        FROM
                            ms_request_material a
                            LEFT JOIN users b ON b.id_user = a.dibuat_oleh
                            LEFT JOIN m_warehouse c ON c.id = a.id_gudang_to
                            LEFT JOIN ms_request_material_detail d ON d.id_request = a.id
                            LEFT JOIN m_warehouse e ON e.id = a.id_gudang_from
                            LEFT JOIN ms_inventory_category3 f ON f.id_category1 = d.id_category1
                        WHERE
                            d.id_category1 = '" . $material_stock->id_category1 . "' AND a.status = '1' AND a.id IS NOT NULL AND a.tgl_request < '".$results['tgl_from']."'

                        UNION ALL

                        SELECT
                            'LHP' as xx,
                            DATE_FORMAT(d.dibuat_tgl, '%Y-%m-%d') AS history_date,
                            e.full_name AS nm_by,
                            'Production Warehouse' AS dari_gudang,
                            'LHP' AS ke_gudang,
                            SUM(g.qty_aktual) AS qty_up,
                            h.konversi AS konversi,
                            '0' AS qty_down,
                            b.id_spk AS no_transaksi,
                            '' AS keterangan,
                            c.id_category1 AS id_category1,
                            d.sts AS status
                        FROM
                            ms_so a 
                            LEFT JOIN ms_create_spk b ON b.id_so = a.id_so
                            LEFT JOIN ms_bom_detail_material c ON c.id_bom = a.id_bom
                            LEFT JOIN ms_closing_lhp d ON d.id_spk = b.id_spk
                            LEFT JOIN users e ON e.id_user = d.dibuat_oleh
                            LEFT JOIN ms_inventory_category1 f ON f.id_category1 = c.id_category1
                            LEFT JOIN ms_lhp_aktual_qty g ON g.id_spk = b.id_spk AND g.id_material = c.id_category1
                            LEFT JOIN ms_inventory_category3 h ON h.id_category1 = c.id_category1
                        WHERE
                            c.id_category1 = '" . $material_stock->id_category1 . "' AND d.sts = 'closing' AND d.id_spk IS NOT NULL AND DATE_FORMAT(d.dibuat_tgl, '%Y-%m-%d') >= '2024-02-26' AND DATE_FORMAT(a.due_date, '%Y-%m-%d') >= '2024-02-01' AND  DATE_FORMAT(d.dibuat_tgl, '%Y-%m-%d') <= '".$results['tgl_from']."'
                        
                        group by no_transaksi
                        
                    ")->result();

                    $stock_awal = 0;
                    foreach($get_history as $history){
                        $qty = $history->qty_down;
                        if ($history->qty_up > 0) {
                            $qty = ($history->qty_up / $history->konversi);
                        }

                        $pen_stock_awal = $stock_awal;
                        $pen_stock_akhir = $stock_akhir;
                        if ($history->xx !== 'LHP') {
                            $pen_stock_awal = ($stock_awal * $history->konversi);
                            $pen_stock_akhir = ($stock_akhir * $history->konversi);
                        }

                        if ($history->xx !== 'LHP') {
                            $stock_awal += ($history->qty_down * $history->konversi);
                            $stock_awal -= ($history->qty_up * $history->konversi);
                        } else {
                            $stock_awal += $history->qty_down;
                            $stock_awal -= $history->qty_up;
                        }
                    }

                    $in_stock = 0;
                    $get_in_stock = $this->db->query("
                        select
                            'Outgoing' as xx,
                            a.tgl_request AS history_date,
                            b.full_name AS nm_by,
                            e.warehouse_nm AS dari_gudang,
                            c.warehouse_nm AS ke_gudang,
                            '0' AS qty_up,
                            f.konversi,
                            SUM(d.request_qty) AS qty_down,
                            a.id AS no_transaksi,
                            a.keterangan AS keterangan,
                            d.id_category1 AS id_category1,
                            a.status AS status
                        FROM
                            ms_request_material a
                            LEFT JOIN users b ON b.id_user = a.dibuat_oleh
                            LEFT JOIN m_warehouse c ON c.id = a.id_gudang_to
                            LEFT JOIN ms_request_material_detail d ON d.id_request = a.id
                            LEFT JOIN m_warehouse e ON e.id = a.id_gudang_from
                            LEFT JOIN ms_inventory_category3 f ON f.id_category1 = d.id_category1
                        WHERE
                            d.id_category1 = '" . $material_stock->id_category1 . "' AND a.status = '1' AND a.id IS NOT NULL AND a.tgl_request BETWEEN '".$results['tgl_from']."' AND '".$results['tgl_to']."'
                        GROUP BY a.id
                    ")->result();
                    foreach($get_in_stock as $history){
                        if ($history->xx !== 'LHP') {
                            $in_stock += ($history->qty_down * $history->konversi);
                        } else {
                            $in_stock += $history->qty_down;
                        }
                    }

                    $out_stock = 0;
                    $get_out_stock = $this->db->query("
                        SELECT
                            'LHP' as xx,
                            DATE_FORMAT(d.dibuat_tgl, '%Y-%m-%d') AS history_date,
                            e.full_name AS nm_by,
                            'Production Warehouse' AS dari_gudang,
                            'LHP' AS ke_gudang,
                            SUM(g.qty_aktual) AS qty_up,
                            h.konversi AS konversi,
                            '0' AS qty_down,
                            b.id_spk AS no_transaksi,
                            '' AS keterangan,
                            c.id_category1 AS id_category1,
                            d.sts AS status
                        FROM
                            ms_so a 
                            LEFT JOIN ms_create_spk b ON b.id_so = a.id_so
                            LEFT JOIN ms_bom_detail_material c ON c.id_bom = a.id_bom
                            LEFT JOIN ms_closing_lhp d ON d.id_spk = b.id_spk
                            LEFT JOIN users e ON e.id_user = d.dibuat_oleh
                            LEFT JOIN ms_inventory_category1 f ON f.id_category1 = c.id_category1
                            LEFT JOIN ms_lhp_aktual_qty g ON g.id_spk = b.id_spk AND g.id_material = c.id_category1
                            LEFT JOIN ms_inventory_category3 h ON h.id_category1 = c.id_category1
                        WHERE
                            c.id_category1 = '" . $id_category1 . "' AND d.sts = 'closing' AND d.id_spk IS NOT NULL AND DATE_FORMAT(d.dibuat_tgl, '%Y-%m-%d') >= '2024-02-26' AND DATE_FORMAT(a.due_date, '%Y-%m-%d') >= '2024-02-01' AND DATE_FORMAT(d.dibuat_tgl, '%Y-%m-%d') BETWEEN '" . $results['tgl_from'] . "' AND '" . $results['tgl_to'] . "'
                    ");
                    foreach($get_out_stock as $history){
                        if ($history->xx !== 'LHP') {
                            $out_stock -= ($history->qty_up * $history->konversi);
                        } else {
                            $out_stock += $history->qty_up;
                        }
                    }

                    echo '
						<tr>
							<td class="text-center">' . $no . '</td>
							<td class="text-center">' . $material_stock->category_nm . '</td>
							<td class="text-center">' . $material_stock->nama . '</td>
							<td class="text-center">' . $material_stock->nm_packaging . '</td>
							<td class="text-center">' . number_format($material_stock->konversi) . '</td>
							<td class="text-center">' . $material_stock->nm_unit . '</td>
							<td class="text-center">' . number_format($stock_awal, 2) . '</td>
							<td class="text-center">' . number_format($in_stock, 2) . '</td>
							<td class="text-center">' . number_format($out_stock, 2) . '</td>
							<td class="text-center">' . number_format(($stock_awal + $in_stock - $out_stock), 2) . '</td>
							<td class="text-center">' . number_format(($stock_awal + $in_stock - $out_stock) * $material_stock->konversi, 2) . '</td>
						</tr>
					';
                    $no++;
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
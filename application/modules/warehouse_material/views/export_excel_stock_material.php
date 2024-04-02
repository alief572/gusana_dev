<?php 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Material Stock Report - ('.date('d F Y', strtotime($results['tgl_from'])).' - '.date('d F Y', strtotime($results['tgl_to'])).').xls"');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Export Stock Material</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div style="text-align: center;">
        <h2>PT. GUNUNG SAGARA BUANA - Material Stock Report</h2>
        <h4>Per <?= date('d F Y', strtotime($results['tgl_from'])).' - '.date('d F Y', strtotime($results['tgl_to'])) ?></h4>
    </div>
    <div class="mt-5">
        <table id="dataTable" class="table table-bordered table-striped" border="1">
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
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($results['list_material_stock'] as $material_stock) :

                    $get_in_stock = $this->db->query("
                        SELECT 
                            IF(SUM(a.qty) IS NOT NULL, SUM(a.qty), 0) as ttl_in_stock
                        FROM
                            ms_pr_material_detail a
                            JOIN ms_pr_material b ON b.id = a.id_pr
                        WHERE
                            a.id_category1 = '".$material_stock->id_category1."' AND b.sts = '1' AND b.tgl BETWEEN '".$results['tgl_from']."' AND '".$results['tgl_to']."'
                        
                    ")->row();

                    $get_out_stock = $this->db->query("
                        SELECT 
                            IF(SUM(a.request_qty) IS NOT NULL, SUM(a.request_qty), 0) as ttl_out_stock
                        FROM
                            ms_request_material_detail a
                            JOIN ms_request_material b ON b.id = a.id_request
                        WHERE
                            a.id_category1 = '".$material_stock->id_category1."' AND b.status = '1' AND b.tgl_request BETWEEN '".$results['tgl_from']."' AND '".$results['tgl_to']."'
                        
                    ")->row();

                    $stock_awal = 0;
                    $get_history = $this->db->query("
                        select
                            'PR' as xx,
                            a.tgl AS history_date,
                            b.full_name AS nm_by,
                            'Pembelian Material' AS dari_gudang,
                            c.warehouse_nm AS ke_gudang,
                            SUM(d.qty) AS qty_up,
                            '0' AS qty_down,
                            a.id AS no_transaksi,
                            a.keterangan AS keterangan,
                            d.id_category1 AS id_category1,
                            a.sts AS status
                        FROM
                            ms_pr_material a
                            LEFT JOIN users b ON b.id_user = a.dibuat_oleh
                            LEFT JOIN m_warehouse c ON c.id = a.id_gudang_to
                            LEFT JOIN ms_pr_material_detail d ON d.id_pr = a.id
                        WHERE 
                            d.id_category1 = '" . $material_stock->id_category1 . "' AND a.sts = '1' AND a.id IS NOT NULL AND a.tgl < '" . $results['tgl_from'] . "'

                        union all

                        select
                            'Outgoing' as xx,
                            a.tgl_request AS history_date,
                            b.full_name AS nm_by,
                            e.warehouse_nm AS dari_gudang,
                            c.warehouse_nm AS ke_gudang,
                            '0' AS qty_up,
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
                        WHERE
                            d.id_category1 = '" . $material_stock->id_category1 . "' AND a.status = '1' AND a.id IS NOT NULL AND a.tgl_request < '" . $results['tgl_from'] . "'
                        group by no_transaksi
                        ")->result();

                        foreach($get_history as $history):
                            $stock_awal += $history->qty_up;
                            $stock_awal -= $history->qty_down;
                        endforeach;


                    echo '
						<tr>
							<td style="text-align: center;">' . $no . '</td>
							<td style="text-align: center;">' . $material_stock->category_nm . '</td>
							<td style="text-align: left;">' . $material_stock->nama . '</td>
							<td style="text-align: center;">' . $material_stock->nm_packaging . '</td>
							<td style="text-align: right;">' . number_format($material_stock->konversi) . '</td>
							<td style="text-align: center;">' . $material_stock->nm_unit . '</td>
							<td style="text-align: right;">' . number_format($stock_awal, 2) . '</td>
							<td style="text-align: right;">' . number_format($get_in_stock->ttl_in_stock, 2) . '</td>
							<td style="text-align: right;">' . number_format($get_out_stock->ttl_out_stock, 2) . '</td>
							<td style="text-align: right;">' . number_format($material_stock->stock_unit, 2) . '</td>
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
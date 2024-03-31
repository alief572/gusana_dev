
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
        <h2>PT. GUNUNG SAGARA BUANA - Production Stock Report</h2>
        <h4>Per <?= date('d F Y') ?></h4>
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
                    <th class="text-center">Stock Unit</th>
                    <th class="text-center">Stock Unit (Kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($results['list_material_stock'] as $material_stock) :


                    echo '
						<tr>
							<td class="text-center">' . $no . '</td>
							<td class="text-center">' . $material_stock->category_nm . '</td>
							<td class="text-center">' . $material_stock->nama . '</td>
							<td class="text-center">' . $material_stock->nm_packaging . '</td>
							<td class="text-center">' . number_format($material_stock->konversi) . '</td>
							<td class="text-center">' . $material_stock->nm_unit . '</td>
							<td class="text-center">' . number_format($material_stock->stock_unit, 2) . '</td>
							<td class="text-center">' . number_format($material_stock->stock_unit * $material_stock->konversi, 2) . '</td>
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
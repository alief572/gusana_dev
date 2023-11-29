<?php
$id_product 	= (!empty($header[0]->id_product)) ? $header[0]->id_product : '0';
$variant_product 	= (!empty($header[0]->variant)) ? $header[0]->variant : '0';
$nm_product		= (isset($product) ? $product->nama : null);

$file_upload 	= (!empty($header[0]->file_upload)) ? $header[0]->file_upload : '';

$BERAT_MINUS = 0;
if (!empty($detail_additive)) {
	foreach ($detail_additive as $val => $valx) {
		$val++;
		$detail_custom    = $this->db->get_where('bom_detail_custom', array('no_bom_detail' => $valx['no_bom_detail'], 'category' => 'additive'))->result();
		$PENGURANGAN_BERAT = 0;
		foreach ($detail_custom as $valx2) {
			$PENGURANGAN_BERAT += $valx2->weight * $valx2->persen / 100;
		}
		$BERAT_MINUS += $PENGURANGAN_BERAT;
	}
}

$TOTAL_PRICE_ALL = 0;
?>
<div class="box box-primary">
	<div class="col-12 box-body">
		<br>
		<table width='100%'>
			<tr>
				<th width='20%'>Product Name</th>
				<td><?= $nm_product; ?></td>
			</tr>
			<tr>
				<th>Variant Product</th>
				<td><?= $variant_product; ?></td>
			</tr>
		</table>
		<hr>
		<table class='table' width='100%'>
			<thead>
				<tr>
					<th colspan='8'>A. Mixing & Proses</th>
				</tr>
				<tr>
					<th class='text-left' style='width: 3%;'>#</th>
					<th class='text-left'>Material Category</th>
					<th class='text-right' style='width: 8%;'>Berat</th>
					<th class='text-right' style='width: 1%;'></th>
					<th class='text-right' style='width: 8%;'>Berat Bersih</th>
					<th class='text-right' style='width: 8%;'>Price Ref</th>
					<th class='text-right' style='width: 8%;'>Total Price</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($detail as $val => $valx) {
					// echo '<pre>';
					// print_r($valx); 
					// echo'</pre>';
					// exit;
					$val++;
					$nm_material		= (!empty($GET_LEVEL3[$valx['id_category1']]['nama'])) ? $GET_LEVEL3[$valx['id_category1']]['nama'] : '-';


					$price_ref      = (!empty($GET_PRICE_REF[$valx['id_category1']]['price_ref_idr'])) ? $GET_PRICE_REF[$valx['id_category1']]['price_ref_idr'] : 0;

					$nm_category = strtolower(get_name('ms_inventory_category1', 'nama', 'id_category1', $valx['id_category1']));
					echo "<tr>";
					echo "<td align='left'>" . $val . "</td>";
					echo "<td>" . strtoupper($nm_category) . "</td>";
					echo "<td align='right'>" . number_format($valx['weight'], 2) . " Kg</td>";
					$berat_pengurang_additive = ($nm_category == 'resin') ? $BERAT_MINUS : 0;
					// if($nm_category == 'resin'){
					// 	echo "<td align='right' class='text-red'>".number_format($berat_pengurang_additive,4)." Kg</td>";
					// }
					// else{
					echo "<td align='right' class='text-red'></td>";
					// }
					$berat_bersih = $valx['weight'] - $berat_pengurang_additive;
					$total_price = $berat_bersih * $price_ref;
					$TOTAL_PRICE_ALL += $total_price;
					echo "<td align='right'>" . number_format($berat_bersih, 2) . " Kg</td>";
					echo "<td align='right' class='text-green'>" . number_format($price_ref, 2) . "</td>";
					echo "<td align='right' class='text-blue'>" . number_format($total_price, 2) . "</td>";
					echo "</tr>";
				}
				?>
			</tbody>
			<tbody>
				<tr>
					<th class='text-left' colspan='1'></th>
					<th class='text-left' colspan='5'>TOTAL PRICE</th>
					<th class='text-right text-red'><?= number_format($TOTAL_PRICE_ALL, 2); ?></th>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php
$totalTime = 0;
$TimeTotal = get_total_time_ct($header[0]->id_product);
if ($TimeTotal > 0) {
	$totalTime = $TimeTotal / 60;
}
?>
<div class="box box-primary">
	<div class="box-body"><br>
		<div class="form-group row">
			<div class="col-md-2">
				<label for="inventory_1">Product Name</label>
			</div>
			<div class="col-md-10">
				<input type="text" class="form-control input-sm" id="spec6" name="spec6" readonly="readonly" value="<?= strtoupper($header[0]->nm_product); ?>">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-12">
				<div class="tableFixHead" style="height:600px;">
					<table id="example1" border='0' width='100%' class="table table-striped table-bordered table-hover table-condensed">
						<thead class="thead">
							<tr class='bg-blue'>
								<th class='text-center th' width='5%'>#</th>
								<th class='text-center th'>Cost Center</th>
								<th class='text-center th' width='12%'>Time (minutes)</th>
								<th class='text-center th' width='12%'>Man Power</th>
								<th class='text-center th' width='24%'>Information</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$q_header_test = $this->db->query("SELECT * FROM cycletime_detail_header WHERE id_time='" . $header[0]->id_time . "'")->result_array();
							$nox = 0;
							$ttl_CT = 0;
							foreach ($q_header_test as $val2 => $val2x) {
								$nox++;

								echo "<tr>";
								echo "<td align='center'>" . $nox . "</td>";
								echo "<td align='left'><b>" . strtoupper(get_name('ms_costcenter', 'nama_costcenter', 'id_costcenter', $val2x['costcenter'])) . "</b></td>";
								echo "<td align='right'><b></b></td>";
								echo "<td align='right'><b></b></td>";
								echo "<td align='left'></td>";
								echo "</tr>";
								$q_dheader_test = $this->db->query("SELECT a.* FROM cycletime_detail_detail a WHERE a.id_costcenter='" . $val2x['id_costcenter'] . "'")->result_array();
								
								foreach ($q_dheader_test as $val2D => $val2Dx) {
									$val2D++;
									$nomor = ($val2D == 1) ? $val2D : '';

									$CT2 = (get_name('asset', 'nm_asset', 'id', $val2Dx['machine']) != '0') ? get_name('asset', 'nm_asset', 'id', $val2Dx['machine']) : '';
									$MP2 = (get_name('asset', 'nm_asset', 'id', $val2Dx['mould']) != '0') ? get_name('asset', 'nm_asset', 'id', $val2Dx['mould']) : '';

									$CT = ($val2Dx['cycletime'] != 0) ? $val2Dx['cycletime'] : '';
									$MP = ($val2Dx['qty_mp'] != 0) ? $val2Dx['qty_mp'] : '';
									echo "<tr>";
									echo "<td align='center'></td>";
									echo "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $val2Dx['nm_process'];
									if ($CT2 != '') {
										echo "<br><span class='text-primary'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $CT2 . "</span>";
									}
									if ($MP2 != '') {
										echo "<br><span class='text-primary'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $MP2 . "</span>";
									}
									echo "</td>";
									echo "<td align='right'>" . $CT . " Minutes</td>";
									echo "<td align='right'>" . $MP . "</td>";
									echo "<td align='left'>" . $val2Dx['note'] . "</td>";
									echo "</tr>";
									$ttl_CT += $CT;
								}
							}
							?>

							<tr>
								<td class='text-center'></td>
								<td class='text-right text-bold'>Total Time</td>
								<td class='text-right text-bold'><?= number_format($ttl_CT, 2) ?> Minutes</td>
								<td class='text-center'></td>
								<td class='text-center'></td>
							</tr>
							<tr>
								<td class='text-center'></td>
								<td class='text-right text-bold'>Cycletime</td>
								<td class='text-right text-bold'><?= number_format(($ttl_CT / 60), 2); ?> Hours</td>
								<td class='text-center'></td>
								<td class='text-center'></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<style media="screen">
	/* JUST COMMON TABLE STYLES... */
	.table {
		border-collapse: collapse;
		width: 100%;
	}

	.td {
		background: #fff;
		padding: 8px 16px;
	}

	.tableFixHead {
		overflow: auto;
		height: 300px;
		position: sticky;
		top: 0;
	}

	.thead .th {
		position: sticky;
		top: 0;
		z-index: 9999;
		background: #0073b7;
	}
</style>
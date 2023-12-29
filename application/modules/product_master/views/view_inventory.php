<?php
$ENABLE_ADD     = has_permission('Product_Master.Add');
$ENABLE_MANAGE  = has_permission('Product_Master.Manage');
$ENABLE_VIEW    = has_permission('Product_Master.View');
$ENABLE_DELETE  = has_permission('Product_Master.Delete');

?>
<div class="box box-primary">
	<div class="box-body">
		<form id="data-form" method="post">
			<div class="col-sm-12">
				<table class="table">
					<tr>
						<th>Product Type</th>
						<td>:</td>
						<td colspan="4"><?= $results['inventory_4']->nama_material_1; ?></td>
					</tr>
					<tr>
						<th>Product Category</th>
						<td>:</td>
						<td colspan="4"><?= $results['inventory_4']->nama_material_2; ?></td>
					</tr>
					<tr>
						<th>Product Jenis</th>
						<td>:</td>
						<td colspan="4"><?= $results['inventory_4']->nama_material_3; ?></td>
					</tr>
					<tr>
						<th>Product Master</th>
						<td>:</td>
						<td colspan="4"><?= $results['inventory_4']->nama; ?></td>
					</tr>
					<tr>
						<th>Product Code</th>
						<td>:</td>
						<td><?= $results['inventory_4']->product_code; ?></td>
						<th>Curing Agent</th>
						<td>:</td>
						<td><?= $results['inventory_4']->nm_curing_agent; ?></td>
					</tr>
					<tr>
						<th>Curing Agent Konversi</th>
						<td>:</td>
						<td><?= $results['inventory_4']->curing_agent_konversi; ?></td>
						<th>MOQ</th>
						<td>:</td>
						<td><?= $results['inventory_4']->moq; ?></td>
					</tr>
					<tr>
						<th>Packaging Unit / Conversion</th>
						<td>:</td>
						<td><?= $results['inventory_4']->nama_packaging; ?></td>
						<th>Conversion</th>
						<td>:</td>
						<td><?= $results['inventory_4']->konversi; ?></td>
					</tr>
					<tr>
						<th>Unit Measurement</th>
						<td>:</td>
						<td colspan="4"><?= $results['inventory_4']->nm_unit; ?></td>
					</tr>
					<tr>
						<th>Maximum Stok</th>
						<td>:</td>
						<td><?= $results['inventory_4']->max_stok ?></td>
						<th>Minimum Stok</th>
						<td>:</td>
						<td><?= $results['inventory_4']->min_stok ?></td>
					</tr>
					<tr>
						<th>MSDS</th>
						<td>:</td>
						<td colspan="4">
							<?php if (file_exists('.' . $results['inventory_4']->pds)) : ?>
								<a href="<?= '.' . $results['inventory_4']->pds ?>" target="_blank"><?= str_replace('/uploads/', '', $results['inventory_4']->pds); ?></a>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th>Dimensi (L x W x H / Meter)</th>
						<td>:</td>
						<td colspan="4">
							<?= $results['inventory_4']->dim_length . ' x ' . $results['inventory_4']->dim_width . ' x ' . $results['inventory_4']->dim_height ?>
						</td>
					</tr>
					<tr>
						<th>CBM (Meter)</th>
						<td>:</td>
						<td colspan="4">
							<?= number_format($results['inventory_4']->cbm, 2) ?>
						</td>
					</tr>
					<tr>
						<th>Dimensi Tabung (22/7 x r<sup>2</sup> x t)</th>
						<td>:</td>
						<td colspan="4">
							<?= '22/7 x ' . ($results['inventory_4']->dim_tabung_r * $results['inventory_4']->dim_tabung_r) . ' x ' . $results['inventory_4']->dim_tabung_t ?>
						</td>
					</tr>
					<tr>
						<th>CBM Tabung (Meter)</th>
						<td>:</td>
						<td colspan="4">
							<?= number_format($results['inventory_4']->cbm_tabung, 2) ?>
						</td>
					</tr>
					<tr>
						<th>Status</th>
						<td>:</td>
						<td colspan="4">
							<?php if ($results['inventory_4']->aktif == 1) : ?>
								<div class="badge badge-primary">Aktif</div>
							<?php else : ?>
								<div class="badge badge-danger">Non Aktif</div>
							<?php endif; ?>
						</td>
					</tr>
				</table>
				<table class="table">
					<tr>
						<th class="text-center" colspan="6">
							<b>Spesifikasi</b>
						</th>
					</tr>
					<tr>
						<th>Aplikasi penggunaan cat dan coating</th>
						<td>:</td>
						<td><?= $results['aplikasi_penggunaan_cat'] ?></td>
						<th>Water Resistance</th>
						<td>:</td>
						<td><?= $results['water_resistance'] ?></td>
					</tr>
					<tr>
						<th>Weather & UV resistance</th>
						<td>:</td>
						<td><?= $results['weather_uv_resistance'] ?></td>
						<th>Corrosion Resistance</th>
						<td>:</td>
						<td><?= $results['corrosion_resistance'] ?></td>
					</tr>
					<tr>
						<th>Heat Resistance</th>
						<td>:</td>
						<td><?= $results['heat_resistant'] ?></td>
						<th>Daya Rekat (Adhesi)</th>
						<td>:</td>
						<td><?= $results['daya_rekat'] ?></td>
					</tr>
					<tr>
						<th>Lama Pengeringan</th>
						<td>:</td>
						<td><?= $results['lama_pengeringan'] ?></td>
						<th>Permukaan</th>
						<td>:</td>
						<td><?= $results['permukaan'] ?></td>
					</tr>
					<tr>
						<th>Anti Jamur dan Lumut</th>
						<td>:</td>
						<td><?= $results['anti_jamur_lumut'] ?></td>
						<th>Mudah Dibersihkan (Dirt Resistance)</th>
						<td>:</td>
						<td><?= $results['mudah_dibersihkan'] ?></td>
					</tr>
					<tr>
						<th>Anti Bakteri</th>
						<td>:</td>
						<td><?= $results['anti_bakteri'] ?></td>
						<th>Daya tahan gesekan</th>
						<td>:</td>
						<td><?= $results['daya_tahan_gesekan'] ?></td>
					</tr>
					<tr>
						<th>Anti Slip</th>
						<td>:</td>
						<td><?= $results['anti_slip'] ?></td>
						<th>Fire Resistance</th>
						<td>:</td>
						<td><?= $results['fire_resistance'] ?></td>
					</tr>
					<tr>
						<th>Ketahanan Bahan Kimia</th>
						<td>:</td>
						<td><?= $results['ketahanan_bahan_kimia'] ?></td>
					</tr>
				</table>
				<h4 class="text-center">Alternatif Competitor</h4>
				<table class="table">
					<thead>
						<tr>
							<th class="text-center">Nama Competitor</th>
							<th class="text-center">Nama Produk</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($results['alt_comp'] as $alt_comp) : ?>
							<tr>
								<td class="text-center"><?= $alt_comp->nm_competitor ?></td>
								<td class="text-center"><?= $alt_comp->nm_product ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<hr>

		</form>



	</div>
</div>




<script type="text/javascript">
	//$('#input-kendaraan').hide();
	var base_url = '<?php echo base_url(); ?>';
	var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

	$(document).ready(function() {
		var data_pay = <?php echo json_encode($results['supplier']); ?>;
		var max_fields2 = 10; //maximum input boxes allowed
		var wrapper2 = $(".input_fields_wrap2"); //Fields wrapper
		var add_button2 = $(".add_field_button2"); //Add button ID
		//console.log(persen);

		var x2 = 1; //initlal text box count
		$(add_button2).click(function(e) { //on add input button click
			e.preventDefault();
			if (x2 < max_fields2) { //max input box allowed
				x2++; //text box increment

				$(wrapper2).append('<div class="row">' +
					'<div class="col-xs-1">' + x2 + '</div>' +
					'<div class="col-xs-3">' +
					'<div class="input-group">' +
					'<input type="text" name="hd' + x2 + '[produk]"  class="form-control input-sm" value="">' +
					'</div>' +
					'<div class="input-group">' +
					'<input type="text" name="hd' + x2 + '[costcenter]"  class="form-control input-sm" value="">' +
					'</div>' +
					'<div class="input-group">' +
					'<input type="text" name="hd' + x2 + '[mesin]"  class="form-control input-sm" value="">' +
					'</div>' +
					'<div class="input-group">' +
					'<input type="text" name="hd' + x2 + '[mold_tools]"  class="form-control input-sm" value="">' +
					'</div>' +
					'</div>' +
					'<a href="#" class="remove_field2">Remove</a>' +
					'</div>'); //add input box
				$('#datepickerxxr' + x2).datepicker({
					format: 'dd-mm-yyyy',
					autoclose: true
				});
			}
		});

		$(wrapper2).on("click", ".remove_field2", function(e) { //user click on remove text
			e.preventDefault();
			$(this).parent('div').remove();
			x2--;
		})



		$('#add-payment').click(function() {
			var jumlah = $('#list_payment').find('tr').length;
			if (jumlah == 0 || jumlah == null) {
				var ada = 0;
				var loop = 1;
			} else {
				var nilai = $('#list_payment tr:last').attr('id');
				var jum1 = nilai.split('_');
				var loop = parseInt(jum1[1]) + 1;
			}
			Template = '<tr id="tr_' + loop + '">';
			Template += '<td align="left">';
			Template += '<select id="id_supplier" name="data1[' + loop + '][id_supplier]" id="data1_' + loop + '_id_supplier" class="form-control select" required>';
			Template += '<option value="">-- Pilih Type --</option>';
			Template += '<?php foreach ($results["id_supplier"] as $id_supplier) { ?>';
			Template += '<option value="<?= $id_supplier->id_suplier ?>"><?= ucfirst(strtolower($id_supplier->name_suplier)) ?></option>';
			Template += '<?php } ?>';
			Template += '</select>';
			Template += '</td>';
			Template += '<td align="left">';
			Template += '<input type="text" class="form-control input-sm" name="data1[' + loop + '][lead]" id="data1_' + loop + '_lead" label="FALSE" div="FALSE">';
			Template += '</td>';
			Template += '<td align="left">';
			Template += '<input type="text" class="form-control input-sm" name="data1[' + loop + '][minimum]" id="data1_' + loop + '_minimum" label="FALSE" div="FALSE">';
			Template += '</td>';
			Template += '<td align="center"><button type="button" class="btn btn-sm btn-danger" title="Hapus Data" data-role="qtip" onClick="return DelItem(' + loop + ');"><i class="fa fa-trash-o"></i></button></td>';
			Template += '</tr>';
			$('#list_payment').append(Template);
			$('input[data-role="tglbayar"]').datepicker({
				format: 'dd-mm-yyyy',
				autoclose: true
			});
		});





	});
	$('#inventory_2').change(function() {
		var inventory_2 = $("#inventory_2").val();
		$.ajax({
			type: "GET",
			url: siteurl + 'inventory_4/get_compotition',
			data: "inventory_2=" + inventory_2,
			success: function(html) {
				$("#list_compotition").html(html);
			}
		});
	});

	function get_inv2() {
		var inventory_1 = $("#inventory_1").val();
		$.ajax({
			type: "GET",
			url: siteurl + 'inventory_4/get_inven2',
			data: "inventory_1=" + inventory_1,
			success: function(html) {
				$("#inventory_2").html(html);
			}
		});
	}

	function get_bentuk() {
		var id_bentuk = $("#id_bentuk").val();
		$.ajax({
			type: "GET",
			url: siteurl + 'inventory_4/get_dimensi',
			data: "id_bentuk=" + id_bentuk,
			success: function(html) {
				$("#list_dimensi").html(html);
			}
		});
	}

	function get_inv3() {
		var inventory_2 = $("#inventory_2").val();
		$.ajax({
			type: "GET",
			url: siteurl + 'inventory_4/get_inven3',
			data: "inventory_2=" + inventory_2,
			success: function(html) {
				$("#inventory_3").html(html);
			}
		});
	}

	function DelItem(id) {
		$('#list_payment #tr_' + id).remove();

	}
</script>
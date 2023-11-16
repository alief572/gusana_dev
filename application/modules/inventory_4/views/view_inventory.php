<?php
$ENABLE_ADD     = has_permission('master_bentuk.Add');
$ENABLE_MANAGE  = has_permission('master_bentuk.Manage');
$ENABLE_VIEW    = has_permission('master_bentuk.View');
$ENABLE_DELETE  = has_permission('master_bentuk.Delete');

?>
<div class="box box-primary">
	<div class="box-body">
		<form id="data-form" method="post">
			<div class="col-sm-12">
				<table class="table">
					<tr>
						<th>Material Lv 1</th>
						<td>:</td>
						<td><?= $results['inventory_4']->nama_material_1; ?></td>
						<th>Material Lv 2</th>
						<td>:</td>
						<td><?= $results['inventory_4']->nama_material_2; ?></td>
					</tr>
					<tr>
						<th>Material Lv 3</th>
						<td>:</td>
						<td><?= $results['inventory_4']->nama_material_3; ?></td>
						<th>Nama Material</th>
						<td>:</td>
						<td><?= $results['inventory_4']->nama; ?></td>
					</tr>
					<tr>
						<th>Other Name</th>
						<td>:</td>
						<td><?= $results['inventory_4']->other_name; ?></td>
						<th>Supplier Utama</th>
						<td>:</td>
						<td><?= $results['inventory_4']->nama_supplier; ?></td>
					</tr>
					<tr>
						<th>Lead Time</th>
						<td>:</td>
						<td><?= $results['inventory_4']->lead_time; ?></td>
						<th>MOQ</th>
						<td>:</td>
						<td><?= $results['inventory_4']->moq; ?></td>
					</tr>
					<tr>
						<th>Packaging</th>
						<td>:</td>
						<td><?= $results['inventory_4']->nama_packaging; ?></td>
						<th>Konversi</th>
						<td>:</td>
						<td><?= $results['inventory_4']->konversi; ?></td>
					</tr>
					<tr>
						<th>Unit</th>
						<td>:</td>
						<td><?= $results['inventory_4']->nm_unit; ?></td>
						<th>Spesifikasi</th>
						<td>:</td>
						<td><?= $results['inventory_4']->spesifikasi; ?></td>
					</tr>
					<tr>
						<th>Brand</th>
						<td>:</td>
						<td><?= $results['inventory_4']->brand; ?></td>
						<th>MSDS</th>
						<td>:</td>
						<td>
							<?php if (file_exists('.' . $results['inventory_4']->msds)) : ?>
								<a href="<?= '.' . $results['inventory_4']->msds ?>" target="_blank"><?= str_replace('/uploads/', '', $results['inventory_4']->msds); ?></a>
							<?php endif; ?>
						</td>
					</tr>
				</table>
				<h4 class="text-center">Alternatif Supplier</h4>
				<table class="table">
					<thead>
						<tr>
							<th class="text-center">Nama Supplier</th>
							<th class="text-center">Lead Time</th>
							<th class="text-center">MOQ</th>
							<th class="text-center">Alternatif Material</th>
							<th class="text-center">Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($results['alt_suppliers'] as $alt_suppliers) : ?>
							<tr>
								<td class="text-center"><?= $alt_suppliers->supplier_name ?></td>
								<td class="text-center"><?= $alt_suppliers->lead_time ?></td>
								<td class="text-center"><?= $alt_suppliers->moq ?></td>
								<td class="text-center"><?= $alt_suppliers->nama ?></td>
								<td class="text-center"><?= $alt_suppliers->keterangan ?></td>
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
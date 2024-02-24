<?php
$ENABLE_ADD     = has_permission('Material_Jenis.Add');
$ENABLE_MANAGE  = has_permission('Material_Jenis.Manage');
$ENABLE_VIEW    = has_permission('Material_Jenis.View');
$ENABLE_DELETE  = has_permission('Material_Jenis.Delete');
?>
<style type="text/css">
	thead input {
		width: 100%;
	}
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert/dist/sweetalert.css') ?>">

<div class="box box-primary">
	<div class="box-body">


		<div class="form-group row">
			<div class="col-md-2">
				<label for="inventory_1" class="col-sm-3 control-label">Material Type</label>
			</div>
			<div class="col-md-6">
				<select id="inventory_1" name="inventory_1" class="form-control form-control-sm select" onchange="get_inv2()" required>
					<option value="">-- Material Type --</option>
					<?php foreach ($results['inventory_1'] as $inventory_1) {
					?>
						<option value="<?= $inventory_1->id_type ?>"><?= ucfirst(strtolower($inventory_1->nama)) ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-2">
				<label for="inventory_2" class="col-sm-3 control-label">Material Category</label>
			</div>
			<div class="col-md-6">
				<select id="inventory_2" name="inventory_2" class="form-control form-control-sm select" required>
					<option value="">-- Material Category --</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group row">
					<div class="col-md-2">
						<label for="">Material Jenis</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control form-control-sm" id="" required name="nm_inventory" placeholder="Material Jenis">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2">
						<label for="">Material Jenis (Mandarin)</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control form-control-sm" id="" name="nm_inventory_mandarin" placeholder="Material Jenis (Mandarin)">
					</div>
				</div>

				<div class="form-group row">
					<div class="col-md-3">

					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- Modal Bidus-->
	<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
	<script src="<?= base_url('assets/plugins/sweetalert/dist/sweetalert.js') ?>"></script>
	<!-- End Modal Bidus-->

	<script type="text/javascript">
		$(document).ready(function() {
			$('.select').select2({
				placeholder: 'Choose one',
				dropdownParent: $('#dialog-popup'),
				width: "100%",
				allowClear: true
			});
			$("#inventory_1_old").change(function() {

				// variabel dari nilai combo box kendaraan
				var inventory_1 = $("#inventory_1").val();

				// Menggunakan ajax untuk mengirim dan dan menerima data dari server
				$.ajax({
					url: siteurl + 'material_jenis/get_inven2',
					method: "POST",
					data: {
						inventory_1: inventory_1
					},
					async: false,
					dataType: 'json',
					success: function(data) {
						var html = '';
						var i;

						for (i = 0; i < data.length; i++) {
							html += '<option value=' + data[i].id_category1 + '>' + data[i].nama + '</option>';
						}
						$('#inventory_2').html(html);

					}
				});
			});





		});


		function get_inv2() {
			var inventory_1 = $("#inventory_1").val();
			$.ajax({
				type: "GET",
				url: siteurl + 'material_jenis/get_inven2',
				data: "inventory_1=" + inventory_1,
				success: function(html) {
					$("#inventory_2").html(html);
				}
			});
		}
	</script>
<?php
$ENABLE_ADD     = has_permission('Product_Jenis.Add');
$ENABLE_MANAGE  = has_permission('Product_Jenis.Manage');
$ENABLE_VIEW    = has_permission('Product_Jenis.View');
$ENABLE_DELETE  = has_permission('Product_Jenis.Delete');
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
				<label for="product_type" class="col-sm-3 control-label">Product Type</label>
			</div>
			<div class="col-md-6">
				<select id="product_type" name="product_type" class="form-control form-control-sm select chosen-select" onchange="get_inv2()" required>
					<option value="">-- Product Type --</option>
					<?php foreach ($results['product_type'] as $product_type) {
					?>
						<option value="<?= $product_type->id_type ?>"><?= ucfirst(strtolower($product_type->nama)) ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-2">
				<label for="product_category" class="col-sm-3 control-label">Product Category</label>
			</div>
			<div class="col-md-6">
				<select id="product_category" name="product_category" class="form-control form-control-sm select chosen-select" required>
					<option value="">-- Product Category --</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group row">
					<div class="col-md-2">
						<label for="">Product Jenis</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control form-control-sm" id="" required name="nm_product_jenis" placeholder="Product Jenis">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group row">
					<div class="col-md-2">
						<label for="">Product Jenis (Mandarin)</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control form-control-sm" id="" name="nm_product_jenis_mandarin" placeholder="Product Jenis">
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
			$('.chosen-select').select2({
				dropdownParent: $('#dialog-popup'),
				selectOnClose: true,
				width: '100%'
			});
			$("#product_type_old").change(function() {

				// variabel dari nilai combo box kendaraan
				var product_type = $("#product_type").val();

				// Menggunakan ajax untuk mengirim dan dan menerima data dari server
				$.ajax({
					url: siteurl + 'product_jenis/get_inven2',
					method: "POST",
					data: {
						product_type: product_type
					},
					async: false,
					dataType: 'json',
					success: function(data) {
						var html = '';
						var i;

						for (i = 0; i < data.length; i++) {
							html += '<option value=' + data[i].id_category1 + '>' + data[i].nama + '</option>';
						}
						$('#product_category').html(html);

					}
				});
			});





		});


		function get_inv2() {
			var product_type = $("#product_type").val();
			$.ajax({
				type: "GET",
				url: siteurl + 'product_jenis/get_inven2',
				data: "product_type=" + product_type,
				success: function(html) {
					$("#product_category").html(html);
				}
			});
		}
	</script>
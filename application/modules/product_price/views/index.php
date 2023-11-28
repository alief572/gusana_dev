<?php
$ENABLE_ADD     = has_permission('Product_Price.Add');
$ENABLE_MANAGE  = has_permission('Product_Price.Manage');
$ENABLE_VIEW    = has_permission('Product_Price.View');
$ENABLE_DELETE  = has_permission('Product_Price.Delete');
?>
<style type="text/css">
	thead input {
		width: 100%;
	}
</style>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>">

<div class="box">
	<div class="box-header">
		<span class="pull-left">
			<button type='button' class='btn btn-sm btn-primary' id='btnUpdate'>Update Product Price</button>
			<br>
			<span class='text-bold text-red'><?= $last_update; ?></span>
		</span>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th style="width:5%;">#</th>
					<!-- <th>Product Type</th> -->
					<th style="width:25%;">Product Master</th>
					<th  style="width:10%;" class='text-right'>Total Weight</th>
					<th style="width:10%;" class='text-right'>Price Material</th>
					<th style="width:10%;" class='text-right'>Price MP</th>
					<th style="width:10%;" class='text-right'>Price Total</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody></tbody>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>

<!-- page script -->
<script type="text/javascript">
	// DELETE DATA
	$(document).on('click', '#btnUpdate', function(e) {
		e.preventDefault()
		new swal({
				title: "Anda Yakin?",
				text: "Menarik Price Ref & BOM Terbaru !",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-info",
				confirmButtonText: "Ya, Update!",
				cancelButtonText: "Batal",
				closeOnConfirm: false
			}).then((hasil) => {
				console.log(base_url + thisController + '/update_product_price');
				if(hasil.isConfirmed){
					$.ajax({
					type: 'POST',
					url: base_url + thisController + '/update_product_price',
					dataType: "json",
					success: function(result) {
						console.log(result);

						if (result.status == '1') {
							new swal({
								title: "Sukses",
								text: "Data berhasil diupdate.",
								type: "success"
							}).then((hasil1) => {
								window.location.reload(true);
							});
						} else {
							new swal({
								title: "Error",
								text: "Data error. Gagal diupdate",
								type: "error"
							})

						}
					},
					error: function() {
						new swal({
							title: "Error",
							text: "Data error. Gagal request Ajax",
							type: "error"
						})
					}
				});
				}
			});

	});

	$(function() {
		DataTables();
	});


	function DataTables() {
		var dataTable = $('#example1').DataTable({
			// "scrollX": true,
			// "scrollY": "500",
			// "scrollCollapse" : true,
			"processing": true,
			"serverSide": true,
			"stateSave": true,
			"bAutoWidth": true,
			"destroy": true,
			"responsive": true,
			"aaSorting": [
				[1, "asc"]
			],
			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
			}],
			"sPaginationType": "simple_numbers",
			"iDisplayLength": 10,
			"aLengthMenu": [
				[10, 20, 50, 100, 150],
				[10, 20, 50, 100, 150]
			],
			"ajax": {
				url: base_url + thisController + '/data_side_product_price',
				type: "post",
				data: function(d) {
					// d.kode_partner = $('#kode_partner').val()
				},
				cache: false,
				error: function() {
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display", "none");
				}
			}
		});
	}
</script>
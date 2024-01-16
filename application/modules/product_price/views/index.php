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
			<button type='button' class='btn btn-sm btn-primary' id='btnUpdate'>Update Product Price</button> <span class="text-danger">(更新品价)</span>
			<br>
			<span class='text-bold text-red'><?= $last_update; ?></span> <br>
			<button type="button" class="btn btn-sm btn-success add_product_set">
				<i class="fa fa-plus"></i> Add Product Set
			</button> <span class="text-danger">(添加品价)</span>
		</span>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>#</th>
					<!-- <th>Product Type</th> -->
					<th>Product Master <span class="text-danger">(产品全名)</span></th>
					<th>Product Master (Mandarin) <span class="text-danger">(產品中文名稱)</span></th>
					<th class='text-right'>Lot Size <span class="text-danger">(产品名称)</span></th>
					<th class='text-right'>Material Price <span class="text-danger">(材料价格)</span></th>
					<th class='text-right'>Manpower Price <span class="text-danger">(人力成本)</span></th>
					<th class='text-right'>Price Total <span class="text-danger">(总价格)</span></th>
					<th class='text-center'>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<div class="modal fade" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg mx-wd-md-70p-force mx-wd-lg-70p-force">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><span class="fa fa-users"></span>&nbsp;Add Product Set</h4>
			</div>
			<div class="modal-body" id="ModalView">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save_price">Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">
					<span class="glyphicon glyphicon-remove"></span> Close</button>
			</div>
		</div>
	</div>
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
			if (hasil.isConfirmed) {
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

	$(document).on('click', '.add_product_set', function() {
		var id = $(this).data('id_bentuk');
		// $(".modal-title").html("<i class='fa fa-list-alt'></i><b></b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'product_price/add_product_set/',
			data: {
				'id': ''
			},
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);
			}
		})
	});

	$(document).on('click', '.save_price', function() {

		var product_master = $('.product_master').val();
		var lot_size = $('.lot_size').val();

		if (product_master == '' || lot_size == '') {
			new swal({
				title: "Please make sure Product Master and Lot Size is filled !",
				text: data.pesan,
				type: "warning",
				timer: 1500,
				showCancelButton: false,
				showConfirmButton: false,
				allowOutsideClick: false
			});
		} else {
			$.ajax({
				type: 'post',
				url: siteurl + thisController + 'add_product_set2',
				data: {
					'product_master': product_master,
					'lot_size': lot_size
				},
				cache: false,
				dataType: 'json',
				success: function(result) {
					
					if (result.hasil == 1) {
						new swal({
							title: "Save Success !",
							text: result.msg,
							type: "success",
							timer: 1500,
							showCancelButton: false,
							showConfirmButton: false,
							allowOutsideClick: false
						});
					} else {
						new swal({
							title: "Save Failed !",
							text: result.msg,
							type: "warning",
							timer: 1500,
							showCancelButton: false,
							showConfirmButton: false,
							allowOutsideClick: false
						});
					}
					$('#dialog-popup').modal('hide');
					DataTables();
				}
			});
		}

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
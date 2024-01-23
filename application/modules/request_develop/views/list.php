<?php
$ENABLE_ADD     = has_permission('Request_Development.Add');
$ENABLE_MANAGE  = has_permission('Request_Development.Manage');
$ENABLE_VIEW    = has_permission('Request_Development.View');
$ENABLE_DELETE  = has_permission('Request_Development.Delete');
$id_bentuk = $this->uri->segment(3);
?>
<style type="text/css">
	thead input {
		width: 100%;
	}
</style>
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> -->
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<!-- <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>"> -->
<div class="box">
	<div class="box-header">
		<a class="btn btn-success btn-sm add" href="javascript:void(0)" data-id_bentuk="<?= $id_bentuk ?>" title="Add"><i class="fa fa-plus">&nbsp;</i>Add New Product</a>

		<span class="pull-right">
		</span>
	</div>
	<!-- /.box-header -->
	<!-- /.box-header -->
	<div class="box-body">
		<table id="dataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Product Type</th>
					<th>Product Category</th>
					<th>Product Jenis</th>
					<th>Keterangan</th>
					<th>Status</th>
					<?php if ($ENABLE_MANAGE) : ?>
						<th>Action</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<!-- awal untuk modal dialog -->
<!-- Modal -->
<div class="modal modal-primary" id="dialog-rekap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg mx-wd-md-70p-force mx-wd-lg-70p-force">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Rekap Data Customer</h4>
			</div>
			<div class="modal-body" id="MyModalBody">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<span class="glyphicon glyphicon-remove"></span> Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal  fade" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg mx-wd-md-70p-force mx-wd-lg-70p-force">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="head_title"><span class="fa fa-users"></span>&nbsp;</h4>
			</div>
			<div class="modal-body" id="ModalView">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">
					<span class="glyphicon glyphicon-remove"></span> Close</button>
			</div>
		</div>
	</div>
</div>

<!-- DataTables -->
<!-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script> -->
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>

<!-- page script -->
<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var active_controller = '<?php echo ($this->uri->segment(1)); ?>';



	$(document).ready(function() {
		loadData();
	});

	$(document).on("change", ".gabung_nama", function() {
		var inv_lv_1 = $(".inv_lv_1").val();
		var inv_lv_2 = $(".inv_lv_2").val();
		var inv_lv_3 = $(".inv_lv_3").val();

		if (inv_lv_1 !== "" || inv_lv_2 !== "" || inv_lv_3 !== "") {
			$.ajax({
				type: "POST",
				url: siteurl + 'request_develop/gabung_nama',
				data: {
					"inv_lv_1": inv_lv_1,
					"inv_lv_2": inv_lv_2,
					"inv_lv_3": inv_lv_3
				},
				success: function(result) {
					$(".nm_lv_4").val(result);
				}
			});
		} else {
			$(".nm_lv_4").val("");
		}
	});
	$(document).on('click', '.edit', function(e) {
		var id = $(this).data('id');
		var id_type = $(this).data('id_type');
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Edit Inventory</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'request_develop/editInventory/' + id,
			data: {
				"id_type": id_type
			},
			success: function(data) {
				$("#dialog-popup").modal();
				// console.log(data);
				$("#ModalView").html(data);
				$('#simpan-com').removeClass('d-none');
			}
		})
	});

	$(document).on('click', '.copy', function(e) {
		var id = $(this).data('id');
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Copy Inventory</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'request_develop/copyInventory/' + id,
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);

			}
		})
	});

	$(document).on('click', '.view', function() {
		var id = $(this).data('id');
		// alert(id);
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>View Request Develop</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'request_develop/viewInventory/' + id,
			data: {
				'id': id
			},
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);
				$('#simpan-com').addClass('d-none');
			}
		})
	});
	$(document).on('click', '.add', function() {
		var id = $(this).data('id_bentuk');
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Add Request Development</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'request_develop/addInventory/' + id,
			data: {
				'id': id
			},
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);
				$('#simpan-com').removeClass('d-none');

			}
		})
	});

	$(document).on("submit", "#edit-form", function(e) {
		e.preventDefault();
		var deskripsi = $('#deskripsi').val();
		var image = $('#image').val();
		var idtype = $('#product_master').val();

		var data, xhr;
		new swal({
			title: "Are you sure?",
			text: "You will not be able to process again this data!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, Process it!",
			cancelButtonText: "No, cancel process!",
			closeOnConfirm: true,
			closeOnCancel: false
		}).then((hasil) => {
			if (hasil.isConfirmed) {
				var formData = new FormData($(this)[0]);
				var baseurl = siteurl + 'request_develop/saveEditInventory';
				$.ajax({
					url: baseurl,
					type: "POST",
					data: formData,
					cache: false,
					dataType: 'json',
					processData: false,
					contentType: false,
					async: false,
					success: function(data) {
						// console.log(formData);
						if (data.status == 1) {
							new swal({
								title: "Save Success!",
								text: data.pesan,
								type: "success",
								timer: 1500,
								showCancelButton: false,
								showConfirmButton: false,
								allowOutsideClick: false
							});
							$("#dialog-popup").modal('hide');
							loadData_reload();
						} else {
							if (data.status == 2) {
								new swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 1500,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: false
								}).then((hasil3) => {
									$("#dialog-popup").modal('hide');
									loadData_reload();
								});
							} else {
								new swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 1500,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: false
								}).then((hasil4) => {
									$("#dialog-popup").modal('hide');
									loadData_reload();
								});
							}

						}
					},
					error: function() {

						new swal({
							title: "Error Message !",
							text: 'An Error Occured During Process. Please try again..',
							type: "warning",
							timer: 1500,
							showCancelButton: false,
							showConfirmButton: false,
							allowOutsideClick: false
						});
					}
				});
			} else {
				new swal("Cancelled", "Data can be process again :)", "error");
				return false;
			}
		});
	});

	$(document).on("submit", "#data-form", function(e) {
		e.preventDefault();
		var deskripsi = $('#deskripsi').val();
		var image = $('#image').val();
		var idtype = $('#product_master').val();

		var data, xhr;
		new swal({
			title: "Are you sure?",
			text: "You will not be able to process again this data!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, Process it!",
			cancelButtonText: "No, cancel process!",
			closeOnConfirm: true,
			closeOnCancel: false
		}).then((hasil) => {
			if (hasil.isConfirmed) {
				var formData = new FormData($(this)[0]);
				var baseurl = siteurl + 'request_develop/saveNewInventory';
				$.ajax({
					url: baseurl,
					type: "POST",
					data: formData,
					cache: false,
					dataType: 'json',
					processData: false,
					contentType: false,
					async: false,
					success: function(data) {
						// console.log(formData);
						if (data.status == 1) {
							new swal({
								title: "Save Success!",
								text: data.pesan,
								type: "success",
								timer: 1500,
								showCancelButton: false,
								showConfirmButton: false,
								allowOutsideClick: false
							});
							$("#dialog-popup").modal('hide');
							loadData_reload();
						} else {
							if (data.status == 2) {
								new swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 1500,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: false
								}).then((hasil3) => {
									$("#dialog-popup").modal('hide');
									loadData_reload();
								});
							} else {
								new swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 1500,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: false
								}).then((hasil4) => {
									$("#dialog-popup").modal('hide');
									loadData_reload();
								});
							}

						}
					},
					error: function() {

						new swal({
							title: "Error Message !",
							text: 'An Error Occured During Process. Please try again..',
							type: "warning",
							timer: 1500,
							showCancelButton: false,
							showConfirmButton: false,
							allowOutsideClick: false
						});
					}
				});
			} else {
				new swal("Cancelled", "Data can be process again :)", "error");
				return false;
			}
		});
	});


	// DELETE DATA
	$(document).on('click', '.delete', function(e) {
		e.preventDefault()
		var id = $(this).data('id');
		// alert(id);
		new swal({
			title: "Anda Yakin?",
			text: "Data Request Develop akan di hapus.",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-info",
			confirmButtonText: "Ya, Hapus!",
			cancelButtonText: "Batal",
			closeOnConfirm: false
		}).then((hasil) => {
			if (hasil.isConfirmed) {
				$.ajax({
					type: 'POST',
					url: siteurl + 'request_develop/deleteInventory',
					dataType: "json",
					data: {
						'id': id
					},
					success: function(result) {
						if (result.status == '1') {
							new swal({
								title: "Sukses",
								text: "Data Request Develop berhasil dihapus.",
								type: "success",
								timer: 1500
							}).then((hasil1) => {
								// $("#dialog-popup").modal();
								loadData_reload();
							});
						} else {
							new swal({
								title: "Error",
								text: "Data error. Gagal hapus data",
								type: "error",
								timer: 1500
							})

						}
					},
					error: function() {
						new swal({
							title: "Error",
							text: "Data error. Gagal request Ajax",
							type: "error",
							timer: 1500
						})
					}
				});
			}
		});
	});

	$(document).on("click", ".add_alt_comp", function(e) {
		e.preventDefault();
		var id_category3 = $(".id_category3").val();
		var comp = $(".alt_comp").val();
		var produk = $(".alt_produk").val();

		$(".add_alt_comp").prop("disabled", true);

		$.ajax({
			type: "POST",
			url: siteurl + 'request_develop/addAltComp',
			data: {
				"id_category3": id_category3,
				"comp": comp,
				"produk": produk
			},
			cache: false,
			success: function(result) {
				$(".add_alt_comp").prop("disabled", false);
				$("#list_payment").html(result);
			}
		})
	});

	$(document).on("click", ".del_inventory_category3_alt_comp", function() {
		var id = $(this).data('id');
		var id_category3 = $(".id_category3").val();

		$(".del_inventory_category3_alt_comp").prop("disabled", true);

		$.ajax({
			type: "POST",
			url: siteurl + 'request_develop/delAltComp',
			data: {
				'id': id,
				'id_category3': id_category3
			},
			cache: true,
			success: function(result) {
				// alert(result);
				$("#list_payment").html(result);
			}
		});
	});

	function hitung_cbm() {
		var dim_length = parseFloat($(".dim_length").val());
		var dim_width = parseFloat($(".dim_width").val());
		var dim_height = parseFloat($(".dim_height").val());

		var cbm = parseFloat(dim_length * dim_width * dim_height);

		$(".cbm").val(cbm.toFixed(2));
	}


	//Delete

	function PreviewPdf(id) {
		param = id;
		tujuan = 'customer/print_request/' + param;

		$(".modal-body").html('<iframe src="' + tujuan + '" frameborder="no" width="570" height="400"></iframe>');
	}

	function PreviewRekap() {
		tujuan = 'customer/rekap_pdf';
		$(".modal-body").html('<iframe src="' + tujuan + '" frameborder="no" width="100%" height="400"></iframe>');
	}

	function loadData() {
		var oTable = $('#dataTable').DataTable({
			"processing": true,
			"serverSide": true,
			"stateSave": true,
			"bAutoWidth": true,
			"destroy": true,
			"responsive": true,
			"language": {
				"sSearch": "",
				'searchPlaceholder': 'Search...',
				'processing': `<div class="sk-wave">
                  <div class="sk-rect sk-rect1 bg-gray-800"></div>
                  <div class="sk-rect sk-rect2 bg-gray-800"></div>
                  <div class="sk-rect sk-rect3 bg-gray-800"></div>
                  <div class="sk-rect sk-rect4 bg-gray-800"></div>
                  <div class="sk-rect sk-rect5 bg-gray-800"></div>
                </div>`,
				"sLengthMenu": "Display _MENU_",
				"sInfo": "Display <b>_START_</b> to <b>_END_</b> from <b>_TOTAL_</b> data",
				"sInfoFiltered": "(filtered from _MAX_ total entries)",
				"sZeroRecords": "<i>Data tidak tersedia</i>",
				"sEmptyTable": "<i>Data tidak ditemukan</i>",
				"oPaginate": {
					"sPrevious": "<i class='fa fa-arrow-left' aria-hidden='true'></i>",
					"sNext": "<i class='fa fa-arrow-right' aria-hidden='true'></i>"
				}
			},
			"responsive": {
				"breakpoints": [{
						"name": 'desktop',
						"width": Infinity
					},
					{
						"name": 'tablet',
						"width": 1148
					},
					{
						"name": 'mobile',
						"width": 680
					},
					{
						"name": 'mobile-p',
						"width": 320
					}
				],
			},
			"aaSorting": [
				[1, "asc"]
			],
			"columnDefs": [{
					"targets": 'no-sort',
					"orderable": false,
				}, {
					"targets": 'text-center',
					"className": 'text-center',
				}, {
					"targets": 'tx-bold tx-dark',
					"className": 'tx-bold tx-dark',
				}, {
					"targets": 'tx-bold tx-dark text-center',
					"className": 'tx-bold tx-dark text-center',
				}

			],
			"sPaginationType": "simple_numbers",
			"iDisplayLength": 10,
			"aLengthMenu": [5, 10, 20, 50, 100, 150],
			"ajax": {
				url: siteurl + thisController + 'getData',
				type: "post",
				data: function(d) {
					d.status = 'D'
				},
				cache: false,
				error: function() {
					$(".my-grid-error").html("");
					$("#my-grid").append(
						'<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
					);
					$("#my-grid_processing").css("display", "none");
				}
			}
		});
	}

	function loadData_reload() {
		var oTable = $('#dataTable').DataTable({
			"processing": true,
			"serverSide": true,
			"stateSave": true,
			"bAutoWidth": true,
			"destroy": true,
			"responsive": true,
			"language": {
				"sSearch": "",
				'searchPlaceholder': 'Search...',
				'processing': `<div class="sk-wave">
                  <div class="sk-rect sk-rect1 bg-gray-800"></div>
                  <div class="sk-rect sk-rect2 bg-gray-800"></div>
                  <div class="sk-rect sk-rect3 bg-gray-800"></div>
                  <div class="sk-rect sk-rect4 bg-gray-800"></div>
                  <div class="sk-rect sk-rect5 bg-gray-800"></div>
                </div>`,
				"sLengthMenu": "Display _MENU_",
				"sInfo": "Display <b>_START_</b> to <b>_END_</b> from <b>_TOTAL_</b> data",
				"sInfoFiltered": "(filtered from _MAX_ total entries)",
				"sZeroRecords": "<i>Data tidak tersedia</i>",
				"sEmptyTable": "<i>Data tidak ditemukan</i>",
				"oPaginate": {
					"sPrevious": "<i class='fa fa-arrow-left' aria-hidden='true'></i>",
					"sNext": "<i class='fa fa-arrow-right' aria-hidden='true'></i>"
				}
			},
			"responsive": {
				"breakpoints": [{
						"name": 'desktop',
						"width": Infinity
					},
					{
						"name": 'tablet',
						"width": 1148
					},
					{
						"name": 'mobile',
						"width": 680
					},
					{
						"name": 'mobile-p',
						"width": 320
					}
				],
			},
			"aaSorting": [
				[1, "asc"]
			],
			"columnDefs": [{
					"targets": 'no-sort',
					"orderable": false,
				}, {
					"targets": 'text-center',
					"className": 'text-center',
				}, {
					"targets": 'tx-bold tx-dark',
					"className": 'tx-bold tx-dark',
				}, {
					"targets": 'tx-bold tx-dark text-center',
					"className": 'tx-bold tx-dark text-center',
				}

			],
			"sPaginationType": "simple_numbers",
			"iDisplayLength": 10,
			"aLengthMenu": [5, 10, 20, 50, 100, 150],
			"ajax": {
				url: siteurl + thisController + 'getData',
				type: "post",
				data: function(d) {
					d.status = 'D'
				},
				cache: false,
				error: function() {
					$(".my-grid-error").html("");
					$("#my-grid").append(
						'<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
					);
					$("#my-grid_processing").css("display", "none");
				}
			}
		});
	}
</script>
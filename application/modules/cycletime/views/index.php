<?php
$ENABLE_ADD     = has_permission('Cycletime_Detail.Add');
$ENABLE_MANAGE  = has_permission('Cycletime_Detail.Manage');
$ENABLE_VIEW    = has_permission('Cycletime_Detail.View');
$ENABLE_DELETE  = has_permission('Cycletime_Detail.Delete');

// print_r($get_by);
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>">

<div class="box">
	<div class="box-header">
		<div class="box-tool pull-right">
			<?php if ($ENABLE_ADD) : ?>
				<a class="btn btn-success btn-sm btn-custom" href="<?= base_url('cycletime/add') ?>">Add</a>
			<?php endif; ?>
		</div>
		<div class="box-tool pull-left">
			<br><br>
			<button type='button' id='update_cost' class="btn btn-sm btn-primary btn-custom">Update</button>
			<button type='button' id='excel_report' class="btn btn-sm btn-success btn-custom">Download</button>
			<?php if (isset($get_by[0]['created_date']) && !empty($get_by[0]['created_date'])) : ?>
				<div style='color:red;'><b>Last Update On <u><?= date('d-m-Y H:i:s', strtotime($get_by[0]['created_date'])); ?></u></b></div>
			<?php endif; ?>
			<div id="spinnerx">
				<img src="<?php echo base_url('assets/img/tres_load.gif') ?>"> <span style='color:green; font-size:16px;'><b>Please Wait ...</b></span>
			</div>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped" width='100%'>
			<thead>
				<tr>
					<th>#</th>
					<th>Product Name</th>
					<th>Lot Size</th>
					<th>Cycle Time/Hour</th>
					<th>Last By</th>
					<th>Last Date</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	<!-- /.box-body -->
</div>


<div class="modal modal-default fade" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" style='width:80%; '>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><span class="fa fa-users"></span>&nbsp;Detail Cycletime</h4>
			</div>
			<div class="modal-body" id="ModalView">
				...
			</div>
		</div>
	</div>
	<style>
		.btn-custom {
			min-width: 120px;
		}
	</style>
	<!-- DataTables -->
	<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>

	<!-- page script -->
	<script type="text/javascript">
		$('#spinnerx').hide();
		$(document).on('click', '.detail', function() {
			var id = $(this).data('id_time');
			// alert(id);
			$("#head_title").html("<b>Detail Cycletime</b>");
			$.ajax({
				type: 'POST',
				url: siteurl + 'cycletime/view/' + id,
				data: {
					'id': id
				},
				success: function(data) {
					$("#dialog-popup").modal();
					$("#ModalView").html(data);

				}
			})
		});

		$(document).on('click', '#excel_report', function(e) {
			// loading_spinner();
			e.preventDefault();

			var Link = base_url + thisController + 'excel_report';
			window.open(Link);

		});

		$(document).on('click', '#update_cost', function() {
			new swal({
				title: "Update Cycletime ?",
				text: "Tunggu sampai 'Last Update by ' menunjukan nama user dan update jam sekarang. ",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Ya, Update!",
				cancelButtonText: "Tidak, Batalkan!",
				closeOnConfirm: true,
				closeOnCancel: false
			}).then((hasil) => {
				if (hasil.isConfirmed) {
					// loading_spinner();
					$('#spinnerx').show();
					$.ajax({
						url: siteurl + thisController + '/insert_select_ct',
						type: "POST",
						cache: false,
						dataType: 'json',
						processData: false,
						contentType: false,
						success: function(data) {
							if (data.status == 1) {
								new swal({
									title: "Save Success!",
									text: data.pesan,
									type: "success",
									timer: 7000,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: false
								});
								$('#spinnerx').hide();
								window.location.href = siteurl + thisController;
							} else if (data.status == 0) {
								new swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 7000,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: false
								});
								$('#spinnerx').hide();
							}
						},
						error: function() {
							new swal({
								title: "Error Message !",
								text: 'An Error Occured During Process. Please try again..',
								type: "warning",
								timer: 7000,
								showCancelButton: false,
								showConfirmButton: false,
								allowOutsideClick: false
							});
							$('#spinnerx').hide();
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
			var id = $(this).data('id_time');
			// alert(id);
			new swal({
				title: "Anda Yakin?",
				text: "Data akan di hapus.",
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
						url: siteurl + 'cycletime/delete_cycletime',
						dataType: "json",
						data: {
							'id': id
						},
						success: function(result) {
							if (result.status == '1') {
								new swal({
									title: "Sukses",
									text: "Data berhasil dihapus.",
									type: "success"
								}).then((hasil1) => {
									window.location.reload(true);
								});
							} else {
								new swal({
									title: "Error",
									text: "Data error. Gagal hapus data",
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
					url: siteurl + 'cycletime/data_side_cycletime',
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
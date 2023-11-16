<?php
$ENABLE_ADD     = has_permission('Rate_Machine.Add');
$ENABLE_MANAGE  = has_permission('Rate_Machine.Manage');
$ENABLE_VIEW    = has_permission('Rate_Machine.View');
$ENABLE_DELETE  = has_permission('Rate_Machine.Delete');
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
		<?php if ($ENABLE_VIEW) : ?>
			<a class="btn btn-success btn-sm add" href="javascript:void(0)" title="Add"><i class="fa fa-plus">&nbsp;</i>Add</a>
		<?php endif; ?>

		<span class="pull-right">
		</span>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Code</th>
					<th>Machine Name</th>
					<th>Capacity</th>
					<th>Unit</th>
					<th>Price</th>
					<th>Est Used /Year</th>
					<th>Depresiasi /Month</th>
					<th>Used Hour /Month</th>
					<th>Price Machine /Hour</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>
				<?php if (empty($result)) {
				} else {
					$numb = 0;
					foreach ($result as $record) {
						$numb++;
						$nm_mesin = (!empty($list_asset[$record->kd_mesin]['nm_asset'])) ? $list_asset[$record->kd_mesin]['nm_asset'] : '';
						$nm_satuan = (!empty($list_satuan[$record->id_unit]['code'])) ? $list_satuan[$record->id_unit]['code'] : '';
				?>
						<tr>
							<td><?= $numb; ?></td>
							<td><?= strtoupper($record->kd_mesin) ?></td>
							<td><?= strtoupper($nm_mesin) ?></td>
							<td><?= strtoupper($record->kapasitas) ?></td>
							<td><?= strtoupper($nm_satuan) ?></td>
							<td><?= number_format($record->harga_mesin, 2) ?></td>
							<td><?= number_format($record->est_manfaat, 2) ?></td>
							<td><?= number_format($record->depresiasi_bulan) ?></td>
							<td><?= number_format($record->used_hour_month, 2) ?></td>
							<td><?= number_format($record->biaya_mesin, 2) ?></td>

							<td>

								<?php if ($ENABLE_MANAGE) : ?>
									<a class="btn btn-primary btn-sm edit" href="javascript:void(0)" title="Edit" data-id="<?= $record->id ?>"><i class="fa fa-edit"></i>
									</a>
								<?php endif; ?>

								<?php if ($ENABLE_DELETE) : ?>
									<a class="btn btn-danger btn-sm delete" href="javascript:void(0)" title="Delete" data-id="<?= $record->id ?>"><i class="fa fa-trash"></i>
									</a>
								<?php endif; ?>
							</td>

						</tr>
				<?php }
				}  ?>
			</tbody>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<!-- awal untuk modal dialog -->

<div class="modal modal-default fade" id="dialog-popup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="head_title">Default</h4>
			</div>
			<div class="modal-body" id="ModalView">
				...
			</div>
		</div>
	</div>

	<!-- DataTables -->
	<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>

	<!-- page script -->
	<script type="text/javascript">
		$(document).on('click', '.edit', function(e) {
			var id = $(this).data('id');
			$("#head_title").html("<b>Machine Rate</b>");
			$.ajax({
				type: 'POST',
				url: siteurl + thisController + '/add/' + id,
				success: function(data) {
					$("#dialog-popup").modal();
					$("#ModalView").html(data);

				}
			})
		});

		$(document).on('click', '.add', function() {
			$("#head_title").html("<b>Machine Rate</b>");
			$.ajax({
				type: 'POST',
				url: siteurl + thisController + '/add/',
				success: function(data) {
					$("#dialog-popup").modal();
					$("#ModalView").html(data);

				}
			})
		});

		$(document).on('keyup', '.getDep', function() {
			get_depresiasi();
		});


		$(document).on('submit', '#data_form', function(e) {
			e.preventDefault()

			var kd_mesin = $('#kd_mesin').val();

			if (kd_mesin == '0') {
				new swal({
					title: "Error Message!",
					text: 'Machine not selected...',
					type: "warning"
				});
				return false;
			}
			// alert(data);

			new swal({
				title: "Anda Yakin?",
				text: "Data akan diproses!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-info",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				closeOnConfirm: false
			}).then((hasil) => {
				if (hasil.isConfirmed) {
					var form_data = new FormData($('#data_form')[0]);
					$.ajax({
						type: 'POST',
						url: siteurl + thisController + 'add',
						dataType: "json",
						data: form_data,
						processData: false,
						contentType: false,
						success: function(data) {
							if (data.status == '1') {
								new swal({
									title: "Sukses",
									text: data.pesan,
									type: "success"
								}).then((hasil1) => {
									if (hasil1.isConfirmed) {
										window.location.reload(true);
									}
								});
							} else {
								new swal({
									title: "Error",
									text: data.pesan,
									type: "error"
								})

							}
						},
						error: function() {
							new swal({
								title: "Error",
								text: "Error proccess !",
								type: "error"
							})
						}
					});
				}
			});

		})


		// DELETE DATA
		$(document).on('click', '.delete', function(e) {
			e.preventDefault()
			var id = $(this).data('id');
			// alert(id);
			new swal({
				title: "Anda Yakin?",
				text: "Data akan di hapus!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-info",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				closeOnConfirm: false
			}).then((hasil) => {
				$.ajax({
					type: 'POST',
					url: siteurl + thisController + '/delete',
					dataType: "json",
					data: {
						'id': id
					},
					success: function(data) {
						if (data.status == '1') {
							new swal({
								title: "Sukses",
								text: data.pesan,
								type: "success"
							}).then((hasil1) => {
								if (hasil1.isConfirmed) {
									window.location.reload(true);
								}
							});
						} else {
							new swal({
								title: "Error",
								text: data.pesan,
								type: "error"
							})

						}
					},
					error: function() {
						new swal({
							title: "Error",
							text: "Error proccess !",
							type: "error"
						})
					}
				});
			});

		})

		$(function() {
			var table = $('#example1').DataTable({
				orderCellsTop: true,
				fixedHeader: true
			});
			$("#form-area").hide();
		});

		function get_depresiasi() {
			var harga_mesin = getNum($('#harga_mesin').val().split(",").join(""));
			var est_manfaat = getNum($('#est_manfaat').val().split(",").join(""));
			var used_hour_month = getNum($('#used_hour_month').val().split(",").join(""));
			var depresiasi = harga_mesin / (est_manfaat * 12);
			var biaya_mesin = depresiasi / used_hour_month;

			$('#depresiasi_bulan').val(depresiasi.toFixed(2));
			$('#biaya_mesin').val(biaya_mesin.toFixed(2));
		}
	</script>
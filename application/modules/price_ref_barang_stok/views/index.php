<?php
$ENABLE_ADD     = has_permission('Prf_Barang_Stok.Add');
$ENABLE_MANAGE  = has_permission('Prf_Barang_Stok.Manage');
$ENABLE_VIEW    = has_permission('Prf_Barang_Stok.View');
$ENABLE_DELETE  = has_permission('Prf_Barang_Stok.Delete');
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
		<span class="pull-right">
		</span>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Stok Code</th>
					<th>Stok Master</th>
					<th>Trade Name</th>
					<th>Price Ref</th>
					<th>Expired</th>
					<!-- <th>Price Pur</th>
			<th>Expired</th> -->
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>
				<?php if (empty($result)) {
				} else {
					$numb = 0;
					foreach ($result as $record) {
						$numb++;
						$date_now		= date('Y-m-d');

						$status = 'Not Set';
						$status_ = 'yellow';
						$status2 = '';

						$expired = '-';
						$expired_new = '-';
						if (!empty($record->price_ref_date_use)) {
							$price_ref_date 	= date('Y-m-d', strtotime('+' . $record->price_ref_expired_use . ' month', strtotime($record->price_ref_date_use)));
							$expired = date('d-M-Y', strtotime($price_ref_date));
							if ($date_now > $price_ref_date) {
								$status = 'Expired';
								$status_ = 'red';
							} else {
								$status = 'Oke';
								$status_ = 'green';
							}
						}
						if (!empty($record->price_ref_date)) {
							$price_ref_date 	= date('Y-m-d', strtotime('+' . $record->price_ref_expired . ' month', strtotime($record->price_ref_date)));
							$expired_new = date('d-M-Y', strtotime($price_ref_date));
						}
						if ($record->status_app == 'Y') {
							$status2 = 'Waiting Approve';
							$status2_ = 'purple';
						}
				?>
						<tr>
							<td><?= $numb; ?></td>
							<td><?= strtoupper($record->item_code) ?></td>
							<td><?= strtoupper($record->nm_barang_stok) ?></td>
							<td><?= strtoupper($record->trade_name) ?></td>
							<td align='right'><?= number_format($record->price_ref_use_idr, 2) ?></td>
							<td align='center'><?= $expired; ?></td>

							<!-- <td align='right'><?= number_format($record->price_ref, 2) ?></td>
			<td align='center'><?= $expired_new; ?></td> -->

							<td><span class='badge bg-<?= $status_; ?>'><?= $status; ?></span><br><span class='badge bg-<?= $status2_; ?>'><?= $status2; ?></span></td>


							<td align='left'>

								<?php if ($ENABLE_MANAGE and $record->status_app == 'Y') : ?>
									<a class="btn btn-success btn-sm edit" href="javascript:void(0)" title="Approve" data-id="<?= $record->id_barang_stok ?>"><i class="fa fa-check"></i></a>
								<?php endif; ?>

								<?php if (!empty($record->upload_file)) : ?>
									<a class="btn btn-warning btn-sm" href="<?= base_url($record->upload_file); ?>" target='_blank' title="Download"><i class="fa fa-download"></i></a>
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
	<div class="modal-dialog modal-lg mx-wd-md-90p-force mx-wd-lg-90p-force">
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
			$("#head_title").html("<b>Stok Master</b>");
			$.ajax({
				type: 'POST',
				url: siteurl + thisController + '/add/' + id,
				success: function(data) {
					$("#dialog-popup").modal();
					$("#ModalView").html(data);

				}
			})
		});


		$(document).on('submit', '#data_form', function(e) {
			e.preventDefault()

			var price_ref_expired_use_after = $('#price_ref_expired_use_after').val();
			var action_app = $('#action_app').val();

			if (price_ref_expired_use_after == '0' && action_app == '1') {
				new swal({
					title: "Error Message!",
					text: 'Expired not selected...',
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

		$(function() {
			var table = $('#example1').DataTable({
				orderCellsTop: true,
				fixedHeader: true
			});
			$("#form-area").hide();
		});
	</script>
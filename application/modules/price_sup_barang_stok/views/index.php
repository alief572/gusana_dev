<?php
$ENABLE_ADD     = has_permission('Sup_Barang_Stok.Add');
$ENABLE_MANAGE  = has_permission('Sup_Barang_Stok.Manage');
$ENABLE_VIEW    = has_permission('Sup_Barang_Stok.View');
$ENABLE_DELETE  = has_permission('Sup_Barang_Stok.Delete');
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
					<th>Lower Price<br>Before</th>
					<th>Lower Price<br>After</th>
					<th>Higher Price<br>Before</th>
					<th>Higher Price<br>After</th>
					<th>Expired<br>Before</th>
					<th>Expired<br>After</th>
					<th>Status</th>
					<th>Alasan Reject</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>
				<?php if (empty($result)) {
				} else {
					$numb = 0;
					foreach ($result as $record) {
						$numb++;
						$tgl_create 	= $record->price_ref_new_date;
						$max_exp 		= $record->price_ref_new_expired;
						$tgl_expired 	= date('Y-m-d', strtotime('+' . $max_exp . ' month', strtotime($tgl_create)));
						$date_now		= date('Y-m-d');

						$status = 'Not Set';
						$status_ = 'yellow';
						$status2 = '';

						$expired = '-';
						$expired_new = '-';
						if (!empty($record->price_ref_date)) {
							$price_ref_date 	= date('Y-m-d', strtotime('+' . $record->price_ref_expired . ' month', strtotime($record->price_ref_date)));
							$expired = date('d-M-Y', strtotime($price_ref_date));
							if ($date_now > $price_ref_date) {
								$status = 'Expired';
								$status_ = 'red';
							} else {
								$status = 'Oke';
								$status_ = 'green';
							}
						}
						if ($record->status_app == 'Y') {
							$expired_new = date('d-M-Y', strtotime($tgl_expired));
							$status2 = 'Waiting Approve';
							$status2_ = 'purple';
						}
				?>
						<tr>
							<td><?= $numb; ?></td>
							<td><?= strtoupper($record->item_code) ?></td>
							<td><?= strtoupper($record->nm_barang_stok) ?></td>
							<td><?= strtoupper($record->trade_name) ?></td>
							<td align='right'><?= number_format($record->price_ref_idr, 2) ?></td>
							<td align='right'><?= number_format($record->price_ref_new_idr, 2) ?></td>
							<td align='right'><?= number_format($record->price_ref_high_idr, 2) ?></td>
							<td align='right'><?= number_format($record->price_ref_high_new_idr, 2) ?></td>

							<td align='center'><?= $expired; ?></td>
							<td align='center'><?= $expired_new; ?></td>
							<td><span class='badge bg-<?= $status_; ?>'><?= $status; ?></span><br><span class='badge bg-<?= $status2_; ?>'><?= $status2; ?></span></td>
							<td><?= strtoupper($record->status_reject) ?></td>


							<td align='left'>

								<?php if ($ENABLE_MANAGE) : ?>
									<a class="btn btn-primary btn-sm edit" href="javascript:void(0)" title="Edit" data-id="<?= $record->id_barang_stok ?>"><i class="fa fa-edit"></i></a>
								<?php endif; ?>

								<?php if (!empty($record->upload_file)) : ?>
									<a class="btn btn-success btn-sm" href="<?= base_url($record->upload_file); ?>" target='_blank' title="Download"><i class="fa fa-download"></i></a>
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
	<div class="modal-dialog  modal-lg mx-wd-md-90p-force mx-wd-lg-90p-force">
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
			$("#head_title").html("<b>Barang Stok</b>");
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

			var price_ref_expired = $('#price_ref_expired').val();

			if (price_ref_expired == '0') {
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
					var form_data = new FormData($(this)[0]);
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
								}).then((hasil) => {
									if (hasil.isConfirmed) {
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
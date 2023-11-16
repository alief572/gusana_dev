<?php
$ENABLE_ADD     = has_permission('Master_Shift.Add');
$ENABLE_MANAGE  = has_permission('Master_Shift.Manage');
$ENABLE_VIEW    = has_permission('Master_Shift.View');
$ENABLE_DELETE  = has_permission('Master_Shift.Delete');

?>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>">

<div class="box box-solid">
	<!-- /.box-header -->
	<div class="box-body">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<?php if (empty($results)) {
					} else {
						$numb = 0;
						foreach ($results as $record) {
							$numb++; ?>
							<legend>
								<h3 style="margin:0px !Important"><label>ID Inventory :<?= $record['id_shift'] ?></label></h3>
							</legend>
							<legend>
								<h3 style="margin:0px !Important"><label>Nama Iventory :<?= $result['nm_inventory1'] ?></label></h3>
							</legend>
					<?php }
					}  ?>
					<div>
					</div>
				</div>
			</div>
			<!-- /.box-body -->
		</div>

		<!-- DataTables -->
		<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
		<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>

		<!-- page script -->
		<script type="text/javascript">
			$(function() {
				$("#example1").DataTable();
				$("#form-area").hide();
			});

			function add_data() {
				var url = 'customer/create/';
				$(".box").hide();
				$("#form-area").show();
				$("#form-area").load(siteurl + url);
				$("#title").focus();
			}

			function edit_data(id_customer) {
				if (id_customer != "") {
					var url = 'customer/edit/' + id_customer;
					$(".box").hide();
					$("#form-area").show();
					$("#form-area").load(siteurl + url);
					$("#title").focus();
				}
			}

			//Delete
			function delete_data(id) {
				//alert(id);
				new swal({
					title: "Anda Yakin?",
					text: "Data Akan Terhapus secara Permanen!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Ya, delete!",
					cancelButtonText: "Tidak!",
					closeOnConfirm: false,
					closeOnCancel: true
				}).then((hasil) => {
					if (hasil.isConfirmed) {
						$.ajax({
							url: siteurl + 'customer/hapus_customer/' + id,
							dataType: "json",
							type: 'POST',
							success: function(msg) {
								if (msg['delete'] == '1') {
									$("#dataku" + id).hide(2000);
									//new swal("Terhapus!", "Data berhasil dihapus.", "success");
									new swal({
										title: "Terhapus!",
										text: "Data berhasil dihapus",
										type: "success",
										timer: 1500,
										showConfirmButton: false
									});
								} else {
									new swal({
										title: "Gagal!",
										text: "Data gagal dihapus",
										type: "error",
										timer: 1500,
										showConfirmButton: false
									});
								};
							},
							error: function() {
								new swal({
									title: "Gagal!",
									text: "Gagal Eksekusi Ajax",
									type: "error",
									timer: 1500,
									showConfirmButton: false
								});
							}
						});
					} else {
						//cancel();
					}
				});
			}

			function PreviewPdf(id) {
				param = id;
				tujuan = 'customer/print_request/' + param;

				$(".modal-body").html('<iframe src="' + tujuan + '" frameborder="no" width="570" height="400"></iframe>');
			}
		</script>
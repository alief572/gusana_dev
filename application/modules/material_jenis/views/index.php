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
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th width="5">#</th>
					<th>Material Type</th>
					<th>Material Category</th>
					<th>Material Jenis</th>
					<th>Status</th>
					<?php if ($ENABLE_MANAGE) : ?>
						<th>Action</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($results)) {
				} else {
					$numb = 0;
					foreach ($results as $record) {
						$numb++; ?>
						<tr>
							<td><?= $numb; ?></td>
							<td><?= $record->nama_type ?></td>
							<td><?= $record->nama_category1 ?></td>
							<td><?= $record->nama ?></td>
							<td>
								<?php if ($record->aktif == 'aktif') { ?>
									<label class="badge badge-success">Aktif</label>
								<?php } else { ?>
									<label class="badge badge-danger">Non Aktif</label>
								<?php } ?>
							</td>
							<td style="padding-left:20px">
								<?php if ($ENABLE_VIEW) : ?>
									<a class="btn btn-primary btn-sm view" href="javascript:void(0)" title="View" data-id_inventory3="<?= $record->id_category2 ?>"><i class="fa fa-eye"></i>
									</a>
								<?php endif; ?>

								<?php if ($ENABLE_MANAGE) : ?>
									<a class="btn btn-success btn-sm edit" href="javascript:void(0)" title="Edit" data-id_inventory3="<?= $record->id_category2 ?>"><i class="fa fa-edit"></i>
									</a>
								<?php endif; ?>

								<?php if ($ENABLE_DELETE) : ?>
									<a class="btn btn-danger btn-sm delete" href="javascript:void(0)" title="Delete" data-id_inventory3="<?= $record->id_category2 ?>"><i class="fa fa-trash"></i>
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
<!-- Modal -->
<div class="modal modal-primary" id="dialog-rekap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data Material Jenis</h4>
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

<div class="modal modal-default fade" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<form id="data_form" method="post">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><span class="fa fa-users"></span>&nbsp;Data Material Jenis</h4>
				</div>
				<div class="modal-body" id="ModalView">
					...
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" name="save" id="save"><i class="fa fa-save"></i> Save</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">
						<span class="glyphicon glyphicon-remove"></span> Close</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>

<!-- page script -->
<script type="text/javascript">
	$(document).on('click', '.edit', function(e) {
		var id = $(this).data('id_inventory3');
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Edit Inventory</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'material_jenis/editInventory/' + id,
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);

			}
		})
	});

	$(document).on('click', '.view', function() {
		var id = $(this).data('id_inventory3');
		// alert(id);
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Detail Inventory</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'material_jenis/viewInventory/',
			data: {
				'id': id
			},
			success: function(data) {
				$("#dialog-rekap").modal();
				$("#MyModalBody").html(data);

			}
		})
	});
	$(document).on('click', '.add', function() {
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Tambah Inventory</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'material_jenis/addInventory',
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);

			}
		})
	});


	// DELETE DATA
	$(document).on('click', '.delete', function(e) {
		e.preventDefault()
		var id = $(this).data('id_inventory3');
		// alert(id);
		new swal({
			title: "Anda Yakin?",
			text: "Data Inventory akan di hapus.",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-info",
			confirmButtonText: "Ya, Hapus!",
			cancelButtonText: "Batal",
			closeOnConfirm: false
		}).then((hasil) => {
			$.ajax({
				type: 'POST',
				url: siteurl + 'material_jenis/deleteInventory',
				dataType: "json",
				data: {
					'id': id
				},
				success: function(result) {
					if (result.status == '1') {
						new swal({
							title: "Sukses",
							text: "Data Inventory berhasil dihapus.",
							type: "success"
						}).then((hasil2) => {
							window.location.reload(true);
						});
					} else {
						new swal({
							title: "Error",
							text: "Data error. Gagal hapus data",
							type: "error"
						});

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
		});

	});

	$(document).on('submit', '#data_form', function(e) {
		e.preventDefault();
		var data = new FormData($(this)[0]);
		// alert(data);

		new swal({
			title: "Anda Yakin?",
			text: "Data Inventory akan di simpan.",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-info",
			confirmButtonText: "Ya, Simpan!",
			cancelButtonText: "Batal",
			closeOnConfirm: false
		}).then((hasil) => {
			if (hasil.isConfirmed) {
				var formData = new FormData($(this)[0]);
				$.ajax({
					url: baseurl + 'material_jenis/saveNewinventory',
					type: "POST",
					data: formData,
					cache: false,
					dataType: 'json',
					processData: false,
					contentType: false,
					success: function(result) {
						if (result.status == '1') {
							new swal({
								title: "Sukses",
								text: "Data Inventory berhasil disimpan.",
								type: "success"
							}).then((hasil2) => {
								window.location.reload(true);
							});
						} else {
							new swal({
								title: "Error",
								text: "Data error. Gagal insert data",
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
		// $('#example1 thead tr').clone(true).appendTo( '#example1 thead' );
		// $('#example1 thead tr:eq(1) th').each( function (i) {
		// var title = $(this).text();
		//alert(title);
		// if (title == "#" || title =="Action" ) {
		// $(this).html( '' );
		// }else{
		// $(this).html( '<input type="text" />' );
		// }

		// $( 'input', this ).on( 'keyup change', function () {
		// if ( table.column(i).search() !== this.value ) {
		// table
		// .column(i)
		// .search( this.value )
		// .draw();
		// }else{
		// table
		// .column(i)
		// .search( this.value )
		// .draw();
		// }
		// } );
		// } );

		var table = $('#example1').DataTable({
			orderCellsTop: true,
			fixedHeader: true
		});
		$("#form-area").hide();
	});


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
</script>
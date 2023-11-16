<?php
$ENABLE_ADD     = has_permission('Level_3.Add');
$ENABLE_MANAGE  = has_permission('Level_3.Manage');
$ENABLE_VIEW    = has_permission('Level_3.View');
$ENABLE_DELETE  = has_permission('Level_3.Delete');

?>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>">

<div class="box box-solid">
	<!-- /.box-header -->
	<div class="box-body">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<table class="table">
						<tr>
							<th>ID Product Jenis</th>
							<th>:</th>
							<th><?= $result['cust']->id_category2; ?></th>
						</tr>
						<tr>
							<th>Product Type</th>
							<th>:</th>
							<th><?= $result['cust']->nama_inventory; ?></th>
						</tr>
						<tr>
							<th>Product Category</th>
							<th>:</th>
							<th><?= $result['cust']->nama_material; ?></th>
						</tr>
						<tr>
							<th>Product Jenis</th>
							<th>:</th>
							<th><?= $result['cust']->nama; ?></th>
						</tr>
						<tr>
							<th>Aktif / Non Aktif</th>
							<th>:</th>
							<th><?= $result['status']; ?></th>
						</tr>
					</table>
				</div>
			</div>
			<!-- /.box-body -->
		</div>
	</div>
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


	function PreviewPdf(id) {
		param = id;
		tujuan = 'customer/print_request/' + param;

		$(".modal-body").html('<iframe src="' + tujuan + '" frameborder="no" width="570" height="400"></iframe>');
	}
</script>
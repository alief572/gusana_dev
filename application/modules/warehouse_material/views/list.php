<?php
$ENABLE_ADD     = has_permission('Stock_Material.Add');
$ENABLE_MANAGE  = has_permission('Stock_Material.Manage');
$ENABLE_VIEW    = has_permission('Stock_Material.View');
$ENABLE_DELETE  = has_permission('Stock_Material.Delete');
$id_bentuk = $this->uri->segment(3);
?>
<style type="text/css">
	thead input {
		width: 100%;
	}
</style>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<div class="box">
	<div class="box-header" style="padding: 10px;">


		<span class="pull-right">
		</span>
	</div>
	<!-- /.box-header -->
	<!-- /.box-header -->
	<div class="box-body" style="padding: 10px;">
		<table id="dataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th class="text-center">No</th>
					<th class="text-center">Code</th>
					<th class="text-center" style="width: 200px;">Material</th>
					<th class="text-center">Packing</th>
					<th class="text-center">Conversion</th>
					<th class="text-center">Unit</th>
					<th class="text-center">Stock Unit</th>
					<th class="text-center">History</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 1;
				foreach ($results['list_material_stock'] as $material_stock) :


					echo '
						<tr>
							<td class="text-center">' . $no . '</td>
							<td class="text-center">' . $material_stock->category_nm . '</td>
							<td class="text-center">' . $material_stock->nama . '</td>
							<td class="text-center">' . $material_stock->nm_packaging . '</td>
							<td class="text-center">' . number_format($material_stock->konversi) . '</td>
							<td class="text-center">' . $material_stock->nm_unit . '</td>
							<td class="text-center">' . number_format($material_stock->stock_unit, 2) . '</td>
							<td class="text-center">
								<button type="button" class="btn btn-sm btn-info history_material history_material_' . $material_stock->id_category1 . '" data-id_category1="' . $material_stock->id_category1 . '"><i class="fa fa-eye"></i></button>
							</td>
						</tr>
					';
					$no++;
				endforeach;
				?>
			</tbody>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<!-- awal untuk modal dialog -->
<!-- Modal -->


<div class="modal  fade" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg mx-wd-md-70p-force mx-wd-lg-70p-force">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><span class="fa fa-users"></span>&nbsp;Data Inventory</h4>
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

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>

<!-- page script -->
<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

	$(document).ready(function() {
		loadData();
	});

	$(document).on('click', '.history_material', function() {
		var id_category1 = $(this).data('id_category1');

		$.ajax({
			type: "POST",
			url: siteurl + active_controller + '/history_material',
			data: {
				'id_category1': id_category1
			},
			cache: false,
			beforeSend: function(result) {
				$('.history_material_' + id_category1).html('<i class="fa fa-spin fa-spinner"></i>');
			},
			success: function(result) {
				$('#myModalLabel').html('Data Stock Material');
				$('#ModalView').html(result);
				$('#dialog-popup').modal();

				$('.history_material_' + id_category1).html('<i class="fa fa-eye"></i>');
			},
			error: function(result) {
				$('.history_material_' + id_category1).html('<i class="fa fa-eye"></i>');
			}
		});
	});

	// function loadData() {
	// 	var oTable = $('#dataTable').DataTable({
	// 		"processing": true,
	// 		"serverSide": true,
	// 		"stateSave": true,
	// 		"bAutoWidth": true,
	// 		"destroy": true,
	// 		"responsive": true,
	// 		"language": {
	// 			"sSearch": "",
	// 			'searchPlaceholder': 'Search...',
	// 			"sLengthMenu": "Display _MENU_",
	// 			"sInfo": "Display <b>_START_</b> to <b>_END_</b> from <b>_TOTAL_</b> data",
	// 			"sInfoFiltered": "(filtered from _MAX_ total entries)",
	// 			"sZeroRecords": "<i>Data tidak tersedia</i>",
	// 			"sEmptyTable": "<i>Data tidak ditemukan</i>",
	// 			"oPaginate": {
	// 				"sPrevious": "<i class='fa fa-arrow-left' aria-hidden='true'></i>",
	// 				"sNext": "<i class='fa fa-arrow-right' aria-hidden='true'></i>"
	// 			}
	// 		},
	// 		"responsive": {
	// 			"breakpoints": [{
	// 					"name": 'desktop',
	// 					"width": Infinity
	// 				},
	// 				{
	// 					"name": 'tablet',
	// 					"width": 1148
	// 				},
	// 				{
	// 					"name": 'mobile',
	// 					"width": 680
	// 				},
	// 				{
	// 					"name": 'mobile-p',
	// 					"width": 320
	// 				}
	// 			],
	// 		},
	// 		"aaSorting": [
	// 			[1, "asc"]
	// 		],
	// 		"columnDefs": [{
	// 				"targets": 'no-sort',
	// 				"orderable": false,
	// 			}, {
	// 				"targets": 'text-center',
	// 				"className": 'text-center',
	// 			}, {
	// 				"targets": 'tx-bold tx-dark',
	// 				"className": 'tx-bold tx-dark',
	// 			}, {
	// 				"targets": 'tx-bold tx-dark text-center',
	// 				"className": 'tx-bold tx-dark text-center',
	// 			}

	// 		],
	// 		"sPaginationType": "simple_numbers",
	// 		"iDisplayLength": 10,
	// 		"aLengthMenu": [5, 10, 20, 50, 100, 150],
	// 		"ajax": {
	// 			url: siteurl + thisController + 'getData',
	// 			type: "post",
	// 			data: function(d) {
	// 				d.status = 'D'
	// 			},
	// 			cache: false,
	// 			error: function() {
	// 				$(".my-grid-error").html("");
	// 				$("#my-grid").append(
	// 					'<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
	// 				);
	// 				$("#my-grid_processing").css("display", "none");
	// 			}
	// 		}
	// 	});
	// }
	function loadData() {
		var oTable = $('#dataTable').DataTable();
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
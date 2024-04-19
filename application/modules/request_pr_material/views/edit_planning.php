<div class="box box-primary">
	<div class="box-body">
		<form id="data-form" method="post" autocomplete="off"><br>
			<input type="hidden" name='so_number' id='so_number' value='<?= $header[0]['so_number']; ?>'>
			<div class="form-group row">
				<div class="col-md-12">
					<table class='table' width='70%'>
						<tr>
							<td width='20%'>No Request / SO</td>
							<td width='1%'>:</td>
							<td width='29%'><?= $header[0]['so_number']; ?></td>
							<td width='20%'></td>
							<td width='1%'></td>
							<td width='29%'></td>
						</tr>
						<tr>
							<td>No. PR</td>
							<td>:</td>
							<td><?= $header[0]['no_pr']; ?></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Customer</td>
							<td>:</td>
							<td>PT. GUNUNG SAGARA BUANA</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<?php
						$tgl_dibutuhkan = (!empty($header[0]['tgl_dibutuhkan'])) ? date('d F Y', strtotime($header[0]['tgl_dibutuhkan'])) : '';
						?>
						<tr>
							<td>Tgl Dibutuhkan</td>
							<td>:</td>
							<td><input type="date" class="form-control form-control-sm" name="tgl_dibutuhkan" value="<?= $header[0]['tgl_dibutuhkan'] ?>"></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</table>
				</div>
				<div class="col-md-12">
					<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead class='thead'>
							<tr class='bg-blue'>
								<th class='text-center th'>#</th>
								<th class='text-center th'>Material Name</th>
								<!-- <th class='text-center th'>Estimasi (Kg)</th>
							<th class='text-center th'>Stock Free (Kg)</th>
							<th class='text-center th'>Use Stock (Kg)</th>
							<th class='text-center th'>Sisa Stock Free (Kg)</th> -->
								<th class='text-center th'>Min Stock</th>
								<th class='text-center th'>Max Stock</th>
								<th class='text-center th'>Min Order</th>
								<th class='text-center th'>Qty PR</th>
								<th class='text-center th'>#</th>
								<th class='text-center th'>Notes</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($detail as $key => $value) {
								$key++;
								$nm_material 	= $value['nama'];
								$stock_free 	= $value['stock_free'];
								$use_stock 		= $value['use_stock'];
								$sisa_free 		= $stock_free - $use_stock;
								$propose 		= $value['propose_purchase'];

								if ($propose > 0) {
									echo "<tr>";
									echo "<td class='text-center'>" . $key . "</td>";
									echo "	<td class='text-left'>" . $nm_material . "
										
										</td>";
									// echo "<td class='text-right qty_order'>".number_format($value['qty_order'],5)."</td>";
									// echo "<td class='text-right stock_free'>".number_format($stock_free,5)."</td>";
									// echo "<td class='text-right stock_free'>".number_format($use_stock,5)."</td>";
									// echo "<td class='text-right sisa_free'>".number_format($sisa_free,5)."</td>";
									echo "<td class='text-right min_stok'>" . number_format($value['min_stok'], 2) . "</td>";
									echo "<td class='text-right max_stok'>" . number_format($value['max_stok'], 2) . "</td>";
									echo "<td class='text-right min_order'>" . number_format(0, 2) . "</td>";
									// echo "<td class='text-right'>" . number_format($propose, 2) . "</td>";
									if ($value['status_app'] == 'N') {
										echo "<td align='center'>";
										echo "<input type='hidden' name='detail[" . $key . "][id]' value='" . $value['id'] . "'>";
										echo "<input type='text' name='detail[" . $key . "][qty]' class='form-control input-sm text-center autoNumeric2' style='width:100px;' value='" . $propose . "'>";
										echo "</td>";
										echo "<td class='text-center'><span class='badge badge-primary text-bold'>Waiting Process</span></td>";
									}
									if ($value['status_app'] == 'Y') {
										echo "<td class='text-center'>" . number_format($propose, 2) . "</td>";
										echo "<td class='text-center'><span class='badge badge-success text-bold'>Approved</span></td>";
									}
									if ($value['status_app'] == 'D') {
										echo "<td class='text-center'>" . number_format($propose, 2) . "</td>";
										echo "<td class='text-center'><span class='badge badge-danger text-bold'>Rejected</span></td>";
									}
									echo "<td class=''><input type='text' name='detail[".$key."][note]' class='form-control' value='".$value['note']."'></td>";
									echo "</tr>";
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-12">
					<button type="button" class="btn btn-primary" name="save" id="save">Update</button>
					<button type="button" class="btn btn-danger" style='margin-left:5px;' name="back" id="back">Back</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal modal-default fade" id="dialog-popup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" style='width:70%;'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><span class="fa fa-users"></span>&nbsp;Detail Data</h4>
			</div>
			<div class="modal-body" id="ModalView">
				...
			</div>
		</div>
	</div>


	<script src="<?= base_url('assets/js/jquery.maskMoney.js') ?>"></script>
	<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>

	<script type="text/javascript">
		var base_url = '<?php echo base_url(); ?>';
		var thisController = '<?php echo ($this->uri->segment(1)); ?>';

		$(document).ready(function() {
			$('.datepicker').datepicker({
				dateFormat: 'dd-M-yy'
			});
			$('.autoNumeric5').autoNumeric('init', {
				mDec: '5',
				aPad: false
			})
			$('.autoNumeric2').autoNumeric('init', {
				mDec: '2',
				aPad: false
			})
			$('.chosen-select').select2()

			//back
			$(document).on('click', '#back', function() {
				window.location.href = base_url + thisController
			});

			$('#save').click(function(e) {
				e.preventDefault();

				swal.fire({
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
							var formData = new FormData($('#data-form')[0]);
							var baseurl = siteurl + thisController + '/process_update_all';
							$.ajax({
								url: baseurl,
								type: "POST",
								data: formData,
								cache: false,
								dataType: 'json',
								processData: false,
								contentType: false,
								success: function(data) {
									if (data.status == 1) {
										swal.fire({
											title: "Save Success!",
											text: data.pesan,
											type: "success",
											timer: 7000
										});
										window.location.href = base_url + thisController
									} else {
										swal.fire({
											title: "Save Failed!",
											text: data.pesan,
											type: "warning",
											timer: 7000
										});
									}
								},
								error: function() {

									swal.fire({
										title: "Error Message !",
										text: 'An Error Occured During Process. Please try again..',
										type: "warning",
										timer: 7000
									});
								}
							});
						} else {
							swal.fire("Cancelled", "Data can be process again :)", "error");
							return false;
						}
					});
			});
		});
	</script>
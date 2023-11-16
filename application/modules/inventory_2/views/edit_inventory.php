<?php
$ENABLE_ADD     = has_permission('Level_2.Add');
$ENABLE_MANAGE  = has_permission('Level_2.Manage');
$ENABLE_VIEW    = has_permission('Level_2.View');
$ENABLE_DELETE  = has_permission('Level_2.Delete');
foreach ($results['inven'] as $inven) {
}
?>
<div class="box box-primary">
	<div class="box-body">
		<form id="data-form" method="post">
			<div class="col-sm-12">
				<div class="input_fields_wrap2">

					<div class="row">
						<div class="form-group row">
							<div class="col-md-4">
								<label for="customer">Inventory Type</label>
							</div>
							<div class="col-md-6">
								<select id="inventory_1" name="hd1[1][inventory_1]" class="form-control select" required>
									<option value="">-- Inventory --</option>
									<?php foreach ($results['lvl1'] as $lvl1) {
										$select = $inven->id_type == $lvl1->id_type ? 'selected' : '';
									?>
										<option value="<?= $lvl1->id_type ?>" <?= $select ?>><?= $lvl1->nama ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-4">
								<label for="customer">Inventory Name</label>
							</div>
							<div class="col-md-6" hidden>
								<input type="text" class="form-control" id="" required name="hd1[1][id_inventory]" value="<?= $inven->id_category1 ?>" placeholder="Nama Inventory">
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control" id="" required name="hd1[1][nm_inventory]" value="<?= $inven->nama ?>" placeholder="Nama Inventory">
							</div>
						</div>

						<div class="col-xs-2">
							&nbsp;
						</div>
					</div>
					<div class="col-sm-12">
						<b>Komposisi</b>
						<div class='box-tool pull-right'>
							<?php
							echo form_button(array('type' => 'button', 'class' => 'btn btn-md btn-success', 'value' => 'back', 'content' => 'Add', 'id' => 'add-payment'));
							?>
						</div>
						<table class='table table-bordered table-striped'>
							<thead>
								<tr class='bg-blue'>
									<td align='center' hidden><b>Id Compotition</b></td>
									<td align='center'><b>Name Compotition</b></td>
									<td align='center'><b>Action</b></td>
								</tr>

							</thead>
							<tbody id='old_payment'>
								<?php
								$numb = 0;
								foreach ($results['komposisi'] as $komposisi) {
									$numb++; ?>
									<tr id="tr_'<?= $numb ?>'">
										<td align="left" hidden>
											<input type="text" class="form-control input-sm" name="data2['<?= $numb ?>'][id_compotition]" value="<?= $komposisi->id_compotition ?>" id="data1_'<?= $numb ?>'_id_compotition" label="FALSE" div="FALSE">
										</td>
										<td align="left">
											<input type="text" class="form-control input-sm" name="data2['<?= $numb ?>'][name_compotition]" value="<?= $komposisi->name_compotition ?>" id="data1_'<?= $numb ?>'_name_compotition" label="FALSE" div="FALSE">
										</td>
										<td align="center">
											<a class="btn btn-danger btn-sm delete" href="javascript:void(0)" title="Delete" data-id_compotition="<?= $komposisi->id_compotition ?>">Delete</i>
											</a>
										</td>
									</tr>
								<?php
								}
								?>
							</tbody>
							<tbody id='list_payment'>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
			</div>
	</div>
</div>


<hr>
<center>
	<!--<button type="submit" class="btn btn-primary btn-sm add_field_button2" name="save"><i class="fa fa-plus"></i>Add Main Produk</button>
					--><button type="submit" class="btn btn-success btn-sm" name="save" id="simpan-com"><i class="fa fa-save"></i>Simpan</button>
</center>

</form>



</div>
</div>




<script type="text/javascript">
	//$('#input-kendaraan').hide();
	var base_url = '<?php echo base_url(); ?>';
	var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

	$(document).ready(function() {
		var data_pay = <?php echo json_encode($results['supplier']); ?>;

		///INPUT PERKIRAAN KIRIM


		var max_fields2 = 10; //maximum input boxes allowed
		var wrapper2 = $(".input_fields_wrap2"); //Fields wrapper
		var add_button2 = $(".add_field_button2"); //Add button ID

		//console.log(persen);

		var x2 = 1; //initlal text box count
		$(add_button2).click(function(e) { //on add input button click
			e.preventDefault();
			if (x2 < max_fields2) { //max input box allowed
				x2++; //text box increment

				$(wrapper2).append('<div class="row">' +
					'<div class="col-xs-1">' + x2 + '</div>' +
					'<div class="col-xs-3">' +
					'<div class="input-group">' +
					'<input type="text" name="hd' + x2 + '[produk]"  class="form-control input-sm" value="">' +
					'</div>' +
					'<div class="input-group">' +
					'<input type="text" name="hd' + x2 + '[costcenter]"  class="form-control input-sm" value="">' +
					'</div>' +
					'<div class="input-group">' +
					'<input type="text" name="hd' + x2 + '[mesin]"  class="form-control input-sm" value="">' +
					'</div>' +
					'<div class="input-group">' +
					'<input type="text" name="hd' + x2 + '[mold_tools]"  class="form-control input-sm" value="">' +
					'</div>' +
					'</div>' +
					'<a href="#" class="remove_field2">Remove</a>' +
					'</div>'); //add input box
				$('#datepickerxxr' + x2).datepicker({
					format: 'dd-mm-yyyy',
					autoclose: true
				});
			}
		});

		$(wrapper2).on("click", ".remove_field2", function(e) { //user click on remove text
			e.preventDefault();
			$(this).parent('div').remove();
			x2--;
		})



		$('#add-payment').click(function() {
			var jumlah = $('#list_payment').find('tr').length;
			if (jumlah == 0 || jumlah == null) {
				var ada = 0;
				var loop = 1;
			} else {
				var nilai = $('#list_payment tr:last').attr('id');
				var jum1 = nilai.split('_');
				var loop = parseInt(jum1[1]) + 1;
			}
			Template = '<tr id="tr_' + loop + '">';
			Template += '<td align="left">';
			Template += '<input type="text" class="form-control input-sm" name="data1[' + loop + '][name_compotition]" id="data1_' + loop + '_name_compotition" label="FALSE" div="FALSE">';
			Template += '</td>';
			Template += '<td align="center"><button type="button" class="btn btn-sm btn-danger" title="Hapus Data" data-role="qtip" onClick="return DelItem(' + loop + ');">Delete</button></td>';
			Template += '</tr>';
			$('#list_payment').append(Template);
			$('input[data-role="tglbayar"]').datepicker({
				format: 'dd-mm-yyyy',
				autoclose: true
			});
		});

		$(document).on('click', '.delete', function(e) {
			e.preventDefault()
			var id = $(this).data('id_compotition');
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
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						type: 'POST',
						url: siteurl + 'inventory_2/delDetail',
						dataType: "json",
						data: {
							'id': id
						},
						success: function(result) {
							if (result.status == '1') {
								new swal("Sukses", "Data Inventory berhasil dihapus", "success");
								return false;
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

		})

		$('#simpan-com').click(function(e) {
			e.preventDefault();
			var deskripsi = $('#deskripsi').val();
			var image = $('#image').val();
			var idtype = $('#inventory_1').val();

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
			}).then((result) => {
				if (result.isConfirmed) {
					var formData = new FormData($('#data-form')[0]);
					var baseurl = siteurl + 'inventory_2/saveEditinventory';
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
								new swal({
									title: "Save Success!",
									text: data.pesan,
									type: "success",
									timer: 7000,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: false
								});
								window.location.href = base_url + active_controller;
							} else {

								if (data.status == 2) {
									new swal({
										title: "Save Failed!",
										text: data.pesan,
										type: "warning",
										timer: 7000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									});
								} else {
									new swal({
										title: "Save Failed!",
										text: data.pesan,
										type: "warning",
										timer: 7000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									});
								}

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
						}
					});
				} else {
					new swal("Cancelled", "Data can be process again :)", "error");
					return false;
				}
			});
		});

	});

	function DelItem(id) {
		$('#list_payment #tr_' + id).remove();

	}
</script>
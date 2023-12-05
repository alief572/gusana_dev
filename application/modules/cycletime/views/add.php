 <div class="box box-primary">
 	<div class="box-body"><br>
 		<form id="data-form" method="post">
 			<div class="form-group row">
 				<div class="col-md-2">
 					<label for="customer">Produk Name <span class='text-danger'>*</span></label>
 				</div>
 				<div class="col-md-10">
 					<select id="produk" name="produk" class="form-control input-md chosen-select produk"  required>
 						<option value="0">Select An Option</option>
 						<?php
							foreach ($results['product'] as $material) {

							?>
 							<option value="<?= $material->id_category3; ?>"><?= strtoupper(strtolower($material->nama)) ?></option>
 						<?php } ?>
 					</select>
 				</div>
 				<div class="col-md-2 mt-2">
 					<label for="customer">Lot Size <span class='text-danger'>*</span></label>
 				</div>
 				<div class="col-md-10 mt-2">
 					<select id="lot_size" name="lot_size" class="form-control input-md chosen-select lot_size" required>
 						<option value="">- Pilih Lot Size -</option>
 					</select>
 				</div>
 			</div>

 			<br>
 			<div class='box box-info'>
 				<div class='box-header'>
 					<h3 class='box-title'>Detail Product</h3>
 					<div class='box-tool pull-right'>
 						<!--<button type='button' data-id='frp_".$a."' class='btn btn-md btn-info panelSH'>SHOW</button>-->
 					</div>
 				</div>
 				<div class='box-body hide_header'>
 					<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 						<thead>
 							<tr class='bg-blue'>
 								<th class='text-center' style='width: 4%;'>#</th>
 								<th class='text-center' style='width: 30%;'>Cost Center</th>
 								<th class='text-center' style='width: 22%;'></th>
 								<th class='text-center' style='width: 22%;'></th>
 								<th class='text-center'>Information</th>
 								<th class='text-center' style='width: 4%;'>#</th>
 							</tr>
 						</thead>
 						<tbody>
 							<tr id='add_0'>
 								<td align='center'></td>
 								<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPart' title='Add Costcenter'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Costcenter</button></td>
 								<td align='center'></td>
 								<td align='center'></td>
 								<td align='center'></td>
 								<td align='center'></td>
 							</tr>
 						</tbody>
 					</table>
 					<br>
 					<button type="button" class="btn btn-danger" style='float:right; margin-left:5px;' name="back" id="back"><i class="fa fa-reply"></i> Back</button>
 					<button type="submit" class="btn btn-primary" style='float:right;' name="save" id="save"><i class="fa fa-save"></i> Save</button>
 				</div>
 			</div>
 		</form>
 	</div>
 </div>

 <script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>

 <script type="text/javascript">
 	//$('#input-kendaraan').hide();
 	var base_url = '<?php echo base_url(); ?>';
 	var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

	function chosen_select(){
		$('.chosen-select').select2({
 			width: '100%'
 		});
	}

 	$(document).ready(function() {

 		chosen_select();

 		$(document).on("change", ".produk", function() {
 			var produk = $(this).val();
			// alert(produk);
 			$.ajax({
 				type: "POST",
 				url: base_url + thisController + '/get_lot_size',
 				data: {
 					"produk": produk
 				},
 				cache: "FALSE",
 				dataType: "JSON",
 				success: function(result) {
					// console.log(result);
 					$(".lot_size").html(result);
					chosen_select();
 				}
 			});
 		});


 		//add part
 		$(document).on('click', '.addPart', function() {
 			// loading_spinner();
 			var get_id = $(this).parent().parent().attr('id');
 			// console.log(get_id);
 			var split_id = get_id.split('_');
 			var id = parseInt(split_id[1]) + 1;
 			var id_bef = split_id[1];

 			$.ajax({
 				url: base_url + active_controller + '/get_add/' + id,
 				cache: false,
 				type: "POST",
 				dataType: "json",
 				success: function(data) {
 					$("#add_" + id_bef).before(data.header);
 					$("#add_" + id_bef).remove();
 					$('.chosen-select').select2({
 						width: '100%'
 					});
 					$('.maskM').autoNumeric();
 					new swal.close();
 				},
 				error: function() {
 					new swal({
 						title: "Error Message !",
 						text: 'Connection Time Out. Please try again..',
 						type: "warning",
 						timer: 3000,
 						showCancelButton: false,
 						showConfirmButton: false,
 						allowOutsideClick: false
 					});
 				}
 			});
 		});

 		//add part
 		$(document).on('click', '.addSubPart', function() {
 			// loading_spinner();
 			var get_id = $(this).parent().parent().attr('id');
 			// console.log(get_id);
 			var split_id = get_id.split('_');
 			var id = split_id[1];
 			var id2 = parseInt(split_id[2]) + 1;
 			var id_bef = split_id[2];

 			$.ajax({
 				url: base_url + active_controller + '/get_add_sub/' + id + '/' + id2,
 				cache: false,
 				type: "POST",
 				dataType: "json",
 				success: function(data) {
 					$("#add_" + id + "_" + id_bef).before(data.header);
 					$("#add_" + id + "_" + id_bef).remove();
 					$('.chosen-select').select2();
 					$('.maskM').autoNumeric();
 					new swal.close();
 				},
 				error: function() {
 					new swal({
 						title: "Error Message !",
 						text: 'Connection Time Out. Please try again..',
 						type: "warning",
 						timer: 3000,
 						showCancelButton: false,
 						showConfirmButton: false,
 						allowOutsideClick: false
 					});
 				}
 			});
 		});

 		//delete part
 		$(document).on('click', '.delPart', function() {
 			var get_id = $(this).parent().parent().attr('class');
 			$("." + get_id).remove();
 		});

 		$(document).on('click', '.delSubPart', function() {
 			var get_id = $(this).parent().parent('tr').html();
 			$(this).parent().parent('tr').remove();
 		});

 		//add part
 		$(document).on('click', '#back', function() {
 			window.location.href = base_url + active_controller;
 		});



 		$('#save').click(function(e) {
 			e.preventDefault();
 			var produk = $('#produk').val();
 			var costcenter = $('.costcenter').val();
 			var process = $('.process').val();

 			if (produk == '0') {
 				new swal({
 					title: "Error Message!",
 					text: 'Product name empty, select first ...',
 					type: "warning"
 				});

 				$('#save').prop('disabled', false);
 				return false;
 			}
 			if (costcenter == '0') {
 				new swal({
 					title: "Error Message!",
 					text: 'Costcenter empty, select first ...',
 					type: "warning"
 				});

 				$('#save').prop('disabled', false);
 				return false;
 			}
 			if (process == '0') {
 				new swal({
 					title: "Error Message!",
 					text: 'Process name empty, select first ...',
 					type: "warning"
 				});

 				$('#save').prop('disabled', false);
 				return false;
 			}

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
 			}).then((hasil) => {
 				if (hasil.isConfirmed) {
 					var formData = new FormData($('#data-form')[0]);
 					var baseurl = siteurl + 'cycletime/save_cycletime';
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
 </script>
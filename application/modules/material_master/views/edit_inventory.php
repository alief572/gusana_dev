<div class="box box-primary">
	<div class="box-body">
		<form action="<?= base_url('material_master/saveEditInventory'); ?>" id="edit-form" method="post" enctype="multipart/form-data">
			<div class="col-sm-12">
				<div class="input_fields_wrap2">
					<input type="hidden" name="id_category3" class="id_category3" value="<?= (isset($results['inventory_4'])) ? $results['inventory_4']->id_category3 : null ?>">
					<table class="table" border="0" style="border:none !important;">
						<tr style="border:none !important;">
							<th>Material Type</th>
							<th colspan="5">
								<select id="inventory_1" name="inventory_1" class="form-control form-control-sm gabung_nama select inv_lv_1" onchange="get_inv2()" required>
									<option value="">-- Pilih Material Type --</option>
									<?php foreach ($results['inventory_1'] as $inventory_1) {
									?>
										<option value="<?= $inventory_1->id_type ?>" <?= (isset($results['inventory_4']) && $results['inventory_4']->id_type == $inventory_1->id_type) ? 'selected' : null; ?>><?= ucfirst(strtolower($inventory_1->nama)) ?></option>
									<?php } ?>
								</select>
							</th>
						</tr>
						<tr style="border:none !important;">
							<th>Material Category</th>
							<th colspan="5">
								<select id="inventory_2" name="inventory_2" class="form-control form-control-sm inv_lv_2 gabung_nama select" onchange="get_inv3()" required>
									<option value="">-- Pilih Material Category --</option>
									<?php foreach ($results['inventory_2'] as $iv_2) : ?>
										<option value="<?= $iv_2->id_category1 ?>" <?= (isset($results['inventory_4']) && $results['inventory_4']->id_category1 == $iv_2->id_category1) ? 'selected' : null ?>><?= $iv_2->nama ?></option>
									<?php endforeach; ?>
								</select>
							</th>
						</tr>
						<tr style="border:none !important;">
							<th>Material Jenis</th>
							<th colspan="5">
								<select id="inventory_3" name="inventory_3" class="form-control form-control-sm inv_lv_3 gabung_nama select" required>
									<option value="">-- Pilih Material Jenis --</option>
									<?php foreach ($results['inventory_3'] as $iv_3) : ?>
										<option value="<?= $iv_3->id_category2 ?>" <?= (isset($results['inventory_4']) && $results['inventory_4']->id_category2 == $iv_3->id_category2) ? 'selected' : null ?>><?= $iv_3->nama ?></option>
									<?php endforeach; ?>
								</select>
							</th>
						</tr>
						<tr style="border:none !important;">
							<th>Material Master</th>
							<th colspan="5">
								<input type="text" name="nm_lv_4" id="" class="form-control form-control-sm nm_lv_4" placeholder="Material Master" value="<?= $results['inventory_4']->nama ?>">
							</th>
						</tr>
						<tr style="border:none !important;">
							<th>Material Code</th>
							<th>
								<input type="text" name="material_code" id="" class="form-control form-control-sm" placeholder="Material Code" value="<?= $results['inventory_4']->material_code ?>">
							</th>
							<th>Supplier Utama</th>
							<th>
								<div id="slWrapperSupplier" class="parsley-select">
									<select name="supplier_utama" id="" class="form-control form-control-sm select" data-parsley-inputs data-parsley-class-handler="#slWrapperSupplier" data-parsley-errors-container="#slErrorContainerSupplier">
										<option value="">-- Supplier Utama --</option>
										<?php
										foreach ($results['suppliers'] as $supp) {
											$selected = "";
											if (isset($results['inventory_4']) && $results['inventory_4']->supplier_utama == $supp->id) {
												$selected = "selected";
											}
											echo '<option value="' . $supp->id . '" ' . $selected . '>' . $supp->supplier_name . '</option>';
										}
										?>
									</select>
									<div id="slErrorContainerSupplier"></div>
								</div>
							</th>
							<th>
								<input type="text" name="lead_time" id="" class="form-control form-control-sm" placeholder="Lead Time" value="<?= $results['inventory_4']->lead_time ?>">
							</th>
							<th>
								<input type="text" name="moq" id="" class="form-control form-control-sm" placeholder="MOQ" value="<?= $results['inventory_4']->moq ?>">
							</th>
						</tr>
						<tr style="border:none !important;">
							<th>Packing Unit / Conversion</th>
							<th>
								<div id="slWrapperPackaging" class="parsley-select">
									<select name="packaging" id="" class="form-control form-control-sm select" data-parsley-inputs data-parsley-class-handler="#slWrapperPackaging" data-parsley-errors-container="#slErrorContainerPackaging">
										<option value="">-- Packaging --</option>
										<?php
										foreach ($results['packaging'] as $pack) {
											$selected = "";
											if ($results['inventory_4']->packaging == $pack->id) {
												$selected = "selected";
											}
											echo '<option value="' . $pack->id . '" ' . $selected . '>' . $pack->nm_packaging . '</option>';
										}
										?>
									</select>
									<div id="slErrorContainerPackaging"></div>
								</div>
							</th>
							<th>Conversion</th>
							<th>
								<input type="number" name="konversi" id="" class="form-control form-control-sm" placeholder="Konversi" value="<?= $results['inventory_4']->konversi ?>" step="0.01">
							</th>
							<th>Unit Measurement</th>
							<th>
								<div id="slWrapperUnit" class="parsley-select">
									<select name="unit" id="" class="form-control form-control-sm select" data-parsley-inputs data-parsley-class-handler="#slWrapperUnit" data-parsley-errors-container="#slErrorContainerUnit">
										<option value="">-- Unit --</option>
										<?php
										foreach ($results['unit'] as $unit) {
											$selected = "";
											if ($results['inventory_4']->unit == $unit->id_unit) {
												$selected = "selected";
											}
											echo '<option value="' . $unit->id_unit . '" ' . $selected . '>' . $unit->nm_unit . '</option>';
										}
										?>
									</select>
									<div id="slErrorContainerUnit"></div>
								</div>
							</th>
						</tr>
						<tr style="border:none !important;">
							<th>Maximum Stok</th>
							<th colspan="2">
								<input type="number" name="max_stok" id="" class="form-control form-control-sm" step="0.001" value="<?= $results['inventory_4']->max_stok ?>">
							</th>
							<th>Minimum Stok</th>
							<th colspan="2">
								<input type="number" name="min_stok" id="" class="form-control form-control-sm" step="0.01" value="<?= $results['inventory_4']->min_stok ?>">
							</th>
						</tr>
						<tr style="border:none !important;">
							<th>MSDS</th>
							<th colspan="2">
								<input type="file" class="form-control form-control-sm" id="msds" name="msds" size="20" placeholder="msds">
							</th>
							<th colspan="3"></th>
						</tr>
						<tr>
							<th>Dimensi (meter)</th>
							<th>
								<input type="number" name="dim_length" id="" class="form-control form-control-sm dim_length" placeholder="Length" onchange="hitung_cbm()" step="0.01" value="<?= $results['inventory_4']->dim_length ?>">
							</th>
							<th>
								<input type="number" name="dim_width" id="" class="form-control form-control-sm dim_width" placeholder="Width" onchange="hitung_cbm()" step="0.01" value="<?= $results['inventory_4']->dim_width ?>">
							</th>
							<th>
								<input type="number" name="dim_height" id="" class="form-control form-control-sm dim_height" placeholder="Height" onchange="hitung_cbm()" step="0.01" value="<?= $results['inventory_4']->dim_height ?>">
							</th>
							<th colspan="4"></th>
						</tr>
						<tr>
							<th>CBM (m3)</th>
							<th>
								<input type="number" name="cbm" id="" class="form-control form-control-sm cbm" step="0.01" value="<?= $results['inventory_4']->cbm ?>" readonly>
							</th>
							<th colspan="4"></th>
						</tr>
						<tr>
							<td>Status</td>
							<td colspan="5">
								<input type="radio" name="aktif" class="ml-2" id="" value="aktif" <?= ($results['inventory_4']->aktif == "aktif") ? 'checked' : null ?>> Aktif
								<input type="radio" name="aktif" class="ml-2" id="" value="nonaktif" <?= ($results['inventory_4']->aktif == "nonaktif") ? 'checked' : null ?>> Non Aktif
							</td>
						</tr>
					</table>
					<div class="row">


						<div class="col-xs-2">
							&nbsp;
						</div>
					</div>

				</div>
			</div>
			<div class="col-12">
				<center>
					<h5>Alternatif Supplier</h5>
				</center>
				<table class='table'>
					<thead>
						<tr style="border:none !important;">
							<th class="text-center">Nama Supplier</th>
							<th class="text-center">Lead Time</th>
							<th class="text-center">MOQ</th>
							<th class="text-center">Alternatif Material</th>
							<th class="text-center">Keterangan</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody id='list_payment'>
						<?php foreach ($results['alt_suppliers'] as $alt_supplier) : ?>
							<tr>
								<td>
									<select name="" id="" class="form-control form-control-sm alt_supplier_<?= $alt_supplier->id ?>">
										<option value="">-- Nama Supplier --</option>
										<?php foreach ($results['suppliers'] as $supp) : ?>
											<option value="<?= $supp->id ?>" <?= ($supp->id == $alt_supplier->id_supplier) ? 'selected' : null ?>><?= $supp->supplier_name ?></option>
										<?php endforeach ?>
									</select>
								</td>
								<td>
									<input type="text" name="" id="" class="form-control form-control-sm alt_lead_time_<?= $alt_supplier->id ?>" placeholder="Lead Time" value="<?= $alt_supplier->lead_time ?>">
								</td>
								<td>
									<input type="text" name="" id="" class="form-control form-control-sm alt_moq_<?= $alt_supplier->id ?>" placeholder="MOQ" value="<?= $alt_supplier->moq ?>">
								</td>
								<td>
									<select name="" id="" class="form-control form-control-sm alt_material_<?= $alt_supplier->id ?>">
										<option value="">-- Alternatif Material --</option>
										<?php foreach ($results['inv_4_alt'] as $inv_4_alt) : ?>
											<option value="<?= $inv_4_alt->id_category3 ?>" <?= ($inv_4_alt->id_category3 == $alt_supplier->id_category3) ? 'selected' : null ?>><?= $inv_4_alt->nama ?></option>
										<?php endforeach; ?>
									</select>
								</td>
								<td>
									<input type="text" name="" id="" class="form-control form-control-sm alt_keterangan_<?= $alt_supplier->id ?>" placeholder="Keterangan" value="<?= $alt_supplier->keterangan ?>">
								</td>
								<td>
									<button type="button" class="btn btn-sm btn-danger del_inventory_category3_alt_supp" data-id="<?= $alt_supplier->id ?>">
										<i class="fa fa-trash"></i>
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tbody>
						<tr style="border:none !important;">
							<td>
								<select name="" id="" class="form-control form-control-sm alt_supplier">
									<option value="">-- Nama Supplier --</option>
									<?php foreach ($results['suppliers'] as $supp) : ?>
										<option value="<?= $supp->id ?>"><?= $supp->supplier_name ?></option>
									<?php endforeach ?>
								</select>
							</td>
							<td>
								<input type="text" name="" id="" class="form-control form-control-sm alt_lead_time" placeholder="Lead Time">
							</td>
							<td>
								<input type="text" name="" id="" class="form-control form-control-sm alt_moq" placeholder="MOQ">
							</td>
							<td>
								<select name="" id="" class="form-control form-control-sm alt_material_id">
									<option value="">-- Alternatif Material --</option>
									<?php foreach ($results['material_master'] as $mater) : ?>
										<option value="<?= $mater->id_category3 ?>"><?= $mater->nama ?></option>
									<?php endforeach ?>
								</select>
							</td>
							<td>
								<input type="text" name="" id="" class="form-control form-control-sm alt_keterangan" placeholder="Keterangan">
							</td>
							<td>
								<button type="button" class="btn btn-sm btn-success add_alt_supplier">
									<i class="fa fa-plus"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
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

	// $(document).on('keyup','#inventory_2','#inventory_3','#nm_inventory','.maker','.hardness', function(){
	// cariNama();
	// });



	$('.select').select2({
		placeholder: 'Choose one',
		dropdownParent: $('.box-primary'),
		width: "100%",
		allowClear: true
	});

	$(document).on('keyup', '#dimensi1', function() {
		cariThickness();
	});

	$(document).on('change', '#inventory_3', function() {
		cariAlloy();
	});

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
					'<input type="text" name="hd' + x2 + '[produk]"  class="form-control form-control-sm input-sm" value="">' +
					'</div>' +
					'<div class="input-group">' +
					'<input type="text" name="hd' + x2 + '[costcenter]"  class="form-control form-control-sm input-sm" value="">' +
					'</div>' +
					'<div class="input-group">' +
					'<input type="text" name="hd' + x2 + '[mesin]"  class="form-control form-control-sm input-sm" value="">' +
					'</div>' +
					'<div class="input-group">' +
					'<input type="text" name="hd' + x2 + '[mold_tools]"  class="form-control form-control-sm input-sm" value="">' +
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

		$('#data-form').submit(function(e) {
			e.preventDefault();
			var deskripsi = $('#deskripsi').val();
			var image = $('#image').val();
			var idtype = $('#material_master').val();

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
			}).then((hasil) => {
				if (hasil.isConfirmed) {
					var formData = new FormData(this);
					var baseurl = siteurl + 'material_master/saveEditInventory';
					$.ajax({
						url: baseurl,
						type: "POST",
						data: formData,
						cache: false,
						dataType: 'json',
						processData: false,
						contentType: false,
						async: false,
						success: function(data) {
							if (data.status == 1) {
								new swal({
									title: "Save Success!",
									text: data.pesan,
									type: "success",
									timer: 3000,
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
										timer: 3000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									}).then((hasil3) => {
										window.location.href = base_url + active_controller;
									});
								} else {
									new swal({
										title: "Save Failed!",
										text: data.pesan,
										type: "warning",
										timer: 3000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									}).then((hasil4) => {
										window.location.href = base_url + active_controller;
									});
								}

							}
						},
						error: function() {

							new swal({
								title: "Error Message !",
								text: 'An Error Occured During Process. Please try again..',
								type: "warning",
								timer: 3000,
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
	$('#inventory_2').change(function() {
		var inventory_2 = $("#inventory_2").val();
		$.ajax({
			type: "GET",
			url: siteurl + 'material_master/get_compotition',
			data: "inventory_2=" + inventory_2,
			success: function(html) {
				$("#list_compotition").html(html);
			}
		});
	});


	function get_inv2() {
		var inventory_1 = $("#inventory_1").val();
		$.ajax({
			type: "GET",
			url: siteurl + 'material_master/get_inven2',
			data: "inventory_1=" + inventory_1,
			success: function(html) {
				$("#inventory_2").html(html);
			}
		});
	}

	function cariAlloy() {
		var inventory_1 = $("#inventory_3").val();
		$.ajax({
			type: "POST",
			url: siteurl + 'material_master/get_namainven2',
			data: "inventory_1=" + inventory_1,
			success: function(html) {
				$("#alloy").val(html);
			}
		});
	}

	function get_bentuk() {
		var id_bentuk = $("#id_bentuk").val();
		$.ajax({
			type: "GET",
			url: siteurl + 'material_master/get_dimensi',
			data: "id_bentuk=" + id_bentuk,
			success: function(html) {
				$("#list_dimensi").html(html);
			}
		});
	}





	function get_olddata() {
		var nm_inventory = $("#nm_inventory").val();
		$.ajax({
			type: "GET",
			url: siteurl + 'material_master/get_olddata',
			data: "id_bentuk=" + id_bentuk,
			success: function(html) {
				$("#list_dimensi").html(html);
			}
		});
	}

	function get_inv3() {
		var inventory_2 = $("#inventory_2").val();
		$.ajax({
			type: "GET",
			url: siteurl + 'material_master/get_inven3',
			data: "inventory_2=" + inventory_2,
			success: function(html) {
				$("#inventory_3").html(html);
			}
		});
	}


	function get_surface() {
		var idsurface = $(".idsurface").val();
		$.ajax({
			type: "GET",
			url: siteurl + 'material_master/get_surface',
			data: "idsurface=" + idsurface,
			success: function(data) {
				$(".surface").val(data);
			}
		});



	}

	function cariThickness() {
		var dimensi = $("#dimensi1").val();
		$("#thickness").val(dimensi);

		cariNama();
	}

	function cariNama() {
		var alloy = $("#alloy").val();

		var spek = $("#nm_inventory").val();
		if (spek != '') {
			var stripspek = '-';
		} else {
			var stripspek = '';
		};
		var hardness = $(".hardness").val();
		if (hardness != '') {
			var striphardness = '-';
		} else {
			var striphardness = '';
		};
		var thickness = $("#thickness").val();
		if (thickness != '') {
			var stripthickness = '-';
		} else {
			var stripthickness = '';
		};
		var maker = $(".maker").val();
		if (maker != '') {
			var stripmaker = '-';
		} else {
			var stripmaker = '';
		};
		var surface = $(".surface").val();
		if (surface != '') {
			var stripsurface = '-';
		} else {
			var stripsurface = '';
		};

		var nama = alloy + stripspek + spek + striphardness + hardness + stripthickness + thickness + stripsurface + surface;
		$(".nama").val(nama);

	}

	function DelItem(id) {
		$('#list_payment #tr_' + id).remove();

	}
</script>
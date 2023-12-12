<div class="box box-primary">
	<div class="box-body">
		<form action="<?= base_url('material_master/saveEditInventory'); ?>" id="edit-form" method="post" enctype="multipart/form-data">
			<div class="col-sm-12">
				<div class="input_fields_wrap2">
					<input type="hidden" name="id_category3" class="id_category3" value="<?= (isset($results['inventory_4'])) ? $results['inventory_4']->id_category3 : null ?>">
					<table class="table" border="0" style="border:none !important;">
						<tr style="border:none !important;">
							<th>Product Type</th>
							<th colspan="5">
								<select id="inventory_1" name="inventory_1" class="form-control form-control-sm gabung_nama inv_lv_1 select" onchange="get_inv2()" required>
									<option value="">-- Pilih Product Type --</option>
									<?php foreach ($results['inventory_1'] as $inventory_1) {
									?>
										<option value="<?= $inventory_1->id_type ?>" <?= (isset($results['inventory_4']) && $results['inventory_4']->id_type == $inventory_1->id_type) ? 'selected' : null; ?>><?= ucfirst(strtolower($inventory_1->nama)) ?></option>
									<?php } ?>
								</select>
							</th>
						</tr>
						<tr style="border:none !important;">
							<th>Product Category</th>
							<th colspan="5">
								<select id="inventory_2" name="inventory_2" class="form-control form-control-sm inv_lv_2 gabung_nama select" onchange="get_inv3()" required>
									<option value="">-- Pilih Product Category --</option>
									<?php foreach ($results['inventory_2'] as $iv_2) : ?>
										<option value="<?= $iv_2->id_category1 ?>" <?= (isset($results['inventory_4']) && $results['inventory_4']->id_category1 == $iv_2->id_category1) ? 'selected' : null ?>><?= $iv_2->nama ?></option>
									<?php endforeach; ?>
								</select>
							</th>
						</tr>
						<tr style="border:none !important;">
							<th>Product Jenis</th>
							<th colspan="5">
								<select id="inventory_3" name="inventory_3" class="form-control form-control-sm inv_lv_3 gabung_nama select" required>
									<option value="">-- Pilih Product Jenis --</option>
									<?php foreach ($results['inventory_3'] as $iv_3) : ?>
										<option value="<?= $iv_3->id_category2 ?>" <?= (isset($results['inventory_4']) && $results['inventory_4']->id_category2 == $iv_3->id_category2) ? 'selected' : null ?>><?= $iv_3->nama ?></option>
									<?php endforeach; ?>
								</select>
							</th>
						</tr>
						<tr style="border:none !important;">
							<th>Product Master</th>
							<th colspan="5">
								<input type="text" name="nm_lv_4" id="" class="form-control form-control-sm nm_lv_4" placeholder="Product Master" value="<?= $results['inventory_4']->nama ?>">
							</th>
						</tr>
						<tr style="border:none !important;">
							<th>Product Code</th>
							<th>
								<input type="text" name="product_code" id="" class="form-control form-control-sm" placeholder="Product Code" value="<?= $results['inventory_4']->product_code ?>">
							</th>
							<th>Trade Name</th>
							<th>
								<input type="text" name="trade_name" id="" class="form-control form-control-sm" value="<?= $results['inventory_4']->trade_name; ?>">
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
											if ($results['inventory_4']->unit_id == $unit->id_unit) {
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
							<th>PDS</th>
							<th colspan="2">
								<input type="file" class="form-control form-control-sm" id="pds" name="pds" size="20" placeholder="pds">
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
							<th>Dimensi Tabung (Jari - jari / Meter)</th>
							<th>
								<input type="number" name="dim_tabung_r" id="" class="form-control form-control-sm dim_tabung_r dim_tabung" value="<?= $results['inventory_4']->dim_tabung_r ?>" step="0.01">
							</th>
							<th>Dimensi Tabung (Jari - jari / Meter)</th>
							<th>
								<input type="number" name="dim_tabung_t" id="" class="form-control form-control-sm dim_tabung_t dim_tabung" value="<?= $results['inventory_4']->dim_tabung_t ?>" step="0.01">
							</th>
						</tr>
						<tr>
							<th>CBM Tabung (m3)</th>
							<th>
								<input type="text" name="cbm_tabung" id="" class="form-control form-control-sm cbm_tabung" value="<?= $results['inventory_4']->cbm_tabung ?>" readonly>
							</th>
							<th colspan="4"></th>
						</tr>
						<tr>
							<td>Status</td>
							<td colspan="5">
								<input type="radio" name="aktif" class="ml-2" id="" value="1" <?= ($results['inventory_4']->aktif == "1") ? 'checked' : null ?>> Aktif
								<input type="radio" name="aktif" class="ml-2" id="" value="0" <?= ($results['inventory_4']->aktif == "0") ? 'checked' : null ?>> Non Aktif
							</td>
						</tr>
					</table>
					<table class="table" border="0">
						<thead>
							<tr>
								<th colspan="3">Spesifikasi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<label for="">Aplikasi penggunaan cat dan coating</label>
									<select name="aplikasi_penggunaan_cat" id="" class="form-control form-control-sm">
										<option value="">- Aplikasi penggunaan cat dan coating -</option>
										<option value="1" <?= ($results['inventory_4']->aplikasi_penggunaan_cat == 1) ? 'selected' : null ?>>Steel/Besi</option>
										<option value="2" <?= ($results['inventory_4']->aplikasi_penggunaan_cat == 2) ? 'selected' : null ?>>Kayu</option>
										<option value="3" <?= ($results['inventory_4']->aplikasi_penggunaan_cat == 3) ? 'selected' : null ?>>Tembok</option>
										<option value="4" <?= ($results['inventory_4']->aplikasi_penggunaan_cat == 4) ? 'selected' : null ?>>Lantai</option>
										<option value="5" <?= ($results['inventory_4']->aplikasi_penggunaan_cat == 5) ? 'selected' : null ?>>Batu/Bata</option>
										<option value="6" <?= ($results['inventory_4']->aplikasi_penggunaan_cat == 6) ? 'selected' : null ?>>Gypsum</option>
										<option value="7" <?= ($results['inventory_4']->aplikasi_penggunaan_cat == 7) ? 'selected' : null ?>>Polymer</option>
										<option value="8" <?= ($results['inventory_4']->aplikasi_penggunaan_cat == 8) ? 'selected' : null ?>>Beton</option>
										<option value="9" <?= ($results['inventory_4']->aplikasi_penggunaan_cat == 9) ? 'selected' : null ?>>Baja</option>
										<option value="10" <?= ($results['inventory_4']->aplikasi_penggunaan_cat == 10) ? 'selected' : null ?>>Semen</option>
										<option value="11" <?= ($results['inventory_4']->aplikasi_penggunaan_cat == 11) ? 'selected' : null ?>>Keramik dan Kaca</option>
									</select>
								</td>
								<td>
									<label for="">Water Resistance</label>
									<select name="water_resistance" id="" class="form-control form-control-sm">
										<option value="">- Water Resistance -</option>
										<option value="1" <?= ($results['inventory_4']->water_resistance == 1) ? 'selected' : null ?>>Yes</option>
										<option value="2" <?= ($results['inventory_4']->water_resistance == 2) ? 'selected' : null ?>>No</option>
									</select>
								</td>
								<td>
									<label for="">Weather & UV resistance</label>
									<select name="weather_uv_resistance" id="" class="form-control form-control-sm">
										<option value="">- Weather & UV resistance -</option>
										<option value="1" <?= ($results['inventory_4']->weather_uv_resistance == 1) ? 'selected' : null ?>>Yes</option>
										<option value="2" <?= ($results['inventory_4']->weather_uv_resistance == 2) ? 'selected' : null ?>>No</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="">Corrosion Resistance</label>
									<select name="corrosion_resistance" id="" class="form-control form-control-sm">
										<option value="">- Corrosion Resistance -</option>
										<option value="1" <?= ($results['inventory_4']->corrosion_resistance == 1) ? 'selected' : null ?>>High</option>
										<option value="2" <?= ($results['inventory_4']->corrosion_resistance == 2) ? 'selected' : null ?>>Medium</option>
										<option value="3" <?= ($results['inventory_4']->corrosion_resistance == 3) ? 'selected' : null ?>>Low</option>
									</select>
								</td>
								<td>
									<label for="">Heat Resistance</label>
									<select name="heat_resistance" id="" class="form-control form-control-sm">
										<option value="">- Heat Resistance -</option>
										<option value="1" <?= ($results['inventory_4']->heat_resistance == 1) ? 'selected' : null ?>>Up to 200 °C</option>
										<option value="2" <?= ($results['inventory_4']->heat_resistance == 2) ? 'selected' : null ?>>Up to 300 °C</option>
										<option value="3" <?= ($results['inventory_4']->heat_resistance == 3) ? 'selected' : null ?>>Up to 400 °C</option>
										<option value="4" <?= ($results['inventory_4']->heat_resistance == 4) ? 'selected' : null ?>>Up to 500 °C</option>
										<option value="5" <?= ($results['inventory_4']->heat_resistance == 5) ? 'selected' : null ?>>Up to 600 °C</option>
										<option value="6" <?= ($results['inventory_4']->heat_resistance == 6) ? 'selected' : null ?>>Up to 800 °C</option>
									</select>
								</td>
								<td>
									<label for="">Daya Rekat (Adhesi)</label>
									<select name="daya_rekat" id="" class="form-control form-control-sm">
										<option value="">- Daya Rekat -</option>
										<option value="1" <?= ($results['inventory_4']->daya_rekat == 1) ? 'selected' : null ?>>High</option>
										<option value="2" <?= ($results['inventory_4']->daya_rekat == 2) ? 'selected' : null ?>>Medium</option>
										<option value="3" <?= ($results['inventory_4']->daya_rekat == 3) ? 'selected' : null ?>>Low</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="">Lama Pengeringan</label>
									<select name="lama_pengeringan" id="" class="form-control form-control-sm">
										<option value="">- Lama Pengeringan -</option>
										<option value="1" <?= ($results['inventory_4']->lama_pengeringan == 1) ? 'selected' : null ?>>Cepat</option>
										<option value="2" <?= ($results['inventory_4']->lama_pengeringan == 2) ? 'selected' : null ?>>Lambat</option>
									</select>
								</td>
								<td>
									<label for="">Permukaan</label>
									<select name="permukaan" id="" class="form-control form-control-sm">
										<option value="">- Permukaan -</option>
										<option value="1" <?= ($results['inventory_4']->permukaan == 1) ? 'selected' : null ?>>Glossy</option>
										<option value="2" <?= ($results['inventory_4']->permukaan == 2) ? 'selected' : null ?>>Matte</option>
										<option value="3" <?= ($results['inventory_4']->permukaan == 3) ? 'selected' : null ?>>Semi Matte</option>
									</select>
								</td>
								<td>
									<label for="">Anti Jamur dan Lumut</label>
									<select name="anti_jamur_lumut" id="" class="form-control form-control-sm">
										<option value="">- Anti Jamur dan Lumut -</option>
										<option value="1" <?= ($results['inventory_4']->anti_jamur_lumut == 1) ? 'selected' : null ?>>Yes</option>
										<option value="2" <?= ($results['inventory_4']->anti_jamur_lumut == 2) ? 'selected' : null ?>>No</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="">Mudah dibersihkan (Dirt Resistant)</label>
									<select name="mudah_dibersihkan" id="" class="form-control form-control-sm">
										<option value="">- Mudah dibersihkan (Dirt Resistant) -</option>
										<option value="1" <?= ($results['inventory_4']->mudah_dibersihkan == 1) ? 'selected' : null ?>>Yes</option>
										<option value="2" <?= ($results['inventory_4']->mudah_dibersihkan == 2) ? 'selected' : null ?>>No</option>
									</select>
								</td>
								<td>
									<label for="">Anti Bakteri</label>
									<select name="anti_bakteri" id="" class="form-control form-control-sm">
										<option value="">- Anti Bakteri -</option>
										<option value="1" <?= ($results['inventory_4']->anti_bakteri == 1) ? 'selected' : null ?>>Yes</option>
										<option value="2" <?= ($results['inventory_4']->anti_bakteri == 2) ? 'selected' : null ?>>No</option>
									</select>
								</td>
								<td>
									<label for="">Daya tahan gesekan</label>
									<select name="daya_tahan_gesekan" id="" class="form-control form-control-sm">
										<option value="">- Daya tahan gesekan -</option>
										<option value="1" <?= ($results['inventory_4']->daya_tahan_gesekan == 1) ? 'selected' : null ?>>Yes</option>
										<option value="2" <?= ($results['inventory_4']->daya_tahan_gesekan == 2) ? 'selected' : null ?>>No</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="">Anti Slip</label>
									<select name="anti_slip" id="" class="form-control form-control-sm">
										<option value="">- Anti Slip -</option>
										<option value="1" <?= ($results['inventory_4']->anti_slip == 1) ? 'selected' : null ?>>Yes</option>
										<option value="2" <?= ($results['inventory_4']->anti_slip == 2) ? 'selected' : null ?>>No</option>
									</select>
								</td>
								<td>
									<label for="">Fire Resistance</label>
									<select name="fire_resistance" id="" class="form-control form-control-sm">
										<option value="">- Fire Resistance -</option>
										<option value="1" <?= ($results['inventory_4']->fire_resistance == 1) ? 'selected' : null ?>>Yes</option>
										<option value="2" <?= ($results['inventory_4']->fire_resistance == 2) ? 'selected' : null ?>>No</option>
									</select>
								</td>
								<td>
									<label for="">Ketahanan Bahan Kimia</label>
									<select name="ketahanan_bahan_kimia" id="" class="form-control form-control-sm">
										<option value="">- Ketahanan Bahan Kimia -</option>
										<option value="1" <?= ($results['inventory_4']->ketahanan_bahan_kimia == 1) ? 'selected' : null ?>>Yes</option>
										<option value="2" <?= ($results['inventory_4']->ketahanan_bahan_kimia == 2) ? 'selected' : null ?>>No</option>
									</select>
								</td>
							</tr>
						</tbody>
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
					<h5>Alternatif Competitor</h5>
				</center>
				<table class='table'>
					<thead>
						<tr style="border:none !important;">
							<th class="text-center">Nama Competitor</th>
							<th class="text-center">Nama Produk</th>
						</tr>
					</thead>
					<tbody id='list_payment'>
						<?php foreach ($results['alt_comp'] as $alt_comp) : ?>
							<tr>
								<td>
									<input type="text" name="" id="" class="form-control form-control-sm" placeholder="Nama Competitor" value="<?= $alt_comp->nm_competitor ?>">
								</td>
								<td>
									<input type="text" name="" id="" class="form-control form-control-sm" placeholder="Nama Produk" value="<?= $alt_comp->nm_product ?>">
								</td>
								<td>
									<button type="button" class="btn btn-sm btn-danger del_inventory_category3_alt_comp" data-id="<?= $alt_comp->id ?>">
										<i class="fa fa-trash"></i>
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tbody>
						<tr style="border:none !important;">
							<td>
								<input type="text" name="" id="" class="form-control form-control-sm alt_comp" placeholder="Nama Competitor">
							</td>
							<td>
								<input type="text" name="" id="" class="form-control form-control-sm alt_produk" placeholder="Nama Produk">
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-sm btn-success add_alt_comp">
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

	$(".cbm_tabung").autoNumeric();

	$('.select').select2({
		placeholder: 'Choose one',
		dropdownParent: $('#dialog-popup'),
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

	$(document).on("keyup", ".dim_tabung", function() {
		var dim_tabung_r = $(".dim_tabung_r").val();
		var dim_tabung_t = $(".dim_tabung_t").val();
		if (dim_tabung_r == "") {
			dim_tabung_r = 0;
		} else {
			dim_tabung_r = dim_tabung_r.split(',').join('');
			dim_tabung_r = parseFloat(dim_tabung_r);
		}

		if (dim_tabung_t == "") {
			dim_tabung_t = 0;
		} else {
			dim_tabung_t = dim_tabung_t.split(',').join('');
			dim_tabung_t = parseFloat(dim_tabung_t);
		}

		if (dim_tabung_r == 0 || dim_tabung_t == 0) {
			var cbm_tabung = 0;
		} else {
			var cbm_tabung = parseFloat(3.14 * (dim_tabung_r * dim_tabung_r) * dim_tabung_t);
		}

		$(".cbm_tabung").autoNumeric('set', cbm_tabung.toFixed(2));
	});

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
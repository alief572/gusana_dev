<div class="box box-primary">
	<div class="box-body">
		<form action="" id="edit-form" method="post" enctype="multipart/form-data">
			<div class="col-sm-12">
				<div class="input_fields_wrap2">
					<input type="hidden" name="id" class="" value="<?= (isset($results['request_develop'])) ? $results['request_develop']->id : null ?>">
					<h5>Input Sales</h5>
					<hr>
					<table class="w-100" border="0" style="border:none !important;">
						<tbody>
							<tr style="border:none !important;">
								<th>Product Type</th>
								<th colspan="5">
									<input type="text" name="" id="" class="form-control form-control-sm" value="<?= (isset($results['request_develop'])) ? $results['request_develop']->nm_type : null ?>" readonly>
								</th>
							</tr>
							<tr style="border:none !important;">
								<th>Product Category</th>
								<th colspan="5">
									<input type="text" name="" id="" class="form-control form-control-sm" value="<?= (isset($results['request_develop'])) ? $results['request_develop']->nm_category1 : null ?>" readonly>
								</th>
							</tr>
							<tr style="border:none !important;">
								<th>Product Jenis</th>
								<th colspan="5">
									<input type="text" name="" id="" class="form-control form-control-sm" value="<?= (isset($results['request_develop'])) ? $results['request_develop']->nm_type : null ?>" readonly>
								</th>
							</tr>
							<tr style="border:none !important;">
								<th>Spesifikasi Packaging</th>
								<th>
									<input type="number" name="spek_packaging" class="form-control " id="" value="<?= (isset($results['request_develop'])) ? $results['request_develop']->konversi : null ?>" readonly>
								</th>
								<th>Unit</th>
								<th>
									<input type="text" name="" id="" class="form-control form-control-sm" value="<?= (isset($results['request_develop'])) ? $results['request_develop']->unit_nm : null ?>" readonly>
								</th>
							</tr>
							<tr>
								<th>Jenis Packaging</th>
								<th>
									<select name="jenis_packaging" id="" class="form-control jenis_packaging chosen-select" disabled>
										<option value="">- Jenis Packaging -</option>
										<?php foreach ($results['packaging'] as $pack) :
											$selected = '';
											if (isset($results['request_develop']) && $results['request_develop']->packaging == $pack->nm_packaging) {
												$selected = 'selected';
											}
										?>
											<option value="<?= $pack->nm_packaging ?>" <?= $selected ?>><?= $pack->nm_packaging ?></option>
										<?php endforeach; ?>
									</select>
								</th>
							</tr>
							<tr style="border:none !important;">
								<th>Keterangan</th>
								<th colspan="5">
									<textarea name="keterangan" id="" cols="30" rows="6" class="form-control" readonly><?= (isset($results['request_develop'])) ? $results['request_develop']->keterangan : null ?></textarea>
								</th>
							</tr>
						</tbody>
					</table>
					<div class="">
						<h5>Input R&D</h5>
						<hr>
						<table class="table product_form <?= (isset($results['request_develop']) && $results['request_develop']->sts == 'approved') ? '' : 'd-none' ?>">
							<tbody>
								<tr style="border:none !important;">
									<th>Product Type</th>
									<th colspan="5">
										<select id="inventory_1" name="inventory_1" class="form-control form-control-sm gabung_nama inv_lv_1 select" onchange="get_inv2()" disabled>
											<option value="">-- Pilih Product Type --</option>
											<?php foreach ($results['product_type'] as $inventory_1) {
											?>
												<option value="<?= $inventory_1->id_type ?>" <?= (isset($results['product_master']) && $results['product_master']->id_type == $inventory_1->id_type) ? 'selected' : null; ?>><?= ucfirst(strtolower($inventory_1->nama)) ?></option>
											<?php } ?>
										</select>
									</th>
								</tr>
								<tr style="border:none !important;">
									<th>Product Category</th>
									<th colspan="5">
										<select id="inventory_2" name="inventory_2" class="form-control form-control-sm inv_lv_2 gabung_nama select" onchange="get_inv3()" disabled>
											<option value="">-- Pilih Product Category --</option>
											<?php foreach ($results['product_category'] as $iv_2) : ?>
												<option value="<?= $iv_2->id_category1 ?>" <?= (isset($results['product_master']) && $results['product_master']->id_category1 == $iv_2->id_category1) ? 'selected' : null ?>><?= $iv_2->nama ?></option>
											<?php endforeach; ?>
										</select>
									</th>
								</tr>
								<tr style="border:none !important;">
									<th>Product Jenis</th>
									<th colspan="5">
										<select id="inventory_3" name="inventory_3" class="form-control form-control-sm inv_lv_3 gabung_nama select" disabled>
											<option value="">-- Pilih Product Jenis --</option>
											<?php foreach ($results['product_jenis'] as $iv_3) : ?>
												<option value="<?= $iv_3->id_category2 ?>" <?= (isset($results['product_master']) && $results['product_master']->id_category2 == $iv_3->id_category2) ? 'selected' : null ?>><?= $iv_3->nama ?></option>
											<?php endforeach; ?>
										</select>
									</th>
								</tr>
								<tr style="border:none !important;">
									<th>Product Master</th>
									<th colspan="5">
										<input type="text" name="nm_lv_4" id="" class="form-control form-control-sm nm_lv_4" placeholder="Product Master" value="<?= (isset($results['product_master'])) ? $results['product_master']->nama : null ?>" readonly>
									</th>
								</tr>
								<tr style="border:none !important;">
									<th>Product Master (Mandarin)</th>
									<th colspan="5">
										<input type="text" name="nm_lv_4_mandarin" id="" class="form-control form-control-sm" placeholder="Product Master (Mandarin)" value="<?= (isset($results['product_master'])) ? $results['product_master']->nama_mandarin : null ?>" readonly>
									</th>
								</tr>
								<tr style="border:none !important;">
									<th>Product Code</th>
									<th>
										<input type="text" name="product_code" id="" class="form-control form-control-sm" placeholder="Product Code" value="<?= (isset($results['product_master'])) ? $results['product_master']->product_code : null ?>" readonly>
									</th>
									<th>Curing Agent</th>
									<th>
										<select name="curing_agent" id="" class="form-control form-control-sm select" placeholder="Curing Agent" disabled>
											<option value="">- Curing Agent -</option>
											<?php foreach ($results['list_curing_agent'] as $curing_agent) : ?>
												<option value="<?= $curing_agent->id_category3 ?>" <?= (isset($results['product_master']) && $results['product_master']->curing_agent == $curing_agent->id_category) ? 'selected' : null ?>><?= $curing_agent->nama ?></option>
											<?php endforeach; ?>
										</select>
										<!-- <input type="text" name="trade_name" id="" class="form-control form-control-sm" placeholder="Trade Name"> -->
									</th>
									<th>
										<input type="text" name="curing_agent_konversi" id="" class="form-control form-control-sm text-right autonum" placeholder="Curing Agent Konversi (Kg)" value="<?= (isset($results['product_master'])) ? $results['product_master']->curing_agent_konversi : null ?>" readonly>
									</th>
									<th>
										<input type="text" name="moq" id="" class="form-control form-control-sm" placeholder="MOQ" value="<?= (isset($results['product_master'])) ? $results['product_master']->moq : null ?>" readonly>
									</th>
								</tr>
								<tr style="border:none !important;">
									<th>Packing Unit / Conversion</th>
									<th>
										<div id="slWrapperPackaging" class="parsley-select">
											<select name="packaging" id="" class="form-control form-control-sm select" data-parsley-inputs data-parsley-class-handler="#slWrapperPackaging" data-parsley-errors-container="#slErrorContainerPackaging" disabled>
												<option value="">-- Packaging --</option>
												<?php
												foreach ($results['packaging'] as $pack) {
													echo '<option value="' . $pack->id . '" ' . (isset($results['product_master']) && $results['product_master']->packaging == $pack->id) ? 'selected' : null . '>' . $pack->nm_packaging . '</option>';
												}
												?>
											</select>
											<div id="slErrorContainerPackaging"></div>
										</div>
									</th>
									<th>Conversion</th>
									<th>
										<input type="number" name="konversi" id="" class="form-control form-control-sm" placeholder="Konversi" step="0.01" value="<?= (isset($results['product_master'])) ? $results['product_master']->konversi : null ?>" readonly>
									</th>
									<th>Unit Measurement</th>
									<th>
										<div id="slWrapperUnit" class="parsley-select">
											<select name="unit" id="" class="form-control form-control-sm select" data-parsley-inputs data-parsley-class-handler="#slWrapperUnit" data-parsley-errors-container="#slErrorContainerUnit" disabled>
												<option value="">-- Unit --</option>
												<?php
												foreach ($results['unit'] as $unit) {
													echo '<option value="' . $unit->id_unit . '" ' . (isset($results['product_master']) && $results['product_master']->unit_id == $unit->id_unit) ? 'selected' : null . '>' . $unit->nm_unit . '</option>';
												}
												?>
											</select>
											<div id="slErrorContainerUnit"></div>
										</div>
									</th>
								</tr>
								<tr style="border:none !important;">
									<th>Maximum Stok</th>
									<th>
										<input type="number" name="max_stok" id="" class="form-control form-control-sm" step="0.001" value="<?= (isset($results['product_master'])) ? $results['product_master']->max_stock : null ?>" readonly>
									</th>
									<th>Minimum Stok</th>
									<th>
										<input type="number" name="min_stok" id="" class="form-control form-control-sm" step="0.01" value="<?= (isset($results['product_master'])) ? $results['product_master']->min_stock : null ?>" readonly>
									</th>
									<th colspan="2"></th>
								</tr>
								<tr style="border:none !important;">
									<th>PDS</th>
									<th>
										<?php if (file_exists('.' . $results['request_develop']->pds)) : ?>
											<a href="<?= '.' . $results['request_develop']->pds ?>" target="_blank"><?= str_replace('/uploads/', '', $results['request_develop']->pds); ?></a>
										<?php endif; ?>
									</th>
									<th colspan="4"></th>
								</tr>
								<tr>
									<th>Dimensi (meter)</th>
									<th>
										<input type="number" name="dim_length" id="" class="form-control form-control-sm dim_length" placeholder="Length" onchange="hitung_cbm()" value="<?= (isset($results['product_master'])) ? $results['product_master']->dim_length : null ?>" step="0.01" readonly>
									</th>
									<th>
										<input type="number" name="dim_width" id="" class="form-control form-control-sm dim_width" placeholder="Width" onchange="hitung_cbm()" value="<?= (isset($results['product_master'])) ? $results['product_master']->dim_width : null ?>" step="0.01" readonly>
									</th>
									<th>
										<input type="number" name="dim_height" id="" class="form-control form-control-sm dim_height" placeholder="Height" onchange="hitung_cbm()" value="<?= (isset($results['product_master'])) ? $results['product_master']->dim_height : null ?>" step="0.01" readonly>
									</th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
								<tr>
									<th>CBM (m3)</th>
									<th>
										<input type="number" name="cbm" id="" class="form-control form-control-sm cbm" step="0.01" value="<?= (isset($results['product_master'])) ? $results['product_master']->cbm : null ?>" readonly>
									</th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
								<tr>
									<th>Dimensi Tabung (Jari - jari / Meter)</th>
									<th>
										<input type="number" name="dim_tabung_r" id="" class="form-control form-control-sm dim_tabung_r dim_tabung" value="<?= (isset($results['product_master'])) ? $results['product_master']->dim_tabung_r : null ?>" step="0.01" readonly>
									</th>
									<th>Dimensi Tabung (Tinggi / Meter)</th>
									<th>
										<input type="number" name="dim_tabung_t" id="" class="form-control form-control-sm dim_tabung_t dim_tabung" value="<?= (isset($results['product_master'])) ? $results['product_master']->dim_tabung_t : null ?>" step="0.01" readonly>
									</th>
									<th></th>
									<th></th>
								</tr>
								<tr>
									<th>CBM Tabung (m3)</th>
									<th>
										<input type="text" name="cbm_tabung" id="" class="form-control form-control-sm cbm_tabung" step="0.01" value="<?= (isset($results['product_master'])) ? $results['product_master']->cbm_tabung : null ?>" readonly>
									</th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
								<tr>
									<th>Refer Product</th>
									<th colspan="5">
										<select name="refer_product" id="" class="form-control form-control-sm select" disabled>
											<option value="">- Refer Product -</option>
											<?php
											foreach ($results['inventory_4'] as $inven_4) :
												echo '<option value="' . $inven_4->id_category3 . '" ' . (isset($results['product_master']) && $results['product_master']->id_product_refer == $inven_4->id_category3) ? 'selected' : null . '>' . $inven_4->nama . '</option>';
											endforeach;
											?>
										</select>
									</th>
								</tr>
							</tbody>
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
										<select name="aplikasi_penggunaan_cat" id="" class="form-control form-control-sm" disabled>
											<option value="">- Aplikasi penggunaan cat dan coating -</option>
											<option value="1" <?= ($results['request_develop']->aplikasi_penggunaan_cat == 1) ? 'selected' : null ?>>Steel/Besi</option>
											<option value="2" <?= ($results['request_develop']->aplikasi_penggunaan_cat == 2) ? 'selected' : null ?>>Kayu</option>
											<option value="3" <?= ($results['request_develop']->aplikasi_penggunaan_cat == 3) ? 'selected' : null ?>>Tembok</option>
											<option value="4" <?= ($results['request_develop']->aplikasi_penggunaan_cat == 4) ? 'selected' : null ?>>Lantai</option>
											<option value="5" <?= ($results['request_develop']->aplikasi_penggunaan_cat == 5) ? 'selected' : null ?>>Batu/Bata</option>
											<option value="6" <?= ($results['request_develop']->aplikasi_penggunaan_cat == 6) ? 'selected' : null ?>>Gypsum</option>
											<option value="7" <?= ($results['request_develop']->aplikasi_penggunaan_cat == 7) ? 'selected' : null ?>>Polymer</option>
											<option value="8" <?= ($results['request_develop']->aplikasi_penggunaan_cat == 8) ? 'selected' : null ?>>Beton</option>
											<option value="9" <?= ($results['request_develop']->aplikasi_penggunaan_cat == 9) ? 'selected' : null ?>>Baja</option>
											<option value="10" <?= ($results['request_develop']->aplikasi_penggunaan_cat == 10) ? 'selected' : null ?>>Semen</option>
											<option value="11" <?= ($results['request_develop']->aplikasi_penggunaan_cat == 11) ? 'selected' : null ?>>Keramik dan Kaca</option>
										</select>
									</td>
									<td>
										<label for="">Water Resistance</label>
										<select name="water_resistance" id="" class="form-control form-control-sm" disabled>
											<option value="">- Water Resistance -</option>
											<option value="1" <?= ($results['request_develop']->water_resistance == 1) ? 'selected' : null ?>>Yes</option>
											<option value="2" <?= ($results['request_develop']->water_resistance == 2) ? 'selected' : null ?>>No</option>
										</select>
									</td>
									<td>
										<label for="">Weather & UV resistance</label>
										<select name="weather_uv_resistance" id="" class="form-control form-control-sm" disabled>
											<option value="">- Weather & UV resistance -</option>
											<option value="1" <?= ($results['request_develop']->weather_uv_resistance == 1) ? 'selected' : null ?>>Yes</option>
											<option value="2" <?= ($results['request_develop']->weather_uv_resistance == 2) ? 'selected' : null ?>>No</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<label for="">Corrosion Resistance</label>
										<select name="corrosion_resistance" id="" class="form-control form-control-sm" disabled>
											<option value="">- Corrosion Resistance -</option>
											<option value="1" <?= ($results['request_develop']->corrosion_resistance == 1) ? 'selected' : null ?>>High</option>
											<option value="2" <?= ($results['request_develop']->corrosion_resistance == 2) ? 'selected' : null ?>>Medium</option>
											<option value="3" <?= ($results['request_develop']->corrosion_resistance == 3) ? 'selected' : null ?>>Low</option>
										</select>
									</td>
									<td>
										<label for="">Heat Resistance</label>
										<select name="heat_resistance" id="" class="form-control form-control-sm" disabled>
											<option value="">- Heat Resistance -</option>
											<option value="1" <?= ($results['request_develop']->heat_resistance == 1) ? 'selected' : null ?>>Up to 200 °C</option>
											<option value="2" <?= ($results['request_develop']->heat_resistance == 2) ? 'selected' : null ?>>Up to 300 °C</option>
											<option value="3" <?= ($results['request_develop']->heat_resistance == 3) ? 'selected' : null ?>>Up to 400 °C</option>
											<option value="4" <?= ($results['request_develop']->heat_resistance == 4) ? 'selected' : null ?>>Up to 500 °C</option>
											<option value="5" <?= ($results['request_develop']->heat_resistance == 5) ? 'selected' : null ?>>Up to 600 °C</option>
											<option value="6" <?= ($results['request_develop']->heat_resistance == 6) ? 'selected' : null ?>>Up to 800 °C</option>
										</select>
									</td>
									<td>
										<label for="">Daya Rekat (Adhesi)</label>
										<select name="daya_rekat" id="" class="form-control form-control-sm" disabled>
											<option value="">- Daya Rekat -</option>
											<option value="1" <?= ($results['request_develop']->daya_rekat == 1) ? 'selected' : null ?>>High</option>
											<option value="2" <?= ($results['request_develop']->daya_rekat == 2) ? 'selected' : null ?>>Medium</option>
											<option value="3" <?= ($results['request_develop']->daya_rekat == 3) ? 'selected' : null ?>>Low</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<label for="">Lama Pengeringan</label>
										<select name="lama_pengeringan" id="" class="form-control form-control-sm" disabled>
											<option value="">- Lama Pengeringan -</option>
											<option value="1" <?= ($results['request_develop']->lama_pengeringan == 1) ? 'selected' : null ?>>Cepat</option>
											<option value="2" <?= ($results['request_develop']->lama_pengeringan == 2) ? 'selected' : null ?>>Lambat</option>
										</select>
									</td>
									<td>
										<label for="">Permukaan</label>
										<select name="permukaan" id="" class="form-control form-control-sm" disabled>
											<option value="">- Permukaan -</option>
											<option value="1" <?= ($results['request_develop']->permukaan == 1) ? 'selected' : null ?>>Glossy</option>
											<option value="2" <?= ($results['request_develop']->permukaan == 2) ? 'selected' : null ?>>Matte</option>
											<option value="3" <?= ($results['request_develop']->permukaan == 3) ? 'selected' : null ?>>Semi Matte</option>
										</select>
									</td>
									<td>
										<label for="">Anti Jamur dan Lumut</label>
										<select name="anti_jamur_lumut" id="" class="form-control form-control-sm" disabled>
											<option value="">- Anti Jamur dan Lumut -</option>
											<option value="1" <?= ($results['request_develop']->anti_jamur_lumut == 1) ? 'selected' : null ?>>Yes</option>
											<option value="2" <?= ($results['request_develop']->anti_jamur_lumut == 2) ? 'selected' : null ?>>No</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<label for="">Mudah dibersihkan (Dirt Resistant)</label>
										<select name="mudah_dibersihkan" id="" class="form-control form-control-sm" disabled>
											<option value="">- Mudah dibersihkan (Dirt Resistant) -</option>
											<option value="1" <?= ($results['request_develop']->mudah_dibersihkan == 1) ? 'selected' : null ?>>Yes</option>
											<option value="2" <?= ($results['request_develop']->mudah_dibersihkan == 2) ? 'selected' : null ?>>No</option>
										</select>
									</td>
									<td>
										<label for="">Anti Bakteri</label>
										<select name="anti_bakteri" id="" class="form-control form-control-sm" disabled>
											<option value="">- Anti Bakteri -</option>
											<option value="1" <?= ($results['request_develop']->anti_bakteri == 1) ? 'selected' : null ?>>Yes</option>
											<option value="2" <?= ($results['request_develop']->anti_bakteri == 2) ? 'selected' : null ?>>No</option>
										</select>
									</td>
									<td>
										<label for="">Daya tahan gesekan</label>
										<select name="daya_tahan_gesekan" id="" class="form-control form-control-sm" disabled>
											<option value="">- Daya tahan gesekan -</option>
											<option value="1" <?= ($results['request_develop']->daya_tahan_gesekan == 1) ? 'selected' : null ?>>Yes</option>
											<option value="2" <?= ($results['request_develop']->daya_tahan_gesekan == 2) ? 'selected' : null ?>>No</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<label for="">Anti Slip</label>
										<select name="anti_slip" id="" class="form-control form-control-sm" disabled>
											<option value="">- Anti Slip -</option>
											<option value="1" <?= ($results['request_develop']->anti_slip == 1) ? 'selected' : null ?>>Yes</option>
											<option value="2" <?= ($results['request_develop']->anti_slip == 2) ? 'selected' : null ?>>No</option>
										</select>
									</td>
									<td>
										<label for="">Fire Resistance</label>
										<select name="fire_resistance" id="" class="form-control form-control-sm" disabled>
											<option value="">- Fire Resistance -</option>
											<option value="1" <?= ($results['request_develop']->fire_resistance == 1) ? 'selected' : null ?>>Yes</option>
											<option value="2" <?= ($results['request_develop']->fire_resistance == 2) ? 'selected' : null ?>>No</option>
										</select>
									</td>
									<td>
										<label for="">Ketahanan Bahan Kimia</label>
										<select name="ketahanan_bahan_kimia" id="" class="form-control form-control-sm" disabled>
											<option value="">- Ketahanan Bahan Kimia -</option>
											<option value="1" <?= ($results['request_develop']->ketahanan_bahan_kimia == 1) ? 'selected' : null ?>>Yes</option>
											<option value="2" <?= ($results['request_develop']->ketahanan_bahan_kimia == 2) ? 'selected' : null ?>>No</option>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="row">


						<div class="col-xs-2">
							&nbsp;
						</div>
					</div>

				</div>
			</div>
			<!-- <hr> -->
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
	$(".autonum").autoNumeric();

	$('.chosen-select').select2({
		dropdownParent: $('#ModalView'),
		selectOnClose: true,
		width: '100%'
	});


	$('.select').select2({
		placeholder: 'Choose one',
		dropdownParent: $('#ModalView'),
		width: "100%",
		allowClear: true
	});

	$('.select.not-search').select2({
		minimumResultsForSearch: -1,
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

	$(document).on('change', '.req_action', function() {
		var req_action = $(this).val();
		if (req_action == '1') {
			$('.product_form').removeClass('d-none');
		} else {
			$('.product_form').addClass('d-none');
		}
	});

	$(document).on('change', '.get_lv_1', function() {
		var lv_type = $(this).val();

		$.ajax({
			type: 'post',
			url: siteurl + thisController + 'get_lv_1',
			data: {
				'lv_type': lv_type,
			},
			cache: false,
			dataType: 'json',
			success: function(result) {
				$('.product_category1').html(result.hasil);
			}
		});
	});
	$(document).on('change', '.get_lv_2', function() {
		var lv_type1 = $(this).val();

		$.ajax({
			type: 'post',
			url: siteurl + thisController + 'get_lv_2',
			data: {
				'lv_type1': lv_type1,
			},
			cache: false,
			dataType: 'json',
			success: function(result) {
				$('.product_category2').html(result.hasil);
			}
		});
	});

	$(document).ready(function() {
		var data_pay = <?php echo json_encode($results['supplier']); ?>;

		///INPUT PERKIRAAN KIRIM


		var max_fields2 = 10; //maximum input boxes allowed
		var wrapper2 = $(".input_fields_wrap2"); //Fields wrapper
		var add_button2 = $(".add_field_button2"); //Add button ID

		//console.log(persen);

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
			Template += '<select id="id_supplier" name="data1[' + loop + '][id_supplier]" id="data1_' + loop + '_id_supplier" class="form-control form-control-sm select" required>';
			Template += '<option value="">-- Pilih Type --</option>';
			Template += '<?php foreach ($results["id_supplier"] as $id_supplier) { ?>';
			Template += '<option value="<?= $id_supplier->id ?>"><?= ucfirst(strtolower($id_supplier->supplier_name)) ?></option>';
			Template += '<?php } ?>';
			Template += '</select>';
			Template += '</td>';
			Template += '<td align="left">';
			Template += '<input type="text" class="form-control form-control-sm input-sm" name="data1[' + loop + '][lead]" id="data1_' + loop + '_lead" label="FALSE" div="FALSE">';
			Template += '</td>';
			Template += '<td align="left">';
			Template += '<input type="text" class="form-control form-control-sm input-sm" name="data1[' + loop + '][minimum]" id="data1_' + loop + '_minimum" label="FALSE" div="FALSE">';
			Template += '</td>';
			Template += '<td align="center"><button type="button" class="btn btn-sm btn-danger" title="Hapus Data" data-role="qtip" onClick="return DelItem(' + loop + ');">Delete</button></td>';
			Template += '</tr>';
			$('#list_payment').append(Template);
			$('input[data-role="tglbayar"]').datepicker({
				format: 'dd-mm-yyyy',
				autoclose: true
			});
		});



		$('#data-form').submit(function(e) {
			e.preventDefault();
			var deskripsi = $('#deskripsi').val();
			var image = $('#image').val();
			var idtype = $('#product_master').val();

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
					var baseurl = siteurl + 'request_develop/saveNewInventory';
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
			url: siteurl + 'request_develop/get_compotition',
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
			url: siteurl + 'request_develop/get_inven2',
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
			url: siteurl + 'request_develop/get_namainven2',
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
			url: siteurl + 'request_develop/get_dimensi',
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
			url: siteurl + 'request_develop/get_olddata',
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
			url: siteurl + 'request_develop/get_inven3',
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
			url: siteurl + 'request_develop/get_surface',
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
 <div class="box box-primary">
 	<div class="box-body">
 		<form action="<?= base_url('request_develop/saveNewInventory'); ?>" id="data-form" method="post" enctype="multipart/form-data">
 			<div class="col-sm-12">
 				<div class="input_fields_wrap2">
 					<input type="hidden" name="" class="id_category3" value="<?= (isset($results['id_category3'])) ? $results['id_category3'] : null ?>">
 					<h5>Input Sales</h5>
 					<hr>
 					<table class="w-100" border="0" style="border:none !important;">
 						<tbody>
 							<tr style="border:none !important;">
 								<th>Product Type</th>
 								<th colspan="5">
 									<select id="nm_type" name="nm_type" class="form-control form-control-sm get_lv_1 chosen-select" required>
 										<option value="">-- Pilih Product Type --</option>
 										<?php foreach ($results['product_type'] as $product_type) {
											?>
 											<option value="<?= $product_type->id_type ?>"><?= ucfirst(strtolower($product_type->nama)) ?></option>
 										<?php } ?>
 									</select>
 								</th>
 							</tr>
 							<tr style="border:none !important;">
 								<th>Product Category</th>
 								<th colspan="5">
 									<select id="nm_category1" name="nm_category1" class="form-control form-control-sm get_lv_2 product_category1 chosen-select" required>
 										<option value="">-- Pilih Product Category --</option>
 										<?php foreach ($results['product_category'] as $product_category) {
											?>
 											<option value="<?= $product_category->id_category1 ?>"><?= ucfirst(strtolower($product_category->nama)) ?></option>
 										<?php } ?>
 									</select>
 								</th>
 							</tr>
 							<tr style="border:none !important;">
 								<th>Product Jenis</th>
 								<th colspan="5">
 									<select id="nm_category2" name="nm_category2" class="form-control form-control-sm product_category2 chosen-select" required>
 										<option value="">-- Pilih Product Jenis --</option>
 										<?php foreach ($results['product_jenis'] as $product_jenis) {
											?>
 											<option value="<?= $product_jenis->id_category2 ?>"><?= ucfirst(strtolower($product_jenis->nama)) ?></option>
 										<?php } ?>
 									</select>
 								</th>
 							</tr>
 							<tr style="border:none !important;">
 								<th>Spesifikasi Packaging</th>
 								<th>
 									<input type="number" name="spek_packaging" class="form-control " id="">
 								</th>
 								<th>Jenis Packaging</th>
 								<th>
 									<select name="jenis_packaging" id="" class="form-control jenis_packaging chosen-select">
 										<option value="">- Jenis Packaging -</option>
 										<?php foreach ($results['packaging'] as $pack) : ?>
 											<option value="<?= $pack->nm_packaging ?>"><?= $pack->nm_packaging ?></option>
 										<?php endforeach; ?>
 									</select>
 								</th>
 							</tr>
 							<tr style="border:none !important;">
 								<th>Keterangan</th>
 								<th colspan="5">
 									<textarea name="keterangan" id="" cols="30" rows="6" class="form-control"></textarea>
 								</th>
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
 		width: "150px",
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
 </script>
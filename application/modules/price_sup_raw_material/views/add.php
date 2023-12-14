<?php
$id 		= (!empty($listData[0]->id_category3)) ? $listData[0]->id_category3 : '';
$nama 		= (!empty($listData[0]->nama)) ? $listData[0]->nama : '';
$status_app = (!empty($listData[0]->status_app)) ? $listData[0]->status_app : '';

$price_ref_idr 		= (!empty($listData[0]->price_ref_idr)) ? $listData[0]->price_ref_idr : 0;
$price_ref_usd 		= (!empty($listData[0]->price_ref_usd)) ? $listData[0]->price_ref_usd : 0;
$price_ref_rmb 		= (!empty($listData[0]->price_ref_rmb)) ? $listData[0]->price_ref_rmb : 0;
$price_ref_high_idr = (!empty($listData[0]->price_ref_high_idr)) ? $listData[0]->price_ref_high_idr : 0;
$price_ref_high_usd = (!empty($listData[0]->price_ref_high_usd)) ? $listData[0]->price_ref_high_usd : 0;
$price_ref_high_rmb = (!empty($listData[0]->price_ref_high_rmb)) ? $listData[0]->price_ref_high_rmb : 0;

$price_ref_new 	= '';
$price_ref_high_new 	= '';
$note 			= '';
$upload_file 	= '';

$expired1 	= '';
$expired3 	= '';
$expired6 	= '';
$expired12 	= '';

$price_ref_new_idr 	= 0;
$price_ref_new_usd 	= 0;
$price_ref_new_rmb 	= 0;
$price_ref_high_new_idr 	= 0;
$price_ref_high_new_usd 	= 0;
$price_ref_high_new_rmb 	= 0;

if ($status_app == 'Y') {

	$price_ref_new_idr 	= (!empty($listData[0]->price_ref_new_idr)) ? $listData[0]->price_ref_new_idr : 0;
	$price_ref_new_usd 	= (!empty($listData[0]->price_ref_new_usd)) ? $listData[0]->price_ref_new_usd : 0;
	$price_ref_new_rmb 	= (!empty($listData[0]->price_ref_new_rmb)) ? $listData[0]->price_ref_new_rmb : 0;
	$price_ref_high_new_idr 	= (!empty($listData[0]->price_ref_high_new_idr)) ? $listData[0]->price_ref_high_new_idr : 0;
	$price_ref_high_new_usd 	= (!empty($listData[0]->price_ref_high_new_usd)) ? $listData[0]->price_ref_high_new_usd : 0;
	$price_ref_high_new_rmb 	= (!empty($listData[0]->price_ref_high_new_rmb)) ? $listData[0]->price_ref_high_new_rmb : 0;
	$note 			= (!empty($listData[0]->note)) ? $listData[0]->note : '';
	$upload_file 	= (!empty($listData[0]->upload_file)) ? $listData[0]->upload_file : '';

	$expired1 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '1') ? 'selected' : '';
	$expired3 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '3') ? 'selected' : '';
	$expired6 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '6') ? 'selected' : '';
	$expired12 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '12') ? 'selected' : '';
}
?>
<div class="box box-primary">
	<div class="box-body">
		<form id="data_form" method="post" autocomplete="off" enctype='multiple/form-data'>
			<div class="form-group row">
				<div class="col-md-2">
					<label for="">Material Master</label>
				</div>
				<div class="col-md-10">
					<input type="hidden" class="form-control" id="id" name="id" value='<?= $id; ?>'>
					<input type="text" class="form-control" id="nama" required name="nama" placeholder="Material Type" value='<?= $nama; ?>' readonly>
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-2"></div>
				<div class="col-md-5">
					<label class='text-danger'>Lower Price</label>
				</div>
				<div class="col-md-5">
					<label class='text-success'>Higher Price</label>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Before</label>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_idr" name="price_ref_idr" value='<?= $price_ref_idr; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_usd" name="price_ref_usd" value='<?= $price_ref_usd; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_rmb" name="price_ref_rmb" value='<?= $price_ref_rmb; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">RMB</button>
						</span>
					</div>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_high_idr" name="price_ref_high_idr" value='<?= $price_ref_high_idr; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_high_usd" name="price_ref_high_usd" value='<?= $price_ref_high_usd; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_high_rmb" name="price_ref_high_rmb" value='<?= $price_ref_high_rmb; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">RMB</button>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>After <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_new_idr" name="price_ref_new_idr" value='<?= $price_ref_new_idr; ?>'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_new_usd" name="price_ref_new_usd" value='<?= $price_ref_new_usd; ?>'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_new_rmb" name="price_ref_new_rmb" value='<?= $price_ref_new_rmb; ?>'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">RMB</button>
						</span>
					</div>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_high_new_idr" name="price_ref_high_new_idr" value='<?= $price_ref_high_new_idr; ?>'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_high_new_usd" name="price_ref_high_new_usd" value='<?= $price_ref_high_new_usd; ?>'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_high_new_rmb" name="price_ref_high_new_rmb" value='<?= $price_ref_high_new_rmb; ?>'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">RMB</button>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Expired <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-4">
					<select id="price_ref_expired" name="price_ref_expired" class="form-control form-control-sm input-md chosen-select" required>
						<option value="0">Select An Expired</option>
						<option value="1" <?= $expired1; ?>>1 Bulan</option>
						<option value="3" <?= $expired3; ?>>3 Bulan</option>
						<option value="6" <?= $expired6; ?>>Semester</option>
						<option value="12" <?= $expired12; ?>>Tahunan</option>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>File Evidence <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<input type="file" name='photo' id="photo">
					</div>
					<?php if (!empty($upload_file)) { ?>
						<a href='<?= base_url() . $upload_file; ?>' target='_blank' class="help-block" title='Download'>Download File</a>
					<?php } ?>
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Note</label>
				</div>
				<div class="col-md-10">
					<textarea class="form-control" id="note" name="note" row='3' placeholder="Note"><?= $note; ?></textarea>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2"></div>
				<div class="col-md-10">
					<button type="submit" class="btn btn-primary" name="save" id="save"><i class="fa fa-save"></i> Save</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('.chosen-select').select2({
			width: '100%',
			dropdownParent: $("#ModalView")
		});
		$('.autoNumeric').autoNumeric();
		$('#price_ref_new_idr').autoNumeric();
		$('#price_ref_new_usd').autoNumeric();
		$('#price_ref_new_rmb').autoNumeric();
		$('#price_ref_high_new_idr').autoNumeric();
		$('#price_ref_high_new_usd').autoNumeric();
		$('#price_ref_high_new_rmb').autoNumeric();

		$(document).on("keyup", "#price_ref_new_idr", function() {
			var price_ref_new_idr = $(this).val();
			if (price_ref_new_idr == "" || price_ref_new_idr == null) {
				var price_ref_bew_idr = 0;
			} else {
				var price_ref_new_idr = price_ref_new_idr.split(",").join("");
				var price_ref_new_idr = parseFloat(price_ref_new_idr);
			}

			var kurs_usd = 0;
			var kurs_rmb = 0;

			$.ajax({
				type: "POST",
				url: base_url + thisController + 'get_kurs',
				cache: false,
				dataType: "JSON",
				success: function(result) {
					// console.log(result.kurs_usd);
					var kurs_usd = parseFloat(result.kurs_usd);
					var kurs_rmb = parseFloat(result.kurs_rmb);

					$("#price_ref_new_usd").autoNumeric('set',(price_ref_new_idr / kurs_usd).toFixed(2));
					$("#price_ref_new_rmb").autoNumeric('set',(price_ref_new_idr / kurs_rmb).toFixed(2));
				}
			});
		});

		$(document).on("keyup","#price_ref_new_usd",function(){
			var price_ref_new_usd = $(this).val();
			if (price_ref_new_usd == "" || price_ref_new_usd == null) {
				var price_ref_bew_usd = 0;
			} else {
				var price_ref_new_usd = price_ref_new_usd.split(",").join("");
				var price_ref_new_usd = parseFloat(price_ref_new_usd);
			}

			var kurs_usd = 0;
			var kurs_rmb = 0;

			$.ajax({
				type: "POST",
				url: base_url + thisController + 'get_kurs',
				cache: false,
				dataType: "JSON",
				success: function(result) {
					// console.log(result.kurs_usd);
					var kurs_usd = parseFloat(result.kurs_usd);
					var kurs_rmb = parseFloat(result.kurs_rmb);

					$("#price_ref_new_idr").autoNumeric('set',(price_ref_new_usd * kurs_usd).toFixed(2));
					$("#price_ref_new_rmb").autoNumeric('set',(price_ref_new_usd * kurs_usd / kurs_rmb).toFixed(2));
				}
			});
		});

		$(document).on("keyup","#price_ref_new_rmb",function(){
			var price_ref_new_rmb = $(this).val();
			if (price_ref_new_rmb == "" || price_ref_new_rmb == null) {
				var price_ref_bew_rmb = 0;
			} else {
				var price_ref_new_rmb = price_ref_new_rmb.split(",").join("");
				var price_ref_new_rmb = parseFloat(price_ref_new_rmb);
			}

			var kurs_usd = 0;
			var kurs_rmb = 0;

			$.ajax({
				type: "POST",
				url: base_url + thisController + 'get_kurs',
				cache: false,
				dataType: "JSON",
				success: function(result) {
					// console.log(result.kurs_usd);
					var kurs_usd = parseFloat(result.kurs_usd);
					var kurs_rmb = parseFloat(result.kurs_rmb);

					$("#price_ref_new_idr").autoNumeric('set',(price_ref_new_rmb * kurs_rmb).toFixed(2));
					$("#price_ref_new_usd").autoNumeric('set',(price_ref_new_rmb * kurs_rmb / kurs_usd).toFixed(2));
				}
			});
		});

		$(document).on("keyup","#price_ref_high_new_idr",function(){
			var price_ref_high_new_idr = $(this).val();
			if (price_ref_high_new_idr == "" || price_ref_high_new_idr == null) {
				var price_ref_high_new_idr = 0;
			} else {
				var price_ref_high_new_idr = price_ref_high_new_idr.split(",").join("");
				var price_ref_high_new_idr = parseFloat(price_ref_high_new_idr);
			}

			var kurs_usd = 0;
			var kurs_rmb = 0;

			$.ajax({
				type: "POST",
				url: base_url + thisController + 'get_kurs',
				cache: false,
				dataType: "JSON",
				success: function(result) {
					// console.log(result.kurs_usd);
					var kurs_usd = parseFloat(result.kurs_usd);
					var kurs_rmb = parseFloat(result.kurs_rmb);

					$("#price_ref_high_new_usd").autoNumeric('set',(price_ref_high_new_idr / kurs_usd).toFixed(2));
					$("#price_ref_high_new_rmb").autoNumeric('set',(price_ref_high_new_idr / kurs_rmb).toFixed(2));
				}
			});
		});

		$(document).on("keyup","#price_ref_high_new_usd",function(){
			var price_ref_high_new_usd = $(this).val();
			if (price_ref_high_new_usd == "" || price_ref_high_new_usd == null) {
				var price_ref_high_new_usd = 0;
			} else {
				var price_ref_high_new_usd = price_ref_high_new_usd.split(",").join("");
				var price_ref_high_new_usd = parseFloat(price_ref_high_new_usd);
			}

			var kurs_usd = 0;
			var kurs_rmb = 0;

			$.ajax({
				type: "POST",
				url: base_url + thisController + 'get_kurs',
				cache: false,
				dataType: "JSON",
				success: function(result) {
					// console.log(result.kurs_usd);
					var kurs_usd = parseFloat(result.kurs_usd);
					var kurs_rmb = parseFloat(result.kurs_rmb);

					$("#price_ref_high_new_idr").autoNumeric('set',(price_ref_high_new_usd * kurs_usd).toFixed(2));
					$("#price_ref_high_new_rmb").autoNumeric('set',(price_ref_high_new_usd * kurs_usd / kurs_rmb).toFixed(2));
				}
			});
		});

		$(document).on("keyup","#price_ref_high_new_rmb",function(){
			var price_ref_high_new_rmb = $(this).val();
			if (price_ref_high_new_rmb == "" || price_ref_high_new_rmb == null) {
				var price_ref_high_new_rmb = 0;
			} else {
				var price_ref_high_new_rmb = price_ref_high_new_rmb.split(",").join("");
				var price_ref_high_new_rmb = parseFloat(price_ref_high_new_rmb);
			}

			var kurs_usd = 0;
			var kurs_rmb = 0;

			$.ajax({
				type: "POST",
				url: base_url + thisController + 'get_kurs',
				cache: false,
				dataType: "JSON",
				success: function(result) {
					// console.log(result.kurs_usd);
					var kurs_usd = parseFloat(result.kurs_usd);
					var kurs_rmb = parseFloat(result.kurs_rmb);

					$("#price_ref_high_new_usd").autoNumeric('set',(price_ref_high_new_rmb * kurs_rmb / kurs_usd).toFixed(2));
					$("#price_ref_high_new_idr").autoNumeric('set',(price_ref_high_new_rmb * kurs_rmb).toFixed(2));
				}
			});
		});
	});
</script>
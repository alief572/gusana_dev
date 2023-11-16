<?php
$id 		= (!empty($listData[0]->id_category3)) ? $listData[0]->id_category3 : '';
$nama 		= (!empty($listData[0]->nama)) ? $listData[0]->nama : '';
$status_app = (!empty($listData[0]->status_app)) ? $listData[0]->status_app : '';

$price_ref 		= (!empty($listData[0]->price_ref)) ? $listData[0]->price_ref : '';
$price_ref_high = (!empty($listData[0]->price_ref_high)) ? $listData[0]->price_ref_high : '';

$price_ref_new 	= '';
$price_ref_high_new 	= '';
$note 			= '';
$upload_file 	= '';

$expired1 	= '';
$expired3 	= '';
$expired6 	= '';
$expired12 	= '';

if ($status_app == 'Y') {

	$price_ref_new 	= (!empty($listData[0]->price_ref_new)) ? $listData[0]->price_ref_new : '';
	$price_ref_high_new 	= (!empty($listData[0]->price_ref_high_new)) ? $listData[0]->price_ref_high_new : '';
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
				<div class="col-md-2">
					<label>Lower Price Before</label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control autoNumeric" id="price_ref" name="price_ref" value='<?= $price_ref; ?>' placeholder="Lower Price Before" readonly>
				</div>
				<div class="col-md-2">
					<label>Higher Price Before</label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control autoNumeric" id="price_ref_high" name="price_ref_high" value='<?= $price_ref_high; ?>' placeholder="Higher Price Before" readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Lower Price After <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control autoNumeric" id="price_ref_new" required name="price_ref_new" placeholder="Lower Price After" value='<?= $price_ref_new; ?>'>
				</div>
				<div class="col-md-2">
					<label>Higher Price After <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control autoNumeric" id="price_ref_high_new" required name="price_ref_high_new" placeholder="Higher Price After" value='<?= $price_ref_high_new; ?>'>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Expired <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-4">
					<select id="price_ref_expired" name="price_ref_expired" class="form-control form-control-sm input-md" required>
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
					<label>File Evidance <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<input type="file" name='photo' id="photo" required>
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
			width: '100%'
		});
		$('.autoNumeric').autoNumeric();
	});
</script>
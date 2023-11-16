<?php
$id 		= (!empty($listData[0]->id_category3)) ? $listData[0]->id_category3 : '';
$nama 		= (!empty($listData[0]->nama)) ? $listData[0]->nama : '';
$code 		= (!empty($listData[0]->material_code)) ? $listData[0]->material_code : '';
$status_app = (!empty($listData[0]->status_app)) ? $listData[0]->status_app : '';

$price_ref 		= (!empty($listData[0]->price_ref)) ? $listData[0]->price_ref : 0;
$price_ref_high 		= (!empty($listData[0]->price_ref_high)) ? $listData[0]->price_ref_high : 0;
$price_ref_new 	= (!empty($listData[0]->price_ref_new)) ? $listData[0]->price_ref_new : 0;
$price_ref_high_new 	= (!empty($listData[0]->price_ref_high_new)) ? $listData[0]->price_ref_high_new : 0;
$price_ref_use 	= (!empty($listData[0]->price_ref_use)) ? $listData[0]->price_ref_use : 0;

$price_ref_date 		= (!empty($listData[0]->price_ref_date)) ? $listData[0]->price_ref_date : 0;
$price_ref_new_date 	= (!empty($listData[0]->price_ref_new_date)) ? $listData[0]->price_ref_new_date : 0;
$price_ref_date_use 	= (!empty($listData[0]->price_ref_date_use)) ? $listData[0]->price_ref_date_use : 0;

$price_ref_expired 		= (!empty($listData[0]->price_ref_expired)) ? $listData[0]->price_ref_expired : 0;
$price_ref_new_expired 	= (!empty($listData[0]->price_ref_new_expired)) ? $listData[0]->price_ref_new_expired : 0;
$price_ref_expired_use 	= (!empty($listData[0]->price_ref_expired_use)) ? $listData[0]->price_ref_expired_use : 0;

$tgl_expired 		= '';
$tgl_expired_new 	= '';
$tgl_expired_use 	= '';

if ($price_ref_date != 0) {
	$tgl_expired 		= date('d-M-Y', strtotime('+' . $price_ref_expired . ' month', strtotime($price_ref_date)));
}
if ($price_ref_new_date != 0) {
	$tgl_expired_new 	= date('d-M-Y', strtotime('+' . $price_ref_new_expired . ' month', strtotime($price_ref_new_date)));
}
if ($price_ref_date_use != 0) {
	$tgl_expired_use 	= date('d-M-Y', strtotime('+' . $price_ref_expired_use . ' month', strtotime($price_ref_date_use)));
}

$note 			= (!empty($listData[0]->note)) ? $listData[0]->note : '';

$expired1 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '1') ? 'selected' : '';
$expired3 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '3') ? 'selected' : '';
$expired6 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '6') ? 'selected' : '';
$expired12 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '12') ? 'selected' : '';

?>
<div class="box box-primary">
	<div class="box-body">
		<form id="data_form" method="post" autocomplete="off" enctype='multiple/form-data'>
			<div class="form-group row">
				<div class="col-md-2">
					<label for="">Material ID</label>
				</div>
				<div class="col-md-10">
					<input type="text" class="form-control form-control-sm" id="code" required name="code" placeholder="Material ID" value='<?= $code; ?>' readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label for="">Material Master</label>
				</div>
				<div class="col-md-10">
					<input type="hidden" class="form-control form-control-sm" id="id" name="id" value='<?= $id; ?>'>
					<input type="text" class="form-control form-control-sm" id="nama" required name="nama" placeholder="Material Type" value='<?= $nama; ?>' readonly>
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-2">

				</div>
				<div class="col-md-5">
					<span class='text-red text-bold'>BEFORE</span>
				</div>
				<div class="col-md-5">
					<span class='text-green text-bold'>AFTER</span>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Lower Price</label>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control form-control-sm autoNumeric" id="price_ref" name="price_ref" value='<?= $price_ref; ?>' placeholder="Lower Price Before" readonly>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control form-control-sm autoNumeric" id="price_ref_high" name="price_ref_high" value='<?= $price_ref_high; ?>' placeholder="Lower Pricee After" readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Higher Price</label>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control form-control-sm autoNumeric" id="price_ref_new" name="price_ref_new" value='<?= $price_ref_new; ?>' placeholder="Higher Before" readonly>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control form-control-sm autoNumeric" id="price_ref_high_new" name="price_ref_high_new" value='<?= $price_ref_high_new; ?>' placeholder="Higher After" readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Expired Purchase</label>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control form-control-sm" id="tgl_expired" name="tgl_expired" value='<?= $tgl_expired; ?>' placeholder="Expired Purchase Before" readonly>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control form-control-sm" id="tgl_expired_new" name="tgl_expired_new" value='<?= $tgl_expired_new; ?>' placeholder="Expired Purchase After" readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Price Reference</label>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control form-control-sm autoNumeric" id="price_ref_use" name="price_ref_use" placeholder="Price Reference Before" value='<?= $price_ref_use; ?>' readonly>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control form-control-sm autoNumeric" id="price_ref_use_after" required name="price_ref_use_after" placeholder="Price Reference After" value='<?= $price_ref_new; ?>'>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Expired Reference</label>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control form-control-sm" id="tgl_expired_use" name="tgl_expired_use" value='<?= $tgl_expired_use; ?>' placeholder="Expired Price Reference Before" readonly>
				</div>
				<div class="col-md-5">
					<select id="price_ref_expired_use_after" name="price_ref_expired_use_after" class="form-control form-control-sm input-md chosen-select" required>
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
					<label>Note</label>
				</div>
				<div class="col-md-10">
					<textarea class="form-control form-control-sm" id="note" name="note" row='3' placeholder="Note" readonly><?= $note; ?></textarea>
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Action</label>
				</div>
				<div class="col-md-10">
					<select id="action_app" name="action_app" class="form-control form-control-sm input-md" required>
						<option value="1">Approve</option>
						<option value="0">Reject</option>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Reason</label>
				</div>
				<div class="col-md-10">
					<textarea class="form-control form-control-sm" id="status_reject" name="status_reject" row='3' placeholder="Reason"></textarea>
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
<?php
$id 		= (!empty($listData[0]->id_barang_stok)) ? $listData[0]->id_barang_stok : '';
$nama 		= (!empty($listData[0]->nm_barang_stok)) ? $listData[0]->nm_barang_stok : '';
$trade_name 		= (!empty($listData[0]->trade_name)) ? $listData[0]->trade_name : '';
$status_app = (!empty($listData[0]->status_app)) ? $listData[0]->status_app : '';

$price_ref_idr 		= (!empty($listData[0]->price_ref_idr)) ? $listData[0]->price_ref_idr : 0;
$price_ref_usd 		= (!empty($listData[0]->price_ref_usd)) ? $listData[0]->price_ref_usd : 0;
$price_ref_rmb 		= (!empty($listData[0]->price_ref_rmb)) ? $listData[0]->price_ref_rmb : 0;
$price_ref_high_idr 		= (!empty($listData[0]->price_ref_high_idr)) ? $listData[0]->price_ref_high_idr : 0;
$price_ref_high_usd 		= (!empty($listData[0]->price_ref_high_usd)) ? $listData[0]->price_ref_high_usd : 0;
$price_ref_high_rmb 		= (!empty($listData[0]->price_ref_high_rmb)) ? $listData[0]->price_ref_high_rmb : 0;
$price_ref_new_idr 	= (!empty($listData[0]->price_ref_new_idr)) ? $listData[0]->price_ref_new_idr : 0;
$price_ref_new_usd 	= (!empty($listData[0]->price_ref_new_usd)) ? $listData[0]->price_ref_new_usd : 0;
$price_ref_new_rmb 	= (!empty($listData[0]->price_ref_new_rmb)) ? $listData[0]->price_ref_new_rmb : 0;
$price_ref_high_new_idr 	= (!empty($listData[0]->price_ref_high_new_idr)) ? $listData[0]->price_ref_high_new_idr : 0;
$price_ref_high_new_usd 	= (!empty($listData[0]->price_ref_high_new_usd)) ? $listData[0]->price_ref_high_new_usd : 0;
$price_ref_high_new_rmb 	= (!empty($listData[0]->price_ref_high_new_rmb)) ? $listData[0]->price_ref_high_new_rmb : 0;
$price_ref_use_idr 	= (!empty($listData[0]->price_ref_use_idr)) ? $listData[0]->price_ref_use_idr : 0;
$price_ref_use_usd 	= (!empty($listData[0]->price_ref_use_usd)) ? $listData[0]->price_ref_use_usd : 0;
$price_ref_use_rmb 	= (!empty($listData[0]->price_ref_use_rmb)) ? $listData[0]->price_ref_use_rmb : 0;

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
					<label for="">Stok Name</label>
				</div>
				<div class="col-md-10">
					<input type="hidden" class="form-control" id="id" name="id" value='<?= $id; ?>'>
					<input type="text" class="form-control" id="nama" required name="nama" placeholder="Stok Name" value='<?= $nama; ?>' readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label for="">Trade Name</label>
				</div>
				<div class="col-md-10">
					<input type="text" class="form-control" id="trade_name" required name="trade_name" placeholder="Spesification" value='<?= $trade_name; ?>' readonly>
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-2">

				</div>
				<div class="col-md-5">
					<span class='text-danger text-bold'>Lower Price</span>
				</div>
				<div class="col-md-5">
					<span class='text-success text-bold'>Higher Price</span>
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
					<label>After</label>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_new_idr" name="price_ref_new_idr" value='<?= $price_ref_new_idr; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_new_usd" name="price_ref_new_usd" value='<?= $price_ref_new_usd; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_new_rmb" name="price_ref_new_rmb" value='<?= $price_ref_new_rmb; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">RMB</button>
						</span>
					</div>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_high_new_idr" name="price_ref_high_new_idr" value='<?= $price_ref_high_new_idr; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_high_new_usd" name="price_ref_high_new_usd" value='<?= $price_ref_high_new_usd; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_high_new_rmb" name="price_ref_high_new_rmb" value='<?= $price_ref_high_new_rmb; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">RMB</button>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Expired Purchase</label>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control" id="tgl_expired" name="tgl_expired" value='<?= $tgl_expired; ?>' placeholder="Expired Purchase Before" readonly>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control" id="tgl_expired_new" name="tgl_expired_new" value='<?= $tgl_expired_new; ?>' placeholder="Expired Purchase After" readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Price Reference</label>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_use_idr" name="price_ref_use_idr" value='<?= $price_ref_use_idr; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_use_usd" name="price_ref_use_usd" value='<?= $price_ref_use_usd; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_use_rmb" name="price_ref_use_rmb" value='<?= $price_ref_use_rmb; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">RMB</button>
						</span>
					</div>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_use_after_idr" name="price_ref_use_after_idr" value='<?= $price_ref_new_idr; ?>' >
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_use_after_usd" name="price_ref_use_after_usd" value='<?= $price_ref_new_usd; ?>' >
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_use_after_rmb" name="price_ref_use_after_rmb" value='<?= $price_ref_new_rmb; ?>' >
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">RMB</button>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Expired Reference</label>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control" id="tgl_expired_use" name="tgl_expired_use" value='<?= $tgl_expired_use; ?>' placeholder="Expired Price Reference Before" readonly>
				</div>
				<div class="col-md-5">
					<select id="price_ref_expired_use_after" name="price_ref_expired_use_after" class="form-control input-md chosen-select" required>
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
					<textarea class="form-control" id="note" name="note" row='3' placeholder="Note" readonly><?= $note; ?></textarea>
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Action</label>
				</div>
				<div class="col-md-10">
					<select id="action_app" name="action_app" class="form-control input-md chosen-select" required>
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
					<textarea class="form-control" id="status_reject" name="status_reject" row='3' placeholder="Reason"></textarea>
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
		$('#price_ref_use_after_idr').autoNumeric();
		$('#price_ref_use_after_usd').autoNumeric();
		$('#price_ref_use_after_rmb').autoNumeric();

		$(document).on("keyup", "#price_ref_use_after_idr", function() {
			var price_ref_use_after_idr = $(this).val();
			if (price_ref_use_after_idr == "" || price_ref_use_after_idr == null) {
				var price_ref_bew_idr = 0;
			} else {
				var price_ref_use_after_idr = price_ref_use_after_idr.split(",").join("");
				var price_ref_use_after_idr = parseFloat(price_ref_use_after_idr);
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

					$("#price_ref_use_after_usd").autoNumeric('set',(price_ref_use_after_idr / kurs_usd).toFixed(2));
					$("#price_ref_use_after_rmb").autoNumeric('set',(price_ref_use_after_idr / kurs_rmb).toFixed(2));
				}
			});
		});

		$(document).on("keyup","#price_ref_use_after_usd",function(){
			var price_ref_use_after_usd = $(this).val();
			if (price_ref_use_after_usd == "" || price_ref_use_after_usd == null) {
				var price_ref_bew_usd = 0;
			} else {
				var price_ref_use_after_usd = price_ref_use_after_usd.split(",").join("");
				var price_ref_use_after_usd = parseFloat(price_ref_use_after_usd);
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

					$("#price_ref_use_after_idr").autoNumeric('set',(price_ref_use_after_usd * kurs_usd).toFixed(2));
					$("#price_ref_use_after_rmb").autoNumeric('set',(price_ref_use_after_usd * kurs_usd / kurs_rmb).toFixed(2));
				}
			});
		});

		$(document).on("keyup","#price_ref_use_after_rmb",function(){
			var price_ref_use_after_rmb = $(this).val();
			if (price_ref_use_after_rmb == "" || price_ref_use_after_rmb == null) {
				var price_ref_bew_rmb = 0;
			} else {
				var price_ref_use_after_rmb = price_ref_use_after_rmb.split(",").join("");
				var price_ref_use_after_rmb = parseFloat(price_ref_use_after_rmb);
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

					$("#price_ref_use_after_idr").autoNumeric('set',(price_ref_use_after_rmb * kurs_rmb).toFixed(2));
					$("#price_ref_use_after_usd").autoNumeric('set',(price_ref_use_after_rmb * kurs_rmb / kurs_usd).toFixed(2));
				}
			});
		});
	});
</script>
<?php
$id 				= (!empty($listData[0]->id)) ? $listData[0]->id : '';
$kd_mesin 			= (!empty($listData[0]->kd_mesin)) ? $listData[0]->kd_mesin : '';
$kapasitas 			= (!empty($listData[0]->kapasitas)) ? $listData[0]->kapasitas : '';
$id_unit 			= (!empty($listData[0]->id_unit)) ? $listData[0]->id_unit : '';
$harga_mesin 		= (!empty($listData[0]->harga_mesin)) ? $listData[0]->harga_mesin : '';
$est_manfaat 		= (!empty($listData[0]->est_manfaat)) ? $listData[0]->est_manfaat : '';
$depresiasi_bulan	= (!empty($listData[0]->depresiasi_bulan)) ? $listData[0]->depresiasi_bulan : '';
$used_hour_month 	= (!empty($listData[0]->used_hour_month)) ? $listData[0]->used_hour_month : '';
$biaya_mesin 		= (!empty($listData[0]->biaya_mesin)) ? $listData[0]->biaya_mesin : '';

?>
<div class="box box-primary">
	<div class="box-body">
		<form id="data_form" method="post" autocomplete="off" enctype='multiple/form-data'>
			<div class="form-group row">
				<div class="col-md-2">
					<label for="">Machine Name <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-4">
					<select name="kd_mesin" id="kd_mesin" class='form-control form-control-sm chosen-select'>
						<option value="0">Select Machine</option>
						<?php
						foreach ($list_asset as $key => $value) {
							$selected = ($kd_mesin == $value['kd_asset']) ? 'selected' : '';
							echo "<option value='" . $value['kd_asset'] . "' " . $selected . ">" . strtoupper($value['nm_asset']) . "</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Kapasitas</label>
				</div>
				<div class="col-md-4">
					<input type="hidden" id="id" name="id" value='<?= $id; ?>'>
					<input type="text" class="form-control" id="kapasitas" name="kapasitas" value='<?= $kapasitas; ?>' placeholder="Kapasitas">
				</div>
				<div class="col-md-2">
					<label>Unit Measurement</label>
				</div>
				<div class="col-md-4">
					<select id="id_unit" name="id_unit" class="form-control input-md chosen-select">
						<option value="0">Select An Option</option>
						<?php foreach ($satuan as $value) {
							$sel = ($value->id_unit == $id_unit) ? 'selected' : '';
						?>
							<option value="<?= $value->id_unit; ?>" <?= $sel; ?>><?= strtoupper($value->nm_unit) ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Machine Price</label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control maskM getDep" id="harga_mesin" name="harga_mesin" value='<?= $harga_mesin; ?>' placeholder="Machine Price">
				</div>
				<div class="col-md-2">
					<label>Used Estimation /Year</label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control maskM getDep" id="est_manfaat" name="est_manfaat" value='<?= $est_manfaat; ?>' placeholder="Used Estimation /Year">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Depresiasi /Month</label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control maskM" id="depresiasi_bulan" name="depresiasi_bulan" value='<?= $depresiasi_bulan; ?>' placeholder="Depresiasi /Month" readonly>
				</div>
				<div class="col-md-2">
					<label>Used Hour/Month</label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control maskM getDep" id="used_hour_month" name="used_hour_month" value='<?= $used_hour_month; ?>' placeholder="Used Hour/Month">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Machine Price /Hour</label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control maskM" id="biaya_mesin" name="biaya_mesin" value='<?= $biaya_mesin; ?>' placeholder="Machine Price /Hour" readonly>
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
			dropdownParent: $('#dialog-popup'),
			selectOnClose: true,
			width: '100%'
		});
		$('.maskM').autoNumeric();
	});
</script>
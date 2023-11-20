<?php
$kurs_rmb = $gaji_pokok->kurs_rmb;
if($kurs_rmb == 0 || $kurs_rmb == null){
	$kurs_rmb = 1;
}
$kurs = $gaji_pokok->kurs;
if($kurs == 0 || $kurs == null){
	$kurs = 1;
}
?>

<div class="box box-primary">
	<div class="box-body">
		<form id="data-form" method="post"><br>
			<div class="col-4">
				<div class="form-group">
					<label for="">Gaji Pokok</label>
					<input type="text" name="" id="" class="form-control form-control-sm text-right gaji_pokok" step="0.01" value="<?= (isset($gaji_pokok)) ? $gaji_pokok->gaji_pokok : null ?>">
				</div>
			</div>
			<div class="col-12">
				<div class='box box-info'>
					<div class='box-header'>
						<h3 class='box-title'>A. Salary Direct Man Power</h3>
					</div>
					<div class='box-body hide_header'>
						<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
							<thead>
								<tr class='bg-blue'>
									<th class="text-center">#</th>
									<th class="text-center">Salary Direct Manpower</th>
									<th class="text-center">Standar</th>
									<th class="text-center">Nominal (Rp)</th>
									<th class="text-center">Keterangan</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody class="list_komp_sdmp">
								<tr>
									<td class="text-center">1</td>
									<td class="text-center">THR</td>
									<td class="text-center">Gaji Pokok / 12</td>
									<td class="text-center"><?= number_format($gaji_pokok->gaji_pokok / 12, 2) ?></td>
									<td class="text-center">Per Bulan</td>
									<td class="text-center">

									</td>
								</tr>
								<tr>
									<td class="text-center">2</td>
									<td class="text-center">Cuti + Sakit</td>
									<td class="text-center">Gaji Pokok / 12</td>
									<td class="text-center"><?= number_format($gaji_pokok->gaji_pokok / 12, 2) ?></td>
									<td class="text-center">Per Bulan</td>
									<td class="text-center">

									</td>
								</tr>
								<?php
								$val_sdmp = (($gaji_pokok->gaji_pokok / 12) * 2);
								$x = 2;
								foreach ($komp_sdmp as $list_komp) :
									$x++;
									$nominal = $list_komp->nominal;
									$val_sdmp += $nominal;
								?>
									<tr>
										<td class="text-center"><?= $x ?></td>
										<td class="text-center"><?= $list_komp->nm_komp ?></td>
										<td class="text-center"><?= $list_komp->standar ?>%</td>
										<td class="text-center"><?= number_format($nominal, 2) ?></td>
										<td class="text-center"><?= $list_komp->keterangan ?></td>
										<td class="text-center">
											<button type="button" class="btn btn-sm btn-danger del_komp" data-id="<?= $list_komp->id ?>" data-tipe="1">
												<i class="fa fa-trash"></i>
											</button>
										</td>
									</tr>

								<?php endforeach;
								?>
							</tbody>
							<tbody>
								<tr>
									<td></td>
									<td>
										<input type="text" name="" id="" class="form-control form-control-sm nm_komp_1" placeholder="Nama Komponen">
									</td>
									<td>
										<input type="text" name="" id="" class="form-control form-control-sm text-right val_standar standar_1" step="0.01" data-tipe="1">
									</td>
									<td>
										<input type="text" name="" id="" class="form-control form-control-sm input_nominal val_nominal text-right nominal_1" step="0.01" data-tipe="1">
									</td>
									<td>
										<input type="text" name="keterangan_sdmp" id="" class="form-control form-control-sm keterangan_1" placeholder="Keterangan">
									</td>
									<td class="text-center">
										<button type="button" class="btn btn-sm btn-success add_komp" data-tipe="1">Add <i class="fa fa-plus"></i></button>
									</td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<th colspan="3" class="text-right">Total</th>
									<td class="text-center ttl_sdmp"><?= number_format($val_sdmp, 2) ?></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class='box box-info'>
					<div class='box-header'>
						<h3 class='box-title'>B. BPJS</h3>
					</div>
					<div class='box-body hide_header'>
						<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
							<thead>
								<tr class='bg-blue'>
									<th class='text-center'>#</th>
									<th class='text-center'>BPJS</th>
									<th class='text-center'>Standar</th>
									<th class='text-center'>Nominal (Rp)</th>
									<th class='text-center'>Keterangan</th>
									<th class='text-center'>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$val_bpjs = 0;
								$x = 0;
								foreach ($komp_bpjs as $list_komp) :
									$x++;
									$nominal = $list_komp->nominal;
									$val_bpjs += $nominal;
								?>
									<tr>
										<td class="text-center"><?= $x ?></td>
										<td class="text-center"><?= $list_komp->nm_komp ?></td>
										<td class="text-center"><?= $list_komp->standar ?>%</td>
										<td class="text-center"><?= number_format($nominal, 2) ?></td>
										<td class="text-center"><?= $list_komp->keterangan ?></td>
										<td class="text-center">
											<button type="button" class="btn btn-sm btn-danger del_komp" data-id="<?= $list_komp->id ?>" data-tipe="2">
												<i class="fa fa-trash"></i>
											</button>
										</td>
									</tr>

								<?php endforeach;
								?>
							</tbody>
							<tbody>
								<tr>
									<td></td>
									<td>
										<input type="text" name="" id="" class="form-control form-control-sm nm_komp_2" placeholder="Nama Komponen">
									</td>
									<td>
										<input type="text" name="" id="" class="form-control form-control-sm text-right val_standar standar_2" step="0.01" data-tipe="2">
									</td>
									<td>
										<input type="text" name="" id="" class="form-control form-control-sm input_nominal val_nominal text-right nominal_2" step="0.01" data-tipe="2">
									</td>
									<td>
										<input type="text" name="keterangan_sdmp" id="" class="form-control form-control-sm keterangan_2" placeholder="Keterangan">
									</td>
									<td class="text-center">
										<button type="button" class="btn btn-sm btn-success add_komp" data-tipe="2">Add <i class="fa fa-plus"></i></button>
									</td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<th colspan="3" class="text-right">Total</th>
									<td class="text-center ttl_bpjs"><?= number_format($val_bpjs, 2) ?></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class='box box-info'>
					<div class='box-header'>
						<h3 class='box-title'>C. Biaya Lain-Lain</h3>
					</div>
					<div class='box-body hide_header'>
						<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
							<thead>
								<tr class='bg-blue'>
									<th class="text-center">#</th>
									<th class="text-center">Biaya Lain-lain</th>
									<th class="text-center">Standar</th>
									<th class="text-center">Periode (Bulan)</th>
									<th class="text-center">Nominal (Rp)</th>
									<th class="text-center">Keterangan</th>
									<th class="text-center">Harga/Pcs</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody class="list_bll">
								<?php
								$val_bll = 0;
								$x = 0;
								foreach ($komp_bll as $list_komp) :
									$x++;
									$nominal = $list_komp->nominal;
									$val_bll += $nominal;
								?>
									<tr>
										<td class="text-center"><?= $x ?></td>
										<td class="text-center"><?= $list_komp->nm_komp ?></td>
										<td class="text-center"><?= number_format($list_komp->standar, 2) ?></td>
										<td class="text-center">
											<input type="number" class="form-control form-control-sm text-right periode_bulan_<?= $list_komp->id ?>" name="periode_bulan_<?= $list_komp->id ?>" id="" value="<?= $list_komp->periode_bulan ?>" data-id="<?= $list_komp->id ?>" readonly>
										</td>
										<td class="text-center"><?= number_format($nominal, 2) ?></td>
										<td class="text-center"><?= $list_komp->keterangan ?></td>
										<td class="text-center">
											<input type="text" name="harga_pcs_<?= $list_komp->id ?>" id="" class="form-control form-control-sm text-right ubah_harga_pcs input_nominal text-right harga_pcs_<?= $list_komp->id ?>" value="<?= $list_komp->harga_pcs ?>" data-id="<?= $list_komp->id ?>" readonly>
										</td>
										<td class="text-center">
											<button type="button" class="btn btn-sm btn-danger del_komp" data-id="<?= $list_komp->id ?>" data-tipe="3">
												<i class="fa fa-trash"></i>
											</button>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
							<tbody>
								<tr>
									<td></td>
									<td>
										<input type="text" name="" id="" class="form-control form-control-sm nm_komp_3" placeholder="Nama Komponen">
									</td>
									<td>
										<input type="text" name="" id="" class="form-control form-control-sm text-right hitung_nominal_bll standar_3" step="0.01" data-tipe="3">
									</td>
									<td>
										<input type="text" name="" id="" class="form-control form-control-sm hitung_nominal_bll input_nominal periode_bulan_3 text-right" placeholder="Periode Bulan" data-tipe="3">
									</td>
									<td>
										<input type="text" name="" id="" class="form-control form-control-sm input_nominal text-right nominal_3" step="0.01" readonly>
									</td>
									<td>
										<input type="text" name="keterangan_bpjs" id="" class="form-control form-control-sm keterangan_3" placeholder="Keterangan">
									</td>
									<td>
										<input type="text" name="" id="" class="form-control form-control-sm input_nominal text-right hitung_nominal_bll harga_pcs_3" data-tipe="3">
									</td>
									<td class="text-center">
										<button type="button" class="btn btn-sm btn-success add_komp" data-tipe="3">Add <i class="fa fa-plus"></i></button>
									</td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<th colspan="4" class="text-right">Total</th>
									<td class="text-center ttl_bll"><?= number_format($val_bll, 2) ?></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<br>
				<div class="form-group row">
					<div class="col-md-2">
						<label for="customer">Kurs $</label>
					</div>
					<div class="col-md-2">
						<input type="hidden" name="" class="ttl_all" value="<?= ($val_sdmp + $val_bpjs + $val_bll) ?>">
						<input type="text" name="rate_dollar" id="rate_dollar" class='form-control input-md text-right input_nominal text-right summaryCal ubah_kurs' value="<?= $gaji_pokok->kurs; ?>">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2">
						<label for="customer">Kurs RMB</label>
					</div>
					<div class="col-md-2">
						<!-- <input type="hidden" name="" class="ttl_all" value=""> -->
						<input type="text" name="rate_rmb" id="rate_rmb" class='form-control input-md text-right input_nominal text-right summaryCal ubah_kurs_rmb' value="<?= $gaji_pokok->kurs_rmb; ?>">
					</div>
				</div>
				<hr>
				<div class="form-group row">
					<div class="col-md-2">
						<label for="customer">Upah /Bulan $</label>
					</div>
					<div class="col-md-2">
						<input type="text" name="upah_per_bulan_dollar" id="upah_per_bulan_dollar" class='form-control input-md text-right input_nominal' readonly value="<?= (($gaji_pokok->gaji_pokok + ($val_sdmp + $val_bpjs + $val_bll)) / $gaji_pokok->kurs) ?>">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2">
						<label for="customer">Upah /Jam $</label>
					</div>
					<div class="col-md-2">
						<input type="text" name="upah_per_jam_dollar" id="upah_per_jam_dollar" class='form-control input-md text-right input_nominal' readonly value="<?= ((($gaji_pokok->gaji_pokok + ($val_sdmp + $val_bpjs + $val_bll)) / $gaji_pokok->kurs) / 173) ?>">
					</div>
				</div>
				<hr>
				<div class="form-group row">
					<div class="col-md-2">
						<label for="customer">Upah /Bulan RMB</label>
					</div>
					<div class="col-md-2">
						<input type="text" name="upah_per_bulan_rmb" id="upah_per_bulan_rmb" class='form-control input-md text-right input_nominal' readonly value="<?= (($gaji_pokok->gaji_pokok + ($val_sdmp + $val_bpjs + $val_bll)) / $kurs_rmb) ?>">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2">
						<label for="customer">Upah /Jam RMB</label>
					</div>
					<div class="col-md-2">
						<input type="text" name="upah_per_jam_rmb" id="upah_per_jam_rmb" class='form-control input-md text-right input_nominal' readonly value="<?= ((($gaji_pokok->gaji_pokok + ($val_sdmp + $val_bpjs + $val_bll)) / $kurs_rmb) / 173) ?>">
					</div>
				</div>
				<hr>
				<div class="form-group row">
					<div class="col-md-2">
						<label for="customer">Upah /Bulan (Rp)</label>
					</div>
					<div class="col-md-2">
						<input type="text" name="upah_per_bulan" id="upah_per_bulan" class='form-control input-md text-right input_nominal text-right ' readonly value="<?= ($gaji_pokok->gaji_pokok + ($val_sdmp + $val_bpjs + $val_bll)) ?>">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2">
						<label for="customer">Upah /Jam (Rp)</label>
					</div>
					<div class="col-md-2">
						<input type="text" name="upah_per_jam" id="upah_per_jam" class='form-control input-md text-right input_nominal text-right' readonly value="<?= (($gaji_pokok->gaji_pokok + ($val_sdmp + $val_bpjs + $val_bll)) / 173) ?>">
					</div>
				</div>
				<button type="button" class="btn btn-danger" style=' margin-left:5px;' name="back" id="back"><i class="fa fa-reply"></i> Back</button>
				<a href="javascript:void(0);" class="btn btn-primary" onclick="window.location.reload(true);"><i class="fa fa-save"></i> Save</a>
			</div>

		</form>
	</div>
</div>

<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<style media="screen">
	.datepicker {
		cursor: pointer;
		padding-left: 12px;
	}
</style>
<script type="text/javascript">
	//$('#input-kendaraan').hide();
	var base_url = '<?php echo base_url(); ?>';
	var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

	function hitung_bll() {
		// alert("awdawd");
		var periode_bulan = $(".periode_bulan_3").val();
		var periode_bulan = parseInt(periode_bulan);

		var harga_pcs = $(".harga_pcs_3").val();
		var harga_pcs = harga_pcs.split(",").join("");
		var harga_pcs = parseFloat(harga_pcs);

		var standar = $(".standar_3").val();

		var nominal = 0;
		if (periode_bulan > 0 && harga_pcs > 0 && standar > 0) {
			var nominal = parseFloat((harga_pcs * standar) / periode_bulan);
		}

		$(".nominal_3").autoNumeric('set', nominal);
	}

	$(document).ready(function() {
		$('.chosen-select').select2({
			// minimumResultsForSearch: -1,
			placeholder: 'Choose one',
			dropdownPosition: 'below',
			width: "100%"
		});
		$(".gaji_pokok").autoNumeric();
		$(".input_nominal").autoNumeric();
		$('.chosen-select').select2();
		$(".datepicker").datepicker();
		$(".autoNumeric2").autoNumeric('init', {
			mDec: '2',
			aPad: false
		});

		$(document).on("change", ".ubah_kurs_rmb", function() {
			var kurs = $(this).val();
			var kurs = kurs.split(",").join("");
			var kurs = parseFloat(kurs);

			var ttl_all = parseFloat($(".ttl_all").val());

			var gaji_pokok = $(".gaji_pokok").val();
			var gaji_pokok = gaji_pokok.split(",").join("");
			var gaji_pokok = parseFloat(gaji_pokok);

			$.ajax({
				type: "POST",
				url: base_url + active_controller + "/ubah_kurs_rmb",
				data: {
					"kurs": kurs,
					"ttl_all": ttl_all,
					"gaji_pokok": gaji_pokok
				},
				cache: false,
				dataType: "JSON",
				success: function(result) {
					$("#upah_per_bulan_rmb").autoNumeric('set', result.upah_per_bulan_rmb);
					$("#upah_per_jam_rmb").autoNumeric('set', result.upah_per_jam_rmb);
				}
			});
		});

		$(document).on("change", ".hitung_nominal_bll", function() {
			var tipe = $(this).data('tipe');

			var standar = $(".standar_" + tipe).val();
			if (standar == "") {
				var standar = 0;
			}

			var periode = $(".periode_bulan_" + tipe).val();
			if (periode == "") {
				var periode = 0;
			}

			var harga_pcs = $(".harga_pcs_" + tipe).val();
			if (harga_pcs == "") {
				var harga_pcs = 0;
			} else {
				var harga_pcs = harga_pcs.split(",").join("");
				var harga_pcs = parseFloat(harga_pcs);
			}

			// alert(standar);
			// alert(periode);
			// alert(harga_pcs);

			var nominal = 0;
			if (harga_pcs > 0 && standar > 0 && periode > 0) {
				var nominal = ((harga_pcs * standar) / periode);
			}

			$(".nominal_" + tipe).autoNumeric("set", nominal);
		});

		$(document).on("change", ".val_standar", function() {
			var tipe = $(this).data('tipe');
			var standar = parseFloat($(".standar_" + tipe).val());

			var gaji_pokok = $(".gaji_pokok").val();
			var gaji_pokok = gaji_pokok.split(",").join("");
			var gaji_pokok = parseFloat(gaji_pokok);

			var nominal = parseFloat(gaji_pokok * standar / 100);

			$(".nominal_" + tipe).autoNumeric('set', nominal);
		});

		$(document).on("change", ".val_nominal", function() {
			var tipe = $(this).data('tipe');

			var nominal = $(".nominal_" + tipe).val();
			var nominal = nominal.split(",").join("");
			var nominal = parseFloat(nominal);

			var gaji_pokok = $(".gaji_pokok").val();
			var gaji_pokok = gaji_pokok.split(",").join("");
			var gaji_pokok = parseFloat(gaji_pokok);

			var standar = parseFloat((nominal / gaji_pokok * 100).toFixed(2));

			$(".standar_" + tipe).val(standar);
		});

		$(document).on("change", ".check_komp", function() {
			var komp = $(this).val();
			var tipe = $(this).data('tipe');

			var gaji_pokok = $(".gaji_pokok").val();
			var gaji_pokok = gaji_pokok.split(",").join("");
			var gaji_pokok = parseFloat(gaji_pokok);

			$.ajax({
				type: "POST",
				url: base_url + active_controller + '/check_komp',
				data: {
					"komp": komp,
					"gaji_pokok": gaji_pokok
				},
				cache: false,
				dataType: "JSON",
				success: function(result) {
					// console.log(result);
					$(".standar_" + tipe).val(result.data.std_val);
					$(".keterangan_" + tipe).val(result.data.keterangan);
					if (tipe != 3) {
						$(".nominal_" + tipe).val(result.nominal);
						$(".nominal_" + tipe).autoNumeric('set', result.nominal);
					}
				}
			});
		});

		$(document).on("click", ".add_komp", function() {
			var tipe = $(this).data('tipe');

			var nm_komp = $(".nm_komp_" + tipe).val();

			var standar = $(".standar_" + tipe).val();
			var keterangan = $(".keterangan_" + tipe).val();

			var nominal = $(".nominal_" + tipe).val();
			var nominal = nominal.split(",").join("");
			var nominal = parseFloat(nominal);

			var gaji_pokok = $(".gaji_pokok").val();
			var gaji_pokok = gaji_pokok.split(",").join("");
			var gaji_pokok = parseFloat(gaji_pokok);

			var harga_pcs = 0;
			var periode_bulan = 0;
			if (tipe == 3) {
				var harga_pcs = $(".harga_pcs_" + tipe).val();
				var harga_pcs = harga_pcs.split(",").join("");
				var harga_pcs = parseFloat(harga_pcs);

				var periode_bulan = $(".periode_bulan_" + tipe).val();
			}

			if (nm_komp !== "" || nominal > 0) {
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
						$.ajax({
							type: "POST",
							url: base_url + active_controller + '/add_komp',
							data: {
								"tipe": tipe,
								"gaji_pokok": gaji_pokok,
								"nm_komp": nm_komp,
								"standar": standar,
								"nominal": nominal,
								"keterangan": keterangan,
								"harga_pcs": harga_pcs,
								"periode_bulan": periode_bulan
							},
							cache: false,
							dataType: "JSON",
							success: function(results) {
								if (results.valid == 1) {
									new swal({
										title: "Success",
										text: "Komponen telah berhasil di add",
										type: "success"
									});
									window.location.reload(true);
								} else {
									new swal({
										title: "Failed",
										text: "Komponen gagal di add",
										type: "danger"
									});
									window.location.reload(true);
								}
							}
						});
					}
				});
			} else {
				alert("Pastikan kolom Nama Komponen dan Nominal sudah terisi !!");
			}
		});

		$(document).on("click", ".del_komp", function() {
			var id = $(this).data('id');
			var tipe = $(this).data('tipe');

			var gaji_pokok = $(".gaji_pokok").val();
			var gaji_pokok = gaji_pokok.split(",").join("");
			var gaji_pokok = parseFloat(gaji_pokok);

			new swal({
				title: "Hapus Data",
				text: "Data ini akan terhapus !",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Hapus",
				cancelButtonText: "Batal",
				closeOnCancel: false
			}).then((hasil) => {
				if (hasil.isConfirmed) {
					$.ajax({
						type: "POST",
						url: base_url + active_controller + '/del_komp',
						data: {
							"id": id,
							"tipe": tipe,
							"gaji_pokok": gaji_pokok
						},
						cache: false,
						dataType: "JSON",
						success: function(results) {
							if (results.valid == 1) {
								new swal({
									title: "Success",
									text: "Komponen telah berhasil di hapus",
									type: "success"
								});
								window.location.reload(true);
							} else {
								new swal({
									title: "Failed",
									text: "Komponen gagal di hapus",
									type: "danger"
								});
								window.location.reload(true);
							}
						}
					});
				}
			});
		});

		$(document).on("change", ".ubah_kurs", function() {
			var kurs = $(this).val();
			var kurs = kurs.split(",").join("");
			var kurs = parseFloat(kurs);

			var ttl_all = parseFloat($(".ttl_all").val());

			var gaji_pokok = $(".gaji_pokok").val();
			var gaji_pokok = gaji_pokok.split(",").join("");
			var gaji_pokok = parseFloat(gaji_pokok);

			$.ajax({
				type: "POST",
				url: base_url + active_controller + "/ubah_kurs",
				data: {
					"kurs": kurs,
					"ttl_all": ttl_all,
					"gaji_pokok": gaji_pokok
				},
				cache: false,
				dataType: "JSON",
				success: function(result) {
					$("#upah_per_bulan_dollar").autoNumeric('set', result.upah_per_bulan_dollar);
					$("#upah_per_jam_dollar").autoNumeric('set', result.upah_per_jam_dollar);
				}
			});
		});

		$(document).on("change", ".ubah_periode_bulan", function() {
			var id = $(this).data('id');

			var gaji_pokok = $(".gaji_pokok").val();
			var gaji_pokok = gaji_pokok.split(",").join("");
			var gaji_pokok = parseFloat(gaji_pokok);

			var periode_bulan = $(this).val();
			var periode_bulan = parseFloat(periode_bulan);
			$.ajax({
				type: "POST",
				url: base_url + active_controller + '/ubah_periode_bulan',
				data: {
					"id": id,
					"periode_bulan": periode_bulan,
					"gaji_pokok": gaji_pokok
				},
				cache: false,
				dataType: "JSON",
				success: function(result) {
					$(".list_bll").html(result.hasil);
					$(".ttl_bll").html(result.ttl);
					$(".input_nominal text-right").autoNumeric();
				}
			});
		});

		$(document).on("change", ".ubah_harga_pcs", function() {
			var id = $(this).data('id');

			var gaji_pokok = $(".gaji_pokok").val();
			var gaji_pokok = gaji_pokok.split(",").join("");
			var gaji_pokok = parseFloat(gaji_pokok);

			var harga_pcs = $(this).val();
			var harga_pcs = harga_pcs.split(",").join("");
			var harga_pcs = parseFloat(harga_pcs);

			$.ajax({
				type: "POST",
				url: base_url + active_controller + '/ubah_harga_pcs',
				data: {
					"id": id,
					"harga_pcs": harga_pcs,
					"gaji_pokok": gaji_pokok
				},
				cache: false,
				dataType: "JSON",
				success: function(result) {
					$(".list_bll").html(result.hasil);
					$(".ttl_bll").html(result.ttl);
					$(".input_nominal text-right").autoNumeric();
				}
			});
		});

		$(document).on("change", ".gaji_pokok", function() {
			var gaji_pokok = $(this).val();
			var gaji_pokok = gaji_pokok.split(',').join('');
			var gaji_pokok = parseFloat(gaji_pokok);

			$.ajax({
				type: "POST",
				url: base_url + active_controller + "/upd_gaji_pokok",
				data: {
					"gaji_pokok": gaji_pokok
				},
				cache: false,
				dataType: "JSON",
				success: function(result) {

				}
			});
		});

		//add part
		$(document).on('click', '.addPart', function() {
			var get_id = $(this).parent().parent().attr('id');
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
					$('.chosen_select').select2({
						width: '100%'
					});
					$('.autoNumeric2').autoNumeric('init', {
						mDec: '2',
						aPad: false
					});
					swal.close();
				},
				error: function() {
					swal({
						title: "Error Message !",
						text: 'Connection Time Out. Please try again..',
						type: "warning",
						timer: 3000
					});
				}
			});
		});

		$(document).on('click', '.addPart2', function() {
			var get_id = $(this).parent().parent().attr('id');
			var split_id = get_id.split('_');
			var id = parseInt(split_id[1]) + 1;
			var id_bef = split_id[1];

			$.ajax({
				url: base_url + active_controller + '/get_add2/' + id,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data) {
					$("#add2_" + id_bef).before(data.header);
					$("#add2_" + id_bef).remove();
					$('.chosen_select').select2({
						width: '100%'
					});
					$('.autoNumeric2').autoNumeric('init', {
						mDec: '2',
						aPad: false
					});
					swal.close();
				},
				error: function() {
					swal({
						title: "Error Message !",
						text: 'Connection Time Out. Please try again..',
						type: "warning",
						timer: 3000
					});
				}
			});
		});

		$(document).on('click', '.addPart3', function() {
			var get_id = $(this).parent().parent().attr('id');
			var split_id = get_id.split('_');
			var id = parseInt(split_id[1]) + 1;
			var id_bef = split_id[1];

			$.ajax({
				url: base_url + active_controller + '/get_add3/' + id,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data) {
					$("#add3_" + id_bef).before(data.header);
					$("#add3_" + id_bef).remove();
					$('.chosen_select').select2({
						width: '100%'
					});
					$('.autoNumeric2').autoNumeric('init', {
						mDec: '2',
						aPad: false
					});
					swal.close();
				},
				error: function() {
					swal({
						title: "Error Message !",
						text: 'Connection Time Out. Please try again..',
						type: "warning",
						timer: 3000
					});
				}
			});
		});

		//delete part
		$(document).on('click', '.delPart', function() {
			var get_id = $(this).parent().parent().attr('class');
			$("." + get_id).remove();
		});

		//back
		$(document).on('click', '#back', function() {
			window.location.href = base_url + active_controller;
		});

		$(document).on('keyup', '.summaryCal', function() {
			// get_summary();
		});

		$('#save').click(function(e) {
			e.preventDefault();

			swal({
					title: "Are you sure?",
					text: "You will not be able to process again this data!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Yes, Process it!",
					cancelButtonText: "No, cancel process!",
					closeOnConfirm: true,
					closeOnCancel: false
				},
				function(isConfirm) {
					if (isConfirm) {
						var formData = new FormData($('#data-form')[0]);
						var baseurl = base_url + active_controller + '/add'
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
									swal({
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
										swal({
											title: "Save Failed!",
											text: data.pesan,
											type: "warning",
											timer: 3000,
										});
									} else {
										swal({
											title: "Save Failed!",
											text: data.pesan,
											type: "warning",
											timer: 3000,
										});
									}

								}
							},
							error: function() {

								swal({
									title: "Error Message !",
									text: 'An Error Occured During Process. Please try again..',
									type: "warning",
									timer: 3000,
								});
							}
						});
					} else {
						swal("Cancelled", "Data can be process again :)", "error");
						return false;
					}
				});
		});

	});

	function get_summary() {
		var rate_dollar = getNum($('#rate_dollar').val().split(",").join(""));

		let summary_direct = 0
		let summary_bpjs = 0
		let summary_lainnya = 0

		$(".nilaiDirect").each(function() {
			summary_direct += getNum($(this).val().split(",").join(""));
		});
		$(".nilaiBPJS").each(function() {
			summary_bpjs += getNum($(this).val().split(",").join(""));
		});
		$(".nilaiLain").each(function() {
			summary_lainnya += getNum($(this).val().split(",").join(""));
		});

		let sum_semua = summary_direct + summary_bpjs + summary_lainnya
		var upah_per_jam = sum_semua / 173

		let sum_semua_usd = 0
		let upah_per_jam_usd = 0
		if (rate_dollar > 0) {
			sum_semua_usd = sum_semua / rate_dollar
			upah_per_jam_usd = sum_semua_usd / 173
		}

		$('#upah_per_bulan_dollar').val(number_format(sum_semua_usd, 2));
		$('#upah_per_jam_dollar').val(number_format(upah_per_jam_usd, 2));

		$('#upah_per_bulan').val(number_format(sum_semua));
		$('#upah_per_jam').val(number_format(upah_per_jam));

		$('#total_direct').val(number_format(summary_direct))
		$('#total_bpjs').val(number_format(summary_bpjs))
		$('#total_biaya_lain').val(number_format(summary_lainnya))
	}

	function number_format(number, decimals, dec_point, thousands_sep) {
		// Strip all characters but numerical ones.
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function(n, prec) {
				var k = Math.pow(10, prec);
				return '' + Math.round(n * k) / k;
			};
		// Fix for IE parseFloat(0.55).toFixed(0) = 0;
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);
	}
</script>
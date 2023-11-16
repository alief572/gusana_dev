<style>
	/* Add custom CSS for datepicker z-index */
	.ui-datepicker {
		z-index: 9999 !important;
	}
</style>
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"></h3>
	</div>
	<div class="box-body">
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Nama Asset <span class='text-red'>*</span></b></label>
			<div class='col-sm-4'>
				<?php
				echo form_input(array('id' => 'nm_asset', 'name' => 'nm_asset', 'class' => 'form-control input-md', 'autocomplete' => 'off', 'placeholder' => 'Nama Asset'));
				?>
			</div>
			<label class='label-control col-sm-2'><b>Kategori <span class='text-red'>*</span></b></label>
			<div class='col-sm-4'>
				<select name='category' id='category' class='form-control input-md chosen-select'>
					<option value='0'>Pilih Kategori</option>
					<?php
					foreach ($list_catg as $val => $valx) {
						// $sexd	= ($valx['id'] == 2)?'selected':'';
						$sexd	= "";
						echo "<option value='" . $valx['id'] . "' " . $sexd . ">" . strtoupper($valx['nm_category']) . "</option>";
					}
					?>
				</select>
			</div>
		</div>
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Department <span class='text-red'>*</span></b></label>
			<div class='col-sm-4'>
				<select name='lokasi_asset' id='lokasi_asset' class='form-control input-md chosen-select'>
					<option value='0'>Pilih Department</option>
					<?php
					foreach ($list_dept as $val => $valx) {
						// $sexd	= ($valx['nm_dept'] == 'UMUM')?'selected':'';
						$sexd	= "";
						echo "<option value='" . $valx['id_departemen'] . "' " . $sexd . ">" . strtoupper($valx['nm_departemen']) . "</option>";
					}
					?>
				</select>
			</div>
			<label class='label-control col-sm-2'><b>Cost Center <span class='text-red'>*</span></b></label>
			<div class='col-sm-4'>
				<select name='cost_center' id='cost_center' class='form-control input-md chosen-select'></select>
			</div>
		</div>
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Nilai Asset <span class='text-red'>*</span></b></label>
			<div class='col-sm-4'>
				<?php
				echo form_input(array('id' => 'nilai_asset', 'name' => 'nilai_asset', 'class' => 'form-control input-md', 'autocomplete' => 'off', 'placeholder' => 'Nilai Asset', 'data-decimal' => '.', 'data-thousand' => '', 'data-precision' => '0', 'data-allow-zero' => false));
				?>
			</div>
			<label class='label-control col-sm-2'><b>Jangka Waktu <span class='text-red'>*</span></b></label>
			<div class='col-sm-4'>
				<select name='depresiasi' id='depresiasi' class='form-control input-md chosen-select'>
					<option value='0'>Pilih Jangka Waktu</option>
					<?php
					for ($a = 1; $a <= 16; $a++) {
						// $sexd	= ($a == 4)?'selected':'';
						$sexd	= "";
						echo "<option value='" . $a . "' " . $sexd . ">" . $a . " Tahun</option>";
					}
					?>
				</select>
			</div>
		</div>
		<div class='form-group row'>

			<label class='label-control col-sm-2'><b>Qty <span class='text-red'>*</span></b></label>
			<div class='col-sm-4'>
				<?php
				echo form_input(array('id' => 'qty', 'name' => 'qty', 'class' => 'form-control input-md', 'autocomplete' => 'off', 'placeholder' => 'Qty Assets', 'data-decimal' => '.', 'data-thousand' => '', 'data-precision' => '0', 'data-allow-zero' => false));
				?>
			</div>
			<label class='label-control col-sm-2'><b>Dipresiasi Perbulan</b></label>
			<div class='col-sm-4'>
				<?php
				echo form_input(array('id' => 'value', 'name' => 'value', 'class' => 'form-control input-md', 'autocomplete' => 'off', 'placeholder' => 'Dipresiasi Perbulan', 'readonly' => 'readonly', 'data-decimal' => '.', 'data-thousand' => '', 'data-precision' => '0', 'data-allow-zero' => false));
				?>
			</div>
		</div>
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Tanggal Perolehan <span class='text-red'>*</span></b></label>
			<div class='col-sm-4'>
				<?php
				echo form_input(array('id' => 'tanggal', 'name' => 'tanggal', 'class' => 'form-control input-md', 'autocomplete' => 'off', 'placeholder' => 'Tanggal', 'readonly' => 'readonly'));
				?>
			</div>
		</div>

		<?php
		echo form_button(array('type' => 'button', 'class' => 'btn btn-md btn-primary', 'value' => 'save', 'content' => 'Save', 'id' => 'simpan-bro', 'style' => 'width:100px; float:right;')) . ' ';
		?>
	</div>
</div>

<style media="screen">
	.select2-container {
		box-sizing: border-box;
		display: inline-block;
		margin: 0;
		position: relative;
		vertical-align: middle;
		width: 100% !important;
	}
</style>

<script>
	function addCommas(number) {
		number = parseFloat(number);
		number = number.toFixed(2);
		return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	$(document).ready(function() {
		$('.chosen-select').select2({
			// minimumResultsForSearch: -1,
			placeholder: 'Choose one',
			dropdownParent: $('#ModalView'),
			dropdownPosition: 'below',
			width: "100%"
		});
		$('#nilai_asset').autoNumeric();
		$('#qty').autoNumeric();
		$('#value').autoNumeric({
			'decimalPlaces': 2
		});

		$('#tanggal').datepicker({
			format: 'yyyy-mm-dd'
			// minDate: 0
		});
	});

	$(document).on('keyup', '#nilai_asset', function() {
		var nilai_asset = $('#nilai_asset').val();
		var qty_asset = $('#qty').val();
		var depresiasi = parseFloat($('#depresiasi').val());
		var nilai = parseFloat(nilai_asset.split(',').join(''));

		var per_bulan = (nilai / (depresiasi * 12));
		if (isNaN(per_bulan)) {
			var per_bulan = 0;
		}
		var nilai = addCommas(per_bulan);
		$('#value').val(nilai);
	});

	$(document).on('change', '#depresiasi', function() {
		var nilai_asset = $('#nilai_asset').val();
		var qty_asset = $('#qty').val();
		var depresiasi = parseFloat($('#depresiasi').val());
		var nilai = parseFloat(nilai_asset.split(',').join(''));

		var per_bulan = (nilai / (depresiasi * 12));
		if (isNaN(per_bulan)) {
			var per_bulan = 0;
		}
		var nilai = addCommas(per_bulan);
		$('#value').val(nilai);
	});

	$(document).on('change', '#lokasi_asset', function() {
		var nilai_asset = $(this).val();
		var cost_center = $("#cost_center");

		$.ajax({
			url: base_url + 'index.php/' + thisController + '/list_center/' + nilai_asset,
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data) {
				$(cost_center).html(data.option).trigger("chosen:updated");
				new swal.close();
			},
			error: function() {
				new swal({
					title: "Error Message !",
					text: 'Connection Time Out. Please try again..',
					type: "warning",
					timer: 3000,
					showCancelButton: false,
					showConfirmButton: false,
					allowOutsideClick: false
				});
			}
		});
	});


	$('#simpan-bro').click(function(e) {
		e.preventDefault();
		$(this).prop('disabled', true);
		var nm_asset = $('#nm_asset').val();
		var category = $('#category').val();
		var lokasi_asset = $('#lokasi_asset').val();
		var cost_center = $('#cost_center').val();
		var depresiasi = $('#depresiasi').val();
		var nilai_asset = $('#nilai_asset').val();
		var qty = $('#qty').val();
		var tanggal = $('#tanggal').val();

		if (nm_asset == '' || nm_asset == null) {
			// $("#error").html("Nama asset masih kosong !!!");
			// $('#myModal').modal("show");
			new swal({
				title: "Error Message!",
				text: 'Nama asset masih kosong ...',
				type: "warning"
			});

			$('#simpan-bro').prop('disabled', false);
			return false;
		}
		if (category == '' || category == null || category == 0) {
			new swal({
				title: "Error Message!",
				text: 'Kategori asset belum dipilih ...',
				type: "warning"
			});

			$('#simpan-bro').prop('disabled', false);
			return false;
		}

		if (lokasi_asset == '' || lokasi_asset == null || lokasi_asset == 0) {
			new swal({
				title: "Error Message!",
				text: 'Department belum dipilih ...',
				type: "warning"
			});

			$('#simpan-bro').prop('disabled', false);
			return false;
		}

		if (cost_center == '' || cost_center == null || cost_center == 0) {
			new swal({
				title: "Error Message!",
				text: 'Cost Center belum dipilih ...',
				type: "warning"
			});

			$('#simpan-bro').prop('disabled', false);
			return false;
		}

		if (depresiasi == '' || depresiasi == null || depresiasi == 0) {
			new swal({
				title: "Error Message!",
				text: 'Jangka waktu asset belum dipilih ...',
				type: "warning"
			});

			$('#simpan-bro').prop('disabled', false);
			return false;
		}
		if (nilai_asset == '' || nilai_asset == null || nilai_asset == 0) {
			new swal({
				title: "Error Message!",
				text: 'Nilai asset belum dipilih ...',
				type: "warning"
			});

			$('#simpan-bro').prop('disabled', false);
			return false;
		}
		if (qty == '' || qty == null || qty == 0) {
			new swal({
				title: "Error Message!",
				text: 'Qty asset belum dipilih ...',
				type: "warning"
			});

			$('#simpan-bro').prop('disabled', false);
			return false;
		}
		if (tanggal == '' || tanggal == null || tanggal == 0) {
			new swal({
				title: "Error Message!",
				text: 'Tanggal asset belum dipilih ...',
				type: "warning"
			});

			$('#simpan-bro').prop('disabled', false);
			return false;
		}
		// new swal({
		// title	: "Error Message!",
		// text	: 'STOP',
		// type	: "warning"
		// });

		// return false;

		new swal({
			title: "Are you sure?",
			text: "You will not be able to process again this data!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, Process it!",
			cancelButtonText: "No, cancel process!",
			closeOnConfirm: false,
			closeOnCancel: false
		}).then((hasil) => {
			if (hasil.isConfirmed) {
				// loading_spinner();
				var formData = new FormData($('#form_proses_bro')[0]);
				var baseurl = siteurl + 'asset/saved';
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
							new swal({
								title: "Save Success!",
								text: data.pesan,
								type: "success",
								timer: 7000
							}).then((hasil1) => {
								window.location.href = siteurl + 'asset';
							});
						} else {
							if (data.status == 2) {
								new swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 7000
								});
							} else if (data.status == 3) {
								new swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 7000
								});
							} else {
								new swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 7000,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: false
								});
							}
							$('#simpan-bro').prop('disabled', false);
						}
					},
					error: function() {
						new swal({
							title: "Error Message !",
							text: 'An Error Occured During Process. Please try again..',
							type: "warning",
							timer: 7000,
							showCancelButton: false,
							showConfirmButton: false,
							allowOutsideClick: false
						});
						$('#simpan-bro').prop('disabled', false);
					}
				});
			} else {
				new swal("Cancelled", "Data can be process again :)", "error");
				$('#simpan-bro').prop('disabled', false);
				return false;
			}
		});
	});
</script>
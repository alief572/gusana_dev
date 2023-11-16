<div class="card-body" id="data-form-customer">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="id">Company ID </label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="id" name="id" readonly placeholder="Auto" value="<?= isset($company) ? $company->id : null; ?>">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="company_name">Company Name <span class="tx-danger">*</span></label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="company_name" value="<?= isset($company) ? $company->company_name : null; ?>" required name="company_name" placeholder="Nama Company">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="telephone">Phone Number <span class="tx-danger">*</span></label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="telephone" value="<?= isset($company) ? $company->telephone : ''; ?>" required name="telephone" placeholder="Phone Number">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="telephone_alt"></label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="telephone_alt" value="<?= isset($company) ? $company->telephone_alt : ''; ?>" name="telephone_alt" placeholder="Alt. Telephone">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="fax">Fax</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="fax" name="fax" value="<?= isset($company) ? $company->fax : ''; ?>" placeholder="Fax Number">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="email">Email</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="email" value="<?= isset($company) ? $company->email : ''; ?>" data-parsley-type="email" name="email" placeholder="email@domain.adress">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="start_date">Start Date</label>
				</div>
				<div class="col-md-8">
					<input type="date" class="form-control" id="start_date" value="<?= isset($company) ? $company->start_date : ''; ?>" name="start_date" placeholder="Start Date">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="sales_id">API Type</label>
				</div>
				<div class="col-md-8">
					<input type="text" name="api_type" id="api_type" value="<?= isset($company) ? $company->api_type : ''; ?>" class="form-control" placeholder="API Type">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="sales_id">Documents (Lartas)</label>
				</div>
				<div class="col-md-8">
					<select name="documents[]" id="documents" class="form-control select-tags" multiple>
						<option value=""></option>
						<?php if (isset($company->documents)) : foreach (json_decode($company->documents) as $doc) : ?>
								<option value="<?= $doc; ?>" selected><?= $doc; ?></option>
							<?php endforeach;
						else : ?>
							<option value="PI Besi Baja">PI Besi Baja</option>
							<option value="PI Kehutanan">PI Kehutanan</option>
						<?php endif; ?>

					</select>
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="country_id">Country</label>
				</div>
				<div class="col-md-8">
					<div id="slWrapperCountry" class="parsley-select">
						<select id="country_id" name="country_id" class="form-control select" data-parsley-inputs data-parsley-class-handler="#slWrapperCountry" data-parsley-errors-container="#slErrorContainerCountry">
							<option value=""></option>
							<?php if ($countries) foreach ($countries as $country) : ?>
								<option value="<?= $country->id; ?>" <?= ($country->id == $company->country_id) ? 'selected' : ''; ?>><?= $country->country_code . " - " . $country->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div id="slErrorContainerCountry"></div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="state_id">Province</label>
				</div>
				<div class="col-md-8">
					<div id="slWrapperProv" class="parsley-select">
						<div id="states_select">
							<select id="state_id" name="state_id" class="form-control select" data-parsley-inputs data-parsley-class-handler="#slWrapperProv" data-parsley-errors-container="#slErrorContainerProv">
								<option value=""></option>
								<?php if (isset($company) && $company->state_id) foreach ($states as $state) : ?>
									<option value="<?= $company->state_id; ?>" <?= (isset($company) && $company->state_id == $state->id) ? 'selected' : ''; ?>><?= $state->name; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div id="slErrorContainerProv"></div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="city_id">City</label>
				</div>
				<div class="col-md-8">
					<div id="slWrapperCity" class="parsley-select">
						<select id="city_id" name="city_id" class="form-control select" data-parsley-inputs data-parsley-class-handler="#slWrapperCity" data-parsley-errors-container="#slErrorContainerCity">
							<option value=""></option>
							<?php if (isset($company) && $company->city_id) foreach ($cities as $city) : ?>
								<option value="<?= $company->city_id; ?>" <?= (isset($company) && $company->city_id == $city->id) ? 'selected' : ''; ?>><?= $city->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div id="slErrorContainerCity"></div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="address">Address <span class="tx-danger">*</span></label>
				</div>
				<div class="col-md-8">
					<textarea type="text" name="address" id="address" required class="form-control" placeholder="Address"><?= isset($company) ? $company->address : ''; ?></textarea>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="zip_code">Zip Code</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="zip_code" value="<?= isset($company) ? $company->zip_code : ''; ?>" name="zip_code" placeholder="Kode Pos">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="longitude">Longtitude</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="longitude" value="<?= isset($company) ? $company->longitude : ''; ?>" name="longitude" placeholder="Longtitude">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="latitude">Latitude</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="latitude" value="<?= isset($company) ? $company->latitude : ''; ?>" name="latitude" placeholder="Latitude">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="statusActive">Status <span class="tx-danger">*</span></label>
				</div>
				<div class="col-md-8">
					<div id="cbWrapperStatus" class="parsley-checkbox mg-b-0">
						<label class="rdiobox rdiobox-success d-inline-block mg-r-5">
							<input type="radio" id="statusActive" <?= isset($company) && $company->status == '1' ? 'checked' : null; ?> name="status" value="1" required data-required="true" data-parsley-inputs data-parsley-class-handler="#cbWrapperStatus" data-parsley-errors-container="#cbErrorContainerStatus">
							<span>Active</span>
						</label>
						<label class="rdiobox rdiobox-danger d-inline-block mg-r-5">
							<input type="radio" id="statusInactive" <?= isset($company) && $company->status == '0' ? 'checked' : null; ?> name="status" value="0">
							<span>Non Active</span>
						</label>
					</div>
					<div id="cbErrorContainerStatus"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card pd-7">
	<ul class="nav nav-pills flex-column flex-md-row tx-bold tx-dark" role="tablist">
		<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#PIC" role="tab">PIC</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#invoicing" role="tab">Invoicing</a>
		</li>
		<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#vat_info" role="tab">VAT
				Information</a>
		</li>
		<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#bank_info" role="tab">BANK
				Information</a>
		</li>
	</ul>
	<hr style="margin-top: 7px;margin-bottom:0px">
	<div class="tab-content br-profile-body">
		<div class="tab-pane fade active show" id="PIC">
			<div class="card-body">
				<table id="list-pic" class="table table-sm table-bordered border">
					<thead class="bg-gray-200">
						<tr>
							<th class="text-center" width="50">No</th>
							<th>PIC Name</th>
							<th>Phone Number</th>
							<th>Email</th>
							<th>Position</th>
							<th class="text-center" width="100">Opsi</th>
						</tr>
					</thead>
					<tbody>
						<?php $n = 0;
						if (isset($PIC) && $PIC)
							foreach ($PIC as $pic) : $n++; ?>
							<tr>
								<td><?= $n; ?></td>
								<td><?= $pic->name; ?></td>
								<td><?= $pic->phone_number; ?></td>
								<td><?= $pic->email; ?></td>
								<td><?= $pic->position; ?></td>
								<td class="text-center">
									<button type="button" class="btn btn-sm btn-success editPic" data-id="<?= $pic->id; ?>"><i class="fas fa-edit"></i></button>
									<button type="button" class="btn btn-sm btn-danger deletePic" data-id="<?= $pic->id; ?>"><i class="fas fa-trash"></i></button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<button type="button" id="add-pic" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add PIC</button>
			</div>
		</div>
		<div class="tab-pane fade" id="invoicing">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="row form-group">
							<div class="col-md-3 tx-dark tx-bold">
								<label for="day1">Receive Invoice Day</label>
							</div>
							<div class="col-md-9">
								<label class="ckbox ckbox-success d-inline-block mg-b-10">
									<input type="checkbox" id="day1" <?= $receive_invoice_day && (in_array('senin', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="senin">
									<span>Senin</span>
								</label>
								&nbsp;
								<label class="ckbox ckbox-success d-inline-block mg-b-10">
									<input type="checkbox" id="day2" <?= ($receive_invoice_day && in_array('selasa', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="selasa">
									<span>Selasa</span>
								</label>
								&nbsp;
								<label class="ckbox ckbox-success d-inline-block mg-b-10">
									<input type="checkbox" id="day3" <?= $receive_invoice_day &&  (in_array('rabu', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="rabu">
									<span>Rabu</span>
								</label>
								&nbsp;
								<label class="ckbox ckbox-success d-inline-block mg-b-10">
									<input type="checkbox" id="day4" <?= $receive_invoice_day && (in_array('kamis', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="kamis">
									<span>Kamis</span>
								</label>
								&nbsp;
								<label class="ckbox ckbox-success d-inline-block mg-b-10">
									<input type="checkbox" id="day5" <?= $receive_invoice_day && (in_array('jumat', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="jumat">
									<span>Ju'mat</span>
								</label>
								&nbsp;
								<label class="ckbox ckbox-success d-inline-block mg-b-10">
									<input type="checkbox" id="day6" <?= $receive_invoice_day && (in_array('sabtu', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="sabtu">
									<span>Sabtu</span>
								</label>
								&nbsp;
								<label class="ckbox ckbox-success d-inline-block mg-b-10">
									<input type="checkbox" id="day7" <?= ($receive_invoice_day && in_array('minggu', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="minggu">
									<span>Minggu</span>
								</label>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-3 tx-dark tx-bold">
								<label for="start_receive_time_invoice">Receive Invoice Time</label>
							</div>
							<div class="col-lg-9">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">Start</span>
									</div>
									<input type="time" class="form-control" id="start_receive_time_invoice" value="<?= isset($company) ? $company->start_receive_time_invoice : null; ?>" name="start_receive_time_invoice" placeholder="-">
									<div class="input-group-prepend">
										<span class="input-group-text">End</span>
									</div>
									<input type="time" class="form-control" id="end_receive_time_invoice" value="<?= isset($company) ? $company->end_receive_time_invoice : null; ?>" name="end_receive_time_invoice" placeholder="-">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-3 tx-dark tx-bold">
								<label for="address_invoice">Invoice Address</label>
							</div>
							<div class="col-md-9">
								<textarea type="text" name="address_invoice" id="address_invoice" class="form-control input-sm w70" placeholder="Address"><?= isset($company) ? $company->address_invoice : ''; ?></textarea>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6">
								<label class="ckbox ckbox-indigo mg-b-10">
									<input type="checkbox" id="requirement1" <?= ($invoicing_requirement && in_array('BERITA ACARA', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="BERITA ACARA">
									<span>Berita Acara</span>
								</label>
								<label class="ckbox ckbox-indigo mg-b-10">
									<input type="checkbox" id="requirement2" <?= ($invoicing_requirement && in_array('TTD', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="TTD">
									<span>TTD Specimen / Tax Invoice Serial Number</span>
								</label>
								<label class="ckbox ckbox-indigo mg-b-10">
									<input type="checkbox" id="requirement3" <?= ($invoicing_requirement && in_array('PAYMENT CERTIFICATE', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="PAYMENT CERTIFICATE">
									<span>Payment Certificate</span>
								</label>
								<label class="ckbox ckbox-indigo mg-b-10">
									<input type="checkbox" id="requirement4" <?= ($invoicing_requirement && in_array('PHOTO', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="PHOTO">
									<span>Photo</span>
								</label>
								<label class="ckbox ckbox-indigo mg-b-10">
									<input type="checkbox" id="requirement5" <?= ($invoicing_requirement && in_array('SIUP', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="SIUP">
									<span>SIUP</span>
								</label>
								<label class="ckbox ckbox-indigo mg-b-10">
									<input type="checkbox" id="requirement6" <?= ($invoicing_requirement && in_array('SPK', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="SPK">
									<span>SPK</span>
								</label>
							</div>
							<div class="col-md-6">
								<label class="ckbox ckbox-indigo mg-b-10">
									<input type="checkbox" id="requirement7" <?= ($invoicing_requirement && in_array('NPWP', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="NPWP">
									<span>NPWP</span>
								</label>
								<label class="ckbox ckbox-indigo mg-b-10">
									<input type="checkbox" id="requirement8" <?= ($invoicing_requirement && in_array('DO', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="DO">
									<span>Delivery Order</span>
								</label>
								<label class="ckbox ckbox-indigo mg-b-10">
									<input type="checkbox" id="requirement9" <?= ($invoicing_requirement && in_array('FAKTUR PAJAK', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="FAKTUR PAJAK">
									<span>Faktur Pajak</span>
								</label>
								<label class="ckbox ckbox-indigo mg-b-10">
									<input type="checkbox" id="requirement10" <?= ($invoicing_requirement && in_array('TDP', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="TDP">
									<span>TDP</span>
								</label>
								<label class="ckbox ckbox-indigo mg-b-10">
									<input type="checkbox" id="requirement11" <?= ($invoicing_requirement && in_array('REAL PO', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="REAL PO">
									<span>Real PO</span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="vat_info">
			<div class="card-body">
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="npwp_number">Nomor NPWP/PKP</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="npwp_number" value="<?= isset($company) ? $company->npwp_number : null; ?>" name="npwp_number" placeholder="Nama Number">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="npwp_name">Nama NPWP/PKP</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="npwp_name" value="<?= isset($company) ? $company->npwp_name : null; ?>" name="npwp_name" placeholder="Nama NPWP">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="npwp_address">Alamat NPWP</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="npwp_address" value="<?= isset($company) ? $company->npwp_address : null; ?>" name="npwp_address" placeholder="Alamat NPWP">
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="bank_info">
			<div class="card-body">
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="bank_name">Bank Name</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="bank_name" value="<?= isset($company) ? $company->bank_name : null; ?>" name="bank_name" placeholder="Bank Name">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="bank_account_number">Account Number</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="bank_account_number" value="<?= isset($company) ? $company->bank_account_number : null; ?>" name="bank_account_number" placeholder="Account Number">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="bank_account_name">Account Name</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="bank_account_name" value="<?= isset($company) ? $company->bank_account_name : null; ?>" name="bank_account_name" placeholder="Account Name">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="bank_account_address">Bank Address</label>
					</div>
					<div class="col-md-6">
						<textarea type="text" name="bank_account_address" id="bank_account_address" class="form-control input-sm w70" placeholder="Bank Address"><?= isset($company) ? $company->bank_account_address : null; ?></textarea>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="swift_code">Swift Code</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="swift_code" value="<?= isset($company) ? $company->swift_code : null; ?>" name="swift_code" placeholder="Swift Code">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<hr>
<h5 class="tx-dark tx-bold"><i class="fa fa-file" aria-hidden="true"></i> Letter Head</h5>
<div class="card">
	<div class="card-body">
		<div class="form-group mb-3 row">
			<div class="col-md-2 tx-dark tx-bold">
				<label for="longitude">Header</label>
			</div>
			<div class="col-md-10">
				<?php $no_image = 'assets/no-image.jpg'; ?>
				<input type="file" name="header" id="header" accept=".png,.jpg" class="inputfile">
				<div class="rounded text-center" style="border:2px dashed #ccc;cursor:pointer;" onclick="$('#header').click()" title="Click to upload file">
					<img src="<?= base_url($company->header ? $path . $company->header : $no_image); ?>" id="preview-header" alt="no-image" class="border-0 mx-wd-100p">
				</div>
				<div class="mg-t-10">
					<button type="button" id="removeHeader" class="btn btn-sm btn-danger <?= ($company->header) ? '' : 'd-none'; ?>">Delete Header <i class="fa fa-trash" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-2 tx-dark tx-bold">
				<label for="longitude">Watermark</label>
			</div>
			<div class="col-md-10">
				<input type="file" name="watermark" id="watermark" accept=".png,.jpg" class="inputfile">
				<div class="rounded text-center" style="border:2px dashed #ccc;cursor:pointer;" onclick="$('#watermark').click()" title="Click to upload file">
					<img src="<?= base_url($company->watermark ? $path . $company->watermark : $no_image); ?>" id="preview-watermark" alt="no-image" class="border-0 mx-wd-100p">
				</div>
				<div class="mg-t-10">
					<button type="button" id="removeWatermark" class="btn btn-sm btn-danger <?= ($company->watermark) ? '' : 'd-none'; ?>">Delete Watermark <i class="fa fa-trash" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-2 tx-dark tx-bold">
				<label for="longitude">Footer</label>
			</div>
			<div class="col-md-10">
				<input type="file" name="footer" id="footer" accept=".png,.jpg" class="inputfile">
				<div class="rounded text-center" style="border:2px dashed #ccc;cursor:pointer;" onclick="$('#footer').click()" title="Click to upload file">
					<img src="<?= base_url($company->footer ? $path . $company->footer : $no_image); ?>" id="preview-footer" alt="no-image" class="border-0 mx-wd-100p">
				</div>
				<div class="mg-t-10">
					<button type="button" id="removeFooter" class="btn btn-sm btn-danger <?= ($company->footer) ? '' : 'd-none'; ?>">Delete Footer <i class="fa fa-trash" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
		<input type="hidden" name="remove_lh" id="remove_lh">
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$(document).on('click', '#removeHeader', function() {
			let lhRemove = 'header'
			$(this).addClass('d-none');
			$('#preview-header').attr('src', siteurl + 'assets/no-image.jpg')
			$('#header').val('')
			let lh = $('#remove_lh').val();
			if (lh) {
				lhRemove = lhRemove + "," + lh
			}
			console.log(lhRemove);
			$('#remove_lh').val(lhRemove)
		})

		$(document).on('click', '#removeFooter', function() {
			let lhRemove = 'footer'
			$(this).addClass('d-none');
			$('#preview-footer').attr('src', siteurl + 'assets/no-image.jpg')
			$('#footer').val('')
			let lh = $('#remove_lh').val();
			if (lh) {
				lhRemove = lhRemove + "," + lh
			}
			console.log(lhRemove);
			$('#remove_lh').val(lhRemove)
		})

		$(document).on('click', '#removeWatermark', function() {
			let lhRemove = 'watermark'
			$(this).addClass('d-none');
			$('#preview-watermark').attr('src', siteurl + 'assets/no-image.jpg')
			$('#watermark').val('')
			let lh = $('#remove_lh').val();
			if (lh) {
				lhRemove = lhRemove + "," + lh
			}
			console.log(lhRemove);
			$('#remove_lh').val(lhRemove)
		})

		$('#header').change(function() {
			const file = this.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function(event) {
					console.log(event.target.result);
					$('#preview-header').attr('src', event.target.result);
					$('#removeHeader').removeClass('d-none')
				}
				reader.readAsDataURL(file);
			}
		});

		$('#watermark').change(function() {
			const file = this.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function(event) {
					console.log(event.target.result);
					$('#preview-watermark').attr('src', event.target.result);
					$('#removeWatermark').removeClass('d-none')
				}
				reader.readAsDataURL(file);
			}
		});

		$('#footer').change(function() {
			const file = this.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function(event) {
					console.log(event.target.result);
					$('#preview-footer').attr('src', event.target.result);
					$('#removeWatermark').removeClass('d-none')
				}
				reader.readAsDataURL(file);
			}
		});

		$('.select').select2({
			// minimumResultsForSearch: -1,
			placeholder: 'Choose one',
			dropdownParent: $('#data-form-customer'),
			width: "100%",
			allowClear: true
		});

		$('.select-tags').select2({
			// minimumResultsForSearch: -1,
			tags: true,
			placeholder: 'Choose one',
			dropdownParent: $('#data-form-customer'),
			width: "100%",
			allowClear: true
		});

		$('.select.not-search').select2({
			minimumResultsForSearch: -1,
			placeholder: 'Choose one',
			dropdownParent: $('#data-form-customer'),
			width: "100%",
			allowClear: true
		});

		$(document).on('change', '#country_id', function() {
			let country_id = $('#country_id').val();
			$('#state_id').val('null').trigger('change')
			$('#city_id').val('null').trigger('change')
			$('#state_id').select2({
				ajax: {
					url: siteurl + thisController + 'getProvince',
					dataType: 'JSON',
					type: 'GET',
					delay: 100,
					data: function(params) {
						return {
							q: params.term, // search term
							country_id: country_id, // search term
						};
					},
					processResults: function(res) {
						return {
							results: $.map(res, function(item) {
								return {
									id: item.id,
									text: item.name
								}
							})
						};
					}
				},
				cache: true,
				placeholder: 'Choose one',
				dropdownParent: $('#data-form-customer'),
				width: "100%",
				allowClear: true
			})
		})

		$(document).on('change.select2', '#state_id', function() {
			let state_id = $('#state_id').val();
			$('#city_id').val('null').trigger('change')
			$('#city_id').select2({
				ajax: {
					url: siteurl + thisController + 'getCities',
					dataType: 'JSON',
					type: 'GET',
					delay: 100,
					data: function(params) {
						return {
							q: params.term, // search term
							state_id: state_id, // search term
						};
					},
					processResults: function(res) {
						return {
							results: $.map(res, function(item) {
								return {
									id: item.id,
									text: item.name
								}
							})
						};
					}
				},
				cache: true,
				placeholder: 'Choose one',
				dropdownParent: $('#data-form-customer'),
				width: "100%",
				allowClear: true
			})


		})
	})
</script>
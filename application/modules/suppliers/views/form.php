<div class="card-body" id="data-form-supplier">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="id">Supplier ID</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="id" name="id" readonly placeholder="Auto" value="<?= isset($supplier) ? $supplier->id : null; ?>">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="supplier_name">Supplier Name <span class="tx-danger">*</span></label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="supplier_name" value="<?= isset($supplier) ? $supplier->supplier_name : null; ?>" required name="supplier_name" placeholder="Supplier Name">
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="telephone">Phone Number <span class="tx-danger">*</span></label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="telephone" value="<?= isset($supplier) ? $supplier->telephone : ''; ?>" required name="telephone" placeholder="Phone Number">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="telephone_alt"></label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="telephone_alt" value="<?= isset($supplier) ? $supplier->telephone_alt : ''; ?>" name="telephone_alt" placeholder="Alt. Phone Number">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="fax">Fax</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="fax" name="fax" value="<?= isset($supplier) ? $supplier->fax : ''; ?>" placeholder="Fax Number">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="email">Email</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="email" value="<?= isset($supplier) ? $supplier->email : ''; ?>" data-parsley-type="email" name="email" placeholder="email@domain.adress">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="start_date">Tanggal Mulai</label>
				</div>
				<div class="col-md-8">
					<input type="date" class="form-control" id="start_date" value="<?= isset($supplier) ? $supplier->start_date : ''; ?>" name="start_date" placeholder="Tanggal Mulai">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="supplier_type">Supplier Type <span class="tx-danger">*</span></label>
				</div>
				<div class="col-md-8">
					<div id="slWrapperSupplierType" class="parsley-select">
						<select id="supplier_type" name="supplier_type" class="form-control select" required data-parsley-inputs data-parsley-class-handler="#slWrapperSupplierType" data-parsley-errors-container="#slErrorContainerSupplierType">
							<option value=""></option>
							<?php if ($category_suppliers) foreach ($category_suppliers as $sType) : ?>
								<option value="<?= $sType->id_category; ?>" <?= (isset($supplier) && $sType->id_category == $supplier->supplier_type) ? 'selected' : ''; ?>><?= $sType->nm_category_supplier; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div id="slErrorContainerSupplierType"></div>
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
								<option value="<?= $country->id; ?>" <?= (isset($supplier) && $country->id == $supplier->country_id) ? 'selected' : ''; ?>><?= $country->country_code . " - " . $country->name; ?></option>
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
								<?php foreach ($states as $state) : ?>
									<option value="<?= $state->id; ?>" <?= (isset($supplier) && $supplier->state_id == $state->id) ? 'selected' : ''; ?>><?= $state->name; ?></option>
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
							<?php if (isset($supplier) && $supplier->city_id) foreach ($cities as $city) : ?>
								<option value="<?= $supplier->city_id; ?>" <?= (isset($supplier) && $supplier->city_id == $city->id) ? 'selected' : ''; ?>><?= $city->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div id="slErrorContainerCity"></div>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="address">Alamat <span class="tx-danger">*</span></label>
				</div>
				<div class="col-md-8">
					<textarea type="text" name="address" id="address" required class="form-control" placeholder="Alamat"><?= isset($supplier) ? $supplier->address : ''; ?></textarea>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="zip_code">Zip Code</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="zip_code" value="<?= isset($supplier) ? $supplier->zip_code : ''; ?>" name="zip_code" placeholder="Kode Pos">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="longitude">Longtitude</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="longitude" value="<?= isset($supplier) ? $supplier->longitude : ''; ?>" name="longitude" placeholder="Longtitude">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="latitude">Latitude</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" id="latitude" value="<?= isset($supplier) ? $supplier->latitude : ''; ?>" name="latitude" placeholder="Latitude">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3 tx-dark tx-bold">
					<label for="statusActive">Status <span class="tx-danger">*</span></label>
				</div>
				<div class="col-md-8">
					<div id="cbWrapperStatus" class="parsley-checkbox mg-b-0">
						<label class="rdiobox rdiobox-success d-inline-block mg-r-5">
							<input type="radio" checked id="statusActive" name="status" value="1" required data-required="true" data-parsley-inputs data-parsley-class-handler="#cbWrapperStatus" data-parsley-errors-container="#cbErrorContainerStatus">
							<span>Active</span>
						</label>
						<label class="rdiobox rdiobox-danger d-inline-block mg-r-5">
							<input type="radio" id="statusInactive" <?= isset($supplier) && $supplier->status == '0' ? 'checked' : null; ?> name="status" value="0">
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
		<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#bank_info" role="tab">Bank Information</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#vat_info" role="tab">VAT Information</a></li>
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
		<div class="tab-pane fade" id="bank_info">
			<div class="card-body">
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="bank_name">Bank Name</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="bank_name" value="<?= isset($supplier) ? $supplier->bank_name : null; ?>" name="bank_name" placeholder="Bank Name">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="bank_account_number">Account Number</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="bank_account_number" value="<?= isset($supplier) ? $supplier->bank_account_number : null; ?>" name="bank_account_number" placeholder="Account Number">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="bank_account_name">Account Name</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="bank_account_name" value="<?= isset($supplier) ? $supplier->bank_account_name : null; ?>" name="bank_account_name" placeholder="Account Name">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="bank_account_address">Bank Address</label>
					</div>
					<div class="col-md-6">
						<textarea type="text" name="bank_account_address" id="bank_account_address" class="form-control input-sm w70" placeholder="Bank Address"><?= isset($supplier) ? $supplier->bank_account_address : null; ?></textarea>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="swift_code">Swift Code</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="swift_code" value="<?= isset($supplier) ? $supplier->swift_code : null; ?>" name="swift_code" placeholder="Swift Code">
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="vat_info">
			<div class="card-body">
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="npwp_number">NPWP/PKP Number</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="npwp_number" value="<?= isset($supplier) ? $supplier->npwp_number : null; ?>" name="npwp_number" placeholder="NPWP Number">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="npwp_name">NPWP/PKP Name</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="npwp_name" value="<?= isset($supplier) ? $supplier->npwp_name : null; ?>" name="npwp_name" placeholder="NPWP Name">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<label for="npwp_address">NPWP Address</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="npwp_address" value="<?= isset($supplier) ? $supplier->npwp_address : null; ?>" name="npwp_address" placeholder="NPWP Address">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$('.select').select2({
			placeholder: 'Choose one',
			dropdownParent: $('#data-form-supplier'),
			width: "100%",
			allowClear: true
		});

		$('.select.not-search').select2({
			minimumResultsForSearch: -1,
			placeholder: 'Choose one',
			dropdownParent: $('#data-form-supplier'),
			width: "100%",
			allowClear: true
		});

	})
</script>
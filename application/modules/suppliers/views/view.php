<div class="card-body" id="data-form-supplier">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Supplier ID</span>
				</div>
				<div class="col-md-8">:
					<?= isset($supplier) ? $supplier->id : null; ?>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Supplier Name</span>
				</div>
				<div class="col-md-8 tx-dark tx-bold">:
					<?= isset($supplier) ? $supplier->supplier_name : null; ?>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Phone Number</span>
				</div>
				<div class="col-md-8">:
					<?= isset($supplier) ? $supplier->telephone : ''; ?>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span /span>
				</div>
				<div class="col-md-8">:
					<?= isset($supplier) ? $supplier->telephone_alt : ''; ?>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Fax</span>
				</div>
				<div class="col-md-8">:
					<?= isset($supplier) ? $supplier->fax : ''; ?>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Email</span>
				</div>
				<div class="col-md-8">:
					<?= isset($supplier) ? $supplier->email : ''; ?>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Tanggal Mulai</span>
				</div>
				<div class="col-md-8">:
					<?= isset($supplier) ? $supplier->start_date : ''; ?>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Supplier Type</span>
				</div>
				<div class="col-md-8">:
					<?= $supplier->nm_category_supplier; ?>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Country</span>
				</div>
				<div class="col-md-8">:
					<?= $ArrCountries[$supplier->country_id]; ?>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Province</span>
				</div>
				<div class="col-md-8">:
					<?= $ArrStates[$supplier->state_id]; ?>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>City</span>
				</div>
				<div class="col-md-8">:
					<?= $ArrCities[$supplier->city_id]; ?>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Alamat</span>
				</div>
				<div class="col-md-8">:
					<?= isset($supplier) ? $supplier->address : ''; ?>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Zip Code</span>
				</div>
				<div class="col-md-8">:
					"<?= isset($supplier) ? $supplier->zip_code : ''; ?>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Longtitude</span>
				</div>
				<div class="col-md-8">:
					<?= isset($supplier) ? $supplier->longitude : ''; ?>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Latitude</span>
				</div>
				<div class="col-md-8">:
					<?= isset($supplier) ? $supplier->latitude : ''; ?>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-4 tx-dark tx-bold">
					<span>Status</span>
				</div>
				<div class="col-md-8">:
					<?= isset($supplier) && $supplier->status == '0' ? 'Inactive' : 'Active'; ?>
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

							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="tab-pane fade" id="bank_info">
			<div class="card-body">
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<span>Bank Name</span>
					</div>
					<div class="col-md-6">:
						<?= isset($supplier) ? $supplier->bank_name : null; ?>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<span>Account Number</span>
					</div>
					<div class="col-md-6">:
						<?= isset($supplier) ? $supplier->bank_account_number : null; ?>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<span>Account Name</span>
					</div>
					<div class="col-md-6">:
						<?= isset($supplier) ? $supplier->bank_account_name : null; ?>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<span>Bank Address</span>
					</div>
					<div class="col-md-6">:
						<?= isset($supplier) ? $supplier->bank_account_address : null; ?>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<span>Swift Code</span>
					</div>
					<div class="col-md-6">:
						<?= isset($supplier) ? $supplier->swift_code : null; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="vat_info">
			<div class="card-body">
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<span>NPWP/PKP Number</span>
					</div>
					<div class="col-md-6">:
						<?= isset($supplier) ? $supplier->npwp_number : null; ?>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<span>NPWP/PKP Name</span>
					</div>
					<div class="col-md-6">:
						<?= isset($supplier) ? $supplier->npwp_name : null; ?>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-2 tx-dark tx-bold">
						<span>NPWP Address</span>
					</div>
					<div class="col-md-6">:
						<?= isset($supplier) ? $supplier->npwp_address : null; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
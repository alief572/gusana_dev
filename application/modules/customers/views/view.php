<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_customer">ID Customer </label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer) ? $customer->id_customer : null; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="customer_name">Customer Name</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer) ? $customer->customer_name : null; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="telephone">Phone Number</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer) ? $customer->telephone : ''; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="telephone_alt">&nbsp;</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer) ? $customer->telephone_alt : ''; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="fax">Fax</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer) ? $customer->fax : ''; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="email">Email</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer) ? $customer->email : ''; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="sales_id">Marketing</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer->sales_id) ? $ArrMkt[$customer->sales_id] : '-' ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="country_id">Country </label>
                </div>
                <div class="col-md-8">:
                    <?= $ArrCountries[$customer->country_id]; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="state_id">Province</label>
                </div>
                <div class="col-md-8">:
                    <?= $ArrStates[$customer->state_id]; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="city_id">City</label>
                </div>
                <div class="col-md-8">:
                    <?= $ArrCities[$customer->city_id]; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="address">Address</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer) ? $customer->address : ''; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="zip_code">Zip Code</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer) ? $customer->zip_code : ''; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="longitude">Longtitude</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer) ? $customer->longitude : ''; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="latitude">Latitude</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer) ? $customer->latitude : ''; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="statusActive">Status</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($customer) && $customer->status == '1' ? 'Active' : 'Inactive'; ?>
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

        <div class="tab-pane fade" id="invoicing">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row form-group">
                            <div class="col-md-4 tx-dark tx-bold">
                                <label for="day1">Receive Invoice Day</label>
                            </div>
                            <div class="col-md-9">
                                <label class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input type="checkbox" disabled id="day1" <?= $receive_invoice_day && (in_array('senin', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="senin">
                                    <span>Senin</span>
                                </label>
                                &nbsp;
                                <label class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input type="checkbox" disabled id="day2" <?= ($receive_invoice_day && in_array('selasa', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="selasa">
                                    <span>Selasa</span>
                                </label>
                                &nbsp;
                                <label class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input type="checkbox" disabled id="day3" <?= $receive_invoice_day &&  (in_array('rabu', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="rabu">
                                    <span>Rabu</span>
                                </label>
                                &nbsp;
                                <label class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input type="checkbox" disabled id="day4" <?= $receive_invoice_day && (in_array('kamis', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="kamis">
                                    <span>Kamis</span>
                                </label>
                                &nbsp;
                                <label class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input type="checkbox" disabled id="day5" <?= $receive_invoice_day && (in_array('jumat', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="jumat">
                                    <span>Ju'mat</span>
                                </label>
                                &nbsp;
                                <label class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input type="checkbox" disabled id="day6" <?= $receive_invoice_day && (in_array('sabtu', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="sabtu">
                                    <span>Sabtu</span>
                                </label>
                                &nbsp;
                                <label class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input type="checkbox" disabled id="day7" <?= ($receive_invoice_day && in_array('minggu', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="minggu">
                                    <span>Minggu</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4 tx-dark tx-bold">
                                <label for="start_receive_time_invoice">Receive Invoice Time</label>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Start</span>
                                    </div>
                                    <input type="time" class="form-control" disabled id="start_receive_time_invoice" value="<?= isset($customer) ? $customer->start_receive_time_invoice : null; ?>" placeholder="-">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">End</span>
                                    </div>
                                    <input type="time" class="form-control" disabled id="end_receive_time_invoice" value="<?= isset($customer) ? $customer->end_receive_time_invoice : null; ?>" placeholder="-">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4 tx-dark tx-bold">
                                <label for="address_invoice">Invoice Address</label>
                            </div>
                            <div class="col-md-9">
                                <textarea type="text" disabled id="address_invoice" class="form-control input-sm w70" placeholder="Address"><?= isset($customer) ? $customer->address_invoice : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-7">
                                <label class="ckbox ckbox-indigo mg-b-10">
                                    <input type="checkbox" disabled id="requirement1" <?= ($invoicing_requirement && in_array('BERITA ACARA', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="BERITA ACARA">
                                    <span>Berita Acara</span>
                                </label>
                                <label class="ckbox ckbox-indigo mg-b-10">
                                    <input type="checkbox" disabled id="requirement2" <?= ($invoicing_requirement && in_array('TTD', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="TTD">
                                    <span>TTD Specimen / Tax Invoice Serial Number</span>
                                </label>
                                <label class="ckbox ckbox-indigo mg-b-10">
                                    <input type="checkbox" disabled id="requirement3" <?= ($invoicing_requirement && in_array('PAYMENT CERTIFICATE', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="PAYMENT CERTIFICATE">
                                    <span>Payment Certificate</span>
                                </label>
                                <label class="ckbox ckbox-indigo mg-b-10">
                                    <input type="checkbox" disabled id="requirement4" <?= ($invoicing_requirement && in_array('PHOTO', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="PHOTO">
                                    <span>Photo</span>
                                </label>
                                <label class="ckbox ckbox-indigo mg-b-10">
                                    <input type="checkbox" disabled id="requirement5" <?= ($invoicing_requirement && in_array('SIUP', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="SIUP">
                                    <span>SIUP</span>
                                </label>
                                <label class="ckbox ckbox-indigo mg-b-10">
                                    <input type="checkbox" disabled id="requirement6" <?= ($invoicing_requirement && in_array('SPK', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="SPK">
                                    <span>SPK</span>
                                </label>
                            </div>
                            <div class="col-md-5">
                                <label class="ckbox ckbox-indigo mg-b-10">
                                    <input type="checkbox" disabled id="requirement7" <?= ($invoicing_requirement && in_array('NPWP', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="NPWP">
                                    <span>NPWP</span>
                                </label>
                                <label class="ckbox ckbox-indigo mg-b-10">
                                    <input type="checkbox" disabled id="requirement8" <?= ($invoicing_requirement && in_array('DO', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="DO">
                                    <span>Delivery Order</span>
                                </label>
                                <label class="ckbox ckbox-indigo mg-b-10">
                                    <input type="checkbox" disabled id="requirement9" <?= ($invoicing_requirement && in_array('FAKTUR PAJAK', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="FAKTUR PAJAK">
                                    <span>Faktur Pajak</span>
                                </label>
                                <label class="ckbox ckbox-indigo mg-b-10">
                                    <input type="checkbox" disabled id="requirement10" <?= ($invoicing_requirement && in_array('TDP', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="TDP">
                                    <span>TDP</span>
                                </label>
                                <label class="ckbox ckbox-indigo mg-b-10">
                                    <input type="checkbox" disabled id="requirement11" <?= ($invoicing_requirement && in_array('REAL PO', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="REAL PO">
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
                        <label for="npwp_number">NPWP/PKP Number</label>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($customer) &&  $customer->npwp_number ? $customer->npwp_number : '-'; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <label for="npwp_name">NPWP/PKP Name</label>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($customer) && $customer->npwp_name ? $customer->npwp_name : '-'; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <label for="npwp_address">NPWP Address</label>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($customer) && $customer->npwp_address ? $customer->npwp_address : '-'; ?>
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
                    <div class="col-md-6">:
                        <?= isset($customer) && $customer->bank_name ? $customer->bank_name : '-'; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <label for="bank_account_number">Account Number</label>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($customer) && $customer->bank_account_number ? $customer->bank_account_number : '-'; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <label for="bank_account_name">Account Name</label>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($customer) && $customer->bank_account_name ? $customer->bank_account_name : '-'; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <label for="bank_account_address">Bank Address</label>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($customer) && $customer->bank_account_address ? $customer->bank_account_address : '-'; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <label for="swift_code">Swift Code</label>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($customer) && $customer->swift_code ? $customer->swift_code : '-'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Company ID </span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) ? $company->id : null; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Company Name</span>
                </div>
                <div class="col-md-8">:
                    <strong class="tx-dark"><?= isset($company) ? $company->company_name : null; ?></strong>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Phone Number</span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) ? $company->telephone : ''; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span></span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) ? $company->telephone_alt : ''; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Fax</span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) ? $company->fax : ''; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Email</span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) ? $company->email : ''; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Start Date</span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) ? $company->start_date : ''; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>API Type</span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) ? $company->api_type : ''; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Documents (Lartas)</span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) && $company->documents ? implode(", ", json_decode($company->documents)) : '-'; ?>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Country</span>
                </div>
                <div class="col-md-8">:
                    <?= $ArrCountry[$company->country_id]; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Province</span>
                </div>
                <div class="col-md-8">:
                    <?= $ArrStates[$company->state_id]; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>City</span>
                </div>
                <div class="col-md-8">:
                    <?= $ArrCity[$company->city_id]; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Address</span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) ? $company->address : ''; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Zip Code</span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) ? $company->zip_code : ''; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Longtitude</span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) ? $company->longitude : ''; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Latitude</span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) ? $company->latitude : ''; ?>
                </div>
            </div>
            <div class="mg-b-8 row">
                <div class="col-md-3 tx-dark tx-bold">
                    <span>Status</span>
                </div>
                <div class="col-md-8">:
                    <?= isset($company) && $company->status == '1' ? 'Active' : 'Inactive'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card pd-7">
    <ul class="nav nav-pills flex-column flex-md-row tx-bold tx-dark" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#PIC" role="tab">PIC</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#invoicing" role="tab">Invoicing</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#vat_info" role="tab">VAT Information</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#bank_info" role="tab">BANK Information</a></li>
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
                    <div class="col-md-7">
                        <div class="row form-group">
                            <div class="col-md-3 tx-dark tx-bold">
                                <span class="tx-dark">Receive Invoice Day</span>
                            </div>
                            <div class="col-md-9">
                                <span class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input disabled type="checkbox" id="day1" <?= $receive_invoice_day && (in_array('senin', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="senin">
                                    <span class="tx-dark" class="tx-dark">Senin</span>
                                </span>
                                &nbsp;
                                <span class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input disabled type="checkbox" id="day2" <?= ($receive_invoice_day && in_array('selasa', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="selasa">
                                    <span class="tx-dark" class="tx-dark">Selasa</span>
                                </span>
                                &nbsp;
                                <span class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input disabled type="checkbox" id="day3" <?= $receive_invoice_day &&  (in_array('rabu', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="rabu">
                                    <span class="tx-dark" class="tx-dark">Rabu</span>
                                </span>
                                &nbsp;
                                <span class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input disabled type="checkbox" id="day4" <?= $receive_invoice_day && (in_array('kamis', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="kamis">
                                    <span class="tx-dark" class="tx-dark">Kamis</span>
                                </span>
                                &nbsp;
                                <span class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input disabled type="checkbox" id="day5" <?= $receive_invoice_day && (in_array('jumat', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="jumat">
                                    <span class="tx-dark" class="tx-dark">Ju'mat</span>
                                </span>
                                &nbsp;
                                <span class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input disabled type="checkbox" id="day6" <?= $receive_invoice_day && (in_array('sabtu', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="sabtu">
                                    <span class="tx-dark" class="tx-dark">Sabtu</span>
                                </span>
                                &nbsp;
                                <span class="ckbox ckbox-success d-inline-block mg-b-10">
                                    <input disabled type="checkbox" id="day7" <?= ($receive_invoice_day && in_array('minggu', $receive_invoice_day)) ? 'checked' : null; ?> name="receive_invoice_day[]" value="minggu">
                                    <span class="tx-dark" class="tx-dark">Minggu</span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 tx-dark tx-bold">
                                <span class="tx-dark">Receive Invoice Time</span>
                            </div>
                            <div class="col-lg-9">
                                <span class="tx-dark">Start : <?= isset($company) && $company->start_receive_time_invoice ? $company->start_receive_time_invoice : '--:--'; ?></span>
                                <span class="tx-dark">End : <?= isset($company) && $company->end_receive_time_invoice ? $company->end_receive_time_invoice : '--:--'; ?></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 tx-dark tx-bold">
                                <span class="tx-dark">Invoice Address</span>
                            </div>
                            <div class="col-md-9">:
                                <?= isset($company) && $company->address_invoice ? $company->address_invoice : ''; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-8">
                                <span class="ckbox ckbox-indigo mg-b-10">
                                    <input disabled type="checkbox" id="requirement1" <?= ($invoicing_requirement && in_array('BERITA ACARA', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="BERITA ACARA">
                                    <span class="tx-dark">Berita Acara</span>
                                </span>
                                <span class="ckbox ckbox-indigo mg-b-10">
                                    <input disabled type="checkbox" id="requirement2" <?= ($invoicing_requirement && in_array('TTD', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="TTD">
                                    <span class="tx-dark">TTD Specimen / Tax Invoice Serial Number</span>
                                </span>
                                <span class="ckbox ckbox-indigo mg-b-10">
                                    <input disabled type="checkbox" id="requirement3" <?= ($invoicing_requirement && in_array('PAYMENT CERTIFICATE', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="PAYMENT CERTIFICATE">
                                    <span class="tx-dark">Payment Certificate</span>
                                </span>
                                <span class="ckbox ckbox-indigo mg-b-10">
                                    <input disabled type="checkbox" id="requirement4" <?= ($invoicing_requirement && in_array('PHOTO', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="PHOTO">
                                    <span class="tx-dark">Photo</span>
                                </span>
                                <span class="ckbox ckbox-indigo mg-b-10">
                                    <input disabled type="checkbox" id="requirement5" <?= ($invoicing_requirement && in_array('SIUP', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="SIUP">
                                    <span class="tx-dark">SIUP</span>
                                </span>
                                <span class="ckbox ckbox-indigo mg-b-10">
                                    <input disabled type="checkbox" id="requirement6" <?= ($invoicing_requirement && in_array('SPK', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="SPK">
                                    <span class="tx-dark">SPK</span>
                                </span>
                            </div>
                            <div class="col-md-4">
                                <span class="ckbox ckbox-indigo mg-b-10">
                                    <input disabled type="checkbox" id="requirement7" <?= ($invoicing_requirement && in_array('NPWP', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="NPWP">
                                    <span class="tx-dark">NPWP</span>
                                </span>
                                <span class="ckbox ckbox-indigo mg-b-10">
                                    <input disabled type="checkbox" id="requirement8" <?= ($invoicing_requirement && in_array('DO', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="DO">
                                    <span class="tx-dark">Delivery Order</span>
                                </span>
                                <span class="ckbox ckbox-indigo mg-b-10">
                                    <input disabled type="checkbox" id="requirement9" <?= ($invoicing_requirement && in_array('FAKTUR PAJAK', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="FAKTUR PAJAK">
                                    <span class="tx-dark">Faktur Pajak</span>
                                </span>
                                <span class="ckbox ckbox-indigo mg-b-10">
                                    <input disabled type="checkbox" id="requirement10" <?= ($invoicing_requirement && in_array('TDP', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="TDP">
                                    <span class="tx-dark">TDP</span>
                                </span>
                                <span class="ckbox ckbox-indigo mg-b-10">
                                    <input disabled type="checkbox" id="requirement11" <?= ($invoicing_requirement && in_array('REAL PO', $invoicing_requirement)) ? 'checked' : null; ?> name="invoicing_requirement[]" value="REAL PO">
                                    <span class="tx-dark">Real PO</span>
                                </span>
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
                        <span class="tx-dark">Nomor NPWP/PKP</span>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($company) ? $company->npwp_number : null; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <span class="tx-dark">Nama NPWP/PKP</span>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($company) ? $company->npwp_name : null; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <span class="tx-dark">Alamat NPWP</span>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($company) ? $company->npwp_address : null; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="bank_info">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <span class="tx-dark">Bank Name</span>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($company) ? $company->bank_name : null; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <span class="tx-dark">Account Number</span>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($company) ? $company->bank_account_number : null; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <span class="tx-dark">Account Name</span>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($company) ? $company->bank_account_name : null; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <span class="tx-dark">Bank Address</span>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($company) ? $company->bank_account_address : null; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 tx-dark tx-bold">
                        <span class="tx-dark">Swift Code</span>
                    </div>
                    <div class="col-md-6">:
                        <?= isset($company) ? $company->swift_code : null; ?>
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
                <div class="rounded text-center" style="border:2px solid #eee;">
                    <?php $no_image = 'assets/no-image.jpg'; ?>
                    <img src="<?= base_url($company->header ? $path . $company->header : $no_image); ?>" id="preview-header" alt="no-image" class="border-0 mx-wd-100p">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-2 tx-dark tx-bold">
                <label for="longitude">Watermark</label>
            </div>
            <div class="col-md-10">
                <div class="rounded text-center wd-100p" style="border:2px solid #eee;">
                    <img src="<?= base_url($company->watermark ? $path . $company->watermark : $no_image); ?>" id="preview-watermark" height="auto" alt="no-image" class="border-0 mx-wd-100p">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-2 tx-dark tx-bold">
                <label for="longitude">Footer</label>
            </div>
            <div class="col-md-10">
                <div class="rounded text-center" style="border:2px solid #eee;">
                    <img src="<?= base_url($company->footer ? $path . $company->footer : $no_image); ?>" id="preview-footer" alt="no-image" class="border-0 mx-wd-100p">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-body" id="dataForm">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="employee_code" class="tx-dark tx-bold">Employee ID <span class="tx-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="hidden" class="form-control" id="id" name="id" value="<?= (isset($employee) && $employee->id) ? $employee->id : null; ?>">
                    <input type="text" class="form-control" id="employee_code" required name="employee_code" value="<?= (isset($employee)) ? $employee->employee_code : null; ?>" maxlength="10" placeholder="Employee ID">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="name" class="tx-dark tx-bold">Employee Name <span class="tx-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" id="name" required name="name" value="<?= (isset($employee) && $employee->name) ? $employee->name : null; ?>" placeholder="Employee Name">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="nik" class="tx-dark tx-bold">Personal ID Number</label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" id="nik" name="nik" value="<?= (isset($employee)) ? $employee->nik : null; ?>" maxlength="16" placeholder="NIK">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="birth_place" class="tx-dark tx-bold">Birth Place</label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" id="birth_place" name="birth_place" value="<?= (isset($employee) && $employee->birth_place) ? $employee->birth_place : null; ?>" placeholder="Birth Place">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="birth_date" class="tx-dark tx-bold">Birth Date</label>
                </div>
                <div class="col-md-7">
                    <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?= (isset($employee) && $employee->birth_date) ? $employee->birth_date : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="division" class="tx-dark tx-bold">Division <span class="tx-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <div id="slWrapperDivision" class="parsley-select">
                        <select id="division" name="division" class="form-control select" required data-parsley-inputs data-parsley-class-handler="#slWrapperDivision" data-parsley-errors-container="#slErrorContainerDivision">
                            <option value=""></option>
                            <?php foreach ($divisions as $div) : ?>
                                <option value="<?= $div->id ?>" <?= (isset($employee) && $div->id == $employee->division) ? 'selected' : ''; ?>><?= $div->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="slErrorContainerDivision"></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="gender" class="tx-dark tx-bold">Gender <span class="tx-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <div id="slWrapperGender" class="parsley-select">
                        <select id="gender" name="gender" class="form-control select" required data-parsley-inputs data-parsley-class-handler="#slWrapperGender" data-parsley-errors-container="#slErrorContainerGender">
                            <option value=""></option>
                            <option value="L" <?= (isset($employee) && $employee->gender == 'L') ? 'selected' : ''; ?>>Laki-Laki</option>
                            <option value="P" <?= (isset($employee) && $employee->gender == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                    <div id="slErrorContainerGender"></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="religion" class="tx-dark tx-bold">Religion</label>
                </div>
                <div class="col-md-7">
                    <select id="religion" name="religion" class="form-control select">
                        <option value=""></option>
                        <?php foreach ($religions as $religion) { ?>
                            <option value="<?= $religion->id ?>" <?= (isset($employee) && $religion->id == $employee->religion) ? 'selected' : ''; ?>><?= ucfirst(strtolower($religion->name_religion)) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="tgl_pengangkatan" class="tx-dark tx-bold">Tanggal Pengangkatan</label>
                </div>
                <div class="col-md-7">
                    <input type="date" name="tgl_pengangkatan" id="tgl_pengangkatan" class="form-control" value="<?= (isset($employee) && $employee->tgl_pengangkatan) ? $employee->tgl_pengangkatan : null ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="tgl_resign" class="tx-dark tx-bold">Tanggal Resign</label>
                </div>
                <div class="col-md-7">
                    <input type="date" name="tgl_resign" id="tgl_resign" class="form-control" value="<?= (isset($employee) && $employee->tgl_resign) ? $employee->tgl_resign : null ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="" class="tx-dark tx-bold">Pendidikan Terakhir</label>
                </div>
                <div class="col-md-7">
                    <select name="pendidikan_terakhir" id="" class="form-control">
                        <option value="">- Pendidikan Terakhir -</option>
                        <option value="SD" <?= (isset($employee) && $employee->pendidikan_terakhir == "SD") ? 'selected' : null ?>>SD</option>
                        <option value="SMP" <?= (isset($employee) && $employee->pendidikan_terakhir == "SMP") ? 'selected' : null ?>>SMP</option>
                        <option value="SMA" <?= (isset($employee) && $employee->pendidikan_terakhir == "SMA") ? 'selected' : null ?>>SMA</option>
                        <option value="D3" <?= (isset($employee) && $employee->pendidikan_terakhir == "D3") ? 'selected' : null ?>>D3</option>
                        <option value="D4" <?= (isset($employee) && $employee->pendidikan_terakhir == "D4") ? 'selected' : null ?>>D4</option>
                        <option value="S1" <?= (isset($employee) && $employee->pendidikan_terakhir == "S1") ? 'selected' : null ?>>S1</option>
                        <option value="S2" <?= (isset($employee) && $employee->pendidikan_terakhir == "S2") ? 'selected' : null ?>>S2</option>
                        <option value="S3" <?= (isset($employee) && $employee->pendidikan_terakhir == "S3") ? 'selected' : null ?>>S3</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="tgl_resign" class="tx-dark tx-bold">Status Pernikahan</label>
                </div>
                <div class="col-md-7">
                    <select name="status_pernikahan" id="" class="form-control">
                        <option value="">- Status Pernikahan -</option>
                        <option value="1" <?= (isset($employee) && $employee->status_pernikahan == "1") ? 'selected' : null ?>>Menikah</option>
                        <option value="2" <?= (isset($employee) && $employee->status_pernikahan == "2") ? 'selected' : null ?>>Belum Menikah</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="no_kartu_keluarga" class="tx-dark tx-bold">No. Kartu Keluarga </label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" id="no_kartu_keluarga" required name="no_kartu_keluarga" value="<?= (isset($employee) && $employee->no_kartu_keluarga) ? $employee->no_kartu_keluarga : null; ?>" placeholder="No. Kartu Keluarga">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="no_bpjs" class="tx-dark tx-bold">No. BPJS Kesehatan & Ketenagakerjaan </label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" id="no_bpjs" name="no_bpjs" value="<?= (isset($employee) && $employee->no_bpjs) ? $employee->no_bpjs : null; ?>" placeholder="No. BPJS Kesehatan & Ketenagakerjaan">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="phone_number" class="tx-dark tx-bold">Phone Number <span class="tx-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" id="phone_number" required name="phone_number" value="<?= (isset($employee) && $employee->phone_number) ? $employee->phone_number : null; ?>" placeholder="+62...">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="no_darurat" class="tx-dark tx-bold">Nomor Telp Darurat</label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" id="no_darurat" name="no_darurat" value="<?= (isset($employee) && $employee->no_darurat) ? $employee->no_darurat : null; ?>" placeholder="+62...">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="email" class="tx-dark tx-bold">Email</label>
                </div>
                <div class="col-md-7">
                    <input type="email" class="form-control" id="email" name="email" value="<?= (isset($employee) && $employee->email) ? $employee->email : null; ?>" placeholder="email@domain.com">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="" class="tx-dark tx-bold">Address</label>
                </div>
                <div class="col-md-7">
                    <textarea type="text" class="form-control" id="address" name="address" placeholder="Address"><?= (isset($employee) && $employee->address) ? $employee->address : null; ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="residence_address" class="tx-dark tx-bold">Alamat Domisili</label>
                </div>
                <div class="col-md-7">
                    <textarea type="text" class="form-control" id="residence_address" name="residence_address" placeholder="Alamat Domisili"><?= (isset($employee) && $employee->residence_address) ? $employee->residence_address : null; ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="npwp_number" class="tx-dark tx-bold">NPWP</label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" id="npwp_number" name="npwp_number" value="<?= (isset($employee) && $employee->npwp_number) ? $employee->npwp_number : null; ?>" placeholder="NPWP Number">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="joint_ate" class="tx-dark tx-bold">Join Date</label>
                </div>
                <div class="col-md-7">
                    <input type="date" class="form-control" id="joint_date" name="joint_date" value="<?= (isset($employee) && $employee->joint_date) ? $employee->joint_date : null; ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="employee_type" class="tx-dark tx-bold">Employee Type <span class="tx-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <div id="slWrapperEmployeeType" class="parsley-select">
                        <select id="employee_type" name="employee_type" class="form-control select" required data-parsley-inputs data-parsley-class-handler="#slWrapperEmployeeType" data-parsley-errors-container="#slErrorContainerEmployeeType">
                            <option value=""></option>
                            <option value="Kontrak" <?= isset($employee) && $employee->employee_type == 'Kontrak' ? 'selected' : ''; ?>>Kontrak</option>
                            <option value="Tetap" <?= isset($employee) && $employee->employee_type == 'Tetap' ? 'selected' : ''; ?>>Tetap</option>
                        </select>
                    </div>
                    <div id="slErrorContainerEmployeeType"></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="job_description" class="tx-dark tx-bold">Job Desc.</label>
                </div>
                <div class="col-md-7">
                    <textarea class="form-control" id="job_description" name="job_description" placeholder="Job Description"><?= (isset($employee) && $employee->job_description) ? $employee->job_description : null; ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="statusActive">Status <span class="tx-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <div id="cbWrapperStatus" class="parsley-checkbox mg-b-0">
                        <label class="rdiobox rdiobox-success d-inline-block mg-r-5">
                            <input type="radio" id="statusActive" checked <?= isset($employee) && $employee->status == '1' ? 'checked' : null; ?> name="status" value="1" data-parsley-required="true" data-parsley-inputs data-parsley-class-handler="#cbWrapperStatus" data-parsley-errors-container="#cbErrorContainerStatus">
                            <span>Active</span>
                        </label>
                        <label class="rdiobox rdiobox-danger d-inline-block mg-r-5">
                            <input type="radio" id="statusInactive" <?= isset($employee) && $employee->status == '0' ? 'checked' : null; ?> name="status" value="0">
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
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#bank_info" role="tab">BANK
                Information</a>
        </li>
    </ul>
    <div class="tab-pane fade active show" id="bank_info">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="bank_name">Bank Name</label>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="bank_name" value="<?= isset($employee) ? $employee->bank_name : null; ?>" name="bank_name" placeholder="Bank Name">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="bank_account_number">Account Number</label>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="bank_account_number" value="<?= isset($employee) ? $employee->bank_account_number : null; ?>" name="bank_account_number" placeholder="Account Number">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="bank_account_name">Account Name</label>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="bank_account_name" value="<?= isset($employee) ? $employee->bank_account_name : null; ?>" name="bank_account_name" placeholder="Account Name">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="bank_account_address">Bank Address</label>
                </div>
                <div class="col-md-6">
                    <textarea type="text" name="bank_account_address" id="bank_account_address" class="form-control input-sm w70" placeholder="Bank Address"><?= isset($employee) ? $employee->bank_account_address : null; ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2 tx-dark tx-bold">
                    <label for="swift_code">Swift Code</label>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="swift_code" value="<?= isset($employee) ? $employee->swift_code : null; ?>" name="swift_code" placeholder="Swift Code">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select').select2({
            placeholder: "Choose one",
            allowClear: true,
            width: "100%",
            dropdownParent: $("#dataForm"),
            minimumResultsForSearch: -1
        });
    });
</script>
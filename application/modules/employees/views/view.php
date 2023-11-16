<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Employee ID</span>
                </div>
                <div class="col-md-7 tx-bold tx-dark">:
                    <?= (isset($employee)) ? $employee->employee_code : null; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Employee Name</span>
                </div>
                <div class="col-md-7 tx-bold tx-dark">:
                    <?= (isset($employee) && $employee->name) ? $employee->name : null; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Personal ID Number</span>
                </div>
                <div class="col-md-7">:
                    <?= (isset($employee)) ? $employee->nik : null; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Birth Place</span>
                </div>
                <div class="col-md-7">:
                    <?= (isset($employee) && $employee->birth_place) ? $employee->birth_place : null; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Birth Date</span>
                </div>
                <div class="col-md-7">:
                    <?= (isset($employee) && $employee->birth_date) ? $employee->birth_date : null; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Division</span>
                </div>
                <div class="col-md-7">:
                    <?= $ArrDiv[$employee->division] ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Gender</span>
                </div>
                <div class="col-md-7">:
                    <?= (isset($employee) && $employee->gender == 'L') ? 'Laki-laki' : 'Perempuan'; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Religion</span>
                </div>
                <div class="col-md-7">:
                    <?= isset($employee->religion) ? $ArrRel[$employee->religion] : '-' ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Phone Number</span>
                </div>
                <div class="col-md-7">:
                    <?= (isset($employee) && $employee->phone_number) ? $employee->phone_number : null; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Email</span>
                </div>
                <div class="col-md-7">:
                    <?= (isset($employee) && $employee->email) ? $employee->email : null; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Address</span>
                </div>
                <div class="col-md-7">:
                    <?= (isset($employee) && $employee->address) ? $employee->address : null; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">NPWP</span>
                </div>
                <div class="col-md-7">:
                    <?= (isset($employee) && $employee->npwp_number) ? $employee->npwp_number : null; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Join Date</span>
                </div>
                <div class="col-md-7">:
                    <?= (isset($employee) && $employee->joint_date) ? $employee->joint_date : null; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Employee Type</span>
                </div>
                <div class="col-md-7">:
                    <?= isset($employee) && $employee->employee_type == 'Kontrak' ? 'Kontrak' : 'Tetap'; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span class="tx-dark tx-bold">Job Desc.</span>
                </div>
                <div class="col-md-7">:
                    <?= (isset($employee) && $employee->job_description) ? $employee->job_description : null; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 tx-dark tx-bold">
                    <span>Status</span>
                </div>
                <div class="col-md-7">:
                    <?= isset($employee) && $employee->status == '1' ? 'Active' : 'Inactive'; ?>
                </div>
            </div>
        </div>
    </div>
</div>
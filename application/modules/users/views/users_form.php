<div class="br-pagetitle">
	<i class="icon icon ion-ios-personadd-outline"></i>
	<div>
		<h4>New User</h4>
		<p class="mg-b-0">Lorem ipsum dolor sit.</p>
	</div>
</div>
<div class="br-pagebody pd-x-20 pd-sm-x-30 mg-y-3 pd-t-30">
	<?= Template::message(); ?>
	<form action="<?= base_url($this->uri->uri_string()) ?>" method="POST" id="frm_users" name="frm_users" data-parsley-validate="">
		<div class="card mg-b-0">
			<div class="card-body pd-30">
				<h5>User Account</h5>
				<div class="row mg-t-20">
					<div class="col-xl-12">
						<div class="row">
							<label for="username" class="col-md-3 control-label"><?= lang('users_username') ?> <span class="tx-danger">*</span></label>
							<div class="col-sm-8 mg-t-10 mg-sm-t-0">
								<input type="text" class="form-control" required id="username" placeholder="<?= lang('users_username') ?>" name="username" maxlength="45" value="<?= set_value('username', isset($data->username) ? $data->username : ''); ?>" autofocus />
							</div>
						</div>
						<div class="row mg-t-20">
							<label for="password" class="col-md-3 control-label"><?= lang('users_password') ?> <span class="tx-danger">*</span></label>
							<div class="col-sm-8 mg-t-10 mg-sm-t-0">
								<input type="password" class="form-control" data-parsley-length="[4, 32]" id="password" placeholder="<?= lang('users_password') ?>" name="password" maxlength="100" value="<?= set_value('password') ?>" />
							</div>
						</div>
						<div class="row mg-t-20">
							<label for="re-password" class="col-sm-3 control-label">Confirm Password <span class="tx-danger">*</span></label>
							<div class="col-sm-8 mg-t-10 mg-sm-t-0">
								<input type="password" class="form-control" data-parsley-length="[4, 32]" placeholder="Confirm Password" id="re-password" name="re-password" maxlength="100" value="<?= set_value('re-password') ?>" />
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="card mg-t-10">
			<div class="card-body">
				<h5>User Profile</h5>
				<div class="row mg-t-20">
					<label for="full_name" class="col-sm-3 control-label">Full Name User <span class="tx-danger">*</span></label>
					<div class="col-sm-8 mg-t-10 mg-sm-t-0">
						<input type="text" class="form-control" required placeholder="Full Name" id="full_name" name="full_name" maxlength="100" value="<?= set_value('full_name', isset($data->full_name) ? $data->full_name : ''); ?>" />
					</div>
				</div>
				<div class="row mg-t-20">
					<label for="email" class="col-sm-3 control-label"><?= lang('users_email') ?> <span class="tx-danger">*</span></label>
					<div class="col-sm-8 mg-t-10 mg-sm-t-0">
						<input type="email" class="form-control" required placeholder="your_mail@mail.com" id="email" name="email" maxlength="100" value="<?= set_value('email', isset($data->email) ? $data->email : ''); ?>" />
					</div>
				</div>
				<div class="row mg-t-20">
					<label for="address" class="col-sm-3 control-label">Address</label>
					<div class="col-sm-8 mg-t-10 mg-sm-t-0">
						<textarea class="form-control" id="address" placeholder="Address" name="address" maxlength="255"><?= set_value('address', isset($data->address) ? $data->address : ''); ?></textarea>
					</div>
				</div>
				<div class="row mg-t-20">
					<label for="city" class="col-sm-3 control-label">City</label>
					<div class="col-sm-8 mg-t-10 mg-sm-t-0">
						<input type="text" class="form-control" placeholder="City" id="city" name="city" maxlength="20" value="<?= set_value('city', isset($data->city) ? $data->city : ''); ?>" />
					</div>
				</div>
				<div class="row mg-t-20">
					<label for="phone" class="col-sm-3 control-label">Phone Number</label>
					<div class="col-sm-8 mg-t-10 mg-sm-t-0">
						<input type="text" class="form-control" placeholder="+62 ..." id="phone" name="phone" maxlength="20" value="<?= set_value('phone', isset($data->phone) ? $data->phone : ''); ?>" />
					</div>
				</div>
				<div class="row mg-t-20">
					<label for="status" class="col-sm-3 control-label">Status</label>
					<div class="col-sm-8 mg-t-10 mg-sm-t-0">
						<select name="status" id="status" class="form-control select2">
							<option value="1" <?= set_select('status', 1, isset($data->status) && $data->status == 1) ?>>Active</option>
							<option value="0" <?= set_select('status', 0, isset($data->status) && $data->status == 0) ?>>Inactive</option>
						</select>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="offset-sm-3 col-sm-8">
						<div class="d-flex justify-content-between">
							<button type="submit" name="save" class="btn-oblong btn btn-primary wd-100"><i class="fa fa-save mg-r-5"></i>Save</button>
							<a href="<?= base_url('users/setting'); ?>" class="btn-oblong btn btn-danger wd-100"><i class="fa fa-reply mg-r-5" aria-hidden="true"></i>Back</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
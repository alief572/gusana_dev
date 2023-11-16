<div class="br-pagetitle">
	<i class="fas fa-user-shield tx-60 lh-0 text-primary"></i>
	<div>
		<h4>User Permission</h4>
		<p class="mg-b-0">Lorem ipsum dolor sit.</p>
	</div>
</div>

<div class="br-pagebody pd-x-20 pd-sm-x-30 mg-y-3 pd-t-30">
	<!-- form start -->
	<?= form_open($this->uri->uri_string(), array('id' => 'frm_users', 'name' => 'frm_users', 'role' => 'form', 'class' => 'form-horizontal')) ?>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-lg-6 col-12">
					<div class="row <?= form_error('username') ? ' has-error' : ''; ?>">
						<label for="username" class="col-sm-2 control-label"><?= lang('users_username') ?></label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="username" name="username" maxlength="45" value="<?= set_value('username', isset($data->username) ? $data->username : ''); ?>" readonly />
						</div>
					</div>
					<div class="row mg-t-20 <?= form_error('full_name') ? ' has-error' : ''; ?>">
						<label for="full_name" class="col-sm-2 control-label"><?= lang('users_full_name') ?></label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="full_name" name="full_name" maxlength="100" value="<?= set_value('full_name', isset($data->full_name) ? $data->full_name : ''); ?>" readonly />
						</div>
					</div>
				</div>
			</div>
			<div class="row mg-t-20 mg-t-lg-20">
				<div class="col-lg-6 col-12">
					<div class="row">
						<div class="offset-sm-2 col-lg-6 col-12">
							<button type="submit" name="save" class="btn btn-primary wd-100 btn-oblong shadow"><i class="fa fa-save mg-r-5"></i>Save</button>
							<a href="<?= base_url('users/setting'); ?>" class="btn btn-danger wd-100 btn-oblong shadow"><i class="fa fa-reply mg-r-5" aria-hidden="true"></i>Back</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card mg-t-15">
		<div class="card-body">
			<label class="ckbox ckbox-success d-flex justify-content-start">
				<input type="checkbox" class="d-none" name="chk_all" id="chk_all">
				<span class="mg-l-15">Check All</span>
			</label>
			<table id="" class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th><?= lang('users_facility_name') ?></th>
						<?php foreach ($header as $key => $hd) : ?>
							<th class="text-center"><?= $hd ?></th>
						<?php endforeach ?>
					</tr>
				</thead>

				<tbody id="listDetail">
					<?php
					$no = 1;
					foreach ($permissions as $key => $pr) :
					?>
						<tr>
							<td><?= $no ?></td>
							<td><?php echo $pr['View']['nm']; ?></td>
							<?php foreach ($pr as $key2 => $action) : ?>
								<td class="text-center">
									<?php if ($action == 0) : ?>
										-
									<?php else : ?>
										<input type="checkbox" name="id_permissions[]" value="<?= $action['perm_id'] ?>" title="<?= $action['action_name'] ?>" <?= ($action['value'] == 1) ? "checked='checked'" : '' ?> <?= ($action['is_role_permission'] == 1) ? "disabled='disabled'" : '' ?> />
									<?php endif ?>
								</td>
							<?php endforeach ?>
						</tr>
					<?php $no++;
					endforeach ?>
				</tbody>

				<tfoot>
					<tr>
						<th>#</th>
						<th><?= lang('users_facility_name') ?></th>
						<?php foreach ($header as $key => $hd) : ?>
							<th class="text-center"><?= $hd ?></th>
						<?php endforeach ?>
					</tr>
				</tfoot>
			</table>
		</div>
		<div class="card-footer bg-white d-flex justify-content-between">
			<button type="submit" name="save" class="btn btn-primary wd-100 mg-r-3 btn-oblong"><i class="fa fa-save mg-r-5"></i>Save</button>
			<a href="<?= base_url('users/setting'); ?>" class="btn btn-danger wd-100 btn-oblong"><i class="fa fa-reply mg-r-5" aria-hidden="true"></i>Back</a>
		</div>
	</div>
	<?= form_close() ?>
</div>

</div><!-- /.box -->
<!-- page script -->
<script>
	$(document).ready(function() {
		$('#example1').DataTable({
			"lengthMenu": [
				[100, -1],
				[100, "All"]
			]
		});
		$("td").click(function(e) {
			var chk = $(this).find("input:checkbox").get(0);
			if (e.target != chk) {
				chk.checked = !chk.checked;
			}
		});

		$('#chk_all').click(function() {
			if ($('#chk_all').is(':checked')) {
				$('#listDetail').find('input[type="checkbox"]').each(function() {
					$(this).prop('checked', true);
				});
			} else {
				$('#listDetail').find('input[type="checkbox"]').each(function() {
					$(this).prop('checked', false);
				});
			}
		});


	});
</script>
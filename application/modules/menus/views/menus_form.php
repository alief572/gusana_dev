<div class="row">
    <?php if (isset($data->id)) {
        $type = 'edit';
    } ?>
    <input type="hidden" id="type" name="type" value="<?= isset($type) ? $type : 'add' ?>">
    <input type="hidden" id="id" name="id" value="<?php echo set_value('id', isset($data->id) ? $data->id : ''); ?>">

    <label for="id" class="col-sm-2">ID Menu <span class="tx-danger">*</span></label>
    <div class="col-sm-3">
        <input type="text" class="form-control" id="id" name="id" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"
            maxlength="4" value="<?php echo set_value('id', isset($data->id) ? $data->id : ''); ?>"
            placeholder="ID menu" readonly>
    </div>

    <label for="title" class="col-sm-2">Menu <span class="tx-danger">*</span></label>
    <div class="col-sm-3">
        <input type="text" class="form-control" id="title" name="title" maxlength="45"
            value="<?php echo set_value('title', isset($data->title) ? $data->title : ''); ?>" placeholder="Menu's Name"
            required>
    </div>
</div>

<div class="row mg-t-20">
    <label for="link" class="col-sm-2">Path Menu <span class="tx-danger">*</span>
    </label>
    <div class="col-sm-3">
        <input type="text" class="form-control" id="link" name="link"
            value="<?php echo set_value('link', isset($data->link) ? $data->link : ''); ?>" placeholder="Link" required>
    </div>

    <label for="link" class="col-sm-2">Parent Menu <span class="tx-danger">*</span></label>

    <div class="col-sm-3">
        <select name="parent_id" id="parent_id" class="select form-control parent_id">
            <option value=""></option>
            <?php if ($parent) foreach ($parent as $k => $par) : ?>
            <option value="<?= $k; ?>" <?= ($k == $parent) ? 'selected' : ''; ?>><?= $par ; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="row mg-t-20">
    <label for="link" class="col-sm-2">Group Menu <span class="tx-danger">*</span></label>
    <div class="col-sm-3">
        <select class="form-control select group_menu" data-placeholder="Choose An Options" name="group_menu"
            id="group_menu">
            <option value=""></option>
            <option value="1" <?= (isset($data->group_menu) && $data->group_menu == '1') ? 'selected' : ''; ?>>Back End
            </option>
            <option value="2" <?= (isset($data->group_menu) && $data->group_menu == '2') ? 'selected' : ''; ?>>Front End
            </option>
        </select>
    </div>
    <label for="parent_id" class="col-sm-2">Icon <span class="tx-danger">*</span></label>
    <div class="col-sm-3">
        <input type="text" class="form-control" id="icon" name="icon"
            value="<?php echo set_value('icon', isset($data->icon) ? $data->icon : ''); ?>" placeholder="Icon menu"
            required>
    </div>
</div>
<div class="row mg-t-20">
    <label for="target" class="col-sm-2">Target</label>
    <div class="col-sm-3">
        <select id="target" name="target" class="form-control select">
            <option value=""></option>
            <option value="_blank"
                <?= set_select('target', '_blank', isset($data->target) && $data->target == '_blank'); ?>>
                Blank
            </option>
            <option value="sametab"
                <?= set_select('target', 'sametab', isset($data->target) && $data->target == 'sametab'); ?>>
                Same Tab
            </option>
        </select>
    </div>
    <label for="status" class="col-sm-2">Status</label>
    <div class="col-sm-2">
        <select id="status" name="status" class="form-control select">
            <option value="1" <?= set_select('status', '1', isset($data->status) && $data->status == '1'); ?>>
                Active
            </option>
            <option value="0" <?= set_select('status', '0', isset($data->status) && $data->status == '0'); ?>>
                Inactive
            </option>
        </select>
    </div>
</div>
<div class="row mg-t-20">
    <label for="parent_id" class="col-sm-2">Order <span class="tx-danger">*</span></label>
    <div class="col-sm-3">
        <input type="text" class="form-control" id="order" name="order"
            value="<?php echo set_value('order', isset($data->order) ? $data->order : ''); ?>" placeholder="order menu"
            required>
    </div>
    <?php if (isset($data->id)) : ?>
    <label for="link" class="col-sm-2">Permission ID <span class="tx-danger">*</span></label>
    <div class="col-sm-3">
        <?php
                $permission[0]    = 'Select An Option';
                echo form_dropdown('permission_id', $permission, set_value('permission_id', isset($data->permission_id) ? $data->permission_id : 'selected'), array('id' => 'permission_id', 'class' => 'form-control select permission_id'));
                ?>
    </div>
    <?php endif; ?>
</div>


<style>
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 2.7rem;
    display: block;
}

.select2-container .select2-selection--single .select2-selection__clear {
    z-index: 5;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('.select').select2({
        minimumResultsForSearch: -1,
        placeholder: 'Choose one',
        dropdownParent: $('#dialog-popup'),
        width: "100%",
        allowClear: true
    });
});
</script>
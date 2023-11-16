<?php
$ENABLE_ADD     = has_permission('Users.Add');
$ENABLE_MANAGE  = has_permission('Users.Manage');
$ENABLE_DELETE  = has_permission('Users.Delete');
?>
<div class="br-pagetitle">
    <i class="icon ion-ios-list-outline"></i>
    <div>
        <h4>User Manager</h4>
        <p class="mg-b-0">Dashboard cards are used in an overview or summary of a project, for crm or form cms.</p>
    </div>
</div><!-- d-flex -->
<div class="d-flex align-items-center justify-content-between pd-x-20 pd-sm-x-30 pd-t-25 mg-b-20 mg-sm-b-30">

    <div class="btn-group hidden-sm-down">
        <?php if ($ENABLE_ADD) : ?>
            <a class="btn btn-primary wd-150 btn-oblong" title="Add" href="<?= site_url('users/setting/create') ?>"><i class="icon ion-plus">&nbsp;</i>Add User</a>
        <?php endif; ?>
    </div><!-- btn-group -->
</div>
<div class="br-pagebody pd-x-20 pd-sm-x-30 mg-y-3">
    <div class="card bd-gray-400">
        <div class="table-wrapper">
            <table id="dataTable" width="100%" class="table mg-b-0 border-left-0 border-right-0 responsive display">
                <thead>
                    <tr>
                        <th width="30">#</th>
                        <th><?= lang('users_username') ?></th>
                        <th><?= lang('users_nm_lengkap') ?></th>
                        <th><?= lang('users_email') ?></th>
                        <th><?= lang('users_alamat') ?></th>
                        <th><?= lang('users_kota') ?></th>
                        <th><?= lang('users_hp') ?></th>
                        <th><?= lang('users_st_aktif') ?></th>
                        <?php if ($ENABLE_MANAGE) : ?>
                            <th width="80"></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th width="30">#</th>
                        <th><?= lang('users_username') ?></th>
                        <th><?= lang('users_nm_lengkap') ?></th>
                        <th><?= lang('users_email') ?></th>
                        <th><?= lang('users_alamat') ?></th>
                        <th><?= lang('users_kota') ?></th>
                        <th><?= lang('users_hp') ?></th>
                        <th><?= lang('users_st_aktif') ?></th>
                        <?php if ($ENABLE_MANAGE) : ?>
                            <th width="80"></th>
                        <?php endif; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
</div>

<!-- page script -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "bAutoWidth": true,
            "destroy": true,
            // "responsive": true,
            "language": {
                "sSearch": "",
                'searchPlaceholder': 'Search...',
                'processing': `<div class="sk-wave">
                  <div class="sk-rect sk-rect1 bg-gray-800"></div>
                  <div class="sk-rect sk-rect2 bg-gray-800"></div>
                  <div class="sk-rect sk-rect3 bg-gray-800"></div>
                  <div class="sk-rect sk-rect4 bg-gray-800"></div>
                  <div class="sk-rect sk-rect5 bg-gray-800"></div>
                </div>`,
                "sLengthMenu": "Display _MENU_",
                "sInfo": "Display <b>_START_</b> to <b>_END_</b> From <b>_TOTAL_</b> data",
                "sInfoFiltered": "(filtered from _MAX_ total entries)",
                "sZeroRecords": "<i>Data tidak tersedia</i>",
                "sEmptyTable": "<i>Data tidak ditemukan</i>",
                "oPaginate": {
                    "sPrevious": "<i class='fa fa-arrow-left' aria-hidden='true'></i>",
                    "sNext": "<i class='fa fa-arrow-right' aria-hidden='true'></i>"
                }
            },
            "aaSorting": [
                [1, "asc"]
            ],
            "columnDefs": [{
                "targets": 'no-sort',
                "orderable": false,
            }],
            "sPaginationType": "simple_numbers",
            "iDisplayLength": 10,
            "aLengthMenu": [5, 10, 20, 50, 100, 150],
            "ajax": {
                url: siteurl + thisController + 'setting/getData',
                type: "post",
                data: function(d) {
                    d.status = '1'
                },
                cache: false,
                error: function() {
                    $(".my-grid-error").html("");
                    $("#my-grid").append(
                        '<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                    );
                    $("#my-grid_processing").css("display", "none");
                }
            }
        });
    })
</script>
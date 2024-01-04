<?php
$ENABLE_ADD     = has_permission('Penawaran.Add');
$ENABLE_MANAGE  = has_permission('Penawaran.Manage');
$ENABLE_VIEW    = has_permission('Penawaran.View');
$ENABLE_DELETE  = has_permission('Penawaran.Delete');
?>

<div class="br-pagetitle">
    <i class="tx-primary fa-4x <?= $template['page_icon']; ?>"></i>
    <div>
        <h4>Penawaran</h4>
    </div>
</div><!-- d-flex -->

<div class="d-flex align-items-center justify-content-between pd-x-20 pd-sm-x-30 pd-t-25 mg-b-20 mg-sm-b-30">
    <?php echo Template::message(); ?>
    <?php if ($ENABLE_ADD) : ?>
        <a class="btn btn-primary btn-oblong add" href="penawaran/add/new" title="Add"><i class="fa fa-plus">&nbsp;</i>Add Penawaran</a>
        <!-- <button type="button" class="btn btn-success btn-oblong" onclick="loadData()" title="Add"><i class="fa fa-plus">&nbsp;</i>Refresh</button> -->
    <?php endif; ?>
</div>

<div class="br-pagebody pd-x-20 pd-sm-x-30 mg-y-3">
    <div class="card bd-gray-400">
        <div class="table-wrapper">
            <table id="dataTable" width="100%" class="table display table-bordered table-hover table-striped border-left-0 border-right-0">
                <thead>
                    <tr>
                        <th width="15" class="text-center">No</th>
                        <th class="desktop tablet mobile tx-bold tx-dark" width="">No. Penawaran</th>
                        <th class="desktop tablet mobile tx-bold tx-dark" width="">Nama Customer</th>
                        <th class="desktop tablet mobile tx-bold tx-dark" width="">Nama Marketing</th>
                        <th class="desktop tablet mobile tx-bold tx-dark" width="">Nilai Penawaran</th>
                        <th class="desktop tablet mobile tx-bold tx-dark" width="">Tanggal Penawaran</th>
                        <th class="desktop tablet mobile tx-bold tx-dark" width="">Revisi</th>
                        <th class="desktop tablet mobile tx-bold tx-dark" width="">Status</th>
                        <?php if ($ENABLE_MANAGE) : ?>
                            <th class="desktop text-center no-sort" width="110">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>No. Penawaran</th>
                        <th>Nama Customer</th>
                        <th>Nama Marketing</th>
                        <th>Nilai Penawaran</th>
                        <th>Tanggal Penawaran</th>
                        <th>Revisi</th>
                        <th>Status</th>
                        <?php if ($ENABLE_MANAGE) : ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<div class="modal fade effect-scale" id="dialog-popup" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="data-form" method="post" data-parsley-validate>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title tx-bold text-dark" id="myModalLabel"></h4>
                    <button type="button" class="btn btn-default close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn wd-100 btn btn-primary" name="save" id="save"><i class="fa fa-save"></i>
                        Save</button>
                    <!-- <button type="button" class="btn wd-100 btn btn-warning" name="request_app" id="request_app"><i class="fa fa-save"></i>
                        Request Approval</button> -->
                    <button type="button" class="btn wd-100 btn btn-danger" data-dismiss="modal">
                        <span class="fa fa-times"></span> Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- page script -->
<script type="text/javascript">

    
    
    $(document).ready(function() {
        loadData()
    })

    $(document).on('click', '.add', function() {
        
        $.ajax({
            type: 'POST',
            url: siteurl + thisController + 'add',
            success: function(data) {
                // $('#dialog-popup .modal-title').html("<span class='<?= $template['page_icon']; ?>'></span>&nbsp;Add Penawaran")
                // $('#dialog-popup .modal-dialog').css({
                //     'max-width': '90%'
                // })
                // $("#dialog-popup").modal();
                // $("#dialog-popup .modal-body").html(data);
                // $("#save").removeClass('d-none');

                // $("#save").removeClass('btn-warning');
                // $("#save").addClass('btn-primary');
                // $("#save").html('<i class="fa fa-save"></i> Save');
            }
        })
    });

    $(document).on('click', '.edit', function(e) {
        var id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: siteurl + thisController + 'edit/' + id,
            success: function(data) {
                $('#dialog-popup .modal-title').html("<span class='<?= $template['page_icon']; ?>'></span>&nbsp;Edit Penawaran")
                $('#dialog-popup .modal-dialog').css({
                    'max-width': '90%'
                })
                $("#dialog-popup").modal();
                $("#dialog-popup .modal-body").html(data);
                $("#save").removeClass('d-none');

                $("#save").removeClass('btn-warning');
                $("#save").addClass('btn-primary');
                $("#save").html('<i class="fa fa-save"></i> Save');
            }
        })
    });

    $(document).on('click', '.request_approval', function(e) {
        var id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: siteurl + thisController + 'request_approval/' + id,
            success: function(data) {
                // $('#dialog-popup .modal-title').html("<span class='<?= $template['page_icon']; ?>'></span>&nbsp;Request Approval Penawaran")
                // $('#dialog-popup .modal-dialog').css({
                //     'max-width': '90%'
                // })
                // $("#dialog-popup").modal();
                // $("#dialog-popup .modal-body").html(data);
                // $("#save").removeClass('d-none');
                // $("#save").removeClass('btn-primary');
                // $("#save").addClass('btn-warning');
                // $("#save").html('<i class="fa fa-check"></i> Request');
            }
        })
    });

    $(document).on('click', '.view', function(e) {
        var id = $(this).data('id');
        id = id.split('/').join('-');
        $.ajax({
            type: 'GET',
            url: siteurl + thisController + 'view/' + id,
            success: function(data) {
                $('#dialog-popup .modal-title').html("<span class='<?= $template['page_icon']; ?>'></span>&nbsp;View Penawaran")
                $('#dialog-popup .modal-dialog').css({
                    'max-width': '75%'
                })
                $("#dialog-popup").modal();
                $("#dialog-popup .modal-body").html(data);
                $("#save").addClass('d-none');
            }
        })
    });

    $(document).on('click', '.delete', function(e) {
        e.preventDefault()
        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mg-r-10 wd-100',
                cancelButton: 'btn btn-danger wd-100'
            },
            buttonsStyling: false
        })
        let id = $(this).data('id');
        swalWithBootstrapButtons.fire({
            title: "Confirm!",
            text: "Are you sure to delete this data?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "<i class='fa fa-check'></i> Yes",
            cancelButtonText: "<i class='fa fa-ban'></i> No",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    type: 'POST',
                    url: siteurl + thisController + 'delete',
                    dataType: "JSON",
                    data: {
                        'id': id
                    },
                    error: function() {
                        Lobibox.notify('error', {
                            title: 'Error!!!',
                            icon: 'fa fa-times',
                            position: 'top right',
                            showClass: 'zoomIn',
                            hideClass: 'zoomOut',
                            soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                            msg: 'Internal server error. Ajax process failed.'
                        });
                    }
                })
            },
            allowOutsideClick: true
        }).then((val) => {
            if (val.isConfirmed) {
                if (val.value.status == '1') {
                    Lobibox.notify('success', {
                        title: 'Success',
                        icon: 'fa fa-check',
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                        msg: val.value.msg
                    });
                    $('#dialog-popup').modal('hide')
                    loadData()

                } else {
                    Lobibox.notify('warning', {
                        title: 'Warning',
                        icon: 'fa fa-ban',
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                        msg: val.value.msg
                    });
                };
            }
        })
    })


    $(document).on('submit', '#data-form', function(e) {
        e.preventDefault();

        var req_approval = $('.req_approval').val();
        // alert(req_approval);
        var msg = 'Are you sure to save this data ?';
        if(req_approval == '1'){
            msg = 'Are you sure to make request approval ?'
        }

        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mg-r-10 wd-100',
                cancelButton: 'btn btn-danger wd-100'
            },
            buttonsStyling: false
        })

        let formData = new FormData($('#data-form')[0]);
        swalWithBootstrapButtons.fire({
            title: "Anda Yakin?",
            text: msg,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "<i class='fa fa-check'></i> Yes",
            cancelButtonText: "<i class='fa fa-ban'></i> No",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    type: 'POST',
                    url: siteurl + thisController + 'save',
                    dataType: "JSON",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    error: function() {
                        Lobibox.notify('error', {
                            title: 'Error!!!',
                            icon: 'fa fa-times',
                            position: 'top right',
                            showClass: 'zoomIn',
                            hideClass: 'zoomOut',
                            soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                            msg: 'Internal server error. Ajax process failed.'
                        });
                    }
                })
            },
            allowOutsideClick: true
        }).then((val) => {
            console.log(val);
            if (val.isConfirmed) {
                if (val.value.status == '1') {
                    Lobibox.notify('success', {
                        icon: 'fa fa-check',
                        msg: val.value.msg,
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                    });
                    $("#dialog-popup").modal('hide');
                    loadData()
                    $('.dataTables_length select').select2({
                        minimumResultsForSearch: -1
                    })
                } else {
                    Lobibox.notify('warning', {
                        icon: 'fa fa-ban',
                        msg: val.value.msg,
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                    });
                };
            }
        })

    })

    function loadData() {

        var oTable = $('#dataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "bAutoWidth": true,
            "destroy": true,
            "responsive": true,
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
                "sInfo": "Display <b>_START_</b> to <b>_END_</b> from <b>_TOTAL_</b> data",
                "sInfoFiltered": "(filtered from _MAX_ total entries)",
                // "sZeroRecords": "<i>Data tidak tersedia</i>",
                // "sEmptyTable": "<i>Data tidak ditemukan</i>",
                "oPaginate": {
                    "sPrevious": "<i class='fa fa-arrow-left' aria-hidden='true'></i>",
                    "sNext": "<i class='fa fa-arrow-right' aria-hidden='true'></i>"
                }
            },
            "responsive": {
                "breakpoints": [{
                        "name": 'desktop',
                        "width": Infinity
                    },
                    {
                        "name": 'tablet',
                        "width": 1148
                    },
                    {
                        "name": 'mobile',
                        "width": 680
                    },
                    {
                        "name": 'mobile-p',
                        "width": 320
                    }
                ],
            },
            "aaSorting": [
                [1, "asc"]
            ],
            "columnDefs": [{
                    "targets": 'no-sort',
                    "orderable": false,
                }, {
                    "targets": 'text-center',
                    "className": 'text-center',
                }, {
                    "targets": 'tx-bold tx-dark',
                    "className": 'tx-bold tx-dark',
                }

            ],
            "sPaginationType": "simple_numbers",
            "iDisplayLength": 10,
            "aLengthMenu": [5, 10, 20, 50, 100, 150],
            "ajax": {
                url: siteurl + thisController + 'getData',
                type: "post",
                data: function(d) {
                    d.status = 'D'
                },
                cache: false,
                error: function() {
                    $(".my-grid-error").html("");
                    $("#my-grid").append(
                        '<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                    );
                    $("#my-grid_processing").css("display", "none");
                },

            }
        });
    }
</script>
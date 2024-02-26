<?php
$ENABLE_ADD     = has_permission('Finish_Good.Add');
$ENABLE_MANAGE  = has_permission('Finish_Good.Manage');
$ENABLE_VIEW    = has_permission('Finish_Good.View');
$ENABLE_DELETE  = has_permission('Finish_Good.Delete');
?>

<div class="br-pagetitle">
    <i class="tx-primary fa-4x <?= $template['page_icon']; ?>"></i>
    <div>
        <h4>Finish Goods</h4>
        <p class="mg-b-0"></p>
    </div>
</div><!-- d-flex -->

<div class="d-flex align-items-center justify-content-between pd-x-20 pd-sm-x-30 pd-t-25 mg-b-20 mg-sm-b-30">
    <?php echo Template::message(); ?>
    <?php if ($ENABLE_ADD) : ?>
        <button class="btn btn-primary btn-oblong add" href="javascript:void(0)" title="Add"><i class="fa fa-plus">&nbsp;</i>Propose</button>
        <!-- <button type="button" class="btn btn-success btn-oblong" onclick="loadData()" title="Add"><i class="fa fa-plus">&nbsp;</i>Refresh</button> -->
    <?php endif; ?>
</div>

<div class="br-pagebody pd-x-20 pd-sm-x-30 mg-y-3">
    <div class="card bd-gray-400">
        <div class="table-responsive">
            <table id="dataTable" width="100%" class="table display table-bordered table-hover table-striped border-left-0 border-right-0">
                <thead>
                    <tr>
                        <th class="text-center"></th>
                        <th class="text-center">No</th>
                        <th>Product Name</th>
                        <th>Packing</th>
                        <th>Konversi (kg)</th>
                        <th>Actual Stock (kg)</th>
                        <th>Actual Stock (kaleng)</th>
                        <th>Stock Booking (kaleng)</th>
                        <th>Free Stock (kaleng)</th>
                        <th>Minimun Stock (kaleng)</th>
                        <th>MOQ (kg)</th>
                        <th>Propose (kg)</th>
                    </tr>
                </thead>
                <tbody></tbody>
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
                        Create SO</button>
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

        var pilih = [];
        $('.pilih:checked').each(function() {
            pilih.push({
                'id_category3': $(this).val(),
                'propose': $('.propose_' + $(this).val()).val()
            });
        });
        // console.log(pilih);
        if ($.isEmptyObject(pilih)) {
            Lobibox.notify('error', {
                title: 'Error!!!',
                icon: 'fa fa-times',
                position: 'top right',
                showClass: 'zoomIn',
                hideClass: 'zoomOut',
                soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                msg: 'Please check at least one product to propose !'
            });
        } else {
            $.ajax({
                type: 'POST',
                url: siteurl + thisController + 'add',
                data: {
                    'list_id_category3': pilih
                },
                success: function(data) {
                    $('#dialog-popup .modal-title').html("<span class='<?= $template['page_icon']; ?>'></span>&nbsp;Propose")
                    $('#dialog-popup .modal-dialog').css({
                        'max-width': '90%'
                    })
                    $("#dialog-popup").modal();
                    $("#dialog-popup .modal-body").html(data);
                    $("#save").removeClass('d-none');
                }
            })
        }

    });

    $(document).on('click', '.edit', function(e) {
        var id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: siteurl + thisController + 'edit/' + id,
            success: function(data) {
                $('#dialog-popup .modal-title').html("<span class='<?= $template['page_icon']; ?>'></span>&nbsp;Edit Divisi")
                $('#dialog-popup .modal-dialog').css({
                    'max-width': '90%'
                })
                $("#dialog-popup").modal();
                $("#dialog-popup .modal-body").html(data);
                $("#save").removeClass('d-none');
            }
        })
    });

    $(document).on('click', '.view', function(e) {
        var id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: siteurl + thisController + 'view/' + id,
            success: function(data) {
                $('#dialog-popup .modal-title').html("<span class='<?= $template['page_icon']; ?>'></span>&nbsp;Edit Divisi")
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

    $(document).on('change', '.pilih', function() {
        var id_category3 = $(this).data('id_category3')
        var moq = $(this).data('moq')
        var qty_asli = $(this).data('qty_asli')
        var min_stok = $(this).data('min_stok')

        if ($('.pilih_' + id_category3).is(':checked')) {
            $('.propose_' + id_category3).attr('readonly', false)
            if (qty_asli < min_stok || qty_asli == 0) {
                $('.propose_' + id_category3).val(moq)
            }
        } else {
            $('.propose_' + id_category3).val(0)
            $('.propose_' + id_category3).attr('readonly', true)
        }
    })

    $(document).on('submit', '#data-form', function(e) {
        e.preventDefault()
        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mg-r-10 wd-100',
                cancelButton: 'btn btn-danger wd-100'
            },
            buttonsStyling: false
        })

        let formData = new FormData($('#data-form')[0]);
        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "Are you sure to make SO from this product ?.",
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
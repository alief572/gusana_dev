<?php
$ENABLE_ADD     = has_permission('Master_Barang_Stok.Add');
$ENABLE_MANAGE  = has_permission('Master_Barang_Stok.Manage');
$ENABLE_VIEW    = has_permission('Master_Barang_Stok.View');
$ENABLE_DELETE  = has_permission('Master_Barang_Stok.Delete');
?>
<div class="br-pagetitle">
    <i class="tx-primary fa-4x <?= $template['page_icon']; ?>"></i>
    <div>
        <h4><?= $template['title']; ?></h4>
        <p class="mg-b-0">Lorem ipsum dolor sit amet.</p>
    </div>
</div><!-- d-flex -->
<div class="d-flex align-items-center justify-content-between pd-x-20 pd-sm-x-30 pd-t-25 mg-b-20 mg-sm-b-30">
    <?php echo Template::message(); ?>
    <div class="btn-group hidden-sm-down">
        <a class="btn btn-primary btn-oblong add" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-plus">&nbsp;</i>Add Barang Stok</a>
    </div><!-- btn-group -->
</div>

<div class="br-pagebody pd-x-20 pd-sm-x-30 mg-y-3">
    <div class="card bd-gray-400">
        <div class="table-wrapper">
            <table id="dataTable" class="table table-bordered display table-striped border-right-0 border-left-0" width="100%">
                <thead>
                    <tr>
                        <th width="10" class="text-center">No</th>
                        <th>Item Code</th>
                        <th>Barang Stok</th>
                        <th>Kategori Stok</th>
                        <th>Trade Name</th>
                        <th>Brand</th>
                        <th>Spec</th>
                        <th>Last By</th>
                        <th>Last Date</th>
                        <th>Status</th>
                        <th width="100" class="text-center desktop">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th width="10">#</th>
                        <th>Item Code</th>
                        <th>Barang Stok</th>
                        <th>Kategori Stok</th>
                        <th>Trade Name</th>
                        <th>Brand</th>
                        <th>Spec</th>
                        <th>Last By</th>
                        <th>Last Date</th>
                        <th>Status</th>
                        <th width="100" class="text-center">Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="modal fade effect-scale" id="dialog-popup" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="data-form" method="post" data-parsley-validate>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title tx-dark" id="myModalLabel"><span class="<?= $template['page_icon']; ?>"></span></h4>
                    <button type="button" class="btn btn-default close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn wd-100 btn-primary" id="save"><i class="fa fa-save mg-r-3"></i>
                        Save</button>
                    <button type="button" class="btn btn-danger wd-100" data-dismiss="modal">
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
        $("#dialog-popup .modal-body").load(siteurl + thisController + 'add');
        $("#dialog-popup .modal-title").html('<i class="<?= $template['page_icon']; ?>" aria-hidden="true"></i> Add New Barang Stok');
        $("#dialog-popup").modal();
        $("#save").removeClass('d-none');
    });

    $(document).on('click', '.edit', function() {
        let id = $(this).data('id');
        if (id) {
            $("#dialog-popup .modal-body").load(siteurl + thisController + 'edit/' + id);
            $("#dialog-popup .modal-title").html('<i class="<?= $template['page_icon']; ?>" aria-hidden="true"></i> Edit Barang Stok');
        } else {
            $("#dialog-popup .modal-body").html("<h5 class='text-center'>Data tidak valid</h5>");
        }
        $("#dialog-popup").modal();
        $("#save").removeClass('d-none');
    });

    $(document).on('click', '.view', function() {
        let id = $(this).data('id');
        if (id) {
            $("#dialog-popup .modal-body").load(siteurl + thisController + 'view/' + id);
            $("#dialog-popup .modal-title").html('<i class="<?= $template['page_icon']; ?>" aria-hidden="true"></i> View Barang Stok');
        } else {
            $("#dialog-popup .modal-body").html("<h5 class='text-center'>Data tidak valid</h5>");
        }
        $("#dialog-popup").modal();
        $("#save").addClass('d-none');
    });

    $(document).on('click', '.delete', function() {
        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mg-r-10 wd-100',
                cancelButton: 'btn btn-danger wd-100'
            },
            buttonsStyling: false
        })

        let id = $(this).data('id')
        swalWithBootstrapButtons.fire({
            title: "Confirm",
            text: "Are you sure to Delete this data Barang Stok?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "<i class='fa fa-check'></i> Yes",
            cancelButtonText: "<i class='fa fa-ban'></i> No",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    url: siteurl + thisController + 'delete',
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        id
                    },
                    error: function() {
                        Lobibox.notify('error', {
                            icon: 'fa fa-times',
                            position: 'top right',
                            showClass: 'zoomIn',
                            hideClass: 'zoomOut',
                            soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                            msg: 'Internal server error. Server timeout'
                        });
                    }
                });
            },
            allowOutsideClick: true
        }).then((val) => {
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
        });
    });

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
            title: "Confirm",
            text: "Are you sure to save this data Barang Stok?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "<i class='fa fa-check'></i> Yes",
            cancelButtonText: "<i class='fa fa-ban'></i> No",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: siteurl + thisController + 'save',
                    type: "POST",
                    data: formData,
                    cache: false,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    error: function() {
                        Lobibox.notify('error', {
                            icon: 'fa fa-times',
                            position: 'top right',
                            showClass: 'zoomIn',
                            hideClass: 'zoomOut',
                            soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                            msg: 'Internal server error. Server timeout'
                        });
                    }
                });
            },
            allowOutsideClick: true
        }).then((val) => {
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
        });
    });


    /* PIC */

    $(document).on('click', '.del-item', function() {
        $(this).parents('tr').fadeOut('slow').css('background-color', '#000')
        setTimeout(() => {
            $(this).parents('tr').remove()
        }, 500);
    })

    $(document).on('click', '#add-pic', function() {
        var n = 0;
        n = $('table#list-pic tbody tr').length + 1;
        var html = '';
        html += `<tr id="tr_` + n + `" style="background-color:#fff5de">
						<td class="text-center"><i class="fa fa-plus tx-10" aria-hidden="true"></i>
						<td><input type="text" class="form-control input-sm" name="PIC[` + n + `][name]" placeholder="PIC Name"></td> 
						<td><input type="text" class="form-control input-sm" name="PIC[` + n + `][phone_number]" placeholder="Phone Number"></td> 
						<td><input type="text" class="form-control input-sm" name="PIC[` + n + `][email]" placeholder="Email"></td> 
						<td><input type="text" class="form-control input-sm" name="PIC[` + n + `][position]" placeholder="Position"></td>
						<td class="text-center"><button type="button" class="btn btn-sm btn-warning del-item" title="Hapus Data" data-role="qtip"><i class="fa fa-times"></i></button></td>
					</tr>`;
        $('table#list-pic tbody').append(html);
    });

    $(document).on('click', '.editPic', function() {
        let n = 0
        n = $('tr.rowEditPIC').length + 1
        let id = $(this).data('id')
        let row = $(this).parents('tr')
        let editRow = $('<tr id="edit_' + n + '" class="rowEditPIC">')
        let newRow = ''

        let col1 = row.find('td:eq(1)').text()
        let col2 = row.find('td:eq(2)').text()
        let col3 = row.find('td:eq(3)').text()
        let col4 = row.find('td:eq(4)').text()
        newRow += `
        <td class="text-center"><i class="fa fa-edit"></i>
        <input type="hidden"class="form-control" readonly name="PIC[` + n + `][id]" value="` + id + `">
        </td>
        <td><input type="text" class="form-control input-sm" name="PIC[` + n + `][name]" placeholder="PIC Name" value="` + col1 + `"></td>
        <td><input type="text" class="form-control input-sm" name="PIC[` + n + `][phone_number]" placeholder="Phone Number" value="` + col2 + `"></td>
        <td><input type="text" class="form-control input-sm" name="PIC[` + n + `][email]" placeholder="Email" value="` + col3 + `"></td>
        <td><input type="text" class="form-control input-sm" name="PIC[` + n + `][position]" placeholder="Position" value="` + col4 + `"></td>
        <td class="text-center">
        <button type="button" class="btn btn-sm btn-warning cancelEditPIC" title="Cancel Edit" data-toggle="tooltip"><i class="fa fa-times"></i></button>
        </td>
        `
        // alert(col1)
        editRow.append(newRow);
        editRow.insertAfter(row.closest('tr'));
        row.hide()
    })

    $(document).on('click', '.cancelEditPIC', function() {
        let prevRow = $(this).parents('tr').prev()
        $(this).parents('tr').remove()
        prevRow.show()
    })

    $(document).on('click', '.deletePic', function() {
        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mg-r-10 wd-100',
                cancelButton: 'btn btn-danger wd-100'
            },
            buttonsStyling: false
        })
        const btn = $(this)
        let id = $(this).data('id')
        swalWithBootstrapButtons.fire({
            title: "Confirm",
            text: "Are you sure to Delete this data PIC Customer?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "<i class='fa fa-check'></i> Yes",
            cancelButtonText: "<i class='fa fa-ban'></i> No",
        }).then((val) => {
            if (val.isConfirmed) {
                $.ajax({
                    url: siteurl + thisController + 'deletePic',
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        id
                    },
                    success: function(result) {
                        if (result.status == '1') {
                            Lobibox.notify('success', {
                                icon: 'fa fa-check',
                                msg: result.msg,
                                position: 'top right',
                                showClass: 'zoomIn',
                                hideClass: 'zoomOut',
                                soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                            });
                            btn.parents('tr').addClass('bg-danger').fadeOut('slow').css('background-color', '#000')
                        } else {
                            Lobibox.notify('warning', {
                                icon: 'fa fa-ban',
                                msg: result.msg,
                                position: 'top right',
                                showClass: 'zoomIn',
                                hideClass: 'zoomOut',
                                soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                            });
                        };
                    },
                    error: function() {
                        Lobibox.notify('error', {
                            icon: 'fa fa-times',
                            position: 'top right',
                            showClass: 'zoomIn',
                            hideClass: 'zoomOut',
                            soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                            msg: 'Internal server error. Server timeout'
                        });
                    }
                });
            }
        });
    });


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
                "sZeroRecords": "<i>Data tidak tersedia</i>",
                "sEmptyTable": "<i>Data tidak ditemukan</i>",
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
                }, {
                    "targets": 'tx-bold tx-dark text-center',
                    "className": 'tx-bold tx-dark text-center',
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
                }
            }
        });

    }
</script>
<?php
$ENABLE_ADD     = has_permission('menus.Add');
$ENABLE_MANAGE  = has_permission('menus.Manage');
$ENABLE_VIEW    = has_permission('menus.View');
$ENABLE_DELETE  = has_permission('menus.Delete');
?>
<div class="br-pagetitle">
    <i class="icon ion-ios-list-outline"></i>
    <div>
        <h4>Manu Manager</h4>
        <p class="mg-b-0">Lorem ipsum dolor sit amet.</p>
    </div>
</div><!-- d-flex -->

<div class="d-flex align-items-center justify-content-between pd-x-20 pd-sm-x-30 pd-t-25 mg-b-20 mg-sm-b-30">
    <?php echo Template::message(); ?>
    <div class="btn-group hidden-sm-down">
        <?php if ($ENABLE_ADD) : ?>
            <button class="btn btn-primary wd-150 btn-oblong add-menu" title="Add" data-toggle="tooltip" data-placement="top"><i class="icon ion-plus">&nbsp;</i>Add Menu</button>
        <?php endif; ?>
    </div><!-- btn-group -->

    <!-- <div class="hidden-xs-down">
        <input type="text" placeholder="Search..." id="searchInput" class="form-control wd-300">
    </div> -->
    <!-- btn-group -->


    <!-- START: DISPLAYED FOR MOBILE ONLY -->
    <!-- <div class="hidden-sm-up">
        <input type="text" placeholder="Search..." id="searchInputMobile" class="form-control">
    </div> -->
    <!-- btn-group -->

    <div class="dropdown hidden-md-up">
        <?php if ($ENABLE_ADD) : ?>
            <button class="btn btn-info wd-100 add-menu" title="Add"><i class="fa fa-plus">&nbsp;</i>Add
                Menu</button>
        <?php endif; ?>
    </div><!-- dropdown -->
    <!-- END: DISPLAYED FOR MOBILE ONLY -->

</div><!-- d-flex -->


<div class="br-pagebody pd-x-20 pd-sm-x-30 mg-y-3">
    <div class="card bd-gray-400">
        <div class="table-wrapper">
            <table id="dataTable" width="100%" class="table mg-b-0 table-sm border-left-0 border-right-0 responsive display">
                <thead class="bg-default">
                    <tr>
                        <th width="5">#</th>
                        <th class="p-2 desktop mobile tablet">Nama Menu</th>
                        <th class="p-2 desktop tablet">Link</th>
                        <th class="p-2 desktop">Parent</th>
                        <th class="p-2 desktop">Icon</th>
                        <th class="p-2 desktop">Permission</th>
                        <th class="p-2 desktop">Status</th>
                        <?php if ($ENABLE_MANAGE) : ?>
                            <th width="" class="p-2 desktop text-center">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Nama Menu</th>
                        <th>Link</th>
                        <th>Parent</th>
                        <th>Icon</th>
                        <th>Permission</th>
                        <th>Status</th>
                        <?php if ($ENABLE_MANAGE) : ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div><!-- card -->
</div>

<!-- awal untuk modal dialog -->
<!-- Modal Body -->
<div class="modal fade effect-scale" id="dialog-popup" tabindex="-1" data-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width:75%">
        <?= form_open($this->uri->uri_string(), array('id' => 'frm_menus', 'name' => 'frm_menus', 'role' => 'form', 'class' => 'form-horizontal')) ?>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-t`itle"></h5>
                <button type="button" class="btn btn- btn-sm btn-icon close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="submit" name="save" class="btn btn-primary wd-100" id="submit"><i class="fa fa-save">&nbsp;</i>Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>
<!-- <button type="button" class="btn btn-primary" id="basicDefault">Test</button> -->

<!-- page script -->
<script type="text/javascript">
    /* error  = sound9 */
    /* warning  = sound14 */
    /* success  = sound18 */
    /* info  = sound7 */

    $(document).ready(function() {

        loadData()
    })

    $(document).on('click', '.add-menu', function() {
        $("#dialog-popup .modal-title").text('Add New Menu');
        $("#dialog-popup .modal-body").load(siteurl + thisController + 'create');
        $("#dialog-popup").modal('show');
        $("#title").focus();

    })

    $(document).on('click', '.edit', function() {
        let id = $(this).data('id')
        $("#dialog-popup .modal-title").text('Edit New Menu');
        $("#dialog-popup .modal-body").load(siteurl + thisController + 'edit/' + id);
        $("#dialog-popup").modal('show');
        $("#title").focus();

    })


    //Delete
    $(document).on('click', '.delete', function() {
        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mg-r-10 wd-100',
                cancelButton: 'btn btn-danger wd-100'
            },
            buttonsStyling: false
        })
        let id = $(this).data('id')
        if (id) {
            swalWithBootstrapButtons.fire({
                title: "Confirm",
                text: "Are you sure to delete this data?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "<i class='fa fa-check'></i> Yes",
                cancelButtonText: "<i class='fa fa-ban'></i> No",
            }).then((val) => {
                if (val.isConfirmed) {
                    $.ajax({
                        url: siteurl + thisController + 'delete',
                        dataType: "JSON",
                        type: 'POST',
                        data: {
                            id
                        },
                        success: function(result) {
                            if (result.status == '1') {
                                Lobibox.notify('success', {
                                    icon: 'fa fa-check',
                                    position: 'top right',
                                    showClass: 'zoomIn',
                                    hideClass: 'zoomOut',
                                    soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                                    msg: 'Lorem ipsum dolor sit amet against apennine any created, spend loveliest, building stripes. Slight fallen one opportunity dyspepsia, puzzled quickening throbbing row worm numerous sagittis wreaths.'
                                });
                                loadData()
                                $('.dataTables_length select').select2({
                                    // containerCs  sClass: 'select2-outline-success',
                                    // dropdownCssClass: 'select2-hidden-accessible hover-success',
                                    minimumResultsForSearch: -1
                                })
                            } else {
                                Lobibox.notify('warning', {
                                    icon: 'fa fa-ban',
                                    position: 'top right',
                                    showClass: 'zoomIn',
                                    hideClass: 'zoomOut',
                                    soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                                    msg: 'Lorem ipsum dolor sit amet against apennine any created, spend loveliest, building stripes. Slight fallen one opportunity dyspepsia, puzzled quickening throbbing row worm numerous sagittis wreaths.'
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
                                msg: 'Lorem ipsum dolor sit amet against apennine any created, spend loveliest, building stripes. Slight fallen one opportunity dyspepsia, puzzled quickening throbbing row worm numerous sagittis wreaths.'
                            });
                        }
                    });
                }
            })
        }
    })

    $('#frm_menus').on('submit', function(e) {
        e.preventDefault();
        var formdata = $("#frm_menus").serialize();
		var base_url			= '<?php echo base_url(); ?>';
		var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
        $.ajax({
            url: siteurl + "menus/save_data_menus",
            dataType: "json",
            type: 'POST',
            data: formdata,
            //alert(msg);
            success: function(result) {
                if (result.status == '1') {
                    Lobibox.notify('success', {
                        icon: 'fa fa-check',
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                        msg: 'Lorem ipsum dolor sit amet against apennine any created, spend loveliest, building stripes. Slight fallen one opportunity dyspepsia, puzzled quickening throbbing row worm numerous sagittis wreaths.'
                    });
                    $('#dialog-popup').modal('hide')
                    $('#dialog-popup .modal-body').html('')
                    loadData()
                    $('.dataTables_length select').select2({
                        // containerCs  sClass: 'select2-outline-success',
                        // dropdownCssClass: 'select2-hidden-accessible hover-success',
                        minimumResultsForSearch: -1
                    })
					
					window.location.href = base_url + active_controller;
                } else {
                    Lobibox.notify('warning', {
                        icon: 'fa fa-ban',
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                        msg: 'Lorem ipsum dolor sit amet against apennine any created, spend loveliest, building stripes. Slight fallen one opportunity dyspepsia, puzzled quickening throbbing row worm numerous sagittis wreaths.'
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
                    msg: 'Lorem ipsum dolor sit amet against apennine any created, spend loveliest, building stripes. Slight fallen one opportunity dyspepsia, puzzled quickening throbbing row worm numerous sagittis wreaths.'
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
            }],
            "sPaginationType": "simple_numbers",
            "iDisplayLength": 10,
            "aLengthMenu": [5, 10, 20, 50, 100, 150],
            "ajax": {
                url: siteurl + thisController + 'getData',
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

    }
</script>
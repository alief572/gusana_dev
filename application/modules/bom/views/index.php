<?php
$ENABLE_ADD     = has_permission('Bom.Add');
$ENABLE_MANAGE  = has_permission('Bom.Manage');
$ENABLE_VIEW    = has_permission('Bom.View');
$ENABLE_DELETE  = has_permission('Bom.Delete');
?>

<div class="br-pagetitle">
    <i class="tx-primary fa-4x <?= $template['page_icon']; ?>"></i>
    <div>
        <h4>Bill of Material</h4>
        <p class="mg-b-0">Lorem ipsum dolor sit amet.</p>
    </div>
</div><!-- d-flex -->

<div class="d-flex align-items-center justify-content-between pd-x-20 pd-sm-x-30 pd-t-25 mg-b-20 mg-sm-b-30">
    <?php echo Template::message(); ?>
    <?php if ($ENABLE_ADD) : ?>
        <button class="btn btn-primary btn-oblong add" href="javascript:void(0)" title="Add"><i class="fa fa-plus">&nbsp;</i>Add New BOM</button>
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
                        <th>Product Master</th>
                        <th>Product Code</th>
                        <th>Lot Size (Kg)</th>
                        <th>Waste Product (%)</th>
                        <th>Waste Setting / Cleaning (%)</th>
                        <th>Last By</th>
                        <th>Last Date</th>
                        <?php if ($ENABLE_MANAGE) : ?>
                            <th class="desktop text-center no-sort" width="110">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Product Master</th>
                        <th>Product Code</th>
                        <th>Total Weight (Kg)</th>
                        <th>Waste Product (%)</th>
                        <th>Waste Setting / Cleaning (%)</th>
                        <th>Last By</th>
                        <th>Last Date</th>
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
    <div class="modal-dialog">
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
    });

    $(document).on("change",".get_product_code",function(){
        var product_master = $(this).val();

        $.ajax({
            type : 'POST',
            url : siteurl + thisController + 'get_product_code',
            data : {
                'product_master' : product_master
            },
            dataType : 'JSON',
            success : function(result){
                $(".product_code").val(result.product_code);
            }
        });
    });

    $(document).on('click', '.add', function() {
        $.ajax({
            type: 'POST',
            url: siteurl + thisController + 'add',
            success: function(data) {
                $('#dialog-popup .modal-title').html("<span class='<?= $template['page_icon']; ?>'></span>&nbsp;Add New BOM")
                $('#dialog-popup .modal-dialog').css({
                    'max-width': '90%'
                })
                $("#dialog-popup").modal();
                $("#dialog-popup .modal-body").html(data);
                $("#save").removeClass('d-none');
            }
        })
    });

    $(document).on('click', '.edit', function(e) {
        var id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: siteurl + thisController + 'edit/' + id,
            success: function(data) {
                $('#dialog-popup .modal-title').html("<span class='<?= $template['page_icon']; ?>'></span>&nbsp;Edit BOM")
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
                $('#dialog-popup .modal-title').html("<span class='<?= $template['page_icon']; ?>'></span>&nbsp;View BOM")
                $('#dialog-popup .modal-dialog').css({
                    'max-width': '75%'
                })
                $("#dialog-popup").modal();
                $("#dialog-popup .modal-body").html(data);
                $("#save").addClass('d-none');
            }
        })
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
            text: "Are you sure to Delete this data BOM?",
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
            title: "Anda Yakin?",
            text: "Are you sure to save this data.",
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

    });

    $(document).on("click", ".add_detail_material", function() {
        var id_bom = $(".id_bom").val();
        var material_type = $(".add_material_type").val();
        var material_category = $(".add_material_category").val();
        var weight = $(".add_weight").val();

        if (material_type == '' || material_category == '' || weight == '' || weight < 0) {
            swal.fire({
                icon: 'error',
                title: 'Error !',
                text: 'Sorry, make sure all column has been filled !'
            });
        } else {
            $(".add_detail_material").prop("disabled", true);
            $.ajax({
                type: "POST",
                url: siteurl + thisController + 'add_detail_material',
                data: {
                    'id_bom': id_bom,
                    'proses': material_type,
                    'material_category': material_category,
                    'weight': weight
                },
                dataType: 'JSON',
                cache: false,
                success: function(result) {
                    // console.log(result);
                    $(".add_detail_material").prop("disabled", false);
                    if (result.status == 1) {
                        $(".list_detail_material").html(result.hasil);
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: 'Error !',
                            text: 'Sorry, data has not been saved !',
                            showConfirmButton: false,
                            time: 1500
                        });
                    }
                }
            });
        }
    });

    $(document).on("click",".del_material_detail",function(e){
        var id = $(this).data('id');
        var id_bom = $(".id_bom").val();

        $.ajax({
            type : 'POST',
            url : siteurl + thisController + 'del_material_detail',
            data : {
                'id' : id,
                'id_bom' : id_bom
            },
            cache : true,
            dataType : 'JSON',
            success : function(results){
                if(results.valid == 1){
                    $(".list_detail_material").html(results.hasil);
                }else{
                    Lobibox.notify('warning', {
                        icon: 'fa fa-ban',
                        msg: 'Maaf, hapus detail material gagal !',
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                    });
                }
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
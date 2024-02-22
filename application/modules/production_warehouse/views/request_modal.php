<?php
$ENABLE_ADD     = has_permission('Request_Material.Add');
$ENABLE_MANAGE  = has_permission('Request_Material.Manage');
$ENABLE_VIEW    = has_permission('Request_Material.View');
$ENABLE_DELETE  = has_permission('Request_Material.Delete');

?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="box box-primary">

    <form id="form-data">
        <input type="hidden" name="id_request" class="id_request" value="<?= (isset($data_request)) ? $data_request->id_request : $this->auth->user_id(); ?>">
        <input type="hidden" name="type_post" class="type_post" value="<?= (isset($data_request)) ? 'EDIT' : 'INSERT' ?>">
        <div class="box-body">
            <div class="col-6">
                <table class="w-100">
                    <tr>
                        <th style="vertical-align: top;">From Warehouse</th>
                        <th>
                            <select name="request_dari" id="" class="form-control form-control-sm chosen-select" required>
                                <option value="">- From Warehouse -</option>
                                <?php foreach ($results['list_warehouse_material'] as $warehouse_material) : ?>
                                    <option value="<?= $warehouse_material->id ?>"><?= $warehouse_material->warehouse_nm ?></option>
                                <?php endforeach; ?>
                            </select>
                        </th>
                    </tr>
                    <tr>
                        <th style="vertical-align: top;">To Warehouse</th>
                        <th>
                            <select name="request_ke" id="" class="form-control form-control-sm chosen-select" required>
                                <option value="">- to Warehouse -</option>
                                <?php foreach ($results['list_warehouse_production'] as $warehouse_production) : ?>
                                    <option value="<?= $warehouse_production->id ?>"><?= $warehouse_production->warehouse_nm ?></option>
                                <?php endforeach; ?>
                            </select>
                        </th>
                    </tr>
                    <tr>
                        <th style="vertical-align: top;">Remarks</th>
                        <th>
                            <textarea name="keterangan" id="" cols="30" rows="5" class="form-control form-control-sm"></textarea>
                        </th>
                    </tr>
                </table>
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-sm btn-success btn_add_material"><i class="fa fa-plus"></i> Add Material</button>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Code</th>
                            <th class="text-center">Material Name</th>
                            <th class="text-center">Packing</th>
                            <th class="text-center">Request</th>
                            <th class="text-center">Remarks</th>
                            <th class="text-center">Option</th>
                        </tr>
                    </thead>
                    <tbody class="list_material_request"></tbody>
                </table>
                <div class="text-right">
                    <button type="submit" name="save" class="btn btn-sm btn-success save"><i class="fa fa-save"></i> Save</button>
                    <a href="<?= base_url('production_warehouse/request') ?>" class="btn btn-sm btn-danger"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal  fade" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mx-wd-md-70p-force mx-wd-lg-70p-force">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Material</h4>
            </div>
            <div class="modal-body" id="ModalView">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remove"></span> Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // alert(siteurl + thisController + '/awd');



    $(document).ready(function() {
        $('.chosen-select').select2({
            width: '100%'
        });



        function refresh_detail_table() {
            var id_request = $('.id_request').val();

            $.ajax({
                type: 'post',
                url: siteurl + thisController + 'refresh_detail_table',
                data: {
                    'id_request': id_request
                },
                cache: false,
                dataType: 'json',
                success: function(result) {
                    $('.list_material_request').html(result.hasil);
                }
            });
        }

        refresh_detail_table();

        $(document).on('click', '.btn_add_material', function() {
            var id_request = $('.id_request').val();
            $.ajax({
                type: 'post',
                url: siteurl + thisController + 'add_material',
                data: {
                    'id_request': id_request
                },
                cache: false,
                beforeSend: function(result) {
                    $('.btn_add_material').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                success: function(result) {
                    $('#ModalView').html(result);
                    $('#dialog-popup').modal();

                    $('.btn_add_material').html('<i class="fa fa-plus"></i> Add Material');
                },
                error: function(result) {
                    $('.btn_add_material').html('<i class="fa fa-plus"></i> Add Material');
                }
            });
        });

        $(document).on('click', '.save_material', function() {
            var id_request = $('.id_request').val();
            var id_category1 = $(this).data('id_category1');


            var request_qty = $('.request_qty_' + id_category1).val();
            if (request_qty !== '') {
                request_qty = request_qty.split(',').join('');
                request_qty = parseFloat(request_qty);
            } else {
                var request_qty = 0;
            }

            var keterangan = $('.keterangan_' + id_category1).val();

            // alert(request_qty);
            if (request_qty <= 0 || request_qty == '') {
                Swal.fire({
                    title: 'Warning !',
                    text: 'Qty Request must filled !',
                    icon: 'warning'
                });
            } else {
                $.ajax({
                    type: 'post',
                    url: siteurl + thisController + 'save_material',
                    data: {
                        'id_request': id_request,
                        'id_category1': id_category1,
                        'request_qty': request_qty,
                        'keterangan': keterangan
                    },
                    cache: false,
                    beforeSend: function(result) {
                        $('.save_material_' + id_category1).html('<i class="fa fa-spin fa-spinner"></i>');
                    },
                    success: function(result) {
                        $('.save_material_' + id_category1).html('<i class="fa fa-plus"></i> Add');
                        $('.save_material_' + id_category1).attr('disabled', true);

                        refresh_detail_table();
                    },
                    error: function(result) {
                        Swal.fire({
                            title: 'Failed !',
                            text: 'Sorry, adding material process is failed !',
                            icon: 'error'
                        });
                    }
                });
            }
        });

        $(document).on('click', '.del_req_material', function() {
            var id = $(this).data('id');

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-danger ml-2",
                    cancelButton: "btn btn-secondary"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                icon: 'question',
                title: "Are you sure?",
                text: "Are you sure to delete this material ?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Delete",
                denyButtonText: "Cancel",
                reverseButtons: true
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: siteurl + thisController + 'del_material',
                        data: {
                            'id': id
                        },
                        cache: false,
                        beforeSend: function(result) {
                            $('.del_req_material_' + id).html('<i class="fa fa-spin fa-spinner"></i>');
                        },
                        success: function(result) {
                            refresh_detail_table();
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire("Delete process has been cancelled", "", "info");
                }
            });
        });

        $(document).on('submit', '#form-data', function(e) {
            e.preventDefault();

            Swal.fire({
                icon: 'question',
                title: "Are you sure?",
                text: "Are you sure to delete this material ?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Save",
                denyButtonText: "Cancel",
                reverseButtons: true
            }).then((hasill) => {
                /* Read more about isConfirmed, isDenied below */
                if (hasill.isConfirmed) {
                    var formData = $('#form-data').serialize();
                    $.ajax({
                        type: "POST",
                        url: siteurl + thisController + 'save',
                        data: formData,
                        cache: false,
                        dataType: 'json',
                        beforeSend: function(result) {
                            $('.save').html('<i class="fa fa-spin fa-spinner"></i>');
                        },
                        success: function(result) {
                            $('.save').html('<i class="fa fa-save"></i> Save');

                            if (result.status == '1') {
                                Swal.fire('Success !', 'Save process has been success !', 'success');

                                window.location.href = siteurl + thisController + 'request';
                            } else {
                                Swal.fire('Failed !', 'Save process has failed !', 'error');
                            }
                        }
                    });
                } else if (hasill.isDenied) {
                    Swal.fire("Save process has been cancelled", "", "info");
                }
            });
        });
    });
</script>
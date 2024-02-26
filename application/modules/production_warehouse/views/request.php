<?php
$ENABLE_ADD     = has_permission('Request_Material.Add');
$ENABLE_MANAGE  = has_permission('Request_Material.Manage');
$ENABLE_VIEW    = has_permission('Request_Material.View');
$ENABLE_DELETE  = has_permission('Request_Material.Delete');
$id_bentuk = $this->uri->segment(3);
?>
<style type="text/css">
    thead input {
        width: 100%;
    }
</style>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<div class="box">
    <div class="box-header" style="padding: 10px;">
        <a href="<?= base_url('production_warehouse/add_request'); ?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add Request</a>

        <span class="pull-right">
        </span>
    </div>
    <!-- /.box-header -->
    <!-- /.box-header -->
    <div class="box-body" style="padding: 10px;">
        <table id="dataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">No Transaksi</th>
                    <th class="text-center">Gudang Dari</th>
                    <th class="text-center">Qty Packing</th>
                    <th class="text-center">By</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Status</th>
                    <?php if ($ENABLE_MANAGE) : ?>
                        <th class="text-center">Option</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>

<!-- awal untuk modal dialog -->
<!-- Modal -->


<div class="modal  fade" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mx-wd-md-70p-force mx-wd-lg-70p-force">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><span class="fa fa-users"></span>&nbsp;Data Inventory</h4>
            </div>
            <form action="" class="form-data">
                <div class="modal-body" id="ModalView">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success save_btn" name="save">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove"></span> Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DataTables -->

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>

<!-- page script -->
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

    $(document).ready(function() {
        loadData();
    });

    $(document).on('click', '.add_request', function() {
        $.ajax({
            url: siteurl + active_controller + '/add_request',
            type: 'POST',
            cache: false,
            beforeSend: function(result) {
                $('.add_request').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(result) {
                $('#myModalLabel').html('Add Request Material');
                $('#ModalView').html(result);
                $('#dialog-popup').modal();

                $('.add_request').html('<i class="fa fa-plus"></i> Add Request');
            },
            error: function(result) {
                $('.add_request').html('<i class="fa fa-plus"></i> Add Request');
            }
        });
    });

    $(document).on('click', '.view', function() {
        var id = $(this).data('id');

        $.ajax({
            type: 'post',
            url: siteurl + thisController + 'view',
            data: {
                'id': id
            },
            cache: false,
            beforeSend: function(result) {
                $(this).html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(result) {
                $(this).html('<i class="fa fa-eye"></i>');
                $('.save_btn').hide();

                $('#ModalView').html(result);
                $('#dialog-popup').modal();
            },
            error: function(result) {
                $(this).html('<i class="fa fa-eye"></i>');
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
            "language": {
                "sSearch": "",
                'searchPlaceholder': 'Search...',
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
                url: siteurl + thisController + 'getData_request',
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

    function loadData_reload() {
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
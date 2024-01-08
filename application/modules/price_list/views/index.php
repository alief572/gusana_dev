<?php
$ENABLE_ADD     = has_permission('Price_List.Add');
$ENABLE_MANAGE  = has_permission('Price_List.Manage');
$ENABLE_VIEW    = has_permission('Price_List.View');
$ENABLE_DELETE  = has_permission('Price_List.Delete');
?>
<div class="br-pagetitle">
    <i class="tx-primary fa-4x <?= $template['page_icon']; ?>"></i>
    <div>
        <h4><?= $template['title']; ?></h4>
    </div>
</div><!-- d-flex -->
<div class="d-flex align-items-center justify-content-between pd-x-20 pd-sm-x-30 pd-t-25 mg-b-20 mg-sm-b-30">
    <?php echo Template::message(); ?>
</div>

<div class="br-pagebody pd-x-20 pd-sm-x-30 mg-y-3">
    <div class="card bd-gray-400">
        <div class="table-wrapper">
            <table id="dataTable" class="table table-bordered display table-striped border-right-0 border-left-0" width="100%">
                <thead>
                    <tr>
                        <th width="10" class="text-center">No <span class="text-danger">(不)</span></th>
                        <th class="desktop tablet mobile tx-bold tx-dark text-center">Kode Product <span class="text-danger">(产品型号)</span></th>
                        <th class="desktop tablet mobile tx-bold tx-dark text-center">Nama Product <span class="text-danger">(产品名称)</span></th>
                        <th class="desktop tablet mobile tx-bold tx-dark text-center">Nama Product (Mandarin) <span class="text-danger">(产品名称)</span></th>
                        <th class="desktop tablet mobile tx-bold tx-dark text-center">Packaging <span class="text-danger">(包装规格)</span></th>
                        <th class="desktop tablet mobile tx-bold tx-dark text-center">Lot Size <span class="text-danger">(生产数量)</span></th>
                        <th class="desktop tablet mobile tx-bold tx-dark text-center">Curing Agent <span class="text-danger">(配套固化剂)</span></th>
                        <th class="desktop tablet mobile tx-bold tx-dark text-center">Curing Agent Konversi <span class="text-danger">(辅助固化剂包装规格（公斤）)</span></th>
                        <th class="desktop tablet mobile tx-bold tx-dark text-center">Pricelist / Kg <span class="text-danger">(单价)</span></th>
                        <th class="desktop tablet mobile tx-bold tx-dark text-center">Pricelist / Kaleng <span class="text-danger">(每罐价格)</span></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>




<!-- page script -->
<script type="text/javascript">
    $(document).ready(function() {
        loadData()
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
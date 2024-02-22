<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="box box-primary">
    <div class="box-body">
        <div class="col-12">
            <table id="example1" class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Material Name</th>
                        <th class="text-center">Packing</th>
                        <th class="text-center">Request</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $x = 1;
                    foreach ($results['list_material_stock'] as $material_stock) :
                        $add_dis = 0;
                        $check_material_add = $this->db->get_where('ms_request_material_detail', ['id_request' => $results['id_request'], 'id_category1' => $material_stock->id_category1])->num_rows();

                        if ($check_material_add > 0) {
                            $add_dis = 1;
                        }
                    ?>
                        <tr>
                            <td class="text-center" style=""><?= $x ?></td>
                            <td class="text-center" style=""><?= $material_stock->category_nm ?></td>
                            <td class="text-center" style=""><?= $material_stock->nama ?></td>
                            <td class="text-center" style=""><?= $material_stock->nm_packaging ?></td>
                            <td>
                                <input type="text" name="" id="" class="form-control form-control-sm text-right numeric request_qty_<?= $material_stock->id_category1 ?>">
                            </td>
                            <td>
                                <textarea name="" id="" cols="30" rows="3" class="form-control form-control-sm keterangan_<?= $material_stock->id_category1 ?>"></textarea>
                            </td>
                            <td class="text-center" style="">
                                <button type="button" class="btn btn-sm btn-success save_material save_material_<?= $material_stock->id_category1 ?>" data-id_category1="<?= $material_stock->id_category1 ?>"><i class="fa fa-plus" <?= ($add_dis == '1') ? 'disabled' : null ?>></i> Add</button>
                            </td>
                        </tr>
                    <?php $x++;
                    endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    // DataTable
    $('#example1').dataTable();

    // Auto Numeric
    $('.numeric').autoNumeric('init');
</script>
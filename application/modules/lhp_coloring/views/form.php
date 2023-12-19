<style>
    th {
        border: 1px solid #ccc !important;
    }

    td {
        border: 1px solid #ccc !important;
    }
</style>
<div class="card-body" id="dataForm">
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="">Tanggal SPK</label>
                <input type="date" name="tgl_spk" id="" class="form-control form-control-sm" min="<?= date('Y-m-d') ?>">
            </div>
        </div>
        <div class="col-md-12">
            <table class="w-100">
                <thead>
                    <tr>
                        <th class="text-center" style="vertical-align:middle;" rowspan="2">No</th>
                        <th class="text-center" style="vertical-align:middle;" rowspan="2">No. SO</th>
                        <th class="text-center" style="vertical-align:middle;" rowspan="2">Product Name</th>
                        <th class="text-center" style="vertical-align:middle;" rowspan="2">SO Sisa (kg)</th>
                        <th class="text-center" style="vertical-align:middle;" rowspan="2">MOQ (kg)</th>
                        <th class="text-center" style="vertical-align:middle;" rowspan="2">SPK Batch</th>
                        <th class="text-center" style="vertical-align:middle;" rowspan="2">Cycle Time (menit)</th>
                        <th class="text-center" style="vertical-align:middle;" colspan="2">Total Cycle Time</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="vertical-align:middle;">Menit</th>
                        <th class="text-center" style="vertical-align:middle;">Jam</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 1;
                    foreach ($results as $result) {
                        // echo $result->id_product."<br>";
                        echo '
                            <tr>
                                <td class="text-center">' . $x . '</td>
                                <td class="text-center">' . $result->id_so . '</td>
                                <td class="text-center">' . $result->nm_product . '</td>
                                <td class="text-center">' . number_format($result->sisa_so) . '</td>
                                <td class="text-center">' . ($result->moq) . '</td>
                                <td class="text-center">
                                    <select name="spk_batch" class="form-control form-control-sm">
                                        <option value="">- Select Batch Produksi -</option>
                                        ';

                        foreach ($spk_batch as $batch_spk) {
                            if ($batch_spk->id_produk == $result->id_product) {
                                echo '<option value="' . $batch_spk->qty_hopper . '">' . $batch_spk->qty_hopper . '</option>';
                            }
                        }

                        echo '
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        ';

                        $x++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select').select2({
            placeholder: "Choose one",
            allowClear: true,
            width: "100%",
            dropdownParent: $("#dataForm"),
            minimumResultsForSearch: -1
        });
    });
</script>
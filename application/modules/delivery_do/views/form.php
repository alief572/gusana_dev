<div class="card-body" id="dataForm">
    <input type="hidden" name="id_do" value="<?= $penawaran->id_do ?>">
    <div class="row">
        <div class="col-4">
            <table class="w-100">
                <tr>
                    <th>No. DO</th>
                    <th>:</th>
                    <td><?= $penawaran->id_do ?></td>
                </tr>
                <tr>
                    <th>DO Date</th>
                    <th>:</th>
                    <td><?= date("d F Y", strtotime($penawaran->tgl_create_do)) ?></td>
                </tr>
                <tr>
                    <th>Delivery Date</th>
                    <th>:</th>
                    <td>
                        <input type="date" name="deliver_date" id="" class="form-control form-control-sm" min="<?= date('Y-m-d') ?>">
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Product Name</th>
                        <th class="text-center">Qty Order</th>
                        <th class="text-center">Packing</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Stock Free</th>
                        <th class="text-center">Qty Sent Earlier</th>
                        <th class="text-center">Delivery</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $x = 0;
                    foreach ($list_penawaran_detail as $penawaran_detail) :
                        $x++;

                        $this->db->select('SUM(a.qty) AS booking_stock');
                        $this->db->from('ms_penawaran_detail a');
                        $this->db->join('ms_penawaran b', 'b.id_penawaran = a.id_penawaran');
                        $this->db->where('a.id_penawaran', $penawaran_detail->id_penawaran);
                        $this->db->where('a.id_product', $penawaran_detail->id_product);
                        $this->db->where('b.sts_do IS NULL  ');
                        $nilai_booking_stock = $this->db->get()->row();

                        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $penawaran_detail->id_penawaran])->row();

                        $this->db->select('IF(SUM(a.qty) > 0 AND SUM(a.qty) IS NOT NULL, SUM(a.qty), 0) AS last_delivery');
                        $this->db->from('ms_detail_do a');
                        $this->db->where('a.id_do', $get_penawaran->id_do);
                        $this->db->where('a.id_product', $penawaran_detail->id_product);
                        $get_last_delivery = $this->db->get()->row();
                    ?>
                        <tr>
                            <td class="text-center"><?= $x ?></td>
                            <td class="text-center"><?= $penawaran_detail->nm_product ?></td>
                            <td class="text-center"><?= $penawaran_detail->qty ?></td>
                            <td class="text-center"><?= $penawaran_detail->konversi . ' ' . $penawaran_detail->unit_nm ?></td>
                            <td class="text-center"><?= $penawaran_detail->weight . ' ' . $penawaran_detail->unit_nm ?></td>
                            <td class="text-center"><?= (($penawaran_detail->stock_aktual - $nilai_booking_stock->booking_stock) / $penawaran_detail->konversi) ?></td>
                            <td class="text-center">
                                <?= number_format($get_last_delivery->last_delivery, 2) ?>
                            </td>
                            <td class="text-center">
                                <input type="number" name="deliver_<?= $penawaran_detail->id ?>" id="" class="form-control text-right" value="0">
                            </td>
                        </tr>
                    <?php endforeach; ?>
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
<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped">
                <tr>
                    <th>Kategori Stok</th>
                    <td>:</td>
                    <td colspan="4"><?= $results['barang_stok']->nm_kategori_stok ?></td>
                </tr>
                <tr>
                    <th>Barang Stok</th>
                    <td>:</td>
                    <td colspan="4"><?= $results['barang_stok']->nm_barang_stok ?></td>
                </tr>
                <tr>
                    <th>Item Code</th>
                    <td>:</td>
                    <td><?= $results['barang_stok']->item_code ?></td>
                    <th>Trade Name</th>
                    <td>:</td>
                    <td><?= $results['barang_stok']->trade_name ?></td>
                </tr>
                <tr>
                    <th>Brand</th>
                    <td>:</td>
                    <td><?= $results['barang_stok']->brand ?></td>
                    <th>Spesification</th>
                    <td>:</td>
                    <td><?= $results['barang_stok']->spek ?></td>
                </tr>
                <tr>
                    <th>Packing Unit / Konversi</th>
                    <td>:</td>
                    <td><?= $results['barang_stok']->nm_packaging . ' ' . $results['barang_stok']->konversi ?></td>
                    <th>Unit Measurement</th>
                    <td>:</td>
                    <td><?= $results['barang_stok']->unit_nm ?></td>
                </tr>
                <tr>
                    <th>Maksimum Stok</th>
                    <td>:</td>
                    <td><?= $results['barang_stok']->max_stok ?></td>
                    <th>Minimum Stok</th>
                    <td>:</td>
                    <td><?= $results['barang_stok']->min_stok ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
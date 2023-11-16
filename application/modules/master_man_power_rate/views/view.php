<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-12">
            <table class="table table-striped">
                <tr>
                    <th>Nama Komponen</th>
                    <th>:</th>
                    <td><?= $komp->nm_komp ?></td>
                </tr>
                <tr>
                    <th>Nilai Standard</th>
                    <th>:</th>
                    <td><?= $komp->std_val ?></td>
                </tr>
                <tr>
                    <th>Tipe Komponen</th>
                    <th>:</th>
                    <td><?= $tipe_komponen ?></td>
                </tr>
                <tr>
                    <th>Keteragan</th>
                    <th>:</th>
                    <td><?= $komp->keterangan ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
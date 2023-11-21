<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="id_kurs">ID Kurs</label>
                </div>
                <div class="col-md-8">:
                    <?= isset($id_kurs) ? $id_kurs : null; ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_packaging">Periode</label>
                </div>
                <div class="col-md-8">:
                    <?= '<span style="font-weight:bold;">'.date("d F Y", strtotime($ms_kurs->tgl_periode_awal)) . '</span> S/D <span style="font-weight:bold;">' . date("d F Y",strtotime($ms_kurs->tgl_periode_akhir)).'</span>' ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_packaging">IDR Ke</label>
                </div>
                <div class="col-md-8">:
                    <?= $ms_kurs->curr_to_idr ?>
                </div>
                <div class="col-md-4 tx-dark tx-bold">
                    <label for="nm_packaging">Kurs</label>
                </div>
                <div class="col-md-8">:
                    <?= number_format($ms_kurs->kurs, 2) ?>
                </div>
            </div>
        </div>
    </div>
</div>
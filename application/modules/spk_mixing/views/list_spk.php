<div class="card-body" id="data-form-customer">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No SPK</th>
                        <th>Dibuat Oleh</th>
                        <th>Dibuat Tgl</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $x = 0;
                    foreach ($list_spk as $spk) : $x++; ?>
                        <tr>
                            <td class="text-center"><?= $x ?></td>
                            <td class="text-center"><?= $spk->id_spk ?></td>
                            <td class="text-center"><?= $spk->full_name ?></td>
                            <td class="text-center"><?= date('d F Y', strtotime($spk->tgl_tarik)) ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('spk_mixing/print_checksheet/' . $spk->id_spk) ?>" target="blank" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
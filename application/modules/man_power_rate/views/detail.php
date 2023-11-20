<?php
$ENABLE_ADD     = has_permission('Man_Power_Rate.Add');
$ENABLE_MANAGE  = has_permission('Man_Power_Rate.Manage');
$ENABLE_VIEW    = has_permission('Man_Power_Rate.View');
$ENABLE_DELETE  = has_permission('Man_Power_Rate.Delete');
// print_r($header);

$kurs_rmb = $gaji_pokok->kurs_rmb;
// print_r($header);

$ttl_mp_bulan = 0;
?>

<style>
	th{
		padding : 8px !important;
	}
	td{
		padding : 8px !important;
	}
</style>

<div class="box box-primary">
	<div class="box-body">
		<div class="box-header">
			<span class="pull-right">
				<?php if ($ENABLE_MANAGE) : ?>
					<a class="btn btn-success btn-md" href="<?= base_url('man_power_rate/add') ?>" title="Edit">Edit</a>
				<?php endif; ?>
			</span>
		</div>
		<div class='box box-info'>
			<div class='box-body'>
				<table class='' style="width:70% !important;border:none !important;">
					<thead>
						<tr>
							<th class='text-center' style='width: 5%;'>#</th>
							<th class='text-left' style='width: 30%;'>Salary Direct Man Power</th>
							<th class='text-left' style='width: 2%;'></th>
							<th class='text-right' style='width: 15%;'></th>
							<th class='text-left' style='width: 15%;'></th>
							<th class='text-left'></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center">1</td>
							<td>Gaji Pokok</td>
							<td>Rp</td>
							<td class="text-right"><?= number_format(($gaji_pokok->gaji_pokok), 2) ?></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-center">2</td>
							<td>THR</td>
							<td>Rp</td>
							<td class="text-right"><?= number_format(($gaji_pokok->gaji_pokok / 12), 2) ?></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-center">3</td>
							<td>Cuti + Sakit</td>
							<td>Rp</td>
							<td class="text-right"><?= number_format(($gaji_pokok->gaji_pokok / 12), 2) ?></td>
							<td></td>
						</tr>
						<?php $x = 4;
						foreach ($komp_sdmp as $list_sdmp) : ?>
							<tr>
								<td class="text-center"><?= $x ?></td>
								<td><?= $list_sdmp->nm_komp ?></td>
								<td>Rp</td>
								<td class="text-right"><?= number_format($list_sdmp->nominal, 2) ?></td>
								<td><?= $list_sdmp->keterangan ?></td>
							</tr>
							<?php
							$x++;
							$ttl_mp_bulan += (($gaji_pokok->gaji_pokok / 12) * 2) + $list_sdmp->nominal;
							?>
						<?php endforeach; ?>
					</tbody>

					<thead>
						<tr>
							<th class='text-center' style='width: 5%;'>#</th>
							<th class='text-left' style='width: 30%;'>BPJS</th>
							<th class='text-left' style='width: 2%;'></th>
							<th class='text-right' style='width: 15%;'></th>
							<th class='text-left' style='width: 25%;'>Tarif</th>
							<th class='text-left'></th>

						</tr>
					</thead>
					<tbody>
						<?php $x = 1;
						foreach ($komp_bpjs as $list_bpjs) : ?>
							<tr>
								<td class="text-center"><?= $x ?></td>
								<td><?= $list_bpjs->nm_komp ?></td>
								<td>Rp</td>
								<td class="text-right"><?= number_format($list_bpjs->nominal, 2) ?></td>
								<td><?= $list_bpjs->keterangan ?></td>
							</tr>
							<?php
							$x++;
							$ttl_mp_bulan += ($list_bpjs->nominal);
							?>
						<?php endforeach; ?>
					</tbody>
					<thead>
						<tr>
							<th class='text-center' style='width: 5%;'>#</th>
							<th class='text-left' style='width: 30%;'>Biaya Lain-lain</th>
							<th class='text-left' style='width: 2%;'></th>
							<th class='text-right' style='width: 15%;'></th>
							<th class='text-left' style='width: 25%;'></th>
							<th class='text-right'>Harga per Pcs</th>

						</tr>
					</thead>
					<tbody>
						<?php $x = 1;
						foreach ($komp_bll as $list_bll) : ?>
							<tr>
								<td class="text-center"><?= $x ?></td>
								<td><?= $list_bll->nm_komp ?></td>
								<td>Rp</td>
								<td class="text-right"><?= number_format($list_bll->nominal, 2) ?></td>
								<td><?= $list_bll->keterangan ?></td>
								<td class="text-right"><?= number_format($list_bll->harga_pcs, 2) ?></td>
							</tr>
							<?php
							$x++;
							$ttl_mp_bulan += ($list_bll->nominal);
							?>
						<?php endforeach; ?>
					</tbody>
					<tbody>
						<tr>
							<th></th>
							<th>Total Biaya MP /Bulan</th>
							<th class="text-center">Rp</th>
							<th class="text-right"><?= number_format($gaji_pokok->gaji_pokok + $ttl_mp_bulan, 2) ?></th>
							<th colspan="2"></th>
						</tr>
						<tr>
							<th colspan="6"></th>
						</tr>
						<tr>
							<th></th>
							<th>Upah / Bulan</th>
							<th class="text-center">$</th>
							<th class="text-right"><?= number_format(($gaji_pokok->gaji_pokok + $ttl_mp_bulan) / $gaji_pokok->kurs, 2) ?></th>
							<th>Kurs</th>
							<th class="text-right"><?= number_format($gaji_pokok->kurs, 2) ?></th>
						</tr>
						<tr>
							<th></th>
							<th>Upah / Jam</th>
							<th class="text-center">$</th>
							<th class="text-right"><?= number_format((($gaji_pokok->gaji_pokok + $ttl_mp_bulan) / $gaji_pokok->kurs) / 173, 2) ?></th>
							<th colspan="2"></th>
						</tr>
						<tr>
							<th></th>
							<th>Upah / Bulan</th>
							<th class="text-center">RMB</th>
							<th class="text-right"><?= number_format(($gaji_pokok->gaji_pokok + $ttl_mp_bulan) / $kurs_rmb, 2) ?></th>
							<th>Kurs</th>
							<th class="text-right"><?= number_format($kurs_rmb, 2) ?></th>
						</tr>
						<tr>
							<th></th>
							<th>Upah / Jam</th>
							<th class="text-center">RMB</th>
							<th class="text-right"><?= number_format((($gaji_pokok->gaji_pokok + $ttl_mp_bulan) / $kurs_rmb) / 173, 2) ?></th>
							<th colspan="2"></th>
						</tr>
						<tr>
							<th></th>
							<th>Upah / Jam</th>
							<th class="text-center">Rp</th>
							<th class="text-right"><?= number_format((($gaji_pokok->gaji_pokok + $ttl_mp_bulan) / 173), 2) ?></th>
							<th colspan="2"></th>
						</tr>
						<tr>
							<th colspan="6"></th>
						</tr>
						<tr>
							<th></th>
							<th>Dibulatkan</th>
							<th class="text-center">$</th>
							<th class="text-right"><?= number_format((($gaji_pokok->gaji_pokok + $ttl_mp_bulan) / $gaji_pokok->kurs) / 173, 2) ?></th>
							<th>Rate per man hour</th>
							<th></th>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
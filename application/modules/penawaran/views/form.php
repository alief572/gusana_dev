<div class="card-body" id="dataForm">
    <input type="hidden" name="id_penawaran" class="id_penawaran" value="<?= (isset($data_penawaran)) ? str_replace('/', '-', $data_penawaran->id_penawaran) : $this->auth->user_id() ?>">
    <input type="hidden" name="req_approval" class="req_approval" value="<?= (isset($request_approval)) ? $request_approval : null ?>">
    <div class="row">
        <div class="col-6">
            <label for="">No. Penawaran</label>
            <input type="text" name="" id="" class="form-control form-control-sm" value="<?= (isset($data_penawaran)) ? $data_penawaran->id_penawaran : 'new' ?>" readonly>
        </div>
        <div class="col-6">
            <label for="">Tanggal</label>
            <input type="date" name="tgl_penawaran" id="" class="form-control form-control-sm" value="<?= (isset($data_penawaran)) ? $data_penawaran->tgl_penawaran : null ?>" required>
        </div>
        <div class="col-6">
            <label for="">Nama Customer</label>
            <select name="customer" class="form-control form-control-sm chosen-select customer" required>
                <option value="">- Nama Customer -</option>
                <?php
                foreach ($list_customer as $customer) :
                    $selected = '';
                    if (isset($data_penawaran)) {
                        if ($data_penawaran->id_cust == $customer->id_customer) {
                            $selected = 'selected';
                        }
                    }
                    echo '<option value="' . $customer->id_customer . '" ' . $selected . '>' . $customer->customer_name . '</option>';
                endforeach;
                ?>
            </select>
        </div>
        <div class="col-6">
            <label for="">Sales/Marketing</label>
            <select name="sales_marketing" id="" class="form-control form-control-sm chosen-select" required>
                <option value="">- Sales/Marketing -</option>
                <?php
                foreach ($list_sales as $sales) :
                    $selected = '';
                    if (isset($data_penawaran)) {
                        if ($data_penawaran->id_marketing == $sales->id) {
                            $selected = 'selected';
                        }
                    }
                ?>

                    <option value="<?= $sales->id ?>" <?= $selected ?>><?= $sales->name ?></option>

                <?php
                endforeach;
                ?>
            </select>
        </div>
        <div class="col-6">
            <label for="">Nomor Telepon</label>
            <input type="text" name="no_telp" id="" class="form-control form-control-sm no_telp_cust" value="<?= (isset($data_penawaran)) ? $data_penawaran->no_telp_cust : null ?>" readonly>
        </div>
        <div class="col-6">
            <label for="">PIC Customer</label>
            <select name="pic_cust" class="form-control form-control-sm chosen-select pic_cust" required>
                <option value="">- PIC Customer -</option>
                <?php
                if (isset($data_penawaran)) {
                    foreach ($list_pic as $pic) :
                        $selected = '';
                        if($pic->id == $data_penawaran->id_pic_cust){
                            $selected = 'selected';
                        }
                        echo '<option value="' . $pic->id . '" '.$selected.'>' . $pic->name . '</option>';
                    endforeach;
                }
                ?>
            </select>
        </div>
        <div class="col-6">
            <label for="">PPN / Non PPN</label>
            <select name="ppn_type" class="form-control form-control-sm chosen-select ppn_type" required>
                <option value="">- PPN / Non PPN -</option>
                <option value="1" <?= (isset($data_penawaran) && $data_penawaran->ppn_type == 1) ? 'selected' : null ?>>PPN</option>
                <option value="0" <?= (isset($data_penawaran) && $data_penawaran->ppn_type == 0) ? 'selected' : null ?>>Non PPN</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <div class="col-12 mt-15">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Produk</th>
                        <th class="text-center">Lot Size</th>
                        <th class="text-center">Kode Produk</th>
                        <th class="text-center">Spesifikasi Kemasan</th>
                        <th class="text-center">RAL Code</th>
                        <th class="text-center">Qty (kaleng)</th>
                        <th class="text-center">Weight (Kg)</th>
                        <th class="text-center">Harga Satuan (Rp / Kg)</th>
                        <th class="text-center">Stock Tersedia (Kaleng)</th>
                        <th class="text-center">Total Harga</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="list_detail_penawaran">
                    <?php $x = 0;
                    foreach ($list_penawaran_detail as $penawaran_detail) : $x++; ?>
                        <tr>
                            <td class="text-center"><?= $x ?></td>
                            <td class="text-center"><?= $penawaran_detail->nm_product ?></td>
                            <td class="text-center"><?= $penawaran_detail->lot_size ?></td>
                            <td class="text-center"><?= $penawaran_detail->kode_product ?></td>
                            <td class="text-center"><?= $penawaran_detail->konversi ?> <?= $penawaran_detail->packaging ?></td>
                            <td class="text-center"><?= $penawaran_detail->ral_code ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->qty, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->weight, 2) ?></td>
                            <td class="text-center">
                                <input type="text" name="harga_satuan_<?= str_replace('/', '-', $penawaran_detail->id) ?>" id="" class="form-control form-control-sm autonum harga_satuan" data-id="<?= str_replace('/', '-', $penawaran_detail->id) ?>" value="<?= $penawaran_detail->harga_satuan ?>">
                            </td>
                            <td class="text-center"><?= number_format($penawaran_detail->free_stock, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->total_harga, 2) ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger del_detail" data-id="<?= str_replace('/', '-', $penawaran_detail->id) ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tbody>
                    <tr>
                        <td></td>
                        <td>
                            <select name="" id="" class="form-control form-control-sm produk_detail chosen-select2" style="width: 300px;">
                                <option value="">- Nama Produk -</option>
                                <?php
                                foreach ($list_produk as $produk) :
                                    echo '<option value="' . $produk->id_category3 . '">' . $produk->nama . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </td>
                        <td>
                            <select name="" id="" class="form-control form-control-sm lot_size_detail chosen-select2">

                            </select>
                        </td>
                        <td class="kode_produk text-center"></td>
                        <td class="spesifikasi_kemasan text-center"></td>
                        <td class="ral_code text-center"></td>
                        <td>
                            <input type="text" name="" id="" class="form-control form-control-sm text-right autonum qty_detail">
                        </td>
                        <td class="col_weight_produk">
                        </td>
                        <td class="harga_satuan text-right" style="min-width: 170px;">
                            <input type="text" name="" id="" class="form-control form-control-sm harga_satuan_detail autonum">
                        </td>
                        <td class="free_stock text-right" style="min-width: 170px;"></td>
                        <td class="total_harga text-right" style="min-width: 200px;"></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-success add_penawaran_detail">
                                <i class="fa fa-plus"></i> Add
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td class="text-left" colspan="3">Total</td>
                        <td class="text-right total_all_harga"><?= number_format($total_harga, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td class="text-center">Discount</td>
                        <td>
                            <table border="0">
                                <tr>
                                    <td>
                                        <input type="text" name="disc_val" id="" class="form-control form-control-sm text-right autonum disc_input disc_val" data-disc_type="val" placeholder="Input Disc Value (Rp)" <?= (isset($data_penawaran) && $data_penawaran->disc_persen > 0) ? 'readonly' : null ?> value="<?= (isset($data_penawaran)) ? $data_penawaran->disc_num : null ?>">
                                    </td>
                                    <td>(Rp)</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table border="0">
                                <tr>
                                    <td>
                                        <input type="number" name="disc_per" id="" class="form-control form-control-sm text-right disc_input disc_per" data-disc_type="per" step="0.01" placeholder="Input Percent Disc (%)" <?= (isset($data_penawaran) && $data_penawaran->disc_num > 0) ? 'readonly' : null ?> value="<?= (isset($data_penawaran)) ? $data_penawaran->disc_persen : null ?>">
                                    </td>
                                    <td>%</td>
                                </tr>
                            </table>
                            
                        </td>
                        <td class="text-right disc_harga">
                            <?= (isset($data_penawaran) ? number_format($data_penawaran->nilai_disc, 2) : null) ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td class="" colspan="3">Price After Discount</td>
                        <td class="text-right total_after_disc">
                            <?= (isset($data_penawaran)) ? number_format($total_harga - $data_penawaran->nilai_disc, 2) : null ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td class="">PPN</td>
                        <td class="" colspan="2">
                            <input type="number" name="persen_ppn" id="" class="form-control form-control-sm text-right persen_ppn" placeholder="Input PPN Percent" value="<?= (isset($data_penawaran)) ? number_format($data_penawaran->ppn_persen, 2) : null ?>">
                        </td>
                        <td class="text-right nilai_ppn">
                            <?= (isset($data_penawaran)) ? number_format($data_penawaran->ppn_num, 2) : null ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td class="" colspan="3">
                            <span style="font-weight:bold;">Grand Total</span>
                        </td>
                        <td class="text-right total_grand_total">
                            <?= (isset($data_penawaran)) ? number_format($total_harga - $data_penawaran->nilai_disc + $data_penawaran->ppn_num, 2) : null ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-5 <?= (isset($request_approval) && $request_approval == 1) ? '' : 'd-none' ?>">
        <div class="form-group">
            <label>Keterangan Request Approval</label>
            <textarea name="keterangan" class="form-control form-control-sm" cols="10" rows="10"></textarea>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js'); ?>"></script>
<script type="text/javascript">
    function get_harga_satuan() {
        var produk_detail = $('.produk_detail').val();
        var lot_size_detail = $('.lot_size_detail').val();

        if (produk_detail !== '' && lot_size_detail !== '') {
            $.ajax({
                type: 'POST',
                url: siteurl + thisController + 'get_harga_satuan',
                data: {
                    'produk_detail': produk_detail,
                    'lot_size_detail': lot_size_detail
                },
                cache: false,
                dataType: 'json',
                success: function(result) {
                    var harga_satuan_detail = $('.harga_satuan_detail').val();
                    if (harga_satuan_detail == '') {
                        $('.harga_satuan_detail').val(result.harga_satuan);
                    }
                }
            });
        } else {
            $('.harga_satuan_detail').val('');
        }
    }

    function hitung_weight() {
        var produk_detail = $('.produk_detail').val();
        var lot_size_detail = $('.lot_size_detail').val();
        var qty_detail = $('.qty_detail').val();
        if (qty_detail == null) {
            qty_detail = 0;
        } else {
            qty_detail = qty_detail.split(',').join('');
            qty_detail = parseFloat(qty_detail);
        }

        $.ajax({
            type: 'POST',
            url: siteurl + thisController + 'hitung_weight',
            data: {
                'produk_detail': produk_detail,
                'lot_size_detail': lot_size_detail,
                'qty_detail': qty_detail
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                $('.col_weight_produk').html(result.weight_form);
                $('.weight_produk').val(result.weight);
            }
        });
    }

    function hitung_total_harga() {
        var produk_detail = $('.produk_detail').val();
        var lot_size_detail = $('.lot_size_detail').val();
        var qty_detail = $('.qty_detail').val();
        if (qty_detail == null) {
            qty_detail = 0;
        } else {
            qty_detail = qty_detail.split(',').join('');
            qty_detail = parseFloat(qty_detail);
        }

        var harga_satuan_detail = $('.harga_satuan_detail').val();
        if (harga_satuan_detail == null) {
            harga_satuan_detail = 0;
        } else {
            harga_satuan_detail = harga_satuan_detail.split(',').join('');
            harga_satuan_detail = parseFloat(harga_satuan_detail);
        }

        $.ajax({
            type: 'POST',
            url: siteurl + thisController + 'hitung_total_harga',
            data: {
                'produk_detail': produk_detail,
                'lot_size_detail': lot_size_detail,
                'qty_detail': qty_detail,
                'harga_satuan_detail': harga_satuan_detail
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                $('.total_harga').html(result.total_harga2);
            }
        });
    }

    function hitung_all() {
        var id_penawaran = $('.id_penawaran').val();
        var disc_val = $('.disc_val').val();
        if (disc_val == null) {
            disc_val = 0;
        } else {
            disc_val = disc_val.split(',').join('');
            disc_val = parseFloat(disc_val);
        }

        var disc_per = $('.disc_per').val();
        if (disc_per == null) {
            disc_per = 0;
        } else {
            disc_per = disc_per.split(',').join('');
            disc_per = parseFloat(disc_per);
        }

        var persen_ppn = $('.persen_ppn').val();
        if (persen_ppn == null) {
            persen_ppn = 0;
        } else {
            persen_ppn = persen_ppn.split(',').join('');
            persen_ppn = parseFloat(persen_ppn);
        }

        $.ajax({
            type: 'post',
            url: siteurl + thisController + 'hitung_all',
            data: {
                'id_penawaran': id_penawaran,
                'disc_val': disc_val,
                'disc_per': disc_per,
                'persen_ppn': persen_ppn
            },
            cache: false,
            dataType: 'json',
            success: function(result) {

                $('.total_all_harga').html(result.total_harga2);
                $('.disc_harga').html(result.nilai_disc2);
                $('.total_after_disc').html(result.total_after_disc2);
                $('.nilai_ppn').html(result.nilai_ppn2);
                $('.total_grand_total').html(result.total_grand_total2);
            }
        });
    }

    $(document).ready(function() {
        $('.chosen-select').select2({
            placeholder: "Choose one",
            allowClear: true,
            width: '100%',
            dropdownParent: $("#dataForm")
        });
        $('.chosen-select2').select2({
            placeholder: "Choose one",
            allowClear: true,
            width: '300px',
            dropdownParent: $("#dataForm")
        });

        $('.autonum').autoNumeric();
    });

    $(document).on('change', '.customer', function() {
        var customer = $(this).val();

        $.ajax({
            type: 'POST',
            url: siteurl + thisController + 'customer',
            data: {
                'customer': customer
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                $('.no_telp_cust').val(result.no_telp_cust);
                $('.pic_cust').html(result.list_pic_cust);
            }
        });
    });

    $(document).on('change', '.produk_detail', function() {
        var produk = $(this).val();

        $.ajax({
            type: 'POST',
            url: siteurl + thisController + 'get_produk_detail',
            data: {
                'produk': produk
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                $('.lot_size_detail').html(result.list_lot_size);
                $('.kode_produk').html(result.kode_produk);
                $('.spesifikasi_kemasan').html(result.spesifikasi_kemasan);
                $('.ral_code').html(result.ral_code);
                $('.free_stock').html(result.free_stock);

                get_harga_satuan();
                hitung_weight();
                hitung_total_harga();
                hitung_all();
            }
        });
    });

    $(document).on('change', '.lot_size_detail', function() {
        get_harga_satuan();
        hitung_weight();
        hitung_total_harga();
        hitung_all();
    });

    $(document).on('keyup', '.qty_detail', function() {
        var qty_detail = $(this).val();
        if (qty_detail == '') {
            qty_detail = 0;
        } else {
            qty_detail = qty_detail.split(',').join('');
            qty_detail = parseFloat(qty_detail);
        }

        var produk_detail = $('.produk_detail').val();
        var lot_size_detail = $('.lot_size_detail').val();

        get_harga_satuan();
        hitung_weight();
        hitung_total_harga();
        hitung_all();
    });

    $(document).on('change', '.ppn_type', function() {
        var ppn_type = $(this).val();
        if (ppn_type == 1) {
            $('.persen_ppn').attr('readonly', false);
            $('.persen_ppn').val(0);
        } else {
            $('.persen_ppn').attr('readonly', true);
            $('.persen_ppn').val(0);
        }
    });

    $(document).on('keyup', '.disc_input', function() {

        var id_penawaran = $('.id_penawaran').val();

        var disc_val = $(this).val();
        if (disc_val == '') {
            disc_val = 0;
        } else {
            disc_val = disc_val.split(',').join('');
            disc_val = parseFloat(disc_val);
        }

        var disc_type = $(this).data('disc_type');
        $('.disc_' + disc_type).attr('readonly', false)
        if (disc_type == 'val') {
            $('.disc_per').val(0);
            $('.disc_per').attr('readonly', true);
        } else {
            $('.disc_val').val(0);
            $('.disc_val').attr('readonly', true);
        }

        if (disc_val == 0 || disc_val == '') {
            $('.disc_per').val(0);
            $('.disc_per').attr('readonly', false);
            $('.disc_val').val(0);
            $('.disc_val').attr('readonly', false);
        }

        $.ajax({
            type: 'post',
            url: siteurl + thisController + 'hitung_disc',
            data: {
                'id_penawaran': id_penawaran,
                'disc_val': disc_val,
                'disc_type': disc_type
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                $('.disc_harga').html(result.nilai_disc2);
                hitung_all();
            }
        });
    });

    $(document).on('click', '.add_penawaran_detail', function() {
        var id_penawaran = $('.id_penawaran').val();
        var produk_detail = $('.produk_detail').val();
        var lot_size_detail = $('.lot_size_detail').val();
        var qty_detail = $('.qty_detail').val();
        if (qty_detail == null) {
            qty_detail = 0;
        } else {
            qty_detail = qty_detail.split(',').join('');
            qty_detail = parseFloat(qty_detail);
        }

        var harga_satuan_detail = $('.harga_satuan_detail').val();
        if (harga_satuan_detail == null) {
            harga_satuan_detail = 0;
        } else {
            harga_satuan_detail = harga_satuan_detail.split(',').join('');
            harga_satuan_detail = parseFloat(harga_satuan_detail);
        }

        var disc_val = $('.disc_val').val();
        if (disc_val == null) {
            disc_val = 0;
        } else {
            disc_val = disc_val.split(',').join('');
            disc_val = parseFloat(disc_val);
        }

        var disc_per = $('.disc_per').val();
        if (disc_per == null) {
            disc_per = 0;
        } else {
            disc_per = disc_per.split(',').join('');
            disc_per = parseFloat(disc_per);
        }

        $.ajax({
            type: 'post',
            url: siteurl + thisController + 'add_penawaran_detail',
            data: {
                'id_penawaran': id_penawaran,
                'produk_detail': produk_detail,
                'lot_size_detail': lot_size_detail,
                'qty_detail': qty_detail,
                'harga_satuan_detail': harga_satuan_detail
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                // alert('1');
                $('.list_detail_penawaran').html(result.hasil);

                if (result.valid_stock < 1) {
                    Lobibox.notify('error', {
                        title: 'Error!!!',
                        icon: 'fa fa-times',
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                        msg: 'Maaf, stock produk ini kurang dari yang di request !'
                    });
                }

                hitung_all();
            }
        });

    });
    $(document).on('keyup', '.persen_ppn', function() {
        hitung_all();
    });

    $(document).on('keyup', '.harga_satuan_detail', function() {
        hitung_total_harga();
        hitung_all();
    });

    $(document).on('click', '.del_detail', function() {
        var id = $(this).data('id');
        var id_penawaran = $('.id_penawaran').val();

        $.ajax({
            type: 'post',
            url: siteurl + thisController + 'del_detail',
            data: {
                'id': id,
                'id_penawaran': id_penawaran
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                $('.list_detail_penawaran').html(result.hasil);
                hitung_total_harga();
                hitung_all();
            }
        });
    });
</script>
<form id="data-form" method="post">
    <div class="card-body" id="dataForm">
        <input type="hidden" name="id_penawaran" class="id_penawaran" value="<?= (isset($data_penawaran)) ? $id_penawaran : $this->auth->user_id() ?>">
        <input type="hidden" name="req_approval" class="req_approval" value="<?= (isset($request_approval)) ? $request_approval : null ?>">
        <div class="row">
            <div class="col-6">
                <label for="">Customer Name</label>
                <select name="customer" class="form-control  chosen-select customer" required>
                    <option value="">- Customer Name -</option>
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
                <label for="">Quote Number</label>
                <input type="text" name="" id="" class="form-control " value="<?= (isset($data_penawaran)) ? $data_penawaran->id_penawaran : 'new' ?>" readonly>
            </div>
            <div class="col-6">
                <label for="">Customer Address</label>
                <input type="text" name="address_cust" id="" class="form-control cust_address" value="<?= (isset($data_penawaran)) ? $data_penawaran->address_cust : null ?>" readonly>
            </div>
            <div class="col-6">
                <label for="">Quote Date</label>
                <input type="date" name="tgl_penawaran" id="" class="form-control " value="<?= (isset($data_penawaran)) ? $data_penawaran->tgl_penawaran : null ?>" required>
            </div>
            <div class="col-6">
                <label for="">Contact Person</label>
                <select name="pic_cust" class="form-control  chosen-select pic_cust" required>
                    <option value="">- Contact Person -</option>
                    <?php
                    if (isset($data_penawaran)) {
                        foreach ($list_pic as $pic) :
                            $selected = '';
                            if ($pic->id == $data_penawaran->id_pic_cust) {
                                $selected = 'selected';
                            }
                            echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                        endforeach;
                    }
                    ?>
                </select>
            </div>
            <div class="col-6">
                <label for="">Delivery Date</label>
                <input type="date" name="deliver_date" id="" class="form-control" value="<?= (isset($data_penawaran)) ? $data_penawaran->deliver_date : null ?>" required>
            </div>
            <div class="col-6">
                <label for="">Delivery Type</label>
                <select name="deliver_type" id="" class="form-control" required>
                    <option value="">- Delivery Type -</option>
                    <option value="1" <?= (isset($data_penawaran) && $data_penawaran->deliver_type == 1) ? 'selected' : null ?>>Delivery</option>
                    <option value="2" <?= (isset($data_penawaran) && $data_penawaran->deliver_type == 2) ? 'selected' : null ?>>Self Pickup</option>
                </select>
            </div>
            <div class="col-6">
                <label for="">Sales</label>
                <select name="sales_marketing" id="" class="form-control  chosen-select" required>
                    <option value="">- Sales -</option>
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
                <label for="">PPN / Non PPN</label>
                <select name="ppn_type" class="form-control  chosen-select ppn_type" required>
                    <option value="">- PPN / Non PPN -</option>
                    <option value="1" <?= (isset($data_penawaran) && $data_penawaran->ppn_type == 1) ? 'selected' : null ?>>PPN</option>
                    <option value="0" <?= (isset($data_penawaran) && $data_penawaran->ppn_type == 0) ? 'selected' : null ?>>Non PPN</option>
                </select>
            </div>
            <div class="col-12"></div>
            <div class="col-6">
                <label for="">Action</label>
                <select name="req_action" id="" class="form-control">
                    <option value="1">Approve</option>
                    <option value="2">Reject</option>
                </select>
            </div>
        </div>

        <div class="col-12">
            <button type="button" class="btn btn-sm btn-success add_product" style="margin-top:20px;">
                <i class="fa fa-plus"></i>
                Add Product
            </button>
            <table class="table table-striped" id="table-product">
                <thead>
                    <tr>
                        <th class="text-center">
                            <span>
                                序号 <br>
                                No.
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                产品名称 <br>
                                Product Name
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                产品型号 <br>
                                Product Code
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                数量 <br>
                                Qty
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                重量 <br>
                                Weight (Kg)
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                单价 <br>
                                Harga Satuan (Rp/Kg)
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                金额 <br>
                                Total Harga
                            </span>
                        </th>
                        <th class="text-center">
                            <span>备注 <br> Keterangan</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="list_detail_penawaran">
                    <?php $x = 0;
                    foreach ($list_penawaran_detail as $penawaran_detail) : $x++; ?>
                        <tr>
                            <td class="text-center"><?= $x ?></td>
                            <td class="text-center"><?= $penawaran_detail->nm_product ?></td>
                            <td class="text-center"><?= $penawaran_detail->kode_product ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->qty, 2) ?></td>
                            <td class="text-center"><?= number_format($penawaran_detail->weight, 2) ?></td>
                            <td class="text-center">
                                <!-- <input type="text" name="harga_satuan_<?= str_replace('/', '-', $penawaran_detail->id) ?>" id="" class="form-control  autonum harga_satuan" data-id="<?= str_replace('/', '-', $penawaran_detail->id) ?>" value="<?= $penawaran_detail->harga_satuan ?>"> -->
                                <?= number_format($penawaran_detail->harga_satuan, 2) ?>
                            </td>
                            <td class="text-center"><?= number_format($penawaran_detail->total_harga, 2) ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning text-light edit_detail" data-id="<?= str_replace('/', '-', $penawaran_detail->id) ?>">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger del_detail" data-id="<?= str_replace('/', '-', $penawaran_detail->id) ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tbody>
                    <tr>
                        <td colspan="4"></td>
                        <td class="text-left" colspan="3">Subtotal</td>
                        <td class="text-right total_all_harga"><?= number_format($total_harga, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td class="">Biaya Pengiriman</td>
                        <td class="" colspan="3">
                            <input type="text" name="biaya_pengiriman" id="" class="form-control  text-right biaya_pengiriman autonum" placeholder="Input Biaya Pengiriman" value="<?= (isset($data_penawaran)) ? number_format($data_penawaran->biaya_pengiriman, 2) : null ?>">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td class="text-center">Discount</td>
                        <td>
                            <table border="0">
                                <tr>
                                    <td>
                                        <input type="text" name="disc_val" id="" class="form-control  text-right autonum disc_input disc_val" data-disc_type="val" placeholder="Input Disc Value (Rp)" <?= (isset($data_penawaran) && $data_penawaran->disc_persen > 0) ? 'readonly' : null ?> value="<?= (isset($data_penawaran)) ? $data_penawaran->disc_num : null ?>">
                                    </td>
                                    <td>(Rp)</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table border="0">
                                <tr>
                                    <td>
                                        <input type="number" name="disc_per" id="" class="form-control  text-right disc_input disc_per" data-disc_type="per" step="0.01" placeholder="Input Percent Disc (%)" <?= (isset($data_penawaran) && $data_penawaran->disc_num > 0) ? 'readonly' : null ?> value="<?= (isset($data_penawaran)) ? $data_penawaran->disc_persen : null ?>">
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
                        <td colspan="4"></td>
                        <td class="" colspan="3">Price After Discount</td>
                        <td class="text-right total_after_disc">
                            <?= (isset($data_penawaran)) ? number_format($total_harga - $data_penawaran->nilai_disc, 2) : null ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td class="">PPN</td>
                        <td class="" colspan="2">
                            <input type="number" name="persen_ppn" id="" class="form-control  text-right persen_ppn" placeholder="Input PPN Percent" value="11" readonly>
                        </td>
                        <td class="text-right nilai_ppn">
                            <?= (isset($data_penawaran)) ? number_format($data_penawaran->ppn_num, 2) : null ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td class="" colspan="3">
                            <span style="font-weight:bold;">Grand Total</span>
                        </td>
                        <td class="text-right total_grand_total">
                            <?= (isset($data_penawaran)) ? number_format($total_harga - $data_penawaran->nilai_disc + $data_penawaran->ppn_num + $data_penawaran->biaya_pengiriman, 2) : null ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-5">
            <div class="form-group">
                <label>Keterangan Approval/Reject</label>
                <textarea name="keterangan" class="form-control " cols="10" rows="5"></textarea>
            </div>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-sm btn-primary" name="save" id="save">Save</button>
            <a href="<?= base_url('/req_app_penawaran') ?>" class="btn btn-sm btn-danger">Back</a>
        </div>
    </div>
</form>

<div class="modal fade effect-scale" id="add-product" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mx-wd-md-90p-force mx-wd-lg-90p-force">
        <form id="add-product-form" method="post" data-parsley-validate>
            <input type="hidden" class="id_penawaran2" name="id_penawaran" value="<?= (isset($data_penawaran)) ? $data_penawaran->id_penawaran : 'new' ?>">
            <input type="hidden" name="id_detail" class="id_detail">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title tx-bold text-dark" id="myModalLabel">Add Product</h4>
                    <button type="button" class="btn btn-default close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                产品名称 <br>
                                Product Name
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <select name="produk_detail" id="" class="form-control produk_detail chosen-select2" style="width: 300px;" required>
                                <option value="">- Nama Produk -</option>
                                <?php
                                foreach ($list_produk as $produk) :
                                    echo '<option value="' . $produk->id_category3 . '">' . $produk->nama . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                Packaging Spec for Supporting Curing Agent (kg)
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <input type="text" name="curing_agent_pack_spec" id="" class="form-control  curing_agent_pack_spec" placeholder="" readonly>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                数量 <br>
                                Qty
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <input type="text" name="qty" id="" class="form-control qty" required>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                色标 <br>
                                RAL Code
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <input type="text" name="ral_code" id="" class="form-control  ral_code" readonly>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                产品型号 <br>
                                Product Code
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <input type="text" name="product_code" id="" class="form-control  product_code" readonly>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                单位 <br>
                                Unit
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <input type="text" name="unit" id="" class="form-control  unit" readonly>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                包装规格 <br>
                                Packaging Spec (Kg)
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <input type="text" name="packaging_spec" id="" class="form-control  packaging_spec" readonly>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                重量 <br>
                                Weight (Kg)
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <input type="text" name="weight" id="" class="form-control text-right weight" readonly>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                Lot Size
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <select name="lot_size_detail" id="" class="form-control lot_size_detail" required>

                            </select>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                单价 <br>
                                Harga Satuan (Rp/Kg)
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <input type="text" name="harga_satuan" id="" class="form-control  harga_satuan text-right autonum" required>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                配套固化剂 <br>
                                Supporting Curing Agent
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <input type="text" name="supporting_curing_agent" id="" class="form-control  supporting_curing_agent" readonly>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                金额 <br>
                                Total Harga
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <input type="text" name="total_harga" id="" class="form-control text-right autonum total_harga" readonly>
                        </div>
                        <div class="col-2 mt-15" style="margin-top: 10px;">
                            <span>
                                备注 <br>
                                Keterangan
                            </span>
                        </div>
                        <div class="col-4" style="margin-top: 10px;">
                            <textarea name="keterangan" id="" cols="30" rows="5" class="form-control form-control-sm keterangan"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn wd-100 btn btn-primary" name="save_product" id="save_product"><i class="fa fa-save"></i>
                        Save</button>
                    <!-- <button type="button" class="btn wd-100 btn btn-warning" name="request_app" id="request_app"><i class="fa fa-save"></i>
                        Request Approval</button> -->
                    <button type="button" class="btn wd-100 btn btn-danger" data-dismiss="modal">
                        <span class="fa fa-times"></span> Close</button>
                </div>
            </div>
        </form>
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
                    var harga_satuan = $('.harga_satuan').val();
                    if (harga_satuan == '') {
                        $('.harga_satuan').val(result.harga_satuan);
                    }
                }
            });
        } else {
            $('.harga_satuan').val('');
        }
    }

    function hitung_weight() {
        var produk_detail = $('.produk_detail').val();
        var lot_size_detail = $('.lot_size_detail').val();
        var qty = $('.qty').val();
        if (qty == null) {
            qty = 0;
        } else {
            qty = qty.split(',').join('');
            qty = parseFloat(qty);
        }

        $.ajax({
            type: 'POST',
            url: siteurl + thisController + 'hitung_weight',
            data: {
                'produk_detail': produk_detail,
                'lot_size_detail': lot_size_detail,
                'qty': qty
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                $('.weight').val(result.weight);
            }
        });
    }

    function hitung_total_harga() {
        var produk_detail = $('.produk_detail').val();
        var lot_size_detail = $('.lot_size_detail').val();
        var qty = $('.qty').val();
        if (qty == null) {
            qty = 0;
        } else {
            qty = qty.split(',').join('');
            qty = parseFloat(qty);
        }

        var harga_satuan = $('.harga_satuan').val();
        if (harga_satuan == null) {
            harga_satuan = 0;
        } else {
            harga_satuan = harga_satuan.split(',').join('');
            harga_satuan = parseFloat(harga_satuan);
        }

        $.ajax({
            type: 'POST',
            url: siteurl + thisController + 'hitung_total_harga',
            data: {
                'produk_detail': produk_detail,
                'lot_size_detail': lot_size_detail,
                'qty': qty,
                'harga_satuan': harga_satuan
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                console.log(result);
                $('.total_harga').val(result.total_harga2);
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

        var ppn_type = $('.ppn_type').val();
        var persen_ppn = $('.persen_ppn').val();
        if (persen_ppn == null) {
            persen_ppn = 0;
        } else {
            persen_ppn = persen_ppn.split(',').join('');
            persen_ppn = parseFloat(persen_ppn);
        }

        var biaya_pengiriman = $('.biaya_pengiriman').val();
        if (biaya_pengiriman == null) {
            biaya_pengiriman = 0;
        } else {
            biaya_pengiriman = biaya_pengiriman.split(',').join('');
            biaya_pengiriman = parseFloat(biaya_pengiriman);
        }

        $.ajax({
            type: 'post',
            url: siteurl + thisController + 'hitung_all',
            data: {
                'id_penawaran': id_penawaran,
                'disc_val': disc_val,
                'disc_per': disc_per,
                'persen_ppn': persen_ppn,
                'ppn_type': ppn_type,
                'biaya_pengiriman': biaya_pengiriman
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

    function refresh_table_product() {
        var id_penawaran = $('.id_penawaran').val();

        $.ajax({
            type: 'post',
            url: siteurl + thisController + 'refresh_table_product',
            data: {
                'id_penawaran': id_penawaran
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                $('#table-product').html(result.hasil);

                $('.autonum').autoNumeric();
                $('.harga_satuan').autoNumeric();
                $('.total_harga').autoNumeric();
                $('.qty').autoNumeric();
                $('.weight').autoNumeric();
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
            width: '100%',
            dropdownParent: $("#add-product")
        });

        $('.autonum').autoNumeric();

        $('.harga_satuan').autoNumeric();
        $('.total_harga').autoNumeric();
        $('.qty').autoNumeric();
        $('.weight').autoNumeric();
    });

    $(document).on('click', '.add_product', function() {
        $('#add-product').modal();

        $('.id_detail').val('');
        $('.produk_detail').val('').trigger('change.select2');
        $('.curing_agent_pack_spec').val('');
        $('.qty').val('');
        $('.ral_code').val('');
        $('.product_code').val('');
        $('.unit').val('');
        $('.packaging_spec').val('');
        $('.weight').val('');

        $('.lot_size_detail').html('');
        $('.lot_size_detail').val('');

        $('.harga_satuan').val('');
        $('.supporting_curing_agent').val('');
        $('.total_harga').val('');

        $('.keterangan').val('');
        v
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
                // $('.no_telp_cust').val(result.no_telp_cust);
                $('.pic_cust').html(result.list_pic_cust);
                $('.cust_address').val(result.address_cust);
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
                $('.product_code').val(result.kode_produk);
                $('.packaging_spec').val(result.spesifikasi_kemasan);
                $('.ral_code').val(result.ral_code);
                $('.unit').val(result.unit_nm);
                $('.supporting_curing_agent').val(result.supporting_curing_agent);
                $('.curing_agent_pack_spec').val(result.curing_agent_pack_spec);
                // $('.free_stock').html(result.free_stock);

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

    $(document).on('keyup', '.qty', function() {
        var qty = $(this).val();
        if (qty == '') {
            qty = 0;
        } else {
            qty = qty.split(',').join('');
            qty = parseFloat(qty);
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
        // if (ppn_type == 1) {
        //     $('.persen_ppn').attr('readonly', false);
        //     $('.persen_ppn').val(11);
        // } else {
        //     $('.persen_ppn').attr('readonly', true);
        //     $('.persen_ppn').val(11);
        // }
        $('.persen_ppn').attr('readonly', true);
        $('.persen_ppn').val(11);

        hitung_all();
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

    $(document).on('click', '.add_penawaran_detail', function(e) {
        e.preventDefault();
        var id_penawaran = $('.id_penawaran').val();
        var produk_detail = $('.produk_detail').val();
        var lot_size_detail = $('.lot_size_detail').val();
        var qty = $('.qty').val();
        if (qty == null) {
            qty = 0;
        } else {
            qty = qty.split(',').join('');
            qty = parseFloat(qty);
        }

        var harga_satuan = $('.harga_satuan').val();
        if (harga_satuan == null) {
            harga_satuan = 0;
        } else {
            harga_satuan = harga_satuan.split(',').join('');
            harga_satuan = parseFloat(harga_satuan);
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
                'qty': qty,
                'harga_satuan': harga_satuan
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

    $(document).on('keyup', '.harga_satuan', function() {
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
            // dataType: 'json',
            success: function(result) {
                // $('.list_detail_penawaran').html(result.hasil);
                refresh_table_product();
                hitung_total_harga();
                hitung_all();
            }
        });
    });

    $(document).on('keyup', '.biaya_pengiriman', function() {
        hitung_all();
    });

    $(document).on('submit', '#add-product-form', function(e) {
        e.preventDefault();

        var req_approval = $('.req_approval').val();
        // alert(req_approval);
        var msg = 'Are you sure to save this data ?';
        if (req_approval == '1') {
            msg = 'Are you sure to make request approval ?'
        }

        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mg-r-10 wd-100',
                cancelButton: 'btn btn-danger wd-100'
            },
            buttonsStyling: false
        })

        let formData = new FormData($('#add-product-form')[0]);
        swalWithBootstrapButtons.fire({
            title: "Anda Yakin?",
            text: msg,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "<i class='fa fa-check'></i> Yes",
            cancelButtonText: "<i class='fa fa-ban'></i> No",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    type: 'POST',
                    url: siteurl + thisController + 'save_product',
                    dataType: "JSON",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    error: function() {
                        Lobibox.notify('error', {
                            title: 'Error!!!',
                            icon: 'fa fa-times',
                            position: 'top right',
                            showClass: 'zoomIn',
                            hideClass: 'zoomOut',
                            soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                            msg: 'Internal server error. Ajax process failed.'
                        });
                    }
                })
            },
            allowOutsideClick: true
        }).then((val) => {
            if (val.isConfirmed) {
                if (val.value.valid == '1') {
                    Lobibox.notify('success', {
                        icon: 'fa fa-check',
                        msg: val.value.msg,
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                    });
                    $("#add-product").modal('hide');
                    $('.dataTables_length select').select2({
                        minimumResultsForSearch: -1
                    });
                    refresh_table_product();
                    // location.reload(true);
                } else {
                    Lobibox.notify('warning', {
                        icon: 'fa fa-ban',
                        msg: val.value.msg,
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                    });
                };
            }
        })
    });

    $(document).on('click', '.edit_detail', function() {
        var id = $(this).data('id');

        $.ajax({
            type: 'post',
            url: siteurl + thisController + 'edit_detail',
            data: {
                'id': id
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                $('#add-product').modal();

                // $('.id_penawaran2').val(result.id_penawaran2);
                $('.id_detail').val(result.id);
                $('.produk_detail').val(result.id_product).trigger('change.select2');
                $('.curing_agent_pack_spec').val(result.curing_agent_pack_spec);
                $('.qty').autoNumeric('set', result.qty);
                $('.ral_code').val(result.ral_code);
                $('.product_code').val(result.product_code);
                $('.unit').val(result.unit);
                $('.packaging_spec').val(result.packaging_spec);
                $('.weight').autoNumeric('set', result.weight);

                $('.lot_size_detail').html(result.list_lot_size);
                $('.lot_size_detail').val(result.lot_size_detail);

                $('.harga_satuan').autoNumeric('set', result.harga_satuan);
                $('.supporting_curing_agent').val(result.supporting_curing_agent);
                $('.total_harga').autoNumeric('set', result.total_harga);

                $('.keterangan').val(result.keterangan);
            }
        });
    });

    $(document).on('submit', '#data-form', function(e) {
        e.preventDefault();

        var req_approval = $('.req_approval').val();
        // alert(req_approval);
        var msg = 'Are you sure to save this data ?';
        if (req_approval == '1') {
            msg = 'Are you sure to make request approval ?'
        }

        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mg-r-10 wd-100',
                cancelButton: 'btn btn-danger wd-100'
            },
            buttonsStyling: false
        })

        let formData = new FormData($('#data-form')[0]);
        swalWithBootstrapButtons.fire({
            title: "Anda Yakin?",
            text: msg,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "<i class='fa fa-check'></i> Yes",
            cancelButtonText: "<i class='fa fa-ban'></i> No",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    type: 'POST',
                    url: siteurl + thisController + 'save',
                    dataType: "JSON",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    error: function() {
                        Lobibox.notify('error', {
                            title: 'Error!!!',
                            icon: 'fa fa-times',
                            position: 'top right',
                            showClass: 'zoomIn',
                            hideClass: 'zoomOut',
                            soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                            msg: 'Internal server error. Ajax process failed.'
                        });
                    }
                })
            },
            allowOutsideClick: true
        }).then((val) => {
            console.log(val);
            if (val.isConfirmed) {
                if (val.value.status == '1') {
                    Lobibox.notify('success', {
                        icon: 'fa fa-check',
                        msg: val.value.msg,
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                    });
                    window.location.href = '<?= base_url('/req_app_penawaran') ?>';
                } else {
                    Lobibox.notify('warning', {
                        icon: 'fa fa-ban',
                        msg: val.value.msg,
                        position: 'top right',
                        showClass: 'zoomIn',
                        hideClass: 'zoomOut',
                        soundPath: '<?= base_url(); ?>themes/bracket/assets/lib/lobiani/sounds/',
                    });
                };
            }
        })

    })
</script>
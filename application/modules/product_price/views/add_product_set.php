<div class="card-body" id="dataForm">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="">Product Master</label>
                <select name="product_master" id="" class="form-control form-control-sm product_master select">
                    <option value="">- Product Master -</option>
                    <?php
                    foreach ($results['list_product'] as $product) :
                        echo '<option value="' . $product->id_category3 . '">' . $product->nama . '</option>';
                    endforeach;
                    ?>
                </select>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Nama Product</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="list_set_product">

                </tbody>
                <tbody>
                    <tr>
                        <td class="text-center"></td>
                        <td class="">
                            <select name="" id="" class="form-control form-control-sm set_product select">
                                <option value="">- Product Set -</option>
                                <?php foreach ($results['list_product_set'] as $product_set) : ?>
                                    <option value="<?= $product_set->id_category3 ?>"><?= $product_set->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-success save_product_set">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tr>
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
            dropdownParent: $("#dataForm")
        });

        $(document).on('click', '.save_product_set', function(e) {
            e.preventDefault();
            var product_master = $('.product_master').val();
            var set_product = $('.set_product').val();

            if (product_master == '' || product_master == null) {
                new swal({
                    title: "Error Message !",
                    text: 'Please input the product master first !',
                    type: "warning",
                    timer: 1500,
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            } else {
                $.ajax({
                    type: 'post',
                    url: siteurl + thisController + 'save_product_set',
                    data: {
                        'product_master': product_master,
                        'set_product': set_product
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(result) {
                        $('.list_set_product').html(result.hasil);
                    }
                });
            }
        });

        $(document).on('change', '.product_master', function() {
            var product_master = $(this).val();

            $.ajax({
                type: 'post',
                url: siteurl + thisController + 'get_list_product_set',
                data: {
                    'product_master': product_master
                },
                cache: false,
                dataType: 'json',
                success: function(result) {
                    $('.list_set_product').html(result.hasil);
                }
            });
        });

        $(document).on('click', '.del_set_product', function(){
            var id = $(this).data('id');
            var product_master = $('.product_master').val();

            $.ajax({
                type: 'post',
                url: siteurl + thisController + 'del_set_product',
                data: {
                    'product_master': product_master,
                    'id': id
                },
                cache: false,
                dataType: 'json',
                success: function(result) {
                    $('.list_set_product').html(result.hasil);
                }
            });
        });
    });
</script>
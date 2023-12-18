<input type="hidden" name="list_id_category3" value="<?= implode(',',$list_id_category3) ?>">
<div class="card-body" id="dataForm">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped w-100">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Product Name</th>
                        <th class="text-center">Packing</th>
                        <th class="text-center">Konversi (kg)</th>
                        <th class="text-center">Actual Stock (Kg)</th>
                        <th class="text-center">Actual Stock (Kaleng)</th>
                        <th class="text-center">Stock Booking (Kaleng)</th>
                        <th class="text-center">Free Stock (Kaleng)</th>
                        <th class="text-center">Minimun Stock (Kaleng)</th>
                        <th class="text-center">MOQ (Kg)</th>
                        <th class="text-center">Propose (Kg)</th>
                        <th class="text-center">Lot Size (Kg)</th>
                        <th class="text-center">Due Date</th>
                    </tr>
                </thead>
                <tbody class="list_so">
                    <?php 
                    // echo '<pre>'; 
                    // print_r($results);
                    // echo'</pre>'; 

                    foreach($data_table as $data){
                        echo $data;
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
<!-- </div> -->
<!-- br-pagebody -->


<div class="pos-fixed b-0 bg-white float-right r-0">
    <footer class="br-footer px-3 py-2">
        <div class="footer-left">
            <strong>Copyright &copy; <?= date('Y'); ?> <a href="<?= site_url(); ?>"><?= isset($idt->nm_perusahaan) ? $idt->nm_perusahaan : 'not-set'; ?></a>.</strong>
            All rights reserved | &nbsp;
        </div>
        <div class="footer-right">
            <?= date('l, m F Y'); ?>&nbsp;|<strong>&nbsp; {elapsed_time}</strong>&nbsp;s
        </div>
    </footer>
</div>
</div><!-- br-mainpanel -->
<!-- ########## END: MAIN PANEL ########## -->
<!-- <script src="<?= base_url(); ?>themes/bracket/assets/lib/jquery/jquery.min.js"></script> -->
<script src="<?= base_url(); ?>themes/bracket/assets/lib/jquery-ui/ui/widgets/datepicker.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/moment/min/moment.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/peity/jquery.peity.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/highlightjs/highlight.pack.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/jquery-steps/build/jquery.steps.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/select2/js/select2.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/parsleyjs/parsley.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/rickshaw/vendor/d3.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/rickshaw/vendor/d3.layout.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/rickshaw/rickshaw.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/lobiani/js/lobibox.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/sweetalert2/sweetalert2.all.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/jquery.maskedinput/jquery.mask.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/jquery-inputmask/bindings/inputmask.binding.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/jquery-inputmask/jquery.inputmask.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/summernote/summernote-bs4.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/js/ResizeSensor.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/js/tooltip-colored.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/js/popover-colored.js"></script>
<script src=" <?= base_url(); ?>themes/bracket/assets/js/bracket.js"></script>

<script>
    $(document).ready(function() {
        // Select2
        $('.select').select2({
            // minimumResultsForSearch: -1,
            placeholder: 'Choose one',
            // dropdownParent: $('#data-form-customer'),
            width: "100%",
            allowClear: true
        });

        $('.dataTables_length select, #dataTables_length select').select2({
            // containerCs  sClass: 'select2-outline-success',
            // dropdownCssClass: 'select2-hidden-accessible hover-success',
            minimumResultsForSearch: Infinity
        })

    })

    Lobibox.notify.OPTIONS = {
        success: {
            'class': 'lobibox-notify-success',
            'title': 'Success',
            'icon': 'fa fa-check',
            sound: 'sound21',
        },
        error: {
            'class': 'lobibox-notify-error',
            'title': 'Error',
            'icon': 'fa fa-check',
            'sound': 'sound9',
        },
        warning: {
            'class': 'lobibox-notify-warning',
            'title': 'Warning',
            'icon': 'fa fa-check',
            sound: 'sound14'
        },
        info: {
            'class': 'lobibox-notify-info',
            'title': 'info',
            'icon': 'fa fa-check',
            sound: 'sound7'
        },
    }
</script>
</body>

</html>
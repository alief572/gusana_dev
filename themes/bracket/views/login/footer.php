<script src="<?= base_url(); ?>themes/bracket/assets/lib/jquery/jquery.min.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/jquery-ui/ui/widgets/datepicker.js"></script>
<script src="<?= base_url(); ?>themes/bracket/assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('.log-btn').click(function() {
        $('.log-status').addClass('wrong-entry');
        $('.alert').fadeIn(500);
        setTimeout("$('.alert').fadeOut(1500);", 3000);
    });
    $('.form-control').keypress(function() {
        $('.log-status').removeClass('wrong-entry');
    });

});
</script>
</body>

</html>
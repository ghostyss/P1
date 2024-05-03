
<!-- start: FOOTER -->
<div class="footer clearfix">
    <div class="footer-inner">
        2013 &copy; clip-one by cliptheme.
    </div>
    <div class="footer-items">
        <span class="go-top"><i class="clip-chevron-up"></i></span>
    </div>
</div>
<!-- end: FOOTER -->
<div id="event-management" class="modal fade" tabindex="-1" data-width="760" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">Event Management</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-light-grey">
                    Close
                </button>
                <button type="button" class="btn btn-danger remove-event no-display">
                    <i class='icon-trash'></i> Delete Event
                </button>
                <button type='submit' class='btn btn-success save-event'>
                    <i class='icon-ok'></i> Save
                </button>
            </div>
        </div>
    </div>
</div>
<!-- start: MAIN JAVASCRIPTS -->
<!--[if lt IE 9]>
<script src="assets/plugins/respond.min.js"></script>
<script src="assets/plugins/excanvas.min.js"></script>
<![endif]-->

<script src="assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js<?php echo $RandNumber;?>"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js<?php echo $RandNumber;?>"></script>
<script src="assets/plugins/blockUI/jquery.blockUI.js<?php echo $RandNumber;?>"></script>
<script src="assets/plugins/iCheck/jquery.icheck.min.js<?php echo $RandNumber;?>"></script>
<script src="assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js<?php echo $RandNumber;?>"></script>
<script src="assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js<?php echo $RandNumber;?>"></script>
<script src="assets/js/main.js<?php echo $RandNumber;?>"></script>
<!-- end: MAIN JAVASCRIPTS -->
<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script src="assets/plugins/flot/jquery.flot.js<?php echo $RandNumber;?>"></script>
<script src="assets/plugins/flot/jquery.flot.pie.js<?php echo $RandNumber;?>"></script>
<script src="assets/plugins/flot/jquery.flot.resize.min.js<?php echo $RandNumber;?>"></script>
<script src="assets/plugins/jquery.sparkline/jquery.sparkline.js<?php echo $RandNumber;?>"></script>
<script src="assets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js<?php echo $RandNumber;?>"></script>
<script src="assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js<?php echo $RandNumber;?>"></script>
<script src="assets/plugins/fullcalendar/fullcalendar/fullcalendar.js<?php echo $RandNumber;?>"></script>
<script src="assets/js/form-calendar.js<?php echo $RandNumber;?>"></script>
<script src="assets/js/index.js<?php echo $RandNumber;?>"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script>
    var Interval1 = '';
    jQuery(document).ready(function () {
        Main.init();
        Calendar.init();
    });
</script>
</body>
<!-- end: BODY -->
</html>
<div class="loader-div">
<div class='col-xs-12 text-center'><div class='main-loader'></div></div>
</div>
<footer class="main-footer text-center" style="padding:10px">
    <div class="pull-right hidden-xs">
    </div>
</footer>

</div>

<script src="../bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" async></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="../bower_components/PACE/pace.min.js"></script>
<!-- Slimscroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="../js/Chart.min.js"></script>

<script>
  //var data = [800, 800, 1300];
$(document).ready(function() {
	var link = window.location.href.split('?')[0];
	$('.sidebar-menu li a').filter(function() {
		if(this.href.split('?')[0] == link) {
			$(this).parent().addClass('active');
		}
	});
	$('.calls_table').slimscroll({
		height: '334px',
		color: '#812990',
		railVisible: true,
		railColor: '#222',
		railOpacity: 0.2,
		alwaysVisible: true
	});
	$('.agent_calls').slimscroll({
	    height: '315px'
    });
});
</script>

<!-- jQuery UI 1.11.4 -->

<script src="../js/jquery-migrate-3.0.1.min.js"></script>
<script src="../bower_components/validator/jquery.validate.min.js" async></script>
<script src="../js/toastr/toastr.min.js" async></script>
<script src="../bower_components/bootstrap-timepicker/js/bootstrap-timepicker.min.js" async></script>
<script src="../js/custom.js"></script>
<script src="../js/comp-5.js"></script>
<script src="../js/comp-4.js"></script>
</body>
</html>

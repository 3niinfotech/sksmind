<?php session_start(); ?>
<!DOCTYPE html>
<?php 

include("../../database.php");
include("../../variable.php");
include_once("../../checkResource.php");

?>	
<html lang="en">
	<?php include("../head.php"); ?>

	<body class="no-skin">
		<?php include("../account_header.php");?>
		<div class="main-container ace-save-state" id="main-container">
			
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>
			<?php include("../account_left.php");
			include_once('../Helper.php');
			$helper = new Helper();
			
			?>
			
			<div class="main-content">
				<div class="main-content-inner">
					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Account Dashboard								
							</h1>
						</div><!-- /.page-header -->

					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<?php
			include("../footer.php");
		?>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="assets/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="assets/js/excanvas.min.js"></script>
		<![endif]-->
		<script src="assets/js/jquery-ui.custom.min.js"></script>
		<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
		<script src="assets/js/jquery.easypiechart.min.js"></script>
		<script src="assets/js/jquery.sparkline.index.min.js"></script>
		<script src="assets/js/jquery.flot.min.js"></script>
		<script src="assets/js/jquery.flot.pie.min.js"></script>
		<script src="assets/js/jquery.flot.resize.min.js"></script>

		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>		
	</body>
</html>







	
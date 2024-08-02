<?php session_start(); ?>
<!DOCTYPE html>
<?php
include("../variable.php"); 
include("../database.php");
include_once("../checkResource.php");

if (!isset($_SESSION['username']) || !in_array($_SESSION['companyId'],$companyResource) )
{
	header("Location: ".$mainUrl);	
	exit;
}

unset($_SESSION['last_inward']);
?>	
<html lang="en">
	<?php include("head.php"); ?>

	<body class="no-skin">
		<?php include("header.php");?>
		<div class="main-container ace-save-state" id="main-container">
			
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>
			<?php include("left.php");?>
			
			<div class="main-content">
				<div class="main-content-inner">					
					
				</div>
			</div><!-- /.main-content -->

			<?php
			include("footer.php");
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
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
		var n=10001;
		
jQuery(function($) {
	//Android's default browser somehow is confused when tapping on label which will lead to dragging the task
				//so disable dragging when clicking on label
				var agent = navigator.userAgent.toLowerCase();
				if(ace.vars['touch'] && ace.vars['android']) {
				  $('#tasks').on('touchstart', function(e){
					var li = $(e.target).closest('#tasks li');
					if(li.length == 0)return;
					var label = li.find('label.inline').get(0);
					if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
				  });
				}
			
				$('#tasks').sortable({
					opacity:0.8,
					revert:true,
					forceHelperSize:true,
					placeholder: 'draggable-placeholder',
					forcePlaceholderSize:true,
					tolerance:'pointer',
					stop: function( event, ui ) {
						//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
						$(ui.item).css('z-index', 'auto');
					}
					}
				);
				//$('#tasks').disableSelection();
				$('#tasks input:checkbox').removeAttr('checked').on('click', function(){
					if(this.checked) $(this).closest('li').addClass('selected');
					else $(this).closest('li').removeClass('selected');
				});
			
			
				//show the dropdowns on top or bottom depending on window height and menu position
				$('#task-tab .dropdown-hover').on('mouseenter', function(e) {
					var offset = $(this).offset();
			
					var $w = $(window)
					if (offset.top > $w.scrollTop() + $w.innerHeight() - 100) 
						$(this).addClass('dropup');
					else $(this).removeClass('dropup');
				});	
				
				jQuery('.Boxchecked').click();
				
				setTimeout(function(){ jQuery('.alert-success1').remove() }, 3000);

});	
</script>
<style>
.noteTextbox{border: 1px solid rgb(204, 204, 204); width: 80%; position: absolute; z-index: 99999 ! important; padding: 0px !important; font-size: 13px; display: block;top: 8px;left: 28px;display:none;}

.dash-left .item-list{height:200px; overflow-y:auto;}
.dash-left .item-list > li {
	padding: 0px;
	background-color: #FFF;
	margin-top: -1px;
	position: relative;
}

.party-dash .duepayment > li {
	width: 20%;	
}

.party-dash .item-list > li {
padding: 0px;
}
</style>		
</body>
</html>







	
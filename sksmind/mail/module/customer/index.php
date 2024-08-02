<?php session_start(); ?>
<!DOCTYPE html>
<?php 

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('party',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}
 
?>	
<html lang="en">
<?php include($emsDir."head.php"); ?>	

	<body class="no-skin">
		<?php if(!isset($_GET['isAjax'])): ?>
		<?php include($emsDir."header.php");?>
		<?php endif;?>
		<div class="main-container ace-save-state" id="main-container">
			<?php if(!isset($_GET['isAjax'])): ?>
			<?php include($emsDir."left.php");?>
			<?php endif;?>
			<?php include($emsDir."message.php");?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1 style="float:left">
								Company								
							</h1>
							<?php if(isset($_GET['pg']) && $_GET['pg'] == "form"): ?>
							
							<button class="btn btn-info" type="button" onClick="location.href='<?php echo $emsUrl;?>module/party'" style="float:right">
								<i class="ace-icon fa fa-reply bigger-110"></i>
								Back
							</button>
							<?php else: ?>
							<button class="btn btn-info" type="button" onClick="location.href='<?php echo $emsUrl;?>module/customer/index.php?pg=form'" style="float:right">
								<i class="ace-icon fa fa-plus bigger-110"></i>
								Add New Company
							</button>
							<?php endif; ?>
						</div><!-- /.page-header -->
						
						<div class="row">
						<?php
							include('customerModel.php');							
							
							$model  = new customerModel();
							?>
							<?php 
							$groupUrl = $emsDir.'module/customer/';
							$modUrl = $emsUrl.'module/customer/';
							if(isset($_GET['pg']) && $_GET['pg'] == "form")
							{	
								$lid=0;
								if(isset($_GET['id']))
								{
									$lid = $_GET['id'];
								}
								$data =  $model->getData($lid);
								include($groupUrl."form.php");								
							}	
							else
							{
								include($groupUrl."grid.php");
							}
							
							?>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<?php
			include($emsDir."footer.php");
		?>
		</div><!-- /.main-container -->
		<div id="please-wait" style="display:none">
			<img src="<?php echo  $daiUrl;?>assets/images/loading.gif" />
		</div>
		<div class="dialog-box-container" id="dialog-box-container" style="display:none;" >
			<div class="box-container" style="width:1250px" >
			</div>
		</div>	
	<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="<?php echo  $daiUrl;?>assets/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		
		
		<script src="<?php echo  $daiUrl;?>assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->

		
		
		<script src="<?php echo  $daiUrl;?>assets/js/jquery-ui.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo  $daiUrl;?>assets/js/ace-elements.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/ace.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/footable.js"></script>
		<script type="text/javascript">
			jQuery(function($) 
			{
				
				 $( "#datepicker" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,					
				}); 
				
				$('.table').footable({
						"paging": {
							"enabled": true,
							"size": 20
						},
						"filtering": {
							"enabled": true
						},
						"sorting": {
							"enabled": true
						},
						"columns": jQuery.get("columns.json"),
						"rows": <?php echo $customer_json; ?>//jQuery.get("customer/rows.json")
					});
			
			});
			
			$('.editGroup').click(function(){
				var gname = $(this).attr('gname');
				var under = $(this).attr('under');
				var gid = $(this).attr('gid');
				
				$('#gname').val(gname);
				$('#gid').val(gid);
				$('#gunder').val(under);
				});
				
			$('.reset').click(function(){
				$('#gid').val('');				
			});	
			$('#gunder').on('change', function() {
				$('.subform  input').attr("disabled",true);
				 $('.subform  select').attr("disabled",true);
				 $('.subform  textarea').attr("disabled",true);
				 // alert( this.value ); // or $(this).val()
				  var v = this.value;
				  var id = '#subform-'+v;
				  $('.subform').addClass('no-display');
				  $(id).removeClass('no-display');
				  
				  $(id+' input').attr("disabled",false);
				 $(id+' select').attr("disabled",false);
				 $(id+' textarea').attr("disabled",false);
				  
				});
				
			function closeBox()
			{
				$('#dialog-box-container').hide();
			}
			function loadparty(id)
			{
				
				jQuery('#please-wait').show();
					var data = {'id':id};
					jQuery.ajax({
						url: '<?php echo $moduleUrl.'party/partyDetail.php' ?>', 
						type: 'GET',
						data: data,		
						success: function(result)
							{		
								if(result != "")
								{
									jQuery('#dialog-box-container').show();
									$('#dialog-box-container').html('<div class="box-container" >'+result+'</div>');									
									jQuery('#please-wait').hide();
									
								}
							}
					});
			}
			
		</script>

		
	</body>
	<style>
	.page-header {
	  float: left;
	  margin-bottom: 20px;
	  padding-bottom: 0;
	  width: 100%;
	}
	</style>
</html>







	
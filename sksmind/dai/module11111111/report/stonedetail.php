<?php session_start(); ?><!DOCTYPE html>
<?php 
include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('stone',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}
?>	
<html lang="en">
<?php include($daiDir."head.php"); ?>	

	<body class="no-skin">
		<?php include($daiDir."header.php");?>
		<div class="main-container ace-save-state" id="main-container">
			
			<?php include($daiDir."left.php");?>
			<?php include($daiDir."message.php");?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						
						<div class="row">
						<?php
							include_once($daiDir.'Helper.php');
							
							$helper  = new Helper();							
							$groupUrl = $daiDir.'module/report/';														
							$sku =  $helper->getAllSku();
							?>
							
							<div class="form-group col-sm-6" style=" margin-bottom: 0; margin-top: 20px;">
								<label class="col-sm-4 control-label no-padding-right" for="form-field-4" style="color:#2679b5">Enter Stone Id / SKU :</label>
								<div class="col-sm-8">
									<input class="input-sm col-sm-8" id="stone" value="" name="invoiceno" placeholder="SKU / Stone Id" type="text">
								</div>
							</div>
							
							<div style="clear:both"></div>
							<hr>
							
							<div style="width:100%;float:left;" id="memo-data" >
							<?php 	//include_once($groupUrl."stoneData.php"); ?>
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
			<div class="dialog-box-container" id="dialog-box-container" style="display:none;" >
				<div class="box-container" style="width:1100px" >
				</div>
			</div>
			<?php
			include($daiDir."footer.php");
		?>
		</div><!-- /.main-container -->
		<div id="please-wait" style="display:none">
			<img src="<?php echo  $daiUrl;?>assets/images/loading.gif" />
		</div> 
		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="<?php echo  $daiUrl;?>assets/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		
		
		<script src="<?php echo  $daiUrl;?>assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->

	
	
		<!-- page specific plugin scripts -->
		<script src="<?php echo  $daiUrl;?>assets/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/jquery.dataTables.bootstrap.min.js"></script>
		

		<script src="<?php echo  $daiUrl;?>assets/js/buttons.colVis.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/dataTables.select.min.js"></script>
		
		
		<script src="<?php echo  $daiUrl;?>assets/js/jquery-ui.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo  $daiUrl;?>assets/js/ace-elements.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/ace.min.js"></script>
		<script type="text/javascript">
			
			
			jQuery(function($) {
			
			var suggestions = new Array(			 
			  <?php foreach($sku as $k=>$v):?>
			  '<?php echo $v ?>',
			  <?php endforeach;?>''
			  );
			 	
			 $("#stone").bind("keypress", {}, keypressInBox);
			 
			 jQuery( "#stone" ).autocomplete({
					source: suggestions
				});
			});
			
			function keypressInBox(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if (code == 13) { //Enter keycode                        
				e.preventDefault();

				loadHistory($('#stone').val());
				}
			};
			
			function loadHistory(sku)
			{
				var data = {'sku':sku};
				
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'module/report/stoneData.php'; ?>', 
					type: 'POST',
					data: data,		
					success: function(result)
						{		
							if(result != "")
							{
								$('#memo-data').html(result);									
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







	
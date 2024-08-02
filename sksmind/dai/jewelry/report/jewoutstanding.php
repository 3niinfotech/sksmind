<?php session_start(); ?><!DOCTYPE html>
<?php 
include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('outstanding',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}
?>	
<html lang="en">
<?php include($daiDir."head.php"); ?>	

	<body class="no-skin">
	<?php include($daiDir."header_jewelry.php");?>
		<div class="main-container ace-save-state" id="main-container">
			
			<?php include($daiDir."left_jewelry.php");?>
			<?php include($daiDir."message.php");?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						
						<div class="row">
						<?php
							include_once($daiDir.'Helper.php');
							include_once($daiDir.'jewelry/party/partyModel.php');
							$pmodel  = new partyModel($cn);
							$helper  = new Helper($cn);
							$attribute = $helper->getAttribute();							
							$groupUrl = $daiDir.'jewelry/report/';														
							$party = $pmodel->getOptionList();							
							?>
							<form class="form-horizontal" id="memo-report" method="POST" role="form" style="margin-top: 10px;" enctype="multipart/form-data" action="<?php echo $jewelryUrl.'report/reportController.php'?>">
							<input type="hidden" value="outreportExport" name="fn">
							<input type="hidden" value="party" name="type">
						<!--	<div class="form-group col-sm-3" style="padding: 0px; margin-bottom: 5px; margin-right: 0px;">
								<label class="col-sm-6 control-label no-padding-right" for="form-field-4">Outstanding Report</label>
								<div class="col-sm-6">
									<select class="col-xs-12" name="report" onChange="loadMemo()">
										<option value="">Report Type</option>
										<option value="sale">Sale</option>
										<option value="purchase">Purchase</option>																	
									</select>
								</div>
							</div>-->
							
							<div class="form-group col-sm-4" style="padding:0px;margin-bottom: 5px;">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Company</label>
								<div class="col-sm-9">
									<select class="col-xs-12" id="ledger" name="party" onChange="loadMemo()">
										<option value="0">All Company</option>
										<?php 
										foreach($party as $key => $value):
										?>						
										<option value="<?php echo $key?>" ><?php echo $value?></option>
										<?php endforeach; ?>
									
									</select>
								</div>
							</div>
							<div class="form-group col-sm-1" style="padding: 0px; margin-bottom: 5px; margin-left: 10px; margin-right: 0px; width: 11%;">						
							<div class="col-sm-12">									
							<input class="input-sm col-sm-12" style="margin-right:2%;"  value="" onChange="loadMemo()" name="invoice" placeholder="Invoice No" type="text">				</div>							
							</div>
							<div class="form-group col-sm-1" style="padding: 0px; margin-bottom: 5px; margin-left: 10px; margin-right: 0px; width: 11%;">				
								<div class="col-sm-12">
									<input class="input-sm col-sm-12" style="margin-right:2%;" id="cfrom" value="" onChange="loadMemo()" name="cfrom" placeholder="From Date" type="text">
								</div>
							</div>
							<div class="form-group col-sm-1" style="width:11%;padding:0px;margin-bottom: 5px;">								
								<div class="col-sm-12">
									<input class="input-sm col-sm-12" style="margin-right:2%;" id="cto" value="" onChange="loadMemo()" name="cto" placeholder="To Date" type="text">
								</div>
							</div>
							<button class="btn btn-info" type="button" onclick="$('#memo-report').submit();" style="float: right; margin: 0px; padding: 0px 20px;">
								<i class="ace-icon fa fa-download bigger-110"></i>
								Export
							</button>
							<div style="clear:both"></div>
							<hr>
							</form>
							<div style="width:100%;float:left;" id="memo-data" >
							<?php 	//include_once($groupUrl."mgrid.php"); ?>
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
			<div class="dialog-box-container" id="dialog-box-container" style="display:none;" >
				<div class="box-container" style="width:1200px" >
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
			loadMemo();
			$( "#cfrom, #cto" ).datepicker({
										showOtherMonths: true,
										selectOtherMonths: false,
										dateFormat: 'yy-mm-dd',
									});
			});
			
		
			function loadMemo()
			{
				var data = $('#memo-report').serialize();
				
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'jewelry/report/jewoutstandingGrid.php'; ?>', 
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
			
			function savePart(id)
			{
				var data = jQuery('#savePart').serialize();
				
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'account/expense/expenseController.php'; ?>', 
					type: 'POST',
					data: data,		
					success: function(result)
						{		
							if(result != "")
								{
									jQuery('#please-wait').hide();
									var obj = jQuery.parseJSON(result);
									alert(obj.message);
									if(obj.status)
									{
										loadRecord(id)
									}
								}
						}
				});
			}
			function saveCharge(id) 
			{
				var data = jQuery('#saveLess').serialize();
				jQuery('#please-wait').show();
				jQuery.ajax({
					url: '<?php echo $daiUrl.'jewelry/jewelry/jewelryController.php '; ?>',
					type: 'POST',
					data: data,
					success: function(result) 
					{
						if (result != "") 
						{
							jQuery('#please-wait').hide();
							var obj = jQuery.parseJSON(result);
							alert(obj.message);
							if (obj.status) 
							{
								loadRecord(id)
							}
						}
					}
			});
			}
			function loadRecord(id)
			{
				var data = {'eid':id};
				
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'jewelry/report/jewoutRecordGrid.php'; ?>', 
					type: 'POST',
					data: data,		
					success: function(result)
						{		
							if(result != "")
							{
								jQuery('#please-wait').hide();
								$('#dialog-box-container').html('<div class="box-container" >'+result+'</div>');
								$('#dialog-box-container').show();
								$( "#date" ).datepicker({
										showOtherMonths: true,
										selectOtherMonths: false,
										dateFormat: 'yy-mm-dd',
								});
							
							}
						}
				});
			}
			function totalSelected(id)
			{
				var total = parseFloat($('.mform-'+id).find("input[type='checkbox']:checked").length);
					if(total > 0)
					{
						$('#return-'+id).attr('disabled',false);
					}
					else
					{
						$('#return-'+id).attr('disabled',true);
					}				
					$('#selected-row-'+id).html(total);				
			}
			
			
			
			
			function closeBox()
			{
				$('#dialog-box-container').hide();
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

.inventory-color-cell {
  float: left;
  width: 50%;
}
.f-ch-input.pos-rel {
  float: left;
  margin-right: 5px;
}
input.ace.bblue[type="checkbox"] + .lbl::before
{ 
  border: 2px solid #6fb3e0; 
}
input.ace.bred[type="checkbox"] + .lbl::before
{ 
  border: 2px solid #d53f40; 
}
input.ace.bgreen[type="checkbox"] + .lbl::before
{ 
  border: 2px solid #9abc32; 
}
input.ace[type="checkbox"] + .lbl::before, input.ace[type="radio"] + .lbl::before {
  background-color: #fafafa;
  border: 1px solid #c8c8c8;
  border-radius: 0;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  color: #fff;
  content: " ";
  cursor: pointer;
  display: inline-block;
  font-family: fontAwesome;
  font-size: 12px;
  font-weight: 400;
  height: 18px;
  line-height: 14px;
  margin-right: 1px;
  min-width: 18px;
  position: relative;
  text-align: center;
  top: -1px;
}
.inventory-color {
   border-bottom: 0 none;
  float: left;
  margin-bottom: 0;
  padding: 5px 10px 10px;
  width: 18%;
   float: left;
}

.color-total {
  float: left;
  font-size: 14px;
  margin-right: 10px;
  font-weight: bold;
}
.divTableBody {
  height: 350px;
  overflow-y: scroll;
}

.alldata b {
  color: #9abc32;
  margin-right: 10px;
}
.alldata li {
  float: left;
  margin: 5px 0;
  width: 20%;
}
.alldata > ul {
  list-style: outside none none;
}
.box-container {
	background: #fff none repeat scroll 0 0 !important;
	height: auto;
	margin: 2% auto 0;
	min-height: 600px;
	padding: 10px;
	width: 1250px;
}
.dialog-box-container {
	background: rgba(0, 0, 0, 0.3) none repeat scroll 0 0;
	height: 100%;
	left: 0;
	position: absolute;
	top: 0;
	width: 100%;
	z-index: 99999;
}
</style>
</html>







	
<?php session_start(); ?><!DOCTYPE html>
<?php include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('stock',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
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
							
							?>
							<!-- <form class="form-horizontal" id="memo-report" method="POST" role="form" style="margin-top: 10px;" enctype="multipart/form-data" action="<?php echo $moduleUrl.'report/reportController.php'?>">
							<input type="hidden" value="reportExport" name="fn">
							
							<div class="form-group col-sm-1" style="padding: 0px; margin-bottom: 5px; margin-right: 0px; width: 11%;">				
								<div class="col-sm-12">
									<input class="input-sm col-sm-12" style="margin-right:2%;" id="cfrom" value="" onChange="loadMemo()" name="cfrom" placeholder="From Date" type="text">
								</div>
							</div>
							<div class="form-group col-sm-1" style="width:11%;padding:0px;margin-bottom: 5px;">								
								<div class="col-sm-12">
									<input class="input-sm col-sm-12" style="margin-right:2%;" id="cto" value="" onChange="loadMemo()" name="cto" placeholder="To Date" type="text">
								</div>
							</div>
							
							<div style="clear:both"></div>
							<hr>
							</form>-->
							<div style="width:100%;float:left;" id="memo-data" >
							<?php 	include_once($groupUrl."stockData.php"); ?>
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
			
			$( "#cfrom, #cto" ).datepicker({
										showOtherMonths: true,
										selectOtherMonths: false,
										dateFormat: 'yy-mm-dd',
									});
			});
			
		
		
				
		</script>

		
	</body>
<style>
.page-header {
	  float: left;
	  margin-bottom: 20px;
	  padding-bottom: 0;
	  width: 100%;
	}

.report-ledger-info {
  border-bottom: 1px dashed #2679b5;
  color: #2679b5;
  letter-spacing: 1px;
  margin: 0 auto;
  padding: 0;
  text-align: center;
  width: 100%;
}
.content-row {
  float: left;
  margin-bottom: 5px;
  margin-top:10px;
  width: 100%;
}
.content-column {
  float: left;
  width: 20%;
}
.content-column:first-child {
  float: left;
  width: 40%;
}


.content-header {
  border-bottom: 1px solid #ccc;
}
.content-column:first-child, .sub-row .content-column.first{
  padding-left:3% !important;  
}
.content-column:last-child, .sub-row .content-column.last {
  padding-right:3% !important;   
}
.a-right{ text-align:right;}

.content-header .content-column {
  font-size: 14px;
  letter-spacing: 1px;
  padding: 10px 0px;
}
.content-footer {
  border-bottom: 1px solid;
  border-top: 1px solid;
  padding: 9px 0;
}
.content-row.sub-row .content-column:last-child {
  border-bottom: 1px solid #999;
  padding-bottom: 10px;
}
.content-row.sub-row .content-column.last {
  text-align: right;
  width: 170px;
}

.content-row.sub-row .content-column{
  margin: 5px 0;
}
.content-row.sub-row {  
  margin: 0;
}
.col-xs-6.col-sm-6.report-content:first-child
{
	border-right: 1px solid;
}
.content-header.content-row {
  margin-top: 0;
}
.content-column.a-right.trading {
  border-bottom: 1px solid #999;
  border-top: 1px solid #999;
  padding: 5px 0;
}
</style>
</html>







	
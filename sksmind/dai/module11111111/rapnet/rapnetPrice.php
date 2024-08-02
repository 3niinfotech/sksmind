<?php session_start(); ?>
<!DOCTYPE html>
<?php 

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('rapnet',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
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
						<div class="page-header">							
							<h1 style="float:left">
								Rapnet Price List					
							</h1>
							<button class="btn btn-success" type="button" onclick="rapnetPrice()" style="float: right; margin: 0px; margin-top:6px; margin-right:10px;padding: 0px 10px;">
								<i class="ace-icon fa fa-pencil bigger-110"></i>
								Update Rapnet Price
							</button>
						</div>
	
						<?php
							include_once('rapnetModel.php');
							include_once($daiDir.'Helper.php');
							
							$model  = new rapnetModel();
							$helper  = new Helper();	

							$data = $model->getAllRapnetPrice();
						?>
						<div class="subform invenotry-cgrid mform-6" style="overflow: auto;">
							<div class="divTable" style="width:880px">
								<div class="divTableHeading">										
									<div class="divTableCell" style="width:10px" ></div>				
									<div class="divTableCell " style="width:120px">Shape</div>
									<div class="divTableCell " style="width:120px">Color</div>
									<div class="divTableCell " style="width:120px">Clarity</div>
									<div class="divTableCell " style="width:120px">Low Size</div>
									<div class="divTableCell " style="width:120px">Hight Size</div>
									<div class="divTableCell " style="width:120px">Carat Price</div>
									<div class="divTableCell " style="width:120px">Date</div>
								</div>	
								<div class="divTableBody">
									<?php foreach($data  as $d):?>
									<div class="divTableRow ">
										<div class="divTableCell" style="width:10px" ></div>				
									<div class="divTableCell " style="width:120px"><?php echo strtoupper($d['shape']);?></div>
									<div class="divTableCell " style="width:120px"><?php echo strtoupper($d['color']);?></div>
									<div class="divTableCell " style="width:120px"><?php echo strtoupper($d['clarity']);?></div>
									<div class="divTableCell " style="width:120px"><?php echo $d['low_size'];?></div>
									<div class="divTableCell " style="width:120px"><?php echo $d['high_size'];?></div>
									<div class="divTableCell " style="width:120px"><?php echo $d['caratprice'];?></div>
									<div class="divTableCell " style="width:120px"><?php echo $d['date'];?></div>
									</div>	
									<?php endforeach;?>		
								</div>
							</div>
						</div>
						
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

		<?php include($daiDir."footer.php"); ?>
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
			
			jQuery(function($) 
			{

			});
			
			function rapnetPrice()
			{
				jQuery('#please-wait').show();
					var data = {'fn':'rapnetPrice'};
					jQuery.ajax({
						url: '<?php echo $daiUrl.'module/rapnet/rapnetController.php' ?>', 
						type: 'GET',
						data: data,		
						success: function(result)
							{		
								if(result != "")
								{
									jQuery('#please-wait').hide();
									var obj = jQuery.parseJSON(result);
									alert(obj.message);						
								}
							}
					});
			}
			
		</script>

		
	</body>
	<style>
	
	.page-header {
	  float: left;	 
	  padding-bottom: 0;
	  width: 100%;
	}
.subform {
  float: left;
  overflow-x: scroll;
  padding: 5px 10px;
  width: 100%;
  
}
hr {
  border-top: 1px solid #eee;
  margin-bottom: 15px;
  margin-top: 15px;
}
.divTableFooter {
  border-top: 1px solid #f1f1f1;
  margin-top: 20px;
  padding-top: 10px;
}


.divTableCell {
  float: left;
  padding: 0;
  text-align: left;
  width: 109px;
}

.divTableCell:first-child {
  width: 30px !important;
  padding-top: 3px;
}

.divTableHeading, .divTableBody, .divTableFooter {
  float: left;
  width: 100%;
}
.main-grid .divTableBody{
	height:400px;
	overflow-y:scroll;
}
.divTableHeading {
  border-bottom: 1px solid #f1f1f1;
  border-top: 1px solid #f1f1f1;
  margin-bottom: 10px;
  margin-top: 10px;
  padding-bottom: 10px;
  padding-top: 5px;
}
.divTableHeading .divTableCell {
  color: #666;
  font-size: 13px;
  font-weight: bold;
  letter-spacing: 1px;
}
.divTableRow {
  float: left;
  margin: 0;
  width: 100%;
}
.divTable {
  font-size: 14px;
}
.a-right {
  padding-top: 15px;
  text-align: right !important;
}
.add-more {
  background-color: #69aa46;
  border-radius: 100%;
  color: #fff;
  font-size: 14px;
  height: 25px;
  padding: 6px 5px 5px;
  width: 25px;
  cursor: pointer;
}

.delete-more {
  background-color: #dd5a43;
  border-radius: 100%;
  color: #fff;
  font-size: 14px;
  height: 22px;
  padding: 4px 5px 5px;
  width: 22px;
  cursor: pointer;
}
.divTableBody .divTableRow .divTableCell input {
  color: #333;
  font-size: 12px;
  padding: 1px 2px 3px;
}
.subform.invenotry-cgrid.main-grid {
  padding: 0 10px 0px !important;
  width: 100%;
}
.invenotry-cgrid .divTableHeading {
  margin-bottom: -1px; 
}
.inventory-color {
  border:0px;
padding:5px 10px;  
  margin-bottom: 1px;
}

.inventory-container{

  margin-top: 4px;
  padding: 3px 10px;
}
/** Filter css  **/
.filter {
  background-color: #f1f1f1;
  border-bottom: 2px solid #ccc;
  float: left;
  padding-left: 10px;
  position: relative;
  width: 100%;
}

#white-filter-form .filter {
  background-color: #f5f5f5;
  }
.f-shape {
  border: 1px solid #ccc;
  float: left;
  width: 118px;
  margin-right:3px;
}
.f-label {
  background-color: #999;
  color: #fff;
  float: left;
  padding: 2px;
  text-align: center;
  width: 100%;
}
.f-ulli {
  float: left;
  height: 76px;
  list-style: outside none none;
  margin-bottom: 5px !important;
  margin-left: 10px;
  overflow-y: scroll;
  width: 91%;
}
.f-ulli > li {
  overflow-x: hidden;
  padding-bottom: 2px;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.f-left {
  float: left;
  width: 35%;
  padding-top: 10px;
}
.f-right {
  float: right;
  width: 65%;
}
.f-package,.f-carat {
  float: right;
  width: 50%;
}
.f-package{ float: left;
  width: 45%;}
.f-package input {
  float: left;
  width: 100%;
}
.f-carat input {
  float: left;
  width: 48%;
}
.f-carat input:first-child{margin-right:2%;}
.f-l-label 
{
 float: left;
font-size: 12px;
font-weight: bold;
letter-spacing: 1px;
text-align: center !important;
width: 100%;
}
.f-memo {
  border-top: 1px solid #ccc;
  float: left;
  margin-top: 10px;
  padding-top: 10px;
  width: 100%;
}
.f-memo > ul {
  float: left;
  margin-left: 0;
  width: 100%;
}
.f-memo li {
  float: left;
  width: 90px;
}
ul {
  list-style: none;
}
.f-button {
  background: #9abc32 none repeat scroll 0 0;
  border: 0 none;
  bottom: 10px;
  color: #fff;
  height: 32px;
  left: 10px;
  padding: 2px 20px;
  position: absolute;
  text-align: center;
}
.mains-grid .color-total-count {
  color: #9abc32;
  letter-spacing: 0px;
  margin-top: 10px;
}
.inventory-color-cell {
  padding-top: 8px;
}
.inventory-color {
  padding-top: 0 !important;
}
#fancy-filter-form .f-shape {  
  width: 138px;
}
.f-ch-input {
  float: left;
  margin-right: 5px;
}
.inventory-color-cell { 
  width: 7%;
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
.changeform {
  height: 250px;
  overflow-y: scroll;
}

input.ace.bgrey[type="checkbox"] + .lbl::before
{ 
  border: 2px solid #999; 
}

.synchro-process {
	position: fixed;
	bottom: 0px;
	right: 10px;
	z-index: 999999999;
	
	border-radius: 2px;
	width: 320px;
}

.upload-process {
	z-index: 999999999;
	padding: 10px;
	border: 1px solid #ccc;
	width: 300px;
	background: #fff;
	border-radius: 2px;
	margin-bottom:10px;
	width:100%;
	float:left;
}
.upload-process .progress {
	margin-bottom: 10px !important;	
}
#close-progress
{
float:right;font-size:18px;
cursor:pointer;
padding-bottom:5px;

}
</style>
</html>







	
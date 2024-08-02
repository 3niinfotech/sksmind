<?php session_start(); ?>
<!DOCTYPE html>
<?php 
include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('inventory',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}
?>	
<html lang="en">
<?php include($daiDir."head.php"); ?>	

	<body class="no-skin">
		<?php include($daiDir."header_jewelry.php");?>
		<div class="main-container ace-save-state" >
			
			<?php include($daiDir."left_jewelry.php");?>
			<?php include($daiDir."message.php");?>
			<div class="main-content">			
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<?php  ?>
							<h1 style="float:left">
								 Jewelry								
							</h1>
						</div>
						<div class="row">	
							<?php
							
								include("jewelryModel.php");
								$model  = new jewelryModel($cn);	
								include_once($daiDir.'jHelper.php');
								$jhelper  = new jHelper($cn);												
								$groupUrl = $daiDir.'module/jewelry/';														
								 
								$id = 0;
								if(isset($_GET['id']))
									$id = $_GET['id'];
								
								$data = $model->getData($id);
								
								$jewType = $jhelper->getJewelryType();
								$gold = $jhelper->getGoldType();
								$goldColor = $jhelper->getGoldColor();
								if(isset($_GET['id']))
									include("form.php");
								else
								{	
									include_once($daiDir.'jewelry/party/partyModel.php');
									$pmodel  = new partyModel($cn);
									$party = $pmodel->getOptionList();
									include("nform.php");
								}	
									
							?>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

		<?php include($daiDir."footer.php"); ?>
		</div><!-- /.main-container -->
<div id="please-wait" style="display:none">
	<img src="<?php echo  $daiUrl;?>assets/images/loading.gif" />
</div>
<div class="synchro-process">

<?php //include($daiDir."Process.php"); ?> 
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
			var n = 1;
			n++;
			var s = 1;
			s++;
			jQuery(function($) 
			{
				$( "#date,#invoicedate" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});
			});
		
			$('.subform .add-more').on("click", function(){					
					addImportRow();					
				});	
				
				function addImportRow(rid)
				{
					
					var total = $('.divTableBody.mains .divTableRow').length;
					if(total == rid)
					{
					var row = '<div class="divTableRow" id="rowid-'+n+'">';
					row += '<div class="divTableCell" style="width:50px !important">'+n+' <i class="delete-more fa fa-times " onClick="removeRow('+n+')" ></i></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="mrecord['+n+'][sku]" id="sku-'+n+'" onBlur="addImportRow('+n+')" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="mrecord['+n+'][color]" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="mrecord['+n+'][color_code]" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord['+n+'][clarity]" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="mrecord['+n+'][shape]" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="mrecord['+n+'][report_no]" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord['+n+'][pcs]" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right scarat"  name="mrecord['+n+'][carat]" id="pcarat-'+n+'" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord['+n+'][price]" id="price-'+n+'" onBlur="calAmount('+n+')" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right amount1"  name="mrecord['+n+'][amount]" id="amount-'+n+'" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="mrecord['+n+'][remarks]" type="text"></div>';
					
					row +='</div>';
					n = n + 1;
					$('.subform .divTableBody.mains').append(row);
					}
				}
				
				function addImportRowSide(rid)
				{
					
					var total = $('.divTableBody.sides .divTableRow').length;
					if(total == rid)
					{
					var row = '<div class="divTableRow" id="srowid-'+s+'">';
					row += '<div class="divTableCell" style="width:50px !important">'+s+' <i class="delete-more fa fa-times " onClick="removeRowSide('+s+')" ></i></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="srecord['+s+'][sku]" id="sku-'+s+'" onBlur="addImportRowSide('+s+')" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="srecord['+s+'][color]" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="srecord['+s+'][clarity]" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="srecord['+s+'][shape]" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="srecord['+s+'][pcs]" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right scarat"  name="srecord['+s+'][carat]" id="spcarat-'+s+'" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="srecord['+s+'][price]" id="sprice-'+s+'" onBlur="calAmountSide('+s+')" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right amount1"  name="srecord['+s+'][amount]" id="samount-'+s+'" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="srecord['+s+'][remarks]" type="text"></div>';
					
					row +='</div>';
					s = s + 1;
					$('.subform .divTableBody.sides').append(row);
					}
				}
			
				function removeRow(id)
				{
					$('#rowid-'+id).remove();					
				}
				function removeRowSide(id)
				{
					$('#srowid-'+id).remove();					
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
	
.diamond-htable, .diamond-btable
{
	width:100%;	
}

table {
    border-collapse: collapse;
}
table, td, th {
    border: 0px solid #ccc;
}
.diamond-th .diamond-col{text-align:center;}



.dc1,
.dc2,
.dc3,
.dc7
{width:18%;}

.dc4,
.dc5,
.dc6{width:9%}

.dc1, .dc2, .dc3, .dc7 {
	width: 10%;
}
.col-sm-8 {
	width: 74.666%;
}


.jewelry-col3 {
   vertical-align: top;
   padding:0px;
 }
.diamond-th th{border-bottom:0px;}

.diamond-btable tr:first-child td {
    border-top: 0px !important;
}
.diamond-col{padding:2px 4px;text-align:center;}
.a-right{text-align:right;}
	

</style>	
</html>



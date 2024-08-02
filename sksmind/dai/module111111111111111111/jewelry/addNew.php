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
		<?php include($daiDir."header.php");?>
		<div class="main-container ace-save-state" >
			
			<?php include($daiDir."left.php");?>
			<?php include($daiDir."message.php");?>
			<div class="main-content">			
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<?php  ?>
							<h1 style="float:left">
								Add New Jewelry								
							</h1>
						</div>
						<div class="row">	
							<?php
							
								include("jewelryModel.php");
								$model  = new jewelryModel();	
								include_once($daiDir.'Helper.php');
								$helper  = new Helper();												
								$groupUrl = $daiDir.'module/jewelry/';														
								 
								$id = 0;
								if(isset($_GET['id']))
									$id = $_GET['id'];
								
								$data = $model->getData($id);
								$gold = $helper->getGoldType();
								include("form.php");
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
			jQuery(function($) 
			{
				$( "#date" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});
			});
		
			function calulateAmount(rid)
			{
				var qty = parseFloat($('#cts-'+rid).val());
				var rate = parseFloat($('#price-'+rid).val());
				var total = parseFloat(qty) * parseFloat(rate);
				if(!isNaN(total))
				{
					$('#amount-'+rid).val(total.toFixed(2));						
				}
				else
				{
					$('#amount-'+rid).val(0);
				}
				//totalqty();
				totalamount();
			}
			function calulateGold(rid)
			{
				var gram = parseFloat($('#gold_gram').val());
				var rate = parseFloat($('#gold_price').val());
				var total = parseFloat(gram) * parseFloat(rate);
				if(!isNaN(total))
				{
					$('#gold_amount').val(total.toFixed(2));						
				}
				else
				{
					$('#gold_amount').val(0);
				}				
				totalamount();
			}
			
			function totalamount()
			{
				
				//var qty = parseFloat($('#qty-'+rid).val());
				//var rate = parseFloat($('#rate-'+rid).val());
				var total = 0;
				
				$(".diamond-col .amount" ).each(function( index ) 
				{
				  var qty = parseFloat($( this ).val());
				  if(!isNaN(qty))
				  {
					total = qty + total;
				  }					 
				});
				
				var labour = parseFloat($('#labour_fee').val());
				if(!isNaN(labour))
				{
					total = labour + total;
				}
				
				
				var gold_amount = parseFloat($('#gold_amount').val());
				if(!isNaN(gold_amount))
				{
					total = gold_amount + total;
				}
			
				
				if(!isNaN(total))
				{
					$('#cost_price').val(total.toFixed(2));						
				}
				else
				{
					$('#cost_price').val(0);
				}
			}
				function finalTotal()
				{
					var gram = parseFloat($('#percentage').val());
					var rate = parseFloat($('#cost_price').val());
					var total = rate + ((parseFloat(gram) * parseFloat(rate))/100);
					if(!isNaN(total))
					{
						$('#final_cost').val(total.toFixed(2));						
					}
					else
					{
						$('#final_cost').val(0);
					}
				}
				
				function addImportRow(rid)
				{
					var total = $('.diamond-htable tbody tr').length;
					
					if(total == rid)
					{
						var row = '';
						row +='<tr class="diamond-tr" id="row-'+n+'">';
						row +='<td class="diamond-col dc1"><input class="input-sm col-sm-12" name="record['+n+'][sku]" type="text" onBlur="loadSkuData('+n+',this.value)"></td>';
						row +='<td class="diamond-col dc2"><input class="input-sm col-sm-12" name="record['+n+'][report]" id="report-'+n+'" type="text"></td>';
						row +='<td class="diamond-col dc3"><input class="input-sm col-sm-12" name="record['+n+'][color]" id="color-'+n+'" type="text"></td>';
						row +='<td class="diamond-col dc4"><input class="input-sm col-sm-12 a-right pcs" id="pcs-'+n+'" name="record['+n+'][pcs]" type="text"></td>';						
						row +='<td class="diamond-col dc5"><input class="input-sm col-sm-12 a-right cts " id="cts-'+n+'" name="record['+n+'][carat]" type="text"></td>';	
						row +='<td class="diamond-col dc6"><input class="input-sm col-sm-12 a-right price" id="price-'+n+'" name="record['+n+'][price]" onKeyUp="calulateAmount('+n+')" onBlur="calulateAmount('+n+')" type="text"></td>';			
						row +='<td class="diamond-col dc7"><input class="input-sm col-sm-12 a-right amount" id="amount-'+n+'" readonly name="record['+n+'][total_amount]" type="text"></td>';			
						row +='</tr>';
						n = n + 1;
						$('.diamond-htable tbody').append(row);
					}
				}
				
				function loadSkuData(rid,sku)
				{
					jQuery('#please-wait').show();
					var data = {'fn':'skuData','sku':sku};
					jQuery.ajax({
						url: '<?php echo $moduleUrl.'jewelry/jewelryController.php' ?>', 
						type: 'POST',
						data: data,		
						success: function(result)
							{		
								if(result != "")
								{
									jQuery('#please-wait').hide();
									var obj = jQuery.parseJSON(result);
									
									if(obj.status)
									{
										jQuery('#report-'+rid).val(obj.message.report_no);
										jQuery('#color-'+rid).val(obj.message.main_color);
										jQuery('#pcs-'+rid).val(obj.message.polish_pcs);
										jQuery('#cts-'+rid).val(obj.message.polish_carat);
										jQuery('#price-'+rid).val(obj.message.price);
										jQuery('#amount-'+rid).val(obj.message.amount);
									}
								}
							}
					});
					totalamount();
					addImportRow(rid);
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
    border: 1px solid #ccc;
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

<?php session_start(); ?>
<!DOCTYPE html>
<?php 


include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('gia',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
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
						<div class="page-header">							
							<h1 style="float:left">
								GIA Lab Stock 
							</h1>
							
						</div><!-- /.page-header -->
						
						<div class="row">
						<?php
							include_once($daiDir.'Helper.php');
							include_once($daiDir.'module/party/partyModel.php');
							$pmodel  = new partyModel($cn);
							$helper  = new Helper($cn);
							$attribute = $helper->getAttribute();							
							$groupUrl = $daiDir.'module/inventory/';														
							$party = $pmodel->getOptionList();							
							?>
							<div class="form-group col-sm-6">
								<label class="col-sm-2 control-label no-padding-right" for="form-field-4">Company</label>
								<div class="col-sm-8">
									<select class="col-xs-10" id="ledger" name="party" onChange="loadMemo(this.value)">
										<option value="0">All Company Name </option>
										<?php 
										foreach($party as $key => $value):
										?>						
										<option value="<?php echo $key?>" ><?php echo $value?></option>
										<?php endforeach; ?>
									
									</select>
								</div>
							</div>
							
							
						</div>
					</div><!-- /.page-content -->
					<div style="clear:both"></div>
					<div class="in-out-crud"  id="memo-data" >
					
					</div>
				</div>
			</div><!-- /.main-content -->

			<?php
			include($daiDir."footer.php");
		?>
		</div><!-- /.main-container -->
		<div id="please-wait" style="display:none">
			<img src="<?php echo  $daiUrl;?>assets/images/loading.gif" />
		</div> 
		<div class="dialog-box-container" id="dialog-box-container" style="display:none;" >
			<div class="box-container" style="width:1100px" >
			</div>
		</div>
		<div class="dialog-box-container" id="edit-box-container" style="display:none;" >
			<div class="box-container" >
			</div>
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
		<script src="<?php echo  $daiUrl;?>assets/js/bootbox.js"></script>

		<script src="<?php echo  $daiUrl;?>assets/js/buttons.colVis.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/dataTables.select.min.js"></script>
		
		
		<script src="<?php echo  $daiUrl;?>assets/js/jquery-ui.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo  $daiUrl;?>assets/js/ace-elements.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/ace.min.js"></script>
		<script type="text/javascript">
			
			
			jQuery(function($) {
				loadMemo(0);
			});
			
		
			function loadMemo(id)
			{
				var data = {'id':id};
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'module/inventory/ggrid.php'; ?>', 
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
			function allCheck(temp,id)
			{
							
				if($(temp).is(':checked'))
				{
					$('.mform-'+id+' .divTableBody input:checkbox').parents('.divTableRow ').addClass('active');
					$('.mform-'+id+' .divTableBody input:checkbox').prop('checked',true);
				}
				else
				{
					$('.mform-'+id+' .divTableBody input:checkbox').parents('.divTableRow ').removeClass('active');
					$('.mform-'+id+' .divTableBody input:checkbox').prop('checked',false);
				}
				totalSelected(temp,id);
							
			}
			function totalSelected(temp,id)
			{
				var total = parseFloat($('.mform-'+id).find("input[type='checkbox']:checked").length);
				if(total > 0)
				{
					$('#return-'+id).attr('disabled',false);
					$('#sale-'+id).attr('disabled',false);
				}
				else
				{
					$('#return-'+id).attr('disabled',true);
					$('#sale-'+id).attr('disabled',true);
				}				
				$('#selected-row-'+id).html(total);
					
				if($(temp).is(':checked'))
				{
					$('.mform-'+id+' .divTableBody input:checkbox').parents('.divTableRow ').addClass('active');
				}
				else
				{
					$('.mform-'+id+' .divTableBody input:checkbox').parents('.divTableRow ').removeClass('active');
				}
				tc=0;
				$( ".active .amount" ).each(function( index ) {
				  var values = parseFloat($( this ).html());
				  if(!isNaN(values))
				  {
					tc = parseFloat(tc) + values;		
				  }
				});	
					
				$('#paid_amount').val(tc.toFixed(2));
				$('#due_amount').val(tc.toFixed(2));			
			}
			
			function closeMemo(id)
			{
				var data = {'fn':'closeGia','id':id};
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'module/outward/outwardController.php'; ?>', 
					type: 'POST',
					data: data,		
					success: function(result)
						{		
							if(result != "")
							{
								jQuery('#please-wait').hide();
								var obj = jQuery.parseJSON(result );						
								alert(obj.message);	
								loadMemo($('#ledger').val());
							}
						}
				});
			}
			
			function returnMemo(id)
			{
				 var checkValues = $('.mform-'+id+' input[name=products]:checked').map(function()
				{
					return $(this).val();
				}).get();
				
				
				var data = {'id':checkValues,'outid':id};
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'module/inventory/giaForm.php'; ?>', 
					type: 'POST',
					data: data,		
					success: function(result)
						{		
							if(result != "")
							{
								jQuery('#please-wait').hide();
								//var obj = jQuery.parseJSON(result );						
								//alert(obj.message);	
								//loadMemo($('#ledger').val());
								
								jQuery('#dialog-box-container').show();
								$('#dialog-box-container').html('<div class="box-container"  >'+result+'</div>');									
							}
						}
				});
			}
			
			function saveGia(id)
			{
								
				var data = $('#gia-form').serialize();
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'module/outward/outwardController.php'; ?>', 
					type: 'POST',
					data: data,		
					success: function(result)
						{		
							if(result != "")
							{
								jQuery('#please-wait').hide();
								var obj = jQuery.parseJSON(result );														
								alert(obj.message);									
								jQuery('#dialog-box-container').hide();
								loadMemo($('#ledger').val());
							}
						}
				});
			}
			function closeBox()
			{
				$('#dialog-box-container').hide();
			}
			
			function editSale(id,lid)
			{
				 $("html, body").animate({ scrollTop: 0 }, 100);
				var data = {'id':id,'lid':lid};
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'module/outward/sale.php'; ?>', 
					type: 'POST',
					data: data,		
					success: function(result)
						{		
							if(result != "")
							{
								jQuery('#please-wait').hide();
								jQuery('#edit-box-container').show();
								$('#edit-box-container .box-container').html(result);								
							}
						}
				});
			}
			
			function deleteSale(id,lid)
			{
				bootbox.confirm("Are you sure you want to delete ?", function(result) {
				if(result) 
				{
					var data = {'id':id,'lid':lid,'fn':'deleteSale'};
					jQuery('#please-wait').show();
					
					jQuery.ajax({
						url: '<?php echo $daiUrl.'module/outward/outwardController.php'; ?>', 
						type: 'POST',
						data: data,		
						success: function(result)
						{		
							if(result != "")
							{
								var obj = jQuery.parseJSON(result);
								alert(obj.message);
								if(obj.status)
								{
									loadMemo(lid);
								}
								Query('#please-wait').hide();
							}
						}
					});
				}
				});				
			}
			function closeBox1(id)
			{
				$('#edit-box-container').hide();			
				$('#edit-box-container .box-container').html('');
				loadMemo(id);
			}
				
		</script>

		
	</body>
<style>
.page-header 
{
  float: left;
  margin-bottom: 20px;
  padding-bottom: 0;
  width: 100%;
}
#edit-box-container .box-container {
	background: #fff none repeat scroll 0 0 !important;
	height: auto;
	margin: 1% auto 0;
	min-height: 780px;
	padding: 10px;
	width: 1300px;
	height:auto;
}
#edit-box-container {	
	position: absolute;	
}
.outward.divTableBody {height:150px; overflow-y:auto;}
</style>
</html>







	
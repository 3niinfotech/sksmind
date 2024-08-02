<?php session_start(); ?>
<!DOCTYPE html>
<?php 

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('memo',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
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
								On Memo Stock 
							</h1>
							
						</div><!-- /.page-header -->
						
						<div class="row">
						<?php
							include_once($daiDir.'Helper.php');
							include_once($daiDir.'module/party/partyModel.php');
							$pmodel  = new partyModel();
							$helper  = new Helper();
							$attribute = $helper->getAttribute();							
							$groupUrl = $daiDir.'module/inventory/';														
							$party = $pmodel->getOptionList();
							$book = $helper->getAllBook(); 							
							?>
							<form id="sale-form" onsubmit="return false;" >
							<input name="start" id="start" value='0' type="hidden">
							<div class="form-group col-sm-5">
								<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Company</label>
								<div class="col-sm-8">
									<select class="col-xs-10" id="ledger" name="party" onChange="loadMemo()">
										<option value="">All Company Name</option>
										<?php 
										foreach($party as $key => $value):
										?>						
										<option value="<?php echo $key?>" ><?php echo $value?></option>
										<?php endforeach; ?>
									
									</select>
								</div>
							</div>
							<div class="form-group col-sm-2">
								<label class="col-sm-4 control-label no-padding-right" for="form-field-4"> Invoice</label>
								<div class="col-sm-8">
									<input class="input-sm col-sm-12" name="invoice" onChange="loadMemo()" onBlur="loadMemo()" type="text">
								</div>
							</div>
							<button class="btn btn-info" type="button" onclick="changePage(1)" style="float: right; margin: 0px; margin-right:10px;">			
								Next
							</button>
							<button class="btn btn-info" type="button" onclick="changePage(0)" style="float: right; margin: 0px; margin-right:10px;">			
								Previous
							</button>
							</form>
							
						</div>
					</div><!-- /.page-content -->
					<div style="clear:both"></div>
						<div class="in-out-crud"  id="memo-data" >
						
						</div>
				</div>
			</div><!-- /.main-content -->
			<div class="dialog-box-container" id="dialog-box-container" style="display:none;" >
				<div class="box-container" style="width: 1000px; min-height:400px;">
					<input id="saleno" name="saleno" type="hidden">	
					<div class="page-header">							
						<h1 style="float:left">
							Sale Stone Detail						
						</h1>
						<button id="close-box" onclick="closeBox()" style="float:right" class="btn reset" type="button">
							<i class="ace-icon fa fa-close bigger-110"></i>
							Close
						</button>
					</div>
					<form id="saleMemo" >
					<input class="input-sm col-sm-12" id="memoProdutcs" name="products" type="hidden">	
					<input class="input-sm col-sm-12" value='saleMemo' name="fn" type="hidden">	
					<input class="input-sm col-sm-12" id="salefromno" value='' name="id" type="hidden">	
					
					<div class="form-group col-sm-4">
						<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Invoice Date</label>
						<div class="col-sm-8">
							<input class="input-sm col-sm-12" id="invoicedate" name="invoicedate" placeholder="Select Date" type="text">
						</div>
					</div>
					<div class="form-group col-sm-4">
						<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Terms</label>
						<div class="col-sm-8">
							<input class="input-sm col-sm-12" id="terms"  name="terms" placeholder="Your Terms" value="0" type="text">
						</div>
					</div>
					<div class="form-group col-sm-4">
						<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Due Date</label>
						<div class="col-sm-8">
							<input class="input-sm col-sm-12" id="duedate"  name="duedate" placeholder="2016/09/02" type="text">
						</div>
					</div>
					<div style="clear:both"></div>
					<br>
					<div class="form-group col-sm-1" style="padding: 0px; margin-bottom: 5px; margin-right: 0px;width:160px; ">								
						<div class="col-sm-12">			
						<input type="checkbox" name="on_payment" id="on_payment" > On Payment	
						</div>
					</div>
					
					<div class="form-group col-sm-3" style="padding: 0px; margin-bottom: 5px; margin-right: 0px; ">								
						<label class="col-sm-4 control-label no-padding-right a-right" for="form-field-4">Book</label>
						<div class="col-sm-8">				
							<select class="col-xs-12" name="book" onChange="changeBook(this.value)">
								<option value="">Book</option>
								<?php foreach($book as $k=>$v): ?>
									<option value="<?php echo $k ?>"><?php echo $v ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="form-group col-sm-3" style="padding: 0px; margin-bottom: 5px; margin-right: 0px; ">
						<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Date</label>
						<div class="col-sm-8">
							<input class="input-sm col-sm-10" id="bdate"  name="bdate" placeholder="2016/08/20" type="text">
						</div>
					</div>
					<div class="form-group col-sm-3" style="padding:0px;margin-bottom: 5px;">
						<label class="col-sm-4 control-label no-padding-right a-right" for="form-field-4">Currency</label>
						<div class="col-sm-8">
							<input class="input-sm col-sm-12" id="paid_amount" name="paid_amount" type="text">	
							<input class="input-sm col-sm-12" id="due_amount" name="due_amount" type="hidden">	
						</div>
					</div>	
					<div class="clearfix form-actions" style="float:left;width:100%;text-align:center;" >
						<div class="col-md-12">
							 <button class="btn btn-info" type="button" onClick="saleMemo()" >
								<i class="ace-icon fa fa-check bigger-110"></i>
								Save
							</button>

							&nbsp; &nbsp; &nbsp;
							<button class="btn reset" type="reset">
								<i class="ace-icon fa fa-undo bigger-110"></i>
								Reset
							</button>
						</div>
					</div>
					</form>
				</div>
			</div>
			<?php
			include($daiDir."footer.php");
		?>
		</div><!-- /.main-container -->
		<div id="please-wait" style="display:none">
			<img src="<?php echo  $daiUrl;?>assets/images/loading.gif" />
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
				$( "#edate, #einvoicedate" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});
				$("#terms,#invoicedate").on("change", function()
				{
					   var selectedDate = $("#invoicedate").val();

					  // var finalDate = selectedDate.substring(6,11)+"/"+selectedDate.substring(3,6)+"/"+selectedDate.substring(0,2);
					   var date = new Date(selectedDate);

						days = parseInt($("#terms").val(), 10);
					   if(days == '')
					   {
						   days = 0 ;
					   }
						if(!isNaN(date.getTime())){
							date.setDate(date.getDate() + days);
							if(date.toInputFormat() != "NaN-NaN-NaN")
							{
								$("#duedate").val(date.toInputFormat());
							}
							else
							{
								$("#duedate").val('');
							}
						} else {
							alert("Invalid Date");  
						}
					
				});
				
			Date.prototype.toInputFormat = function()
			   {
			   var yyyy = this.getFullYear().toString();
			   var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
			   var dd  = this.getDate().toString();				
			   return  yyyy  + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0] ) ;  					
			   };
				
			loadMemo('');	
			});
			
			function closeBox()
			{
				$('#dialog-box-container').hide();
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
			function loadMemo()
			{
				
				var data = jQuery('#sale-form').serialize();
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'module/inventory/mgrid.php'; ?>', 
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
			function totalSelected(temp,id)
			{
				var total = parseFloat($('.mform-'+id).find(".divTableBody input[type='checkbox']:checked").length);
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
				var data = {'fn':'closeMemo','id':id};
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
			
		/* 	function returnMemo(id)
			{
				bootbox.confirm("Are you sure  ?", function(result) {
				if(result) 
				{
					var checkValues = $('.mform-'+id+' input[name=products]:checked').map(function()
					{
						return $(this).val();
					}).get();
					
					
					var data = {'fn':'returnMemo','products':checkValues,'id':id};
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
				});
				
			} */
			
			function returnMemo(id,pid)
			{
				$("html, body").animate({ scrollTop: 0 }, 100);
				
				
				var checkValues = $('.mform-'+id+' input[name=products]:checked').map(function()
				{
					return $(this).val();
				}).get();
					
				var data = {'id':id,'pid':pid,'ids':checkValues};
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'module/inventory/memoReturn.php'; ?>', 
					type: 'POST',
					data: data,		
					success: function(result)
						{		
							if(result != "")
							{
								jQuery('#please-wait').hide();
								jQuery('#edit-box-container').show();
								$('#edit-box-container .box-container').html(result);								
								$( "#date, #invoicedate" ).datepicker({
									showOtherMonths: true,
									selectOtherMonths: false,
									dateFormat: 'yy-mm-dd',
								});
							}
						}
				});
			}
			
			function saleMemo()
			{
				if(confirm('Are you sure ?'))
				{
					var id = $('#saleno').val();
					var checkValues = $('.mform-'+id+' input[name=products]:checked').map(function()
					{
						return $(this).val();
					}).get();
					
					var idate = $('#invoicedate').val();
					var terms = $('#terms').val();
					var ddate = $('#duedate').val();
					if(idate =="" || terms =="" || ddate ==""){
						alert('Please Enter Value.');
						return 0;
					}
					
					jQuery('#memoProdutcs').val(checkValues);
					jQuery('#salefromno').val(id);
					
					//var data = {'fn':'saleMemo','products':checkValues,'id':id,'idate':idate,'terms':terms,'ddate':ddate};
					var data = jQuery('#saleMemo').serialize();
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
									
									$('#dialog-box-container').hide();
									if(obj.status)
									{
										loadMemo($('#ledger').val());
									}
									
								}
							}
					});
				}
				
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
								jQuery( ".divTableCell .stone" ).autocomplete({
									source: suggestions
								});
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
			
			function memoToSale(id,pid)
			{
				$("html, body").animate({ scrollTop: 0 }, 100);
				
				
				var checkValues = $('.mform-'+id+' input[name=products]:checked').map(function()
				{
					return $(this).val();
				}).get();
					
				var data = {'id':id,'pid':pid,'ids':checkValues};
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'module/inventory/memoToSale.php'; ?>', 
					type: 'POST',
					data: data,		
					success: function(result)
						{		
							if(result != "")
							{
								jQuery('#please-wait').hide();
								jQuery('#edit-box-container').show();
								$('#edit-box-container .box-container').html(result);								
								$( "#date, #invoicedate" ).datepicker({
									showOtherMonths: true,
									selectOtherMonths: false,
									dateFormat: 'yy-mm-dd',
								});
							}
						}
				});
			}
			
			
			function closeBox1(id)
			{
				$('#edit-box-container').hide();			
				$('#edit-box-container .box-container').html('');
				loadMemo(id);
			}
			function changeBook(bval)
			{
				if(bval == "")
				{
					jQuery('#on_payment').prop( "checked", false );
				}
				else
				{
					jQuery('#on_payment').prop( "checked", true );
				}
			}
			
			function changePage(v)
			{
				var start = parseInt(jQuery('#start').val());
				
				if(v == 1)
				{
					start = start + 10;
				}
				else
				{
					if(start !=0 )
					{	
						start = start - 10;
					}
				}
				jQuery('#start').val(start);
				loadMemo();
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
.cgrid-header {
	width: 62%;
}
</style>
</html>







	
<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('sale',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}
							
include_once('jewelryModel.php');
include_once($daiDir.'jHelper.php');
include_once($daiDir.'Helper.php');
include_once($daiDir.'jewelry/party/partyModel.php');

$model  = new jewelryModel($cn);
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
//$attribute = $helper->getAttribute();							
$groupUrl = $daiDir.'jewelry/jewelry/';
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$sid= $lid=0;
if(isset($_POST['id']))
{
	$sid = $_POST['id'];
}
if(isset($_POST['lid']))
{
	$lid = $_POST['lid'];
}
$jewType = $jhelper->getJewelryType();
$design = $jhelper->getAllDesign();
$data =  $model->getSaleData($sid);

$sku =  $helper->getAllSku();
$shipping = $helper->getAllShipping(); 
//$origin = $helper->getAllOrigin(); 
include_once($groupUrl."sform.php");							
?>						
		<script type="text/javascript">
			var suggestions;
			var n = <?php echo (count($data['record']))? count($data['record']):'1';?>;
			n++;
			
			jQuery(function($) {
				
		
				$( "#edate, #einvoicedate" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});
				
				
					
			   Date.prototype.toInputFormat = function()
			   {
			   var yyyy = this.getFullYear().toString();
			   var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
			   var dd  = this.getDate().toString();				
			   return  yyyy  + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0] ) ;  					
			   };

				
			suggestions = new Array(			 
			  <?php foreach($sku as $k=>$v):?>
			  '<?php echo $v ?>',
			  <?php endforeach;?>''
			  );
			
			 $(".divTableCell .stone").bind("keypress", function(e)
			 {
				 var code = (e.keyCode ? e.keyCode : e.which);
				if (code == 13) 
				{ //Enter keycode                        
					e.preventDefault();
					var attr = jQuery(this).attr('rid');
					 
					if (typeof attr !== typeof undefined && attr !== false) 
					{
						var rid = jQuery(this).attr('rid');
						var sku = jQuery(this).val();
						
						loadDataBySku(rid,sku)
						
					}
				}
			});
			 
			 jQuery( ".divTableCell .stone" ).autocomplete({
					source: suggestions
				});
			});	
			
			
			function calEditDue()
				{
					   var selectedDate = $("#einvoicedate").val();

					  // var finalDate = selectedDate.substring(6,11)+"/"+selectedDate.substring(3,6)+"/"+selectedDate.substring(0,2);
					   var date = new Date(selectedDate);

						days = parseInt($("#eterms").val(), 10);
						if(days == '')
						{
							$("#eduedate").val(selectedDate);
							return;
						}
						if(!isNaN(date.getTime())){
							date.setDate(date.getDate() + days);
							if(date.toInputFormat() != "NaN/NaN/NaN")
							{
								$("#eduedate").val(date.toInputFormat());
							}
							else
							{
								$("#eduedate").val('');
							}
						} else {
							alert("Invalid Date");  
						}
					
				};
			
			function keypressInBox(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if (code == 13) { //Enter keycode                        
				e.preventDefault();
				}
			};
			
				
			function countCheck(temp)
			{
				var total = $('#dynamic-table').find("input[type='checkbox']:checked").length;
				$('#selected-row').html(total);
				if($(temp).is(':checked'))
				{
					$(temp).parents('tr').addClass('active');
				}
				else
				{
					$(temp).parents('tr').removeClass('active');
				}
			}

			function addImportRow(rid)
			{
				
				var total = $('#edit-box-container .divTableBody.sdivtable .divTableRow').length;
				if(total == rid)
				{
					
				var row = '<div class="divTableRow" id="rowid-'+n+'">';
				row += '<div class="divTableCell"><i class="delete-more fa fa-times " onClick="removeRow('+n+')" ></i></div>';
				row += '<div class="divTableCell">'+n+'</div>';
				row += '<div class="divTableCell"><input class=" col-sm-12 stone" rid="'+n+'" name="record['+n+'][sku]" onBlur="addImportRow('+n+')" id="sku-'+n+'" type="text"></div>';
				/* row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record['+n+'][polish_pcs]" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record['+n+'][polish_carat]" id="pcarat-'+n+'" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record['+n+'][cost]" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record['+n+'][price]" id="price-'+n+'" onBlur="calAmount('+n+')" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record['+n+'][amount]" id="amount-'+n+'" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12"  name="record['+n+'][location]" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12"  name="record['+n+'][remark]" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12"  name="record['+n+'][lab]" type="text"></div>'; */
				<?php /*foreach($attribute as $key=>$v): ?>
				row += '<div class="divTableCell">';			
					row += '<input class=" col-sm-12"  name="record['+n+'][attr][<?php echo $key?>]" type="text">';
				row += '</div>';
				<?php endforeach; */ ?>
		
				row +='</div>';
				n = n + 1;
				$('#edit-box-container .sdivtable').append(row);
				
				
				$(".stone").bind("keypress", function(e)
				 {
					 var code = (e.keyCode ? e.keyCode : e.which);
					if (code == 13) 
					{ //Enter keycode                        
						e.preventDefault();
						 var attr = jQuery(this).attr('rid');
						 
						if (typeof attr !== typeof undefined && attr !== false) 
						{
							var rid = jQuery(this).attr('rid');
							var sku = jQuery(this).val();
							
							loadDataBySku(rid,sku)
							
						}
					}
				});
					jQuery( ".divTableCell .stone" ).autocomplete({
					source: suggestions
				});
				}
				
			}	
			
			function loadDataBySku(rid,sku)
			{
				
				var data = {'sku':sku,'rid':rid};
				
				jQuery('#please-wait').show();
				
				jQuery.ajax({
					url: '<?php echo $daiUrl.'jewelry/jewelry/importNewRow.php'; ?>', 
					type: 'POST',
					data: data,		
					success: function(result)
						{		
							if(result != "")
							{
								//$('#memo-data').html(result);									
								jQuery('#please-wait').hide();
								
								if(result.trim() == 'no')
								{	
									alert('No Data found');
								}
								else
								{
									$('#rowid-'+rid).html(result);
									changeSellPrice();
									$(".stone").bind("keypress", function(e)
									 {
										 var code = (e.keyCode ? e.keyCode : e.which);
										if (code == 13) 
										{ //Enter keycode                        
											e.preventDefault();
											 var attr = jQuery(this).attr('rid');
											 
											if (typeof attr !== typeof undefined && attr !== false) 
											{
												var rid = jQuery(this).attr('rid');
												var sku = jQuery(this).val();
												
												loadDataBySku(rid,sku);
												
											}
										}
									});
									jQuery( ".divTableCell .stone" ).autocomplete({
										source: suggestions
									});		
								}
							}
						}
				});
			
			}
		function removeRow(id)
		{
			$('#rowid-'+id).remove();
			$('#record-'+id).remove();			
			$('#products-'+id).remove();
			changeSellPrice();
		}	
		function saveEditSale()
		{
			var data = jQuery('#edit-box-container #edit-sale').serialize();
			jQuery('#please-wait').show();
			
			jQuery.ajax({
				url: '<?php echo $daiUrl.'jewelry/jewelry/jewelryController.php'; ?>', 
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
								editSale(<?php echo $sid; ?>,<?php echo $lid; ?>);
							}
							//jQuery('#dialog-box-container').show();
							//$('.box-container').html(result);
							
						}
					}
			});
		}
		function calAmount(rid)
		{
			var price = parseFloat($('#price-'+rid).val());
			var pcarat = parseFloat($('#pcarat-'+rid).val());
			var total  = parseFloat(price * pcarat );
			if(!isNaN(total))
			{
				$("#amount-"+rid ).val(total.toFixed(2));
			}
			else
			{
				$("#amount-"+rid ).val(0);
			}
			
		}
		function changeSellPrice()
		{
			var total =0.0;
			
			$(".sdivtable .divTableCell .sell_price" ).each(function( index ) 
			{
				
				  var amount = parseFloat($( this ).val());
				
					  if(!isNaN(amount))
					  {
						total = amount + total;
					  }	 
				
			});
				
				if(!isNaN(total))
				{
					$('#sellamount').val(total.toFixed(2)); 			
				}
			
		}	
		</script>

		
	
	<style>
	.page-header {
	  float: left;
	  margin-bottom: 20px;
	  padding-bottom: 0;
	  width: 100%;
	}
.subform {
  float: left;
  overflow-x: scroll;
  padding: 0px 10px 25px 10px;
  width: 100%;
}
.sdivtable
{
	height:200px;
	overflow-y:auto;
}
.ui-autocomplete {
	background-color: #FFF;
	box-shadow: 0 2px 4px rgba(0, 0, 0, .2);
	z-index: 999999999999;
}
.bdiv{
	border: 1px solid #ccc;
	height: 26px;
}
	</style>








	
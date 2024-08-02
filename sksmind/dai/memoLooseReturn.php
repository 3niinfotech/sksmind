<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('memo',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}
include_once($daiDir.'jewelry/outward/outwardModel.php');
include_once($daiDir.'Helper.php');
include_once($daiDir.'jewelry/party/partyModel.php');

include_once('inventoryModel.php');
$imodel  = new inventoryModel($cn);

$model  = new outwardModel($cn);
$helper  = new Helper($cn);
$attribute = $helper->getAttribute();
$width50 = $helper->width50(); 							
$groupUrl = $daiDir.'jewelry/outward/';
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$lid=0;
if(isset($_POST['id']))
{
	$lid = $_POST['id'];
}
$data =  $model->getData($lid);		
$book = $helper->getAllBook(); 
$shipping = $helper->getAllShipping(); 
$origin = $helper->getAllOrigin(); 
?>

<div class="page-header">							
	<h1 style="float:left">
		Memo Return
	</h1>
	<button id="close-box" onclick="closeBox1()" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
	
	<button class="btn btn-info" style="float:right;margin-right: 10px;" id="btn-save" type="button" onClick="saveMemoForm()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Return Memo
	</button>
	
	<button class="btn reset"  style="float:right;margin-right: 10px;" type="reset">
		<i class="ace-icon fa fa-undo bigger-110"></i>
		Reset
	</button>	
	
</div>
<?php

$entryno = $model->getIncrementEntry('outward');


$invoice = $model->getIncrementEntry('invoice');

/*if($_POST['type'] == 'memo'):
	$invoice = $model->getIncrementEntry('memo_invoice');
endif;

if($_POST['type'] == 'lab'):
	$invoice = $model->getIncrementEntry('lab_invoice');
endif;
*/
?>

<form class="form-horizontal" id="memo-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="memoToReturn" />
<input type="hidden" name="memo_id" value="<?php echo $lid; ?>" />
<div class="subform invenotry-cgrid sendToOut">
<div class="divTable" >
	
	
	<div class="divTableHeading">
			<div class="divTableCell">No</div>
			<div class="divTableCell">Type</div>
			<div class="divTableCell">SKU</div>
			<div class="divTableCell">PCS</div>
			<div class="divTableCell">Carat</div>
			<div class="divTableCell">Price</div>
			<div  class="divTableCell">Amount</div>
			<div  class="divTableCell">Lab</div>
			<div  class="divTableCell">IGI Code</div>
			<div  class="divTableCell">IGI Color</div>
			<div  class="divTableCell">IGI Clarity</div>
			<div  class="divTableCell">IGI Price</div>
			<div  class="divTableCell">IGI Amount</div>
	</div>		
	
	<div class="divTableBody" style="height:180px; overflow-y:auto;">
	<?php 
	
	$i=1;
	$pcs = $carat = $price = $amount = 0.0;
	$mproducts = array();
	if(isset($_POST['main_stone']))
	$mproducts = $_POST['main_stone'];
	foreach($mproducts  as $id): 
	 if($id == '')
		 continue;
	$value = $imodel->getDetail($id,'main');
	$pcs = $pcs + $value['pcs'];
	$carat = $carat + $value['carat'];
	$fprice = ($value['sell_price'] ==0)?$value['price']:$value['sell_price'];
	$price = $price + $fprice;
	$famount =($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'];
	$amount = $amount + $famount;
	?>
	<input type="hidden" name="main_stone[]" value="<?php echo $id ?>" />
	<div class="divTableRow infobox-grey infobox-dark">		
		<div class="divTableCell"><?php echo $i?></div>
		<div class="divTableCell"><?php echo "Main"?></div>
		<div class="divTableCell  "><?php echo $value['msku'];?></div>
		<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
		<div class="divTableCell  a-right" id="mcarat-<?php echo $id ?>"><?php echo $value['carat'];?></div>			
		<div class="divTableCell  a-right"><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $id ?>][price]" id="mprice-<?php echo $id ?>" onBlur="mcalAmount(<?php echo $id ?>)" value="<?php echo ($value['sell_price'] ==0)?$value['price']:$value['sell_price'] ?>"  type="text"><input name="mrecord[<?php echo $id ?>][amount]" id="mamount1-<?php echo $id ?>" type="hidden" value="<?php echo (isset($value['amount']))?$value['amount']:'&nbsp;'?>"> </div>	<div class="divTableCell amount1 a-right" id="mamount-<?php echo $id ?>"><?php echo ($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'] ?></div>	
		<div class="divTableCell  "><?php echo $value['lab'];?></div>
		<div class="divTableCell  "><?php echo $value['igi_code'];?></div>			
		<div class="divTableCell  "><?php echo $value['igi_color'];?></div>			
		<div class="divTableCell a-right "><?php echo $value['igi_clarity'];?></div>			
		<div class="divTableCell a-right "><?php echo $value['igi_price'];?></div>			
		<div class="divTableCell a-right "><?php echo $value['igi_amount'];?></div>
		
	</div>
	
	<?php $i++;
	endforeach;
	?>
	<?php
	$products = array();
	if(isset($_POST['side_stone']))
	$products = $_POST['side_stone'];
	foreach($products  as $id): 
	 if($id == '')
		 continue;
	$value = $imodel->getDetail($id,'loose');
	if($value['outward_parent'] == 0)
	{
		$sku = $value['sku'];
	}
	else
	{
		$parentData = $imodel->getDetail($value['outward_parent'],'loose');					
		$sku = $parentData['sku'];
	}
	$pcs = $pcs + $value['pcs'];
	$carat = $carat + $value['carat'];
	$fprice = ($value['sell_price'] ==0)?$value['price']:$value['sell_price'];
	$price = $price + $fprice;
	$famount =($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'];
	$amount = $amount + $famount;
	?>
	<input type="hidden" name="side_stone[]" value="<?php echo $id ?>" />
	<div class="divTableRow infobox-grey infobox-dark">		
		<div class="divTableCell"><?php echo $i?></div>
		<div class="divTableCell"><?php echo "side"?></div>
		<div class="divTableCell  "><?php echo $sku;?></div>
		<div class="divTableCell"><input class=" col-sm-12  a-right"  name="record[<?php echo $id ?>][pcs]" type="text" value="<?php echo (isset($value['pcs']))?$value['pcs']:'&nbsp;'?>" ></div>
		<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $id ?>][carat]" onChange="calAmount(<?php echo $id ?>)" id="pcarat-<?php echo $id ?>" type="text" value="<?php echo (isset($value['carat']))?$value['carat']:'&nbsp;'?>"></div>			
		<div class="divTableCell  a-right"><input class=" col-sm-12 a-right"  name="record[<?php echo $id ?>][price]" id="price-<?php echo $id ?>" onBlur="calAmount(<?php echo $id ?>)" value="<?php echo ($value['sell_price'] ==0)?$value['price']:$value['sell_price'] ?>"  type="text"> <input name="record[<?php echo $id ?>][amount]" id="amount1-<?php echo $id ?>" type="hidden" value="<?php echo (isset($value['amount']))?$value['amount']:'&nbsp;'?>"></div>			
		<div class="divTableCell amount1 a-right" id="amount2-<?php echo $id ?>"><?php echo ($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'] ?></div>	
		<div class="divTableCell  "></div>
		<div class="divTableCell  "></div>			
		<div class="divTableCell  "></div>			
		<div class="divTableCell a-right "></div>			
		<div class="divTableCell a-right "></div>			
		<div class="divTableCell a-right "></div>
		
	</div>
	
	<?php $i++;
	endforeach;
	?>
</div>	
</div>
</div>
<div class="inventory-color">
<div class="inventory-color-cell" style="width:100%;padding-bottom:10px;border-bottom:1px solid #ccc;margin-bottom:15px">		
		<div class="color-total color-total-count"> <b>Pcs :</b> &nbsp;&nbsp;<span id="total-pcs"><?php echo $pcs ?> </span> &nbsp;&nbsp; |</div>
		<div class="color-total color-total-count"> <b>Carats :</b> &nbsp;&nbsp;<span id="total-carat"><?php echo $carat ?></span> &nbsp;&nbsp; |</div>
		<div class="color-total color-total-count"> <b>Price :</b> &nbsp;&nbsp;<span id="total-price"><?php echo number_format(((float)$amount / (float)$carat),2,".","") ?></span> &nbsp;&nbsp; |</div>
		<div class="color-total color-total-count"> <b>Amount :</b> &nbsp;&nbsp;<span id="total-amount1"><?php echo $amount ?></span> &nbsp;&nbsp; |</div>		
		<textarea class="form-control" id="form-field-8" placeholder="Narretion" style="width: 500px; height:30px;" name="narretion"><?php echo $data['narretion']?></textarea>
	</div>
</div>
<div style="clear:both"></div>

</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">
	
	$( "#minvoicedate,#mdate" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});
	$("#minvoicedate").on("change", function()
	{
		var selectedDate = $("#minvoicedate").val();

		$("#mdate").val(selectedDate);
		
		// var finalDate = selectedDate.substring(6,11)+"/"+selectedDate.substring(3,6)+"/"+selectedDate.substring(0,2);
	    var date = new Date(selectedDate);

		
		days = parseInt($("#mterms").val(), 10);
		if(days == '')
		{
			$("#mduedate").val(selectedDate);
			return;
		}
	    if(!isNaN(date.getTime())){
			date.setDate(date.getDate() + days);
			if(date.toInputFormat() != "NaN/NaN/NaN")
			{
				$("#mduedate").val(date.toInputFormat());
			}
			else
			{
				$("#mduedate").val('');
			}
		} else {
			alert("Invalid Date");  
		}
		
	});	

function calDueDate()
	{
		   var selectedDate = $("#minvoicedate").val();
		
		  // var finalDate = selectedDate.substring(6,11)+"/"+selectedDate.substring(3,6)+"/"+selectedDate.substring(0,2);
		   var date = new Date(selectedDate);

			days = parseInt($("#mterms").val(), 10);
		   
			if(!isNaN(date.getTime())){
				date.setDate(date.getDate() + days);

				if(date.toInputFormat() != "NaN/NaN/NaN")
				{
					$("#mduedate").val(date.toInputFormat());
				}
				else
				{
					$("#mduedate").val('');
				}
			} else {
				alert("Invalid Date");  
			}
		
	}	
function saveMemoForm()
{
	jQuery('#btn-save').attr('disabled',true);
	var data =  $("#memo-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $jewelryUrl.'outward/outwardController.php'?>', 
		type: 'POST',
		data: data,		
		success: function(result)
			{		
				if(result != "")
				{
					//jQuery('#dialog-box-container').show();
					//$('#dialog-box-container').html('<div class="box-container-memo" >'+result+'</div>');									
					jQuery('#please-wait').hide();
					var obj = jQuery.parseJSON(result);
					alert(obj.message);
					if(obj.status)
					{
						$('#edit-box-container').hide();			
						$('#edit-box-container .box-container').html('');
						loadMemo(0);
					}
					jQuery('#btn-save').attr('disabled',false);
				}
			}
	});	
}
function changeTextPrice(price,id)
{
	var carat = parseFloat($('#carat-'+id).html());
	var amount = parseFloat(price) * carat;
	if(!isNaN(amount))
	{
		$('#amount-'+id).html(amount.toFixed(2));
		jQuery('#please-wait').show();
		var data = {'id':id,'price':price,'fn':'updateSinglePrice'};
		jQuery.ajax({
		url: '<?php echo $moduleUrl.'inward/inwardController.php'?>', 
		type: 'POST',
		data: data,		
		success: function(result)
		{		
			jQuery('#please-wait').hide();
			totalamount();
		}
		});
	}	
}
function changeBook(bval)
{
	if(bval == "")
	{
		jQuery('#on_payments').prop( "checked", false );
	}
	else
	{
		jQuery('#on_payments').prop( "checked", true );
	}
}

function totalamount()
{
	var total =0.0;
	
	$(".divTableRow .divTableCell.amount1" ).each(function( index ) 
	{
		if(!$( this ).hasClass('skip'))
		{
			
		  var amount = parseFloat($( this ).html());
			  if(!isNaN(amount))
			  {
				total = amount + total;
			  }	 
		}	 
	});
	
		if(!isNaN(total))
		{
			$('#due_amount').val(total.toFixed(2)); 
			$('#paid_amount').val(total.toFixed(2)); 
			$('#total-amount1').html(total.toFixed(2)); 
			
		}
	
}
function calAmount(rid)
{
	
	var price = parseFloat($('#price-'+rid).val());
	var pcarat = parseFloat($('#pcarat-'+rid).val());
	var total  = parseFloat(price * pcarat );
	
	if(!isNaN(total))
	{
		$("#amount1-"+rid ).val(total.toFixed(2));
		$("#amount2-"+rid ).html(total.toFixed(2));
		$("#amount-"+rid ).addClass('skip');
	}
	else
	{
		$("#amount1-"+rid ).val(0);
		$("#amount2-"+rid ).val(0);
	}
	totalamount();
}
function mcalAmount(rid)
{
	
	var price = parseFloat($('#mprice-'+rid).val());
	var pcarat = parseFloat($('#mcarat-'+rid).html());
	var total  = parseFloat(price * pcarat );
	if(!isNaN(total))
	{
		$("#mamount1-"+rid ).val(total.toFixed(2));
		$("#mamount-"+rid ).html(total.toFixed(2));
	}
	else
	{
		$("#mamount1-"+rid).val(0);
		$("#mamount-"+rid).val(0);
	}
	totalamount();
}
function closePartyPopup()
{
	jQuery('#dialog-box-container1').hide();
}
function addParty()
{
	
	var data = {'id':10};
	jQuery.ajax({
	url: '<?php echo $jewelryUrl.'party/partyForm.php'?>', 
	type: 'POST',
	data: data,		
	success: function(result)
	{		
		 
		jQuery('#dialog-box-container1').html(result);
		jQuery('#dialog-box-container1').show();
	}
	});		
}
function calculateLess(value)
{
	value = parseFloat(value);
	
	var famount = parseFloat(jQuery('#due_amount').val());
	var total  = parseFloat((famount * value)/100);
	
	if(!isNaN(total))
	{	
		jQuery('#less_amount').val(Math.abs(total.toFixed(2)));
	}
	if(value<0)
	{
	 total  = famount - Math.abs(total);
	}
	else
	{
		total  = famount + Math.abs(total);
	}
 
	if(!isNaN(total))
	{	
		jQuery('#netless').val(Math.abs(total.toFixed(2)));
	}
	calculateTotalAmount();
}
function calculateOtherLess(value)
{
	
	value = parseFloat(value);
	
	var famount = parseFloat(jQuery('#netless').val());
	var total  = parseFloat((famount * value)/100);
	
	if(!isNaN(total))
	{	
		jQuery('#other_less_amount').val(Math.abs(total.toFixed(2)));
	}
	
	if(value<0)
	{
	 total  = famount - Math.abs(total);
	}
	else
	{
		total  = famount + Math.abs(total);
	}
	
	if(!isNaN(total))
	{	
		jQuery('#other_netless').val(Math.abs(total.toFixed(2)));
	}	
	calculateTotalAmount();
}

function calculateTotalAmount()
{
	var famount = parseFloat(jQuery('#due_amount').val());
	var less_percent = parseFloat(jQuery('#less_percent').val());
	var less_amount = parseFloat(jQuery('#less_amount').val());
	
	var other_less_percent = parseFloat(jQuery('#other_less_percent').val());
	var other_less_amount = parseFloat(jQuery('#other_less_amount').val());
	var extra = parseFloat(jQuery('#Extra').val());
	
	if(less_percent < 0)
	{
		famount = famount - less_amount;
	}
	if(less_percent > 0)
	{
		famount = famount + less_amount;
	}
	
	if(other_less_percent < 0)
	{
		famount = famount - other_less_percent;
	}
	if(other_less_percent > 0)
	{
		famount = famount + other_less_amount;
	}
	
	if(!isNaN(extra))
	{	
		famount = famount - extra;
	}
	
	if(!isNaN(famount))
	{	
		jQuery('#paid_amount').val(Math.abs(famount.toFixed(2)));
	}	
}
</script>	
<style>	
.divTableCell input {border:0 !important;}	
.form-group{margin-bottom:10px !important;}
</style>
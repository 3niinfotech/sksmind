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

include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');
include_once($daiDir.'jewelry/party/partyModel.php');

include_once($daiDir.'jewelry/jewelry/jewelryModel.php');
$model  = new jewelryModel($cn);
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
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
$data =  $model->getSaleData($lid);		
$book = $helper->getAllBook(); 
$shipping = $helper->getAllShipping(); 
$origin = $helper->getAllOrigin();
$jewType = $jhelper->getJewelryType();
?>

<div class="page-header">							
	<h1 style="float:left">
		Consignment to Sale
	</h1>
	<button id="close-box" onclick="closeBox1(<?php echo $_POST['pid']; ?>)" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
	
	<button class="btn btn-info" style="float:right;margin-right: 10px;" id="btn-save" type="button" onClick="saveMemoForm()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Save Sale
	</button>
	
	<button class="btn reset"  style="float:right;margin-right: 10px;" type="reset">
		<i class="ace-icon fa fa-undo bigger-110"></i>
		Reset
	</button>	
	
</div>
<?php

//$entryno = $model->getIncrementEntry('outward');


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
<input type="hidden" name="fn" value="memoTosaleJewelry" />
<input type="hidden" name="type" value="sale" />
<input type="hidden" name="memo_id" value="<?php echo $lid; ?>" />
<input type="hidden" name="status" value="1" />




<div class="col-xs-12 col-sm-12 ">
	
		
	
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Invoice</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="invoiceno" value="<?php echo $invoice?>" readonly name="invoiceno" placeholder="INC201608" type="text">
		</div>
	</div>
	
	
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Date <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="invoicedate" value="<?php echo $data['date']?>" name="date" placeholder="2016/08/20" type="text">
		</div>
	</div>
	
	
	<div class="form-group col-sm-6">
		<label class="col-sm-2 control-label no-padding-right" for="form-field-4">Company <span class="required">*</span></label>
		<div class="col-sm-10">
			<select class="col-xs-9" id="ledger" name="party">
				<option value="">Select Company Name</option>
				<?php 
				foreach($party as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['party'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			<a href="javascript:void(0);" onclick="addParty()" style="margin-top:8px;margin-left:5px;float: left;" > Add Company</a>
		</div>
	</div>
	<div class="form-group col-sm-2" style="padding: 0px; margin-bottom: 5px; margin-right: 0px; ">								
		<div class="col-sm-10">				
			<select class="col-xs-12" name="shipping_name" >
				<option value="">Select Shipping</option>
				<?php foreach($shipping as $k=>$v): ?>
					<option value="<?php echo $k ?>"><?php echo $v ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group col-sm-2" style="padding: 0px; margin-bottom: 5px; margin-right: 0px; ">		
			<div class="col-sm-10">				
				<select class="col-xs-12" name="origin_of" >
					<option value="">Select Origin</option>
					<?php foreach($origin as $k=>$v): ?>
						<option value="<?php echo $k ?>"><?php echo $v ?></option>
					<?php endforeach; ?>
				</select>
			</div>
	</div>
	<div class="form-group col-sm-2" style="padding:0px;margin-bottom: 5px;">
		<label class="col-sm-5 control-label no-padding-right a-right" for="form-field-4">Charge</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" name="shipping_charge" type="text">	
		</div>
	</div>	
	<div style="clear:both"></div>
	<div style="clear:both"></div>
	
</div>
<div class="subform invenotry-cgrid sendToOut">
<div class="divTable" >
	
	
	<div class="divTableHeading">
		
		<div class="divTableCell">No</div>		
		<div class="divTableCell" >Jew Code</div>
		<div class="divTableCell" >Design</div>
		<div class="divTableCell" >Type</div>			
		<div class="divTableCell width-50px" >Gold</div>
		<div class="divTableCell" >Gold Color</div>
		<div class="divTableCell" >Gross Weight</div>
		<div class="divTableCell" >Pg Weight</div>
		<div class="divTableCell" >Net Weight</div>			
		<div class="divTableCell" >Rate</div>
		<div class="divTableCell" >Amount</div>
		<div class="divTableCell" >Other Code</div>
		<div class="divTableCell " >Other Amount</div>
		<div class="divTableCell " >Labour Rate</div>
		<div class="divTableCell" >labour Amount</div>
		<div class="divTableCell" >Total Amount</div>
		<div class="divTableCell" >Final Price</div>
		
		
	</div>		
	
	<div class="divTableBody" style="height:180px; overflow-y:auto;">
	<?php 
	
	$i=1;
	$pcs = $carat = $price = $amount = 0.0;
	$products = array();
	
	$products = $_POST['ids'];
	
	$tc = $tg = $tf = 0;
	foreach($products  as $id): 
	
	$value = $model->getData($id);
	$tc += (float)$value['cost_price'];
	$tg += (float)$value['gst'];
	$sprice = ($value['selling_price'] ==0)?$value['total_amount']:$value['selling_price'];
	$tf += (float)$sprice;
	?>
	<input type="hidden" name="products[]" value="<?php echo $id ?>" />
	<div class="divTableRow infobox-grey infobox-dark">		
		<div class="divTableCell"><?php echo $i?></div>
		<div class="divTableCell"><?php echo $value['sku'] ?></div>				
				<div class="divTableCell"><?php echo $value['jew_design']?></div>			
				<div class="divTableCell"><?php echo $jewType[$value['jew_type']] ?></div>		
				<div class="divTableCell width-50px"><?php echo $value['gold'] ?></div>				
				<div class="divTableCell"><?php echo $value['gold_color'] ?></div>				
				<div class="divTableCell"><?php echo $value['gross_weight'] ?></div>				
				<div class="divTableCell"><?php echo $value['pg_weight'] ?></div>				
				<div class="divTableCell"><?php echo $value['net_weight'] ?></div>				
				<div class="divTableCell"><?php echo $value['rate'] ?></div>				
				<div class="divTableCell"><?php echo $value['amount'] ?></div>			
				<div class="divTableCell"><?php echo $value['other_code'] ?></div>		
				<div class="divTableCell"><?php echo $value['other_amount'] ?></div>		
				<div class="divTableCell"><?php echo $value['labour_rate'] ?></div>		
				<div class="divTableCell"><?php echo $value['labour_amount'] ?></div>	
				<div class="divTableCell"><?php echo $value['total_amount'] ?></div>	
				<div class="divTableCell a-right"><input class="sellprice" name='record[<?php echo $id ?>][amount]' style="width: 100%; height: 100%; border: 0px none; text-align: right;" type="text" id="price-<?php echo $value['id'] ?>" value="<?php echo $sprice?>" onBlur="changeSellPrice()"></div>				
	</div>
	
	
	
	<?php $i++;
	endforeach;?>
</div>	
</div>
<div style="clear:both"></div>
	
<div class="form-group col-sm-4 center" style="margin-bottom: 0px !important;">
	
</div>

<div class="form-group col-sm-2 center" style="margin-bottom: 0px !important;">
	<label style="padding-top: 0px !important;" class="col-sm-12 control-label center no-padding-right" for="form-field-4">Final Amount</label>
	<div class="col-sm-12">
		<input class="input-sm col-sm-12 a-right" readonly  name="final_amount" value="<?php echo $tf; ?>" type="text" style="margin-right:5px">					
	</div>
</div>
<div class="form-group col-sm-2 center" style="margin-bottom: 0px !important;">
	<label style="padding-top: 0px !important;" class="col-sm-12 control-label center no-padding-right" for="form-field-4">Sell Amount</label>
	<div class="col-sm-12">
		<input id="sellamount" class="input-sm col-sm-12 a-right" readonly  value="<?php echo $tf; ?>" type="text" style="margin-right:5px">					
	</div>
</div>
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
		url: '<?php echo $jewelryUrl.'jewelry/jewelryController.php'?>',
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
function changeSellPrice()
{
	var total =0.0;
	
	$(".divTableRow .divTableCell .sellprice" ).each(function( index ) 
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
.divTableCell input {border:0 !important;}	
.form-group{margin-bottom:10px !important;}
</style>
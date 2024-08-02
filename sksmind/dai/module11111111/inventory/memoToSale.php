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
include_once($daiDir.'module/outward/outwardModel.php');
include_once($daiDir.'Helper.php');
include_once($daiDir.'module/party/partyModel.php');

include_once('inventoryModel.php');
$imodel  = new inventoryModel();

$model  = new outwardModel();
$helper  = new Helper();
$attribute = $helper->getAttribute();
$width50 = $helper->width50(); 							
$groupUrl = $daiDir.'module/outward/';
$pmodel  = new partyModel();
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
		Memo to Sale
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
<input type="hidden" name="fn" value="memoTo" />
<input type="hidden" name="type" value="sale" />
<input type="hidden" name="memo_id" value="<?php echo $lid; ?>" />

<input type="hidden" name="status" value="on_sale" />

<div class="col-xs-12 col-sm-12 ">
	
		
	<div class="form-group col-sm-1" style="width:150px;">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12" id="entryno" name="entryno" placeholder="Entry" readonly value="<?php echo $entryno; ?>" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Invoice</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="invoiceno" value="<?php echo $invoice?>" readonly name="invoiceno" placeholder="INC201608" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Reference</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-10" id="reference" name="reference" value="<?php echo $data['reference']?>" placeholder="#2016/08/45" type="text">
		</div>
	</div>
	
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Date</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="minvoicedate" value="<?php echo $data['invoicedate']?>" name="invoicedate" placeholder="2016/08/20" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Terms</label>
		<div class="col-sm-7">
			<input onChange="calDueDate()" class="input-sm col-sm-12" id="mterms" value="<?php echo (isset($data['terms']) && $data['terms'] !='')?$data['terms']:0;?>" name="terms" placeholder="Your Terms" type="text">
		</div>
	</div>
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Due Date</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="mduedate" value="<?php echo $data['duedate']?>" name="duedate" placeholder="2016/09/02" type="text">
		</div>
	</div>
	
	<!---- -->
	<div style="clear:both"></div>
	<div class="form-group col-sm-6">
		<label class="col-sm-2 control-label no-padding-right" for="form-field-4">Company</label>
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
	
</div>
<div class="subform invenotry-cgrid sendToOut">
<div class="divTable" >
	
	
	<div class="divTableHeading">
		
		<div class="divTableCell">No</div>		
		<div class="divTableCell">Mfg. code</div>
		<div class="divTableCell">D. No.</div>
		<div class="divTableCell">SKU</div>
		<div class="divTableCell width-50px">Pcs</div>
		<div class="divTableCell width-50px">Carat</div>
		<div class="divTableCell width-70px">Cost</div>		
		<div class="divTableCell width-70px">Price</div>
		<div class="divTableCell width-70px">Amount</div>
		<div class="divTableCell width-50px">LOC </div>		 
		<?php foreach($attribute as $key=>$v): ?>
		<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $v; ?></div>
		<?php endforeach;?>
		<div class="divTableCell ">Remark</div>
		<div class="divTableCell width-50px">Lab</div>
	</div>		
	
	<div class="divTableBody" style="height:180px; overflow-y:auto;">
	<?php 
	
	$i=1;
	$pcs = $carat = $price = $amount = 0.0;
	$products = array();
	
	$products = $_POST['ids'];
	
	
	foreach($products  as $id): 
	
	$value = $imodel->getDetail($id);
	$pcs = $pcs + $value['polish_pcs'];
	$carat = $carat + $value['polish_carat'];
	$price = $price + $value['price'];
	$amount = $amount + $value['amount'];
	?>
	<input type="hidden" name="products[]" value="<?php echo $id ?>" />
	<div class="divTableRow infobox-grey infobox-dark">		
		<div class="divTableCell"><?php echo $i?></div>
		<div class="divTableCell">  <?php echo (isset($value['mfg_code']))?$value['mfg_code']:'&nbsp;'?> </div>
		<div class="divTableCell">  <?php echo (isset($value['diamond_no']))?$value['diamond_no']:'&nbsp;'?> </div>		
		<div class="divTableCell"><?php echo (isset($value['sku']))?$value['sku']:'&nbsp;'?> </div>
		
		<?php if( $value['group_type'] == 'box' || $value['group_type']=="parcel" ) :?>
		<div class="divTableCell width-50px"><input class=" col-sm-12  a-right"  name="record[<?php echo $id ?>][polish_pcs]" type="text" value="<?php echo (isset($value['polish_pcs']))?$value['polish_pcs']:'&nbsp;'?>" ></div>
				<div class="divTableCell width-50px"><input class=" col-sm-12 a-right"  name="record[<?php echo $id ?>][polish_carat]" onChange="calAmount(<?php echo $id ?>)" id="pcarat-<?php echo $id ?>" type="text" value="<?php echo (isset($value['polish_carat']))?$value['polish_carat']:'&nbsp;'?>"></div>
				<div class="divTableCell a-right width-70px">  <?php echo (isset($value['cost']))?$value['cost']:'&nbsp;'?> </div>
				<div class="divTableCell width-70px"><input class=" col-sm-12 a-right"  name="record[<?php echo $id ?>][price]" id="price-<?php echo $id ?>" onBlur="calAmount(<?php echo $id ?>)" value="<?php echo ($value['sell_price'] ==0)?$value['price']:$value['sell_price'] ?>"  type="text"> <input name="record[<?php echo $id ?>][amount]" id="amount1-<?php echo $id ?>" type="hidden" value="<?php echo (isset($value['amount']))?$value['amount']:'&nbsp;'?>"></div>
				<div class="divTableCell width-70px amount1 a-right" id="amount2-<?php echo $id ?>"><?php echo ($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'] ?></div>
		<?php else: ?>
				<div class="divTableCell a-right width-50px pcs">  <?php echo (isset($value['polish_pcs']))?$value['polish_pcs']:'&nbsp;'?> </div>
				<div class="divTableCell a-right width-50px carats" id="carat-<?php echo $value['id'] ?>">  <?php echo (isset($value['polish_carat']))?$value['polish_carat']:'&nbsp;'?>  </div>
				<div class="divTableCell a-right width-70px">  <?php echo (isset($value['cost']))?$value['cost']:'&nbsp;'?> </div>
				<div class="divTableCell a-right width-70px price" style="padding:0px"> <input style="width: 100%; height: 100%; border: 0px none; text-align: right;" type="text" value="<?php echo ($value['sell_price'] ==0)?$value['price']:$value['sell_price'] ?>" onBlur="changeTextPrice(this.value,<?php echo $value['id'] ?>)"></div>
				<div class="divTableCell a-right width-70px amount1" id="amount-<?php echo $value['id'] ?>">  <?php echo ($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'] ?></div>
	
		<?php endif; ?>
		
		<div class="divTableCell width-50px ">  <?php echo (isset($value['location']))?$value['location']:'&nbsp;'?> </div>
		<?php foreach($attribute as $key=>$v): ?>
		<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?> "><?php echo (isset($value[$key]))?$value[$key]:'&nbsp;';?></div>
		<?php endforeach;?>
		<div class="divTableCell">  <?php echo (isset($value['remark']))?$value['remark']:'&nbsp;'?> </div>
		<div class="divTableCell width-50px">  <?php echo (isset($value['lab']))?$value['lab']:'&nbsp;'?> </div>
		
	</div>
	
	<?php $i++;
	endforeach;?>
</div>	
</div>
</div>
<div class="inventory-color">
	<div class="inventory-color-cell" style="width:100%;padding-bottom:10px;border-bottom:1px solid #ccc;margin-bottom:15px">		
		<div class="color-total color-total-count"> <b>Pcs :</b> &nbsp;&nbsp;<span id="total-pcs"><?php echo $pcs ?> </span> &nbsp;&nbsp; |</div>
		<div class="color-total color-total-count"> <b>Carats :</b> &nbsp;&nbsp;<span id="total-carat"><?php echo $carat ?></span> &nbsp;&nbsp; |</div>
		<div class="color-total color-total-count"> <b>Price :</b> &nbsp;&nbsp;<span id="total-price"><?php echo number_format(((float)$amount / (float)$carat),2,",","") ?></span> &nbsp;&nbsp; |</div>
		<div class="color-total color-total-count"> <b>Amount :</b> &nbsp;&nbsp;<span id="total-amount1"><?php echo $amount ?></span> &nbsp;&nbsp; |</div>		
		<textarea class="form-control" id="form-field-8" placeholder="Narretion" style="width: 500px; height:30px;" name="narretion"><?php echo $data['narretion']?></textarea>
	</div>
	
	
		<div class="col-xs-12 col-sm-12 " style="">

			<div class="form-group col-sm-4 center" style="margin-bottom: 0px !important;">

				<label style="padding-top: 0px !important;" class="col-sm-12 control-label center no-padding-right" for="form-field-4">Less %</label>

				<div class="col-sm-12">

					<input class="input-sm col-sm-3 a-right"  id="less_percent" name="less_percent" onKeyUp="calculateLess(this.value)" placeholder="Less %" value="<?php echo ($data['less_percent']!=0)?$data['less_percent']:'' ?>" type="text" style="margin-right:5px">
					<input class="input-sm col-sm-4 a-right"  name="less_amount" id="less_amount" readonly value="<?php echo ($data['less_amount'] !=0 )? $data['less_amount']:''; ?>" type="text" style="margin-right:5px">
					<input class="input-sm col-sm-4 a-right" disabled id="netless" type="text">

				</div>

			</div>

			<div class="form-group col-sm-4 center" style="margin-bottom: 0px !important;">

				<label class="col-sm-12 control-label no-padding-right center" style="padding-top: 0px !important;" for="form-field-4">Other Less % </label>

				<div class="col-sm-12">

					<input class="input-sm col-sm-3 a-right"  id="other_less_percent" name="other_less_percent" onKeyUp="calculateOtherLess(this.value)" placeholder="Enter Less %" value="<?php echo ($data['other_less_percent'] !=0)?$data['other_less_percent']:'' ?>" type="text" style="margin-right:5px">
					<input class="input-sm col-sm-4 a-right"  name="other_less_amount" id="other_less_amount" readonly value="<?php echo ($data['other_less_amount'] !=0 )?$data['other_less_amount']:'' ?>" type="text" style="margin-right:5px">
					<input class="input-sm col-sm-4 a-right" disabled id="other_netless" type="text">


				</div>

			</div>
			

			<div class="form-group col-sm-2 center" style="margin-bottom: 0px !important;">

				<label class="col-sm-12 center control-label no-padding-right" for="form-field-4">Extra Charge</label>

				<div class="col-sm-12">

					<input class="input-sm col-sm-11 a-right" id="Extra" name="charge" onBlur="calculateTotalAmount();" value="<?php echo $data['charge'] ?>" type="text" >

				</div>

			</div>


		</div>
		<div style="clear:both"></div>
		<hr>
		<div class="form-group col-sm-1" style="padding: 0px; margin-bottom: 5px; margin-right: 0px;width:160px; ">								
			<div class="col-sm-12">			
			<input type="checkbox" name="on_payment" id="on_payments" > On Payment	
			</div>
		</div>
		<div class="form-group col-sm-3" style="padding: 0px; margin-bottom: 5px; margin-right: 0px; ">								
			<label class="col-sm-4 control-label no-padding-right a-right" for="form-field-4">Select Book</label>
			<div class="col-sm-8">				
				<select class="col-xs-12" name="book" onChange="changeBook(this.value)">
					<option value="">Book</option>
					<?php foreach($book as $k=>$v): ?>
						<option value="<?php echo $k ?>"><?php echo $v ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group col-sm-2" style="padding: 0px; margin-bottom: 5px; margin-right: 0px; ">
			<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Date</label>
			<div class="col-sm-7">
				<input class="input-sm col-sm-12" id="mdate"  name="bdate" placeholder="2016/08/20" type="text">
			</div>
		</div>
		<div class="form-group col-sm-2" style="padding:0px;margin-bottom: 5px;">
			<label class="col-sm-5 control-label no-padding-right a-right" for="form-field-4">Amount</label>
			<div class="col-sm-7">
				<input value="<?php echo $amount ?>" name="due_amount" id="due_amount" type="hidden">	
				<input class="input-sm col-sm-12 a-right" value="<?php echo $amount ?>" id="paid_amount" name="paid_amount" type="text">	
			</div>
		</div>
		<div class="form-group col-sm-2" style="padding:0px;margin-bottom: 5px;">
			<label class="col-sm-5 control-label no-padding-right a-right" for="form-field-4">Cheque</label>
			<div class="col-sm-7">
				<input class="input-sm col-sm-12 a-right" value="" id="cheque" name="cheque" type="text">	
			</div>
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
		url: '<?php echo $moduleUrl.'outward/outwardController.php'?>', 
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
	url: '<?php echo $moduleUrl.'party/partyForm.php'?>', 
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
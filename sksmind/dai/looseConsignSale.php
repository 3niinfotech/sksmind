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
include_once($daiDir.'jewelry/jobwork/jobworkModel.php');
include_once($daiDir.'jewelry/outward/outwardModel.php');
include_once('inventoryModel.php');
$imodel  = new inventoryModel($cn);
$jmodel  = new jobworkModel($cn);
$model  = new outwardModel($cn);
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
//$attribute = $helper->getAttribute();
//$width50 = $helper->width50(); 							
//$groupUrl = $daiDir.'module/outward/';
$book = $helper->getAllBook(); 
$shipping = $helper->getAllShipping(); 
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$lid=0;
if(isset($_POST['id']))
{
	$lid = $_POST['id'];
}
$data =  $model->getData($lid);		




	$main_stone = (isset($_POST['main']))?$_POST['main']:array(); 
	$side_stone = (isset($_POST['side']))?$_POST['side']:array(); 


//$book = $helper->getAllBook(); 
$shipping = $helper->getAllShipping(); 
$origin = $helper->getAllOrigin(); 
?>

<div class="page-header">							
	<h1 style="float:left">
		Send to Sale						
	</h1>
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn btn-danger" type="button">
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
	<!--<?php if(isset($_POST['id'])): ?>
	
		<?php if($_POST['type'] == 'memo' || $_POST['type'] == 'lab'  ): ?>
			<a href="<?php echo $mainUrl.'pdf/file/memo.php?id='.$_POST['id'] ?>" target="_blank" style="float:right; margin-right: 10px;" class="btn btn-info" type="button" >
				<i class="ace-icon fa fa-print bigger-110"></i>
				Print Memo
			</a>
		<?php elseif($_POST['type'] == 'sale' || $_POST['type'] == 'export' || $_POST['type'] == 'consign' ): ?>
			<a href="<?php echo $mainUrl.'pdf/file/invoice.php?id='.$_POST['id'] ?>" target="_blank" style="float:right; margin-right: 10px;" class="btn btn-info" type="button" >
				<i class="ace-icon fa fa-print bigger-110"></i>
				Print Invoice
			</a>
		<?php endif; ?>
	<?php endif; ?>-->
	
</div>
<?php

//$entryno = $jmodel->getIncrementEntry('outward');


$invoice = $jmodel->getIncrementEntry('memo_invoice');

/*if($_POST['type'] == 'memo'):
	$invoice = $model->getIncrementEntry('memo_invoice');
endif;

if($_POST['type'] == 'lab'):
	$invoice = $model->getIncrementEntry('lab_invoice');
endif;
*/
?>

<form class="form-horizontal" id="memo-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $jewelryUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="memoTo" />
<input type="hidden" name="type" value="sale" />
<input type="hidden" name="memo_id" value="<?php echo $lid; ?>" />
<input type="hidden" name="main_stone" value="<?php echo implode(",",$main_stone)?>">
<input type="hidden" name="side_stone" value="<?php echo implode(",",$side_stone) ?>">
<input type="hidden" name="status" value="on_sale" />



<?php if(isset($_POST['id']))
{	
	//$entryno = $data['entryno'];
	$invoice = $data['invoiceno'];	
?>
<?php } ?>
<div class="col-xs-12 col-sm-12 ">
	
		
	<!--<div class="form-group col-sm-1" style="width:150px;">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12" id="entryno" name="entryno" placeholder="Entry" readonly value="<?php echo $entryno; ?>" type="text">
		</div>
	</div>-->
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Invoice</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="invoiceno" value="<?php echo $invoice?>" readonly name="invoiceno" placeholder="INC201608" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Reference </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-10" id="reference" name="reference" value="<?php echo $data['reference']?>" placeholder="#2016/08/45" type="text">
		</div>
	</div>
	
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Date <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="invoicedate" value="<?php echo $data['invoicedate']?>" name="invoicedate" placeholder="2016/08/20" type="text">
		</div>
	</div>
	<?php if($_POST['type'] != 'memo' && $_POST['type'] != 'lab'): ?>
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Terms</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="terms" value="<?php echo (isset($data['terms']) && $data['terms'] !='')?$data['terms']:0;?>" name="terms" placeholder="Your Terms" type="text">
		</div>
	</div>
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Due Date</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="duedate" value="<?php echo $data['duedate']?>" name="duedate" placeholder="2016/09/02" type="text">
		</div>
	</div>
	<?php endif; ?>
	<!---- -->
	<div style="clear:both"></div>
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
	<?php if($_POST['type'] == 'export'  || $_POST['type'] == 'consign'): ?>
	<div class="form-group col-sm-2" style="padding: 0px; margin-bottom: 5px; margin-right: 0px; ">								
		<div class="col-sm-10">				
			<select class="col-xs-12" name="shipping_name" >
				<option value="">Select Shipping</option>
				<?php foreach($shipping as $k=>$v): ?>
					<option value="<?php echo $k ?>" <?php echo ($v == $data['shipping_name'])?"selected":""; ?>><?php echo $v ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group col-sm-2" style="padding: 0px; margin-bottom: 5px; margin-right: 0px; ">								
			
			<div class="col-sm-10">				
				<select class="col-xs-12" name="origin_of" >
					<option value="">Select Origin</option>
					<?php foreach($origin as $k=>$v): ?>
						<option value="<?php echo $k ?>" <?php echo ($v == $data['origin_of'])?"selected":""; ?>><?php echo $v ?></option>
					<?php endforeach; ?>
				</select>
			</div>
	</div>
	<div class="form-group col-sm-2" style="padding:0px;margin-bottom: 5px;">
		<label class="col-sm-5 control-label no-padding-right a-right" for="form-field-4">Charge</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" name="shipping_charge" value="<?php echo $data['shipping_charge'] ?>" type="text">	
		</div>
	</div>	
	<?php endif; ?>	
	<div style="clear:both"></div>
	
</div>
<div class="subform invenotry-cgrid sendToOut">
<div class="divTable" style="width:1338px;">
	
	
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
	if(!empty($main_stone)):
	foreach($main_stone  as $id): 
	
	$value = $imodel->getSaleDetail($id);
	$pcs = $pcs + $value['pcs'];
	$carat = $carat + $value['carat'];
	$fprice = ($value['sell_price'] ==0)?$value['price']:$value['sell_price'];
	$price = $price + $fprice;
	$famount = ($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'];
	$amount = $amount + $famount;
	?>
	
	<div class="divTableRow infobox-grey infobox-dark">		
		<div class="divTableCell"><?php echo $i?></div>
		<div class="divTableCell"><?php echo "Main"?></div>
		<div class="divTableCell  "><?php echo $value['sku'];?></div>
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
	endif;
	?>
	<?php
	if($side_stone):
	foreach($side_stone  as $id): 
	
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
	$famount = ($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'];
	$amount = $amount + $famount;
	?>
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
	endif;
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
	
		<?php if($_POST['type'] == 'sale' || $_POST['type'] == 'export' || $_POST['type'] == 'consign' ): ?>
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
			<input type="checkbox" name="on_payment" id="on_payment" > On Payment	
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
				<input class="input-sm col-sm-12" id="date"  name="bdate" placeholder="2016/08/20" type="text">
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
		<?php endif; ?>
</div>
<div style="clear:both"></div>

</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">
				jQuery(function($) {
				$( "#invoicedate" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});
				});
				$("#terms").on("change", function()
				{
					   var selectedDate = $("#invoicedate").val();

					  // var finalDate = selectedDate.substring(6,11)+"/"+selectedDate.substring(3,6)+"/"+selectedDate.substring(0,2);
					   var date = new Date(selectedDate);

						days = parseInt($("#terms").val(), 10);
					   
						if(!isNaN(date.getTime())){
							date.setDate(date.getDate() + days);
							if(date.toInputFormat() != "NaN/NaN/NaN")
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

	$("#invoicedate").on("change", function()
	{
		var selectedDate = $("#invoicedate").val();

		$("#date").val(selectedDate);
		
		// var finalDate = selectedDate.substring(6,11)+"/"+selectedDate.substring(3,6)+"/"+selectedDate.substring(0,2);
	    var date = new Date(selectedDate);

		
		days = parseInt($("#terms").val(), 10);
	    if(!isNaN(date.getTime())){
			date.setDate(date.getDate() + days);
			if(date.toInputFormat() != "NaN/NaN/NaN")
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
function saveMemoForm()
{
	jQuery('#btn-save').attr('disabled',true);
	var data =  $("#memo-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $jewelryUrl.'outward/outwardController.php' ?>', 
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
						loadGrid();
						/* $('#main-inventory-grid .divTableBody .divTableRow.active').addClass('infobox-red infobox-dark');
						$('#main-inventory-grid .divTableBody .divTableRow input:checkbox').prop('checked',false);
						$('#main-inventory-grid .divTableBody .divTableRow.active').removeClass('active'); */
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
		url: '<?php echo $jewelryUrl.'inward/inwardController.php'?>', 
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

function calculateRapnetPrice(discount,id,rapnetPrice)
{
	
	var price = parseFloat(rapnetPrice) + ( parseFloat(rapnetPrice * discount) / 100 ) ;	
	jQuery('#price-'+id).val(price);
	changeTextPrice(price,id);	
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
		famount = famount - other_less_amount;
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
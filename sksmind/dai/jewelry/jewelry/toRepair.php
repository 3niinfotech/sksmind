<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
include("jewelryModel.php");
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



$model  = new jewelryModel($cn);	
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);

$jewType = $jhelper->getJewelryType();
$design = $jhelper->getAllDesign();
$gold = $jhelper->getGoldType();
$goldColor = $jhelper->getGoldColor();
$mmaker = $jhelper->getAllMemoMaker();

$groupUrl = $daiDir.'jewelry/outward/';
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$lid=0;
//print_r($_POST);
$data = $model->getJewelryForRepair($_POST['ids']);

?>

<div class="page-header">							
	<h1 style="float:left">
		Jewelry Repairing Job Work						
	</h1>
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
	<?php if(!isset($_POST['id'])): ?>
	<button class="btn btn-info" style="float:right;margin-right: 10px;" id="btn-save" type="button" onClick="saveJobWork()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Save Job Work
	</button>
	
	<button class="btn reset"  style="float:right;margin-right: 10px;" type="reset">
		<i class="ace-icon fa fa-undo bigger-110"></i>
		Reset
	</button>
	<?php endif; ?>

</div>
<?php

$entryno = $model->getIncrementEntry('repair');


/*if($_POST['type'] == 'memo'):
	$invoice = $model->getIncrementEntry('memo_invoice');
endif;

if($_POST['type'] == 'lab'):
	$invoice = $model->getIncrementEntry('lab_invoice');
endif;
*/
?>

<form class="form-horizontal" id="job-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="sendToRepair" />



<div class="col-xs-12 col-sm-12 ">
	
		
	<div class="form-group col-sm-3">	
		<label class="col-sm-6 control-label no-padding-right" for="form-field-4">Job Number </label>
		<div class="col-sm-6">
			<input class="input-sm col-sm-12" id="entryno" name="entryno" placeholder="Entry" readonly value="<?php echo $entryno; ?>" type="text">
		</div>
	</div>
	<div class="form-group col-sm-5">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Company <span class="required">*</span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="ledger" name="party">
				<option value="">Select Company Name</option>
				<?php 
				foreach($party as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php //echo ($key == $data['party'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			<a href="javascript:void(0);" onclick="addParty()" style="margin-top:8px;margin-left:5px;float: left;" > Add Company</a>
		</div>
	</div>
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Date <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="date"  name="date" placeholder="" type="text">
		</div>
	</div>
	<!-- <div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Due Date <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="duedate" name="duedate" placeholder="" type="text">
		</div>
	</div>
	<!---- -->
	
	<div style="clear:both"></div>
	
	<div class="form-group col-sm-3">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">M Maker <span class="required">*</span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="jew_type" name="memo_maker">
				<option value="">Memo Maker</option>
				<?php 
				foreach($mmaker as $key => $value):
				?>						
				<option value="<?php echo $key?>" ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	
	<br>
	
	<div style="clear:both"></div>
	
</div>
<div class="subform invenotry-cgrid sendToOut">
<div class="divTable" >
	
	
	<div class="divTableHeading">
		
		<div class="divTableCell">No</div>		
		<div class="divTableCell">Jew Code</div>
		<div class="divTableCell ">Type</div>
		<div class="divTableCell ">Gold</div>
		<div class="divTableCell ">Carat</div>
		<div class="divTableCell ">Gold Gram</div>
		<div class="divTableCell ">Gross CTS</div>
		<div class="divTableCell ">Final</div>
		<div class="divTableCell ">Side Pcs</div>		
		<div class="divTableCell ">Side Carat</div>		
		<div class="divTableCell ">Side Price</div>		
		<div class="divTableCell ">Comment</div>		
	</div>		
	
	<div class="divTableBody" style="height:100px; overflow-y:auto;">
	<?php 
	
	$i=1;
	$pcs = $carat = $price = $amount = 0.0;
	
	//$mainStone = explode(",",$data['main_stone']);
	//$sideStone = explode(",",$data['side_stone']);
	
	foreach($data as $id=>$value): 
	
	//$value = $imodel->getDetail($id);
	$id = $value['id'];
	/* $pcs = $pcs + $value['pcs'];
	$carat = $carat + $value['carat'];
	$price = $price + $value['price'];
	$amount = $amount + $value['amount']; */
	?>

	<input type='hidden' name='products[]' value='<?php echo $value['id'] ?>'>
	<div class="divTableRow infobox-grey infobox-dark">		
		<div class="divTableCell"><?php echo $i?></div>
		<div class="divTableCell"><?php echo (isset($value['sku']))?$value['sku']:'&nbsp;'?> </div>
		
		<?php 
			//$rcolor = $value['color']; ?>
						
		<?php /*if( !isset($_POST['id'])  && ( $value['group_type'] == 'box' || $value['group_type']=="parcel" ) ) :?>
				<div class="divTableCell "><input class=" col-sm-12  a-right"  name="record[<?php echo $id ?>][pcs]" type="text" value="<?php echo (isset($value['pcs']))?$value['pcs']:'&nbsp;'?>" ></div>
				<div class="divTableCell "><input class=" col-sm-12 a-right"  name="record[<?php echo $id ?>][carat]" onChange="calAmount(<?php echo $id ?>)" id="pcarat-<?php echo $id ?>" type="text" value="<?php echo (isset($value['carat']))?$value['carat']:'&nbsp;'?>"></div>				
				<div class="divTableCell a-right  " style="padding:0px"><?php echo ($value['price'] ==0)?$value['price']:$value['price'] ?></div>
				<div class="divTableCell  amount1 a-right" id="amount2-<?php echo $id ?>"><?php echo ($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'] ?></div>
		<?php else:  */ ?>
				<div class="divTableCell a-right  pcs">  <?php echo (isset($value['jew_type']))?$value['jew_type']:'&nbsp;'?> </div>
				<div class="divTableCell a-right " >  <?php echo (isset($value['gold_color']))?$value['gold_color']:'&nbsp;'?>  </div>
				<div class="divTableCell a-right  carats" id="carat-<?php echo $value['id'] ?>">  <?php echo (isset($value['total_carat']))?$value['total_carat']:'&nbsp;'?>  </div>
				
				<div class="divTableCell a-right  " style="padding:0px"><?php echo $value['gold_gram'] ?></div>
				<div class="divTableCell a-right  " >  <?php echo $value['gross_cts'] ?></div>
				<div class="divTableCell a-right  amount1" id="amount-<?php echo $value['id'] ?>" >  <?php echo $value['final_cost'] ?></div>
				<div class="divTableCell a-right  "  >  <input type='text' class="input-sm col-sm-12" name='product[<?php echo $value['id'] ?>][side_pcs]'></div>
				<div class="divTableCell a-right  "  >  <input type='text' class="input-sm col-sm-12" name='product[<?php echo $value['id'] ?>][side_carat]'></div>
				<div class="divTableCell a-right  "  >  <input type='text' class="input-sm col-sm-12" name='product[<?php echo $value['id'] ?>][side_price]'></div>
				<div class="divTableCell a-right  "  >  <input type='text' class="input-sm col-sm-12" name='product[<?php echo $value['id'] ?>][comment]'></div>
				
				
	
		<?php //endif; ?>
		
		
	</div>
	
	
	<?php $i++;
	endforeach; ?>
	
	
</div>	
</div>
</div>


</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">


function saveJobWork()
{
	//jQuery('#btn-save').attr('disabled',true);
	var data =  $("#job-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $jewelryUrl.'jobwork/jobworkController.php'?>', 
		type: 'POST',
		data: data,		
		success: function(result)
			{		
				if(result != "")
				{
					jQuery('#dialog-box-container').show();
					//$('#dialog-box-container').html('<div class="box-container-memo" >'+result+'</div>');									
					jQuery('#please-wait').hide();
					var obj = jQuery.parseJSON(result);
					alert(obj.message);
					if(obj.status)				
					{
						loadGrid();
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
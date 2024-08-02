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
include_once($daiDir.'jewelry/jobwork/jobworkModel.php');
include_once($daiDir.'jewelry/party/partyModel.php');
include_once('inventoryModel.php');

include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$imodel  = new inventoryModel($cn);
$model  = new jobworkModel($cn);
$groupUrl = $daiDir.'jewelry/jobwork/';
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$design = $jhelper->getAllDesign();
$gold = $jhelper->getGoldType();
$goldColor = $jhelper->getGoldColor();
$mmaker = $jhelper->getAllMemoMaker();
$lid=0;
if(isset($_POST['id']))
{
	$lid = $_POST['id'];
}
$data =  $model->getData($lid,'collet');		
/* echo "<pre>";
print_r($data);
exit; */
?>

<div class="page-header">							
	<h1 style="float:left">
		Collet Return - (<?php echo $data['entryno']?>)
	</h1>
	<button id="close-box" onclick="closeBox1(<?php echo $_POST['pid']; ?>)" style="float:right" class="btn btn-danger" type="button">
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


/*if($_POST['type'] == 'memo'):
	$invoice = $model->getIncrementEntry('memo_invoice');
endif;

if($_POST['type'] == 'lab'):
	$invoice = $model->getIncrementEntry('lab_invoice');
endif;
*/
?>

<form class="form-horizontal" id="memo-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="colletToReturn" />
<input type="hidden" name="memo_id" value="<?php echo $lid; ?>" />
<input type="hidden" name="main_stone" value="<?php echo $data['main_stone']; ?>" />
	<div class="col-sm-12" style="margin-bottom:50px">	
	<div class="form-group col-sm-10" style="margin-right:10px;">
	<label class="col-sm-3 control-label no-padding-right green" for="form-field-4">Company : <?php echo $party[$data['party']]; ?></label>
	<label class="col-sm-3 control-label no-padding-right blue" for="form-field-4">M Maker : <?php echo $mmaker[$data['memo_maker']]; ?></label>
	<label class="col-sm-3 control-label no-padding-right green" for="form-field-4">Date : <?php echo $data['date'] ?></label>
	<div style="clear:both"></div>
	<h3>Main Stone</h3>
	<div class="subform mainstone invenotry-cgrid main-grid">
		<div class="divTable" style="width:2230px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
				</div>					
				<div class="divTableCell">SKU</div>
				<div class="divTableCell">PCS</div>
				<div class="divTableCell">Carat</div>
				<div class="divTableCell">Shape</div>
				<div class="divTableCell">Clarity</div>
				<div class="divTableCell">Color</div>
				<div class="divTableCell">Price</div>
				<div class="divTableCell">Amount</div>
				<div  class="divTableCell">G Color</div>
				<div  class="divTableCell">G Carat</div>
				<div  class="divTableCell">Gross WT</div>
				<div  class="divTableCell">NET WT</div>
				<div  class="divTableCell">PG WT</div>
				<div  class="divTableCell">Rate</div>
				<div  class="divTableCell">Amount</div>
				<div  class="divTableCell">Other Code</div>
				<div  class="divTableCell">Other Amount</div>
				<div  class="divTableCell">Labour Rate</div>
				<div  class="divTableCell">Labour Amount</div>
				<div  class="divTableCell">Total Amount</div>
				
			</div>	
			<div class="divTableBody" >
				<?php 
						$i=1;
					$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
					$class ="";
					 $id = $data['main_stone'];
					$value = $imodel->getDetail($id);
					$cvalue = $model->getDataCollet($id);
					       
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?>
					</div>
						
					<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell a-right"><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell a-right "><?php echo $value['price'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['amount'];?></div>			
					<div class="divTableCell"><?php echo $cvalue['collet_color'];?></div>		
					<div class="divTableCell a-right"><?php echo $cvalue['collet_kt'];?></div>			
					<div class="divTableCell a-right"><?php echo $cvalue['gross_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $cvalue['net_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $cvalue['pg_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $cvalue['collet_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $cvalue['collet_amount'];?></div>
					<div class="divTableCell "><?php echo $cvalue['other_code'];?></div>
					<div class="divTableCell a-right"><?php echo $cvalue['other_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $cvalue['labour_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $cvalue['labour_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $cvalue['total_amount'];?></div>
					
				</div>
				<?php 
			$tpcs = (int)$tpcs +  (int)$value['pcs'];
			$tcarat = (float)$tcarat +  (float)$value['carat'];
			$tcost = (float)$tcost +  (float)$value['cost'];
			$tprice = (float)$tprice +  (float)$value['price'];
			$tamount = (float)$tamount +  (float)$value['amount'];?>
			</div>
		</div>
	</div>
</div>
<div class="form-group col-sm-2" style="margin-right:10px;margin-top:100px;">
	<p><b>Total Carat :</b> <span id="total_carat"><?php echo $tcarat; ?></span> </p>
	<p><b>Total Amount :</b> <span id="total_amount"><?php echo $tamount; ?></span> </p>
</div>
</div>
	
<div style="clear:both"></div>

	<div class="form-group col-sm-3">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Color <span class="required">*</span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="goldcolor" name="gold_color">
				<option value="">Gold Color</option>
				<?php 
				foreach($goldColor as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['gold_color'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Carat <span class="required">*</span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="gold" name="gold" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()">
				<option value="">Gold Carat</option>
				<?php 
				foreach($gold as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['gold'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Gross WT <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="gross_weight" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()" value="<?php echo $data['gross_weight']?>" name="gross_weight" placeholder="" type="text">
		</div>
	</div>
		
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right"  for="form-field-4">NET WT <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="net_weight" value="<?php echo $data['net_weight']?>" name="net_weight" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">PG WT <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="pg_weight" value="<?php echo $data['pg_weight']?>" name="pg_weight" placeholder="" type="text">
		</div>
	</div>
		<div style="clear:both"></div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Rate <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="rate" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()" value="<?php echo $data['rate']?>" name="rate" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Amount <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="amount" readonly value="<?php echo $data['amount']?>" name="amount" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Other Code </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="other_code" value="<?php echo $data['other_code']?>" name="other_code" placeholder="" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Other Amount </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="other_amount" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()" value="<?php echo $data['other_amount']?>" name="other_amount" placeholder="" type="text">
		</div>
	</div>
	<div style="clear:both"></div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Labour Rate</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="labour_rate"  onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()" value="<?php echo $data['labour_rate']?>" name="labour_rate" placeholder="" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Labour Amount</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="labour_amount" readonly value="<?php echo $data['labour_amount']?>" name="labour_amount" placeholder="" type="text">
		</div>
	</div>
		
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Total Amount </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="ftotal_amount"  readonly value="<?php echo $data['total_amount']?>" name="total_amount" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-2 center">
				<label class="col-sm-4 control-label center no-padding-right" for="form-field-4">Date</label>
				<div class="col-sm-8 center">
					<input class="input-sm col-sm-12" id="date" name="return_date" value="<?php echo $data['return_date']?>" type="text" style="margin-right:5px">					
				</div>
	</div>

	<div class="form-group col-sm-3 center">
				<label class="col-sm-5 control-label center no-padding-right" for="form-field-4">Remark</label>
				<div class="col-sm-7 center">
					<input class="input-sm col-sm-12" id="remark" name="remark" value="<?php echo $data['remark']?>" type="text" style="margin-right:5px">					
				</div>
	</div>		
</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">
	
	$( "#date" ).datepicker({
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
	//jQuery('#btn-save').attr('disabled',true);
	var data =  $("#memo-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $jewelryUrl.'jobwork/jobworkController.php'?>', 
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
function calculateTotalAmount()
{
	var gross_weight = parseFloat(jQuery('#gross_weight').val());
	var total_carat = parseFloat(jQuery('#total_carat').html());
	var total_amount = parseFloat(jQuery('#total_amount').html());
	
	var rate = parseFloat(jQuery('#rate').val());
	var other_amount = parseFloat(jQuery('#other_amount').val());
	var labour_rate = parseFloat(jQuery('#labour_rate').val());
	
	var gold = parseInt(jQuery('#gold').val());
	
	if( gold >0)
	{
		if(isNaN(gross_weight))
		{gross_weight = 0;}		
		if(isNaN(total_carat))
		{total_carat = 0;}
		if(isNaN(total_amount))
		{total_amount = 0;}
		if(isNaN(rate))
		{rate = 0;}
		if(isNaN(other_amount))
		{other_amount = 0;}	
		if(isNaN(labour_rate))
		{labour_rate = 0;}
		
		var gold_per = 0;
		
		switch(gold) {
			case 22:
				gold_per = 92;
				break;
			case 18:
				gold_per=76;
				break;
			case 14:
				gold_per=59;
				break;
			case 16:
				gold_per=70;
				break;	
			case 20:
				gold_per=84;
				break;	
		} 

		
		var net_gram = parseFloat(jQuery('#net_weight').val());/* parseFloat(gross_weight - (total_carat/5)); */
		if(!isNaN(net_gram))
		{
			//jQuery('#net_weight').val(Math.abs(net_gram.toFixed(3)));
			
			var pg_weight = parseFloat( (net_gram * gold_per)/ 100 );
			if(!isNaN(pg_weight))
			{
				jQuery('#pg_weight').val(Math.abs(pg_weight.toFixed(3)));
			}	
		}
		var camount = parseFloat(net_gram * rate);
		if(!isNaN(camount))
		{
			jQuery('#amount').val(Math.abs(camount.toFixed(2)));
		}
		else
		{
			camount=0;
		}
		
		var labour_amount = parseFloat(gross_weight * labour_rate);
		if(!isNaN(labour_amount))
		{
			jQuery('#labour_amount').val(Math.abs(labour_amount.toFixed(2)));
		}
		else
		{
			labour_amount=0;
		}
		var famount =  parseFloat(total_amount + labour_amount + other_amount  + camount );
		
		if(!isNaN(famount))
		{	
			jQuery('#ftotal_amount').val(Math.abs(famount.toFixed(2)));
		}	
	}
	else
	{
		alert('Please Select Gold Carat');
	}	
	
	
}

function totalamount(no)
{
	var total =0.0;
	
	var amount = parseFloat(jQuery('#collet_amount-'+no).val());
	//var carat_amount = parseFloat(jQuery('#gold_amount').val());
	var labour_fee = parseFloat(jQuery('#collet_labour-'+no).val());	
	var handling_charge = parseFloat(jQuery('#collet_handling-'+no).val());
	var gst = parseFloat(jQuery('#collet_gst-'+no).val());
	var price = parseFloat(jQuery('#amount-'+no).html());
	var carat = parseFloat(jQuery('#carat-'+no).html());
	var collet_gram = parseFloat(jQuery('#collet_gram-'+no).val());
	
	if(isNaN(gst))
	{
		gst = 0;
	}
	if(isNaN(labour_fee))
	{
		labour_fee = 0;
	}
/* 	if(isNaN(roadiam_cost))
	{
		roadiam_cost = 0;
	} */
	/* if(isNaN(carat_amount))
	{
		carat_amount = 0;
	} */
	if(isNaN(amount))
	{
		amount = 0;
	}
	if(isNaN(handling_charge))
	{
		handling_charge = 0;
	}	
	total = amount + labour_fee+ handling_charge + gst;	
	
	if(!isNaN(total))
	{
		jQuery('#collet_gold_amount-'+no).val(total.toFixed(2));	
		jQuery('#after_collet_amount-'+no).val( (total + price).toFixed(2));	
		
		
	}	
	var temp = collet_gram + (parseFloat(carat/5) );
	jQuery('#after_collet_gram-'+no).val( temp.toFixed(2));	
}

function closePartyPopup()
{
	jQuery('#dialog-box-container1').hide();
}

function calculateGold(no)
{
	var gram = parseFloat(jQuery('#collet_gram-'+no).val());
	var price = parseFloat(jQuery('#collet_rate-'+no).val());
	var total  = parseFloat(gram * price);
	
	if(!isNaN(total))
	{	
		jQuery('#collet_amount-'+no).val(Math.abs(total.toFixed(2)));
	}
	
	//calculateCarat();
	totalamount(no);
}
function calculateCarat()
{
	var carat = parseFloat(jQuery('#total_carat').val());
	var gram = parseFloat(jQuery('#gold_gram').val());
	var total  = parseFloat(gram + (carat/5 ));
	
	if(!isNaN(total))
	{	
		jQuery('#gross_cts').val(Math.abs(total.toFixed(2)));
	}	
}


</script>	
<style>	
.a-right{text-align:right;}
.divTableCell input {border:0 !important;}	
.form-group{margin-bottom:10px !important;}
.main-grid .divTableBody {
	height: 100px !important;
}
</style>
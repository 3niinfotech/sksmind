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
include_once($daiDir.'jewelry/jewelry/jewelryModel.php');
include_once($daiDir.'module/party/partyModel.php');
include_once('inventoryModel.php');

include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$imodel  = new inventoryModel($cn);
$model  = new jewelryModel($cn);

$groupUrl = $daiDir.'jewelry/jewelry/';
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$design = $jhelper->getAllDesign();
$gold = $jhelper->getGoldType();
$goldColor = $jhelper->getGoldColor();
$mmaker = $jhelper->getAllMemoMaker();

$ids=0;
$lid=0;
$id=0;
if(isset($_POST['ids']))
{
	$ids = $_POST['ids'];
}
$record =  $model->getJewelryForRepair($ids);		
/* echo "<pre>";
print_r($data);
exit; */

if(isset($_POST['id']))
{
	$id = $_POST['id'];
}
?>

<div class="page-header">							
	<h1 style="float:left">
		Job Memo Return
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
<input type="hidden" name="fn" value="repairToReturn" />
<input type="hidden" name="memo_id" value="<?php echo $id; ?>" />
<input type="hidden" name="products" value="<?php echo implode(",",$ids); ?>" />

<div class="form-group col-sm-2 center">
	<label class="col-sm-4 control-label center no-padding-right" for="form-field-4">Date</label>
	<div class="col-sm-8 center">
		<input class="input-sm col-sm-12 " id="date" name="date" type="text" style="margin-right:5px">					
	</div>
</div>

<div class="form-group col-sm-3">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">M Maker <span class="required">*</span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" name="memo_maker">
				<option value="">Memo Maker</option>
				<?php 
				foreach($mmaker as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php //echo ($key == $data['memo_maker'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	<div class="col-sm-12">	
	<div class="subform invenotry-cgrid ">
		<div class="divTable" style="width:100%">
			<div class="divTableHeading">
				<div class="divTableCell" style="width:100px !important">SKU</div>				
				<div class="divTableCell" style="width:100px !important">Design</div>				
				<div class="divTableCell " style="width:90px !important">Type</div>				
				<div class="divTableCell " style="width:50px !important">Carat</div>				
				<div class="divTableCell " style="width:50px !important">Gram</div>
				<div class="divTableCell " style="width:50px !important">Gross Cts</div>			
				<div class="divTableCell " style="width:80px !important">New Gram</div>			
				<div class="divTableCell " style="width:80px !important">New Rate</div>			
				<div class="divTableCell " style="width:90px !important">New Amt</div>			
				<div class="divTableCell " style="width:60px !important">S Pcs</div>			
				<div class="divTableCell " style="width:60px !important">S Carat</div>			
				<div class="divTableCell " style="width:80px !important">S Rate</div>			
				<div class="divTableCell " style="width:90px !important">S Amount</div>			
				<div class="divTableCell " style="width:60px !important">Loss G</div>
				<div class="divTableCell " style="width:60px !important">Labour</div>			
				<div class="divTableCell " style="width:130px !important">Comment</div>			
			</div>	
			<div class="outward divTableBody">
				<?php 
				
				$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = 0.0; 
				
				foreach($record as $k => $jData):	
				
			 	$tpp += (float) $jData['gold_gram'];
				$tpc += (float) $jData['total_carat'];
				$ta += (float) $jData['gross_cts'];
			
				$class="";
				$sku = $jData['sku'];
				
				?>
				
				<div class="divTableRow <?php echo $class;?> infobox-grey infobox-dark">					
					<div class="divTableCell" style="width:100px !important">  <?php echo $sku; ?> </div>
					<div class="divTableCell  a-right  amount" style="width:100px !important">  <?php echo $design[$jData['jew_design']]; ?></div>
					<div class="divTableCell  a-right  amount" style="width:90px !important">  <?php echo $jData['jew_type'] ?></div>
					
					<div class="divTableCell a-right" style="width:50px !important">  <?php echo $jData['total_carat']?> </div>
					<div class="divTableCell a-right" style="width:50px !important">  <?php echo $jData['gold_gram']?> </div>					
					<div class="divTableCell a-right" style="width:50px !important">  <?php echo $jData['gross_cts']?> </div>					
					<div class="divTableCell a-right" style="width:80px !important;padding:1px;">  <input class="input-sm col-sm-12 a-right" id="new_gram-<?php echo $k ?>" name="product[<?php echo $k ?>][new_gram]" onKeyUp="calculateGold(<?php echo $k ?>)" type="text" style="margin-right:5px">					 </div>					
					<div class="divTableCell a-right" style="width:80px !important;padding:1px;">  <input class="input-sm col-sm-12 a-right" id="new_rate-<?php echo $k ?>" name="product[<?php echo $k ?>][new_rate]" onKeyUp="calculateGold(<?php echo $k ?>)" type="text" style="margin-right:5px">					 </div>					
					<div class="divTableCell a-right" style="width:90px !important;padding:1px;">  <input class="input-sm col-sm-12 a-right" id="new_amount-<?php echo $k ?>" readonly name="product[<?php echo $k ?>][new_amount]" onKeyUp="calculateGold(<?php echo $k ?>)" type="text" style="margin-right:5px">					 </div>					
					<div class="divTableCell a-right" style="width:60px !important;padding:1px;">  <input class="input-sm col-sm-12 a-right" id="side_pcs-<?php echo $k ?>" name="product[<?php echo $k ?>][side_pcs]" onKeyUp="calculateSide(<?php echo $k ?>)" type="text" style="margin-right:5px">					 </div>					
					<div class="divTableCell a-right" style="width:60px !important;padding:1px;">  <input class="input-sm col-sm-12 a-right" id="side_carat-<?php echo $k ?>" name="product[<?php echo $k ?>][side_carat]" onKeyUp="calculateSide(<?php echo $k ?>)" type="text" style="margin-right:5px">					 </div>					
					<div class="divTableCell a-right" style="width:80px !important;padding:1px;">  <input class="input-sm col-sm-12 a-right" id="side_rate-<?php echo $k ?>" name="product[<?php echo $k ?>][side_rate]" onKeyUp="calculateSide(<?php echo $k ?>)" type="text" style="margin-right:5px">					 </div>					
					<div class="divTableCell a-right" style="width:90px !important;padding:1px;">  <input class="input-sm col-sm-12 a-right" id="side_amount-<?php echo $k ?>" readonly name="product[<?php echo $k ?>][side_amount]" onKeyUp="calculateSide(<?php echo $k ?>)" type="text" style="margin-right:5px">					 </div>					
					<div class="divTableCell a-right" style="width:60px !important;padding:1px;">  <input class="input-sm col-sm-12 a-right" id="labour-<?php echo $k ?>" name="product[<?php echo $k ?>][loss]"  type="text" style="margin-right:5px">					 </div>					
					<div class="divTableCell a-right" style="width:60px !important;padding:1px;">  <input class="input-sm col-sm-12 a-right" id="labour-<?php echo $k ?>" name="product[<?php echo $k ?>][labour]"  type="text" style="margin-right:5px">					 </div>					
					<div class="divTableCell a-right" style="width:130px !important;padding:1px;">  <input class="input-sm col-sm-12" id="labour-<?php echo $k ?>" name="product[<?php echo $k ?>][comment]"  type="text" style="margin-right:5px">					 </div>					
				</div>
				<?php endforeach;?>
				
				<div class="divTableRow">					
					<div class="divTableCell" style="width:100px !important"> <b>Total : <?php echo count($record) ?></b></div>
					<div class="divTableCell" style="width:100px !important"></div>
					
					<div class="divTableCell " style="width:90px !important">  </div>					
					<div class="divTableCell a-right " style="width:50px !important"> <b> <?php echo $tpc?></b>  </div>					
					<div class="divTableCell a-right " style="width:50px !important">  <b><?php echo $tpp?></b></div>
					<div class="divTableCell a-right " style="width:50px !important">  <b><?php echo $ta?></b></div>
					<div class="divTableCell a-right " style="width:80px !important">  <b><?php //echo $ta?></b></div>
					<div class="divTableCell a-right " style="width:80px !important">  <b><?php //echo $ta?></b></div>
					
					<div class="divTableCell a-right " style="width:90px !important">  <b><?php //echo $ta?></b></div>
					<div class="divTableCell a-right " style="width:60px !important">  <b><?php //echo $ta?></b></div>
					<div class="divTableCell a-right " style="width:60px !important">  <b><?php //echo $ta?></b></div>
					<div class="divTableCell a-right " style="width:80px !important">  <b><?php //echo $ta?></b></div>
					<div class="divTableCell a-right " style="width:90px !important">  <b><?php //echo $ta?></b></div>
					<div class="divTableCell a-right " style="width:60px !important">  <b><?php //echo $ta?></b></div>
					<div class="divTableCell a-right " style="width:60px !important">  <b><?php //echo $ta?></b></div>
					<div class="divTableCell a-right " style="width:130px !important">  <b><?php //echo $ta?></b></div>
					
				</div>
			</div>
		</div>
	</div>
	</div>
	
	
</div>
<div style="clear:both"></div>
<br>

			
			
			
			
</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">
	
	$( "#date" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				}).datepicker("setDate", new Date());
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


function totalamount()
{
	var total =0.0;
	var amount = parseFloat(jQuery('#total_amount').val());
	var carat_amount = parseFloat(jQuery('#gold_amount').val());
	var labour_fee = parseFloat(jQuery('#labour_fee').val());
	var roadiam_cost = parseFloat(jQuery('#roadiam_cost').val());
	var handling_charge = parseFloat(jQuery('#handling_charge').val());
	var gst = parseFloat(jQuery('#gst').val());
	
	if(isNaN(gst))
	{
		gst = 0;
	}
	if(isNaN(labour_fee))
	{
		labour_fee = 0;
	}
	if(isNaN(roadiam_cost))
	{
		roadiam_cost = 0;
	}
	if(isNaN(carat_amount))
	{
		carat_amount = 0;
	}
	if(isNaN(amount))
	{
		amount = 0;
	}
	if(isNaN(handling_charge))
	{
		handling_charge = 0;
	}
	
	total = amount + carat_amount + labour_fee+ roadiam_cost + handling_charge;
	
	if(!isNaN(total))
	{
		$('#cost_price').val(total.toFixed(2));		
	}
	if(!isNaN(gst))
	{
		var te = total + gst;
		$('#final_cost').val(te.toFixed(2));		
	}
	
}

function closePartyPopup()
{
	jQuery('#dialog-box-container1').hide();
}

function calculateGold(id)
{
	var gram = parseFloat(jQuery('#new_gram-'+id).val());
	var price = parseFloat(jQuery('#new_rate-'+id).val());
	var total  = parseFloat(gram * price);
	
	if(!isNaN(total))
	{	
		jQuery('#new_amount-'+id).val(total.toFixed(2));
	}
	
	//calculateCarat();
	//totalamount();
}
function calculateSide(id)
{
	var carat = parseFloat(jQuery('#side_carat-'+id).val());
	var gram = parseFloat(jQuery('#side_rate-'+id).val());
	var total  = parseFloat(gram * carat );
	
	if(!isNaN(total))
	{	
		jQuery('#side_amount-'+id).val(Math.abs(total.toFixed(2)));
	}	
}


</script>	
<style>	
.a-right{text-align:right;}
.divTableCell input {border:0 !important;}	
.form-group{margin-bottom:10px !important;}
</style>
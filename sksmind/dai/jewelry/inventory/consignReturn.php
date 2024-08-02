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
include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');


$model  = new jewelryModel($cn);
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$groupUrl = $daiDir.'jewelry/outward/';
$lid=0;
$id=0;
if(isset($_POST['id']))
{
	$id = $_POST['id'];
}	
if(isset($_POST['ids']))
{
	$ids = $_POST['ids'];
}
$record =  $model->getJewelryForRepair($ids);	
$jewType = $jhelper->getJewelryType();
?>

<div class="page-header">							
	<h1 style="float:left">
		Consignment Return
	</h1>
	<button id="close-box" onclick="closeBox1(<?php echo $_POST['pid']; ?>)" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
	
	<button class="btn btn-info" style="float:right;margin-right: 10px;" id="btn-save" type="button" onClick="saveMemoForm()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Save Return
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
<input type="hidden" name="fn" value="consignToReturn" />
<input type="hidden" name="memo_id" value="<?php echo $id; ?>" />
<input type="hidden" name="products" value="<?php echo implode(",",$ids); ?>" />

	<div class="col-sm-12">	
	<div class="subform invenotry-cgrid ">
		<div class="divTable" style="width:100%">
			<div class="divTableHeading">
				<div class="divTableCell" style="width:100px !important">SKU</div>
				<div class="divTableCell">Design</div>
				<div class="divTableCell">Type</div>
				<div class="divTableCell width-50px" >Gold</div>
				<div class="divTableCell" >Gold Color</div>
				<div class="divTableCell" >Gross Weight</div>
				<div class="divTableCell" >Pg Weight</div>
				<div class="divTableCell" >Net Weight</div>			
				<div class="divTableCell" >Total Amount</div>	
				<div class="divTableCell">Sell Price</div>			
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
				
				<div class="divTableRow <?php echo $class;?> ">					
					<div class="divTableCell" style="width:100px !important">  <?php echo $sku; ?> </div>
					<div class="divTableCell"><?php echo $jData['jew_design']?></div>
					<div class="divTableCell"><?php echo $jewType[$jData['jew_type']] ?></div>		
					<div class="divTableCell width-50px"><?php echo $jData['gold'] ?></div>			
					<div class="divTableCell"><?php echo $jData['gold_color'] ?></div>				
					<div class="divTableCell"><?php echo $jData['gross_weight'] ?></div>			
					<div class="divTableCell"><?php echo $jData['pg_weight'] ?></div>				
					<div class="divTableCell"><?php echo $jData['net_weight'] ?></div>				
					<div class="divTableCell"><?php echo $jData['total_amount'] ?></div>			
					<div class="divTableCell"><?php echo $jData['sell_price']?></div>	
				</div>
				<?php endforeach;?>
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
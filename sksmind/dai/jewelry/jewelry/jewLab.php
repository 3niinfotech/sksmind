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

$jewType = $jhelper->getJewelryType();
$gold = $jhelper->getGoldType();
$goldColor = $jhelper->getGoldColor();
$mmaker = $jhelper->getAllMemoMaker();

$groupUrl = $daiDir.'jewelry/outward/';
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();

$ids = (isset($_POST['ids']))?$_POST['ids']:array(); 

?>

<div class="page-header">							
	<h1 style="float:left">
		Send to Lab						
	</h1>
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
	<?php //if(!isset($_POST['id'])): ?>
	<button class="btn btn-info" style="float:right;margin-right: 10px;" id="btn-save" type="button" onClick="saveJobWork()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Save Job Work
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
endif;*/
?>

<form class="form-horizontal" id="job-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'jewelry/jewelryController.php'?>">
<input type="hidden" name="fn" value="sendToLab" />
<?php if(isset($_POST['id']))
{	
	$entryno = $data['entryno'];
	$invoice = $data['invoiceno'];	
?>
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
<?php } ?>




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
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Reference </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-10" id="reference" name="reference"  placeholder="#2016/08/45" type="text">
		</div>
	</div>
	
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Date <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="date" name="date" placeholder="" type="text">
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
				<option value="<?php echo $key?>"><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>	
	<br>
	
	<div style="clear:both"></div>
	
</div>

<div class="col-xs-12 col-sm-12 jewelry-job">
<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Jewelry</h3>
	<div class="subform mainstone invenotry-cgrid main-grid">
		<div class="divTable" style="width:1750px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>					
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
			<div class="divTableCell" >Final Amount</div>
				
			</div>	
			<div class="divTableBody sdivtable" >
				<?php 
					$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
					$i = 0 ;
					foreach($ids as $id):
					$i++;
					$class = $sku = "";
					$value = $model->getData($id);
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>" id="rowid-<?php echo $i?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?><input type="hidden" id="products-<?php echo $i?>" name="products[]" value="<?php echo $id ?>" />
					</div>
						
					<div class="divTableCell"><?php echo $value['sku'] ?></div>				
					<div class="divTableCell"><?php echo $value['jew_design'] ?></div>			
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
					<div class="divTableCell"><?php echo $value['selling_price'] ?></div>				
					
				</div>
				<?php 
			/* $tpcs = (int)$tpcs +  (int)$value['pcs'];
			$tcarat = (float)$tcarat +  (float)$value['carat'];
			$tcost = (float)$tcost +  (float)$value['cost'];
			$tprice = (float)$tprice +  (float)$value['price'];
			$tamount = (float)$tamount +  (float)$value['amount']; */
			endforeach; ?>	
			</div>
		</div>
	</div>
</div>
<!--<div class="form-group col-sm-2" style="margin-right:10px;margin-top:100px;">
	<p><b>Total Carat :</b> <span id="total_carat"><?php echo $tcarat; ?></span> </p>
	<p><b>Total Amount :</b> <span id="total_amount"><?php echo $tamount; ?></span> </p>
</div>-->
</div>
</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">
$( "#date, #duedate" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				}).datepicker("setDate", new Date());;

function saveJobWork()
{
	jQuery('#btn-save').attr('disabled',true);
	var data =  $("#job-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $jewelryUrl.'jewelry/jewelryController.php'?>', 
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

</script>	
<style>	
.divTableCell input {border:0 !important;}	
.form-group{margin-bottom:10px !important;}
.jewelry-job .main-grid .divTableBody {
	height:250px !important;
}
#addNewRow {
	position: absolute;
	right: 347px;
	top: 17px;
	border: 1px solid;
	border-radius: 100%;
	padding: 3px 7px;
}
</style>
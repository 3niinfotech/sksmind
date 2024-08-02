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

include_once('inventoryModel.php');
$imodel  = new inventoryModel($cn);

$model  = new jobworkModel($cn);
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
//$entryno = $model->getOptionListEntryno('collet');
$lid=0;
if(isset($_POST['id']))
{
	$lid = $_POST['id'];
}
$data =  $model->getDataLab($lid);		


if(isset($_POST['id']))
{
	//$collet_stone = explode(",",$data['collet_stone']);
	$main_stone = explode(",",$data['products_receive']);
	//$side_stone = explode(",",$data['side_stone']);
}
else
{
	//$collet_stone = (isset($_POST['collet']))?$_POST['collet']:array(); 
	$main_stone = (isset($_POST['main']))?$_POST['main']:array(); 
	//$side_stone = (isset($_POST['side']))?$_POST['side']:array(); 
}


?>

<div class="page-header">							
	<h1 style="float:left">
		Send to Lab						
	</h1>
	<button id="close-box" onclick="closeBox(),closeBox1()" style="float:right" class="btn btn-danger" type="button">
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
	<?php //endif; ?>
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

$entryno = $model->getIncrementEntry('outward');


$invoice = $model->getIncrementEntry('invoice');


/*if($_POST['type'] == 'memo'):
	$invoice = $model->getIncrementEntry('memo_invoice');
endif;

if($_POST['type'] == 'lab'):
	$invoice = $model->getIncrementEntry('lab_invoice');
endif;*/
?>

<form class="form-horizontal" id="job-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
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
			<input class="input-sm col-sm-10" id="reference" name="reference" value="<?php echo $data['reference']?>" placeholder="#2016/08/45" type="text">
		</div>
	</div>
	
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Date <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="date" value="<?php echo $data['date']?>" name="date" placeholder="" type="text">
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
				<option value="<?php echo $key?>" <?php echo ($key == $data['party'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	
	<!--<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> D.Date <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="duedate" value="<?php echo $data['duedate']?>" name="duedate" placeholder="" type="text">
		</div>
	</div>-->
	<!---- -->
	
	<br>
	
	<div style="clear:both"></div>
	
</div>

<div class="col-xs-12 col-sm-12 jewelry-job">
<div class="form-group col-sm-10" style="margin-right:10px;">
	<h3>Main Stone</h3>
	<div class="subform mainstone invenotry-cgrid main-grid">
		<div class="divTable" style="width:1000px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>					
				<div onClick="sortForFilter('sku')" class="divTableCell">SKU</div>
				<div onClick="sortForFilter('pcs')" class="divTableCell">PCS</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Carat</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Shape</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Clarity</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Color</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Price</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Amount</div>
				
			</div>	
			<div class="divTableBody sdivtable" >
				<?php 
					$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
					$i = 0 ;
					foreach($main_stone as $id):
					$i++;
					$class = $sku = "";
					$value = $model->getProductDetail($id);
					 $sku = $value['sku'];
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>" id="rowid-<?php echo $i?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?><input type="hidden" id="products-<?php echo $i?>" name="products[]" value="<?php echo $id ?>" />
					</div>
						
					<div class="divTableCell  "><?php echo $sku;?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell  "><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['price'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['amount'];?></div>				
					
				</div>
				<?php 
			$tpcs = (int)$tpcs +  (int)$value['pcs'];
			$tcarat = (float)$tcarat +  (float)$value['carat'];
			$tcost = (float)$tcost +  (float)$value['cost'];
			$tprice = (float)$tprice +  (float)$value['price'];
			$tamount = (float)$tamount +  (float)$value['amount'];
			endforeach; ?>	
			</div>
		</div>
	</div>
</div>
<div class="form-group col-sm-2" style="margin-right:10px;margin-top:100px;">
	<p><b>Total Carat :</b> <span id="total_carat"><?php echo $tcarat; ?></span> </p>
	<p><b>Total Amount :</b> <span id="total_amount"><?php echo $tamount; ?></span> </p>
</div>
</div>
</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">
$( "#date, #duedate" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});

function saveJobWork()
{
	jQuery('#btn-save').attr('disabled',true);
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

</script>	
<style>	
.divTableCell input {border:0 !important;}	
.form-group{margin-bottom:10px !important;}
.jewelry-job .main-grid .divTableBody {
	height: 130px !important;
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
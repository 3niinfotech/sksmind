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
$entryno = $model->getOptionListEntryno('jewelry');
$groupUrl = $daiDir.'jewelry/outward/';
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$lid=0;
$firm = $imodel->getAllMainFirm();
$mfirm = $imodel->getMainFirm();
	$collet_stone = (isset($_POST['collet']))?$_POST['collet']:array(); 
	$main_stone = (isset($_POST['main']))?$_POST['main']:array(); 
	$side_stone = (isset($_POST['side']))?$_POST['side']:array(); 

$tccarat = $tmcarat = $tscarat = $tsamount = $tamount =0.00;
?>

<div class="page-header">							
	<h1 style="float:left">
		Send to Transfer						
	</h1>
	<button id="close-box" onclick="closeBox(),closeBox1()" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
	<button class="btn btn-info" style="float:right;margin-right: 10px;" id="btn-save" type="button" onClick="saveJobWork()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Transfer
	</button>
	
	<button class="btn reset"  style="float:right;margin-right: 10px;" type="reset">
		<i class="ace-icon fa fa-undo bigger-110"></i>
		Reset
	</button>
	
</div>

<form class="form-horizontal" id="job-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="stockTransfer" />
<input type="hidden" name="fromfirm" value="<?php echo $mfirm ?>" />
<input type="hidden" name="main_stone" value="<?php echo implode(",",$main_stone)?>">
<input type="hidden" name="side_stone" value="<?php echo implode(",",$side_stone) ?>">

<div class="col-xs-12 col-sm-12 ">
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?>Date<span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="date" name="date" placeholder="" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?>Description</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="description" name="description" placeholder="Description" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">	
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">To Firm<span class="required">*</span></label>
		<div class="col-sm-8">
			<select class="col-xs-12" id="ledger" name="tofirm">
				<option value="">Select firm</option>					
				<?php foreach($firm as $key => $value):?>						
				<option value="<?php echo $key?>"><?php echo $value?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div style="clear:both"></div>
	
</div>

<div class="col-xs-12 col-sm-12 jewelry-job">
<?php if(!empty($main_stone)): ?>
<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Main Stone</h3>
	<div class="subform mainstone invenotry-cgrid main-grid">
		<div class="divTable" style="width: 2885px;" >
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
				<div onClick="sortForFilter('amount')" class="divTableCell">Lab</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Code</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Color</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Clarity</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Amount</div>
			
				
			</div>	
			<div class="divTableBody" >
				<?php 
					$tpcs = $tmcarat = $tcost = $tprice = $tamount = 0.00;
					$i = 0 ;
					foreach($main_stone as $id):
					$i++;
					$class ="";
					$mvalue = $imodel->getDetail($id);
					?>
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?>
					</div>
						
					<div class="divTableCell  "><?php echo $mvalue['msku'];?></div>
					<div class="divTableCell  a-right"><?php echo $mvalue['pcs'];?></div>
					<div class="divTableCell  a-right" id="total_carat-<?php echo $i ?>"><?php echo $mvalue['carat'];?></div>
					<div class="divTableCell  "><?php echo $mvalue['shape'];?></div>				
					<div class="divTableCell  "><?php echo $mvalue['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $mvalue['color'];?></div>				
					<div class="divTableCell  a-right"><?php echo $mvalue['price'];?></div>			<div class="divTableCell  a-right" id="total_amount-<?php echo $i ?>"><?php echo $mvalue['amount'];?></div>
					<div class="divTableCell  "><?php echo $mvalue['lab'];?></div>
					<div class="divTableCell  "><?php echo $mvalue['igi_code'];?></div>			
					<div class="divTableCell  "><?php echo $mvalue['igi_color'];?></div>			
					<div class="divTableCell a-right "><?php echo $mvalue['igi_clarity'];?></div>	
					<div class="divTableCell a-right "><?php echo $mvalue['igi_amount'];?></div>					
				</div>
				<?php 
			$tpcs = (int)$tpcs +  (int)$mvalue['pcs'];
			$tmcarat = (float)$tmcarat +  (float)$mvalue['carat'];
			$tcost = (float)$tcost +  (float)$mvalue['cost'];
			$tprice = (float)$tprice +  (float)$mvalue['price'];
			$tamount = (float)$tamount +  (float)$mvalue['amount'];
			endforeach; ?>
			
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php if($side_stone):?>
<div class="form-group col-sm-12">
	<h3>Side Stone</h3>
	<div class="subform sidestone invenotry-cgrid main-grid">
		<div class="divTable " style="width:1000px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					
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
			<div class="divTableBody" >
				
				
				<?php 
					$tscarat = 0.00;
					$i= 0;
					foreach($side_stone as $id):
					$i++;
					$class ="";
					$value = $imodel->getDetail($id,'loose');
					
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?>
					</div>
						
					<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
						<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell  "><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['price'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['amount'];?></div>				
					
				</div>
				<?php 
			
			$tscarat = (float)$tscarat +  (float)$value['carat'];
			$tsamount =  (float)$tsamount +  (float)$value['amount'];
			
			endforeach; ?>	
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
</div>
<p><b>Main Stone Carat : </b> <span id="tmcts"><?php echo $tmcarat; ?></span> &nbsp;&nbsp; |  &nbsp;&nbsp; <b>Main Stone Amount : </b> <span id="total_amount"><?php echo $tamount; ?> &nbsp;&nbsp; |  &nbsp;&nbsp; <b>Side Stone Carat : </b> <span id="tscts"><?php echo $tscarat; ?></span> &nbsp;&nbsp; |  &nbsp;&nbsp; <b>Side Stone Amount : </b> <span id="total_amount"><?php echo $tsamount; ?></span> </p>
	
	
</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">


function saveJobWork()
{
	jQuery('#btn-save').attr('disabled',true);
	var data =  $("#job-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $jewelryUrl.'inventory/inventoryController.php'?>', 
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
.box-container-memo {
	height: 1100px;
}
.jewelry-job .main-grid .divTableBody {
	height: 60px !important;
}
#edit-box-container .box-container {
	min-height: 1020px !important;
}
</style>
<?php
session_start();

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 

include_once('inventoryModel.php');
include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');

$model  = new inventoryModel($cn);
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$post = "";
if(isset($_POST))
{
	$post = $_POST;
}

$right = $helper->right();
$CalClass = $jhelper->InventoryCalClass();
$attribute = $jhelper->getColletInventoryAttribute();		
$inventory = $model->getMyColletInventory($post);
?>
<form id="grid-form" class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'/jewelry/inventory/inventoryController.php'; ?>">
<input type="hidden" name="fn" value="cexportToExcel">
<!--<input type="hidden" name="sort" value="" id="sortFilter">
<input type="hidden" name="sorttype" value="" id="sortType">-->
<input type="hidden" name="exportProducts" id="exportProducts" value="">

<div class="inventory-container" style="padding:0px 0px;">
	<div class="inventory-color">
		<div class="inventory-color-cell">		
			<div class="color-total" style="width:100%"> <b>Total :</b> &nbsp;&nbsp;<?php echo count($inventory) ?> &nbsp;&nbsp; </div>
		</div>
		
		<button class="btn btn-success" type="button" style="float: right; margin: 0px;" onclick="location.reload();">
			<i class="ace-icon fa fa-refresh bigger-110"></i> Reload
		</button>	
		<button class="btn btn-info" type="button" onclick="exportToCsv()" style="float: right; margin: 0px;margin-right:10px;">
			<i class="ace-icon fa fa-download bigger-110"></i>
			Export
		</button>
		<div class="inventory-color-cell" style="float:right">				
			<label class="f-ch-input pos-rel">
				<input class="ace bgreen" type="checkbox" name="stype[]" onclick="submitFilter()" value="" />
				<span class="lbl"></span>
			</label> 							
			<div class="color-total"> NON-IGI </div>		
		</div>

		<div class="inventory-color-cell" style="float:right">				
			<label class="f-ch-input pos-rel">
				<input class="ace bblue" type="checkbox" onclick="submitFilter()" name="stype[]" value="IGI" />
				<span class="lbl"></span>
			</label> 							
			<div class="color-total"> IGI  </div>		
		</div>
	
	</div>
</div>
</form>
<div class="subform invenotry-cgrid main-grid">
	<div class="divTable " style="width:2800px;" >
		<div class="divTableHeading" >
			
			<div class="divTableCell" style="width:100px !important">
				<label class="pos-rel">
					<input class="ace" onclick="allCheck(this)" type="checkbox">
					<span class="lbl"></span>
				</label>
			</div>
			
			<?php foreach($attribute as $key=>$v): ?>				
				<div onClick="sortForFilter('<?php echo $key ?>')" class="divTableCell"><?php echo $v; ?></div>
			<?php endforeach;?>
		</div>	
		<div class="divTableBody" >
			
			
			<?php 
		
				$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
				foreach($inventory as $value):
				$class ="";
				if($value['outward'] == "lab")
				{
					$class ="infobox-green infobox-dark";
				}
				if($value['outward'] =="memo" || $value['outward'] =="consign" )
				{
					$class ="infobox-red infobox-dark";
				}
				if($value['lab'] == "IGI")
				{
					$class ="infobox-blue infobox-dark";
				}
				
			?>
		
			
			
			<div class="divTableRow <?php echo $class;?>">					
				<div class="divTableCell " style="width:100px !important">
				<label class="pos-rel">
					<input type="checkbox" name="mainstone" value="<?php echo $value['product_id'];?>" class="ace mainstone" onClick="mainCheck(this)" />
					<span class="lbl"></span>
				</label>
				</div>
				
				<?php foreach($attribute as $key=>$v): ?>	
					<div class="divTableCell  $CalClass <?php if(array_key_exists($key,$CalClass))echo $CalClass[$key]; ?> <?php if(in_array($key,$right))echo 'a-right'; ?> "><?php echo (isset($value[$key]))?$value[$key]:'&nbsp;';?></div>
				<?php endforeach;?>
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
<style>

.box-container-memo {
    width: 1250px !important;
}
.col-sm-4{ padding-left: 5px !important;}
</style>
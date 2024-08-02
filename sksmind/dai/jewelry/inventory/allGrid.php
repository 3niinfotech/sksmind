<?php
session_start();

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 


$post = $_POST;
$form_type = '';
if(isset($_GET['form_type']))
	$form_type=$_GET['form_type'];
	
$sku='';
if(isset($post['sku']))
	$sku=$post['sku'];

$cfrom='';
if(isset($post['cfrom']))
	$cfrom=$post['cfrom'];

$diamond = '';
if(isset($post['diamond']))
	$diamond=$post['diamond'];
	
$cto='';
if(isset($post['cto']))
	$cto=$post['cto'];

$groupType=array();
if(isset($post['type']))
	$groupType=$post['type'];

$memoType=array();
if(isset($post['memo']))
	$memoType=$post['memo'];

$shape=array();
if(isset($post['shape']))
	$shape=$post['shape'];	
	
$color=array();
if(isset($post['color']))
	$color=$post['color'];		
	
$intensity = array();
if(isset($post['intensity']))
	$intensity=$post['intensity'];

$overtone = array();
if(isset($post['overtone']))
	$overtone=$post['overtone'];	
	
$f_intensity = array();
if(isset($post['f_intensity']))
	$f_intensity=$post['f_intensity'];	
	
$clarity = array();
if(isset($post['clarity']))
	$clarity=$post['clarity'];	

$polish = array();
if(isset($post['polish']))
	$polish=$post['polish'];
	
$package = array();
if(isset($post['package']))
	$package=$post['package'];

$location = array();
if(isset($post['location']))
	$location=$post['location'];

$symmentry = array();
if(isset($post['symmentry']))
	$symmentry=$post['symmentry'];
	
$cut = array();
if(isset($post['cut']))
	$cut=$post['cut'];	
	
include_once('inventoryModel.php');
include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');

$model  = new inventoryModel($cn);
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);

$right = $helper->right();
$CalClass = $helper->InventoryCalClass();
$attribute = $jhelper->getInventoryAttribute();							
$groupUrl = $daiDir.'jewelry/inventory/';														


$main = $model->getMyInventory($post,$form_type);
$collet = $model->getMyColletInventory($post,$form_type);
$side = $model->getMyLooseInventory($post,$form_type);

?>
<form id="filter-form" class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'jewelry/inventory/inventoryController.php'; ?>">
<input type="hidden" name="fn" value="exportToExcel">
<input type="hidden" name="sort" value="" id="sortFilter">
<input type="hidden" name="sorttype" value="" id="sortType">


<input class="input-sm col-sm-3 packet" id="packet" value="<?php echo $sku ?>" name="sku" placeholder="Stone Id Or SKU Or Report"  style="text-transform:uppercase" type="text">

<div class="inventory-container" style="padding:0px 0px;">
	<div class="inventory-color">
		<button class="btn btn-success" type="button" style="float: right; margin: 0px;" onclick="location.reload();">
			<i class="ace-icon fa fa-refresh bigger-110"></i> Reload
		</button>
		
	
		<button class="btn btn-info" type="button" onclick="exportToCsv()" style="float: right; margin: 0px;margin-right:10px;">
			<i class="ace-icon fa fa-download bigger-110"></i>
			Export
		</button>
		
		<button class="btn btn-success" type="button" onclick="tojobwork('jewelry')" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-briefcase bigger-110"></i>
			To Job Jewelry
		</button>
		<button class="btn btn-success" type="button" onclick="tojobwork('collet')" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-briefcase bigger-110"></i>
			To Job Collet
		</button>
		<button class="btn btn-success" type="button" onclick="tojobwork('lab')" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-briefcase bigger-110"></i>
			Lab
		</button>
		<button class="btn btn-success" type="button" onclick="tojobwork('sale')" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-briefcase bigger-110"></i>
			Sale
		</button>
		<button class="btn btn-success" type="button" onclick="tojobwork('consign')" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-briefcase bigger-110"></i>
			Consign
		</button>
		<button class="btn btn-success" type="button" onclick="tojobwork('transfer')" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-briefcase bigger-110"></i>
			Transfer
		</button>
	</div>
</div>
</form>


<div class="form-group col-sm-4">
	<h3>Main Stone</h3>
	<div class="subform mainstone invenotry-cgrid main-grid">
		<div class="divTable" style="width:1480px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>					
				<div onClick="sortForFilter('sku')" class="divTableCell">SKU</div>
				<div onClick="sortForFilter('pcs')" class="divTableCell">PCS</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Carat</div>
				<div onClick="sortForFilter('price')" class="divTableCell">Price</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">Amount</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">Height</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">Width</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">Length</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">Lab</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Code</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Color</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Clarity</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Amount</div>
				
				
			</div>	
			<div class="divTableBody" >
				<?php 
					$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
					foreach($main as $value):
					
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
					
					$sku = $value['sku'];
					$rcolor = $value['color'];
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
					<label class="pos-rel">
						<input type="checkbox" name="mainstone" value="<?php echo $value['id'];?>" class="ace mainstone" onClick="mainCheck(this)" />
						<span class="lbl"></span>
					</label>
					</div>
						
					<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>			
					<div class="divTableCell  a-right"><?php echo $value['price'];?></div>			
					<div class="divTableCell  a-right"><?php echo $value['amount'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['height'];?></div>	
					<div class="divTableCell  a-right"><?php echo $value['width'];?></div>	
					<div class="divTableCell  a-right"><?php echo $value['length'];?></div>	
					<div class="divTableCell  "><?php echo $value['lab'];?></div>
					<div class="divTableCell  "><?php echo $value['igi_code'];?></div>			
					<div class="divTableCell  "><?php echo $value['igi_color'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['igi_clarity'];?></div>		
					<div class="divTableCell a-right "><?php echo $value['igi_amount'];?></div>						
					
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

<div class="form-group col-sm-4">
	<h3>Collet</h3>
	<div class="subform colletstone invenotry-cgrid main-grid">
		<div class="divTable " style="width:1250px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>					
				<div onClick="sortForFilter('sku')" class="divTableCell">SKU</div>
				<div onClick="sortForFilter('pcs')" class="divTableCell">PCS</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Carat</div>
				<div onClick="sortForFilter('price')" class="divTableCell">Price</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">Amount</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">Lab</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Code</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Color</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Clarity</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Amount</div>
			</div>	
			<div class="divTableBody" >
				<?php 
					$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
					foreach($collet as $value):
					
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
					
					$sku = $value['sku'];
					$rcolor = $value['color'];
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
					<label class="pos-rel">
						<input type="checkbox" name="colletstone" value="<?php echo $value['id'];?>" class="ace mainstone" onClick="mainCheck(this)" />
						<span class="lbl"></span>
					</label>
					</div>
						
					<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['price'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['amount'];?></div>	
					<div class="divTableCell  "><?php echo $value['lab'];?></div>
					<div class="divTableCell  "><?php echo $value['igi_code'];?></div>			
					<div class="divTableCell  "><?php echo $value['igi_color'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['igi_clarity'];?></div>		
					<div class="divTableCell a-right "><?php echo $value['igi_amount'];?></div>			
					
				</div>
				<?php 
				$tpcs = (int)$tpcs + (int)$value['pcs'];
				$tcarat = (float)$tcarat + (float)$value['carat'];
				$tcost = (float)$tcost + (float)$value['cost'];
				$tprice = (float)$tprice + (float)$value['price'];
				$tamount = (float)$tamount + (float)$value['amount'];
			endforeach; ?>	
			</div>
		</div>
	</div>
</div>

<div class="form-group col-sm-4">
	<h3>Side Stone</h3>
	<div class="subform sidestone invenotry-cgrid main-grid">
		<div class="divTable " style="width:700px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>					
				<div onClick="sortForFilter('sku')" class="divTableCell">SKU</div>
				<div onClick="sortForFilter('pcs')" class="divTableCell">PCS</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Carat</div>
				<div onClick="sortForFilter('price')" class="divTableCell">Price</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">Amount</div>
				
			</div>	
			<div class="divTableBody" >
				
				
				<?php 
					$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
					foreach($side as $value):
					
					$class ="";
					if($value['outward'] == "lab")
					{
						$class ="infobox-green infobox-dark";
					}
					
					if($value['outward'] =="memo" || $value['outward'] =="consign" )
					{
						$class ="infobox-red infobox-dark";
					}
					
					if($value['outward_parent'] == 0)
					{
						$sku = $value['sku'];
					}
					else
					{
						$parentData = $jhelper->getSideProductDetail($value['outward_parent']);					
						$sku = $parentData['sku'];
					}
					$rcolor = $value['color'];
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
					<label class="pos-rel">
						<input type="checkbox" name="sidestone" value="<?php echo $value['id'];?>" class="ace mainstone" onClick="mainCheck(this)" />
						<span class="lbl"></span>
					</label>
					</div>
						
					<div class="divTableCell  "><?php echo $sku;?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
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



<form id="grid-form" class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'/module/inventory/inventoryController.php'; ?>">
	<input type="hidden" name="fn" value="exportToExcel">
	<input type="hidden" name="exportProducts" id="exportProducts" value="">
</form>	



<div class="dialog-box-container" id="dialog-box-container" style="display:none;" >
	<div class="box-container" style="width:1250px" >
	</div>
</div>	

<script>
			  
	/* jQuery('.subform.invenotry-cgrid').scroll(function(event) {
		//jQuery(".fixsku").css("margin-left", jQuery(document).scrollLeft());
		//console.log(jQuery('.subform.invenotry-cgrid').scrollLeft());
		var tempscroll1 =jQuery('.subform.invenotry-cgrid').scrollLeft()+15+'px';
		var tempscroll =jQuery('.subform.invenotry-cgrid').scrollLeft()+-15+'px';
		if(jQuery('.subform.invenotry-cgrid').scrollLeft() > 100)
		{
			jQuery(".divTableCell:first-child").attr('style','position: absolute; left:'+tempscroll+'; background:#094C72; color:#fff!important');
			jQuery(".fixsku").attr('style','position: absolute; left:'+tempscroll1+'; background:#094C72');
			jQuery(".fixsku a").attr('style','color:#fff!important');
			jQuery(".invenotry-cgrid .divTableHeading .divTableCell:nth-child(3)").attr('style','position: absolute; left:'+tempscroll1+'; background:#094C72');
			
			jQuery(".main-grid .divTable").attr('style','width:2440px;');
			
		}	
		else
		{
			jQuery(".fixsku").attr('style','');
			jQuery(".fixsku a").attr('style','');
			jQuery(".divTableCell:first-child").attr('style','');
			jQuery(".invenotry-cgrid .divTableHeading .divTableCell:nth-child(3)").attr('style','');
			jQuery(".main-grid .divTable").attr('style','width:2600px;');
		}	
		 */
		
	/*	//console.log(jQuery('.subform.invenotry-cgrid').scrollTop());
		var toptempscroll =jQuery('.subform.invenotry-cgrid').scrollTop()+'px';
		if(jQuery('.subform.invenotry-cgrid').scrollTop() > 5)
		{
			jQuery(".divTableHeading").attr('style','z-index:9999;width:2520px; position: absolute; top:0px'+toptempscroll);
		}	
		else
		{
			jQuery(".divTableHeading").attr('style','');
		}
	}); */
</script>
<style>

.box-container-memo {
    width: 1250px !important;
}
.col-sm-4{padding-left: 5px !important;}
</style>
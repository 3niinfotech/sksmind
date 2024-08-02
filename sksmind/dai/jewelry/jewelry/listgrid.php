<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif;
	
include_once('jewelryModel.php');
include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');

$model  = new jewelryModel($cn);
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);

$width50 = $helper->width50(); 
$width70 = $helper->width70(); 
$right = $helper->right();
$CalClass = $helper->InventoryCalClass();
$attribute = $helper->getInventoryAttribute();							
$groupUrl = $daiDir.'module/inventory/';														
$design = $jhelper->getAllDesign();
$jewType = $jhelper->getJewelryType();

$post = $_POST;
$sku='';
if(isset($post['sku']))
	$sku=$post['sku'];
$stype="0";
if(isset($post['stype']))
	$stype=$post['stype'];
$memo="";
if(isset($post['memo']))
	$memo=$post['memo'];

$inventory = $model->getAllData($post);


?>
<form id="filter-form" class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'/jewelry/jewelry/jewelryController.php'; ?>">
<input type="hidden" name="fn" value="exportToExcel">
<input class="input-sm col-sm-3 packet" id="packet" value="<?php echo $sku ?>" name="sku" placeholder="Stone Id Or SKU Or Report"  style="text-transform:uppercase" type="text">

<div class="inventory-container" style="padding:0px 0px;">
	<div class="inventory-color">
		<div class="inventory-color-cell">		
			<div class="color-total" style="width:100%"> <b>Total :</b> &nbsp;&nbsp;<?php echo count($inventory) ?> &nbsp;&nbsp;</div>
		</div>
		
		
		<button class="btn btn-success" type="button" style="float: right; margin: 0px;" onclick="location.reload();">
			<i class="ace-icon fa fa-refresh bigger-110"></i> Realod
		</button>
		
			
		<!--<button class="btn btn-info" type="button" onclick="exportToCsv()" style="float: right; margin: 0px;margin-right:10px;">
			<i class="ace-icon fa fa-download bigger-110"></i>
			Export
		</button>-->
		
		<button class="btn btn-success" type="button" onclick="sendToLab()" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-briefcase bigger-110"></i>
			Lab
		</button>
		
		<button class="btn btn-info" type="button" onclick="changePrice()" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-pencil bigger-110"></i>
			Change Price
		</button>
		
		<!--<button class="btn btn-info" type="button" onclick="toRepair()" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-pencil bigger-110"></i>
			To Repair
		</button>-->
		
		<button class="btn btn-info" type="button" onClick="loadMemoForm(0,'consign')" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-pencil bigger-110"></i>
			To Consign
		</button>
		<button class="btn btn-info" type="button" onClick="loadMemoForm(0,'sale')" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-pencil bigger-110"></i>
			To Sale
		</button>
		<button class="btn btn-danger" type="button" onclick="printSticker()" style="float: right; margin: 0px; margin-right:10px;padding: 0px 8px;">			
			Tag Print
		</button>
		<div class="inventory-color-cell" style="float:right">				
			<label class="f-ch-input pos-rel">
				<input class="ace bgreen" type="checkbox" name="stype[]" onclick="submitFilter()" value="" />
				<span class="lbl"></span>
			</label> 							
			<div class="color-total">NON-IGI</div>		
		</div>

		<div class="inventory-color-cell" style="float:right">				
			<label class="f-ch-input pos-rel">
				<input class="ace bblue" type="checkbox" onclick="submitFilter()" name="stype[]" value="IGI" />
				<span class="lbl"></span>
			</label> 							
			<div class="color-total">IGI</div>		
		</div>
		
		<div class="inventory-color-cell" style="float:right">				
			<label class="f-ch-input pos-rel">
				<input class="ace bred" type="checkbox" onclick="submitFilter()" name="memo[]" value="memo" />
				<span class="lbl"></span>
			</label> 							
			<div class="color-total">Consign</div>		
		</div>
	</div>
</div>
</form>

<form id="grid-form" class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'/jewelry/jewelry/jewelryController.php'; ?>">
<input type="hidden" name="fn" value="exportToExcel">
<input type="hidden" name="exportProducts" id="exportProducts" value="">
<div class="subform invenotry-cgrid main-grid">
	<div class="divTable " style="width:1785px" >
		<div class="divTableHeading">
			
			<div class="divTableCell"><label class="pos-rel">
					<input class="ace" onclick="allCheck(this)" type="checkbox">
					<span class="lbl"></span>
				</label></div>							
			
			<div class="divTableCell" >Jew Code</div>
			<div class="divTableCell" >Design</div>
			<div class="divTableCell" >Type</div>			
			<div class="divTableCell width-50px" >Gold</div>
			<div class="divTableCell width-50px" >Metal</div>
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
		<div class="divTableBody jewelry-form" >
			
			
			<?php 
				$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
				foreach($inventory as $id=>$value): 
				
				$class ="";
					if($value['lab'] == "IGI")
					{
						$class ="infobox-blue infobox-dark";
					}
					if($value['outward'] == "lab")
					{
						$class ="infobox-green infobox-dark";
					}
					if($value['outward'] =="consign" )
					{
						$class ="infobox-red infobox-dark";
					}
			?>
			<div class="divTableRow <?php echo $class;?>">					
				<div class="divTableCell">
				<label class="pos-rel">
					<input type="checkbox" name="sku" value="<?php echo $value['id'];?>" class="ace" onClick="countCheck(this)" />
					<span class="lbl"></span>
				</label>
				</div>			
				<div class="divTableCell"><?php echo $value['sku'] ?></div>				
				<div class="divTableCell"><?php echo $value['jew_design'] ?></div>			
				<div class="divTableCell"><?php echo $jewType[$value['jew_type']] ?></div>		
				<div class="divTableCell width-50px"><?php echo $value['gold'] ?></div>				
				<div class="divTableCell width-50px"><?php echo $value['metal'] ?></div>				
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
		/* $tpcs = (int)$tpcs +  (int)$value['polish_pcs'];
		$tcarat = (float)$tcarat +  (float)$value['polish_carat'];
		$tcost = (float)$tcost +  (float)$value['cost'];
		$tprice = (float)$tprice +  (float)$value['price'];
		$tamount = (float)$tamount +  (float)$value['amount']; */
		endforeach; ?>	
		</div>
	</div>
</div>
</form>	

<!--<div class="inventory-container mains-grid">
	
	<div class="inventory-total" >
		<div class="int-lable">Pcs :<span class="int-total"> <?php echo $tpcs ?> </span> 
		</div>
	</div>
	<div class="inventory-total" >
		<div class="int-lable">Carat :<span class="int-total"> <?php echo $tcarat ?> </span>
		</div>
	</div>	
	<div class="inventory-total" >
		<div class="int-lable">Avg. Price :<span class="int-total"> <?php if($tcarat >0 ):echo number_format(((float)$tamount/(float)$tcarat ),2,".","");else: echo 0; endif;  ?> </span>
		</div>
	</div>
	<div class="inventory-total" >
		<div class="int-lable">Amount :<span class="int-total"> <?php echo $tamount ?> </span>
		</div>
	</div>
	<div class="color-total color-total-count"> <b>Select Pcs :</b> &nbsp;&nbsp;<span id="total-pcs">0</span> &nbsp;&nbsp; |</div>
	<div class="color-total color-total-count"> <b>Select Carats :</b> &nbsp;&nbsp;<span id="total-carat">0</span> &nbsp;&nbsp; |</div>
	<div class="color-total color-total-count"> <b>Select Price :</b> &nbsp;&nbsp;<span id="total-price">0</span> &nbsp;&nbsp; |</div>
	<div class="color-total color-total-count"> <b>Select Amount :</b> &nbsp;&nbsp;<span id="total-amount">0</span> &nbsp;&nbsp; |</div>
				
</div>-->

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
		
		
	/*	//console.log(jQuery('.subform.invenotry-cgrid').scrollTop());
		var toptempscroll =jQuery('.subform.invenotry-cgrid').scrollTop()+'px';
		if(jQuery('.subform.invenotry-cgrid').scrollTop() > 5)
		{
			jQuery(".divTableHeading").attr('style','z-index:9999;width:2520px; position: absolute; top:0px'+toptempscroll);
		}	
		else
		{
			jQuery(".divTableHeading").attr('style','');
		}*
	}); */
</script>
<style>

.box-container-memo {
    width: 1250px !important;
}
.col-sm-4{padding-left: 5px !important;}
</style>
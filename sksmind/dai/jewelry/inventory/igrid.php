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
$CalClass = $jhelper->InventoryCalClass();
$attribute = $jhelper->getInventoryAttribute();							
$groupUrl = $daiDir.'jewelry/inventory/';														


$inventory = $model->getMyInventory($post,$form_type);

?>
<form id="filter-form" class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'module/inventory/inventoryController.php'; ?>">
<input type="hidden" name="fn" value="exportToExcel">
<input type="hidden" name="sort" value="" id="sortFilter">
<input type="hidden" name="sorttype" value="" id="sortType">


<input class="input-sm col-sm-3 packet" id="packet" value="<?php echo $sku ?>" name="sku" placeholder="Stone Id Or SKU Or Report"  style="text-transform:uppercase" type="text">

<div id="fancy-filter-form" style="<?php if($form_type !='fancy'):  echo 'display:none'; endif; ?>">

<div class="filter" >

	<div class="f-left">
		
		<div class="f-carat">
			<label class="f-l-label" >Carats</label>
			<input class="input-sm col-sm-3" style="margin-right:2%;" id="cfrom" value="<?php echo $cfrom ?>" name="cfrom" placeholder="+ Carat" type="text">
			<input class="input-sm col-sm-3"  id="cto" value="<?php echo $cto ?>" name="cto" placeholder=" - Carat" type="text">
			<button class="btn btn-success" type="button" style="float: left; margin: 0px;padding: 2px;" onclick="submitFilter()">
				<i class="ace-icon fa fa-search bigger-110"></i> Search
			</button>
		</div>
	</div>
	<div class="f-right">
	<div class="f-shape">
		<label class="f-label">Shape</label>
		<ul class="f-ulli">
			<?php foreach($helper->getShape() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace"  type="checkbox" name="shape[]" <?php if(in_array($k,$shape)): echo 'checked'; endif;?>  value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>
	<div class="f-shape">
		<label class="f-label">Clarity</label>
		<ul class="f-ulli">
			<?php foreach($helper->getClarity() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace"  type="checkbox" name="clarity[]" <?php if(in_array($k,$clarity)): echo 'checked'; endif;?> value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>
	<div class="f-shape">
		<label class="f-label">Color</label>
		<ul class="f-ulli">
			<?php foreach($helper->getColor('f') as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace" type="checkbox" name="color[]" <?php if(in_array($k,$color)): echo 'checked'; endif;?> value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>
	
	</div>
</div>

</div>

<div id="white-filter-form" style="<?php if($form_type !='white'):  echo 'display:none'; endif; ?>">

<div class="filter" >

	<div class="f-left">
			<!--<div class="f-package">
				<label class="f-l-label">Stone Id / SKU / Report</label>
				<input class="input-sm col-sm-10 packet" id="packet" value="<?php echo $sku ?>" name="sku" style="text-transform:uppercase" placeholder="Stone Id Or SKU Or Report" type="text">
			</div> -->
			<div class="f-carat">
				<label class="f-l-label" >Carats</label>
				<input class="input-sm col-sm-5" style="margin-right:2%;" id="cfrom" value="<?php echo $cfrom ?>" name="cfrom" placeholder="+ Carat" type="text">
				<input class="input-sm col-sm-5" id="cto" value="<?php echo $cto ?>" name="cto" placeholder=" - Carat" type="text">
				<button class="btn btn-success" type="button" style="float: left; margin: 0px;padding: 2px;" onclick="submitFilter()">
				<i class="ace-icon fa fa-search bigger-110"></i> Search
			</button>
			</div>
			
	</div>
	<div class="f-right">
	<div class="f-shape">
		<label class="f-label">Shape</label>
		<ul class="f-ulli">
			<?php foreach($helper->getShape() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace" type="checkbox" name="shape[]" <?php if(in_array($k,$shape)): echo 'checked'; endif;?>  value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>
	
	<div class="f-shape">
		<label class="f-label">Color</label>
		<ul class="f-ulli">
			<?php foreach($helper->getColor('w') as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace" type="checkbox" name="color[]" <?php if(in_array($k,$color)): echo 'checked'; endif;?> value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>
	
	<div class="f-shape">
		<label class="f-label">Clarity</label>
		<ul class="f-ulli">
			<?php foreach($helper->getClarity() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace" type="checkbox" name="clarity[]" <?php if(in_array($k,$clarity)): echo 'checked'; endif;?> value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>
	
	</div>
</div>
</div>

<div class="inventory-container" style="padding:0px 0px;">
	<div class="inventory-color">
		<div class="inventory-color-cell">		
			<div class="color-total" style="width:100%"> <b>Total :</b> &nbsp;&nbsp;<?php echo count($inventory) ?> &nbsp;&nbsp;</div>
		</div>
	
		
		<?php /* foreach($helper->getGroupType() as $k=>$v): ?>
			<div class="inventory-color-cell" >				
				<label class="f-ch-input pos-rel">
					<input class="ace" onclick="submitFilter()" <?php if(in_array($k,$groupType)): echo 'checked'; endif;?> type="checkbox" name="type[]" value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label>
				<?php 
				$fclass ='';
				if($k=='single')
					$fclass ='fa-diamond';
				if($k=='box')
					$fclass ='fa-codepen';
				if($k=='parcel')
					$fclass ='fa-dropbox';
				?>	
				<div class="color-total"> <i class="fa orange <?php echo $fclass;?>" style="font-size: 14px;" ></i> <?php echo $v; ?> </div>		
			</div>	
		<?php endforeach; */ ?>		
		
		<button class="btn btn-success" type="button" style="float: right; margin: 0px;" onclick="location.reload();">
			<i class="ace-icon fa fa-refresh bigger-110"></i> Reload
		</button>
		
		
		<button class="btn btn-info" type="button" onclick="exportToCsv()" style="float: right; margin: 0px;margin-right:10px;">
			<i class="ace-icon fa fa-download bigger-110"></i>
			Export
		</button>
		
		<button class="btn btn-info" type="button" onclick="changePrice(0)" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-pencil bigger-110"></i>
			Change Price
		</button>
		
		<button class="btn btn-success" type="button" onclick="tojobwork()" style="float: right; margin: 0px; margin-right:10px;">
			<i class="ace-icon fa fa-briefcase bigger-110"></i>
			To Job Collet
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
		
		<div class="inventory-color-cell" style="float:right">				
			<label class="f-ch-input pos-rel">
				<input class="ace bred" type="checkbox" onclick="submitFilter()" name="memo[]" value="memo" />
				<span class="lbl"></span>
			</label> 							
			<div class="color-total"> Memo  </div>		
		</div>
			
	</div>
</div>
</form>

<form id="grid-form" class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'jewelry/inventory/inventoryController.php'; ?>">
<input type="hidden" name="fn" value="mexportToExcel">
<input type="hidden" name="exportProducts" id="exportProducts" value="">
<div class="subform invenotry-cgrid main-grid">
	<div class="divTable " style="width:1800px;" >
		<div class="divTableHeading" >
			
			<div class="divTableCell" style="width:100px !important">
			<label class="pos-rel">
					<input class="ace" onclick="allCheck(this)" type="checkbox">
					<span class="lbl"></span>
				</label>	
			</div>
			<!-- <div class="divTableCell" style="width:100px !important">
				<label class="pos-rel">
					Side Stone
				</label>
			</div>	 -->
			
			<?php foreach($attribute as $key=>$v): ?>				
				<?php if($key == 'main_color'):?>
						<div class="divTableCell" onClick="sortForFilter('<?php echo $key ?>')" style="width:200px"><?php echo $v; ?></div>
				<?php else:?>		
						<div onClick="sortForFilter('<?php echo $key ?>')" style="<?php echo($key == 'remarks')?"width:200px":""; ?>" class="divTableCell"><?php echo $v; ?></div>
				<?php endif;?>
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
				if($value['lab'] =="IGI")
				{
					$class ="infobox-blue infobox-dark";
				}
				
				$sku = $value['sku'];
				$rcolor = $value['color'];
				?>
		
			
			
			<div class="divTableRow <?php echo $class;?>">					
				<div class="divTableCell " style="width:100px !important">
				<label class="pos-rel">
					<input type="checkbox" name="mainstone" value="<?php echo $value['id'];?>" class="ace mainstone" onClick="mainCheck(this)" />
					<span class="lbl"></span>
				</label>
				</div>
				<!-- <div class="divTableCell center " style="width:100px !important">
				<label class="pos-rel">
					<input type="checkbox" name="sidestone" value="<?php echo $value['id'];?>" class="ace sidestone" onClick="sideCheck(this)" />
					<span class="lbl"></span>
				</label>
				</div>	 -->
				
				<?php foreach($attribute as $key=>$v): 
				
				?>
					
					
				
					<?php if($key =='sku' ): ?>
						<div class="divTableCell  $CalClass  <?php if(in_array($key,$right))echo 'a-right'; ?> "><?php echo $sku?></div>
					<?php else : ?>							
					<div class="divTableCell  $CalClass <?php if(array_key_exists($key,$CalClass))echo $CalClass[$key]; ?> <?php if(in_array($key,$right))echo 'a-right'; ?> " style="<?php echo($key == 'remarks')?"width:200px":""; ?>" ><?php echo (isset($value[$key]))?$value[$key]:'&nbsp;';?></div>
					<?php endif;?>
				<?php endforeach;?>
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
</form>	

<div class="inventory-container mains-grid">
	
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
				
</div>

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
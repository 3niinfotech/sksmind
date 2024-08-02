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

$model  = new inventoryModel();
$helper  = new Helper();

$width50 = $helper->width50(); 
$width70 = $helper->width70(); 
$right = $helper->right();
$CalClass = $helper->InventoryCalClass();
$attribute = $helper->getInventoryAttribute();							
$groupUrl = $daiDir.'module/inventory/';														


$inventory = $model->getMyInventory($post,$form_type);
$stockDate = $helper->getAllStockDate();

$currentDate =0;
if(isset($post['currentDate']))
	$currentDate=$post['currentDate'];	

$stockData = $helper->getStockDateData($currentDate);
//$stockData[154] = 100;


?>
<form id="filter-form" class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'/module/inventory/inventoryController.php'; ?>">
<input type="hidden" name="fn" value="exportToExcel">
<input type="hidden" name="sort" value="" id="sortFilter">
<input type="hidden" name="sorttype" value="" id="sortType">


<input class="input-sm col-sm-3 packet" id="packet" value="<?php echo $sku ?>" name="sku" placeholder="Stone Id Or SKU Or Report"  style="text-transform:uppercase" type="text">

<div id="fancy-filter-form" style="<?php if($form_type !='fancy'):  echo 'display:none'; endif; ?>">

<div class="filter" >

	<div class="f-left">
		<!-- <div class="f-package">
			<label class="f-l-label">Stone Id / SKU / Report.</label>
			<input class="input-sm col-sm-10 packet" id="packet" value="<?php echo $sku ?>" name="sku" placeholder="Stone Id Or SKU Or Report"  style="text-transform:uppercase" type="text">
		</div> -->
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
	<div class="f-shape">
		<label class="f-label">Intensity</label>
		<ul class="f-ulli">
			<?php foreach($helper->getIntensity() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace"  type="checkbox" name="intensity[]" <?php if(in_array($k,$intensity)): echo 'checked'; endif;?> value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>
	<div class="f-shape">
		<label class="f-label">Overtone</label>
		<ul class="f-ulli">
			<?php foreach($helper->getOvertone() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace"  type="checkbox" name="overtone[]" <?php if(in_array($k,$overtone)): echo 'checked'; endif;?> value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>
	
	<div class="f-shape">
		<label class="f-label">Fluorescence</label>
		<ul class="f-ulli">
			<?php foreach($helper->getFlsIntensity() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace"  type="checkbox" name="f_intensity[]" <?php if(in_array($k,$f_intensity)): echo 'checked'; endif;?> value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>
	<div class="f-shape">
		<label class="f-label">Package</label>
		<ul class="f-ulli">
			<?php foreach($helper->getPackage() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace"  type="checkbox" name="package[]" <?php if(in_array($k,$package)): echo 'checked'; endif;?>  value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>
	
	<div class="f-shape" style="width:120px">
		<label class="f-label">Location</label>
		<ul class="f-ulli">
			<?php foreach($helper->getLocation() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace"  type="checkbox" name="location[]" <?php if(in_array($k,$location)): echo 'checked'; endif;?>  value="<?php echo $k;?>">
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
	<div class="f-shape">
		<label class="f-label">Polish</label>
		<ul class="f-ulli">
			<?php foreach($helper->getPolish() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace"  type="checkbox" name="polish[]" <?php if(in_array($k,$polish)): echo 'checked'; endif;?> value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>
	 <div class="f-shape">
		<label class="f-label">Symmetry</label>
		<ul class="f-ulli">
			<?php foreach($helper->getPolish() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace" type="checkbox" name="symmentry[]" <?php if(in_array($k,$symmentry)): echo 'checked'; endif;?> value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>	
	<div class="f-shape">
		<label class="f-label">Cut</label>
		<ul class="f-ulli">
			<?php foreach($helper->getPolish() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace" type="checkbox" name="cut[]" <?php if(in_array($k,$cut)): echo 'checked'; endif;?> value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> <?php echo $v;?>
			</li>
			<?php endforeach; ?>				
		</ul>
	</div>	
	
	<div class="f-shape">
		<label class="f-label">Flo.Inten</label>
		<ul class="f-ulli">
			<?php foreach($helper->getFlsIntensity() as $k=>$v): ?>
			<li><label class="pos-rel">
					<input class="ace" type="checkbox" name="f_intensity[]" <?php if(in_array($k,$f_intensity)): echo 'checked'; endif;?> value="<?php echo $k;?>">
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
		
		<?php foreach($helper->getOutwardType() as $k=>$v): 
			
			$bclass = "";
			if($k =="gia")
				$bclass = "bblue";	
			if($k =="memo")
				$bclass = "bred";	
			if($k =="lab")
				$bclass = "bgreen";		
		?>
			<div class="inventory-color-cell" >				
				<label class="f-ch-input pos-rel">
					<input class="ace <?php echo $bclass;?>" onclick="submitFilter()" type="checkbox" <?php if(in_array($k,$memoType)): echo 'checked'; endif;?> name="memo[]" value="<?php echo $k;?>">
					<span class="lbl"></span>
				</label> 
				
				<div class="color-total"> <?php echo $v; ?> </div>		
			</div>		
		<?php endforeach; ?>
		<div class="inventory-color-cell" >				
			<label class="f-ch-input pos-rel">
				<input class="ace bgrey" onclick="submitFilter()" type="checkbox" <?php if(isset($post['hold'])): echo 'checked'; endif;?> name="hold" >
				<span class="lbl"></span>
			</label> 
			
			<div class="color-total"> Hold </div>		
		</div>
		<div class="inventory-color-cell" >				
			<label class="f-ch-input pos-rel">
				<input class="ace bgrey" onclick="submitFilter()" type="checkbox" <?php if(isset($post['nm'])): echo 'checked'; endif;?> name="nm" >
				<span class="lbl"></span>
			</label>
			<div class="color-total"> NM </div>		
		</div>
		
		<?php foreach($helper->getGroupType() as $k=>$v): ?>
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
		<?php endforeach; ?>		
		<div class="inventory-color-cell" >				
			<label class="f-ch-input pos-rel">
				<input class="ace" onclick="submitFilter()" type="radio" <?php if($diamond == 'F'): echo 'checked'; endif;?> name="diamond" value="F">
				<span class="lbl"></span>
			</label>
			<div class="color-total"> F </div>		
		</div>
		<div class="inventory-color-cell" >				
			<label class="f-ch-input pos-rel">
				<input class="ace" onclick="submitFilter()" type="radio" <?php if($diamond == 'W'): echo 'checked'; endif;?> name="diamond" value="W">
				<span class="lbl"></span>
			</label>
			<div class="color-total"> W </div>		
		</div>
		<button class="btn btn-success" type="button" style="float: right; margin: 0px;" onclick="document.location.href = '<?php echo $daiUrl.'module/inventory/stock.php/' ?>'">
			<i class="ace-icon fa fa-refresh bigger-110"></i> Reset
		</button>
		
		<button class="btn btn-info" type="button" onclick="saveStock()" style="float: right; margin: 0px;margin-right:10px;">
			<i class="ace-icon fa fa-save bigger-110"></i>
			Save Stock
		</button>
		
		
	</div>
</div>

<select class="col-xs-10" id="dataChange" name="currentDate" onchange="submitFilter();">
	<option value="">Select Date</option>
	<?php 
	foreach($stockDate as $key => $value):
	?>						
	<option value="<?php echo $key?>" <?php echo ($key == $currentDate)?"selected":""; ?> ><?php echo $value?></option>
	<?php endforeach; ?>

</select>
</form>



<form id="grid-form" class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'/module/inventory/inventoryController.php'; ?>">
<input class="input-sm col-sm-12" id="stockdate" value="" name="stockdate" placeholder="Stock Date" type="text">
<input type="hidden" name="fn" value="saveStock">
<input type="hidden" name="olddate" value="<?php echo $currentDate;?>" >

<div class="subform invenotry-cgrid main-grid">
	<div class="divTable " style="width:2810px;" >
		<div class="divTableHeading">
			
			<div class="divTableCell"><label class="pos-rel">
					<input class="ace" onclick="allCheck(this)" type="checkbox">
					<span class="lbl"></span>
				</label></div>				
			<div class="divTableCell">Action</div>
			<div class="divTableCell">Remark</div>
			<?php foreach($attribute as $key=>$v): ?>				
				<?php if($key == 'main_color'):?>
						<div class="divTableCell" onClick="sortForFilter('<?php echo $key ?>')" style="width:200px"><?php echo $v; ?></div>
				<?php else:?>		
						<div onClick="sortForFilter('<?php echo $key ?>')" class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?> <?php if(in_array($key,$width70))echo 'width-70px'; ?>"><?php echo $v; ?></div>
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
				if($value['lab'] !="")
				{
					$class ="infobox-blue infobox-dark";
				}
				if($value['outward'] =="memo" || $value['outward'] =="consign" )
				{
					$class ="infobox-red infobox-dark";
				}
				if($value['hold'])
				{
					$class ="infobox-grey infobox-dark";
				}
				
				/*$MPData = array();
				if(($value['group_type'] =="box" || $value['group_type'] =="parcel" )  && $value['child_count'] != 0 )
				{
					$MPData = $helper->getParentDetail ($value['id']);
				}*/
				
				if($value['outward_parent'] == 0)
				{
					$sku = $value['sku'];
				}
				else
				{
					$parentData = $helper->getProductDetail ($value['outward_parent']);					
					$sku = $parentData['sku'];
				}
				?>
		
			
			
			<div class="divTableRow <?php echo $class;?>">					
				<div class="divTableCell">
				<label class="pos-rel">
					<input type="checkbox" name="products" value="<?php echo $value['id'];?>" class="ace" onClick="countCheck(this)" />
					<span class="lbl"></span>
				</label>
				</div>	
				<div class="divTableCell"  style="background:#fff!important;" id="product-id-<?php echo $value['id'] ?>" >
					<?php  ?>
					<input type="hidden" id='action-<?php echo $value['id'] ?>' name="action[<?php echo $value['id'] ?>][a]" value="<?php echo (isset($stockData[$value['id']])) ? $stockData[$value['id']]['a'] : '' ; ?>" />
					<span class="fa fa-check green grid-action <?php echo (isset($stockData[$value['id']]) && $stockData[$value['id']]['a'] ==1) ? 'active':''; ?>" data='1' pid='<?php echo $value['id'] ?>' ></span> 
					<span class="fa fa-minus-square grey grid-action <?php echo (isset($stockData[$value['id']]) && $stockData[$value['id']]['a'] ==2) ? 'active':''; ?>" data='2' pid='<?php echo $value['id'] ?>'></span> 
					<span class="fa fa-times red grid-action <?php echo (isset($stockData[$value['id']]) && $stockData[$value['id']]['a'] ==3) ? 'active':''; ?>" data='3' pid='<?php echo $value['id'] ?>'></span>					
				
				</div>	
				<div class="divTableCell">
					<input type="text" name="action[<?php echo $value['id'] ?>][r]" value="<?php echo (isset($stockData[$value['id']])) ? $stockData[$value['id']]['r'] : '' ; ?>" />				
				</div>
				<div class="divTableCell">
				<?php 
						$id = $value['id'];
						$fclass ='';
						if(strtoupper($value['group_type']) =='SINGLE')
							$fclass ='fa-diamond';
						if(strtoupper($value['group_type']) =='BOX')
							$fclass ='fa-codepen';
						if(strtoupper($value['group_type']) =='PARCEL')
							$fclass ='fa-dropbox';
							
						if(strtoupper($value['group_type']) =='BOX' && $value['box_products'] !="" )
						{	
							 echo '<a style="cursor:pointer;" onClick="loadBox('.$id.',1)" >'.'<i class="fa orange '.$fclass.'" style="font-size: 14px;" ></i> '.$value['mfg_code'];echo($value['diamond_no']!="" )?'-'.$value['diamond_no']:'';echo'</a>'; 
						 }
						 else if(strtoupper($value['group_type']) =='PARCEL' && $value['parcel_products'] !="" ){	
							 echo '<a style="cursor:pointer;" onClick="loadBox('.$id.',0)" >'.'<i class="fa orange '.$fclass.'" style="font-size: 14px;" ></i> '.$value['mfg_code'];echo($value['diamond_no']!="" )?'-'.$value['diamond_no']:'';echo'</a>'; 
						 }
						else
						{
							echo '<i class="fa orange '.$fclass.'" style="font-size: 14px;" ></i> '.$value['mfg_code'];echo($value['diamond_no']!="" )?'-'.$value['diamond_no']:'';
						}
				?>	
				</div>
				<?php foreach($attribute as $key=>$v): ?>
					<?php if($key =='mfg_code' || $key =='discount' ) continue;?>
					
					<?php if($key =='rapnet' ): ?>
						<?php 
						$rcolor = ($value['main_color'] !='')?$value['main_color']:$value['color'];
						$rapData = $helper->RapnetPrice($value['shape'],$rcolor,$value['clarity'],$value['polish_carat'],$value['price']); ?>
						<div class="divTableCell  $CalClass  a-right width-70px"><?php echo $rapData['price'];?></div>
						<div class="divTableCell  $CalClass  center width-70px "><?php echo $rapData['discount'];?></div>
					<?php elseif($key =='main_color' ): ?>
						<div class="divTableCell  $CalClass <?php if(array_key_exists($key,$CalClass))echo $CalClass[$key]; ?>" style="width:200px"><?php echo (isset($value[$key]))?$value[$key]:'&nbsp;';?></div>
						
					<?php elseif($key =='sku' ): ?>
						<div class="divTableCell fixsku $CalClass <?php if(array_key_exists($key,$CalClass))echo $CalClass[$key]; ?> <?php if(in_array($key,$right))echo 'a-right'; ?> <?php if(in_array($key,$width50))echo 'a-right'; ?>  <?php if(in_array($key,$width50))echo 'width-50px'; ?> <?php if(in_array($key,$width70))echo 'width-70px'; ?> "><?php //echo $id; ?> <a href="http://shreehk.com/rapnet.php?sku=<?php echo $sku ?>"  class="ni-color" target="_blank" title="View Videos and Photos " ><?php //echo $value['id'].' - ' ;?><?php echo $sku?></a></div>
					<?php elseif($key =='report_no' ): ?>
						<div class="divTableCell  $CalClass <?php if(array_key_exists($key,$CalClass))echo $CalClass[$key]; ?> <?php if(in_array($key,$right))echo 'a-right'; ?> <?php if(in_array($key,$width50))echo 'a-right'; ?>  <?php if(in_array($key,$width50))echo 'width-50px'; ?> <?php if(in_array($key,$width70))echo 'width-70px'; ?> "><?php //echo $id; ?> <a href="https://www.gia.edu/report-check?reportno=<?php echo $value[$key] ?>" class="ni-color" target="_blank" title="View GIA Report " ><?php echo (isset($value[$key]))?$value[$key]:'&nbsp;';?></a></div>
					<?php elseif($key =='remark' ): ?>
						<div class="divTableCell  $CalClass <?php if(array_key_exists($key,$CalClass))echo $CalClass[$key]; ?> <?php if(in_array($key,$right))echo 'a-right'; ?> <?php if(in_array($key,$width50))echo 'a-right'; ?>  <?php if(in_array($key,$width50))echo 'width-50px'; ?> <?php if(in_array($key,$width70))echo 'width-70px'; ?> "><input style="width: 100%; height: 100%; border: 0px none;" type="text" value="<?php echo $value[$key] ?>" onBlur="changeTextValue(this.value,<?php echo $value['id'] ?>,'remark')"></div>
					<?php /*elseif($key =='polish_pcs' && $value['child_count'] != 0 ): ?>
						<div class="divTableCell  $CalClass <?php if(array_key_exists($key,$CalClass))echo $CalClass[$key]; ?> <?php if(in_array($key,$right))echo 'a-right'; ?> <?php if(in_array($key,$width50))echo 'a-right'; ?>  <?php if(in_array($key,$width50))echo 'width-50px'; ?> <?php if(in_array($key,$width70))echo 'width-70px'; ?> "><span style="font-size:10px" class="red"><?php echo $MPData['pcs'];?></span>&nbsp; <?php echo (isset($value[$key]))?$value[$key]:'&nbsp;';?></div>
	
					<?php elseif($key =='polish_carat' && $value['child_count'] != 0 ): ?>
						<div class="divTableCell  $CalClass <?php if(array_key_exists($key,$CalClass))echo $CalClass[$key]; ?> <?php if(in_array($key,$right))echo 'a-right'; ?> <?php if(in_array($key,$width50))echo 'a-right'; ?>  <?php if(in_array($key,$width50))echo 'width-50px'; ?> <?php if(in_array($key,$width70))echo 'width-70px'; ?> "><span style="font-size:10px" class="red"><?php echo $MPData['carat'];?></span>&nbsp; <?php echo (isset($value[$key]))?$value[$key]:'&nbsp;';?></div>
														
														
					<?php /*elseif($key =='package' ): ?>
						<div class="divTableCell  $CalClass <?php if(array_key_exists($key,$CalClass))echo $CalClass[$key]; ?> <?php if(in_array($key,$right))echo 'a-right'; ?> <?php if(in_array($key,$width50))echo 'a-right'; ?>  <?php if(in_array($key,$width50))echo 'width-50px'; ?> <?php if(in_array($key,$width70))echo 'width-70px'; ?> "><input style="width: 100%; height: 100%; border: 0px none;" type="text" value="<?php echo $value[$key] ?>" onBlur="changeTextValue(this.value,<?php echo $value['id'] ?>,'package')"></div>
					<?php */ else : ?>							
					<div class="divTableCell  $CalClass <?php if(array_key_exists($key,$CalClass))echo $CalClass[$key]; ?> <?php if(in_array($key,$right))echo 'a-right'; ?> <?php if(in_array($key,$width50))echo 'a-right'; ?>  <?php if(in_array($key,$width50))echo 'width-50px'; ?> <?php if(in_array($key,$width70))echo 'width-70px'; ?> "><?php echo (isset($value[$key]))?$value[$key]:'&nbsp;';?></div>
					<?php endif;?>
				<?php endforeach;?>
			</div>
			<?php 
		$tpcs = (int)$tpcs +  (int)$value['polish_pcs'];
		$tcarat = (float)$tcarat +  (float)$value['polish_carat'];
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

	$('.grid-action').on('click',function()
	{		
		var pid = $(this).attr('pid');		
		
		$('#product-id-'+pid+' .grid-action').removeClass('active');
		$(this).addClass('active');					
		var vals = $(this).attr('data');		
		
		$('#action-'+pid).val(vals);
		
   });
   
   $( "#stockdate" ).datepicker({
		showOtherMonths: true,
		selectOtherMonths: false,
		dateFormat: 'yy-mm-dd',
	});

	
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
			
			jQuery(".main-grid .divTable").attr('style','width:2380px;');
			
		}	
		else
		{
			jQuery(".fixsku").attr('style','');
			jQuery(".fixsku a").attr('style','');
			jQuery(".divTableCell:first-child").attr('style','');
			jQuery(".invenotry-cgrid .divTableHeading .divTableCell:nth-child(3)").attr('style','');
			jQuery(".main-grid .divTable").attr('style','width:2520px;');
		}	
		
		
		//console.log(jQuery('.subform.invenotry-cgrid').scrollTop());
		var toptempscroll =jQuery('.subform.invenotry-cgrid').scrollTop()+'px';
		if(jQuery('.subform.invenotry-cgrid').scrollTop() > 5)
		{
			jQuery(".divTableHeading").attr('style','z-index:9999;width:2520px; position: absolute; top:0px'+toptempscroll);
		}	
		else
		{
			jQuery(".divTableHeading").attr('style','');
		}
	});
	*/
</script>
<style>

.box-container-memo {
    width: 1250px !important;
}
.col-sm-4{padding-left: 5px !important;}
</style>
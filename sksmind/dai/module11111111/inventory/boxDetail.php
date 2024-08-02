<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 	
include_once('inventoryModel.php');
include_once($daiDir.'Helper.php');

$model  = new inventoryModel();
$helper  = new Helper();
$attribute = $helper->getAttribute();							
$BData = $model->getDetail($_GET['id']);														
$width50 = $helper->width50(); 	
?>	

<div class="page-header">							
	<h1 style="float:left">
		<?php echo ($_GET['type'])?'BOX':'PARCEL'; echo '-'. $BData['mfg_code'] ?>								
	</h1>
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn grid-btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
	<?php if(isset($_GET['from']) && $_GET['from'] == 'box' ) : ?>	
	<button id="addToBox"  class="btn btn-info grid-btn" onClick="removeFromBox(<?php echo $_GET['id']?>)" style="float:right" type="button">
		<i class="ace-icon fa fa-minus bigger-110"></i>
		Remove from <?php echo ($_GET['type'])?'BOX':'PARCEL';?>	
	</button>
	
	<button id="addToParcel"  class="btn btn-info grid-btn" onClick="openParcelPopup(<?php echo $_GET['id']?>)" style="float:right" type="button">
		<i class="ace-icon fa fa-plus bigger-110"></i>
		Add to <?php echo (!$_GET['type'])?'BOX':'PARCEL';?>	
	</button>
	<?php endif;?>
</div>

<div class="subform invenotry-cgrid ">
	<div class="divTable" >
		<div class="divTableHeading">
			
			<div class="divTableCell"><label class="pos-rel">
					<input class="ace" onclick="calcSelecteds(this)" type="checkbox">

					<span class="lbl"></span>
				</label></div>				
			<div class="divTableCell">Mfg. code</div>
			<div class="divTableCell width-50px">D. No.</div>
			<div class="divTableCell">SKU</div>
			<div class="divTableCell width-50px">R.Pcs</div>
			<div class="divTableCell width-50px">R.Carat</div>		
			<div class="divTableCell width-50px">P.Pcs</div>
			<div class="divTableCell width-50px">P.Carat</div>
			<div class="divTableCell width-70px">Cost</div>		
			<div class="divTableCell width-70px">Price</div>
			<div class="divTableCell width-70px">Amount</div>
			<div class="divTableCell width-50px">LOC </div>
			<div class="divTableCell">Remark</div>
			<div class="divTableCell width-50px">Lab</div> 
			<?php foreach($attribute as $key=>$v): ?>
			<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $v; ?></div>
			<?php endforeach;?>
		</div>	
		<div class="divTableBody">
			<?php 
			
			
			$i=1;
			if($_GET['type'])
				$bpProducts = explode(',',$BData['box_products']); 
			else
				$bpProducts = explode(',',$BData['parcel_products']);
				
			foreach($bpProducts  as $id): 
			
			$jData = $model->getDetail($id);
	
			if(empty($jData))
				continue;
			$class="";
			if($jData['outward'] =="lab")
			{
				$class ="infobox-green infobox-dark";
			}
			else if($jData['lab'] !="")
			{
				$class ="infobox-blue infobox-dark";
			}
			else if($jData['outward'] =="memo")
			{
				$class ="infobox-red infobox-dark";
			}
			?>
			
			<div class="divTableRow <?php echo $class;?>">					
				<div class="divTableCell">
				<label class="pos-rel">
					
					
					<?php if(isset($_GET['from']) && $_GET['from'] == 'box'): ?>					
						
						<input name="products" value="<?php echo $jData['id']?>" class="ace" onclick="countCheck(this)" type="checkbox">
					<?php else: ?>
						<input name="products" value="<?php echo $jData['id']?>" class="ace" onclick="totalSelected(1)" type="checkbox">
					<?php endif; ?>
					<span class="lbl"></span>
				</label>
				</div>			
				<div class="divTableCell"><?php echo $jData['mfg_code']?></div>
				<div class="divTableCell width-50px">  <?php echo $jData['diamond_no']?>  </div>
				<div class="divTableCell">  <?php echo $jData['sku']?> </div>
				<div class="divTableCell width-50px a-right">  <?php echo $jData['rought_pcs']?></div>
				<div class="divTableCell width-50px a-right">  <?php echo $jData['rought_carat']?> </div>
				<div class="divTableCell width-50px pcs a-right">  <?php echo $jData['polish_pcs']?> </div>
				<div class="divTableCell width-50px carats a-right">  <?php echo $jData['polish_carat']?>  </div>
				<div class="divTableCell width-70px a-right">  <?php echo $jData['cost']?> </div>
				<div class="divTableCell width-70px price a-right">  <?php echo $jData['price']?></div>
				<div class="divTableCell width-70px amount a-right">  <?php echo $jData['amount']?></div>
				<div class="divTableCell width-50px">  <?php echo $jData['location']?> </div>
				<div class="divTableCell">  <?php echo $jData['remark']?> </div>
				<div class="divTableCell width-50px ">  <?php echo $jData['lab']?> </div>
				<?php foreach($attribute as $key=>$v): ?>
				<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo (isset($jData[$key]))?$jData[$key]:'&nbsp;'?></div>
				<?php endforeach;?>
			</div>
			<?php endforeach;?>
		</div>
	</div>
</div>
<?php if($BData['polish_carat'] !="0"): ?>	
<div class="inventory-color">
	<div class="inventory-color-cell" style="width:100%;padding-bottom:10px">		
		<div class="color-total" style=""> <?php echo ($_GET['type'])? '<b>Item In Box :</b> &nbsp;&nbsp;'.count(explode(',',$BData['box_products'])) : '<b>Item In Parcel :</b> &nbsp;&nbsp;'.count(explode(',',$BData['parcel_products'])) ?> &nbsp;&nbsp; |</div>
		
		<div class="color-total color-total-count"> <b>Pcs :</b> &nbsp;&nbsp;<span id="total-pcs"><?php echo $BData['polish_pcs'] ?> </span> &nbsp;&nbsp; |</div>
		<div class="color-total color-total-count"> <b>Carats :</b> &nbsp;&nbsp;<span id="total-carat"><?php echo $BData['polish_carat'] ?></span> &nbsp;&nbsp; |</div>
		<div class="color-total color-total-count"> <b>Price :</b> &nbsp;&nbsp;<span id="total-price"><?php echo number_format(((float)$BData['price'] / (float)$BData['polish_carat'] ),2,",","") ?></span> &nbsp;&nbsp; |</div>
		<div class="color-total color-total-count"> <b>Amount :</b> &nbsp;&nbsp;<span id="total-amount"><?php echo $BData['amount'] ?></span> &nbsp;&nbsp; |</div>
		
	</div>
	<div class="inventory-color-cell" style="width:100%;padding-bottom:10px">		
		<div class="color-total" style=""> <b>Shape :</b> &nbsp;&nbsp;<?php echo $BData['shape'] ?> &nbsp;&nbsp; |</div>
		<div class="color-total" style=""> <b>Color :</b> &nbsp;&nbsp;<?php echo $BData['color'] ?> &nbsp;&nbsp; |</div>
		<div class="color-total" style=""> <b>Clarity :</b> &nbsp;&nbsp;<?php echo $BData['clarity'] ?> &nbsp;&nbsp; |</div>
	</div>	
	
	<div class="inventory-color-cell" style="width:100%;padding-bottom:10px">		
		<div class="color-total" style=""> <b>Location :</b> &nbsp;&nbsp;<?php echo $BData['location'] ?> &nbsp;&nbsp; |</div>
		<div class="color-total" style=""> <b>Date :</b> &nbsp;&nbsp;<?php echo $BData['date'] ?> &nbsp;&nbsp; |</div>
		<div class="color-total" style=""> <b>Remark :</b> &nbsp;&nbsp;<?php echo $BData['remark'] ?> &nbsp;&nbsp; |</div>
	</div>	
</div>
<?php endif;?>

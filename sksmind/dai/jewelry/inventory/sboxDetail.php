<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 	
include_once('inventoryModel.php');
include_once($daiDir.'Helper.php');

$model  = new inventoryModel($cn);
$helper  = new Helper($cn);
$attribute = $helper->getAttribute();							
$BData = $model->getDetail($_GET['id']);														
$width50 = $helper->width50(); 


	
?>	

<form id="box-form">
<input type="hidden" name="fn" value="toSeparateSingle">
<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
<div class="page-header">							
	<h1 style="float:left">
		<?php echo ($_GET['type'])?'BOX':'PARCEL';?> Detail - <?php echo $BData['mfg_code'] ?>								
	</h1>
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn grid-btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
		
	
	
	<button id="addToBox"  class="btn btn-info grid-btn" onClick="separateFromBox(<?php echo $_GET['id']?>)" style="float:right" type="button">
		<i class="ace-icon fa fa-minus bigger-110"></i>
		Remove from <?php echo ($_GET['type'])?'BOX':'PARCEL';?>	
	</button>
	<button id="removeToBox" disabled class="btn btn-info grid-btn" onClick="removeFromBox(<?php echo $_GET['id']?>)" style="float:right" type="button">
		<i class="ace-icon fa fa-minus bigger-110"></i>
		Remove Single from <?php echo ($_GET['type'])?'BOX':'PARCEL';?>	
	</button>
	
</div>
<div class="subform invenotry-cgrid " style="height: 120px;">
	<div class="divTable" >
		<div class="divTableHeading">
			
			<div class="divTableCell">No</div>				
			<div class="divTableCell">Mfg. code</div>

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
		<div class="divTableBody" id="seprare-grid" >
			<?php $jData = $BData;?>
			<div class="divTableRow infobox-grey infobox-dark">					
				<div class="divTableCell">1</label>
				</div>			
				<div class="divTableCell"><?php echo $jData['mfg_code']?></div>				
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
			
			<div class="divTableRow <?php //echo $class;?>">					
				<div class="divTableCell">
				<label class="pos-rel">
					
					
					<?php if(isset($_GET['from']) && $_GET['from'] == 'box'): ?>					
						
						<input name="products" value="<?php echo $jData['id']?>" class="ace" onclick="totalSelected(this)" type="checkbox">
					<?php else: ?>
						<input name="products" value="<?php echo $jData['id']?>" class="ace" onclick="totalSelected(1)" type="checkbox">
					<?php endif; ?>
					<span class="lbl"></span>
				</label>
				</div>			
				<div class="divTableCell"><?php echo $jData['mfg_code']?></div>
				
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
<div style="clear:both"></div>
<br/><br/>
<div class="subform" style="height: 200px;">
<div class="divTable separate-table" style="width: 3250px;">
	<div class="divTableHeading">
		<div class="divTableCell">No.</div>		
		<div class="divTableCell">Mfg</div>		
		<div class="divTableCell">SKU <span class="required">*</span></div>
		<div class="divTableCell">Pcs</div>
		<div class="divTableCell">Carat <span class="required">*</span></div>
		<div class="divTableCell">Cost</div>		
		<div class="divTableCell">Price <span class="required">*</span></div>
		<div class="divTableCell">Amount <span class="required">*</span></div>
		<div class="divTableCell">LOC </div>
		<div class="divTableCell">Remark</div>
		<div class="divTableCell">Lab</div> 
		<?php foreach($attribute as $key=>$v): ?>
		<div class="divTableCell"><?php echo $v; ?></div>
		<?php endforeach;?>
	</div>		
	
	<div class="divTableBody">		
		<div class="divTableRow">
			<div class="divTableCell">1</div>			
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][mfg_code]" id="mfg-1" onBlur="addNewRow(1)" type="text" ></div>			
			<div class="divTableCell"><input class="col-sm-12"  name="record[1][sku]" id="sku-1" onBlur="addNewRow(1)" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][polish_pcs]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][polish_carat]" id="pcarat-1" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][cost]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][price]" id="price-1" onBlur="calAmount(1)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][amount]" id="amount-1" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][location]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][remark]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][lab]" type="text"></div> 
			<?php 
			foreach($attribute as $key=>$v): ?>
			<div class="divTableCell">			
					<input class=" col-sm-12"  name="record[1][attr][<?php echo $key?>]" type="text">
			</div>
			<?php endforeach;?>
		</div>					
	</div>	
</div>
</div>

</form>

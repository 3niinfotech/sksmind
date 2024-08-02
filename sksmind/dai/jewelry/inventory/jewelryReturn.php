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
include_once($daiDir.'jewelry/jobwork/jobworkModel.php');
include_once($daiDir.'jewelry/party/partyModel.php');
include_once('inventoryModel.php');

include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$imodel  = new inventoryModel($cn);
$model  = new jobworkModel($cn);

$groupUrl = $daiDir.'jewelry/jobwork/';
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$jewType = $jhelper->getJewelryType();
$design = $jhelper->getAllDesign();
$gold = $jhelper->getGoldType();
$goldColor = $jhelper->getGoldColor();
$mmaker = $jhelper->getAllMemoMaker();
$lid=0;
if(isset($_POST['id']))
{
	$lid = $_POST['id'];
}
$data =  $model->getData($lid);		
$dataRecord =  $model->getRecordData($lid);		

$main_stone =  $dataRecord['main_stone'];
$side_stone =  $dataRecord['side_stone'];
$collet_stone =  $dataRecord['collet_stone'];
?>

<div class="page-header">							
	<h1 style="float:left">
		Jewelry Return - (<?php echo $data['entryno']?>)
	</h1>
	<button id="close-box" onclick="closeBox1(<?php echo $_POST['pid']; ?>)" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
	
	<button class="btn btn-info" style="float:right;margin-right: 10px;" id="btn-save" type="button" onClick="saveMemoForm()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Return Memo
	</button>
	
	<button class="btn reset"  style="float:right;margin-right: 10px;" type="reset">
		<i class="ace-icon fa fa-undo bigger-110"></i>
		Reset
	</button>	
	
</div>
<?php


/*if($_POST['type'] == 'memo'):
	$invoice = $model->getIncrementEntry('memo_invoice');
endif;

if($_POST['type'] == 'lab'):
	$invoice = $model->getIncrementEntry('lab_invoice');
endif;
*/
$tcpcs = $tccarat = $tccost = $tcprice = $tcamount = 0.00;
$tspcs = $tscarat = $tscost = $tsprice = $tsamount = 0.00;
$tmpcs = $tmcarat = $tmcost = $tmprice = $tmamount = 0.00;
?>

<form class="form-horizontal" id="memo-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="memoToReturn" />
<input type="hidden" name="memo_id" value="<?php echo $lid; ?>" />
<input type="hidden" name="main_stone" value="<?php echo $data['main_stone']; ?>" />
<input type="hidden" name="collet_stone" value="<?php echo $data['collet_stone']; ?>" />
<input type="hidden" name="side_stone" value="<?php echo $data['side_stone']; ?>" />
<div class="col-xs-12 col-sm-12 jewelry-job jewelryMaking">
	<label class="col-sm-2 control-label no-padding-right green" for="form-field-4">Company : <?php echo $party[$data['party']]; ?></label>
	<label class="col-sm-2 control-label no-padding-right blue" for="form-field-4">M Maker : <?php echo $mmaker[$data['memo_maker']]; ?></label>
	<label class="col-sm-2 control-label no-padding-right green" for="form-field-4">Design : <?php echo $data['jew_design']; ?></label>
	<label class="col-sm-2 control-label no-padding-right blue" for="form-field-4">Jewelry : <?php echo $jewType[$data['jew_type']]; ?></label>
	<label class="col-sm-2 control-label no-padding-right green" for="form-field-4">Date : <?php echo $data['date'] ?></label>
	<div style="clear:both"></div>
<?php if(!empty($collet_stone )): ?>
<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Collet Stone</h3>
	<div class="subform colletstone invenotry-cgrid main-grid">
		<div class="divTable " style="width:2350px" >
			<div class="divTableHeading">
				
				<div class="divTableCell" style="width:50px !important">
				</div>								
				<div class="divTableCell">SKU</div>
				<div class="divTableCell">PCS</div>
				<div class="divTableCell">Carat</div>
				<div class="divTableCell">Shape</div>
				<div class="divTableCell">Clarity</div>
				<div class="divTableCell">Color</div>
				<div class="divTableCell">Price</div>
				<div class="divTableCell">Amount</div>
				<div  class="divTableCell">G Color</div>
				<div  class="divTableCell">G Carat</div>
				<div  class="divTableCell">Gross WT</div>
				<div  class="divTableCell">NET WT</div>
				<div  class="divTableCell">PG WT</div>
				<div  class="divTableCell">Rate</div>
				<div  class="divTableCell">Amount</div>
				<div  class="divTableCell">Other Code</div>
				<div  class="divTableCell">Other Amount</div>
				<div  class="divTableCell">Labour Rate</div>
				<div  class="divTableCell">Labour Amount</div>
				<div  class="divTableCell">Total Amount</div>
				<div  class="divTableCell">Cost Amount</div>
				
			</div>	
				
			
			<div class="divTableBody" >
				
				
				<?php 
				$i = 0;
				foreach($collet_stone as $value):
				$i++;
				$class ="";
				//$value = $imodel->getDetail($id);
	
				?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
					<?php echo $i;?>
					</div>
						
					<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell a-right"><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell a-right "><?php echo $value['price'];?></div>			
					<div class="divTableCell a-right"><?php echo $value['amount'];?></div>			
					<div class="divTableCell"><?php echo $value['collet_color'];?></div>		
					<div class="divTableCell a-right"><?php echo $value['collet_kt'];?></div>			
					<div class="divTableCell a-right"><?php echo $value['gross_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['net_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['pg_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['collet_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $value['collet_amount'];?></div>
					<div class="divTableCell "><?php echo $value['other_code'];?></div>
					<div class="divTableCell a-right"><?php echo $value['other_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $value['labour_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $value['labour_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $value['total_amount'];?></div>			<div class="divTableCell a-right amount1"><?php echo $value['total_amount_cost'];?></div>				
					
				</div>
				<?php 
			$tcpcs = (int)$tcpcs +  (int)$value['pcs'];
			$tccarat = (float)$tccarat +  (float)$value['carat'];			
			$tcprice = (float)$tcprice +  (float)$value['price'];
			$tcamount = (float)$tcamount +  (float)$value['total_amount_cost'];
			endforeach; ?>	
			</div>
			</div>	
		</div>
	</div>
<?php endif; ?>

<?php if(!empty($main_stone)): ?>
<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Main Stone</h3>
	<div class="subform colletstone invenotry-cgrid main-grid">
		<div class="divTable " style="width:2230px" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
				</div>											
				<div class="divTableCell">SKU</div>
				<div class="divTableCell">PCS</div>
				<div class="divTableCell">Carat</div>
				<div class="divTableCell">Shape</div>
				<div class="divTableCell">Clarity</div>
				<div class="divTableCell">Color</div>
				<div class="divTableCell">Price</div>
				<div class="divTableCell">Amount</div>
				<div  class="divTableCell">G Color</div>
				<div  class="divTableCell">G Carat</div>
				<div  class="divTableCell">Gross WT</div>
				<div  class="divTableCell">NET WT</div>
				<div  class="divTableCell">PG WT</div>
				<div  class="divTableCell">Rate</div>
				<div  class="divTableCell">Amount</div>
				<div  class="divTableCell">Other Code</div>
				<div  class="divTableCell">Other Amount</div>
				<div  class="divTableCell">Labour Rate</div>
				<div  class="divTableCell">Labour Amount</div>
				<div  class="divTableCell">Total Amount</div>
				
			</div>	
			<div class="divTableBody" >
				<?php 
					$i = 0 ;
					foreach($main_stone as $value):
					$i++;
					$class ="";
					//$value = $imodel->getDetail($id);
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
					<?php echo $i;?>
					</div>
						
					<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell a-right"><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell a-right "><?php echo $value['price'];?></div>			
					<div class="divTableCell a-right amount1"><?php echo $value['amount'];?></div>			
					<div class="divTableCell"><?php echo $value['collet_color'];?></div>		
					<div class="divTableCell a-right"><?php echo $value['collet_kt'];?></div>			
					<div class="divTableCell a-right"><?php echo $value['gross_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['net_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['pg_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['collet_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $value['collet_amount'];?></div>
					<div class="divTableCell "><?php echo $value['other_code'];?></div>
					<div class="divTableCell a-right"><?php echo $value['other_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $value['labour_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $value['labour_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $value['total_amount'];?></div>				
					
				</div>
				<?php 
			$tmpcs = (int)$tmpcs +  (int)$value['pcs'];
			$tmcarat = (float)$tmcarat +  (float)$value['carat'];
			$tmprice = (float)$tmprice +  (float)$value['price'];
			$tmamount = (float)$tmamount +  (float)$value['amount'];
			endforeach; ?>	
			<div class="mscarat" style="display:none;"><?php echo $tmcarat; ?></div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php if(!empty($side_stone)): ?>
<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Side Stone</h3>
	<div class="subform colletstone invenotry-cgrid main-grid">
		<div class="divTable " style="width:1000px" >
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
					$i= 0;
					foreach($side_stone as $value):
					$i++;
					$id=$value['id'];
					$class = "infobox-grey infobox-dark";
					if($value['outward_parent'] == 0)
					{
						$sku = $value['sku'];
					}
					else
					{
						$parentData = $jhelper->getSideProductDetail($value['outward_parent']);					
						$sku = $parentData['sku'];
					}
					//$value = $imodel->getDetail($id);
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?>
					</div>
						
					<div class="divTableCell  "><?php echo $sku;?></div>
					<div class="divTableCell"><input class=" col-sm-12  a-right"  name="srecord[<?php echo $id ?>][pcs]" type="text" value="<?php echo (isset($value['pcs']))?$value['pcs']:'&nbsp;'?>" ></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right tcarat"  name="srecord[<?php echo $id ?>][carat]" onChange="calAmount(<?php echo $id ?>)" id="pcarat-<?php echo $id ?>" type="text" value="<?php echo (isset($value['carat']))?$value['carat']:'&nbsp;'?>"></div>	<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell  "><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell  a-right"><input class=" col-sm-12 a-right"  name="srecord[<?php echo $id ?>][price]" id="price-<?php echo $id ?>" onBlur="calAmount(<?php echo $id ?>)" value="<?php echo ($value['sell_price'] ==0)?$value['price']:$value['sell_price'] ?>"  type="text"> <input name="srecord[<?php echo $id ?>][amount]" id="amount1-<?php echo $id ?>" type="hidden" value="<?php echo (isset($value['amount']))?$value['amount']:'&nbsp;'?>"></div>					
					<div class="divTableCell amount1 a-right" id="amount2-<?php echo $id ?>"><?php echo ($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'] ?></div>					
					
				</div>
				<?php 
			$tspcs = (int)$tspcs +  (int)$value['pcs'];
			$tscarat = (float)$tscarat +  (float)$value['carat'];
			$tsprice = (float)$tsprice +  (float)$value['price'];
			$tsamount = (float)$tsamount +  (float)$value['amount'];
			endforeach; ?>	
			</div>
		</div>
	</div>
</div>
</div>
<?php endif; ?>
<p><b>Total Stone Carat : </b> <span id="tscts"><?php echo $tccarat+$tscarat+$tmcarat; ?></span> &nbsp;&nbsp; |  &nbsp;&nbsp; <b>Total Stone Amount : </b> <span id="total_amount"><?php echo $tsamount+$tcamount+$tmamount; ?></span> </p>
<hr>
<div style="clear:both"></div>
<p><b>Extra Side Stone Detail: </b></p>
<div class="form-group col-sm-2 center">
				<label class="col-sm-4 control-label center no-padding-right" for="form-field-4">Pcs</label>
				<div class="col-sm-8 center">
					<input class="input-sm col-sm-12" name="side_pcs" value="<?php echo $data['side_pcs']?>" type="text" style="margin-right:5px">					
				</div>
	</div>
	<div class="form-group col-sm-2 center">
				<label class="col-sm-4 control-label center no-padding-right" for="form-field-4">Carat</label>
				<div class="col-sm-8 center">
					<input class="input-sm col-sm-12" name="side_carat" onBlur="calculateCarat()" id="side_carat" value="<?php echo $data['side_carat']?>" type="text" style="margin-right:5px">					
				</div>
	</div>
	<div class="form-group col-sm-2 center">
				<label class="col-sm-4 control-label center no-padding-right" for="form-field-4">Price</label>
				<div class="col-sm-8 center">
					<input class="input-sm col-sm-12" onBlur="calculateCarat()" id="side_price" name="side_price" value="<?php echo $data['side_price']?>" type="text" style="margin-right:5px">					
				</div>
	</div>
	<div class="form-group col-sm-2 center">
				<label class="col-sm-4 control-label center no-padding-right" for="form-field-4">Amount</label>
				<div class="col-sm-8 center">
					<input class="input-sm col-sm-12" id="side_amount" name="side_amount" value="<?php echo $data['side_amount']?>" type="text" style="margin-right:5px">					
				</div>
	</div>
	<div style="clear:both"></div>
	<hr>
	<div class="form-group col-sm-3">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Color <span class="required">*</span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="goldcolor" name="gold_color">
				<option value="">Gold Color</option>
				<?php 
				foreach($goldColor as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['gold_color'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Carat <span class="required">*</span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="gold" name="gold" onBlur="calculateReturnJewTotalAmount()" onChange="calculateReturnJewTotalAmount()">
				<option value="">Gold Carat</option>
				<?php 
				foreach($gold as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['gold'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Gross WT <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="gross_weight" onBlur="calculateReturnJewTotalAmount()" onChange="calculateReturnJewTotalAmount()" value="<?php echo $data['gross_weight']?>" name="gross_weight" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">NET WT <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="net_weight" value="<?php echo $data['net_weight']?>" name="net_weight" placeholder="" type="text">
		</div>
	</div>
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">PG WT <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="pg_weight" value="<?php echo $data['pg_weight']?>" name="pg_weight" placeholder="" type="text">
		</div>
	</div>
		<div style="clear:both"></div>
	<div class="form-group col-sm-3">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Metal<span class="required">*</span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="metal" name="metal">
				<option value="">Select Metal</option>
				<?php 
				foreach($metal as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['metal'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Rate <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="rate" onBlur="calculateReturnJewTotalAmount()" onChange="calculateReturnJewTotalAmount()" value="<?php echo $data['rate']?>" name="rate" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Amount <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="amount" readonly value="<?php echo $data['amount']?>" name="amount" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Other Code </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="other_code" value="<?php echo $data['other_code']?>" name="other_code" placeholder="" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Other Amount </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" onBlur="calculateReturnJewTotalAmount()" onChange="calculateReturnJewTotalAmount()"  id="other_amount" value="<?php echo $data['other_amount']?>" name="other_amount" placeholder="" type="text">
		</div>
	</div>
<!--	<div style="clear:both"></div> -->
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Labour Rate</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" onBlur="calculateReturnJewTotalAmount()" onChange="calculateReturnJewTotalAmount()" id="labour_rate" value="<?php echo $data['labour_rate']?>" name="labour_rate" placeholder="" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Labour Amount</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" readonly id="labour_amount" value="<?php echo $data['labour_amount']?>" name="labour_amount" placeholder="" type="text">
		</div>
	</div>
		
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Total Amount </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" readonly id="ftotal_amount" value="<?php echo $data['total_amount']?>" name="total_amount" placeholder="" type="text">
		</div>
	</div>
	<div style="clear:both"></div>
	<div class="form-group col-sm-2 center">
				<label class="col-sm-4 control-label center no-padding-right" for="form-field-4">Date</label>
				<div class="col-sm-8 center">
					<input class="input-sm col-sm-12" id="date" name="return_date" value="<?php echo $data['return_date']?>" type="text" style="margin-right:5px">					
				</div>
	</div>

	<div class="form-group col-sm-3 center">
				<label class="col-sm-5 control-label center no-padding-right" for="form-field-4">Remark</label>
				<div class="col-sm-7 center">
					<input class="input-sm col-sm-12" id="remark" name="remark" value="<?php echo $data['remark']?>" type="text" style="margin-right:5px">					
				</div>
	</div>		
	<div class="form-group col-sm-3 center">
		<label class="col-sm-4 control-label center no-padding-right" for="form-field-4">Jewelry SKU <span class="required">*</span></label>
		<div class="col-sm-8 center">
			<input class="input-sm col-sm-12"  name="sku" placeholder="SKU" value="<?php echo '' ?>" type="text" onBlur="checkSku(this.value)" style="margin-right:5px">					
		</div>
	</div>		
</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">
	
	$( "#date" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});
	$("#minvoicedate").on("change", function()
	{
		var selectedDate = $("#minvoicedate").val();

		$("#mdate").val(selectedDate);
		
		// var finalDate = selectedDate.substring(6,11)+"/"+selectedDate.substring(3,6)+"/"+selectedDate.substring(0,2);
	    var date = new Date(selectedDate);

		
		days = parseInt($("#mterms").val(), 10);
		if(days == '')
		{
			$("#mduedate").val(selectedDate);
			return;
		}
	    if(!isNaN(date.getTime())){
			date.setDate(date.getDate() + days);
			if(date.toInputFormat() != "NaN/NaN/NaN")
			{
				$("#mduedate").val(date.toInputFormat());
			}
			else
			{
				$("#mduedate").val('');
			}
		} else {
			alert("Invalid Date");  
		}
		
	});	

function calDueDate()
	{
		   var selectedDate = $("#minvoicedate").val();
		
		  // var finalDate = selectedDate.substring(6,11)+"/"+selectedDate.substring(3,6)+"/"+selectedDate.substring(0,2);
		   var date = new Date(selectedDate);

			days = parseInt($("#mterms").val(), 10);
		   
			if(!isNaN(date.getTime())){
				date.setDate(date.getDate() + days);

				if(date.toInputFormat() != "NaN/NaN/NaN")
				{
					$("#mduedate").val(date.toInputFormat());
				}
				else
				{
					$("#mduedate").val('');
				}
			} else {
				alert("Invalid Date");  
			}
		
	}	
function saveMemoForm()
{
	//jQuery('#btn-save').attr('disabled',true);
	var data =  $("#memo-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $jewelryUrl.'jobwork/jobworkController.php'?>', 
		type: 'POST',
		data: data,		
		success: function(result)
			{		
				if(result != "")
				{
					//jQuery('#dialog-box-container').show();
					//$('#dialog-box-container').html('<div class="box-container-memo" >'+result+'</div>');									
					jQuery('#please-wait').hide();
					var obj = jQuery.parseJSON(result);
					alert(obj.message);
					if(obj.status)
					{
						$('#edit-box-container').hide();			
						$('#edit-box-container .box-container').html('');
						loadMemo(0);
					}
					jQuery('#btn-save').attr('disabled',false);
				}
			}
	});	
}
function checkSku(sku)
{
	//jQuery('#btn-save').attr('disabled',true);
	
		
		jQuery.ajax({
		url: '<?php echo $jewelryUrl.'jewelry/jewelryController.php'?>', 
		type: 'POST',
		data: {sku:sku,fn:'checkSku'},		
		success: function(result)
			{		
				if(result != "")
				{
					var obj = jQuery.parseJSON(result);
					
					if(obj.status == 0)
					{
						alert(obj.message);						
					}
					
				}
			}
	});	
}
function totalcarat()
	{
		var total =0.0;
		
		$(".divTableRow .divTableCell .tcarat" ).each(function( index ) 
		{
			
			  var amount = parseFloat($( this ).val());
				  if(!isNaN(amount))
				  {
					total = amount + total;
				  }	 
		});

			if(!isNaN(total))
			{
				var md = parseFloat($('.mscarat').html());
				if(!isNaN(md))
				{
					total = md + total;
				}
				$('#tscts').html(total.toFixed(2)); 
				
			}
		
	}
function calAmount(rid)
{
		
		var price = parseFloat($('#price-'+rid).val());
		var pcarat = parseFloat($('#pcarat-'+rid).val());
		var total  = parseFloat(price * pcarat );
		
		if(!isNaN(total))
		{
			$("#amount1-"+rid ).val(total.toFixed(2));
			$("#amount2-"+rid ).html(total.toFixed(2));
			$("#amount-"+rid ).addClass('skip');
		}
		else
		{
			$("#amount1-"+rid ).val(0);
			$("#amount2-"+rid ).val(0);
		}
		totalamount();
		totalcarat();
		calculateCarat();
}
function totalamount()
{
	
	var total =0.0;
		
		$(".divTableRow .divTableCell.amount1" ).each(function( index ) 
		{
			if(!$( this ).hasClass('skip'))
			{
				
			  var amount = parseFloat($( this ).html());
			
				  if(!isNaN(amount))
				  {
					total = amount + total;
				  }	 
			}	 
		});
			
			if(!isNaN(total))
			{
				//$('#due_amount').val(total.toFixed(2)); 
				//$('#paid_amount').val(total.toFixed(2)); 
				$('#total_amount').html(total.toFixed(2)); 
				
			}
	/* var total =0.0;
	var amount = parseFloat(jQuery('#total_amount').val());
	var carat_amount = parseFloat(jQuery('#gold_amount').val());
	var labour_fee = parseFloat(jQuery('#labour_fee').val());
	var roadiam_cost = parseFloat(jQuery('#roadiam_cost').val());
	var handling_charge = parseFloat(jQuery('#handling_charge').val());
	var gst = parseFloat(jQuery('#gst').val());
	
	if(isNaN(gst))
	{
		gst = 0;
	}
	if(isNaN(labour_fee))
	{
		labour_fee = 0;
	}
	if(isNaN(roadiam_cost))
	{
		roadiam_cost = 0;
	}
	if(isNaN(carat_amount))
	{
		carat_amount = 0;
	}
	if(isNaN(amount))
	{
		amount = 0;
	}
	if(isNaN(handling_charge))
	{
		handling_charge = 0;
	}
	
	total = amount + carat_amount + labour_fee + roadiam_cost + handling_charge;
	
	if(!isNaN(total))
	{
		$('#cost_price').val(total.toFixed(2));		
	}
	if(!isNaN(gst))
	{
		var te = total + gst;
		$('#final_cost').val(te.toFixed(2));		
	} */
	
}
function mcalAmount(rid)
{
	
	var price = parseFloat($('#mprice-'+rid).val());
	var pcarat = parseFloat($('#mcarat-'+rid).html());
	var total  = parseFloat(price * pcarat);
	if(!isNaN(total))
	{
		$("#mamount1-"+rid ).val(total.toFixed(2));
		$("#mamount-"+rid ).html(total.toFixed(2));
	}
	else
	{
		$("#mamount1-"+rid).val(0);
		$("#mamount-"+rid).val(0);
	}
}
function closePartyPopup()
{
	jQuery('#dialog-box-container1').hide();
}

function calculateGold()
{
	var gram = parseFloat(jQuery('#gold_gram').val());
	var price = parseFloat(jQuery('#gold_price').val());
	var total  = parseFloat(gram * price);
	
	if(!isNaN(total))
	{	
		jQuery('#gold_amount').val(Math.abs(total.toFixed(2)));
	}
	
	calculateCarat();
	totalamount();
}
function calculateCarat()
{
	var carat = parseFloat(jQuery('#side_carat').val());
	var price = parseFloat(jQuery('#side_price').val());
	var amount = price * carat;
	if(!isNaN(amount))
	{	
		jQuery('#side_amount').val(amount.toFixed(2));
	}
	calculateReturnJewTotalAmount();
}
function calculateReturnJewTotalAmount()
{
	var gross_weight = parseFloat(jQuery('#gross_weight').val());
	var carat = parseFloat(jQuery('#side_carat').val());
	var amount = parseFloat(jQuery('#side_amount').val());
	if(isNaN(carat))
	{carat = 0;}
	if(isNaN(amount))
	{amount = 0;}
	var total_carat = parseFloat(jQuery('#tscts').html());
	total_carat = total_carat + carat;
	var total_amount = parseFloat(jQuery('#total_amount').html());
	total_amount = total_amount + amount;
	var rate = parseFloat(jQuery('#rate').val());
	var other_amount = parseFloat(jQuery('#other_amount').val());
	var labour_rate = parseFloat(jQuery('#labour_rate').val());
	
	var gold = parseInt(jQuery('#gold').val());
	
	if( gold >0)
	{
		if(isNaN(gross_weight))
		{gross_weight = 0;}		
		if(isNaN(total_carat))
		{total_carat = 0;}
		if(isNaN(total_amount))
		{total_amount = 0;}
		if(isNaN(rate))
		{rate = 0;}
		if(isNaN(other_amount))
		{other_amount = 0;}	
		if(isNaN(labour_rate))
		{labour_rate = 0;}
		
		var gold_per = 0;
		
		switch(gold) {
			case 22:
				gold_per = 92;
				break;
			case 18:
				gold_per=76;
				break;
			case 14:
				gold_per=59;
				break;
			case 16:
				gold_per=70;
				break;	
			case 20:
				gold_per=84;
				break;	
		} 

		
		var net_gram = parseFloat(jQuery('#net_weight').val());/* parseFloat(gross_weight - (total_carat/5)); */
		if(!isNaN(net_gram))
		{
			//jQuery('#net_weight').val(Math.abs(net_gram.toFixed(3)));
			
			var pg_weight = parseFloat( (net_gram * gold_per)/ 100 );
			if(!isNaN(pg_weight))
			{
				jQuery('#pg_weight').val(Math.abs(pg_weight.toFixed(3)));
			}	
		}
		var camount = parseFloat(net_gram * rate);
		if(!isNaN(camount))
		{
			jQuery('#amount').val(Math.abs(camount.toFixed(2)));
		}
		else
		{
			camount=0;
		}
		
		var labour_amount = parseFloat(gross_weight * labour_rate);
		if(!isNaN(labour_amount))
		{
			jQuery('#labour_amount').val(Math.abs(labour_amount.toFixed(2)));
		}
		else
		{
			labour_amount=0;
		}
		var famount =  parseFloat(total_amount + labour_amount + other_amount  + camount );
		
		if(!isNaN(famount))
		{	
			jQuery('#ftotal_amount').val(Math.abs(famount.toFixed(2)));
		}	
	}
	else
	{
		alert('Please Select Gold Carat');
	}	
	
	
}

</script>	
<style>	
.a-right{text-align:right;}
.divTableCell input {border:0 !important;}	
.form-group{margin-bottom:10px !important;}
</style>
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
$Allsku = $jhelper->getAllSkuOf('side');

$lid=0;
if(isset($_POST['id']))
{
	$lid = $_POST['id'];
}
$data =  $model->getData($lid);		


if(isset($_POST['id']))
{
	$collet_stone = ($data['collet_stone'] != "")?explode(",",$data['collet_stone']):array();
	$main_stone = ($data['main_stone'] != "")?explode(",",$data['main_stone']):array();
	$side_stone = ($data['side_stone'] != "")?explode(",",$data['side_stone']):array();
}
else
{
	$collet_stone = (isset($_POST['collet']))?$_POST['collet']:array(); 
	$main_stone = (isset($_POST['main']))?$_POST['main']:array(); 
	$side_stone = (isset($_POST['side']))?$_POST['side']:array(); 
}
$metal = array();
$metal['gold']  = 'Gold';
$metal['brass']  = 'Brass';
$metal['silver']  = 'Silver';
$metal['Pletinum'] = 'Pletinum';
$tccarat = $tmcarat = $tscarat = $tsamount = 0.00;
?>

<div class="page-header">							
	<h1 style="float:left">
		Send to Job Work						
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

//$entryno = $model->getIncrementEntry('job_no');


/*if($_POST['type'] == 'memo'):
	$invoice = $model->getIncrementEntry('memo_invoice');
endif;

if($_POST['type'] == 'lab'):
	$invoice = $model->getIncrementEntry('lab_invoice');
endif;
*/
?>

<form class="form-horizontal" id="job-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="sendToJob" />
<input type="hidden" name="job" value="jewelry" />

<input type="hidden" name="main_stone" value="<?php echo implode(",",$main_stone)?>">
<input type="hidden" name="collet_stone" value="<?php echo implode(",",$collet_stone) ?>">
<input type="hidden" name="side_stone" value="<?php echo implode(",",$side_stone) ?>">


<?php if(isset($_POST['id']))
{	
	//$entryno = $data['entryno'];
	//$invoice = $data['invoiceno'];	
?>
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
<?php } ?>




<div class="col-xs-12 col-sm-12 ">
	
		
	<div class="form-group col-sm-3">	
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Job Number <span class="required">*</span></label>
		<div class="col-sm-8">
			<select class="col-xs-12" id="ledger" name="entryno">
				<option value="">Select Job Number</option>
				<?php 
				foreach($entryno as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['entryno'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
		</div>
	</div>
	<!--<div class="form-group col-sm-5">
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
			<a href="javascript:void(0);" onclick="addParty()" style="margin-top:8px;margin-left:5px;float: left;" > Add Company</a>
		</div>
	</div>-->
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Date <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="date" value="<?php echo $data['date']?>" name="date" placeholder="" type="text">
		</div>
	</div>
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> D.Date <span class="required"></span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="duedate" value="<?php echo $data['duedate']?>" name="duedate" placeholder="" type="text">
		</div>
	</div>
	<!---- -->
	
	<div style="clear:both"></div>
	<div class="form-group col-sm-3">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Design <span class="required"></span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" value="<?php echo $data['jew_design']?>" name="jew_design" placeholder="Design" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Jewelry <span class="required"></span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="jew_type" name="jew_type">
				<option value="">Jewelry Type</option>
				<?php 
				foreach($jewType as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['jew_type'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	
	
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">M Maker <span class="required">*</span></label>
		<div class="col-sm-8">
			<select class="col-xs-12" id="jew_type" name="memo_maker">
				<option value="">Memo Maker</option>
				<?php 
				foreach($mmaker as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['memo_maker'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	
	<br>
	
	<div style="clear:both"></div>
	
</div>

<div class="col-xs-12 col-sm-12 jewelry-job">
<?php if(!empty($collet_stone )): ?>
<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Selected Collet</h3>
	<div class="subform colletstone invenotry-cgrid main-grid">
		<div class="divTable " style="width:2260" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>					
				<div class="divTableCell">SKU</div>
				<div class="divTableCell">PCS</div>
				<div class="divTableCell">Carat</div>
				<div class="divTableCell">Shape</div>
				<div class="divTableCell">Clarity</div>
				<div class="divTableCell">Metal</div>
				<div class="divTableCell">Color</div>
				<div class="divTableCell">Price</div>
				<div class="divTableCell">Amount</div>
				<div class="divTableCell">Lab</div>
				<div class="divTableCell">IGI Code</div>
				<div class="divTableCell">IGI Color</div>
				<div class="divTableCell">IGI Clarity</div>
				<div class="divTableCell">IGI Amount</div>
				<div class="divTableCell">Gross WT</div>
				<div class="divTableCell">Net Wt</div>
				<div class="divTableCell">PG WT</div>
				<div class="divTableCell">Rate</div>
				<div class="divTableCell">Amount</div>
				<div class="divTableCell">Total</div>
				
			</div>	
			<div class="divTableBody" >
				
				
				<?php 
				$tccarat = $tamount = $tgross = $tnet = $tpg = 0.00;
				$i = 0;
				foreach($collet_stone as $id):
				$i++;
				$class ="";
				$value = $imodel->getDetail($id);
				
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
					<div class="divTableCell  "><?php echo $value['lab'];?></div>
					<div class="divTableCell  "><?php echo $value['igi_code'];?></div>			
					<div class="divTableCell  "><?php echo $value['igi_color'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['igi_clarity'];?></div>		
					<div class="divTableCell a-right "><?php echo $value['igi_amount'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['gross_weight'];?></div>	
					<div class="divTableCell  a-right"><?php echo $value['net_weight'];?></div>	
					<div class="divTableCell  a-right"><?php echo $value['pg_weight'];?></div>	
					<div class="divTableCell  a-right"><?php echo $value['collet_rate'];?></div>	
					<div class="divTableCell  a-right"><?php echo $value['collet_amount'];?></div>	
					<div class="divTableCell  a-right"><?php echo $value['total_amount'];?></div>	
				</div>
				<?php 
			
			$tccarat = (float)$tccarat +  (float)$value['carat'];
			$tgross = (float)$tgross +  (float)$value['gross_weight'];
			$tnet = (float)$tnet +  (float)$value['net_weight'];
			$tpg = (float)$tpg +  (float)$value['pg_weight'];
			$tamount = (float)$tamount +  (float)$value['total_amount'];
			
			endforeach; ?>	
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if(!empty($main_stone)): ?>
<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Selected Main Stone</h3>
	<div class="subform mainstone invenotry-cgrid main-grid">
		<div class="divTable" style="width: 2885px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>					
				<div class="divTableCell">SKU</div>
				<div class="divTableCell">PCS</div>
				<div class="divTableCell">Carat</div>
				<div class="divTableCell">Shape</div>
				<div class="divTableCell">Clarity</div>
				<div class="divTableCell">Color</div>
				<div class="divTableCell">Price</div>
				<div class="divTableCell">Amount</div>
				<div  class="divTableCell">Lab</div>
				<div  class="divTableCell">IGI Code</div>
				<div  class="divTableCell">IGI Color</div>
				<div  class="divTableCell">IGI Clarity</div>
				<div  class="divTableCell">IGI Amount</div>
				<div  class="divTableCell">Color</div>
				<div  class="divTableCell">Carat</div>
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
					$tpcs = $tmcarat = $tcost = $tprice = $tamount = 0.00;
					$i = 0 ;
					foreach($main_stone as $id):
					$i++;
					$class ="infobox-grey infobox-dark";
					$mvalue = $imodel->getDetail($id);
					/*  echo "<pre>";
					print_r($mvalue);
					exit;  */
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
					<div class="divTableCell  a-right"><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $id ?>][price]" id="mprice-<?php echo $i ?>" onChange="mcalAmount(<?php echo $i ?>)" onBlur="mcalAmount(<?php echo $i ?>)" value="<?php echo ($mvalue['sell_price'] ==0)?$mvalue['price']:$mvalue['sell_price'] ?>"  type="text"><input name="mrecord[<?php echo $id ?>][amount]" id="mamount1-<?php echo $i ?>" type="hidden" value="<?php echo (isset($mvalue['amount']))?$mvalue['amount']:'&nbsp;'?>"></div>			
					<div class="divTableCell  a-right" id="total_amount-<?php echo $i ?>"><?php echo ($mvalue['sell_amount'] ==0)?$mvalue['amount']:$mvalue['sell_amount'] ?></div>
					<div class="divTableCell  "><?php echo $mvalue['lab'];?></div>
					<div class="divTableCell  "><?php echo $mvalue['igi_code'];?></div>			
					<div class="divTableCell  "><?php echo $mvalue['igi_color'];?></div>			
					<div class="divTableCell a-right "><?php echo $mvalue['igi_clarity'];?></div>			
					<div class="divTableCell a-right "><?php echo $mvalue['igi_amount'];?></div>		
					<div class="divTableCell">
					<select class="col-xs-12" id="goldcolor-<?php echo $i?>" name="record[<?php echo $i ?>][gold_color]">
							<option value="">Gold Color</option>
							<?php 
							foreach($goldColor as $key => $value):
							?>						
							<option value="<?php echo $key?>" <?php echo ($key == $mvalue['collet_color'])?"selected":""; ?> ><?php echo $value?></option>
							<?php endforeach; ?>
					</select></div>
					<div class="divTableCell"><select class="col-xs-12" id="gold-<?php echo $i?>" name="record[<?php echo $i ?>][gold]" onBlur="calculateTotalAmountColl(<?php echo $i ?>)" onChange="calculateTotalAmountColl(<?php echo $i ?>)">
							<option value="">Gold Carat</option>
							<?php 
							foreach($gold as $key => $value):
							?>						
							<option value="<?php echo $key?>" <?php echo ($key == $mvalue['collet_kt'])?"selected":""; ?> ><?php echo $value?></option>
							<?php endforeach; ?>
			
							</select></div>
					<div class="divTableCell"><input class="input-sm col-sm-12 a-right" id="gross_weight-<?php echo $i ?>" onBlur="calculateTotalAmountColl(<?php echo $i ?>)" onChange="calculateTotalAmountColl(<?php echo $i ?>)" value="<?php echo $mvalue['gross_weight'] ?>" name="record[<?php echo $i ?>][gross_weight]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class="input-sm col-sm-12 a-right" id="net_weight-<?php echo $i ?>" value="<?php echo $mvalue['net_weight']?>" name="record[<?php echo $i ?>][net_weight]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class="input-sm col-sm-12 a-right" id="pg_weight-<?php echo $i ?>" value="<?php echo $mvalue['pg_weight']?>" name="record[<?php echo $i ?>][pg_weight]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class="input-sm col-sm-12 a-right" id="rate-<?php echo $i ?>" onBlur="calculateTotalAmountColl(<?php echo $i ?>)" onChange="calculateTotalAmountColl(<?php echo $i ?>)" value="<?php echo $mvalue['collet_rate']?>" name="record[<?php echo $i ?>][rate]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class="input-sm col-sm-12 a-right" id="amount-<?php echo $i ?>" readonly value="<?php echo $mvalue['collet_amount']?>" name="record[<?php echo $i ?>][amount]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class="input-sm col-sm-12 a-right" id="other_code-<?php echo $i ?>" value="<?php echo $mvalue['other_code']?>" name="record[<?php echo $i ?>][other_code]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class="input-sm col-sm-12 a-right" id="other_amount-<?php echo $i ?>" onBlur="calculateTotalAmountColl(<?php echo $i ?>)" onChange="calculateTotalAmountColl(<?php echo $i ?>)" value="<?php echo $mvalue['other_amount']?>" name="record[<?php echo $i ?>][other_amount]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class="input-sm col-sm-12 a-right" id="labour_rate-<?php echo $i ?>"  onBlur="calculateTotalAmountColl(<?php echo $i ?>)" onChange="calculateTotalAmountColl(<?php echo $i ?>)" value="<?php echo $mvalue['labour_rate']?>" name="record[<?php echo $i ?>][labour_rate]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class="input-sm col-sm-12 a-right" id="labour_amount-<?php echo $i ?>" readonly value="<?php echo $mvalue['labour_amount']?>" name="record[<?php echo $i ?>][labour_amount]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class="input-sm col-sm-12 a-right" id="ftotal_amount-<?php echo $i ?>"  readonly value="<?php echo $mvalue['total_amount']?>" name="record[<?php echo $i ?>][total_amount]" placeholder="" type="text"></div>
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
<div class="form-group col-sm-12 ">
	<h3>Side Stone <?php if(!isset($_POST['id'])): ?>
<a id="addNewRow" onClick="outwardNewRow()"><i class="fa fa-plus"></i></a><?php endif; ?></h3>
	<div class="subform sidestone invenotry-cgrid main-grid jewside">
		<div class="divTable " style="width:922px;" >
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
				
				
			</div>	
			<div class="divTableBody" >
				
				
				<?php 
					$tscarat = 0.00;
					$i= 0;
					foreach($side_stone as $id):
					$i++;
					$class = "infobox-grey infobox-dark";
					$value = $imodel->getDetail($id,'loose');
					if($value['outward_parent'] == 0)
					{
						$sku = $value['sku'];
					}
					else
					{
						$parentData = $jhelper->getSideProductDetail($value['outward_parent']);					
						$sku = $parentData['sku'];
					}
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<input type="hidden" id="srecord-<?php echo $i?>" name="srecord[<?php echo $i?>][id]" value="<?php echo $value['id']?>" /><?php echo $i;?>
					</div>
					<div class="divTableCell  "><?php echo $sku;?></div>
					<div class="divTableCell"><input class=" col-sm-12  a-right"  name="srecord[<?php echo $i ?>][pcs]" type="text" value="<?php echo (isset($value['pcs']))?$value['pcs']:'&nbsp;'?>" ></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right tcarat"  name="srecord[<?php echo $i ?>][carat]" onChange="calAmount(<?php echo $i ?>)" id="pcarat-<?php echo $i ?>" type="text" value="<?php echo (isset($value['carat']))?$value['carat']:'&nbsp;'?>"></div>	
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell  "><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell  a-right"><input class=" col-sm-12 a-right"  name="srecord[<?php echo $i ?>][price]" id="price-<?php echo $i ?>" onBlur="calAmount(<?php echo $i ?>)" value="<?php echo ($value['sell_price'] ==0)?$value['price']:$value['sell_price'] ?>"  type="text"> <input name="srecord[<?php echo $i ?>][amount]" id="amount1-<?php echo $i ?>" type="hidden" value="<?php echo (isset($value['amount']))?$value['amount']:'&nbsp;'?>"></div>					
					<div class="divTableCell amount1 a-right" id="amount2-<?php echo $i ?>"><?php echo ($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'] ?></div>					
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
<p> <b>Collet Carat : </b> <span id="tccts"><?php echo $tccarat; ?></span> &nbsp;&nbsp; |  &nbsp;&nbsp; <b>Main Stone Carat : </b> <span id="tmcts"><?php echo $tmcarat; ?></span> &nbsp;&nbsp; |  &nbsp;&nbsp; <b>Side Stone Carat : </b> <span id="tscts"><?php echo $tscarat; ?></span> &nbsp;&nbsp; |  &nbsp;&nbsp; <b>Side Stone Amount : </b> <span id="total_amount"><?php echo $tsamount; ?></span> </p>
<hr>
<div style="clear:both"></div>

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
			<select class="col-xs-10" id="gold" name="gold" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()">
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
			<input class="input-sm col-sm-12 a-right" id="gross_weight" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()" value="<?php echo $data['gross_weight']?>" name="gross_weight" placeholder="" type="text">
		</div>
	</div>
	
<!--	<div style="clear:both"></div>-->
	
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
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Metal <span class="required">*</span></label>
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
			<input class="input-sm col-sm-12 a-right" id="rate" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()" value="<?php echo $data['rate']?>" name="rate" placeholder="" type="text">
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
	<div style="clear:both"></div>
	
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Other Amount </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()"  id="other_amount" value="<?php echo $data['other_amount']?>" name="other_amount" placeholder="" type="text">
		</div>
	</div>
<!--	<div style="clear:both"></div>-->
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Labour Rate</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()" id="labour_rate" value="<?php echo $data['labour_rate']?>" name="labour_rate" placeholder="" type="text">
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

	
	
</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">
var suggestions;
				var n = <?php echo count($side_stone); ?>;
			jQuery(function($) {
				
			
			suggestions = new Array(			 
			  <?php foreach($Allsku as $k=>$v):?>
			  '<?php echo $v ?>',
			  <?php endforeach;?>''
			  );
			 
			 $(".jewside .divTableCell .stone").bind("keypress", function(e)
			 {
				 var code = (e.keyCode ? e.keyCode : e.which);
				if (code == 13) 
				{ //Enter keycode                        
					e.preventDefault();
					var attr = jQuery(this).attr('rid');
					 
					if (typeof attr !== typeof undefined && attr !== false) 
					{
						var rid = jQuery(this).attr('rid');
						var sku = jQuery(this).val();
						
						loadDataBySku(rid,sku)
						
					}
				}
			});
			 
			 jQuery( ".divTableCell .stone" ).autocomplete({
					source: suggestions
				});
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

function calculateTotalAmount()
{
	var gross_weight = parseFloat(jQuery('#gross_weight').val());
	var total_carat = parseFloat(jQuery('#tscts').html());
	var total_amount = parseFloat(jQuery('#total_amount').html());
	
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

		
		var net_gram = parseFloat(jQuery('#net_weight').val());/* parseFloat(gross_weight - (total_carat/5)) */
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
function calculateTotalAmountColl(id)
{
	var gross_weight = parseFloat(jQuery('#gross_weight-'+id).val());
	var total_carat = parseFloat(jQuery('#total_carat-'+id).html());
	var total_amount = parseFloat(jQuery('#total_amount-'+id).html());
	
	var rate = parseFloat(jQuery('#rate-'+id).val());
	var other_amount = parseFloat(jQuery('#other_amount-'+id).val());
	var labour_rate = parseFloat(jQuery('#labour_rate-'+id).val());
	
	var gold = parseInt(jQuery('#gold-'+id).val());
	
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

		
		var net_gram = parseFloat(jQuery('#net_weight-'+id).val());/* parseFloat(gross_weight - (total_carat/5)); */
		if(!isNaN(net_gram))
		{
			//jQuery('#net_weight-'+id).val(Math.abs(net_gram.toFixed(3)));
			
			var pg_weight = parseFloat( (net_gram * gold_per)/ 100 );
			if(!isNaN(pg_weight))
			{
				jQuery('#pg_weight-'+id).val(Math.abs(pg_weight.toFixed(3)));
			}	
		}
		var camount = parseFloat(net_gram * rate);
		if(!isNaN(camount))
		{
			jQuery('#amount-'+id).val(Math.abs(camount.toFixed(2)));
		}
		else
		{
			camount=0;
		}
		
		var labour_amount = parseFloat(gross_weight * labour_rate);
		if(!isNaN(labour_amount))
		{
			jQuery('#labour_amount-'+id).val(Math.abs(labour_amount.toFixed(2)));
		}
		else
		{
			labour_amount=0;
		}
		var famount =  parseFloat(total_amount + labour_amount + other_amount  + camount );
		
		if(!isNaN(famount))
		{	
			jQuery('#ftotal_amount-'+id).val(Math.abs(famount.toFixed(2)));
		}	
	}
	else
	{
		alert('Please Select Gold Carat');
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
				$('#tscts').html(total.toFixed(2)); 
				
			}
		
	}
	function mcalAmount(rid)
	{
		
		var price = parseFloat($('#mprice-'+rid).val());
		var pcarat = parseFloat($('#total_carat-'+rid).html());
		var total  = parseFloat(price * pcarat);
		if(!isNaN(total))
		{
			$("#mamount1-"+rid ).val(total.toFixed(2));
			$("#total_amount-"+rid ).html(total.toFixed(2));
		}
		else
		{
			$("#mamount1-"+rid).val(0);
			$("#total_amount-"+rid).val(0);
		}
		calculateTotalAmountColl(rid);
	}
	function outwardNewRow()
		{
				
				var total = $('.jewside .divTableBody .divTableRow').length;
				n=n+1;
				//alert(n);
				var row = '<div class="divTableRow " id="rowid-'+n+'">';
				row += '<div class="divTableCell" style="width:50px !important">'+n+'</div>';
				row += '<div class="divTableCell"><input class=" col-sm-12 stone" rid="'+n+'" id="sku-'+n+'" Placeholder="Enter SKU" type="text"></div>';
				row +='</div>';
				//n = n + 1;
				$('.jewside .divTableBody').append(row);
				
				
				$(".jewside .stone").bind("keypress", function(e)
				 {
					 var code = (e.keyCode ? e.keyCode : e.which);
					if (code == 13) 
					{ //Enter keycode                        
						e.preventDefault();
						
						 var attr = jQuery(this).attr('rid');
						 
						if (typeof attr !== typeof undefined && attr !== false) 
						{
							var rid = jQuery(this).attr('rid');
							var sku = jQuery(this).val();
							
							loadDataBySku(rid,sku);
							
						}
					}
				});
				
				jQuery( ".jewside .divTableCell .stone" ).autocomplete({source: suggestions});
		}
		function loadDataBySku(rid,sku)
		{
			
			var data = {'sku':sku,'rid':rid,'diamond':'side'};
			
			jQuery('#please-wait').show();
			
			jQuery.ajax({
				url: '<?php echo $daiUrl.'jewelry/inventory/importNewRowSide.php'; ?>',
				type: 'POST',
				data: data,		
				success: function(result)
					{		
						if(result != "")
						{
							//$('#memo-data').html(result);									
							jQuery('#please-wait').hide();
							
							if(result.trim() == 'no')
							{	
								alert('No Data found');
							}
							else
							{
								$('#rowid-'+rid).html(result);
								$('#rowid-'+rid).addClass('infobox-grey infobox-dark');
								$(".stone").bind("keypress", function(e)
								 {
									 var code = (e.keyCode ? e.keyCode : e.which);
									if (code == 13) 
									{ //Enter keycode                        
										e.preventDefault();
										 var attr = jQuery(this).attr('rid');
										 
										if (typeof attr !== typeof undefined && attr !== false) 
										{
											var rid = jQuery(this).attr('rid');
											var sku = jQuery(this).val();
											
											loadDataBySku(rid,sku)
											
										}
									}
								});
								jQuery( ".divTableCell .stone" ).autocomplete({
									source: suggestions
								});		
							}
						}
					}
			});
		
		}	
		function removeRow(id)
		{
			$('#rowid-'+id).remove();	
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
#addNewRow {
	border: 1px solid;
	border-radius: 100%;
	padding: 3px 7px;
	display: inline-block;
	font-size: 15px;
	margin-left: 30px;
}
</style>
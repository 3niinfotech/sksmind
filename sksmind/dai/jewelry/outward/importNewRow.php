<?php 
session_start();
include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('sale',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}
							
include_once('outwardModel.php');
include_once($daiDir.'Helper.php');


$model  = new outwardModel($cn);
$helper  = new Helper($cn);
$attribute = $helper->getAttribute();							
$groupUrl = $daiDir.'module/outward/';
$sku  = trim($_POST['sku']);
$type = $_POST['diamond'];
$jData =  $model->getDataBySku($sku,$type);		
$i= $_POST['rid'];
?>
<?php if(!empty($jData)): 
if($type == 'main'):
?>
	<div class="divTableCell" ><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i><input type="hidden" id="mrecord-<?php echo $i?>" name="mrecord[<?php echo $i?>][id]" value="<?php echo $jData['id']?>" />	<input type="hidden" id="main_stone-<?php echo $i?>" name="main_stone[<?php echo $i?>]" value="<?php echo $jData['id']?>" /></div>
	<div class="divTableCell"><?php echo $i?></div>			
	<div class="divTableCell"><input class=" col-sm-12 stone"  rid="<?php echo $i?>" name="mrecord[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" value="<?php echo $jData['sku']?>"  type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $i?>][pcs]" value="<?php echo $jData['pcs']?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $i?>][carat]" value="<?php echo $jData['carat']?>" onchange="calAmount(<?php echo $i?>)" id="pcarat-<?php echo $i?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $i?>][sell_price]" value="<?php echo ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price']; ?>" id="price-<?php echo $i?>" onchange="calAmount(<?php echo $i?>)"  type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $i?>][sell_amount]" value="<?php echo ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount']; ?>" id="amount-<?php echo $i?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12"  name="mrecord[<?php echo $i?>][remark]" value="<?php echo $jData['remark']?>" type="text"></div>
	<div class="divTableCell bdiv "><?php echo $jData['lab'];?></div>
	<div class="divTableCell bdiv "><?php echo $jData['igi_code'];?></div>			
	<div class="divTableCell bdiv "><?php echo $jData['igi_color'];?></div>			
	<div class="divTableCell bdiv a-right "><?php echo $jData['igi_clarity'];?></div>		
	<div class="divTableCell bdiv a-right "><?php echo $jData['igi_price'];?></div>			
	<div class="divTableCell bdiv a-right "><?php echo $jData['igi_amount'];?></div>
<?php else:?>
	<div class="divTableCell" ><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i><input type="hidden" id="record-<?php echo $i?>" name="record[<?php echo $i?>][id]" value="<?php echo $jData['id']?>" />	<input type="hidden" id="side_stone-<?php echo $i?>" name="side_stone[<?php echo $i?>]" value="<?php echo $jData['id']?>" /></div>
	<div class="divTableCell"><?php echo $i?></div>			
	<div class="divTableCell"><input class=" col-sm-12 stone"  rid="<?php echo $i?>" name="record[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" value="<?php echo $jData['sku']?>"  type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][pcs]" value="<?php echo $jData['pcs']?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][carat]" value="<?php echo $jData['carat']?>" onchange="calAmount(<?php echo $i?>)" id="pcarat-<?php echo $i?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][sell_price]" value="<?php echo ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price']; ?>" id="price-<?php echo $i?>" onchange="calAmount(<?php echo $i?>)"  type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][sell_amount]" value="<?php echo ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount']; ?>" id="amount-<?php echo $i?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][remark]" value="<?php echo $jData['remark']?>" type="text"></div>
	<div class="divTableCell bdiv "></div>
	<div class="divTableCell bdiv "></div>			
	<div class="divTableCell bdiv "></div>			
	<div class="divTableCell bdiv a-right "></div>		
	<div class="divTableCell bdiv a-right "></div>			
	<div class="divTableCell bdiv a-right "></div>
	<?php endif; ?>
<?php else:?>
no
<?php endif; ?>
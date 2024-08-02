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


$model  = new outwardModel();
$helper  = new Helper();
$attribute = $helper->getAttribute();							
$groupUrl = $daiDir.'module/outward/';
$sku  = trim($_POST['sku']);
$jData =  $model->getDataBySku($sku);		
$i= $_POST['rid'];
?>
<?php if(!empty($jData)): ?>
	
	<div class="divTableCell" ><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i><input type="hidden" id="record-<?php echo $i?>" name="record[<?php echo $i?>][id]" value="<?php echo $jData['id']?>" />	<input type="hidden" id="products-<?php echo $i?>" name="products[<?php echo $i?>]" value="<?php echo $jData['id']?>" />		</div>
	<div class="divTableCell"><?php echo $i?></div>			
	<div class="divTableCell"><input class=" col-sm-12 stone"  rid="<?php echo $i?>" name="record[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" value="<?php echo $jData['sku']?>"  type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][polish_pcs]" value="<?php echo $jData['polish_pcs']?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][polish_carat]" value="<?php echo $jData['polish_carat']?>" onchange="calAmount(<?php echo $i?>)" id="pcarat-<?php echo $i?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][cost]" value="<?php echo $jData['cost']?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][sell_price]" value="<?php echo ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price']; ?>" id="price-<?php echo $i?>" onchange="calAmount(<?php echo $i?>)"  type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][sell_amount]" value="<?php echo ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount']; ?>" id="amount-<?php echo $i?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][location]" value="<?php echo $jData['location']?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][remark]" value="<?php echo $jData['remark']?>" type="text"></div>
	<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][lab]"  value="<?php echo $jData['lab']?>" type="text"></div>
	<?php /*foreach($attribute as $key=>$v): ?>
	<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][attr][<?php echo $key?>]" value="<?php echo $jData[$key]?>" type="text"></div>
	<?php endforeach; */ ?>

<?php else:?>
no
<?php endif; ?>
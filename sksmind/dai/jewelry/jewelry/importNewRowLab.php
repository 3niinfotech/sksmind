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
include_once($daiDir.'jHelper.php');							
include_once('jewelryModel.php');
include_once($daiDir.'Helper.php');


$model  = new jewelryModel($cn);
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
//$attribute = $helper->getAttribute();							
$groupUrl = $daiDir.'module/outward/';
$sku  = trim($_POST['sku']);
$jewType = $jhelper->getJewelryType();
$design = $jhelper->getAllDesign();
$jData =  $model->getDataBySku($sku);		
$i= $_POST['rid'];
?>
<?php if(!empty($jData)): ?>
	
	<div class="divTableCell" ><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i><input type="hidden" id="record-<?php echo $i?>" name="record[<?php echo $i?>][id]" value="<?php echo $jData['id']?>" />	<input type="hidden" id="products-<?php echo $i?>" name="products[<?php echo $i?>]" value="<?php echo $jData['id']?>" /><input type="hidden" name="record[<?php echo $i?>][lab]" value="<?php echo $jData['lab']?>" />		</div>
	<div class="divTableCell"><?php echo $i?></div>			
	<div class="divTableCell"><input class=" col-sm-12 stone"  rid="<?php echo $i?>" name="record[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" value="<?php echo $jData['sku']?>"  type="text"></div>
	<div class="divTableCell bdiv"><?php echo $jData['jew_design'] ?></div>			
					<div class="divTableCell bdiv"><?php echo $jewType[$jData['jew_type']] ?></div>		
					<div class="divTableCell width-50px bdiv"><?php echo $jData['gold'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $jData['gold_color'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $jData['gross_weight'] ?></div>
					<div class="divTableCell bdiv"><?php echo $jData['pg_weight'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $jData['net_weight'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $jData['rate'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $jData['amount'] ?></div>
					<div class="divTableCell bdiv"><?php echo $jData['total_amount'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $jData['selling_price'] ?></div>

<?php else:?>
no
<?php endif; ?>

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
							
include_once($daiDir.'jewelry/outward/outwardModel.php');
include_once($daiDir.'Helper.php');


$model  = new outwardModel($cn);
$helper  = new Helper($cn);
$attribute = $helper->getAttribute();							
$groupUrl = $daiDir.'module/outward/';
$sku  = trim($_POST['sku']);
$type = $_POST['diamond'];
$value =  $model->getDataBySku($sku,$type);		
$i= $_POST['rid'];
?>
<?php if(!empty($value)): ?>
	<div class="divTableCell " style="width:50px !important"><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i><input type="hidden" id="srecord-<?php echo $i?>" name="srecord[<?php echo $i?>][id]" value="<?php echo $value['id']?>" /><?php echo $i;?></div>
	<div class="divTableCell  "><?php echo $sku;?></div>
	<div class="divTableCell"><input class=" col-sm-12  a-right"  name="srecord[<?php echo $i ?>][pcs]" type="text" value="<?php echo (isset($value['pcs']))?$value['pcs']:'&nbsp;'?>" ></div>
	<div class="divTableCell"><input class=" col-sm-12 a-right tcarat"  name="srecord[<?php echo $i ?>][carat]" onChange="calAmount(<?php echo $i ?>)" id="pcarat-<?php echo $i ?>" type="text" value="<?php echo (isset($value['carat']))?$value['carat']:'&nbsp;'?>"></div>	
	<div class="divTableCell  "><?php echo $value['shape'];?></div>		
	<div class="divTableCell  "><?php echo $value['clarity'];?></div>				
	<div class="divTableCell  "><?php echo $value['color'];?></div>				
	<div class="divTableCell  a-right"><input class=" col-sm-12 a-right"  name="srecord[<?php echo $i ?>][price]" id="price-<?php echo $i ?>" onBlur="calAmount(<?php echo $i ?>)" value="<?php echo ($value['sell_price'] ==0)?$value['price']:$value['sell_price'] ?>"  type="text"> <input name="srecord[<?php echo $i ?>][amount]" id="amount1-<?php echo $i ?>" type="hidden" value="<?php echo (isset($value['amount']))?$value['amount']:'&nbsp;'?>"></div>		
	<div class="divTableCell amount1 a-right" id="amount2-<?php echo $i ?>"><?php echo ($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'] ?></div>
<?php else:?>
no
<?php endif; ?>

<?php 
session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'module/party/partyModel.php');


include_once($daiDir.'Helper.php');
$helper  = new Helper($cn);
$data = $helper ->getDetailBySkuForUpdate($_POST['sku']);


$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
if(!empty($data)):

$party['']="";

$atrribute = $helper->getUpdateAttribute();
$recAttribute = $atrribute['record'];
unset($atrribute['record']);

?>
<form id="update-form" class="form-horizontal" method="POST" role="form" action="<?php echo $moduleUrl.'inventory/inventoryController.php'?>">
<input type="hidden" name="fn" value="updateProduct" />
<input type="hidden" name="id" value="<?php echo $data['id'];?>" />

<div style="clear:both;"></div>

<div class="col-xs-12 col-sm-12 report-content " >

<div class="col-xs-12 col-sm-12 " >
	<div class="col-xs-3">
		<span class="check-label green" > Site Upload : </span>
		<label>	
			<input name="is_uploadsite" <?php if($data['is_uploadsite']):?> checked <?php endif;?> class="ace ace-switch ace-switch-2" type="checkbox" />			
			<span class="lbl"></span>
		</label>
	</div>
	<div class="col-xs-3">
		<span class="check-label green" > Rapnet Upload : </span>
		<label>	
			<input name="is_uploadrapnet" <?php if($data['is_uploadrapnet']):?> checked <?php endif;?> class="ace ace-switch ace-switch-2"  type="checkbox" />			
			<span class="lbl"></span>
		</label>
	</div>
	
	
	<div class="col-xs-3">
		<span class="check-label green" > Hide : </span>
		<label>	
			<input <?php if($data['hide']):?> checked <?php endif;?> class="ace ace-switch ace-switch-2"  name="hide" type="checkbox" />			
			<span class="lbl"></span>
		</label>
	</div>
	
	
	<div style="clear:both;"></div>		
	<hr>
	<?php foreach($atrribute  as $k=>$v) : ?>
	<div class="history-attribute">
		<div class="attribute-label"><?php echo $v;?></div>
		
		<div class="attribute-value">
			<?php if($k =='price' || $k =='amount' || $k =='sku' || $k =='polish_carat'): ?>
				<input type="text" disabled class="update-attribute" name="<?php echo $k?>" value="<?php echo $data[$k];?>" >
			<?php else: ?>
				<input type="text" class="update-attribute" name="<?php echo $k?>" value="<?php echo $data[$k];?>" >
			<?php endif;?>
		</div>
	</div>
	<?php endforeach; ?>
	<div style="clear:both;"></div>
	<br><br><br>
	
	<?php foreach($recAttribute  as $k=>$v) : ?>
	<div class="history-attribute">
		<div class="attribute-label"><?php echo $v;?></div>
		<div class="attribute-value"><input type="text" class="update-attribute" name="record[<?php echo $k?>]" value="<?php echo $data['record'][$k];?>" > </div>
	</div>
	<?php endforeach; ?>
	<div style="clear:both"></div>
	
</div>									


</div>	
</div>
<?php else:?>
	<h4 style="text-align:center">No Data found for this SKU.</h4>
<?php endif;?>

			
<style>
.history-block {
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  border-color: #9abc32 #ccc #ccc;
  border-image: none;
  border-radius: 2px;
  border-style: solid;
  border-width: 5px 1px 1px;
  margin: 10px;
  padding: 0;
  width: 100%;
}
.history-head {
  border-bottom: 1px dashed #ccc;
  float: left;
  padding: 7px;
  width: 100%;
}
.history-type {
  color: #2679b5;
  float: left;
  font-size: 14px;
  font-weight: bold;
  letter-spacing: 1px;
  width: 50%;
}
.history-date {
  color: #666;
  float: left;
  font-size: 14px;
  letter-spacing: 1px;
  text-align: right;
  width: 50%;
}
.history-decription {
  float: left;
  padding: 15px 10px;
  width: 100%;
  height:100px;
}
.history-attribute {
  border-bottom: 1px solid #f1f1f1;
  float: left;
  margin-bottom: 20px;
  margin-right: 3%;
  padding-bottom: 5px;
  width: 22%;
}
.attribute-label {
  color: #2679b5;
  float: left;
  font-size: 14px;
  width: 35%;
}
.attribute-value {
  color: #333;
  float: left;
  text-align: right;
  font-size: 14px;
  width: 65%;
}

.update-attribute {
	padding: 0px 5px !important;
	width: 100%;
}
.check-label {
	
	clear: both;
	float: left;
	font-size: 12px;
	font-weight: bold;
	margin-right: 5px;
}
</style>			

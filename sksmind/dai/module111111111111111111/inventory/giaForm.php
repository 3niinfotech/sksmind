<?php 
session_start();
include("../../../database.php");
include("../../../variable.php");
include_once($daiDir.'Helper.php');
$helper  = new Helper();
$attribute = $helper->getAttribute();		

$i=1;

?>
<div class="page-header">							
	<h1 style="float:left">
		Received from GIA Lab						
	</h1>
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn reset" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>	
</div>

<form class="form-horizontal" id="gia-form"method="POST" role="form" >
<input type="hidden" name="fn" value="saveGia" />
<input type="hidden" name="outid" value="<?php echo $_POST['outid']; ?>" />

<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Mfg. Code</label>
	</div>
</div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Sku</label>
	</div>
</div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Report No.</label>
	</div>
</div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Intensity</label>
	</div>
</div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Overtone</label>
	</div>
</div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Color</label>
	</div>
</div>
<div style="clear:both"></div>
<div class="col-sm-12" style="height:200px;overflow-y:auto;">
<?php foreach($_POST['id'] as $k=>$id): 
$data = $memo = $helper->getProductDetail($id); 
?>

<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12"  value="<?php echo $data['mfg_code'] ?>" name="record[<?php echo $id ?>][mfg_code]" placeholder="Mfg Code" type="text">
		</div>
</div>
<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12" value="<?php echo $data['sku']?>" name="record[<?php echo $id ?>][sku]" placeholder="New Sku" type="text">
		</div>
</div>
<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12"  value="<?php echo $data['report_no']?>" name="record[<?php echo $id ?>][report]" placeholder="Report No." type="text">
		</div>
</div>
<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12"  value="<?php echo $data['intensity']?>" name="record[<?php echo $id ?>][intensity]" placeholder="Intensity" type="text">
		</div>
</div>
<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12"  value="<?php echo $data['overtone']?>" name="record[<?php echo $id ?>][overtone]" placeholder="Overtone" type="text">
		</div>
</div>
<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12"  value="<?php echo $data['color']?>" name="record[<?php echo $id ?>][color]" placeholder="Color" type="text">
		</div>
</div>

<div style="clear:both"></div>

<?php endforeach; ?>
</div >


<div class="col-xs-12 col-sm-12 " >
	
	<div class="clearfix form-actions" >
		<div class="col-md-12">
			
			<button class="btn btn-info" type="button" onClick="saveGia()" >
				<i class="ace-icon fa fa-check bigger-110"></i>
				Save GIA Data
			</button>

			&nbsp; &nbsp; &nbsp;
			<button class="btn reset" type="reset">
				<i class="ace-icon fa fa-undo bigger-110"></i>
				Reset
			</button>
		</div>
	</div>
</div>	
</form>
<style>
.form-group 
{
	margin-bottom: 10px;
}
</style>
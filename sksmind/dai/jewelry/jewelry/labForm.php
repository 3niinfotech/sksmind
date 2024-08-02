<?php 
session_start();
include("../../../database.php");
include("../../../variable.php");
//include_once($daiDir.'jHelper.php');
//$jhelper  = new jHelper($cn);
include_once($daiDir.'jewelry/jewelry/jewelryModel.php'); 
$model = new jewelryModel($cn);
include_once($daiDir.'jewelry/jobwork/jobworkModel.php'); 
$jmodel = new jobworkModel($cn);	
$color = $jmodel->getColorList();
$clarity = $jmodel->getClarityList();
$i=1;

?>
<div class="page-header">							
	<h1 style="float:left">
		Received from Lab						
	</h1>
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn reset" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>	
</div>

<form class="form-horizontal" id="gia-form" method="POST" role="form" >
<input type="hidden" name="fn" value="saveGia" />
<input type="hidden" name="outid" value="<?php echo $_POST['outid']; ?>" />
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">IGI DATE</label>
	</div>
</div>
<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12" id="date" name="receive_date" placeholder="Date" type="text">
		</div>
</div>
<div style="clear:both"></div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Sku</label>
	</div>
</div>

<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">IGI CODE</label>
	</div>
</div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-10 control-label no-padding-right" for="form-field-4">IGI Exp.</label>
	</div>
</div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-10 control-label no-padding-right" for="form-field-4">IGI Amount</label>
	</div>
</div>
<div style="clear:both"></div>
<div class="col-sm-12" style="height:175px;overflow-y:auto;">
<?php foreach($_POST['id'] as $k=>$id): 
	$data = $model->getData($id); 
?>

<div class="form-group col-sm-2">		
		<div class="col-sm-12 center infobox-green infobox-dark">
			<?php echo $data['sku']; ?>
		</div>
</div>
<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12" name="record[<?php echo $id ?>][igi_code]" placeholder="IGI Code" type="text">
		</div>
</div>
<?php 
			$tcarat = 0;
			foreach($data['main_stone'] as $k=>$mdata)
			{
				$tcarat = $tcarat + $mdata['carat'];
			}
			$amount =0;
			if($tcarat <= 0.29)
			$amount = 33;
			elseif($tcarat <= 0.49)
			$amount = 39;
			elseif($tcarat <= 0.69)
			$amount = 46;
			elseif($tcarat <= 0.99)
			$amount = 53;
			elseif($tcarat <= 1.49)
			$amount = 63;
			elseif($tcarat <= 1.99)
			$amount = 90;
			elseif($tcarat <= 2.49)
		    $amount = 108;
			elseif($tcarat <= 2.99)
		    $amount = 128;
			elseif($tcarat <= 3.99)
		    $amount = 195;
			elseif($tcarat <= 4.99)
		    $amount = 256;
			elseif($tcarat <= 5.99)
		    $amount = 308;
			elseif($tcarat <= 7.99)
		    $amount = 359;
			elseif($tcarat <= 9.99)
		    $amount = 462;
			elseif($tcarat <= 11.99)
		    $amount = 513;
			elseif($tcarat <= 14.99)
		    $amount = 615;
			elseif($tcarat <= 19.99)
		    $amount = 821;
			elseif($tcarat <= 39.99)
		    $amount = 1026;
			elseif($tcarat <= 79.99)
		    $amount = 1282;
			elseif($tcarat <= 99.99)
		    $amount = 1538;
			elseif($tcarat <= 199.99)
		    $amount = 1795;
			elseif($tcarat <= 299.99)
		    $amount = 2051;
		?>
<div class="form-group col-sm-2">	
		<div class="col-sm-12">
			<input class="input-sm col-sm-12 a-right" name="record[<?php echo $id ?>][lab_fee]" value="<?php echo $amount; ?>" placeholder="IGI Expense" type="text">
		</div>
</div>
<div class="form-group col-sm-2">	
		<div class="col-sm-12">
			<input class="input-sm col-sm-12 a-right" name="record[<?php echo $id ?>][igi_amount]" value="<?php echo $data['igi_amount']; ?>" placeholder="IGI Amount" type="text">
		</div>
</div>
<div style="clear:both"></div>
<?php foreach($data['main_stone'] as $k=>$mdata): ?>
<div class="form-group col-sm-2">		
		<div class="col-sm-12 center">
			<?php echo $mdata['sku']; ?>
		</div>
		<input name="record[<?php echo $id ?>][mrecord][<?php echo $mdata['id'] ?>][sku]" value="<?php echo $mdata['sku']; ?>" type="hidden">
</div>
<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<select class="col-xs-12" name="record[<?php echo $id ?>][mrecord][<?php echo $mdata['id'] ?>][igi_color]">
				<option value="">IGI Color</option>
				<?php 
				foreach($color as $key => $value):
				?>						
				<option  <?php echo ($value == $mdata['igi_color'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
		</div>
</div>
<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<select class="col-xs-12" name="record[<?php echo $id ?>][mrecord][<?php echo $mdata['id'] ?>][igi_clarity]">
				<option value="">IGI Clarity</option>
				<?php 
				foreach($clarity as $key => $value):
				?>						
				<option <?php echo ($value == $mdata['igi_clarity'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
		</div>
</div>
<div style="clear:both"></div>
<?php endforeach; ?>
<div style="clear:both"></div>
<hr/>
<?php endforeach; ?>
</div >


<div class="col-xs-12 col-sm-12 " >
	
	<div class="clearfix form-actions" >
		<div class="col-md-12">
			
			<button class="btn btn-info" type="button" onClick="saveGia()" >
				<i class="ace-icon fa fa-check bigger-110"></i>
				Save Data
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
.a-right{
	text-align:right;
}

</style>
<script type="text/javascript">
		
			$( "#date, #duedate" ).datepicker({
										showOtherMonths: true,
										selectOtherMonths: false,
										dateFormat: 'yy-mm-dd',
									}).datepicker("setDate", new Date());
</script>									
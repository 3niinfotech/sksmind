<?php
//include('model.php');
//$model  = new model();
$entryno = $model->getIncrementEntry('inward');
$t = "";

if(isset($_GET['t']))
{
		$t = $_GET['t'];
}
?>
<form class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'jewelry/jewelryController.php'?>">
<input type="hidden" name="fn" value="save" />
<?php if(isset($_GET['id']))
{	
	
?>
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
<?php } ?>
<div class="col-xs-12 col-sm-12 ">
	
	<div class="form-group col-sm-5">
		<label class="col-sm-7 control-label no-padding-right" for="form-field-4">SKU</label>
		<div class="col-sm-5">
			<input class="input-sm col-sm-10" name="sku" value="<?php echo $data['sku']?>" placeholder="New Sku" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Gross CTS</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" name="gross_cts" value="<?php echo $data['gross_cts']?>" placeholder="Gross CTS" type="text">
		</div>
	</div>	
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Date</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="date" value="<?php echo $data['date']?>"  name="date" placeholder="Select Date" type="text">
		</div>
	</div>
</div>
<div style="clear:both"></div>
	<hr>
	
<div class="col-xs-12 col-sm-8 " style="margin-left:16.66%;">
	<table class="diamond-htable">
		<thead>
		<tr class="diamond-th">
			<th class="diamond-col dc1">SKU</th>
			<th class="diamond-col dc2">Report</th>
			<th class="diamond-col dc3">Color</th>
			<th class="diamond-col dc4">PCS</th>
			<th class="diamond-col dc5">CTS</th>						
			<th class="diamond-col dc6">Price</th>
			<th class="diamond-col dc7">Total</th>								
		</tr>
		</thead>
		<tbody>
		<?php if(count($data['record'])== 0): ?>
			<tr class="diamond-tr" id="row-1">
				<td class="diamond-col dc1"><input class="input-sm col-sm-12" name="record[1][sku]" type="text" onBlur="loadSkuData(1,this.value)"></td>
				<td class="diamond-col dc2"><input class="input-sm col-sm-12" name="record[1][report]" id="report-1" type="text"></td>
				<td class="diamond-col dc3"><input class="input-sm col-sm-12" name="record[1][color]" id="color-1" type="text"></td>
				<td class="diamond-col dc4"><input class="input-sm col-sm-12 a-right pcs" id="pcs-1" name="record[1][pcs]" type="text"></td>						
				<td class="diamond-col dc5"><input class="input-sm col-sm-12 a-right cts " id="cts-1" name="record[1][carat]" type="text"></td>	
				<td class="diamond-col dc6"><input class="input-sm col-sm-12 a-right price" id="price-1" name="record[1][price]" onKeyUp="calulateAmount(1)" onBlur="calulateAmount(1)" type="text"></td>			
				<td class="diamond-col dc7"><input class="input-sm col-sm-12 a-right amount" id="amount-1" readonly name="record[1][total_amount]" type="text"></td>			
			</tr>
		
		<?php else: ?>
			<?php foreach($data['record'] as $key=>$record):?>
			<tr class="diamond-tr">
				<td class="diamond-col dc1"><?php echo $record['sku'] ?></td>
				<td class="diamond-col dc2"><?php echo $record['report'] ?></td>
				<td class="diamond-col dc3"><?php echo $record['color'] ?></td>
				<td class="diamond-col dc4 a-right"><?php echo $record['pcs'] ?></td>						
				<td class="diamond-col dc5 a-right"><?php echo $record['carat'] ?></td>	
				<td class="diamond-col dc6 a-right"><?php echo $record['price'] ?></td>			
				<td class="diamond-col dc7 a-right"><?php echo $record['total_amount'] ?></td>			
			</tr>
			<?php endforeach; ?>
		<?php endif;?>
		<tfoot>
			<tr class="diamond-tr">
				<td class="diamond-col dc1" colspan="7">&nbsp;</td>						
			</tr>
			<tr class="diamond-tr">
				<td class="diamond-col dc1"></td>
				<td class="diamond-col dc2 a-right" colspan='5' >LABOUR FOR JEWELLERY	</td>
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right" value="<?php echo ($data['labour_fee']!='')?$data['labour_fee']:0;?>" name="labour_fee" id="labour_fee" onKeyUp="totalamount()" onBlur="totalamount()" type="text"> </td>			
			</tr>
			<tr class="diamond-tr">
				<td class="diamond-col dc1"></td>
				<td class="diamond-col dc2 a-right" colspan='3'>
				<select class="col-xs-8" name="gold_type">
					<option value="">Select Gold</option>
					<?php 
					foreach($gold as $key => $value):
					?>						
					<option value="<?php echo $key?>" <?php echo ($key == $data['gold_type'])?"selected":""; ?> ><?php echo $value?></option>
					<?php endforeach; ?>
				
				</select>
									
				<td class="diamond-col dc5 a-right"><input class="input-sm col-sm-12 a-right"  value="<?php echo $data['gold_gram']?>" id="gold_gram" name="gold_gram" type="text"></td>	
				<td class="diamond-col dc6 a-right"><input class="input-sm col-sm-12 a-right"  value="<?php echo $data['gold_price']?>" id="gold_price" name="gold_price" type="text" onKeyUp="calulateGold(1)" onBlur="calulateGold(1)" ></td>			
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right" readonly value="<?php echo $data['gold_amount']?>" id="gold_amount" name="gold_amount" type="text"></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='6'><b>Total USD</b></td>												
				<td class="diamond-col dc7 a-right"><b><input class="input-sm col-sm-12 a-right" readonly value="<?php echo $data['cost_price']?>" id="cost_price" name="cost_price" type="text"> </b></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='5'><b>Sell Price</b></td>												
				<td class="diamond-col dc6 a-right"><input class="input-sm col-sm-12 a-right"  value="<?php echo $data['percentage']?>" name="percentage" id="percentage" onKeyUp="finalTotal()" type="text"> </td>			
				<td class="diamond-col dc7 a-right"><b><input class="input-sm col-sm-12 a-right" value="<?php echo $data['final_cost']?>"  name="final_cost" id="final_cost" type="text"> </b></td>			
			</tr>
		</tfoot>
		
		</tbody>
	</table>
</div>

<div class="col-xs-12 col-sm-12 " style="margin-top:30px">
	
<div class="clearfix form-actions" >
	<div class="col-md-12">
		<div class="form-group col-sm-5">		
			<textarea class="form-control" id="form-field-8" placeholder="Narretion" style="width: 353px; height: 88px;" name="narretion"><?php echo $data['narretion']?></textarea>
		</div>
		<button class="btn btn-info" type="submit" value="save">
			<i class="ace-icon fa fa-check bigger-110"></i>
			Save Entry
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
.form-group {
  margin-bottom: 20px;
}

.add-more {
  background-color: #69aa46;
  border-radius: 100%;
  color: #fff;
  font-size: 14px;
  height: 25px;
  padding: 6px 5px 5px;
  width: 25px;
  cursor: pointer;
}

.delete-more {
  background-color: #dd5a43;
  border-radius: 100%;
  color: #fff;
  font-size: 14px;
  height: 22px;
  padding: 4px 5px 5px;
  width: 22px;
  cursor: pointer;
}

</style>
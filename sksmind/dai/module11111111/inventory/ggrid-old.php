<?php 
$gia = $helper->getInventory('gia');
?>
<form class="form-horizontal" method="POST" role="form" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="sendToLab" />
<div class="button-group">
	<div class="cgrid-header">
		<div class="color-total"> <b>Total Record :</b> &nbsp;&nbsp;<?php echo count($gia) ?> &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Selected :</b> &nbsp;&nbsp;<span id="selected-row">0</span> &nbsp;&nbsp; |</div>
	</div>	
	<button class="btn btn-info" type="submit" onClick="location.href='<?php echo $daiUrl;?>module/inward/inward.php'" style="float:right">
		<i class="ace-icon fa fa-reply bigger-110"></i>
		Received From LAB
	</button>
	<div style="float:right;padding-top:7px;" class="form-group col-sm-4">
		<label class="col-sm-6 control-label no-padding-right" for="form-field-4">Select LAB From Received</label>
		<div class="col-sm-6">
			<select class="col-xs-11" id="lab" name="lab">
				<option value="GIA" selected>GIA</option>				
				<option value="IGI">IGI</option>				
			</select>
		</div>
	</div>
</div>
<div class="col-xs-12 group-grid inventory-grid">

<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->
<div>
	<table  id="dynamic-table" class="inventory-table table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="center"></th>
				<th>Mfg.code</th>
				<th>P.Pcs</th>
				<th>P.Carat</th>				
				<th>Type</th>
				<th>Cost</th>
				<th>Price</th>
				<th>Amount</th>	
				<th>LOC</th>	
				<th>Remark</th>
				<th>Lab</th>
				<?php foreach($attribute as $key=>$v): ?>
				<th><?php echo $v; ?></th>
				<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
			
			<?php foreach($gia  as $value):	?>						
			<tr>
				<td class="center">
					<label class="pos-rel">
						<input type="checkbox" class="ace" name="id[]" value="<?php echo $value['id']; ?>" onClick="countCheck(this);"  />
						<span class="lbl"></span>
					</label>
				</td>

				<td><?php echo $value['mfg_code'];echo($value['diamond_no']!="" )?'-'.$value['diamond_no']:''; ?>
					
				</td>				
				<td><?php echo $value['polish_pcs']?></td>
				<td><?php echo $value['polish_carat']?></td>							
				<td><?php echo strtoupper($value['group_type'])?></td>
				<td><?php echo $value['cost']?></td>
				<td><?php echo $value['price']?></td>
				<td><?php echo $value['amount']?></td>
				<td><?php echo $value['location']?></td>
				<td><?php echo $value['remark']?></td>
				<td><?php echo $value['lab']?></td>
				<?php foreach($attribute as $key=>$v): ?>
				<td><?php echo (isset($value[$key]))?$value[$key]:'&nbsp;'; ?></td>
				<?php endforeach;?>
			</tr>	
			<?php endforeach; ?>
		</tbody>
		
	</table>
</div>
</div>
</form>
<style>
.table > tbody > tr > td, 
.table > tbody > tr > th, 
.table > tfoot > tr > td, 
.table > tfoot > tr > th, 
.table > thead > tr > td, 
.table > thead > tr > th {  
  padding: 4px 8px;  
}

.table-striped > tbody > tr:nth-of-type(2n+1) {
  background-color: #fff;
}
</style>


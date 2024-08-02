<?php 
$export = $helper->getInventory('export');

?>
<div class="button-group">
	<div class="cgrid-header">
		<div class="color-total"> <b>Total Record :</b> &nbsp;&nbsp;<?php echo count($export) ?> &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Selected :</b> &nbsp;&nbsp;<span id="selected-row">0</span> &nbsp;&nbsp; |</div>
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
			
			<?php foreach($export as $value):	?>						
			<tr>
				<td class="center">
					<label class="pos-rel">
						<input type="checkbox" class="ace" name="products[]" value="<?php echo $value['id']; ?>" onClick="countCheck(this);"  />
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


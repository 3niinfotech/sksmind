<?php 
//$export = $helper->getInventory('sale');

?>
<input type="hidden" name="sproducts" value="<?php echo $data['products']?>" > 
<div class="button-group">
	<div class="cgrid-header">
		<div class="color-total"> <b>Total Record :</b> &nbsp;&nbsp;<?php echo count($data['record'])?> &nbsp;&nbsp; |</div>		
	</div>	
</div>

<div class="subform" style="overflow-x:sroll">
<div class="divTable sale-grid" style="width: 1800px;">
	
	
	<div class="divTableHeading">
		<div class="divTableCell">&nbsp; <!-- <i class="add-more fa fa-plus "> </i> --></div>
		<div class="divTableCell">No.</div>		
		<div class="divTableCell">SKU</div>		
		<div class="divTableCell">Type</div>
		<div class="divTableCell">Design</div>
		<div class="divTableCell width-50px" >Gold</div>
		<div class="divTableCell" >Gold Color</div>
		<div class="divTableCell" >Gross Weight</div>
		<div class="divTableCell" >Pg Weight</div>
		<div class="divTableCell" >Net Weight</div>			
		<div class="divTableCell" >Rate</div>
		<div class="divTableCell" >Amount</div>
		<div class="divTableCell" >Other Code</div>
		<div class="divTableCell " >Other Amount</div>
		<div class="divTableCell " >Labour Rate</div>
		<div class="divTableCell" >labour Amount</div>
		<div class="divTableCell" >Total Amount</div>	
		<div class="divTableCell">Sell Price</div> 
		<?php /*foreach($attribute as $key=>$v): ?>
		<div class="divTableCell"><?php echo $v; ?></div>
		<?php endforeach; */ ?>
	</div>		
	
	<div class="divTableBody sdivtable">
		<?php 
		$i=1;
		foreach($data['record'] as $k=>$jData): 	?>
		<input type="hidden" id="record-<?php echo $i?>" name="record[<?php echo $i?>][id]" value="<?php echo $jData['id']?>" />
		<input type="hidden" id="products-<?php echo $i?>" name="products[<?php echo $i?>]" value="<?php echo $jData['id']?>" />
		<div class="divTableRow" id="rowid-<?php echo $i?>">
			<div class="divTableCell"><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i></div>
			<div class="divTableCell"><?php echo $i?></div>			
			<div class="divTableCell"><input class=" col-sm-12 stone" rid="<?php echo $i?>" name="record[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" value="<?php echo $jData['sku']?>"  type="text"></div>
			<div class="divTableCell bdiv"><?php echo $jData['jew_design']?></div>
					<div class="divTableCell bdiv"><?php echo $jewType[$jData['jew_type']] ?></div>	<div class="divTableCell width-50px bdiv"><?php echo $jData['gold'] ?></div>	<div class="divTableCell bdiv"><?php echo $jData['gold_color'] ?></div>			<div class="divTableCell bdiv"><?php echo $jData['gross_weight'] ?></div>		<div class="divTableCell bdiv"><?php echo $jData['pg_weight'] ?></div>		
					<div class="divTableCell bdiv"><?php echo $jData['net_weight'] ?></div>			<div class="divTableCell bdiv"><?php echo $jData['rate'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $jData['amount'] ?></div>			
					<div class="divTableCell bdiv"><?php echo $jData['other_code'] ?></div>		
					<div class="divTableCell bdiv"><?php echo $jData['other_amount'] ?></div>		
					<div class="divTableCell bdiv"><?php echo $jData['labour_rate'] ?></div>		
					<div class="divTableCell bdiv"><?php echo $jData['labour_amount'] ?></div>	
					<div class="divTableCell bdiv"><?php echo $jData['total_amount'] ?></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right sell_price"  name="record[<?php echo $i?>][sell_price]"  value="<?php echo $jData['sell_price']?>" type="text" onchange="changeSellPrice()"></div>
			<?php /*foreach($attribute as $key=>$v): ?>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][attr][<?php echo $key?>]" value="<?php echo $jData[$key]?>" type="text"></div>
			<?php endforeach;*/?>
		</div>
		<?php $i++;endforeach;?>
	</div>
	<input name="final_amount" value="<?php echo $data['final_amount']?>" type="hidden" id="sellamount">
</div>

</div>


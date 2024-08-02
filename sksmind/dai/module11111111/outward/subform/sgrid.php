<?php 
$export = $helper->getInventory('sale');

?>
<input type="hidden" name="sproducts" value="<?php echo $data['products']?>" >
<div class="button-group">
	<div class="cgrid-header">
		<div class="color-total"> <b>Total Record :</b> &nbsp;&nbsp;<?php echo count($data['record'])?> &nbsp;&nbsp; |</div>		
	</div>	
</div>

<div class="subform" style="overflow-x:sroll">
<div class="divTable sale-grid" style="width: 3250px;">
	
	
	<div class="divTableHeading">
		<div class="divTableCell">&nbsp; <!-- <i class="add-more fa fa-plus "> </i> --></div>
		<div class="divTableCell">No.</div>		
		<div class="divTableCell">SKU</div>
		<div class="divTableCell">P.Pcs</div>
		<div class="divTableCell">P.Carat</div>
		<div class="divTableCell">Cost</div>		
		<div class="divTableCell">Price</div>
		<div class="divTableCell">Amount</div>
		<div class="divTableCell">LOC </div>
		<div class="divTableCell">Remark</div>
		<div class="divTableCell">Lab</div> 
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
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][polish_pcs]" value="<?php echo $jData['polish_pcs']?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][polish_carat]" value="<?php echo $jData['polish_carat']?>" onchange="calAmount(<?php echo $i?>)" id="pcarat-<?php echo $i?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][cost]" value="<?php echo $jData['cost']?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][sell_price]" value="<?php echo ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'] ?>" id="price-<?php echo $i?>" onchange="calAmount(<?php echo $i?>)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][sell_amount]" value="<?php echo ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'] ?>" id="amount-<?php echo $i?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][location]" value="<?php echo $jData['location']?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][remark]" value="<?php echo $jData['remark']?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][lab]"  value="<?php echo $jData['lab']?>" type="text"></div>
			<?php /*foreach($attribute as $key=>$v): ?>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][attr][<?php echo $key?>]" value="<?php echo $jData[$key]?>" type="text"></div>
			<?php endforeach;*/?>
		</div>
		<?php $i++;endforeach;?>
	</div>
	
</div>

</div>


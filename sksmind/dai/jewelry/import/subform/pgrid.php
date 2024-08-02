<?php 
$export = $helper->getInventory('sale');

?>

<div class="button-group">
	<div class="cgrid-header">
		<div class="color-total"> <b>Total Record :</b> &nbsp;&nbsp;<?php echo count($export) ?> &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Selected :</b> &nbsp;&nbsp;<span id="selected-row"><?php echo count($data['record'])?></span> &nbsp;&nbsp; |</div>
	</div>	
</div>

<div class="subform" style="overflow-x:sroll">
<div class="divTable" style="width: 3250px;">
	
	
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
		
		foreach($data['record'] as $k=>$jData): 	
		
		$polish_carat = ($jData['purchase_carat'] ==0) ? $jData['polish_carat'] : $jData['purchase_carat'];
		$polish_pcs = ($jData['purchase_pcs'] ==0) ? $jData['polish_pcs'] : $jData['purchase_pcs'];
		$price = ($jData['purchase_price'] ==0) ? $jData['price'] : $jData['purchase_price'];
		$amount = ($jData['purchase_amount'] ==0) ? $jData['amount'] : $jData['purchase_amount'];
	
				
		?>
		<input type="hidden" id="record-<?php echo $i?>" name="record[<?php echo $i?>][id]" value="<?php echo $jData['id']?>" />
		<input type="hidden" id="products-<?php echo $i?>" name="products[<?php echo $i?>]" value="<?php echo $jData['id']?>" />
		<div class="divTableRow" id="rowid-<?php echo $i?>">
			<div class="divTableCell">
				<i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i>			
			</div>
			<div class="divTableCell ">&nbsp; &nbsp; &nbsp; <?php echo $i?></div>			
			<div class="divTableCell"><input class=" col-sm-12 stone" rid="<?php echo $i?>" name="record[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" value="<?php echo $jData['sku']?>"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][polish_pcs]" value="<?php echo $polish_pcs ?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][polish_carat]" value="<?php echo $polish_carat?>" onchange="calAmount(<?php echo $i?>)" onblur="calAmount(<?php echo $i?>)" id="pcarat-<?php echo $i?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][cost]" value="<?php echo $jData['cost']?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][purchase_price]" value="<?php echo $price ?>" id="price-<?php echo $i?>" onblur="calAmount(<?php echo $i?>)" onchange="calAmount(<?php echo $i?>)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][purchase_amount]" value="<?php echo $amount?>" id="amount-<?php echo $i?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][location]" value="<?php echo $jData['location']?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][remark]" value="<?php echo $jData['remark']?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][lab]"  value="<?php echo $jData['lab']?>" type="text"></div>
			<div class="divTableCell">
			&nbsp; &nbsp; 
			<?php if($jData['outward'] =='sale' || $jData['outward'] =='export' ): $flag=1;?>
				<span class="infobox-green infobox-dark"><?php echo $jData['outward'] ?></span>
				<?php elseif($jData['outward'] =='memo' || $jData['outward'] =='consign' ): $flag=1;?>
				<span class="infobox-red infobox-dark"><?php echo $jData['outward'] ?></span>
			<?php else: ?>
			
			<?php endif; ?>	
			</div>
			<?php /*foreach($attribute as $key=>$v): ?>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][attr][<?php echo $key?>]" value="<?php echo $jData[$key]?>" type="text"></div>
			<?php endforeach;*/?>
		</div>
		<?php $i++;endforeach;?>
	</div>
	
</div>

</div>


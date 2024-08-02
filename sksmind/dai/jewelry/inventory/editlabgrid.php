<?php 
//$export = $helper->getInventory('sale');
$main_stone = explode(",",$data['products_receive']);
?>
<input type="hidden" name="sproducts" value="<?php echo $data['products_receive']?>" >
<div class="button-group">
	<div class="cgrid-header">
		<div class="color-total"> <b>Total Record :</b> &nbsp;&nbsp;<?php echo count($main_stone)?> &nbsp;&nbsp; |</div>		
	</div>	
</div>

<div class="subform" style="overflow-x:sroll">
<div class="divTable sale-grid" style="width: 1780px;">
	
	
	<div class="divTableHeading">
		<div class="divTableCell">&nbsp; <!-- <i class="add-more fa fa-plus "> </i> --></div>
		<div class="divTableCell">No.</div>		
		<div class="divTableCell">SKU</div>		
		<div onClick="sortForFilter('pcs')" class="divTableCell">PCS</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Carat</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Shape</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Clarity</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Color</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Price</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Amount</div>
		<?php /*foreach($attribute as $key=>$v): ?>
		<div class="divTableCell"><?php echo $v; ?></div>
		<?php endforeach; */ ?>
	</div>		
	
	<div class="divTableBody sdivtable">
		<?php 
		$i=1;
					foreach($main_stone as $id):
					if($id == "")
					continue;
					$class = $sku = "";
					$value = $model->getProductDetail($id);
					$sku = $value['sku']; 	
	    ?>
		<input type="hidden" id="record-<?php echo $i?>" name="record[<?php echo $i?>][id]" value="<?php echo $id?>" />
		<input type="hidden" id="products-<?php echo $i?>" name="products[<?php echo $i?>]" value="<?php echo $id?>" />
		<div class="divTableRow" id="rowid-<?php echo $i?>">
			<div class="divTableCell"><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i></div>
			<div class="divTableCell"><?php echo $i?></div>			
			<div class="divTableCell"><input class=" col-sm-12 stone" rid="<?php echo $i?>" name="record[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" value="<?php echo $value['sku']?>"  type="text"></div>
			<div class="divTableCell bdiv"><?php echo $value['pcs'];?></div>
			<div class="divTableCell bdiv"><?php echo $value['carat'];?></div>				
			<div class="divTableCell bdiv "><?php echo $value['shape'];?></div>				
			<div class="divTableCell bdiv "><?php echo $value['clarity'];?></div>				
			<div class="divTableCell bdiv "><?php echo $value['color'];?></div>				
			<div class="divTableCell bdiv "><?php echo $value['price'];?></div>				
			<div class="divTableCell bdiv"><?php echo $value['amount'];?></div>	
		</div>
		<?php $i++;endforeach;?>
	</div>
</div>

</div>

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
<div class="divTable sale-grid" style="width: 1450px !important;">
	
	
	<div class="divTableHeading">
		<div class="divTableCell">&nbsp; <!-- <i class="add-more fa fa-plus "> </i> --></div>
		<div class="divTableCell">No.</div>		
		<div class="divTableCell" >Jew Code</div>
				<div class="divTableCell" >Design</div>
				<div class="divTableCell" >Type</div>			
				<div class="divTableCell width-50px" >Gold</div>
				<div class="divTableCell" >Gold Color</div>
				<div class="divTableCell" >Gross Weight</div>
				<div class="divTableCell" >Pg Weight</div>
				<div class="divTableCell" >Net Weight</div>			
				<div class="divTableCell" >Gold Rate</div>
				<div class="divTableCell" >Gold Amount</div>
				<div class="divTableCell" >Total Amount</div>
				<div class="divTableCell" >Final Amount</div>
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
					$value = $model->getData($id);
					$sku = $value['sku']; 	
	    ?>
		<input type="hidden" id="record-<?php echo $i?>" name="record[<?php echo $i?>][id]" value="<?php echo $id?>" />
		<input type="hidden" id="products-<?php echo $i?>" name="products[<?php echo $i?>]" value="<?php echo $id?>" />
		<div class="divTableRow" id="rowid-<?php echo $i?>">
		<div class="divTableCell"><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i></div>
		<div class="divTableCell"><?php echo $i?></div>			
		<div class="divTableCell"><input class=" col-sm-12 stone" rid="<?php echo $i?>" name="record[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" value="<?php echo $value['sku']?>"  type="text"></div>	
					<div class="divTableCell bdiv"><?php echo $value['jew_design'] ?></div>			
					<div class="divTableCell bdiv"><?php echo $jewType[$value['jew_type']] ?></div>		
					<div class="divTableCell width-50px bdiv"><?php echo $value['gold'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $value['gold_color'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $value['gross_weight'] ?></div>
					<div class="divTableCell bdiv"><?php echo $value['pg_weight'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $value['net_weight'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $value['rate'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $value['amount'] ?></div>
					<div class="divTableCell bdiv"><?php echo $value['total_amount'] ?></div>				
					<div class="divTableCell bdiv"><?php echo $value['selling_price'] ?></div>
		</div>
		<?php $i++;endforeach;?>
	</div>
</div>

</div>

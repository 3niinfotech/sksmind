<div class="subform">
<div class="divTable" style="width: 3500px;">
	
	
	<div class="divTableHeading">
		<div class="divTableCell">&nbsp; <!-- <i class="add-more fa fa-plus "> </i> --></div>
		<div class="divTableCell">No.</div>		
		
		
		<div class="divTableCell">SKU <span class="required">*</span></div>
		<div class="divTableCell">Pcs </div>
		<div class="divTableCell">Carat <span class="required">*</span></div>
		<div class="divTableCell">Price <span class="required">*</span></div>
		<div class="divTableCell">Amount <span class="required">*</span></div>
		<div class="divTableCell">Color </div>	
		<div class="divTableCell">Clarity</div>	
		<div class="divTableCell">LOC </div>				
	</div>		
	
	<?php if(empty($TempData) || isset($_SESSION['last_inward']) ): ?>
	<div class="divTableBody">		
		<div class="divTableRow">
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">1</div>			
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][sku]" id="sku-1" onBlur="addImportRow(1)" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][pcs]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][carat]" id="pcarat-1" type="text"></div>			
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][price]" id="price-1" onBlur="calAmount(1)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][amount]" id="amount-1" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][color]" type="text"></div>	
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][clarity]" type="text"></div>	
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][location]" type="text"></div>						
			
		</div>					
	</div>
	<?php else: ?>
		
		<div class="divTableBody">
			<?php 
			$i=1;
			foreach($TempData as $td): 
			
			$jData = (array)json_decode($td['value']);
		
			?>
			
			<div class="divTableRow">
				<div class="divTableCell">&nbsp;</div>
				<div class="divTableCell"><?php echo $i?></div>			
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" value="<?php echo $jData['sku']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][pcs]" value="<?php echo $jData['pcs']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][carat]" value="<?php echo $jData['carat']?>" id="pcarat-<?php echo $i?>" type="text"></div>
				
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][price]" value="<?php echo $jData['price']?>" id="price-<?php echo $i?>" onBlur="calAmount(<?php echo $i?>)"  type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][amount]" value="<?php echo $jData['amount']?>" id="amount-<?php echo $i?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][color]" value="<?php echo $jData['color']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][clarity]" value="<?php echo $jData['clarity']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][location]" value="<?php echo $jData['location']?>" type="text"></div>
		
			</div>
			<?php $i++;endforeach;?>
		</div>
	<?php endif;?>
</div>

</div>

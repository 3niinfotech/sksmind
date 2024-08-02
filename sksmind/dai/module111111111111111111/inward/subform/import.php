<div class="subform">
<div class="divTable" style="width: 3500px;">
	
	
	<div class="divTableHeading">
		<div class="divTableCell">&nbsp; <!-- <i class="add-more fa fa-plus "> </i> --></div>
		<div class="divTableCell">No.</div>		
		<div class="divTableCell">Mfg. code</div>
		<div class="divTableCell">D. No.</div>
		<div class="divTableCell">SKU <span class="required">*</span></div>
		<div class="divTableCell">R.Pcs</div>
		<div class="divTableCell">R.Carat</div>		
		<div class="divTableCell">P.Pcs </div>
		<div class="divTableCell">P.Carat <span class="required">*</span></div>
		<div class="divTableCell">Cost</div>		
		<div class="divTableCell">Price <span class="required">*</span></div>
		<div class="divTableCell">Amount <span class="required">*</span></div>
		<div class="divTableCell">Main Color </div>	
		<div class="divTableCell">LOC </div>		
		<div class="divTableCell">Lab</div> 
		<?php foreach($attribute as $key=>$v): ?>
		<div class="divTableCell"><?php echo $v; ?></div>		
		<?php endforeach;?>
		<div class="divTableCell">Remark</div>
	</div>		
	
	<?php if(empty($TempData) || isset($_SESSION['last_inward']) ): ?>
	<div class="divTableBody">		
		<div class="divTableRow">
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">1</div>			
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][mfg_code]" id="mfg-1" onBlur="addImportRow(1)" type="text" ></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][diamond_no]" id="dno-1" onBlur="generateSku(1)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][sku]" id="sku-1" onBlur="addImportRow(1)" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][rought_pcs]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][rought_carat]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][polish_pcs]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][polish_carat]" id="pcarat-1" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][cost]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][price]" id="price-1" onBlur="calAmount(1)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][amount]" id="amount-1" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][main_color]" type="text"></div>	
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][location]" type="text"></div>			
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][lab]" type="text"></div> 
			<?php 
			
			foreach($attribute as $key=>$v): ?>
			<div class="divTableCell">			
					<input class=" col-sm-12"  name="record[1][attr][<?php echo $key?>]" type="text">
			</div>
			<?php endforeach;?>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][remark]" type="text"></div>
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
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][mfg_code]" value="<?php echo $jData['mfg_code']?>" id="mfg-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" type="text" ></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][diamond_no]" value="<?php echo $jData['diamond_no']?>" id="dno-<?php echo $i?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" value="<?php echo $jData['sku']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][rought_pcs]" value="<?php echo $jData['rought_pcs']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][rought_carat]" value="<?php echo $jData['rought_carat']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][polish_pcs]" value="<?php echo $jData['polish_pcs']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][polish_carat]" value="<?php echo $jData['polish_carat']?>" id="pcarat-<?php echo $i?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][cost]" value="<?php echo $jData['cost']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][price]" value="<?php echo $jData['price']?>" id="price-<?php echo $i?>" onBlur="calAmount(<?php echo $i?>)"  type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][amount]" value="<?php echo $jData['amount']?>" id="amount-<?php echo $i?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][main_color]" value="<?php echo $jData['main_color']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][location]" value="<?php echo $jData['location']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][lab]"  value="<?php echo $jData['lab']?>" type="text"></div>
				<?php foreach($attribute as $key=>$v): ?>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][attr][<?php echo $key?>]" value="<?php echo $jData[$key]?>" type="text"></div>
				<?php endforeach;?>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][remark]" value="<?php echo $jData['remark']?>" type="text"></div>
			</div>
			<?php $i++;endforeach;?>
		</div>
	<?php endif;?>
</div>

</div>

<div class="subform">
<div class="divTable" style="width: 3500px;">
	
	
	<div class="divTableHeading">
		<div class="divTableCell">&nbsp; <!-- <i class="add-more fa fa-plus "> </i> --></div>
		<div class="divTableCell">No.</div>		
		
		<div class="divTableCell">Kapan Code</div>
		<div class="divTableCell">SKU<span class="required">*</span></div>
		<div class="divTableCell">Color</div>	
		<div class="divTableCell">Color Code</div>
		<div class="divTableCell">Clarity</div>
		<div class="divTableCell">Shape</div>
		
		<div class="divTableCell">Pcs </div>
		<div class="divTableCell">Carat <span class="required">*</span></div>
		<div class="divTableCell">Price <span class="required">*</span></div>
		<div class="divTableCell">Amount <span class="required">*</span></div>
		
				
		<?php /* <div class="divTableCell">Final Amount </div> */ ?>				
		<div class="divTableCell">Location<span class="required">*</span></div>	
	</div>		
	
	<?php if(empty($TempData) || isset($_SESSION['last_inward']) ): ?>
	<div class="divTableBody">		
		<div class="divTableRow">
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">1</div>			
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][mfg_code]" id="mfg-1" onBlur="addImportRow(1)" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][sku]" id="sku-1" onBlur="addImportRow(1)" type="text"></div>
			<div class="divTableCell">
				<select id="color-1" name="record[1][color]" onchange="changeColor(this.value,1)">
					<?php foreach($colorAttri as $k=>$v): ?>
						<option value="<?php echo $k;?>"><?php echo $v;?></option>							
					<?php endforeach; ?>
				</select>
			</div>	
			<div class="divTableCell">
				<select id="color_code-1" name="record[1][color_code]">
					<option value="0">All Color Code</option>
				</select>
			</div>
			
			<div class="divTableCell">
				<select id="clarity-1" name="record[1][clarity]" >
					<?php foreach($clarityAttri as $k=>$v): ?>
						<option value="<?php echo $k;?>"><?php echo $v;?></option>							
					<?php endforeach; ?>
				</select>
			</div>
			<div class="divTableCell">			
				<select id="shape-1" name="record[1][shape]" >
					<?php foreach($shapeAttri as $k=>$v): ?>
						<option value="<?php echo $k;?>"><?php echo $v;?></option>							
					<?php endforeach; ?>
				</select>	
			</div>
			
			<div class="divTableCell"><input class=" col-sm-12 a-right tni-pcs"  name="record[1][pcs]" type="text" onBlur="calAmount(1); calGst()"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right tni-carat" id="pcarat-1" name="record[1][carat]" type="text" onBlur="calAmount(1); calGst()"></div>		
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][price]" id="price-1" onBlur="calAmount(1); calGst()"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right tni-amount" name="record[1][amount]" readonly id="amount-1" type="text"></div>
			
			<?php /* <div class="divTableCell"><input class=" col-sm-12" readonly id="final_amount-1" name="record[1][final_amount]" type="text"></div> */ ?>

			<div class="divTableCell">
				<select id="location-1" name="record[1][location]" >
				<?php foreach($helper->getLocation() as $k=>$v): ?>
					<option value="<?php echo $k;?>"><?php echo $v;?></option>							
				<?php endforeach; ?>
				</select>				
			</div>	
			
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
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][mfg_code]" id="mfg-<?php echo $i?>" value="<?php echo $jData['mfg_code']?>" type="text"></div>		
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" value="<?php echo $jData['sku']?>" type="text"></div>
				<div class="divTableCell">
					<select id="color-<?php echo $i?>" name="record[<?php echo $i?>][color]">
					<?php foreach($helper->getJewAttributebyCode('color') as $k=>$v): ?>
						<option value="<?php echo $k;?>" <?php if($k==$jData['color']): echo 'selected'; endif;?> ><?php echo $v;?></option>
					<?php endforeach; ?>
					</select>	
				</div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][color_code]" value="<?php echo $jData['color_code']?>" type="text"></div>
				
				<div class="divTableCell">
					<select class=" col-sm-12" id="clarity-<?php echo $i?>" name="record[<?php echo $i?>][clarity]">
					<?php foreach($helper->getJewAttributebyCode('clarity') as $k=>$v): ?>
						<option value="<?php echo $k;?>" <?php if($k==$jData['clarity']): echo 'selected'; endif;?> ><?php echo $v;?></option>
					<?php endforeach; ?>
					</select>	
				</div>
				<div class="divTableCell">
					<select class=" col-sm-12" id="shape-<?php echo $i?>" name="record[<?php echo $i?>][shape]">
					<?php foreach($helper->getJewAttributebyCode('shape') as $k=>$v): ?>
						<option value="<?php echo $k;?>" <?php if($k==$jData['shape']): echo 'selected'; endif;?> ><?php echo $v;?></option>
					<?php endforeach; ?>
					</select>	
				</div>
				
				<div class="divTableCell"><input class=" col-sm-12 a-right tni-pcs"  name="record[<?php echo $i?>][pcs]" value="<?php echo $jData['pcs']?>" type="text" onBlur="calAmount(<?php echo $i?>); calGst()"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right tni-carat"  name="record[<?php echo $i?>][carat]" value="<?php echo $jData['carat']?>" id="pcarat-<?php echo $i?>" type="text" onBlur="calAmount(<?php echo $i?>); calGst()"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][price]" value="<?php echo $jData['price']?>" id="price-<?php echo $i?>" onBlur="calAmount(<?php echo $i?>); calGst()""  type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right tni-amount"  name="record[<?php echo $i?>][amount]" value="<?php echo $jData['amount']?>" readonly id="amount-<?php echo $i?>" type="text"></div>
				
				<div class="divTableCell">
					<select id="location-<?php echo $i?>" name="record[<?php echo $i?>][location]">
					<?php foreach($helper->getLocation() as $k=>$v): ?>
						<option value="<?php echo $k;?>" <?php if($k==$jData['location']): echo 'selected'; endif;?> ><?php echo $v;?></option>
					<?php endforeach; ?>
					</select>				
				</div>
			</div>
			<?php $i++;endforeach;?>
		</div>
	<?php endif;?>
</div>

</div>
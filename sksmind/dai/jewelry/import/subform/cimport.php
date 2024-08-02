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
		<div class="divTableCell">Weight<span class="required">*</span></div>
		<div class="divTableCell">Price <span class="required">*</span></div>
		<div class="divTableCell">Amount<span class="required">*</span></div>	
		<div class="divTableCell">Cost price</div>	
		<div class="divTableCell">Cost Amount</div>	
		<div class="divTableCell">Report No<span class="required">*</span></div>	
		<div class="divTableCell">Remarks</div>
		<div class="divTableCell">Gold Color</div>
		<div class="divTableCell">Gold Carat</div>
		<div class="divTableCell">Gross Weight</div>
		<div class="divTableCell">Net Weight</div>
		<div class="divTableCell">Pg Weight</div>
		<div class="divTableCell">Gold Rate</div>
		<div class="divTableCell">Gold Amount</div>
		<div class="divTableCell">Other Code</div>
		<div class="divTableCell">Other Amount</div>
		<div class="divTableCell">Labour Rate</div>
		<div class="divTableCell">Labour Amt</div>
		<div class="divTableCell">Percentage</div>
		<div class="divTableCell">Total Amount</div>
		<div class="divTableCell">T.Cost Amt</div>
		
	</div>		
		
	<?php if(empty($TempData) || isset($_SESSION['last_inward']) ): ?>
	<div class="divTableBody">		
		<div class="divTableRow">
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">1</div>		
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][mfg_code]" id="mfg-1" onBlur="addImportRow(1)" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][sku]" id="sku-1" onBlur="addImportRow(1)" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][color]" type="text"></div>	
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][color_code]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12  a-right"  name="record[1][clarity]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][shape]" type="text"></div>	
			
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][pcs]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="pcarat-1" name="record[1][carat]" type="text"></div>		
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][price]" id="price-1" onBlur="calAmount(1)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][amount]" id="amount-1" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="cost-1" onBlur="calCostAmount(1)" name="record[1][cost]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="cost_amount-1" name="record[1][cost_amount]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][report_no]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][remarks]" type="text"></div>
			<div class="divTableCell">
					<select class="col-sm-12 skph" id="goldcolor-1" name="collet[1][collet_color]">
							<option value="">Gold Color</option>
							<?php 
							foreach($goldColor as $key => $value):
							?>						
							<option value="<?php echo $key?>"><?php echo $value?></option>
							<?php endforeach; ?>
					</select>
			</div>
					<div class="divTableCell"><select class="col-sm-12 skph" id="gold-1" name="collet[1][collet_kt]" onBlur="calculateTotalAmountColl(1)" onChange="calculateTotalAmountColl(1)">
							<option value="">Gold Carat</option>
							<?php 
							foreach($gold as $key => $value):
							?>						
							<option value="<?php echo $key?>"><?php echo $value?></option>
							<?php endforeach; ?>
					</select></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right" id="gross_weight-1" onBlur="calculateTotalAmountColl(1)" onChange="calculateTotalAmountColl(1)" name="collet[1][gross_weight]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right"  id="net_weight-1" name="collet[1][net_weight]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right" id="pg_weight-1"  name="collet[1][pg_weight]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right" id="collet_rate-1" onBlur="calculateTotalAmountColl(1)" onChange="calculateTotalAmountColl(1)" name="collet[1][collet_rate]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right" id="collet_amount-1"  name="collet[1][collet_amount]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right" id="other_code-1"  name="collet[1][other_code]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right" id="other_amount-1" onBlur="calculateTotalAmountColl(1)" onChange="calculateTotalAmountColl(1)" name="collet[1][other_amount]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right" id="labour_rate-1"  onBlur="calculateTotalAmountColl(1)" onChange="calculateTotalAmountColl(1)" name="collet[1][labour_rate]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right" id="labour_amount-1"   name="collet[1][labour_amount]" placeholder="" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right" name="collet[1][percentage]" placeholder="" type="text"></div>	
					<div class="divTableCell"><input class=" col-sm-12 a-right" id="ftotal_amount-1"   name="collet[1][total_amount]" placeholder="" type="text"></div>	
					<div class="divTableCell"><input class=" col-sm-12 a-right" name="collet[1][total_amount_cost]" placeholder="" type="text"></div>	
			
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
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][color]" value="<?php echo $jData['color']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][color_code]" value="<?php echo $jData['color_code']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][clarity]" value="<?php echo $jData['clarity']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][shape]" value="<?php echo $jData['shape']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][pcs]" value="<?php echo $jData['pcs']?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][carat]" value="<?php echo $jData['carat']?>" id="pcarat-<?php echo $i?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][price]" value="<?php echo $jData['price']?>" id="price-<?php echo $i?>" onBlur="calAmount(<?php echo $i?>)"  type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][amount]" value="<?php echo $jData['amount']?>" id="amount-<?php echo $i?>" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][remarks]" value="<?php echo $jData['remarks']?>" type="text"></div>
		
			</div>
			<?php $i++;endforeach;?>
		</div>
	<?php endif;?>
</div>

</div>

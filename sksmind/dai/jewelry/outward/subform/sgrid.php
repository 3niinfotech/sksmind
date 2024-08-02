<?php 
//$export = $helper->getInventory('sale');
$mc = 0;
if(isset($data['record']['main_stone']))	
$mc += count($data['record']['main_stone']);
if(isset($data['record']['side_stone']))				
$mc += count($data['record']['side_stone']);
if(isset($data['record']['collet_stone']))				
$mc += count($data['record']['collet_stone']);
?>
<input type="hidden" name="smain_stone" value="<?php echo $data['main_stone']?>" >
<input type="hidden" name="sside_stone" value="<?php echo $data['side_stone']?>" >
<input type="hidden" name="scollet_stone" value="<?php echo $data['collet_stone']?>" >
<div class="button-group">
	<div class="cgrid-header" style="width:75%">
		<div class="color-total"> <b>Total Record :</b> &nbsp;&nbsp;<?php echo $mc?> &nbsp;&nbsp; |</div>		
	</div>	
		<label for="form-field-2" class="col-sm-1 control-label no-padding-right">Add Stone</label>
		<div class="col-sm-2">
			<select id="diamond" name="diamond" class="col-xs-8">
				<option value="">Select Type</option>			
				<option value="main" selected="selected">Main Stone</option>
				<option value="side">Side Stone</option>
			</select>
		</div>
</div>

<div class="subform" style="overflow-x:sroll">
<div class="divTable sale-grid" style="width: 1550px;">
	
	
	<div class="divTableHeading">
		<div class="divTableCell">&nbsp; <!-- <i class="add-more fa fa-plus "> </i> --></div>
		<div class="divTableCell">No.</div>		
		<div class="divTableCell">SKU</div>
		<div class="divTableCell">P.Pcs</div>
		<div class="divTableCell">P.Carat</div>
		<div class="divTableCell">Price</div>
		<div class="divTableCell">Amount</div>
		<div class="divTableCell">Remark</div>
		<div class="divTableCell">Lab</div>
		<div class="divTableCell">IGI Code</div>
		<div class="divTableCell">IGI Color</div>
		<div class="divTableCell">IGI Clarity</div>
		<div class="divTableCell">IGI Amount</div>
	</div>		
	
	<div class="divTableBody sdivtable">
		<?php 
		$i=1;
		if(isset($data['record']['collet_stone'])):
		foreach($data['record']['collet_stone'] as $k=>$jData): 	?>
		<div class="divTableRow" id="rowid-<?php echo $i?>">
			<div class="divTableCell"><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i><input type="hidden" id="crecord-<?php echo $i?>" name="crecord[<?php echo $i?>][id]" value="<?php echo $jData['id']?>" />
			<input type="hidden" id="collet_stone-<?php echo $i?>" name="collet_stone[<?php echo $i?>]" value="<?php echo $jData['id']?>" /></div>
			<div class="divTableCell"><?php echo $i?></div>			
			<div class="divTableCell"><input class=" col-sm-12 stone" rid="<?php echo $i?>" name="crecord[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" value="<?php echo $jData['sku']?>"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="crecord[<?php echo $i?>][pcs]" value="<?php echo $jData['pcs']?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="crecord[<?php echo $i?>][carat]" value="<?php echo $jData['carat']?>" onchange="calAmount(<?php echo $i?>)" id="pcarat-<?php echo $i?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="crecord[<?php echo $i?>][sell_price]" value="<?php echo ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'] ?>" id="price-<?php echo $i?>" onchange="calAmount(<?php echo $i?>)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="crecord[<?php echo $i?>][sell_amount]" value="<?php echo $jData['sell_amount']?>" id="amount-<?php echo $i?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="crecord[<?php echo $i?>][remark]" value="<?php echo $jData['remark']?>" type="text"></div>
			<div class="divTableCell bdiv "><?php echo $jData['lab'];?></div>
			<div class="divTableCell bdiv "><?php echo $jData['igi_code'];?></div>			
			<div class="divTableCell bdiv "><?php echo $jData['igi_color'];?></div>			
			<div class="divTableCell bdiv a-right "><?php echo $jData['igi_clarity'];?></div>		
			<div class="divTableCell bdiv a-right "><?php echo $jData['igi_amount'];?></div>
		</div>
		<?php $i++;endforeach;
		endif;
		?>
		<?php 
		if(isset($data['record']['main_stone'])):
		foreach($data['record']['main_stone'] as $k=>$jData): 	?>
		<div class="divTableRow" id="rowid-<?php echo $i?>">
			<div class="divTableCell"><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i><input type="hidden" id="mrecord-<?php echo $i?>" name="mrecord[<?php echo $i?>][id]" value="<?php echo $jData['id']?>" />
			<input type="hidden" id="main_stone-<?php echo $i?>" name="main_stone[<?php echo $i?>]" value="<?php echo $jData['id']?>" /></div>
			<div class="divTableCell"><?php echo $i?></div>			
			<div class="divTableCell"><input class=" col-sm-12 stone" rid="<?php echo $i?>" name="mrecord[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" value="<?php echo $jData['sku']?>"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $i?>][pcs]" value="<?php echo $jData['pcs']?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $i?>][carat]" value="<?php echo $jData['carat']?>" onchange="calAmount(<?php echo $i?>)" id="pcarat-<?php echo $i?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $i?>][sell_price]" value="<?php echo ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'] ?>" id="price-<?php echo $i?>" onchange="calAmount(<?php echo $i?>)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $i?>][sell_amount]" value="<?php echo ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'] ?>" id="amount-<?php echo $i?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="mrecord[<?php echo $i?>][remark]" value="<?php echo $jData['remark']?>" type="text"></div>
			<div class="divTableCell bdiv "><?php echo $jData['lab'];?></div>
			<div class="divTableCell bdiv "><?php echo $jData['igi_code'];?></div>			
			<div class="divTableCell bdiv "><?php echo $jData['igi_color'];?></div>			
			<div class="divTableCell bdiv a-right "><?php echo $jData['igi_clarity'];?></div>		
			<div class="divTableCell bdiv a-right "><?php echo $jData['igi_amount'];?></div>
		</div>
		<?php $i++;endforeach;
		endif;
		?>
		<?php
		if(isset($data['record']['side_stone'])):
		foreach($data['record']['side_stone'] as $k=>$jData): 	
				if($jData['outward_parent'] == 0)
				{
					$sku = $jData['sku'];
				}
				else
				{
					$parentData = $jhelper->getSideProductDetail($jData['outward_parent']);					
					$sku = $parentData['sku'];
				}
		?>
		
		<div class="divTableRow" id="rowid-<?php echo $i?>">
			<div class="divTableCell"><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i?>)" ></i><input type="hidden" id="record-<?php echo $i?>" name="record[<?php echo $i?>][id]" value="<?php echo $jData['id']?>" />
			<input type="hidden" id="side_stone-<?php echo $i?>" name="side_stone[<?php echo $i?>]" value="<?php echo $jData['id']?>" /></div>
			<div class="divTableCell"><?php echo $i?></div>			
			<div class="divTableCell"><input class=" col-sm-12 stone" rid="<?php echo $i?>" name="record[<?php echo $i?>][sku]" id="sku-<?php echo $i?>" onBlur="addImportRow(<?php echo $i?>)" value="<?php echo $sku?>"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][pcs]" value="<?php echo $jData['pcs']?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][carat]" value="<?php echo $jData['carat']?>" onchange="calAmount(<?php echo $i?>)" id="pcarat-<?php echo $i?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][sell_price]" value="<?php echo ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'] ?>" id="price-<?php echo $i?>" onchange="calAmount(<?php echo $i?>)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[<?php echo $i?>][sell_amount]" value="<?php echo ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'] ?>" id="amount-<?php echo $i?>" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i?>][remark]" value="<?php echo $jData['remark']?>" type="text"></div>
			<div class="divTableCell bdiv "></div>
			<div class="divTableCell bdiv "></div>			
			<div class="divTableCell bdiv "></div>			
			<div class="divTableCell bdiv a-right "></div>			
			<div class="divTableCell bdiv a-right "></div>
		</div>
		<?php $i++;endforeach;
		endif;
		?>
	</div>
	
</div>

</div>


<?php 
$inventory = $model->getMyInventory();
?>
<form class="form-horizontal" method="POST" role="form" action="<?php echo $moduleUrl.'single/singleController.php' ?>" >
<input type="hidden" name="fn" value="toBox" />
<input type="hidden" name="type" value="parcel" />

<div class="inventory-container" style="padding:10px;">
	<div class="inventory-color">
		<div class="inventory-color-cell" style="width:100%;padding-bottom:20px">		
			<div class="color-total" style=""> <b>Total Record :</b> &nbsp;&nbsp;<?php echo count($inventory) ?> &nbsp;&nbsp; |</div>
			<div class="color-total"> <b>Selected :</b> &nbsp;&nbsp;<span id="selected-row">0</span> &nbsp;&nbsp; |</div>
			<div class="color-total color-total-count"> <b>Select Pcs :</b> &nbsp;&nbsp;<span id="total-pcs">0</span> &nbsp;&nbsp; |</div>
			<div class="color-total color-total-count"> <b>Select Carats :</b> &nbsp;&nbsp;<span id="total-carat">0</span> &nbsp;&nbsp; |</div>
			<div class="color-total color-total-count"> <b>Select Price :</b> &nbsp;&nbsp;<span id="total-price">0</span> &nbsp;&nbsp; |</div>
			<div class="color-total color-total-count"> <b>Select Amount :</b> &nbsp;&nbsp;<span id="total-amount">0</span> &nbsp;&nbsp; |</div>
			
		</div>
		
		<div class="inventory-color-cell" >
			<div class="color-cube infobox-blue infobox-dark"> </div>
			<div class="color-total"> GIA Certified </div>
		</div>
		<div class="inventory-color-cell" >
			<div class="color-cube infobox-red infobox-dark"> </div>
			<div class="color-total"> On Memo </div>
		</div>
		<div class="inventory-color-cell" >
			<div class="color-cube infobox-green infobox-dark"> </div>
			<div class="color-total"> Send to LAB </div>
		</div>
		<div class="inventory-color-cell" >
			<div class="color-cube infobox-white infobox-dark" style="border: 1px solid rgb(0, 0, 0) ! important;"> </div>
			<div class="color-total"> Non-GIA </div>
		</div>
		
	</div>
</div>

<div class="col-xs-12 group-grid inventory-grid">
<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->
<div>
	<table  id="dynamic-table" class="inventory-table table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="center"><label class="pos-rel">
						<input type="checkbox" class="ace" onClick="checkAll(this);"  />
						<span class="lbl"></span>
					</label></th>
				<th>Mfg.code</th>
				<th>Sku</th>
				<th>P.Pcs</th>
				<th>P.Carat</th>				
				<th>Type</th>
				<th>Cost</th>
				<th>Price</th>
				<th>Amount</th>	
				<th>LOC</th>	
				<th>Remark</th>
				<th>Lab</th>
				<?php foreach($attribute as $key=>$v): ?>
				<th><?php echo $v; ?></th>
				<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
			
			<?php 
			$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
			foreach($inventory as $value):
			
			$class ="";
			if($value['outward'] =="lab")
			{
				$class ="infobox-green infobox-dark";
			}
			else if($value['lab'] !="")
			{
				$class ="infobox-blue infobox-dark";
			}
			else if($value['outward'] =="memo")
			{
				$class ="infobox-red infobox-dark";
			}
			
			?>						
			<tr class="<?php echo $class; ?>">
				<td class="center">
					<label class="pos-rel">
						<input type="checkbox" class="ace" name="products[]" value="<?php echo $value['id']; ?>" onClick="countCheck(this);"  />
						<span class="lbl"></span>
					</label>
				</td>

				<td><?php echo $value['mfg_code'];echo($value['diamond_no']!="" )?'-'.$value['diamond_no']:''; ?>
					
				</td>				
				<td class="pcs"><?php echo $value['sku']?></td>
				<td class="pcs"><?php echo $value['polish_pcs']?></td>
				<td class="carats"><?php echo $value['polish_carat']?></td>							
				<td><?php echo strtoupper($value['group_type'])?></td>
				<td ><?php echo $value['cost']?></td>
				<td class="price"><?php echo $value['price']?></td>
				<td class="amount"><?php echo $value['amount']?></td>
				<td><?php echo $value['location']?></td>
				<td><?php echo $value['remark']?></td>
				<td><?php echo $value['lab']?></td>
				<?php foreach($attribute as $key=>$v): ?>
				<td><?php echo (isset($value[$key]))?$value[$key]:'&nbsp;'; ?></td>
				<?php endforeach;?>
			</tr>	
			<?php 
			$tpcs = (float)$tpcs +  (float)$value['polish_pcs'];
			$tcarat = (float)$tcarat +  (float)$value['polish_carat'];
			$tcost = (float)$tcost +  (float)$value['cost'];
			$tprice = (float)$tprice +  (float)$value['price'];
			$tamount = (float)$tamount +  (float)$value['amount'];
			endforeach; ?>
		</tbody>
		
	</table>
</div>
</div>

<div class="inventory-container">
	
	<div class="inventory-total" >
		<div class="int-lable">Pcs :<span class="int-total"> <?php echo $tpcs ?> </span> 
		</div>
	</div>
	<div class="inventory-total" >
		<div class="int-lable">Carat :<span class="int-total"> <?php echo $tcarat ?> </span>
		</div>
	</div>	
	<div class="inventory-total" >
		<div class="int-lable">Avg. Price :<span class="int-total"> <?php echo number_format(((float)$tprice/(float)$tcarat ),2,",","")  ?> </span>
		</div>
	</div>
	<div class="inventory-total" >
		<div class="int-lable">Amount :<span class="int-total"> <?php echo $tamount ?> </span>
		</div>
	</div>		
</div>
<div class="dialog-box-container" id="dialog-box-container" style="display:none;" >
	<div class="box-container" style="width:600px;">
		<div class="col-xs-12 col-sm-12">
		<div class="control-group">
			<label class="control-label bolder blue col-sm-12" style=" margin-bottom: 15px;text-align:left;">Add Selected Diamond to Box</label>		

			<div class="radio col-sm-6">
				<label>
					<input name="type" class="ace input-lg" type="radio" checked value="existing">
					<span class="lbl bigger-120"> Use Existing Parcel</span>
				</label>
			</div>
			
			<div class="radio col-sm-6">
				<label>
					<input name="type" class="ace input-lg" type="radio" value="new">
					<span class="lbl bigger-120"> Create New Parcel</span>
				</label>
			</div>
			<div id="box-existing" class="box-form ">
				<div class="form-group col-sm-6">
					<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Parcel</label>
					<div class="col-sm-8">
						<select class="col-xs-12" id="boxcode" name="boxcode" onChange="getBoxDetail(this.value)">
							<option value="">Select Parcel</option>
													
							<?php foreach($helper->getProfuctGroup('parcel')  as $key=>$value):?>
							<option value="<?php echo $key?>"><?php echo $value?></option>
							<?php endforeach;?>			
						</select>
					</div>
				</div>
				<div class="form-group col-sm-6">
					<label class="col-sm-4 control-label no-padding-right" for="form-field-4">New Sku</label>
					<div class="col-sm-8">
						<input class="input-sm col-sm-12" id="sku" value="" name="esku" placeholder="New Sku Box" type="text">
					</div>
				</div>
				<div class="existing-box" >
						
				</div>
			</div>
			
			<div id="box-new" class="box-form no-display">
				<div class="form-group col-sm-6">
					<label class="col-sm-4 control-label no-padding-right" for="form-field-4">New Parcel</label>
					<div class="col-sm-8">
						<input class="input-sm col-sm-12" id="terms" value="" name="newbox" placeholder="Create New Box" type="text">
					</div>
				</div>
				<div class="form-group col-sm-6">
					<label class="col-sm-4 control-label no-padding-right" for="form-field-4">New Sku</label>
					<div class="col-sm-8">
						<input class="input-sm col-sm-12" id="sku" value="" name="sku" placeholder="New Sku Box" type="text">
					</div>
				</div>
				<div style="clear:both"></div>
				
				<div class="form-group col-sm-6">
					<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Total Pcs</label>
					<div class="col-sm-8">
						<input class="input-sm col-sm-12" id="box-total-pcs" value=""  readonly  type="text">
					</div>
				</div>
				
				<div class="form-group col-sm-6">
					<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Carats</label>
					<div class="col-sm-8">
						<input class="input-sm col-sm-12" id="box-total-carat" value=""  readonly  type="text">
					</div>
				</div>
				
				<div class="form-group col-sm-6">
					<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Price</label>
					<div class="col-sm-8">
						<input class="input-sm col-sm-12" id="box-total-price" value="" readonly  type="text">
					</div>
				</div>
				
				<div class="form-group col-sm-6">
					<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Amount</label>
					<div class="col-sm-8">
						<input class="input-sm col-sm-12" id="box-total-amount" value="" readonly placeholder="Create New Box" type="text">
					</div>
				</div>
				
			</div>
			
			<div class="col-md-12" style="text-align: center; margin-top: 20px;">
				
				<button class="btn btn-info" type="submit">
					<i class="ace-icon fa fa-check bigger-110"></i>
					Create Parcel
				</button>

				&nbsp; &nbsp; &nbsp;
				<button id="close-box" class="btn reset" type="button">
					<i class="ace-icon fa fa-close bigger-110"></i>
					Cancel
				</button>
			</div>
		</div>
		</div>
	</div>
	</div>
</div>
</form>

<?php 
$inventory = $model->getMyInventory($sku);
$width50 = $helper->width50();
?>

<form class="form-horizontal" method="POST" id="form-package" role="form" action="<?php echo $moduleUrl.'single/singleController.php' ?>" >
<input type="hidden" name="fn" value="boxToParcel" />
<input type="hidden" id="box-id" name="id" value="" />
<input type="hidden" name="type" value="parcel" />
<div class="inventory-container" style="padding:10px;">
	<div class="inventory-color">
		
		<div class="inventory-color-cell" >
			<b>Total Record :</b> &nbsp;&nbsp;<?php echo count($inventory) ?> &nbsp;
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

<div class="col-xs-12 ">


<div class="subform box-grid invenotry-cgrid ">
		<div class="divTable" >
			<div class="divTableHeading">
				
				<div class="divTableCell"><label class="pos-rel">
						<input class="ace" onclick="calcSelecteds(this)" type="checkbox">
						<span class="lbl"></span>
					</label>
					</div>				
				
				<div class="divTableCell"  style="width:150px" >SKU</div>
				<div class="divTableCell width-50px">Pcs</div>
				<div class="divTableCell width-50px">Carat</div>				
				<div class="divTableCell width-70px">Price</div>
				<div class="divTableCell width-70px">Amount</div>				
				<?php foreach($attribute as $key=>$v): ?>
				<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $v; ?></div>
				<?php endforeach;?>
			</div>	
			<div class="outward divTableBody">
				<?php 
				foreach($inventory as $jData):	
				
				if($jData['outward_parent'] == 0)
				{
					$sku = $jData['sku'];
				}
				else
				{
					$parentData = $helper->getProductDetail ($jData['outward_parent']);					
					$sku = $parentData['sku'];
				}
				?>
				
				<div class="outward divTableRow">					
					<div class="divTableCell">					
					</div>						
					<div class="divTableCell" style="text-align:left !important;width:150px;" > 
					<?php 
					//echo $jData['box_products'];
						$id = $jData['id'];
						/* if(strtoupper($jData['group_type']) =='BOX' && $jData['box_products'] !="" )
						{	
							 echo '<a style="cursor:pointer; text-align:left !important;" onClick="loadBox('.$id.',1)" >  <i class="ace-icon fa  orange fa-codepen bigger-110"></i> sss'.$sku .'</a>'; 
						}				 
						else
						{ */
							echo '<a style="cursor:pointer;text-align:left !important;" onClick="loadSeprateBox('.$id.')" >  <i class="ace-icon fa  orange fa-codepen bigger-110"></i> '.$sku .'</a>'; 
						//}
						?>
					</div>
					<div class="divTableCell a-right width-50px">  <?php echo $jData['polish_pcs']?> </div>
					<div class="divTableCell a-right width-50px">  <?php echo $jData['polish_carat']?>  </div>
					<div class="divTableCell a-right width-70px">  <?php echo $jData['price']?></div>
					<div class="divTableCell a-right width-70px">  <?php echo $jData['amount']?></div>
					<?php foreach($attribute as $key=>$v): ?>
					<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo (isset($jData[$key]))?$jData[$key]:'&nbsp;';?></div>
					<?php endforeach;?>
				</div>
				<?php endforeach;?>				
				
			</div>
		</div>
	</div>
</div>

<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->


<div class="dialog-box-container" id="dialog-parcel-container" style="display:none;" >
	<div class="box-container" style="width:600px;">
		<div class="col-xs-12 col-sm-12">
		<div class="control-group">
			<label class="control-label bolder blue col-sm-12" style=" margin-bottom: 15px;text-align:left;">Add Selected Diamond to Parcel</label>		

			<div class="radio col-sm-6">
				<label>
					<input name="btype" class="ace input-lg" type="radio" checked value="existing">
					<span class="lbl bigger-120"> Use Existing Parcel</span>
				</label>
			</div>
			
			<div class="radio col-sm-6">
				<label>
					<input name="btype" class="ace input-lg" type="radio" value="new">
					<span class="lbl bigger-120"> Create New Parcel</span>
				</label>
			</div>
			<div id="box-existing" class="box-form ">
				<div class="form-group col-sm-6">
					<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Select Box</label>
					<div class="col-sm-8">
						<select class="col-xs-12" id="boxcode" name="boxcode" onChange="getBoxDetail(this.value)">
							<option value="">Parcel</option>
													
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
					<label class="col-sm-4 control-label no-padding-right" for="form-field-4">New Box</label>
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
				
				<button class="btn btn-info" type="button" onClick="parcelFromBox()">
					<i class="ace-icon fa fa-check bigger-110"></i>
					Save Parcel
				</button>

				&nbsp; &nbsp; &nbsp;
				<button id="close-box" class="btn reset" type="button" onClick="closeParcelPopup()" >
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

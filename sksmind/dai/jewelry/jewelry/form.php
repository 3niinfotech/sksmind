<?php
//include('model.php');
//$model  = new Model($cn);
include_once($daiDir.'jewelry/inventory/inventoryModel.php');
$imodel  = new inventoryModel($cn);
$entryno = $model->getIncrementEntry('inward');
$t = "";

if(isset($_GET['t']))
{
		$t = $_GET['t'];
}
$tcpcs = $tccarat = $tccost = $tcprice = $tcamount = 0.00;
$tspcs = $tscarat = $tscost = $tsprice = $tsamount = 0.00;
$tmpcs = $tmcarat = $tmcost = $tmprice = $tmamount = 0.00;
?>
<form class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $jewelryUrl.'jewelry/jewelryController.php'?>">
<input type="hidden" name="fn" value="save" />
<?php if(isset($_GET['id']))
{	
	
?>
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
<?php } ?>
<div class="col-xs-12 col-sm-12 ">
	<div class="form-group col-sm-5">
		<label class="col-sm-7 control-label no-padding-right" for="form-field-4">Jewelry Name</label>
		<div class="col-sm-5">
			<input class="input-sm col-sm-10" name="name" value="<?php echo $data['name']?>" placeholder="Jewelry Name" type="text">
		</div>
	</div>
	<div class="form-group col-sm-2">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">SKU</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" name="sku" value="<?php echo $data['sku']?>" placeholder="Sku" type="text">
		</div>
	</div>
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Gross Wei.</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-10 a-right" id="gross_weight" value="<?php echo $data['gross_weight']?>" name="gross_weight" onBlur="calJewelry()" placeholder="" type="text">
		</div>
	</div>	
	<div class="form-group col-sm-2">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Date</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="date" value="<?php echo $data['date']?>"  name="date" placeholder="Select Date" type="text">
		</div>
	</div>
</div>
<div style="clear:both"></div>
	<hr>
<div class="col-xs-12 col-sm-12 jewelry-job">
<?php if(!empty($data['collet_stone'])): ?>
<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Collet</h3>
	<div class="subform colletstone invenotry-cgrid main-grid">
		<div class="divTable " style="width:1550px" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>					
				<div class="divTableCell">SKU</div>
				<div class="divTableCell" style="width:73px">PCS</div>
				<div class="divTableCell" style="width:73px">Carat</div>
				<div class="divTableCell">Clarity</div>
				<div class="divTableCell">Color</div>
				<div class="divTableCell">Price</div>
				<div class="divTableCell">Amount</div>
				<div class="divTableCell" style="width:85px">Gross WT</div>
				<div class="divTableCell" style="width:73px">Net Wt</div>
				<div class="divTableCell" style="width:73px">PG WT</div>
				<div class="divTableCell">Gold Amt</div>
				<div class="divTableCell">Labour Amt</div>
				<div class="divTableCell">Other Amt</div>
				<div class="divTableCell">Total</div>
				<div class="divTableCell">Cost Amount</div>
				
			</div>	
			<div class="divTableBody" >
				
				
				<?php 
				$tccarat = $tamount = $tgross = $tnet = $tpg = 0.00;
				$i = 0;
				foreach($data['collet_stone'] as $id):
				$i++;
				$class ="infobox-grey infobox-dark";
				$value = $imodel->getDetail($id['id']);
				$cvalue = $model->getDataCollet($id['id']);
				?>
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
					<?php echo $i;?>
					</div>
					<div class="divTableCell  "><?php echo $value['sku'];?><input class=" col-sm-12 a-right"  name="crecord[<?php echo $id['id'] ?>][carat]" value="<?php echo $value['carat'] ?>"  type="hidden"></div>
					<div class="divTableCell  a-right" style="width:73px"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right" id="ccarat-<?php echo $i ?>" style="width:73px"><?php echo $value['carat'];?></div>
					<div class="divTableCell  "><input class=" col-sm-12 a-right"  name="crecord[<?php echo $id['id'] ?>][clarity]" value="<?php echo $value['clarity'] ?>"  type="text"></div>
					<div class="divTableCell  "><input class=" col-sm-12 a-right"  name="crecord[<?php echo $id['id'] ?>][color]" value="<?php echo $value['color'] ?>"  type="text"></div>
					<div class="divTableCell  a-right" ><input class=" col-sm-12 a-right"  name="crecord[<?php echo $id['id'] ?>][price]" id="cprice-<?php echo $i ?>" onChange="ccalAmount(<?php echo $i ?>)" onBlur="ccalAmount(<?php echo $i ?>)" value="<?php echo $value['price'] ?>"  type="text"></div>				
					<div class="divTableCell  a-right" id="camount-<?php echo $i ?>"><?php echo $value['amount']?></div>
					<div class="divTableCell  a-right" style="width:85px"><?php echo $cvalue['gross_weight'];?></div>	
					<div class="divTableCell  a-right" style="width:73px"><?php echo $cvalue['net_weight'];?></div>	
					<div class="divTableCell  a-right" style="width:73px"><?php echo $cvalue['pg_weight'];?><input class=" col-sm-12 a-right"  name="crecord[<?php echo $id['id'] ?>][total_amount]" id="ctamount-<?php echo $i ?>" value="<?php echo $cvalue['total_amount'] ?>"  type="hidden"></div>
					<div class="divTableCell  a-right" id="coll-ca-<?php echo $i ?>"><?php echo $cvalue['collet_amount'];?></div>	
					<div class="divTableCell  a-right" id="coll-oa-<?php echo $i ?>"><?php echo $cvalue['other_amount'];?></div>	
					<div class="divTableCell  a-right" id="coll-la-<?php echo $i ?>"><?php echo $cvalue['labour_amount'];?></div>	
					<div class="divTableCell  a-right" id="coll-total-<?php echo $i ?>"><?php echo $cvalue['total_amount'];?></div>
					<div class="divTableCell  a-right amount1" id="coll-total-cost-<?php echo $i ?>"><?php echo $cvalue['total_amount_cost'];?></div>	
				</div>
				<?php 
			
			$tccarat = (float)$tccarat +  (float)$value['carat'];
			$tgross = (float)$tgross +  (float)$cvalue['gross_weight'];
			$tnet = (float)$tnet +  (float)$cvalue['net_weight'];
			$tpg = (float)$tpg +  (float)$cvalue['pg_weight'];
			$tcamount = (float)$tcamount +  (float)$cvalue['total_amount_cost'];
			
			endforeach; ?>	
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if(!empty($data['main_stone'])): ?>
<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Main Stone</h3>
	<div class="subform mainstone invenotry-cgrid main-grid">
		<div class="divTable" style="width: 922px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>					
				<div class="divTableCell">SKU</div>
				<div class="divTableCell">PCS</div>
				<div class="divTableCell">Carat</div>
				<div class="divTableCell">Shape</div>
				<div class="divTableCell">Clarity</div>
				<div class="divTableCell">Color</div>
				<div class="divTableCell">Price</div>
				<div class="divTableCell">Amount</div>
			</div>	
			<div class="divTableBody" >
				<?php 
					$tpcs = $tmcarat = $tcost = $tprice = $tamount = 0.00;
					$i = 0 ;
					foreach($data['main_stone'] as $id):
					$i++;
					$class ="infobox-grey infobox-dark";
					$mvalue = $imodel->getDetail($id['id']);
					/*  echo "<pre>";
					print_r($mvalue);
					exit;  */
					?>
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?>
					</div>
						
					<div class="divTableCell  "><?php echo $mvalue['msku'];?><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $id['id'] ?>][carat]" value="<?php echo $mvalue['carat'] ?>"  type="hidden"></div>
					<div class="divTableCell  a-right"><?php echo $mvalue['pcs'];?></div>
					<div class="divTableCell  a-right" id="mcarat-<?php echo $i ?>"><?php echo $mvalue['carat'];?></div>
					<div class="divTableCell  "><?php echo $mvalue['shape'];?></div>				
					<div class="divTableCell  "><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $id['id'] ?>][clarity]" value="<?php echo $mvalue['clarity'] ?>"  type="text"></div>				
					<div class="divTableCell  "><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $id['id'] ?>][color]" value="<?php echo $mvalue['color'] ?>"  type="text"></div>				
					<div class="divTableCell  a-right"><input class=" col-sm-12 a-right"  name="mrecord[<?php echo $id['id'] ?>][price]" id="mprice-<?php echo $i ?>" onChange="mcalAmount(<?php echo $i ?>)" onBlur="mcalAmount(<?php echo $i ?>)" value="<?php echo $mvalue['price'] ?>"  type="text"></div>			
					<div class="divTableCell  a-right amount1" id="mamount-<?php echo $i ?>"><?php echo $mvalue['amount']?></div>
				</div>
				<?php 
			$tpcs = (int)$tpcs +  (int)$mvalue['pcs'];
			$tmcarat = (float)$tmcarat +  (float)$mvalue['carat'];
			$tcost = (float)$tcost +  (float)$mvalue['cost'];
			$tprice = (float)$tprice +  (float)$mvalue['price'];
			$tmamount = (float)$tmamount +  (float)$mvalue['amount'];
			endforeach; ?>
			
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php if($data['side_stone']):?>
<div class="form-group col-sm-12 ">
	<h3>Side Stone</h3>
	<div class="subform sidestone invenotry-cgrid main-grid jewside">
		<div class="divTable " style="width:922px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					
				</div>					
				<div class="divTableCell">SKU</div>
				<div class="divTableCell">PCS</div>
				<div class="divTableCell">Carat</div>
				<div class="divTableCell">Shape</div>
				<div class="divTableCell">Clarity</div>
				<div class="divTableCell">Color</div>
				<div class="divTableCell">Price</div>
				<div class="divTableCell">Amount</div>
				
				
			</div>	
			<div class="divTableBody" >
				
				
				<?php 
					$tscarat = 0.00;
					$i= 0;
					foreach($data['side_stone'] as $id):
					$i++;
					$class = "infobox-grey infobox-dark";
					$value = $imodel->getDetail($id['id'],'loose');
					if($value['outward_parent'] == 0)
					{
						$sku = $value['sku'];
					}
					else
					{
						$parentData = $jhelper->getSideProductDetail($value['outward_parent']);
						$value['shape'] = $parentData['shape'];
						if($value['color'] == '')
						$value['color'] = $parentData['color'];
						if($value['clarity'] == '')
						$value['clarity'] = $parentData['clarity'];
						$sku = $parentData['sku'];
					}
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<input type="hidden" id="srecord-<?php echo $i?>" name="srecord[<?php echo $i?>][id]" value="<?php echo $value['id']?>" /><?php echo $i;?>
					</div>
					<div class="divTableCell  "><?php echo $sku;?></div>
					<div class="divTableCell"><input style="background: #999 !important;border: 0;color: #fff;" class=" col-sm-12  a-right"  name="srecord[<?php echo $i ?>][pcs]" type="text" readonly value="<?php echo (isset($value['pcs']))?$value['pcs']:'&nbsp;'?>" ></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right tcarat" style="background: #999 !important;border: 0;color: #fff;"  name="srecord[<?php echo $i ?>][carat]" readonly onChange="calAmount(<?php echo $i ?>)" id="pcarat-<?php echo $i ?>" type="text" value="<?php echo (isset($value['carat']))?$value['carat']:'&nbsp;'?>"></div>	
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell  "><input class=" col-sm-12 a-right"  name="srecord[<?php echo $i ?>][clarity]" value="<?php echo $value['clarity'] ?>"  type="text"></div>				
					<div class="divTableCell "><input class=" col-sm-12 a-right"  name="srecord[<?php echo $i ?>][color]" value="<?php echo $value['color'] ?>"  type="text"></div>			
					<div class="divTableCell  a-right"><input class=" col-sm-12 a-right"  name="srecord[<?php echo $i ?>][price]" id="price-<?php echo $i ?>" onBlur="calAmount(<?php echo $i ?>)" value="<?php echo $value['price']?>"  type="text"></div>
					<div class="divTableCell amount1 a-right" id="amount2-<?php echo $i ?>"><?php echo $value['amount']?></div>					
				</div>
				<?php 
			
			$tscarat = (float)$tscarat +  (float)$value['carat'];
			$tsamount =  (float)$tsamount +  (float)$value['amount'];
			
			endforeach; ?>	
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
</div>
<p><b>Total Stone Carat : </b> <span id="tscts"><?php echo $tccarat+$tscarat+$tmcarat; ?></span> &nbsp;&nbsp; |  &nbsp;&nbsp; <b>Total Stone Amount : </b> <span id="total_amount"><?php echo $tsamount+$tcamount+$tmamount; ?></span> </p>	
<p><b>Extra Side Stone Detail: </b></p>
	<div class="form-group col-sm-2 center">
		<label class="col-sm-4 control-label center no-padding-right" for="form-field-4"><b>Pcs</b></label>
		<div class="col-sm-8 center">
			<input class="input-sm col-sm-12" readonly name="side_pcs" value="<?php echo $data['side_pcs']?>" type="text" style="margin-right:5px">					
		</div>
	</div>
	<div class="form-group col-sm-2 center">
		<label class="col-sm-4 control-label center no-padding-right" for="form-field-4"><b>Carat</b></label>
		<div class="col-sm-8 center">
			<input class="input-sm col-sm-12" readonly name="side_carat" onBlur="calculateCarat()" id="side_carat" value="<?php echo $data['side_carat']?>" type="text" style="margin-right:5px">					
		</div>
	</div>
	<div class="form-group col-sm-2 center">
		<label class="col-sm-4 control-label center no-padding-right" for="form-field-4"><b>Price</b></label>
		<div class="col-sm-8 center">
			<input class="input-sm col-sm-12" onBlur="calculateCarat()" id="side_price" name="side_price" value="<?php echo $data['side_price']?>" type="text" style="margin-right:5px">					
		</div>
	</div>
	<div class="form-group col-sm-2 center">
		<label class="col-sm-4 control-label center no-padding-right" for="form-field-4"><b>Amount</b></label>
		<div class="col-sm-8 center">
			<input class="input-sm col-sm-12" id="side_amount" name="side_amount" value="<?php echo $data['side_amount']?>" readonly type="text" style="margin-right:5px">					
		</div>
	</div><div class="form-group col-sm-2 center">
		<label class="col-sm-4 control-label center no-padding-right" for="form-field-4"><b>Lab Fee</b></label>
		<div class="col-sm-8 center">
			<input class="input-sm col-sm-12 a-right" id="lab_fee" value="<?php echo $data['lab_fee']?>" onBlur="calJewelry()" name="lab_fee" placeholder="" type="text">		
		</div>
	</div>
<div class="col-xs-12 col-sm-8 " style="margin-left:16.66%;">
	<table class="diamond-htable">
		<thead>
		<tr class="diamond-th">
			<th class="diamond-col dc1">Design</th>
			<th class="diamond-col dc2">Type</th>
			<th class="diamond-col dc3">Gold</th>
			<th class="diamond-col dc5">Gold Color</th>					
			<th class="diamond-col dc5">Net Weight</th>
			<th class="diamond-col dc5">PG Weight</th>	
		</tr>
		</thead>
		<tbody>
		<tfoot>
			<tr class="diamond-tr">
				<td class="diamond-col dc1"><input class="input-sm col-sm-12" value="<?php echo $data['jew_design']?>" name="jew_design" placeholder="Design" type="text"></td>
				<td class="diamond-col dc2 a-right">
				<select class="col-xs-12" id="jew_type" name="jew_type">
				<option value="">Jewelry Type</option>
				<?php 
				foreach($jewType as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['jew_type'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
				</select>
				</td>					
				<td class="diamond-col dc5 a-right"><select class="col-xs-12" id="gold" name="gold" onChange="calJewelry()">
				<option value="">Gold Carat</option>
				<?php 
				foreach($gold as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['gold'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
				</select></td>	
				<td class="diamond-col dc5 a-right">
					<select class="col-xs-12" id="goldcolor" name="gold_color">
					<option value="">Gold Color</option>
					<?php 
					foreach($goldColor as $key => $value):
					?>						
					<option value="<?php echo $key?>" <?php echo ($key == $data['gold_color'])?"selected":""; ?> ><?php echo $value?></option>
					<?php endforeach; ?>
			</select>
				</td>			
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right" id="net_weight" value="<?php echo $data['net_weight']?>" onBlur="calJewelry()" name="net_weight" placeholder="" type="text"></td>
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right" id="pg_weight" value="<?php echo $data['pg_weight']?>" name="pg_weight" placeholder="" type="text"></td>
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='5'><b>Gold Rate</b></td>												
				<td class="diamond-col dc7 a-right"><input onBlur="calJewelry()" class="input-sm col-sm-12 a-right" id="rate" value="<?php echo $data['rate']?>" name="rate" placeholder="" type="text"></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='5'><b>Gold Amount</b></td>												
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right" id="amount" value="<?php echo $data['amount']?>" name="amount" placeholder="" type="text"></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='5'><b>Other Code</b></td>												
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right" id="other_code" value="<?php echo $data['other_code']?>" name="other_code" placeholder="" type="text"></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='5'><b>Other Amount</b></td>												
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right"  id="other_amount" onBlur="calJewelry()" value="<?php echo $data['other_amount']?>" name="other_amount" placeholder="" type="text"></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='5'><b>Labour Rate</b></td>												
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right" onBlur="calJewelry()" id="labour_rate" value="<?php echo $data['labour_rate']?>" name="labour_rate" placeholder="" type="text"></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='5'><b>Labour Amount</b></td>							
				<td class="diamond-col dc6 a-right"><input class="input-sm col-sm-12 a-right" id="labour_amount" value="<?php echo $data['labour_amount']?>" name="labour_amount" placeholder="" type="text"></td>		
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='5'><b>Total Amount</b></td>							
				<td class="diamond-col dc6 a-right"><input class="input-sm col-sm-12 a-right" id="ftotal_amount" value="<?php echo $data['total_amount']?>" name="total_amount" placeholder="" type="text"></td>		
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='5'><b>Sell Price</b></td>							
				<td class="diamond-col dc6 a-right"><input class="input-sm col-sm-12 a-right" id="sell_price" value="<?php echo $data['sell_price']?>" name="sell_price" placeholder="" type="text"></td>		
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='5'><b>Asking Price</b></td>							
				<td class="diamond-col dc6 a-right"><input class="input-sm col-sm-12 a-right" id="selling_price" value="<?php echo $data['selling_price']?>" name="selling_price" placeholder="" type="text"></td>		
			</tr>
		</tfoot>
		
		</tbody>
	</table>
</div>

<div class="col-xs-12 col-sm-12 " style="margin-top:30px">
	
<div class="clearfix form-actions" >
	<div class="col-md-12">
		<div class="form-group col-sm-5">		
			<textarea class="form-control" id="form-field-8" placeholder="Narretion" style="width: 353px; height: 88px;" name="narretion"><?php echo $data['narretion']?></textarea>
		</div>
		<button class="btn btn-info" type="submit" value="save">
			<i class="ace-icon fa fa-check bigger-110"></i>
			Save Entry
		</button>

		&nbsp; &nbsp; &nbsp;
		<button class="btn reset" type="reset">
			<i class="ace-icon fa fa-undo bigger-110"></i>
			Reset
		</button>
	</div>
</div>
</div>	
</form>
<script type="text/javascript">
	function calAmount(rid)
	{
			
			var price = parseFloat($('#price-'+rid).val());
			var pcarat = parseFloat($('#pcarat-'+rid).val());
			var total  = parseFloat(price * pcarat );
			
			if(!isNaN(total))
			{
				$("#amount2-"+rid ).html(total.toFixed(2));
			}
			else
			{
				$("#amount2-"+rid).val(0);
			}
			totalamount();
	}
function mcalAmount(rid)
{
	
	var price = parseFloat($('#mprice-'+rid).val());
	var pcarat = parseFloat($('#mcarat-'+rid).html());
	var total  = parseFloat(price * pcarat);
	if(!isNaN(total))
	{
		$("#mamount-"+rid ).html(total.toFixed(2));
	}
	else
	{
		$("#mamount-"+rid).val(0);
	}
	totalamount();
}
function ccalAmount(rid)
{
	var price = parseFloat($('#cprice-'+rid).val());
	var pcarat = parseFloat($('#ccarat-'+rid).html());
	var ca = parseFloat($('#coll-ca-'+rid).html());
	var la = parseFloat($('#coll-la-'+rid).html());
	var oa = parseFloat($('#coll-oa-'+rid).html());
	if(isNaN(oa))
		{oa = 0;}
	if(isNaN(la))
		{la = 0;}
	if(isNaN(ca))
		{ca = 0;}
	var total  = parseFloat(price * pcarat);
	var jtotal = total + ca + la + oa;
	if(!isNaN(total))
	{
		$("#camount-"+rid ).html(total.toFixed(2));
		$("#coll-total-cost-"+rid).html(jtotal.toFixed(2));
		$("#coll-total-"+rid).html(jtotal.toFixed(2));
		$("#ctamount-"+rid).val(jtotal.toFixed(2));
	}
	else
	{
		$("#camount-"+rid).val(0);
	}
	totalamount();
}
function totalamount()
{
		var total =0.0;
		
		$(".divTableRow .divTableCell.amount1" ).each(function( index ) 
		{
			if(!$( this ).hasClass('skip'))
			{
				
			  var amount = parseFloat($( this ).html());
				  if(!isNaN(amount))
				  {
					total = amount + total;
				  }	 
			}	 
		});
			
			if(!isNaN(total))
			{
				$('#total_amount').html(total.toFixed(2)); 	
			}
		calJewelry();
}
function calculateCarat()
{
	var carat = parseFloat(jQuery('#side_carat').val());
	var price = parseFloat(jQuery('#side_price').val());
	var amount = price * carat;
	if(!isNaN(amount))
	{	
		jQuery('#side_amount').val(amount.toFixed(2));
	}
	calJewelry();
}
	function calJewelry()
	{
		var gross_weight = parseFloat(jQuery('#gross_weight').val());
		var carat = parseFloat(jQuery('#side_carat').val());
		var lab_fee = parseFloat(jQuery('#lab_fee').val());
		var amount = parseFloat(jQuery('#side_amount').val());
		if(isNaN(carat))
		{carat = 0;}
		if(isNaN(lab_fee))
		{lab_fee = 0;}
		if(isNaN(amount))
		{amount = 0;}
		var total_carat = parseFloat(jQuery('#tscts').html());
		total_carat = total_carat + carat;
		var total_amount = parseFloat(jQuery('#total_amount').html());
		total_amount = total_amount + amount + lab_fee;
		var rate = parseFloat(jQuery('#rate').val());
		var other_amount = parseFloat(jQuery('#other_amount').val());
		var labour_rate = parseFloat(jQuery('#labour_rate').val());
		
		var gold = parseInt(jQuery('#gold').val());
		
		if( gold >0)
		{
			if(isNaN(gross_weight))
			{gross_weight = 0;}		
			if(isNaN(total_carat))
			{total_carat = 0;}
			if(isNaN(total_amount))
			{total_amount = 0;}
			if(isNaN(rate))
			{rate = 0;}
			if(isNaN(other_amount))
			{other_amount = 0;}	
			if(isNaN(labour_rate))
			{labour_rate = 0;}
			
			var gold_per = 0;
			
			switch(gold) {
				case 22:
					gold_per = 92;
					break;
				case 18:
					gold_per=76;
					break;
				case 14:
					gold_per=59;
					break;
				case 16:
					gold_per=70;
					break;	
				case 20:
					gold_per=84;
					break;	
			} 

			
			var net_gram = parseFloat(jQuery('#net_weight').val());/* parseFloat(gross_weight - (total_carat/5)); */
			if(!isNaN(net_gram))
			{
				//jQuery('#net_weight').val(Math.abs(net_gram.toFixed(3)));
				
				var pg_weight = parseFloat( (net_gram * gold_per)/ 100 );
				if(!isNaN(pg_weight))
				{
					jQuery('#pg_weight').val(Math.abs(pg_weight.toFixed(3)));
				}	
			}
			var camount = parseFloat(net_gram * rate);
			if(!isNaN(camount))
			{
				jQuery('#amount').val(Math.abs(camount.toFixed(2)));
			}
			else
			{
				camount=0;
			}
			
			var labour_amount = parseFloat(gross_weight * labour_rate);
			if(!isNaN(labour_amount))
			{
				jQuery('#labour_amount').val(Math.abs(labour_amount.toFixed(2)));
			}
			else
			{
				labour_amount=0;
			}
			var famount =  parseFloat(total_amount + labour_amount + other_amount  + camount );
			
			if(!isNaN(famount))
			{	
				jQuery('#ftotal_amount').val(Math.abs(famount.toFixed(2)));
				var sell_price = famount/85*100;
				var selling_price = sell_price/50*100;
				jQuery('#sell_price').val(sell_price.toFixed(2));
				jQuery('#selling_price').val(selling_price.toFixed(2));
			}	
		}
		else
		{
			alert('Please Select Gold Carat');
		}	
		
		
	}
</script>
<style>
.form-group {
  margin-bottom: 20px;
}
.subform.invenotry-cgrid {
	overflow-x: auto;
	padding: 0px 10px 0px 10px;
}
.add-more {
  background-color: #69aa46;
  border-radius: 100%;
  color: #fff;
  font-size: 14px;
  height: 25px;
  padding: 6px 5px 5px;
  width: 25px;
  cursor: pointer;
}

.delete-more {
  background-color: #dd5a43;
  border-radius: 100%;
  color: #fff;
  font-size: 14px;
  height: 22px;
  padding: 4px 5px 5px;
  width: 22px;
  cursor: pointer;
}
.main-grid .divTableBody {
	height: 75px !important;
}
</style>
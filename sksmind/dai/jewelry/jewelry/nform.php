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

$metal = array();
$metal['gold']  = 'Gold';
$metal['brass']  = 'Brass';
$metal['silver']  = 'Silver';
$metal['Pletinum'] = 'Pletinum';
?>
<form class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $jewelryUrl.'jewelry/jewelryController.php'?>">
<input type="hidden" name="fn" value="save" />
<div class="col-xs-12 col-sm-12 ">

	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Invoice No. <span class="required">*</span></label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="invoiceno" required name="invoiceno" placeholder="Enter Invoice" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Invoice Date<span class="required">*</span></label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="invoicedate" name="invoicedate" placeholder="Select Date" type="text">
		</div>
	</div>
	<div style="clear:both;"></div>
	<div class="form-group col-sm-4" >
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Company <span class="required">*</span></label>
		<div class="col-sm-8">
			<select class="col-xs-12" id="ledger" name="party" required >
				<option value="">Select Company Name</option>
				<?php 
				foreach($party as $key => $value):
				?>						
				<option value="<?php echo $key?>"><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
		</div>
	</div>
	<div class="form-group col-sm-5" >
		<label class="col-sm-2 control-label no-padding-right" for="form-field-4">Import </label>
		<div class="col-sm-4">
			<select class="col-xs-10" id="inward_type" name="inward_type" required >
				<option value="import">Import</option>
				<option value="purchase">Purchase</option>
				
			</select>
		</div>
	</div>

	<div class="form-group col-sm-5">
		<label class="col-sm-7 control-label no-padding-right" for="form-field-4">Jewelry Name</label>
		<div class="col-sm-5">
			<input class="input-sm col-sm-10" name="name" placeholder="Jewelry Name" type="text">
		</div>
	</div>
	<div class="form-group col-sm-2">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">SKU</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" name="sku" placeholder="Sku" type="text">
		</div>
	</div>
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Gross Wei.</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-10 a-right" id="gross_weight" name="gross_weight" onBlur="calJewelry()" placeholder="" type="text">
		</div>
	</div>	
	<div class="form-group col-sm-2">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Date</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="date" name="date" placeholder="Select Date" type="text">
		</div>
	</div>
	<div class="form-group col-sm-2">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">IGI Code</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" name="igi_code" placeholder="IGI Code" type="text">
		</div>
	</div>
</div>
<div style="clear:both"></div>
	<hr>
<div class="col-xs-12 col-sm-12 jewelry-job">
<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Main Stone</h3>
	<div class="subform mainstone invenotry-cgrid main-grid">
		<div class="divTable" style="width: 1260px" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>					
				<div class="divTableCell">SKU<span class="required">*</span></div>
				<div class="divTableCell">Color</div>
				<div class="divTableCell">Color Code</div>
				<div class="divTableCell">Clarity</div>	
				<div class="divTableCell">Shape</div>	
				<div class="divTableCell">Report No.</div>	
				<div class="divTableCell">Pcs </div>
				<div class="divTableCell">Weight<span class="required">*</span></div>
				<div class="divTableCell">Price <span class="required">*</span></div>
				<div class="divTableCell">Amount<span class="required">*</span></div>	
				<div class="divTableCell">Remarks </div>
			</div>	
			<div class="divTableBody mains">
				<div class="divTableRow">					
					<div class="divTableCell" style="width:50px !important">1</div>		
					<div class="divTableCell"><input class=" col-sm-12"  name="mrecord[1][sku]" id="sku-1" onBlur="addImportRow(1)" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12"  name="mrecord[1][color]" type="text"></div>	
					<div class="divTableCell"><input class=" col-sm-12"  name="mrecord[1][color_code]" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12  a-right"  name="mrecord[1][clarity]" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12"  name="mrecord[1][shape]" type="text"></div>	
					<div class="divTableCell"><input class=" col-sm-12"  name="mrecord[1][report_no]" type="text"></div>	
					<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord[1][pcs]" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right scarat" id="pcarat-1" name="mrecord[1][carat]" type="text"></div>		
					<div class="divTableCell"><input class=" col-sm-12 a-right"  name="mrecord[1][price]" id="price-1" onBlur="calAmount(1)"  type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right amount1"  name="mrecord[1][amount]" id="amount-1" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12"  name="mrecord[1][remarks]" type="text"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="form-group col-sm-12 ">
	<h3>Side Stone</h3>
	<div class="subform sidestone invenotry-cgrid main-grid jewside">
		<div class="divTable " style="width:1200px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					
				</div>					
				<div class="divTableCell">SKU<span class="required">*</span></div>
				<div class="divTableCell">Color</div>
				<div class="divTableCell">Clarity</div>	
				<div class="divTableCell">Shape</div>	
				<div class="divTableCell">Pcs </div>
				<div class="divTableCell">Weight<span class="required">*</span></div>
				<div class="divTableCell">Price <span class="required">*</span></div>
				<div class="divTableCell">Amount<span class="required">*</span></div>	
				<div class="divTableCell">Remarks </div>
				
				
			</div>	
			<div class="divTableBody sides">
				<div class="divTableRow">					
					<div class="divTableCell" style="width:50px !important">1</div>		
					<div class="divTableCell"><input class=" col-sm-12"  name="srecord[1][sku]" id="sku-1" onBlur="addImportRowSide(1)" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12"  name="srecord[1][color]" type="text"></div>	
					<div class="divTableCell"><input class=" col-sm-12  a-right"  name="srecord[1][clarity]" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12"  name="srecord[1][shape]" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right"  name="srecord[1][pcs]" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right scarat" id="spcarat-1" name="srecord[1][carat]" type="text"></div>		
					<div class="divTableCell"><input class=" col-sm-12 a-right"  name="srecord[1][price]" id="sprice-1" onBlur="calAmountSide(1)"  type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12 a-right amount1"  name="srecord[1][amount]" id="samount-1" type="text"></div>
					<div class="divTableCell"><input class=" col-sm-12"  name="srecord[1][remarks]" type="text"></div>				
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<p><b>Total Stone Carat : </b> <span id="tscts"><?php echo $tccarat+$tscarat+$tmcarat; ?></span> &nbsp;&nbsp; |  &nbsp;&nbsp; <b>Total Stone Amount : </b> <span id="total_amount"><?php echo $tsamount+$tcamount+$tmamount; ?></span> </p>	

<div class="col-xs-12 col-sm-8 " style="margin-left:10.66%;">
	<table class="diamond-htable">
		<thead>
		<tr class="diamond-th">
			<th class="diamond-col dc1">Design</th>
			<th class="diamond-col dc2">Type</th>
			<th class="diamond-col dc3">Metal</th>
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

				<td class="diamond-col dc5 a-right"><select class="col-xs-12" id="metal" name="metal">
				<option value="">Select Metal</option>
				<?php 
				foreach($metal as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['metal'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
				</select></td>	
				
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
				<td class="diamond-col dc2 a-right"  colspan='6'><b>Gold Rate</b></td>												
				<td class="diamond-col dc7 a-right"><input onBlur="calJewelry()" class="input-sm col-sm-12 a-right" id="rate" value="<?php echo $data['rate']?>" name="rate" placeholder="" type="text"></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='6'><b>Gold Amount</b></td>												
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right" id="amount" value="<?php echo $data['amount']?>" name="amount" placeholder="" type="text"></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='6'><b>Other Code</b></td>												
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right" id="other_code" value="<?php echo $data['other_code']?>" name="other_code" placeholder="" type="text"></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='6'><b>Other Amount</b></td>												
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right"  id="other_amount" onBlur="calJewelry()" value="<?php echo $data['other_amount']?>" name="other_amount" placeholder="" type="text"></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='6'><b>Labour Rate</b></td>												
				<td class="diamond-col dc7 a-right"><input class="input-sm col-sm-12 a-right" onBlur="calJewelry()" id="labour_rate" value="<?php echo $data['labour_rate']?>" name="labour_rate" placeholder="" type="text"></td>			
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='6'><b>Labour Amount</b></td>							
				<td class="diamond-col dc6 a-right"><input class="input-sm col-sm-12 a-right" id="labour_amount" value="<?php echo $data['labour_amount']?>" name="labour_amount" placeholder="" type="text"></td>		
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='6'><b>Total Amount</b></td>							
				<td class="diamond-col dc6 a-right"><input class="input-sm col-sm-12 a-right" id="ftotal_amount" value="<?php echo $data['total_amount']?>" name="total_amount" placeholder="" type="text"></td>		
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='6'><b>Sell Price</b></td>							
				<td class="diamond-col dc6 a-right"><input class="input-sm col-sm-12 a-right" id="sell_price" value="<?php echo $data['sell_price']?>" name="sell_price" placeholder="" type="text"></td>		
			</tr>
			<tr class="diamond-tr">							
				<td class="diamond-col dc2 a-right"  colspan='6'><b>Asking Price</b></td>							
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
		$("#amount-"+rid ).val(total.toFixed(2));
	}
	else
	{
		$("#amount-"+rid ).val(0);
	}
	totalamount();
}

function calAmountSide(rid)
{
	var price = parseFloat($('#sprice-'+rid).val());
	var pcarat = parseFloat($('#spcarat-'+rid).val());
	var total  = parseFloat(price * pcarat );
	
	if(!isNaN(total))
	{
		$("#samount-"+rid ).val(total.toFixed(2));
	}
	else
	{
		$("#samount-"+rid ).val(0);
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
		
		$(".divTableRow .divTableCell .amount1" ).each(function( index ) 
		{
			if(!$( this ).hasClass('skip'))
			{
				
			  var amount = parseFloat($( this ).val());
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
			calculateCarat();
}
function calculateCarat()
{
	var total =0.0;
		
		$(".divTableRow .divTableCell .scarat" ).each(function( index ) 
		{
			  var amount = parseFloat($( this ).val());
			  if(!isNaN(amount))
			  {
				total = amount + total;
			  }
		});
		if(!isNaN(total))
		{
			$('#tscts').html(total.toFixed(2)); 	
		}
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
	height: 120px !important;
}
</style>
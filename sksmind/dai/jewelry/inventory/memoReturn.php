<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('memo',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}
include_once($daiDir.'jewelry/jobwork/jobworkModel.php');
include_once($daiDir.'module/party/partyModel.php');
include_once('inventoryModel.php');

include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$imodel  = new inventoryModel($cn);
$model  = new jobworkModel($cn);

$groupUrl = $daiDir.'jewelry/jobwork/';
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$design = $jhelper->getAllDesign();
$gold = $jhelper->getGoldType();
$goldColor = $jhelper->getGoldColor();

$lid=0;
if(isset($_POST['id']))
{
	$lid = $_POST['id'];
}
$data =  $model->getData($lid);		

?>

<div class="page-header">							
	<h1 style="float:left">
		Repair Memo Return - (<?php echo $data['entryno']?>)
	</h1>
	<button id="close-box" onclick="closeBox1(<?php echo $_POST['pid']; ?>)" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
	
	<button class="btn btn-info" style="float:right;margin-right: 10px;" id="btn-save" type="button" onClick="saveMemoForm()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Return Memo
	</button>
	
	<button class="btn reset"  style="float:right;margin-right: 10px;" type="reset">
		<i class="ace-icon fa fa-undo bigger-110"></i>
		Reset
	</button>	
	
</div>
<?php


/*if($_POST['type'] == 'memo'):
	$invoice = $model->getIncrementEntry('memo_invoice');
endif;

if($_POST['type'] == 'lab'):
	$invoice = $model->getIncrementEntry('lab_invoice');
endif;
*/
?>

<form class="form-horizontal" id="memo-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="memoToReturn" />
<input type="hidden" name="memo_id" value="<?php echo $lid; ?>" />
<input type="hidden" name="products" value="<?php echo $data['products']; ?>" />
<input type="hidden" name="main_stone" value="<?php echo $data['main_stone']; ?>" />
<input type="hidden" name="side_stone" value="<?php echo $data['side_stone']; ?>" />
<input type="hidden" name="jew_design" value="<?php echo $data['jew_design']; ?>" />
<input type="hidden" name="jew_type" value="<?php echo $data['jew_type']; ?>" />
	<div class="col-sm-7">	
	<div class="subform invenotry-cgrid ">
		<div class="divTable" style="width:700px">
			<div class="divTableHeading">
				<div class="divTableCell" style="width:100px !important">SKU</div>
				<div class="divTableCell " style="width:50px !important">Pcs</div>
				<div class="divTableCell " style="width:50px !important">Carat</div>				
				<div class="divTableCell " style="width:70px !important">Price</div>
				<div class="divTableCell " style="width:90px !important">Amount</div>
				<div class="divTableCell " style="width:70px !important">Clarity</div>
				<div class="divTableCell " style="width:90px !important">Color</div>				
				<div class="divTableCell " style="width:90px !important">Shape </div>
				<div class="divTableCell " style="width:90px !important">Stone</div>			
			</div>	
			<div class="outward divTableBody">
				<?php 
				
				$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = 0.0; 
				
				foreach($data['record'] as $jData):	
				
				$tpp += (float) $jData['pcs'];
				$tpc += (float) $jData['carat'];
				$tc += (float) $jData['cost'];
				$tp += (float) $jData['price'];
				$ta += (float) $jData['amount'];

				$class="";
				$sku = $jData['sku'];
				
				?>
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell" style="width:100px !important">  <?php echo $sku; ?> </div>
					<div class="divTableCell  a-right " style="width:50px !important">  <?php echo $jData['pcs']?> </div>
					<div class="divTableCell  a-right " style="width:50px !important">  <?php echo $jData['carat']?>  </div>					
					<div class="divTableCell  a-right " style="width:70px !important">  <?php echo $jData['price'] ?></div>
					<div class="divTableCell  a-right  amount" style="width:90px !important">  <?php echo $jData['amount'] ?></div>
					<div class="divTableCell " style="width:70px !important">  <?php echo $jData['clarity']?> </div>
					<div class="divTableCell" style="width:90px !important">  <?php echo $jData['color']?> </div>
					<div class="divTableCell " style="width:90px !important">  <?php echo $jData['shape']?> </div>
					<div class="divTableCell " style="width:90px !important">Main</div>	
				</div>
				<?php endforeach;?>
				
				<div class="divTableRow">					
					<div class="divTableCell" style="width:100px !important"> <b>Total : <?php echo count($data['record']) ?></b></div>
					<div class="divTableCell a-right " style="width:50px !important" >  <b><?php echo $tpp?></b> </div>
					<div class="divTableCell a-right " style="width:50px !important"> <b> <?php echo $tpc?></b>  </div>					
					<div class="divTableCell a-right " style="width:70px !important">  <b><?php echo $tp?></b></div>
					<div class="divTableCell a-right " style="width:90px !important">  <b><?php echo $ta?></b></div>
					<div class="divTableCell " style="width:70px !important">  </div>
					<div class="divTableCell" style="width:90px !important">  </div>
					<div class="divTableCell " style="width:90px !important">  </div>	
					<div class="divTableCell " style="width:90px !important">  </div>	
					
				</div>
			</div>
		</div>
	</div>
	</div>
	<div class="col-sm-5">
	<div class="cgrid-header green">
		<div class="color-total"> <b>Jewelry Design:</b>   <span class="blue" ><?php echo (isset($design[$data['jew_design']])) ?$design[$data['jew_design']] :'';  ?></span> &nbsp;&nbsp;&nbsp;&nbsp;</div>
		<div class="color-total"> <b>Jewelry Type :</b>  <span class="blue" ><?php echo $data['jew_type'] ?></span> </div><br><br>
		<div class="color-total"> <b>Gold :</b>  <span class="blue" ><?php echo $data['gold'] ?></span> &nbsp;&nbsp;&nbsp;&nbsp;</div>
		<div class="color-total"> <b>Gold Color:</b>  <span class="blue" ><?php echo $data['gold_color'] ?></span> </div><br><br>
		<div class="color-total"> <b>Gold Carat:</b>  <span class="blue" ><?php echo $data['gold_carat'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> </div>
		<div class="color-total"> <b>Gold Price:</b>  <span class="blue" ><?php echo $data['gold_price'] ?></span> </div><br><br>
		<div class="color-total"> <b>Labour Charge:</b>  <span class="blue" ><?php echo $data['labour_charge'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> </div>
		<div class="color-total"> <b>Handling Charge:</b>  <span class="blue" ><?php echo $data['handling_charge'] ?></span> </div>
	</div>	
	
</div>
<div style="clear:both"></div>
<br>
	<div class="form-group col-sm-3">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Color <span class="required">*</span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="goldcolor" name="gold_color">
				<option value="">Gold Color</option>
				<?php 
				foreach($goldColor as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['gold_color'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	<div class="form-group col-sm-3">
	
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Carat <span class="required">*</span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="gold" name="gold_type">
				<option value="">Gold Carat</option>
				<?php 
				foreach($gold as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['gold'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>		
	</div>
	<div class="form-group col-sm-1 center">				
	</div>
	<div class="form-group col-sm-3 center">
		<label class="col-sm-4 control-label center no-padding-right" for="form-field-4">Jewelry SKU <span class="required">*</span></label>
		<div class="col-sm-8 center">
			<input class="input-sm col-sm-12"  name="sku" placeholder="SKU" value="<?php echo '' ?>" type="text" style="margin-right:5px">					
		</div>
	</div>
	<div class="form-group col-sm-2 center">
				<label class="col-sm-4 control-label center no-padding-right" for="form-field-4">Date</label>
				<div class="col-sm-8 center">
					<input class="input-sm col-sm-12 a-right" id="date" name="date" type="text" style="margin-right:5px">					
				</div>
			</div>
	<div style="clear:both"></div>
	<hr>
			
			
			<div class="form-group col-sm-2 center">
				<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Total Carat</label>
				<div class="col-sm-12 center">
					<input class="input-sm col-sm-12 a-right" readonly id="total_carat" name="total_carat" onKeyUp="calculateGold()" value="<?php echo $tpc; ?>" type="text" style="margin-right:5px">					
				</div>
			</div>
			
			<div class="form-group col-sm-2 center">
				<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Total Amount</label>
				<div class="col-sm-12 center">
					<input class="input-sm col-sm-12 a-right" readonly id="total_amount" name="total_amount" onKeyUp="calculateGold()" value="<?php echo $ta; ?>" type="text" style="margin-right:5px">					
				</div>
			</div>
			
			<div class="form-group col-sm-2 center">
				<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Gold Gram<span class="required">*</span></label>
				<div class="col-sm-12 center">
					<input class="input-sm col-sm-12 a-right"  id="gold_gram" name="gold_gram" onKeyUp="calculateGold()" placeholder="Jewelry Carat" value="<?php echo '' ?>" type="text" style="margin-right:5px">					
				</div>
			</div>
			
			<div class="form-group col-sm-2 center">
				<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Gold Rate</label>
				<div class="col-sm-12 center">
					<input class="input-sm col-sm-12 a-right" id="gold_price" name="gold_price" onKeyUp="calculateGold()" placeholder="Gold Rate" value="<?php echo $data['gold_price'] ?>" type="text" style="margin-right:5px">					
				</div>
			</div>
			
			<div class="form-group col-sm-2 center">
				<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Gold Amount</label>
				<div class="col-sm-12 center">
					<input class="input-sm col-sm-12 a-right" readonly id="gold_amount" name="gold_amount" placeholder="Gold Amount" value="<?php echo '' ?>" type="text" style="margin-right:5px">					
				</div>
			</div>
			<div class="form-group col-sm-2 center">
				<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Jew Weight</label>
				<div class="col-sm-12 center">
					<input class="input-sm col-sm-12 a-right"  name="gross_cts" id="gross_cts" readonly  placeholder="Jew Weight" value="<?php echo '' ?>" type="text" style="margin-right:5px">					
				</div>
			</div>
			
	<div style="clear:both"></div>
	<br>
	
	<div class="form-group col-sm-2 center">
		<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Roadiam Cost</label>
		<div class="col-sm-12 center">
			<input class="input-sm col-sm-12 a-right" id="roadiam_cost"  name="roadiam_cost" onKeyUp="totalamount()" placeholder="Roadiam Cost" value="<?php echo $data['roadiam_cost'] ?>" type="text" style="margin-right:5px">					
		</div>
	</div>
	
	<div class="form-group col-sm-2 center">
		<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Labour Cost</label>
		<div class="col-sm-12 center">
			<input class="input-sm col-sm-12 a-right" id="labour_fee"  name="labour_fee" onKeyUp="totalamount()" placeholder="Labour Cost" value="<?php echo $data['labour_charge'] ?>" type="text" style="margin-right:5px">					
		</div>
	</div>
	
	<div class="form-group col-sm-2 center">
		<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Hanlding Cost</label>
		<div class="col-sm-12 center">
			<input class="input-sm col-sm-12 a-right" id="handling_charge"  name="handling_charge" onKeyUp="totalamount()" placeholder="Handling Cost" value="<?php echo $data['handling_charge'] ?>" type="text" style="margin-right:5px">					
		</div>
	</div>
	<div class="form-group col-sm-2 center">
		<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Making Cost</label>
		<div class="col-sm-12 center">
			<input class="input-sm col-sm-12 a-right" readonly id="cost_price" name="cost_price"  placeholder="Making Cost" value="<?php echo '' ?>" type="text" style="margin-right:5px">					
		</div>
	</div>
	
	<div class="form-group col-sm-2 center">
		<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">GST</label>
		<div class="col-sm-12 center">
			<input class="input-sm col-sm-12 a-right"  id="gst" name="gst" onKeyUp="totalamount()" placeholder="GST" value="<?php echo '' ?>" type="text" style="margin-right:5px">					
		</div>
	</div>
	
	<div class="form-group col-sm-2 center">
		<label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Final Amount</label>
		<div class="col-sm-12 center">
			<input class="input-sm col-sm-12 a-right" readonly id="final_cost" name="final_cost" placeholder="Final Amount" value="<?php echo '' ?>" type="text" style="margin-right:5px">					
		</div>
	</div>
			
</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">
	
	$( "#date" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});
	$("#minvoicedate").on("change", function()
	{
		var selectedDate = $("#minvoicedate").val();

		$("#mdate").val(selectedDate);
		
		// var finalDate = selectedDate.substring(6,11)+"/"+selectedDate.substring(3,6)+"/"+selectedDate.substring(0,2);
	    var date = new Date(selectedDate);

		
		days = parseInt($("#mterms").val(), 10);
		if(days == '')
		{
			$("#mduedate").val(selectedDate);
			return;
		}
	    if(!isNaN(date.getTime())){
			date.setDate(date.getDate() + days);
			if(date.toInputFormat() != "NaN/NaN/NaN")
			{
				$("#mduedate").val(date.toInputFormat());
			}
			else
			{
				$("#mduedate").val('');
			}
		} else {
			alert("Invalid Date");  
		}
		
	});	

function calDueDate()
	{
		   var selectedDate = $("#minvoicedate").val();
		
		  // var finalDate = selectedDate.substring(6,11)+"/"+selectedDate.substring(3,6)+"/"+selectedDate.substring(0,2);
		   var date = new Date(selectedDate);

			days = parseInt($("#mterms").val(), 10);
		   
			if(!isNaN(date.getTime())){
				date.setDate(date.getDate() + days);

				if(date.toInputFormat() != "NaN/NaN/NaN")
				{
					$("#mduedate").val(date.toInputFormat());
				}
				else
				{
					$("#mduedate").val('');
				}
			} else {
				alert("Invalid Date");  
			}
		
	}	
function saveMemoForm()
{
	//jQuery('#btn-save').attr('disabled',true);
	var data =  $("#memo-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $jewelryUrl.'jewelry/jewelryController.php'?>', 
		type: 'POST',
		data: data,		
		success: function(result)
			{		
				if(result != "")
				{
					//jQuery('#dialog-box-container').show();
					//$('#dialog-box-container').html('<div class="box-container-memo" >'+result+'</div>');									
					jQuery('#please-wait').hide();
					var obj = jQuery.parseJSON(result);
					alert(obj.message);
					if(obj.status)
					{
						$('#edit-box-container').hide();			
						$('#edit-box-container .box-container').html('');
						loadMemo(0);
					}
					jQuery('#btn-save').attr('disabled',false);
				}
			}
	});	
}


function totalamount()
{
	var total =0.0;
	var amount = parseFloat(jQuery('#total_amount').val());
	var carat_amount = parseFloat(jQuery('#gold_amount').val());
	var labour_fee = parseFloat(jQuery('#labour_fee').val());
	var roadiam_cost = parseFloat(jQuery('#roadiam_cost').val());
	var handling_charge = parseFloat(jQuery('#handling_charge').val());
	var gst = parseFloat(jQuery('#gst').val());
	
	if(isNaN(gst))
	{
		gst = 0;
	}
	if(isNaN(labour_fee))
	{
		labour_fee = 0;
	}
	if(isNaN(roadiam_cost))
	{
		roadiam_cost = 0;
	}
	if(isNaN(carat_amount))
	{
		carat_amount = 0;
	}
	if(isNaN(amount))
	{
		amount = 0;
	}
	if(isNaN(handling_charge))
	{
		handling_charge = 0;
	}
	
	total = amount + carat_amount + labour_fee+ roadiam_cost + handling_charge;
	
	if(!isNaN(total))
	{
		$('#cost_price').val(total.toFixed(2));		
	}
	if(!isNaN(gst))
	{
		var te = total + gst;
		$('#final_cost').val(te.toFixed(2));		
	}
	
}

function closePartyPopup()
{
	jQuery('#dialog-box-container1').hide();
}

function calculateGold()
{
	var gram = parseFloat(jQuery('#gold_gram').val());
	var price = parseFloat(jQuery('#gold_price').val());
	var total  = parseFloat(gram * price);
	
	if(!isNaN(total))
	{	
		jQuery('#gold_amount').val(Math.abs(total.toFixed(2)));
	}
	
	calculateCarat();
	totalamount();
}
function calculateCarat()
{
	var carat = parseFloat(jQuery('#total_carat').val());
	var gram = parseFloat(jQuery('#gold_gram').val());
	var total  = parseFloat(gram + (carat/5 ));
	
	if(!isNaN(total))
	{	
		jQuery('#gross_cts').val(Math.abs(total.toFixed(2)));
	}	
}


</script>	
<style>	
.a-right{text-align:right;}
.divTableCell input {border:0 !important;}	
.form-group{margin-bottom:10px !important;}
</style>
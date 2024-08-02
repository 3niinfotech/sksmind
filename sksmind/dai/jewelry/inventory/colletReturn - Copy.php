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
include_once($daiDir.'jewelry/party/partyModel.php');
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
$data =  $model->getData($lid,'collet');		

?>

<div class="page-header">							
	<h1 style="float:left">
		Collet Return - (<?php echo $data['entryno']?>)
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
<input type="hidden" name="fn" value="colletToReturn" />
<input type="hidden" name="memo_id" value="<?php echo $lid; ?>" />
<input type="hidden" name="products" value="<?php echo $data['products']; ?>" />
<input type="hidden" name="main_stone" value="<?php echo $data['main_stone']; ?>" />
	<div class="col-sm-12">	
	<div class="subform invenotry-cgrid ">
		<div class="divTable" style="width:100%">
			<div class="divTableHeading">
				<div class="divTableCell" style="width:100px !important">SKU</div>
				<div class="divTableCell " style="width:40px !important">Pcs</div>
				<div class="divTableCell " style="width:50px !important">Carat</div>				
				<div class="divTableCell " style="width:70px !important">Amount</div>				
				<div class="divTableCell " style="width:60px !important">Clarity</div>
				<div class="divTableCell " style="width:90px !important">Color</div>				
				<div class="divTableCell " style="width:90px !important">Shape</div>
				<div class="divTableCell " style="width:70px !important">N Gram</div>
				<div class="divTableCell " style="width:70px !important">G Rate</div>
				<div class="divTableCell " style="width:90px !important">G Amount</div>
				<div class="divTableCell " style="width:70px !important">Handling</div>
				<div class="divTableCell " style="width:70px !important">Labour</div>
				<div class="divTableCell " style="width:70px !important">GST</div>
				<div class="divTableCell " style="width:90px !important">Final</div>
				<div class="divTableCell " style="width:70px !important">G Gram</div>
				<div class="divTableCell " style="width:90px !important">Total Amount</div>
				
				
			</div>	
			<div class="outward divTableBody">
				<?php 
				
				$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = 0.0; 
				
				foreach($data['record'] as $jData):	
				//$samount = ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'];
				$samount = $jData['amount'];
		
				$tpp += (float) $jData['pcs'];
				$tpc += (float) $jData['carat'];
				$tc += (float) $jData['cost'];
				
				$class="";
				$sku = $jData['sku'];
				
				?>
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell" style="width:100px !important">  <?php echo $sku; ?> </div>
					<div class="divTableCell  a-right " style="width:40px !important">  <?php echo $jData['pcs']?> </div>
					<div class="divTableCell  a-right " style="width:50px !important" id="carat-<?php echo $jData['id']; ?>">  <?php echo $jData['carat']?>  </div>					
					<div class="divTableCell  a-right " style="width:70px !important" id="amount-<?php echo $jData['id']; ?>">  <?php echo $samount  ?>  </div>					
					<div class="divTableCell " style="width:60px !important">  <?php echo $jData['clarity']?> </div>
					<div class="divTableCell" style="width:90px !important">  <?php echo $jData['color']?> </div>
					<div class="divTableCell " style="width:90px !important">  <?php echo $jData['shape']?> </div>					
					<div class="divTableCell " style="width:70px !important">  <input type="text" name="product[<?php echo $jData['id']; ?>][collet_gram]" id="collet_gram-<?php echo $jData['id']; ?>" value=''  onKeyUp="calculateGold(<?php echo $jData['id']; ?>)"> </div>					
					<div class="divTableCell " style="width:70px !important">  <input type="text" name="product[<?php echo $jData['id']; ?>][collet_rate]" id="collet_rate-<?php echo $jData['id']; ?>" value='' onKeyUp="calculateGold(<?php echo $jData['id']; ?>)"> </div>					
					<div class="divTableCell " style="width:90px !important">  <input type="text" name="product[<?php echo $jData['id']; ?>][collet_amount]" readonly id="collet_amount-<?php echo $jData['id']; ?>" value='' onKeyUp="calculateGold(<?php echo $jData['id']; ?>)"> </div>					
					<div class="divTableCell " style="width:70px !important">  <input type="text" name="product[<?php echo $jData['id']; ?>][collet_handling]" id="collet_handling-<?php echo $jData['id']; ?>" value='' onKeyUp="calculateGold(<?php echo $jData['id']; ?>)"> </div>					
					<div class="divTableCell " style="width:70px !important">  <input type="text" name="product[<?php echo $jData['id']; ?>][collet_labour]" id="collet_labour-<?php echo $jData['id']; ?>" value='' onKeyUp="calculateGold(<?php echo $jData['id']; ?>)"> </div>					
					<div class="divTableCell " style="width:70px !important">  <input type="text" name="product[<?php echo $jData['id']; ?>][collet_gst]" id="collet_gst-<?php echo $jData['id']; ?>" value='' onKeyUp="calculateGold(<?php echo $jData['id']; ?>)"> </div>					
					<div class="divTableCell " style="width:90px !important">  <input type="text" readonly name="product[<?php echo $jData['id']; ?>][collet_gold_amount]" id="collet_gold_amount-<?php echo $jData['id']; ?>" value='' > </div>					
					<div class="divTableCell " style="width:70px !important">  <input type="text" readonly name="product[<?php echo $jData['id']; ?>][after_collet_gram]" id="after_collet_gram-<?php echo $jData['id']; ?>" value='' > </div>					
					<div class="divTableCell " style="width:90px !important">  <input type="text" readonly name="product[<?php echo $jData['id']; ?>][after_collet_amount]" id="after_collet_amount-<?php echo $jData['id']; ?>" value='' > </div>					
				</div>
				<?php endforeach;?>
				
				<div class="divTableRow">					
					<div class="divTableCell" style="width:100px !important"> <b>Total : <?php echo count($data['record']) ?></b></div>
					<div class="divTableCell a-right " style="width:40px !important" >  <b><?php echo $tpp?></b> </div>
					<div class="divTableCell a-right " style="width:50px !important"> <b> <?php echo $tpc?></b>  </div>					
					<div class="divTableCell " style="width:70px !important">  </div>
					<div class="divTableCell " style="width:60px !important">  </div>
					<div class="divTableCell" style="width:90px !important">  </div>
					<div class="divTableCell " style="width:90px !important">  </div>	
					<div class="divTableCell " style="width:70px !important">  </div>	
					<div class="divTableCell " style="width:70px !important">  </div>	
					<div class="divTableCell " style="width:90px !important">  </div>	
					<div class="divTableCell " style="width:70px !important">  </div>	
					<div class="divTableCell " style="width:70px !important">  </div>	
					<div class="divTableCell " style="width:70px !important">  </div>	
					<div class="divTableCell " style="width:90px !important">  </div>	
					<div class="divTableCell " style="width:70px !important">  </div>	
					<div class="divTableCell " style="width:90px !important">  </div>	
					
					
				</div>
			</div>
		</div>
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
	
	<div class="form-group col-sm-2 center">
				<label class="col-sm-4 control-label center no-padding-right" for="form-field-4">Date</label>
				<div class="col-sm-8 center">
					<input class="input-sm col-sm-12" id="date" name="date" type="text" style="margin-right:5px">					
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


function totalamount(no)
{
	var total =0.0;
	
	var amount = parseFloat(jQuery('#collet_amount-'+no).val());
	//var carat_amount = parseFloat(jQuery('#gold_amount').val());
	var labour_fee = parseFloat(jQuery('#collet_labour-'+no).val());	
	var handling_charge = parseFloat(jQuery('#collet_handling-'+no).val());
	var gst = parseFloat(jQuery('#collet_gst-'+no).val());
	var price = parseFloat(jQuery('#amount-'+no).html());
	var carat = parseFloat(jQuery('#carat-'+no).html());
	var collet_gram = parseFloat(jQuery('#collet_gram-'+no).val());
	
	if(isNaN(gst))
	{
		gst = 0;
	}
	if(isNaN(labour_fee))
	{
		labour_fee = 0;
	}
/* 	if(isNaN(roadiam_cost))
	{
		roadiam_cost = 0;
	} */
	/* if(isNaN(carat_amount))
	{
		carat_amount = 0;
	} */
	if(isNaN(amount))
	{
		amount = 0;
	}
	if(isNaN(handling_charge))
	{
		handling_charge = 0;
	}	
	total = amount + labour_fee+ handling_charge + gst;	
	
	if(!isNaN(total))
	{
		jQuery('#collet_gold_amount-'+no).val(total.toFixed(2));	
		jQuery('#after_collet_amount-'+no).val( (total + price).toFixed(2));	
		
		
	}	
	var temp = collet_gram + (parseFloat(carat/5) );
	jQuery('#after_collet_gram-'+no).val( temp.toFixed(2));	
}

function closePartyPopup()
{
	jQuery('#dialog-box-container1').hide();
}

function calculateGold(no)
{
	var gram = parseFloat(jQuery('#collet_gram-'+no).val());
	var price = parseFloat(jQuery('#collet_rate-'+no).val());
	var total  = parseFloat(gram * price);
	
	if(!isNaN(total))
	{	
		jQuery('#collet_amount-'+no).val(Math.abs(total.toFixed(2)));
	}
	
	//calculateCarat();
	totalamount(no);
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
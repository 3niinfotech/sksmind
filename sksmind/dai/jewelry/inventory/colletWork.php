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

include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');
include_once($daiDir.'jewelry/party/partyModel.php');
include_once($daiDir.'jewelry/jobwork/jobworkModel.php');

include_once('inventoryModel.php');
$imodel  = new inventoryModel($cn);

$model  = new jobworkModel($cn);
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);

$jewType = $jhelper->getJewelryType();
$design = $jhelper->getAllDesign();
$gold = $jhelper->getGoldType();
$goldColor = $jhelper->getGoldColor();
$mmaker = $jhelper->getAllMemoMaker();

$groupUrl = $daiDir.'jewelry/outward/';
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$entryno = $model->getOptionListEntryno('collet');
$lid=0;
if(isset($_POST['id']))
{
	$lid = $_POST['id'];
}
$data =  $model->getData($lid);		


if(isset($_POST['id']))
{
	$collet_stone = explode(",",$data['collet_stone']);
	$main_stone = explode(",",$data['main_stone']);
	$side_stone = explode(",",$data['side_stone']);
}
else
{
	$collet_stone = (isset($_POST['collet']))?$_POST['collet']:array(); 
	$main_stone = (isset($_POST['main']))?$_POST['main']:array(); 
	$side_stone = (isset($_POST['side']))?$_POST['side']:array(); 
}


?>

<div class="page-header">							
	<h1 style="float:left">
		Send to Job Work						
	</h1>
	<button id="close-box" onclick="closeBox(),closeBox1()" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
	<?php //if(!isset($_POST['id'])): ?>
	<button class="btn btn-info" style="float:right;margin-right: 10px;" id="btn-save" type="button" onClick="saveJobWork()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Save Job Work
	</button>
	
	<button class="btn reset"  style="float:right;margin-right: 10px;" type="reset">
		<i class="ace-icon fa fa-undo bigger-110"></i>
		Reset
	</button>
	<?php //endif; ?>
	<!--<?php if(isset($_POST['id'])): ?>
	
		<?php if($_POST['type'] == 'memo' || $_POST['type'] == 'lab'  ): ?>
			<a href="<?php echo $mainUrl.'pdf/file/memo.php?id='.$_POST['id'] ?>" target="_blank" style="float:right; margin-right: 10px;" class="btn btn-info" type="button" >
				<i class="ace-icon fa fa-print bigger-110"></i>
				Print Memo
			</a>
		<?php elseif($_POST['type'] == 'sale' || $_POST['type'] == 'export' || $_POST['type'] == 'consign' ): ?>
			<a href="<?php echo $mainUrl.'pdf/file/invoice.php?id='.$_POST['id'] ?>" target="_blank" style="float:right; margin-right: 10px;" class="btn btn-info" type="button" >
				<i class="ace-icon fa fa-print bigger-110"></i>
				Print Invoice
			</a>
		<?php endif; ?>
	<?php endif; ?>-->
	
</div>
<?php

//$entryno = $model->getIncrementEntry('job_no');


/*if($_POST['type'] == 'memo'):
	$invoice = $model->getIncrementEntry('memo_invoice');
endif;

if($_POST['type'] == 'lab'):
	$invoice = $model->getIncrementEntry('lab_invoice');
endif;
*/
?>

<form class="form-horizontal" id="job-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="sendToJob" />
<input type="hidden" name="job" value="collet" />

<input type="hidden" name="main_stone" value="<?php echo implode(",",$main_stone)?>">
<input type="hidden" name="collet_stone" value="<?php echo implode(",",$collet_stone) ?>">
<input type="hidden" name="side_stone" value="<?php echo implode(",",$side_stone) ?>">


<?php if(isset($_POST['id']))

{	

	//$entryno = $data['entryno'];

	//$invoice = $data['invoiceno'];	
	if(isset($_POST['return'])) ?>
	<input type="hidden" name="return" value="1" />
	
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />

<?php } ?>




<div class="col-xs-12 col-sm-12 ">
	
		
	<div class="form-group col-sm-3">	
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Job Number <span class="required">*</span></label>
		<div class="col-sm-8">
			<select class="col-xs-12" id="ledger" name="entryno">
				<option value="">Select Job Number</option>
				<?php 
				foreach($entryno as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['entryno'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">M Maker <span class="required">*</span></label>
		<div class="col-sm-8">
			<select class="col-xs-12" id="jew_type" name="memo_maker">
				<option value="">Memo Maker</option>
				<?php 
				foreach($mmaker as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['memo_maker'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
<!--	<div class="form-group col-sm-5">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Company <span class="required">*</span></label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="ledger" name="party">
				<option value="">Select Company Name</option>
				<?php 
				foreach($party as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['party'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>-->
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Date <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="date" value="<?php echo $data['date']?>" name="date" placeholder="" type="text">
		</div>
	</div>
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> D.Date <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="duedate" value="<?php echo $data['duedate']?>" name="duedate" placeholder="" type="text">
		</div>
	</div>
	<!---- -->
	
	<br>
	
	<div style="clear:both"></div>
	
</div>

<div class="col-xs-12 col-sm-12 jewelry-job" style="margin-bottom:30px;">

<div class="form-group col-sm-10" style="margin-right:10px;">
	<h3>Main Stone</h3>
	<div class="subform mainstone invenotry-cgrid main-grid">
		<div class="divTable" style="width:1600px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>					
				<div onClick="sortForFilter('sku')" class="divTableCell">SKU</div>
				<div onClick="sortForFilter('pcs')" class="divTableCell">PCS</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Carat</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Shape</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Clarity</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Color</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Price</div>
				<div onClick="sortForFilter('carat')" class="divTableCell">Amount</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">Lab</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Code</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Color</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Clarity</div>
				<div onClick="sortForFilter('amount')" class="divTableCell">IGI Amount</div>
				
			</div>	
			<div class="divTableBody" >
				<?php 
					$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
					$i = 0 ;
					foreach($main_stone as $id):
					$i++;
					$class ="";
					$value = $model->getProductDetail($id);
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?>
					</div>
						
					<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell  "><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['price'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['amount'];?></div>	<div class="divTableCell  "><?php echo $value['lab'];?></div>
					<div class="divTableCell  "><?php echo $value['igi_code'];?></div>			
					<div class="divTableCell  "><?php echo $value['igi_color'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['igi_clarity'];?></div>
					<div class="divTableCell a-right "><?php echo $value['igi_amount'];?></div>			
					
				</div>
				<?php 
			$tpcs = (int)$tpcs +  (int)$value['pcs'];
			$tcarat = (float)$tcarat +  (float)$value['carat'];
			$tcost = (float)$tcost +  (float)$value['cost'];
			$tprice = (float)$tprice +  (float)$value['price'];
			$tamount = (float)$tamount +  (float)$value['amount'];
			endforeach; ?>	
			</div>
		</div>
	</div>
</div>
<div class="form-group col-sm-2" style="margin-right:10px;margin-top:100px;">
	<p><b>Total Carat :</b> <span id="total_carat"><?php echo $tcarat; ?></span> </p>
	<p><b>Total Amount :</b> <span id="total_amount"><?php echo $tamount; ?></span> </p>
</div>
</div>

<div style="clear:both"></div>

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
			<select class="col-xs-10" id="gold" name="gold" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()">
				<option value="">Gold Carat</option>
				<?php 
				foreach($gold as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['gold'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			
		</div>
	</div>
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Gross WT <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="gross_weight" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()" value="<?php echo $data['gross_weight']?>" name="gross_weight" placeholder="" type="text">
		</div>
	</div>
		
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right"  for="form-field-4">NET WT <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="net_weight" value="<?php echo $data['net_weight']?>" name="net_weight" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">PG WT <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="pg_weight" value="<?php echo $data['pg_weight']?>" name="pg_weight" placeholder="" type="text">
		</div>
	</div>
		<div style="clear:both"></div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Rate <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="rate" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()" value="<?php echo $data['rate']?>" name="rate" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Amount <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="amount" readonly value="<?php echo $data['amount']?>" name="amount" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Other Code </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="other_code" value="<?php echo $data['other_code']?>" name="other_code" placeholder="" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Other Amount </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="other_amount" onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()" value="<?php echo $data['other_amount']?>" name="other_amount" placeholder="" type="text">
		</div>
	</div>
	<div style="clear:both"></div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Labour Rate</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="labour_rate"  onBlur="calculateTotalAmount()" onChange="calculateTotalAmount()" value="<?php echo $data['labour_rate']?>" name="labour_rate" placeholder="" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Labour Amount</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="labour_amount" readonly value="<?php echo $data['labour_amount']?>" name="labour_amount" placeholder="" type="text">
		</div>
	</div>
		
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Total Amount </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12 a-right" id="ftotal_amount"  readonly value="<?php echo $data['total_amount']?>" name="total_amount" placeholder="" type="text">
		</div>
	</div>

	
	
</form>

<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
<script type="text/javascript">
$( "#date, #duedate" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});

function saveJobWork()
{
	jQuery('#btn-save').attr('disabled',true);
	var data =  $("#job-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $jewelryUrl.'jobwork/jobworkController.php'?>', 
		type: 'POST',
		data: data,		
		success: function(result)
			{		
				if(result != "")
				{
					jQuery('#dialog-box-container').show();
					//$('#dialog-box-container').html('<div class="box-container-memo" >'+result+'</div>');									
					jQuery('#please-wait').hide();
					var obj = jQuery.parseJSON(result);
					alert(obj.message);
					if(obj.status)				
					{
						loadGrid();
					}
					jQuery('#btn-save').attr('disabled',false);
				}
			}
	});	
} 


function calculateTotalAmount()
{
	var gross_weight = parseFloat(jQuery('#gross_weight').val());
	var total_carat = parseFloat(jQuery('#total_carat').html());
	var total_amount = parseFloat(jQuery('#total_amount').html());
	
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
		}	
	}
	else
	{
		alert('Please Select Gold Carat');
	}	
	
	
}
</script>	
<style>	
.divTableCell input {border:0 !important;}	
.form-group{margin-bottom:10px !important;}
.jewelry-job .main-grid .divTableBody {
	height: 130px !important;
}
</style>
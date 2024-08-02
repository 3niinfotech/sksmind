<?php session_start();include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'Helper.php');
$helper  = new Helper($cn);
$attribute = $helper->getAttribute();							
$width50 = $helper->width50();
//$memo = $helper->getInventory('rmemo'); 


$data = $helper->getInvetoryTransactionJew($_POST);
 /* echo "<pre>";
print_r($data);
exit;  */
include_once($daiDir.'account/party/partyModel.php');
include_once($daiDir.'account/balance/balanceModel.php');
include_once($daiDir.'account/subgroup/subgroupModel.php');
$pmodel  = new partyModel($cn);
$sgmodel  = new subgroupModel($cn);
$Bmodel  = new balanceModel($cn);

$party = $pmodel->getOptionList();	
$subgroup = $sgmodel->getOption();	
$i=1;
//$cData = $Bmodel->getAllCurrency();
$book = $helper->getAllBook();
$currency = $helper->getAllCurrency();

?>
<div class="page-header">	
	<h1 style="float:left">
		<?php echo $party[$data['party']] ?>						
	</h1>	
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>	
</div>

	<div class="alldata">
		<ul>
			<li><b>Date :</b><?php echo $data['date'] ?>	</li>
			<!--	<li><b>Reference :</b><?php echo $data['reference'] ?></li>
			<li><b>Entry No :</b><?php echo $data['entryno'] ?></li>
			-->
			<li><b>Invoice No :</b><?php echo $data['invoiceno'] ?></li>
			
			<li><b>Invoice Date :</b>			
			<?php 				
			$phpdate = strtotime( $data['date'] );				
			echo  date( 'd-m-Y', $phpdate );			
			?></li>
		<!--		<li><b>Terms :</b><?php echo $data['terms'] ?></li>
		<li><b>Due Date:</b><?php  			if($data['terms'] =='' || $data['terms'] =='0'):				$phpdate = strtotime( $data['date'] );			else:				$phpdate = strtotime( $data['duedate'] );			endif;			echo  date( 'd-m-Y', $phpdate );						?></li>-->
			<li><b>Final Amount:</b><?php echo $data['final_amount'] ?></li>			<li><b>Paid Amount:</b><?php echo $data['paid_amount'] ?></li>
			<li><b>Due Amount:</b><?php echo $data['due_amount'] ?></li>			<li><b>Less Percentage:</b><?php echo $data['less_percent'] ?></li>						<li><b>Less Amount:</b><?php echo $data['less_amount'] ?></li>						<li><b>Charges:</b><?php echo $data['charge'] ?></li>	
			<li><b>Narration</b><?php echo $data['narretion'] ?></li>
		</ul>
	</div>
<div style="clear:both"></div>
<hr>
<form class="form-horizontal" id="saveLess" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'account/expense/expenseController.php'?>">
    <input type="hidden" name="fn" value="saveCharge" />
    <input type="hidden" name="sale_id" value="<?php echo $data['id'] ?>" />
 <!--   <input type="hidden" name="type" value="<?php echo $_POST['type'] ?>" />-->
    <input type="hidden" name="" id="final_amount" value="<?php echo $data['final_amount'] ?>" />
    <div class="col-xs-12 col-sm-12 " style="">
        <div class="form-group col-sm-4 center">
            <label class="col-sm-12 control-label center no-padding-right" for="form-field-4">Less %</label>
            <div class="col-sm-12">
                <input class="input-sm col-sm-3 a-right" name="less_percent" onKeyUp="calculateLess(this.value)" placeholder="Less %" value="<?php echo ($data['less_percent']!=0)?$data['less_percent']:'' ?>" type="text" style="margin-right:5px">
                <input class="input-sm col-sm-4 a-right" name="less_amount" id="less_amount" readonly value="<?php echo ($data['less_amount'] !=0 )? $data['less_amount']:''; ?>" type="text" style="margin-right:5px">
                <input class="input-sm col-sm-4 a-right" disabled id="netless" type="text"> </div>
        </div>
        <div class="form-group col-sm-4 center">
            <label class="col-sm-12 control-label no-padding-right center" for="form-field-4">Other Less % </label>
            <div class="col-sm-12">
                <input class="input-sm col-sm-3 a-right" name="other_less_percent" onKeyUp="calculateOtherLess(this.value)" placeholder="Enter Less %" value="<?php echo ($data['other_less_percent'] !=0)?$data['other_less_percent']:'' ?>" type="text" style="margin-right:5px">
                <input class="input-sm col-sm-4 a-right" name="other_less_amount" id="other_less_amount" readonly value="<?php echo ($data['other_less_amount'] !=0 )?$data['other_less_amount']:'' ?>" type="text" style="margin-right:5px">
                <input class="input-sm col-sm-4 a-right" disabled id="other_netless" type="text"> </div>
        </div>
        <div class="form-group col-sm-2 center">
            <label class="col-sm-12 center control-label no-padding-right" for="form-field-4">Extra Charge</label>
            <div class="col-sm-12">
                <input class="input-sm col-sm-11 a-right" id="Extra" name="charge" value="<?php echo $data['charge'] ?>" type="text"> </div>
        </div>
        <div class="col-sm-2 center">
            <button class="btn btn-info" type="button" onClick="saveCharge(<?php echo $_POST['eid'] ?>)"> <i class="ace-icon fa fa-check bigger-110"></i> Save Charge </button>
        </div>
    </div>
</form>
<div style="clear:both"></div>
<hr>
<p class="blue">&nbsp; &nbsp; <b>Final Amount : </b> &nbsp;
    <?php echo $data['final_amount'] ?>
        | &nbsp; &nbsp; <span class="green"><b>Paid Amount : </b> &nbsp; <?php echo $data['paid_amount'] ?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="red"><b>Due Amount  : </b> &nbsp; <?php echo $data['due_amount'] ?></span>
</p>
<?php if(count($data['record'])): ?>

	<div class="subform invenotry-cgrid " style="overflow-x: auto;" >
		<div class="divTable" style="width:100%">
			<div class="divTableHeading">				
				<div class="divTableCell">No</div>								
				<div class="divTableCell" style="width:8%;text-align:center;">Date</div>
				<div class="divTableCell" style="width:20%">Account</div>
				<div class="divTableCell" style="width:20%">Party</div>				
				<div class="divTableCell" style="width:40%">Description</div>
				<div class="divTableCell " style="width:8%;text-align:center;">Amount</div>				
			</div>	
			<div class="divTableBody" style="overflow-y: auto;height:70px;">
			<?php 
				$tcr = $tdr = 0.0; 
				$i=1;
				foreach($data['record'] as $jData):
				$tcr += (float)$jData['amount'];				
			?>
				
				<div class="divTableRow ">					
					<div class="divTableCell"><?php echo $i; ?></div>		
					<div class="divTableCell" style="width:8%"><?php 
					//echo $jData['date']; 					
					$phpdate = strtotime( $jData['date'] );				
					echo  date( 'd-m-Y', $phpdate );					
					?></div>		
					<div class="divTableCell" style="width:20%"><?php echo isset($subgroup[$jData['under_subgroup']])?$subgroup[$jData['under_subgroup']]:"";?></div>		
					<div class="divTableCell" style="width:20%"><?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>					
					<div class="divTableCell" style="width:40%"><?php echo $book[$jData['book']]." - ". $jData['description']; ?></div>
					<div class="divTableCell a-right" style="width:8%"><?php echo $jData['amount'] ?></div>
				</div>
			<?php $i++;
			endforeach;
			?>
			<div class="divTableRow">					
				<div class="divTableCell"></div>								
				<div class="divTableCell" style="width:8%;text-align:center;"></div>
				<div class="divTableCell" style="width:20%"></div>
				<div class="divTableCell" style="width:20%"></div>				
				<div class="divTableCell" style="width:40%"><b>Total</b></div>
				<div class="divTableCell a-right" style="width:8%;"><b><?php echo $tcr; ?></b></div>
				
			</div>
			</div>	
			</div>
	</div>

<?php else: ?>	<div style="clear:both"></div>		<hr>		
		<h3 style="text-align:center" >No Transaction found.</h3>			<br>
<?php endif;?>
<?php if($data['due_amount'] != "0.00" && $data['due_amount'] !="0" ): ?>
	<div style="clear:both"></div>
	<hr>
	<form class="form-horizontal" id="savePart" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'account/expense/expenseController.php'?>">
		<input type="hidden" name="fn" value="savePartJew" />
		<input type="hidden" name="party" value="<?php echo $data['party'] ?>" />		
		<input type="hidden" name="type" value="cr" />		
		<input type="hidden" name="jew_sale_id" value="<?php echo $data['id'] ?>" />		
		<div class="col-xs-12 col-sm-12 " style="">
			<div class="form-group col-sm-3">
				<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Date</label>
				<div class="col-sm-8">
					<input class="input-sm col-sm-10" id="date"  name="date" placeholder="Select Date" type="text">
				</div>
			</div>
			<div class="form-group col-sm-3">
				<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Book</label>
				<div class="col-sm-8">
					<select class="col-xs-12" id="book" name="book">
						<option value="">Select Book</option>
						<?php foreach($book as $k=>$v): ?>
						<option value="<?php echo $k ?>"><?php echo $v ?></option>
						<?php endforeach;?>			
					</select>
				</div>
			</div>
			
			<div class="form-group col-sm-3">
				<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Amount</label>
				<div class="col-sm-8">
					<input class="input-sm col-sm-10 a-right" id="amount" name="amount" type="text" value="<?php echo $data['due_amount']?>">
				</div>
			</div>
			<div class="form-group col-sm-3">
				<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Cheque</label>
				<div class="col-sm-8">
					<input class="input-sm col-sm-10" id="cheque" name="cheque" value="" type="text">
				</div>
			</div>
			<div style="clear:both"></div>
			<br>
			<!---- -->
			
			
			<div class="form-group col-sm-6">
				<label class="col-sm-2 control-label no-padding-right" for="form-field-4">Description</label>
				<div class="col-sm-10">
				<input class="input-sm col-sm-12" id="description" name="description" placeholder="Enter Description" type="text" value="<?php if($data['paid_amount'] == 0):echo ''; else: echo " Part ";endif; ?> Payment Received of Invoice No:<?php echo $data['invoiceno'] ?> ">
				
			</div>
			</div>
			<div class="col-md-6">
				<button class="btn btn-info" type="button" onClick="savePart(<?php echo $_POST['eid'] ?>)"> <i class="ace-icon fa fa-check bigger-110"></i> Save Installment </button> &nbsp; &nbsp; &nbsp;
				<button class="btn reset" type="reset"> <i class="ace-icon fa fa-undo bigger-110"></i> Reset </button>
			</div>
		</div>		
		<div style="clear:both"></div>		
		<hr>	
		</form>				
<?php endif; ?><style>.form-group {	margin-bottom: 0px !important;}.a-right{text-align:right;}</style><script>function calculateLess(value) 
{
    value = parseFloat(value);
    var famount = parseFloat(jQuery('#final_amount').val());
    var total = parseFloat((famount * value) / 100);
    if (!isNaN(total)) 
	{
        jQuery('#less_amount').val(Math.abs(total.toFixed(2)));
    }
    if (value < 0) 
	{
        total = famount - Math.abs(total);
    } else 
	{
        total = famount + Math.abs(total);
    }
    if (!isNaN(total)) 
	{
        jQuery('#netless').val(Math.abs(total.toFixed(2)));
    }
}

function calculateOtherLess(value) {
    value = parseFloat(value);
    var famount = parseFloat(jQuery('#netless').val());
    var total = parseFloat((famount * value) / 100);
    if (!isNaN(total)) 
	{
        jQuery('#other_less_amount').val(Math.abs(total.toFixed(2)));
    }
    if (value < 0) 
	{
        total = famount - Math.abs(total);
    } else 
	{
        total = famount + Math.abs(total);
    }
    if (!isNaN(total)) 
	{
        jQuery('#other_netless').val(Math.abs(total.toFixed(2)));
    }
}</script>
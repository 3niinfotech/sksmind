<?php

//include('model.php');
//$model  = new model();
//$entryno = $model->getIncrementEntry('inward');
$t = "";

if(isset($_GET['t']))
{
		$t = $_GET['t'];
}
?>
<form class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'account/advance/advanceController.php'?>">
<input type="hidden" name="fn" value="save" />


<?php  if(isset($_GET['id']))
{	
	//$entryno = $data['entryno']; 
?>
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
<?php } ?>
<div class="col-xs-12 col-sm-7 " style="margin-left:25%;">
	<br>
	<div class="form-group col-sm-7">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Party Name</label>
		<div class="col-sm-9">
			<select class="col-xs-10" id="ledger" name="party">
				<option value="">Select Party</option>
				<?php 
				foreach($party as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['party'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
			<a href="javascript:void(0);" onclick="addParty()" style="margin-top:8px;margin-left:5px;float: left;" > Add Party</a>
		</div>
	</div>	
	<div class="form-group col-sm-5">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Date</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="date" value="<?php echo $data['date']?>" name="date" placeholder="Select Date" type="text">
		</div>
	</div>
	
	<div style="clear:both"></div>
	<br>
	<!---- -->
	
	
	<div class="form-group col-sm-5">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Book Type</label>
		<div class="col-sm-8">
			<select class="col-xs-12" id="book" name="book" <?php if($data['book'] !=""){ echo 'disabled';} ?>>				
				<option value="">Select</option>
				<?php foreach($bookData as $k=>$v): ?>
					<option value="<?php echo $k; ?>" <?php echo ($k == $data['book'])?"selected":""; ?>><?php echo $v;?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group col-sm-2">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4"></label>
		<div class="col-sm-8">
		</div>
	</div>
	
	<div class="form-group col-sm-5">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Amount</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10 a-right" id="amount" <?php  if(isset($_GET['id'])):?> readonly <?php endif; ?> value="<?php echo $data['amount']?>" name="amount" type="text">
		</div>
	</div>
	
	<div style="clear:both"></div>
	<br>
	<?php  if(isset($_GET['id'])) : ?>	
	
		<div class="form-group col-sm-6">
			<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Used Amount</label>
			<div class="col-sm-8">
				<input class="input-sm col-sm-10 a-right"  value="<?php echo $data['use_amount']?>" readonly name="use_amount" type="text">
			</div>
		</div>
		
		<div class="form-group col-sm-6">
			<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Balnce Amount</label>
			<div class="col-sm-8">
				<input class="input-sm col-sm-10 a-right"  value="<?php echo $data['balance_amount']?>" readonly name="balance_amount" type="text">
			</div>
		</div>
	<div style="clear:both"></div>
	<br>
		<div class="form-group col-sm-6">
			<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Select Invoice</label>
			<div class="col-sm-8">
				<select class="col-xs-12" id="book" name="invoice" onChange="getInvoiceData(this.value)" >				
					<option value="">Select Invoice</option>
					<?php foreach($invoice as $k=>$v): ?>
						<option value="<?php echo $k; ?>" <?php echo ($k == $data['invoice'])?"selected":""; ?> ><?php echo $v;?></option>
					<?php endforeach; ?>
				</select>
				
				<?php //echo $data['invoice']?>
			</div>
		</div>
		<div class="col-sm-6 blue" id="invoice-data" style="display:none;" >
			<span><b>Paid Amount :</b> <span id="paid_amount"></span> &nbsp;&nbsp;&nbsp; | &nbsp;</span>
			<span><b>Due Amount : </b> <span id="due_amount" ></span></span>
		</div>
	<?php endif; ?>
			
	
</div>


<div class="col-xs-12 col-sm-12 " style="margin-top:30px">
	
<div class="clearfix form-actions" style="text-align:center;" >
	<div class="col-md-12">
		<button class="btn btn-info" type="submit">
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

<style>
.form-group {
  margin-bottom: 20px;
}

.divTableCell {
  float: left;
  padding: 0;
  text-align: center;
  width: 109px;
}
.divTableCell input
{
 padding: 0 2px 3px;
}
.divTableCell:first-child {
  width: 20px !important;
  padding-top: 3px;
}
.divTableCell:nth-child(2) {
  padding: 5px 0 0;
  width: 60px !important;
}
.divTableHeading, .divTableBody, .divTableFooter {
  float: left;
  width: 100%;
}
.divTableHeading {
  border-bottom: 1px solid #f1f1f1;
  border-top: 1px solid #f1f1f1;
  margin-bottom: 10px;
  margin-top: 10px;
  padding-bottom: 10px;
  padding-top: 5px;
}
.divTableHeading .divTableCell {
  color: #666;
  font-size: 13px;
  font-weight: bold;
  letter-spacing: 1px;
}
.divTableRow {
  float: left;
  margin: 0;
  width: 100%;
}
.divTable {
  font-size: 14px;
}
.a-right {
  padding-top: 15px;
  text-align: right !important;
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
.divTableBody .divTableRow .divTableCell input {
  color:#333;
}
</style>
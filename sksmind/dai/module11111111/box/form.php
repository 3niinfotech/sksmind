<?php
//include('model.php');
//$model  = new model();
$entryno = $model->getIncrementEntry();
$t = "";
if(isset($_GET['t']))
{
		$t = $_GET['t'];
}
?>

<form class="form-horizontal" method="POST" role="form" action="<?php echo $moduleUrl.'inward/inwardController.php'?>">
<input type="hidden" name="fn" value="save" />
<input type="hidden" name="inward_type" value="<?php echo $t; ?>" />
<?php if(isset($_GET['id']))
{	
	$entryno = $data['entryno']; 
?>
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
<?php } ?>
<div class="col-xs-12 col-sm-12 ">
	
		
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Entry</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="entryno" name="entryno" placeholder="Entry" readonly value="<?php echo $entryno; ?>" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Inward @</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="place" name="place" value="<?php echo $data['place']?>" placeholder="Place" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Date</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="date" value="<?php echo $data['date']?>" name="date" placeholder="Select Date" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Reference</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="reference" name="reference" value="<?php echo $data['reference']?>" placeholder="#2016/08/45" type="text">
		</div>
	</div>
	
	<!---- -->
	
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Invoice No.</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="invoiceno" value="<?php echo $data['invoiceno']?>" name="invoiceno" placeholder="INC201608" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Invoice Date</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="invoicedate" value="<?php echo $data['invoicedate']?>" name="invoicedate" placeholder="2016/08/20" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Terms</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="terms" value="<?php echo $data['terms']?>" name="terms" placeholder="Your Terms" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Due Date</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="duedate" value="<?php echo $data['duedate']?>" name="duedate" placeholder="2016/09/02" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-6">
		<label class="col-sm-2 control-label no-padding-right" for="form-field-4">Party Name</label>
		<div class="col-sm-8">
			<select class="col-xs-10" id="ledger" name="party">
				<option value="">Select Party</option>
				<?php 
				foreach($party as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['party'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
		</div>
	</div>
	<div style="clear:both"></div>
	<hr>
	<div class="col-xs-12 col-sm-12">
		<div class="control-group">
			<button class="btn btn-info" type="submit">
				<i class="ace-icon fa fa-upload bigger-110"></i>
				Import from Excel
			</button>
		</div>
	</div>
	
	
</div>
<?php include('subform/import.php'); ?>

<div class="col-xs-12 col-sm-12 " style="margin-top:30px">
	
<div class="clearfix form-actions" >
	<div class="col-md-12">
		<div class="form-group col-sm-5">		
			<textarea class="form-control" id="form-field-8" placeholder="Narretion" style="width: 353px; height: 88px;" name="narretion"><?php echo $data['narretion']?></textarea>
		</div>
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
  margin-bottom: 30px;
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
</style>
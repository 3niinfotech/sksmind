<form class="form-horizontal" id="edit-sale" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<?php if(isset($_POST['id']))
{	
	
?>
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
	<input type="hidden" name="fn" value="updateLab" />
<?php } ?>
<div class="col-xs-12 col-sm-12 ">
	
		
	<div class="form-group col-sm-1" style="width:150px;">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12" id="entryno" name="entryno" placeholder="Entry" readonly value="<?php echo $data['entryno']; ?>" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Invoice</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="invoiceno" value="<?php echo $data['invoiceno']?>" readonly name="invoiceno" placeholder="INC201608" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Reference </label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-10" id="reference" name="reference" value="<?php echo $data['reference']?>" placeholder="#2016/08/45" type="text">
		</div>
	</div>
	
	
	<div class="form-group col-sm-2">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4"><?php //echo ucFirst($_POST['type'])?> Date <span class="required">*</span></label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="date" value="<?php echo $data['date']?>" name="date" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-5">
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
	</div>
	<div style="clear:both"></div>
	<hr>
</div>
<?php include('editlabgrid.php'); ?>

<style>
.export .form-group{margin-bottom:0px !important;}
</style>
	
<div class="clearfix form-actions" >
	<div class="col-md-12">
		<div class="form-group col-sm-5">		
			<textarea class="form-control" id="form-field-8" placeholder="Narretion" style="width: 353px; height: 88px;" name="narretion"><?php echo $data['narretion']?></textarea>
		</div>
		<button class="btn btn-info" type="button" onClick="saveEditSale()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Update Data
		</button>
		
		<button id="close-box" onclick="closeBox1(<?php echo $_POST['lid']; ?>)"  class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
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
  width: 109px;
  text-align:center;
}
.sale-grid .divTableCell {
  text-align:center;
}
.divTableCell input
{
 padding: 0 2px 3px;
}
.divTableCell:first-child {
  width: 20px !important;
  padding-top: 3px;
}
.sale-grid .divTableCell:nth-child(2) {
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
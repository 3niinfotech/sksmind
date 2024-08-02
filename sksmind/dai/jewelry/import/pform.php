<form class="form-horizontal" id="edit-sale" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'inward/inwardController.php'?>">
<?php	
	$entryno = $data['entryno']; 
?>
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
	<input type="hidden" name="fn" value="updateInward" />
	<input type="hidden" name="inward_type" value="<?php echo $data['inward_type'] ?>" />

<div class="col-xs-12 col-sm-12 ">
	
		
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Entry</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="entryno" name="entryno" placeholder="Entry" readonly value="<?php echo $entryno; ?>" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Purchase @</label>
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
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Paid Amount</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="paid_amount"  name="paid_amount" value="<?php echo $data['paid_amount']?>" placeholder="#2016/08/45" type="text">
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-sm-5 control-label no-padding-right" for="form-field-4">Due Amount</label>
		<div class="col-sm-7">
			<input class="input-sm col-sm-12" id="due_amount"  name="due_amount" value="<?php echo $data['due_amount']?>" placeholder="#2016/08/45" type="text">
		</div>
	</div>
	<div style="clear:both"></div>
	<hr>
</div>
<?php $flag = 0;
include('subform/pgrid.php'); ?>

<?php if($data['inward_type'] == 'export'): ?>
<div style="clear:both"></div>
<br>
<div class="col-xs-12 export col-sm-12 ">
	<div class="form-group col-sm-3" style="padding: 0px; margin-bottom: 5px; margin-right: 0px; ">								
		<label class="col-sm-4 control-label no-padding-right a-right" for="form-field-4">Shipping</label>
		<div class="col-sm-8">				
			<select class="col-xs-12" name="shipping_name" >
				<option value="">Select Shipping</option>
				<?php foreach($shipping as $k=>$v): ?>
					<option value="<?php echo $k ?>" <?php if($data['shipping_name'] == $k): ?> selected <?php endif;?> ><?php echo $v ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group col-sm-3" style="padding: 0px; margin-bottom: 5px; margin-right: 0px; ">								
			<label class="col-sm-4 control-label no-padding-right a-right" for="form-field-4">Origin of</label>
			<div class="col-sm-8">				
				<select class="col-xs-10" name="origin_of" >
					<option value="">Select Origin</option>
					<?php foreach($origin as $k=>$v): ?>
						<option value="<?php echo $k ?>" <?php if($data['origin_of'] == $k): ?> selected <?php endif;?> ><?php echo $v ?></option>
					<?php endforeach; ?>
				</select>
			</div>
	</div>
	<div class="form-group col-sm-3" style="padding:0px;margin-bottom: 5px;">
		<label class="col-sm-4 control-label no-padding-right a-right" for="form-field-4">Charge</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-8" name="shipping_charge" value="<?php echo $data['shipping_charge']; ?>" type="text">	
		</div>
	</div>	
</div>
<style>
.export .form-group{margin-bottom:0px !important;}
</style>
<?php endif; ?>

<div class="col-xs-12 col-sm-12 " <?php if($data['inward_type'] == 'export'): ?>style="margin-top:0px" <?php else:?>style="margin-top:20px"<?php endif;?>>
<?php if($flag == 0): ?>
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
<?php else: ?>
	<h3 class="red">You can not Edit this Purchase.</h3>
	<button id="close-box" onclick="closeBox1(<?php echo $_POST['lid']; ?>)"  class="btn btn-danger" type="button">
			<i class="ace-icon fa fa-close bigger-110"></i>
			Close
		</button>
<?php endif; ?>
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
}
.divTableCell input
{
 padding: 0 2px 3px;
}
.divTableCell:first-child {
  width: 20px !important;
  padding-top: 3px;
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
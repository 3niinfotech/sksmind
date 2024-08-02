<?php
//include('model.php');
//$model  = new model();
$entryno = $model->getIncrementEntry('inward');
$reference = $model->getIncrementEntry('reference');
$t = "";

if(isset($_GET['t']))
{
		$t = $_GET['t'];
}
?>
<form class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $jewelryUrl.'import/inwardController.php'?>">
<input type="hidden" name="fn" value="looseSave" />
<input type="hidden" name="daiUrl" value="<?php echo $daiDir.'upload/'; ?>" />
<input type="hidden" name="inward_type" value="<?php echo $t; ?>" />
<?php if(isset($_GET['id']))
{	
	$entryno = $data['entryno'];
	$reference = 	$data['reference'];
?>
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
<?php } ?>
<div class="col-xs-12 col-sm-12 ">
	
	
	
	
	<br>
	<div style="clear:both;"></div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Entry</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="entryno" name="entryno" placeholder="Entry" readonly value="<?php echo $entryno; ?>" type="text">
		</div>
	</div>
	<?php /*
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Inward @</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="place" name="place" value="<?php echo $data['place']?>" placeholder="Place" type="text">
		</div>
	</div> */ ?>
	<!-- <div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Date</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="date" value="<?php echo $data['date']?>"  name="date" placeholder="Select Date" type="text">
		</div>
	</div> -->
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Reference <span class="required">*</span></label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="reference" name="reference" value="<?php echo $reference?>" readonly placeholder="#2016/08/45" type="text">
		</div>
	</div>
	
	<!---- -->
	
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Invoice No. <span class="required">*</span></label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="invoiceno" value="<?php echo $data['invoiceno']?>" required name="invoiceno" placeholder="Enter Invoice" type="text">
		</div>
	</div>
	<div style="clear:both;"></div>
	<div class="form-group col-sm-3">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Date <span class="required">*</span></label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="invoicedate" required value="<?php echo $data['invoicedate']?>" name="invoicedate" placeholder="Select Date" type="text">
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
			<input class="input-sm col-sm-10" id="duedate" value="<?php echo $data['duedate']?>" name="duedate" placeholder="Due Date" type="text">
		</div>
	</div>
	<div style="clear:both;"></div>
	<div class="form-group col-sm-6" >
		<label class="col-sm-2 control-label no-padding-right" for="form-field-4">Company <span class="required">*</span></label>
		<div class="col-sm-10">
			<select class="col-xs-8" id="ledger" name="party" required >
				<option value="">Select Company Name</option>
				<?php 
				foreach($party as $key => $value):
				?>						
				<option value="<?php echo $key?>" <?php echo ($key == $data['party'])?"selected":""; ?> ><?php echo $value?></option>
				<?php endforeach; ?>
			
			</select>
		</div>
	</div>
	
	
</div>

<?php include('subform/loose.php'); ?>


<!-- footer -->
<div class="subform">
	<div class="divTableRow" id = "tni_sub_menu" style = "font-weight:bold">
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">Sub Total</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell"><input type="text" name="totalqty" id="totalqty" value="" class="challan-input col-sm-12 a-right" readonly=""></div>
		<div class="divTableCell"><input type="text" name="total_nwt" id="total_nwt" value="" class="challan-input col-sm-12 a-right" readonly=""></div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell"><input type="text" name="totalamount" id="totalamount" value="" readonly="" class="challan-input col-sm-12 a-right"></div>
		<div class="divTableCell">&nbsp;</div>
	</div>	
	<div class="divTableRow" id = "tni_sub_menu" style = "font-weight:bold">
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
	</div>	
	<div class="divTableRow" id = "tni_sub_menu" style = "font-weight:bold; margin-bottom: 10px;">
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">SGST</div>
		<div class="divTableCell">
			<select class="col-sm-12" id="sgst_per" name="sgst_per" required onchange="calGst()">
				<option value="0.000">0.000%</option>	
				<option value="0.125">0.125%</option>	
				<option value="0.250">0.250%</option>
				<option value="1.500">1.500%</option>
				<option value="3.000">3.000%</option>
				<option value="2.500">2.500%</option>	
				<option value="5.000">5.000%</option>				
			</select>
		</div>
		<div class="divTableCell"><input type="text" name="sgst_amount" id="sgst_amount" value="" readonly="" class="challan-input col-sm-12 a-right"></div>
		<div class="divTableCell">&nbsp;</div>
	</div>	
	<div class="divTableRow" id = "tni_sub_menu" style = "font-weight:bold; margin-bottom: 10px;">
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">CGST</div>
		<div class="divTableCell">
			<select class="col-sm-12" id="cgst_per" name="cgst_per" required onchange="calGst()">
				<option value="0.000">0.000%</option>	
				<option value="0.125">0.125%</option>	
				<option value="0.250">0.250%</option>
				<option value="1.500">1.500%</option>
				<option value="3.000">3.000%</option>
				<option value="2.500">2.500%</option>	
				<option value="5.000">5.000%</option>				
			</select>
		</div>
		<div class="divTableCell"><input type="text" name="cgst_amount" id="cgst_amount" value="" readonly="" class="challan-input col-sm-12 a-right"></div>
		<div class="divTableCell">&nbsp;</div>
	</div>	
	<div class="divTableRow" id = "tni_sub_menu" style = "font-weight:bold; margin-bottom: 10px;">
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">IGST</div>
		<div class="divTableCell">
			<select class="col-sm-12" id="igst_per" name="igst_per" required onchange="calGst()">
				<option value="0.000">0.000%</option>	
				<option value="0.125">0.125%</option>	
				<option value="0.250">0.250%</option>
				<option value="1.500">1.500%</option>
				<option value="3.000">3.000%</option>
				<option value="2.500">2.500%</option>	
				<option value="5.000">5.000%</option>				
			</select>
		</div>
		<div class="divTableCell"><input type="text" name="igst_amount" id="igst_amount" value="" readonly="" class="challan-input col-sm-12 a-right"></div>
		<div class="divTableCell">&nbsp;</div>
	</div>	
	<div class="divTableRow" id = "tni_sub_menu" style = "font-weight:bold">
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
	</div>	
	<div class="divTableRow" id = "tni_sub_menu" style = "font-weight:bold">
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell">Final Amount</div>
		<div class="divTableCell">&nbsp;</div>
		<div class="divTableCell"><input type="text" name="afteramount" id="afteramount" value="" readonly="" class="challan-input col-sm-12 a-right"></div>
		<div class="divTableCell">&nbsp;</div>
	</div>	
</div>
<!-- footer -->


<div class="col-xs-12 col-sm-12 " style="margin-top:30px">	
	<div class="clearfix form-actions" >
		<div class="col-md-12">
			<div class="form-group col-sm-5">		
				<textarea class="form-control" id="form-field-8" placeholder="Narretion" style="width: 353px; height: 88px;" name="narretion"><?php echo $data['narretion']?></textarea>
			</div>
			<button class="btn btn-info" type="submit" name="btnsave" value="save">
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
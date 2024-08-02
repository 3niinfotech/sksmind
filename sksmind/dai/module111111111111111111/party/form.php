<?php
//include('model.php');
//$model  = new model();
?>
<form class="form-horizontal" method="POST" role="form" action="<?php echo $moduleUrl.'party/partyController.php'?>">
<input type="hidden" name="fn" value="save" />
<input type="hidden" name="type" value="inventory" />
<?php 
	if(isset($_GET['id']))
	{ ?>
		<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
<?php } ?>
<div class="col-xs-12 col-sm-6 ">
	
	<h4 class="widget-title">Company Detail</h4>
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Company Name</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="name" name="name" value="<?php echo $data['name']  ?>"  placeholder="Company Name"  type="text">
		</div>
	</div>

	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Address</label>
		<div class="col-sm-8">			
			<textarea  class="input-sm col-sm-10" name="address" ><?php echo $data['address']  ?></textarea>
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Country</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="country" name="country" value="<?php echo $data['country']  ?>" placeholder="Country"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Pincode</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="pincode" value="<?php echo $data['pincode']  ?>" placeholder="Pincode"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Email Id</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="email" value="<?php echo $data['email']  ?>" placeholder="Email Id"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Contact Number</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="contact_number" value="<?php echo $data['contact_number']  ?>" placeholder="Contact Number"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Fax</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="fax" value="<?php echo $data['fax']  ?>" placeholder="Fax"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Contact Person</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="contact_person" value="<?php echo $data['contact_person']  ?>" placeholder="Contact Person"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Website</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="website" value="<?php echo $data['website']  ?>" placeholder="Website"  type="text">
		</div>
	</div>
</div>

<div class="col-xs-12 col-sm-6 ">	
	<h4 class="widget-title">Bank Detail</h4>
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Bank Name</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="bank_name" value="<?php echo $data['bank_name']  ?>" placeholder="Bank Name"  type="text">
		</div>
	</div>
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Address</label>
		<div class="col-sm-8">			
			<textarea  class="input-sm col-sm-10" name="bank_address" ><?php echo $data['bank_address']  ?></textarea>
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Account Number</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" name="account_number" value="<?php echo $data['account_number']  ?>" placeholder="Account Number"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Branch</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="branch" value="<?php echo $data['branch']  ?>" placeholder="Branch"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Swift Code</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="swift_code" value="<?php echo $data['swift_code']  ?>" placeholder="Swift Code"  type="text">
		</div>
	</div>
</div>
<br>
<hr>
<div class="col-md-offset-5 col-md-6">
	<button class="btn btn-info" type="submit">
		<i class="ace-icon fa fa-check bigger-110"></i>
		Submit
	</button>

	&nbsp; &nbsp; &nbsp;
	<button class="btn reset" type="reset">
		<i class="ace-icon fa fa-undo bigger-110"></i>
		Reset
	</button>
</div>

</form>

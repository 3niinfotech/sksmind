<?php
//include('model.php');
//$model  = new model();
$entryno = $model->getIncrementEntry('job_no');
?>
<form class="form-horizontal" method="POST" role="form" action="<?php echo $jewelryUrl.'jobwork/jobworkController.php'; ?>">
<input type="hidden" name="fn" value="saveJob" />
<?php 
	if(isset($_GET['id']))
	{ ?>
		<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
<?php 
$entryno = $data['entryno'];
} 
?>
<div class="col-xs-12 col-sm-6 ">
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Entry Number</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="entryno" name="entryno" placeholder="Entry" readonly value="<?php echo $entryno; ?>" type="text">
		</div>
	</div>

	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Company</label>
		<div class="col-sm-8">			
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
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Date</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="date" value="<?php echo $data['date']?>" name="date" placeholder="" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Job Type</label>
		<div class="col-sm-8">
			<select class="col-xs-10" id="ledger" name="job">
				<option value="">Select Type</option>
				<option value="jewelry" <?php echo ($data['job'] == "jewelry")?"selected":""; ?> >jewelry</option>
				<option value="collet" <?php echo ($data['job'] == "collet")?"selected":""; ?> >collet</option>
			
			</select>
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Narretion</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="narretion" value="<?php echo $data['narretion']  ?>" placeholder="narretion"  type="text">
		</div>
	</div>
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
</div>
</form>		
<?php
session_start();

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('party',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}

include_once($daiDir.'Helper.php');
include_once($daiDir.'jewelry/party/partyModel.php');

$helper  = new Helper($cn);

$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$data =  $pmodel->getData(0);
?>
<br><br>
<form class="form-horizontal" id="party-form" method="POST" role="form" action="<?php echo $moduleUrl.'party/partyController.php'?>">
<input type="hidden" name="fn" value="ajaxsave" />
<input type="hidden" name="type" value="inventory" />
<?php 
	if(isset($_GET['id']))
	{ ?>
		<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
<?php } ?>
<div class="col-xs-12 col-sm-10 ">
	
	<h4 class="widget-title">Party Detail</h4>
	<div class="form-group col-sm-6">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Party Name</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="name" name="name" value="<?php echo $data['name']  ?>"  placeholder="Company Name"  type="text">
		</div>
	</div>

	<div class="form-group col-sm-6">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Address</label>
		<div class="col-sm-8">			
			<textarea  class="input-sm col-sm-10" name="address" ><?php echo $data['address']  ?></textarea>
		</div>
	</div>
	
	<div class="form-group col-sm-6">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Country</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="country" name="country" value="<?php echo $data['country']  ?>" placeholder="Country"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-6">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Pincode</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="pincode" value="<?php echo $data['pincode']  ?>" placeholder="Pincode"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-6">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Email Id</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="email" value="<?php echo $data['email']  ?>" placeholder="Email Id"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-6">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Contact Number</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="contact_number" value="<?php echo $data['contact_number']  ?>" placeholder="Contact Number"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-6">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Fax</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="fax" value="<?php echo $data['fax']  ?>" placeholder="Fax"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-6">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Contact Person</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="contact_person" value="<?php echo $data['contact_person']  ?>" placeholder="Contact Person"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-6">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Website</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10"  name="website" value="<?php echo $data['website']  ?>" placeholder="Website"  type="text">
		</div>
	</div>
</div>

<div style="clear:both"></div>
<br><br>
<hr>
<div class="col-md-offset-4 col-md-6">
	<button class="btn btn-info" type="button" onClick="SaveParty()">
		<i class="ace-icon fa fa-check bigger-110"></i>
		Submit
	</button>

	&nbsp; &nbsp; &nbsp;
	<button class="btn reset" type="reset">
		<i class="ace-icon fa fa-undo bigger-110"></i>
		Reset
	</button>
	&nbsp; &nbsp; &nbsp;
	<button class="btn btn-danger" type="button" onClick="closePartyPopup()">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
</div>

</form>
<script>
function SaveParty()
{
	
	jQuery('#please-wait').show();
	var data = jQuery('#party-form').serialize();
	jQuery.ajax({
	url: '<?php echo $jewelryUrl.'party/partyController.php'?>', 
	type: 'POST',
	data: data,		
	success: function(result)
	{		
		jQuery('#please-wait').hide();
		
		
		var obj = jQuery.parseJSON(result);
		alert(obj.message);
		if(obj.status)
		{
			partyOption();
			jQuery('#dialog-box-container1').hide();
		}
	}
	});
	
}
function partyOption()
{
	
	jQuery('#please-wait').show();
	var data = {'id':1};
	jQuery.ajax({
	url: '<?php echo $jewelryUrl.'party/partyOption.php'?>', 
	type: 'POST',
	data: data,		
	success: function(result)
	{		
		jQuery('#please-wait').hide();
		jQuery('#ledger').html(result);
	}
	});
	
}
</script>

<?php 
session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
 
include_once($daiDir.'Helper.php');
$helper  = new Helper($cn);

$pid = $_POST['ids'];

?>

<div class="page-header">							
	<h1 style="float:left">
		Send Mail to Customer						
	</h1>
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>	
</div>

<form class="form-horizontal" id="mail-form" method="POST" role="form" enctype="multipart/form-data" action="">
<input type="hidden" name="fn" value="mailTo" />
<input type="hidden" name="exportProducts"  value="<?php echo implode(",",$pid)?>">
<div class="col-xs-12 col-sm-12 " style="margin-left:10%;">
	<div class="form-group col-sm-12">
		<label class="col-sm-12 control-label no-padding-right" style="text-align:left;font-weight:bold;" for="form-field-4">To Email Address <span class="required">*</span></label>
		<div class="col-sm-5">
			<input class="input-sm col-sm-10" id="percent" value="" name="email" placeholder="Enter Receipt Email" type="text">
		</div>
	</div>
	
	<div style="clear:both"></div>	
	<div class="form-group col-sm-12">
		<label class="col-sm-12 control-label no-padding-right" style="text-align:left;font-weight:bold;" for="form-field-4">Subject</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="percent" name='subject' value="Stone Proposal" placeholder="Enter Receipt Email" type="text">
		</div>
	</div>
	<div style="clear:both"></div>	
	<div class="form-group col-sm-12">
		<label class="col-sm-12 control-label no-padding-right"  style="text-align:left;font-weight:bold;" for="form-field-4">Mail Content</label>
		<div class="col-sm-8">
			<textarea class="input-sm col-sm-10" placeholder="Your Content" name="content" rows="10" >Heres your information for required stone. Review your required stone and get started using our stone. </textarea>
		</div>
	</div>
</div>

<div class="col-xs-12 col-sm-12 " >
	
<div class="clearfix form-actions" >
	<div class="col-md-4" style="margin-left: 40%;">
		<button class="btn btn-info" type="button" onClick="sendMail()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Send Mail
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

<script type="text/javascript">
			
function sendMail()
{

	var data =  $("#mail-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $daiUrl.'/module/inventory/inventoryController.php'; ?>', 
		type: 'POST',
		data: data,		
		success: function(result)
			{		
				if(result != "")
				{
					jQuery('#please-wait').hide();
					var obj = jQuery.parseJSON(result);
					alert(obj.message);
					if(obj.status)
					{
						//loadGrid();
					}
				}
			}
	});	
}
</script>			
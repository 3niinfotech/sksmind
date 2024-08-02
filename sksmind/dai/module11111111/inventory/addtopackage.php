<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
 
include_once($daiDir.'module/outward/outwardModel.php');
include_once($daiDir.'Helper.php');
include_once($daiDir.'module/party/partyModel.php');

include_once('inventoryModel.php');
$imodel  = new inventoryModel();

$model  = new outwardModel();
$helper  = new Helper();
$attribute = $helper->getAttribute();
$width50 = $helper->width50(); 							
$groupUrl = $daiDir.'module/outward/';
$pmodel  = new partyModel();
$party = $pmodel->getOptionList();
$lid=0;
if(isset($_POST['id']))
{
	$lid = $_POST['id'];
}
$data =  $model->getData($lid);		

?>

<div class="page-header">							
	<h1 style="float:left">
		Change Package OR Add To Package						
	</h1>
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>	
</div>

<form class="form-horizontal" id="memo-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $moduleUrl.'inward/inwardController.php'?>">
<input type="hidden" name="fn" value="savePackage" />
<div class="col-xs-12 col-sm-12 ">
	<div class="form-group col-sm-5">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Add to Package</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="percent" value="" placeholder="Enter Package Name" onKeyUp="jQuery('.addpackage').val(this.value.toUpperCase())" style="text-transform:uppercase" type="text">
		</div>
	</div>
	<div style="clear:both"></div>	
</div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4"></label>
	</div>
</div>
<div class="form-group col-sm-1">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Sr.No.</label>
	</div>
</div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Mfg. Code</label>
	</div>
</div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Sku</label>
	</div>
</div>
<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Package</label>
	</div>
</div>
<div style="clear:both"></div>
<hr>
<div class="changeform">
	<?php 
	
	$i=1;
	$pcs = $carat = $price = $amount = 0.0;
	$products = array();
	if(isset($_POST['id']))
	{
		$products = explode(",",$data['products']);
	}
	else
	{
		$products = $_POST['ids'];
	}
	
	foreach($products  as $id): 
	
	$value = $imodel->getDetail($id);
	
	?>
	
	<div class="form-group col-sm-2">		
		<div class="col-sm-12">			
		</div>
	</div>
	<div class="form-group col-sm-1">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4"><?php echo $i ?></label>
	</div>
	</div>
	
	<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12" disabled value="<?php echo $value['mfg_code'].'-'.$value['diamond_no']?>" placeholder="Your Terms" type="text">
		</div>
	</div>
	<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12" disabled value="<?php echo $value['sku']?>" type="text">
		</div>
	</div>	
	
	<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12 addpackage" name="product[<?php echo $id?>]" value="<?php echo $value['package']?>" placeholder="New Package" type="text">
		</div>
	</div>
	
	<div style="clear:both"></div>

	<?php $i++;
	endforeach;?>
</div>	


<div class="col-xs-12 col-sm-12 " >
	
<div class="clearfix form-actions" >
	<div class="col-md-4" style="margin-left: 40%;">
		<button class="btn btn-info" type="button" onClick="savePackage()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Save Package
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
			
function savePackage()
{

	var data =  $("#memo-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $moduleUrl.'inward/inwardController.php'?>', 
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
						loadGrid();
					}
				}
			}
	});	
}
</script>			
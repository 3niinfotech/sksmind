<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
 

include_once($daiDir.'jHelper.php');
include_once($daiDir.'jewelry/party/partyModel.php');

include_once('jewelryModel.php');
$imodel  = new jewelryModel($cn);


$helper  = new jHelper($cn);
						

$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$lid=0;
if(isset($_POST['id']))
{
	$lid = $_POST['id'];
}


?>

<div class="page-header">							
	<h1 style="float:left">
		Change Price						
	</h1>
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>	
</div>

<form class="form-horizontal" id="memo-form" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $jewelryUrl.'inward/inwardController.php'?>">
<input type="hidden" name="fn" value="updatePrice" />

<div class="col-xs-12 col-sm-12 ">
	
	<div class="form-group col-sm-5">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Update Price %</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="percent" value="" placeholder="Update Price %" onKeyUp="changeAllPrice(this.value)" type="text">
		</div>
	</div>
	<div style="clear:both"></div>	
</div>

<div class="form-group col-sm-1">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Sr.No.</label>
	</div>
</div>

<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Sku</label>
	</div>
</div>

<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">Price</label>
	</div>
</div>

<div class="form-group col-sm-2">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4">New Price</label>
	</div>
</div>
<div style="clear:both"></div>
<hr>
<div class="changeform">
	<?php 
	
	$i=1;
	$pcs = $carat = $price = $amount = 0.0;
	$products = array();
	$products = $_POST['ids'];
	
	foreach($products  as $id): 
	
	$value = $imodel->getData($id);
	
	?>
	
	
	<div class="form-group col-sm-1">		
	<div class="col-sm-12">
		<label class="col-sm-8 control-label no-padding-right" for="form-field-4"><?php echo $i ?></label>
	</div>
	</div>
	
	
	<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12" style="color:#000 !important" disabled value="<?php echo $value['sku']?>" placeholder="New Sku" type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12 oldprice"  style="color:#000 !important" disabled value="<?php echo $value['selling_price']?>"  type="text">
		</div>
	</div>
	
	
	<div class="form-group col-sm-2">		
		<div class="col-sm-12">
			<input class="input-sm col-sm-12" style="color:#000 !important" id="newprice-<?php echo $i;?>"  value="" name="product[<?php echo $id?>][price]" placeholder="New Price" type="text">
		</div>
	</div>
	
	<div style="clear:both"></div>

	<?php $i++;
	endforeach;?>
</div>	


<div class="col-xs-12 col-sm-12 " >
	
<div class="clearfix form-actions" >
	<div class="col-md-3" style="margin-left: 41%;">
		<button class="btn btn-info" type="button" onClick="mchangePrice()" >
			<i class="ace-icon fa fa-check bigger-110"></i>
			Save Price
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
			
function mchangePrice()
{

	var data =  $("#memo-form").serialize();
		jQuery('#please-wait').show();
		jQuery.ajax({
		url: '<?php echo $jewelryUrl.'jewelry/jewelryController.php'?>', 
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
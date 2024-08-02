<?php
session_start();

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include("jewelryModel.php");
$model  = new jewelryModel();	
$data = $model->getAllData();


?>

<form id="jewelry-form" class="form-horizontal" method="POST" role="form" enctype="multipart/form-data" action="<?php echo $daiUrl.'/module/inventory/inventoryController.php'; ?>">
<?php foreach($data as $k=>$value): ?>
<div class="jewelry-block">
	<div class="button-group">
		
		<div class="cgrid-header green">
			<label class="pos-rel" style="float:left;margin-right:10px;">
				<input class="ace" name="sku" type="checkbox" value="<?php echo $value['id']?>" >
				<span class="lbl"></span>
			</label> 
			<div class="color-total"> <b>SKU :  &nbsp; <?php echo $value['sku']?></b></div>	
		</div>	
		
		<a onclick="deleteSale('<?php echo $moduleUrl.'jewelry/jewelryController.php?fn=delete&id='.$value['id']?>')" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-danger" type="button" style="float:right">
			<i class="ace-icon fa fa-print bigger-110"></i>
			Delete 
		</a>
		<a onclick="editSale(<?php echo $value['id']?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-success" type="button" style="float:right">
			<i class="ace-icon fa fa-print bigger-110"></i>
			Edit 
		</a>
		
	</div>
	<div class="jewelry-info">
		<table class="jewelry-table" border=1; cellspacing='0' cellpadding='0' >
			<tr class="jewelry-th">
				<th class="jewelry-col jewelry-col1">Jewelry Image</th>
				<th class="jewelry-col jewelry-col2">Gross CTS</th>
				<th class="jewelry-col jewelry-col3">Diamonds
					<table class="diamond-htable">
						<tr class="diamond-th">
							<th class="diamond-col dc1">SKU</th>
							<th class="diamond-col dc2">Report</th>
							<th class="diamond-col dc3">Color</th>
							<th class="diamond-col dc4">PCS</th>
							<th class="diamond-col dc5">CTS</th>						
							<th class="diamond-col dc6">Price</th>
							<th class="diamond-col dc7">Total</th>								
						</tr>
					</table>
				</th>
									
			</tr>
			
			<tr class="jewelry-tr">
				<td class="jewelry-col jewelry-col1"><img src="<?php echo $daiUrl;?>assets/images/avatars/sample.jpg" alt="" /></td>
				<td class="jewelry-col jewelry-col2"><?php echo $value['gross_cts']?></td>
				<td class="jewelry-col jewelry-col3" >
					<table class="diamond-btable">
						<tbody>
						<?php foreach($value['record'] as $key=>$record):?>
						<tr class="diamond-tr">
							<td class="diamond-col dc1"><?php echo $record['sku'] ?></td>
							<td class="diamond-col dc2"><?php echo $record['report'] ?></td>
							<td class="diamond-col dc3"><?php echo $record['color'] ?></td>
							<td class="diamond-col dc4 a-right"><?php echo $record['pcs'] ?></td>						
							<td class="diamond-col dc5 a-right"><?php echo $record['carat'] ?></td>	
							<td class="diamond-col dc6 a-right"><?php echo $record['price'] ?></td>			
							<td class="diamond-col dc7 a-right"><?php echo $record['total_amount'] ?></td>			
						</tr>
						<?php endforeach; ?>
						
						
						<tr class="diamond-tr">
							<td class="diamond-col dc1">&nbsp;</td>
							<td class="diamond-col dc2">&nbsp;</td>
							<td class="diamond-col dc3">&nbsp;</td>
							<td class="diamond-col dc4">&nbsp;</td>						
							<td class="diamond-col dc5">&nbsp;</td>	
							<td class="diamond-col dc6">&nbsp;</td>			
							<td class="diamond-col dc7">&nbsp;</td>			
						</tr>
						</tbody>
						<tfoot>
						<tr class="diamond-tr">
							<td class="diamond-col dc1"></td>
							<td class="diamond-col dc2" colspan='5' >Labour FOR JEWELLERY	</td>
							<td class="diamond-col dc7 a-right"><?php echo $value['labour_fee']?></td>			
						</tr>
						<tr class="diamond-tr">
							<td class="diamond-col dc1"></td>
							<td class="diamond-col dc2" colspan='3'><?php echo $value['gold_type']?></td>
												
							<td class="diamond-col dc5 a-right"><?php echo $value['gold_gram']?></td>	
							<td class="diamond-col dc6 a-right"><?php echo $value['gold_price']?></td>			
							<td class="diamond-col dc7 a-right"><?php echo $value['gold_amount']?></td>			
						</tr>
						<tr class="diamond-tr red">							
							<td class="diamond-col dc2 a-right"  colspan='6'><b>Total USD</b></td>												
							<td class="diamond-col dc7 a-right"><b><?php echo $value['cost_price']?></b></td>			
						</tr>
						<tr class="diamond-tr green">							
							<td class="diamond-col dc2 a-right"  colspan='5'><b>Final Cost</b></td>												
							<td class="diamond-col dc7 a-right"><b><?php echo $value['percentage']?>%</b></td>			
							<td class="diamond-col dc7 a-right"><b><?php echo $value['final_cost']?></b></td>			
						</tr>
						</tfoot>
					</table>
				</td>
									
			</tr>
			
		</table>
	</div>
</div>

<?php endforeach; ?>
</form>
<style>

</style>

<div class="dialog-box-container" id="dialog-box-container" style="display:none;" >
	<div class="box-container" style="width:1250px" >
	</div>
</div>	
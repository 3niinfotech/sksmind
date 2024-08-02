<?php
session_start();

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif;
include_once($daiDir.'jHelper.php');
$jhelper  = new jHelper($cn); 
include("jewelryModel.php");
$model  = new jewelryModel($cn);
$post=$_POST;	
$data = $model->getAllData($post);

?>
<div class="invenotry-cgrid">
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
		
		<!--<a onclick="deleteSale('<?php echo $jewelryUrl.'jewelry/addNew.php?id='.$value['id']?>')" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-danger" type="button" style="float:right">
			<i class="ace-icon fa fa-print bigger-110"></i>
			Delete 
		</a>-->
		<a onClick="location.href='<?php echo $jewelryUrl.'jewelry/addNew.php?id='.$value['id']?>'" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-success" type="button" style="float:right">
			<i class="ace-icon fa fa-pencil bigger-110"></i>
			Edit 
		</a>
		
		<!--<a onClick="tojobRepair(<?php echo $value['id']; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-success" type="button" style="float:right">
			<i class="ace-icon fa fa-pencil bigger-110"></i>
			Repair
		</a>-->
		
	</div>
	<div class="jewelry-info">
		<table class="jewelry-table" border=1; cellspacing='0' cellpadding='0' >
			<tr class="jewelry-th">
				<th class="jewelry-col jewelry-col1">Product Image</th>
				<th class="jewelry-col jewelry-col1">Name & Code</th>
				<th class="jewelry-col jewelry-col2">IGI No.</th>
				<th class="jewelry-col jewelry-col3">Diamonds
					<table class="diamond-htable">
						<tr class="diamond-th">
							<th class="diamond-col dc4">IGI No.</th>
							<th class="diamond-col dc4" style="width:12%">Lot No.</th>
							<th class="diamond-col dc4">Color</th>
							<th class="diamond-col dc4">Clarity</th>
							<th class="diamond-col dc4">PCS</th>
							<th class="diamond-col dc4">CTS</th>						
							<th class="diamond-col dc4">Price</th>
							<th class="diamond-col dc4">Total</th>				
						</tr>
					</table>
				</th>
									
			</tr>
			
			<tr class="jewelry-tr">
				<td class="jewelry-col jewelry-col1">
					<?php 
					$filename = "../../../pdf/file/jewels/".$value['sku'].".jpg";
					if(file_exists($filename)):
					?>
					<img src="<?php echo $filename; ?>" alt="" style="width:140px;height:140px"/>
					<?php else: ?>
					<img src="../../../pdf/file/jewels/logo-new.png" alt="" style="width:140px;height:140px"/>
					<?php endif; ?>
				</td>
				<td class="jewelry-col jewelry-col1" style="position:relative">
				<?php echo $value['name']?>
					<h6 class="jsku"><?php echo $value['sku']?></h6>
				</td>
				<td class="jewelry-col jewelry-col2" style="position:relative"><?php echo $value['igi_code'];?>
				 <h6 class="jsku"><?php echo $value['gross_weight']?></h6>
				</td>
				<td class="jewelry-col jewelry-col3" >
					<table class="diamond-btable">
						<tbody>
						<?php foreach($value['collet'] as $key=>$record):?>
						<tr class="diamond-tr">
							<td class="diamond-col dc4"><?php echo $record['report_no'] ?></td>
							<td class="diamond-col dc4" style="width:12%"><?php echo $record['sku'] ?></td>
							<td class="diamond-col dc4"><?php echo $record['color'] ?></td>
							<td class="diamond-col dc4"><?php echo $record['clarity'] ?></td>
							<td class="diamond-col dc4"><?php echo $record['pcs'] ?></td>						
							<td class="diamond-col dc4"><?php echo $record['carat'] ?></td>	
							<td class="diamond-col dc4"><?php echo $record['price'] ?></td>			
							<td class="diamond-col dc4"><?php echo $record['total_amount_cost'] ?></td>			
						</tr>
						<?php endforeach; ?>
						<?php foreach($value['main'] as $key=>$record):?>
						<tr class="diamond-tr">
							<td class="diamond-col dc4"><?php echo $record['report_no'] ?></td>
							<td class="diamond-col dc4" style="width:12%"><?php echo $record['sku'] ?></td>
							<td class="diamond-col dc4"><?php echo $record['color'] ?></td>
							<td class="diamond-col dc4"><?php echo $record['clarity'] ?></td>
							<td class="diamond-col dc4"><?php echo $record['pcs'] ?></td>						
							<td class="diamond-col dc4"><?php echo $record['carat'] ?></td>	
							<td class="diamond-col dc4"><?php echo $record['price'] ?></td>			
							<td class="diamond-col dc4"><?php echo $record['amount'] ?></td>			
						</tr>
						<?php endforeach; ?>
						<?php foreach($value['side'] as $key=>$record):
							if($record['outward_parent'] == 0)
							{
								$sku = $record['sku'];
							}
							else
							{
								$parentData = $jhelper->getSideProductDetail($record['outward_parent']);
								if($record['color'] == '')
								$record['color'] = $parentData['color'];
								if($record['clarity'] == '')
								$record['clarity'] = $parentData['clarity'];		
								$sku = $parentData['sku'];
							}
						?>
						<tr class="diamond-tr">
							<td class="diamond-col dc4"><?php echo $record['report_no'] ?></td>
							<td class="diamond-col dc4" style="width:12%"><?php echo $sku; ?></td>
							<td class="diamond-col dc4"><?php echo $record['color'] ?></td>
							<td class="diamond-col dc4"><?php echo $record['clarity'] ?></td>
							<td class="diamond-col dc4"><?php echo $record['pcs'] ?></td>						
							<td class="diamond-col dc4"><?php echo $record['carat'] ?></td>	
							<td class="diamond-col dc4"><?php echo $record['price'] ?></td>			
							<td class="diamond-col dc4"><?php echo $record['amount'] ?></td>				
						</tr>
						<?php endforeach; ?>
						<?php if($value['side_carat'] != 0 || $value['side_carat'] != 0.00):?>
						<tr class="diamond-tr">
							<td class="diamond-col dc4"></td>
							<td class="diamond-col dc4"><?php echo "Extra" ?></td>
							<td class="diamond-col dc4"></td>
							<td class="diamond-col dc4"></td>
							<td class="diamond-col dc4"><?php echo $value['side_pcs'] ?></td>						
							<td class="diamond-col dc4"><?php echo $value['side_carat'] ?></td>	
							<td class="diamond-col dc4"><?php echo $value['side_price'] ?></td>			
							<td class="diamond-col dc4"><?php echo $value['side_amount'] ?></td>			
						</tr>
						<?php endif; ?>
							<tr class="diamond-tr">
								<td class="diamond-col dc4" colspan="7">Other Exp.</td>
								<td class="diamond-col dc4"><?php echo $value['other_amount'] ?></td>			
							</tr>
							<tr class="diamond-tr">
								<td class="diamond-col dc4" colspan="7">Labour For Jewellery</td>
								<td class="diamond-col dc4"><?php echo $value['labour_amount'] ?></td>			
							</tr>
							<tr class="diamond-tr">
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4">IGI Exp.</td>					
								<td class="diamond-col dc4"></td>	
								<td class="diamond-col dc4"></td>			
								<td class="diamond-col dc4"><?php echo $value['lab_fee'] ?></td>			
							</tr>
							<tr class="diamond-tr">
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"><?php echo (isset($value['gold']))?$value['gold']."K Gold":'' ?></td>					
								<td class="diamond-col dc4"><?php echo $value['net_weight'] ?></td>	
								<td class="diamond-col dc4"><?php echo $value['rate'] ?></td>			
								<td class="diamond-col dc4"><?php echo $value['amount'] ?></td>			
							</tr>
							<tr class="diamond-tr">
								<td class="diamond-col dc4">&nbsp;</td>
								<td class="diamond-col dc4">&nbsp;</td>
								<td class="diamond-col dc4">&nbsp;</td>
								<td class="diamond-col dc4">&nbsp;</td>
								<td class="diamond-col dc4">&nbsp;</td>							
								<td class="diamond-col dc4 gray" colspan="2" rowspan="2">TOTAL USD</td>	
								<td class="diamond-col dc4 gray" rowspan="2"><?php echo round($value['total_amount']) ?></td>			
							</tr>
							<tr class="diamond-tr">
								<td class="diamond-col dc4">&nbsp;</td>
								<td class="diamond-col dc4">&nbsp;</td>
								<td class="diamond-col dc4">&nbsp;</td>
								<td class="diamond-col dc4">&nbsp;</td>
								<td class="diamond-col dc4">&nbsp;</td>						
							</tr>
							<tr class="diamond-tr">
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>					
								<td colspan="2" class="diamond-col dc4 gray">Sell Price</td>			
								<td class="diamond-col dc4"><?php echo round($value['sell_price']) ?></td>	
							</tr>
							<tr class="diamond-tr">
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>
								<td class="diamond-col dc4"></td>					
								<td colspan="2" class="diamond-col dc4 gray">Asking Price</td>			
								<td class="diamond-col dc4"><?php echo round($value['selling_price']) ?></td>	
							</tr>
						</tbody>
					</table>
				</td>
									
			</tr>
			
		</table>
	</div>
</div>

<?php endforeach; ?>
</form>
</div>
<style>
.diamond-col.dc4.gray {
	background: #999;
	color: #fff;
	font-weight: bold;
}
.jsku {
	position: absolute;
	width: 100%;
	background: #092648;
	color: #fff;
	padding: 18px;
	left: 0;
	margin: 0;
	bottom: 0;
	font-weight: bold;
}
</style>

<div class="dialog-box-container" id="dialog-box-container" style="display:none;" >
	<div class="box-container" style="width:1250px" >
	</div>
</div>	
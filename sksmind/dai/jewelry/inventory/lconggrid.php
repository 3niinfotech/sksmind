<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');
include_once($daiDir.'jewelry/party/partyModel.php');
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$attribute = $helper->getAttribute();							
$width50 = $helper->width50();
//$memo = $helper->getInventory('rmemo');
if(isset($_POST['id'])) 
{	
	$pid = $_POST['id'];
}
else
{
	$pid =0 ;
}
$data = $jhelper->getAllLooseSale($pid,'consign');
//echo"<pre>";
//print_r($data);
//echo"</pre>";

$i=1;
$jewType = $jhelper->getJewelryType();	
$design = $jhelper->getAllDesign();
?>
<?php if(count($data)): ?>
<?php foreach($data as $k=>$memo): ?>

<form class="form-horizontal" method="POST" role="form" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="sendToLab" />
<div class="button-group">
	<div class="cgrid-header green">
		<div class="color-total"> <b>Invoice :</b> <?php echo $memo['invoiceno'] ?>  &nbsp;&nbsp; <span class="blue" ><b><?php echo isset($party[$memo['party']])?$party[$memo['party']]:''; ?></b></span> &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Date :</b> &nbsp;&nbsp;<?php $phpdate = strtotime( $memo['date'] ); echo  date( 'd-m-Y', $phpdate );?> &nbsp;&nbsp; |</div>		
		<div class="color-total"> <b>Selected :</b> &nbsp;&nbsp;<span id="selected-row-<?php echo $k ?>">0</span> &nbsp;&nbsp; |</div>
		
	</div>	
	
	<a onclick="deleteSale(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-danger" type="button" style="float:right">
		<i class="ace-icon fa fa-trash bigger-110"></i>
		Delete
	</a>
	<a onclick="editSale(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-success" type="button" style="float:right">
		<i class="ace-icon fa fa-pencil bigger-110"></i>
		Edit 
	</a>
	
	<button id="return-<?php echo $k ?>"  disabled class="btn grid-btn btn-sm btn-success" type="button" onClick="returnMemo(<?php echo $k ?>,<?php echo $pid; ?>)" style="float:right">
		<i class="ace-icon fa fa-reply bigger-110"></i>
		Return
	</button>
	
	<button id="sale-<?php echo $k ?>" disabled class="btn grid-btn btn-sm btn-success" type="button" onClick="memoToSale(<?php echo $k;?>,<?php echo $pid; ?>);" style="float:right">
		<i class="ace-icon fa fa-tag bigger-110"></i>
		Sale
	</button>
	<a href="<?php echo $mainUrl.'pdf/file/invoice.php?id='.$k ?>" target="_blank" id="invoice-<?php echo $k ?>" class="btn grid-btn btn-sm btn-info" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Print
	</a>	
	
</div>

	<div class="subform invenotry-cgrid mform-<?php echo $k?>">
		<div class="divTable" style="width:100%;">
			<div class="divTableHeading">
				<div class="divTableCell">
					<label class="pos-rel">
						<input class="ace" onclick="allCheck(this,<?php echo $k?>)" type="checkbox">
						<span class="lbl"></span>
					</label>
				</div>	
				<div onClick="sortForFilter('sku')" style="width:100px !important" class="divTableCell">Type</div>
				<div class="divTableCell">SKU</div>
				<div class="divTableCell">PCS</div>
				<div class="divTableCell">Carat</div>
				<div class="divTableCell">Price</div>
				<div  class="divTableCell">Amount</div>
				<div  class="divTableCell">Lab</div>
				<div  class="divTableCell">IGI Code</div>
				<div  class="divTableCell">IGI Color</div>
				<div  class="divTableCell">IGI Clarity</div>
				<div  class="divTableCell">IGI Amount</div>					
			</div>	
			<div class="outward divTableBody">
				<?php 
				
				$pcs = $carat = $price = $amount = 0.0; 
				if(isset($memo['collet_stone'])):
				foreach($memo['collet_stone'] as $jData):	
				
				$pcs = $pcs + $jData['pcs'];
				$carat = $carat + $jData['carat'];
				$price += (float)($jData['sell_price'] == 0)?$jData['price']:$jData['sell_price'];
				$amount += (float)$jData['sell_amount']; 

				$class="";
				$sku = $jData['sku'];
				
				?>
				
				<div class="divTableRow <?php echo $class;?>">
				<div class="divTableCell">
						<label class="pos-rel">
							<input name="collet_stone" value="<?php echo $jData['id']?>" class="ace" onclick="totalSelected(this,<?php echo $k; ?>)" type="checkbox">
							<span class="lbl"></span>
						</label>
				</div>
				<div class="divTableCell" style="width:100px !important"><?php echo "Collet"?></div>
				<div class="divTableCell"><?php echo $sku;?></div>
				<div class="divTableCell  a-right"><?php echo $jData['pcs'];?></div>
				<div class="divTableCell  a-right"><?php echo $jData['carat'];?></div>			
				<div class="divTableCell  a-right"><?php echo ($jData['sell_price'] == 0)?$jData['price']:$jData['sell_price'];?></div>			
				<div class="divTableCell a-right" ><?php echo $jData['sell_amount'];?></div>	
				<div class="divTableCell  "><?php echo $jData['lab'];?></div>
				<div class="divTableCell  "><?php echo $jData['igi_code'];?></div>			
				<div class="divTableCell  "><?php echo $jData['igi_color'];?></div>			
				<div class="divTableCell a-right "><?php echo $jData['igi_clarity'];?></div>				<div class="divTableCell a-right "><?php echo $jData['igi_amount'];?></div>		
				</div>
				<?php endforeach;
				endif;
				?>
				<?php 
				if(isset($memo['main_stone'])):
				foreach($memo['main_stone'] as $jData):	
				
				$pcs = $pcs + $jData['pcs'];
				$carat = $carat + $jData['carat'];
				$price += (float)($jData['sell_price'] == 0)?$jData['price']:$jData['sell_price'];
				$amount += (float)($jData['sell_amount'] == 0)?$jData['amount']:$jData['sell_amount']; 

				$class="";
				$sku = $jData['sku'];
				
				?>
				
				<div class="divTableRow <?php echo $class;?>">
				<div class="divTableCell">
						<label class="pos-rel">
							<input name="main_stone" value="<?php echo $jData['id']?>" class="ace" onclick="totalSelected(this,<?php echo $k; ?>)" type="checkbox">
							<span class="lbl"></span>
						</label>
				</div>
				<div class="divTableCell" style="width:100px !important"><?php echo "Main"?></div>
				<div class="divTableCell"><?php echo $sku;?></div>
				<div class="divTableCell  a-right"><?php echo $jData['pcs'];?></div>
				<div class="divTableCell  a-right"><?php echo $jData['carat'];?></div>			
				<div class="divTableCell  a-right"><?php echo ($jData['sell_price'] == 0)?$jData['price']:$jData['sell_price'];?></div>			
				<div class="divTableCell a-right" ><?php echo ($jData['sell_amount'] == 0)?$jData['amount']:$jData['sell_amount'];?></div>	
				<div class="divTableCell  "><?php echo $jData['lab'];?></div>
				<div class="divTableCell  "><?php echo $jData['igi_code'];?></div>			
				<div class="divTableCell  "><?php echo $jData['igi_color'];?></div>			
				<div class="divTableCell a-right "><?php echo $jData['igi_clarity'];?></div>				<div class="divTableCell a-right "><?php echo $jData['igi_amount'];?></div>		
				</div>
				<?php endforeach;
				endif;
				?>
				<?php
				if(isset($memo['side_stone'])):
				foreach($memo['side_stone'] as $jData):	
				$pcs = $pcs + $jData['pcs'];
				$carat = $carat + $jData['carat'];
				$price += (float)($jData['sell_price'] == 0)?$jData['price']:$jData['sell_price'];
				$amount += (float)($jData['sell_amount'] == 0)?$jData['amount']:$jData['sell_amount']; 
				if($jData['outward_parent'] == 0)
				{
					$sku = $jData['sku'];
				}
				else
				{
					$parentData = $jhelper->getSideProductDetail($jData['outward_parent']);					
					$sku = $parentData['sku'];
				}
				?>
		<div class="divTableRow">	
		<div class="divTableCell">
			<label class="pos-rel">
				<input name="side_stone" value="<?php echo $jData['id']?>" class="ace" onclick="totalSelected(this,<?php echo $k; ?>)" type="checkbox">
				<span class="lbl"></span>
			</label>
		</div>
		<div class="divTableCell" style="width:100px !important"><?php echo "side"?></div>
		<div class="divTableCell  "><?php echo $sku;?></div>
		<div class="divTableCell  a-right"><?php echo $jData['pcs'];?></div>
		<div class="divTableCell  a-right"><?php echo $jData['carat'];?></div>			
		<div class="divTableCell  a-right"><?php echo ($jData['sell_price'] == 0)?$jData['price']:$jData['sell_price'];?></div>			
		<div class="divTableCell a-right" ><?php echo ($jData['sell_amount'] == 0)?$jData['amount']:$jData['sell_amount'];?></div>		
		<div class="divTableCell  "></div>
		<div class="divTableCell  "></div>			
		<div class="divTableCell  "></div>			
		<div class="divTableCell a-right "></div>						
		<div class="divTableCell a-right "></div>
	</div><?php
	endforeach;
	endif;
	?>
				<div class="divTableRow">
					<div class="divTableCell"></div>
					<div class="divTableCell" style="width:100px !important"> <b>Total : <?php //echo $memo['rows'] ?></b></div>
					<div class="divTableCell"> </div>
					<div class="divTableCell a-right"><b><?php echo number_format($pcs,2)?></b> </div>
					<div class="divTableCell a-right"><b><?php echo number_format($carat,2)?></b> </div>
					<div class="divTableCell "></div>
					<div class="divTableCell a-right"><b><?php echo number_format($amount,2)?></b></div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell a-right"> </div>
				</div>
			</div>
		</div>
		
	</div>

</form>
<div style="clear:both"></div>
<br><br>

<?php $i++; endforeach; ?>
<?php else: ?>
<h4 style="text-align:center">No Memo found for this Party.</h4>
<?php endif;?>
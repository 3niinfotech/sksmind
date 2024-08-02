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
$pid = $_POST['id'];
$data = $jhelper->getAllSale($pid,'consign');
//echo"<pre>";
//print_r($data);
//echo"</pre>";
if($pid =='')
	$pid =0 ;

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
	<a href="<?php echo $mainUrl.'pdf/file/jewsale.php?id='.$k ?>" target="_blank" id="invoice-<?php echo $k ?>" class="btn grid-btn btn-sm btn-info" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Print
	</a>	
	
</div>

	<div class="subform invenotry-cgrid mform-<?php echo $k?>">
		<div class="divTable" style="width:1780px">
			<div class="divTableHeading">
				<div class="divTableCell">
					<label class="pos-rel">
						<input class="ace" onclick="allCheck(this,<?php echo $k?>)" type="checkbox">
						<span class="lbl"></span>
					</label>
				</div>	
				<div class="divTableCell" style="width:100px !important">SKU</div>
				<div class="divTableCell">Design</div>
				<div class="divTableCell">Type</div>
				<div class="divTableCell width-50px" >Gold</div>
				<div class="divTableCell" >Gold Color</div>
				<div class="divTableCell" >Gross Weight</div>
				<div class="divTableCell" >Pg Weight</div>
				<div class="divTableCell" >Net Weight</div>			
				<div class="divTableCell" >Rate</div>
				<div class="divTableCell" >Amount</div>
				<div class="divTableCell" >Other Code</div>
				<div class="divTableCell " >Other Amount</div>
				<div class="divTableCell " >Labour Rate</div>
				<div class="divTableCell" >labour Amount</div>
				<div class="divTableCell" >Total Amount</div>	
				<div class="divTableCell">Sell Price</div>						
			</div>	
			<div class="outward divTableBody">
				<?php 
				
				$trp = $trc = $tpp = $tpc = $tc = $tsp = $ta = 0.0; 
				
				foreach($memo['products'] as $jData):	
				
				//$tpp += (float) $jData['pcs'];
				//$tpc += (float) $jData['carat'];
				//$tc += (float) $jData['cost'];
				//$tp += (float) $jData['price'];
				//$ta += (float) $jData['amount'];
				$tc += (float) $jData['gross_weight'];
				$tsp += (float) $jData['sell_price'];
				$ta += (float) $jData['total_amount'];
				
				$class="";
				$sku = $jData['sku'];
				
				?>
				
				<div class="divTableRow <?php echo $class;?>">	
					<div class="divTableCell">
						<label class="pos-rel">
							<input name="products" value="<?php echo $jData['id']?>" class="ace" onclick="totalSelected(<?php echo $k; ?>)" type="checkbox">
							<span class="lbl"></span>
						</label>
					</div>
					<div class="divTableCell" style="width:100px !important">  <?php echo $sku; ?> </div>
					<div class="divTableCell"><?php echo $jData['jew_design']?></div>
					<div class="divTableCell"><?php echo $jewType[$jData['jew_type']] ?></div>		
					<div class="divTableCell width-50px"><?php echo $jData['gold'] ?></div>			
					<div class="divTableCell"><?php echo $jData['gold_color'] ?></div>				
					<div class="divTableCell"><?php echo $jData['gross_weight'] ?></div>			
					<div class="divTableCell"><?php echo $jData['pg_weight'] ?></div>				
					<div class="divTableCell"><?php echo $jData['net_weight'] ?></div>				
					<div class="divTableCell"><?php echo $jData['rate'] ?></div>				
					<div class="divTableCell"><?php echo $jData['amount'] ?></div>			
					<div class="divTableCell"><?php echo $jData['other_code'] ?></div>		
					<div class="divTableCell"><?php echo $jData['other_amount'] ?></div>		
					<div class="divTableCell"><?php echo $jData['labour_rate'] ?></div>		
					<div class="divTableCell"><?php echo $jData['labour_amount'] ?></div>	
					<div class="divTableCell"><?php echo $jData['total_amount'] ?></div>			
					<div class="divTableCell"><?php echo $jData['sell_price']?></div>		
				</div>
				<?php endforeach;?>
				
				<div class="divTableRow">
					<div class="divTableCell"></div>
					<div class="divTableCell" style="width:100px !important"> <b>Total : <?php echo count($memo['products']) ?></b></div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell width-50px"> </div>
					<div class="divTableCell "> </div>
					<div class="divTableCell a-right"><b><?php echo number_format($tc,3)?></b> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell a-right"><b><?php echo number_format($ta,2)?></b> </div>
					<div class="divTableCell a-right"><b><?php echo number_format($tsp,2)?></b> </div>	
					
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
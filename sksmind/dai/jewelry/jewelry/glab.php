<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'jewelry/party/partyModel.php');
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
include_once($daiDir.'jHelper.php');
include_once($daiDir.'Helper.php');
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$attribute = $helper->getAttribute();	
$width50 = $helper->width50(); 						
//$memo = $helper->getInventory('rmemo'); 
$jewType = $jhelper->getJewelryType();
$pid = $_POST['party'];
$data = $jhelper->getJewelryLabData($_POST);
if($pid =='')
	$pid =0 ;
$i=1;
?>
<?php if(count($data)): ?>
<?php foreach($data as $k=>$memo): ?>

<form class="form-horizontal" method="POST" role="form" action="<?php echo $moduleUrl.'jewlery/jewelryController.php'?>">
<input type="hidden" name="fn" value='sendToLab' />
<div class="button-group">
<div class="cgrid-header green">
		<div class="color-total"> <b><?php echo $i; ?>.</b> &nbsp; (<?php echo $memo['entryno'] ?>) <?php echo isset($party[$memo['party']])?$party[$memo['party']]:''; ?> &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Invoice :</b> &nbsp;<?php echo $memo['invoiceno'] ?>&nbsp; |</div>
		<div class="color-total"> <b>Reference :</b> &nbsp;&nbsp;<?php echo $memo['reference'] ?>&nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Date :</b> &nbsp;&nbsp;<?php $phpdate = strtotime( $memo['date'] ); echo  date( 'd-m-Y', $phpdate );?> &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Record :</b> &nbsp;&nbsp;<?php echo count($memo['products']) ?> &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Selected :</b> &nbsp;&nbsp;<span id="selected-row-<?php echo $k ?>">0</span> &nbsp;&nbsp; |</div>
	</div>
	
	
	<!-- <button class="btn grid-btn btn-sm btn-danger" type="button" onClick="closeMemo(<?php echo $k ?>)" style="float:right">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close GIA
	</button> -->
	<a onclick="deleteSale(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-danger" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Delete
	</a>
	<a onclick="editSale(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-success" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Edit
	</a>
	
	<button id="return-<?php echo $k ?>" disabled class="btn grid-btn btn-sm btn-success" type="button" onClick="returnMemo(<?php echo $k ?>)" style="float:right">
		<i class="ace-icon fa fa-reply bigger-110"></i>
		Return
	</button>
	
	<!-- <button id="invoice-<?php echo $k ?>" class="btn grid-btn btn-sm btn-info" type="button" onClick="closeMemo(<?php echo $k ?>)" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Invoice
	</button> -->
</div>

	<div class="subform invenotry-cgrid mform-<?php echo $k?>">
		<div class="divTable" >
			<div class="divTableHeading">
				
				<div class="divTableCell">
					<label class="pos-rel">
					<input class="ace" onclick="allCheck(this,<?php echo $k?>)" type="checkbox">
					<span class="lbl"></span>
					</label>
				</div>				
				<div class="divTableCell" >Jew Code</div>
				<div class="divTableCell" >Design</div>
				<div class="divTableCell" >Type</div>			
				<div class="divTableCell width-50px" >Gold</div>
				<div class="divTableCell" >Gold Color</div>
				<div class="divTableCell" >Gross Weight</div>
				<div class="divTableCell" >Pg Weight</div>
				<div class="divTableCell" >Net Weight</div>			
				<div class="divTableCell" >Gold Rate</div>
				<div class="divTableCell" >Gold Amount</div>
				<div class="divTableCell" >Total Amount</div>
				<div class="divTableCell" >Final Amount</div>
				
			</div>	
			<div class="outward divTableBody">
				<?php 
				$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = 0.0; 
				
				foreach($memo['products'] as $jData):	
				
				?>
				
				<div class="divTableRow <?php //echo $class;?>">					
					<div class="divTableCell">
					<label class="pos-rel">
						<input name="products" value="<?php echo $jData['id']?>" class="ace" onclick="totalSelected(this,<?php echo $k; ?>)" type="checkbox">
						<span class="lbl"></span>
					</label>
					</div>			
					<div class="divTableCell"><?php echo $jData['sku'] ?></div>				
					<div class="divTableCell"><?php echo $jData['jew_design'] ?></div>			
					<div class="divTableCell"><?php echo $jewType[$jData['jew_type']] ?></div>		
					<div class="divTableCell width-50px"><?php echo $jData['gold'] ?></div>				
					<div class="divTableCell"><?php echo $jData['gold_color'] ?></div>				
					<div class="divTableCell"><?php echo $jData['gross_weight'] ?></div>
					<div class="divTableCell"><?php echo $jData['pg_weight'] ?></div>				
					<div class="divTableCell"><?php echo $jData['net_weight'] ?></div>				
					<div class="divTableCell"><?php echo $jData['rate'] ?></div>				
					<div class="divTableCell"><?php echo $jData['amount'] ?></div>
					<div class="divTableCell"><?php echo $jData['total_amount'] ?></div>				
					<div class="divTableCell"><?php echo $jData['selling_price'] ?></div>	
				</div>
				<?php endforeach;?>

				<div class="divTableRow">					
					<div class="divTableCell"></div>			
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell width-50px"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
				
				</div>						
			</div>
		</div>
	</div>

</form>
<div style="clear:both"></div>
<br><br>

<?php $i++; endforeach; ?>
<?php else: ?>
<h4 style="text-align:center">No Data found for this Party.</h4>
<?php endif;?>
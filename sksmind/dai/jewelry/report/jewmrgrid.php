<?php session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$attribute = $helper->getAttribute();							
$width50 = $helper->width50();
//$memo = $helper->getInventory('rmemo'); 


$data = $helper->getJewDetails($_POST['eid']);

$jewType = $jhelper->getJewelryType();
$design = $jhelper->getAllDesign();
include_once($daiDir.'jewelry/party/partyModel.php');
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();	
$i=1;
?>
<div class="page-header">	
	<h1 style="float:left">
		<?php echo $party[ $data['party']] ?>						
	</h1>	
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn reset" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>	
</div>
<?php if(count($data)): ?>

	<div class="alldata">
	
		<ul>
			<li><b>Date :</b><?php 
			$phpdate = strtotime( $data['date'] );
			echo date('d-m-Y',$phpdate);
			?>	</li>
			<li><b>Invoice No :</b><?php echo $data['invoiceno'] ?></li>
			<li><b>Narretion</b><?php echo $data['narretion'] ?></li>
		</ul>

	</div>

<div class="subform invenotry-cgrid ">
		<div class="divTable" >
			<div class="divTableHeading">			
				<div class="divTableCell">No</div>
				<div class="divTableCell">SKU</div>
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
		<div class="divTableBody" style="height:150px;">
		<?php 
		$trp = $tsp = $tnw = $tpw = $tc = $tp = $ta = 0.0; 
		$i=1;
		foreach($data['record'] as  $jData): 
		
		$tc += (float) $jData['gross_weight'];
		$tpw += (float) $jData['pg_weight'];
		$tnw += (float) $jData['net_weight'];
		$tsp += (float) $jData['sell_price'];
		$ta += (float) $jData['total_amount'];
			$class="";
			
			?>
			
			<div class="divTableRow <?php echo $class;?>">	
				<div class="divTableCell"><?php echo $i; ?></div>
				<div class="divTableCell">  <?php echo $jData['sku']?> </div>
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
		<?php $i++; endforeach; ?>
			<div class="divTableRow">				
					<div class="divTableCell"></div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell "> </div>
					<div class="divTableCell width-50px"> </div>
					<div class="divTableCell a-right"><b>Total</b></div>
					<div class="divTableCell a-right"><b><?php echo number_format($tc,3)?></b> </div>
					<div class="divTableCell a-right"><b><?php echo number_format($tpw,3)?></b> </div>
					<div class="divTableCell a-right"><b><?php echo number_format($tnw,3)?></b> </div>
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

<?php else: ?>
<h4 style="text-align:center">No Memo found for this Party.</h4>
<?php endif;?>
<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'Helper.php');
$helper  = new Helper();
$attribute = $helper->getAttribute();							
$width50 = $helper->width50();
//$memo = $helper->getInventory('rmemo'); 


$data = $helper->getInvetoryTransaction($_POST);

include_once($daiDir.'account/party/partyModel.php');
include_once($daiDir.'account/balance/balanceModel.php');
include_once($daiDir.'account/subgroup/subgroupModel.php');
$pmodel  = new partyModel();
$sgmodel  = new subgroupModel();
$Bmodel  = new balanceModel();

$party = $pmodel->getOptionList();	
$subgroup = $sgmodel->getOption();	
$i=1;
$cData = $Bmodel->getAllCurrency();
$book = $helper->getAllBook();
$currency = $helper->getAllCurrency();
$attribute = $helper->getAttribute();							
$width50 = $helper->width50();if($_POST['type'] == 'outward')
	$product = $helper->getOutwardDetails($_POST['eid']);else	$product = $helper->getInwardDetails($_POST['eid']);
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

	<div class="alldata">
		<ul>
			<li><b>Date :</b><?php echo $data['date'] ?>	</li>
			<li><b>Reference :</b><?php echo $data['reference'] ?></li>
			<li><b>Invoice No :</b><?php echo $data['invoiceno'] ?></li>
			<li><b>Entry No :</b><?php echo $data['entryno'] ?></li>
			<li><b>Invoice Date :</b>			<?php 				$phpdate = strtotime( $data['invoicedate'] );				echo  date( 'd-m-Y', $phpdate );			?></li>
			<li><b>Terms :</b><?php echo $data['terms'] ?></li>
			<li><b>Due Date:</b><?php  			if($data['terms'] =='' || $data['terms'] =='0'):				$phpdate = strtotime( $data['invoicedate'] );			else:				$phpdate = strtotime( $data['duedate'] );			endif;			echo  date( 'd-m-Y', $phpdate );						?></li>
			<li><b>Paid Amount:</b><?php echo $data['paid_amount'] ?></li>
			<li><b>Due Amount:</b><?php echo $data['due_amount'] ?></li>			
			<li><b>Narration</b><?php echo $data['narretion'] ?></li>
		</ul>
	</div>

<div style="clear:both"></div>
<hr>

<p class="blue">
&nbsp; &nbsp; <b>Paid Amount : </b> &nbsp; <?php echo $data['paid_amount'] ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Due Amount  : </b> &nbsp; <?php echo $data['due_amount'] ?>
</p>
<?php if(count($product['record'])): ?>
<div class="subform invenotry-cgrid ">
		<div class="divTable" >
			<div class="divTableHeading">			
				<div class="divTableCell">No</div>
				<div class="divTableCell">SKU</div>
				<div class="divTableCell width-50px">R.Pcs</div>
				<div class="divTableCell width-50px">R.Carat</div>		
				<div class="divTableCell width-50px">P.Pcs</div>
				<div class="divTableCell width-50px">P.Carat</div>
				<div class="divTableCell width-70px">Cost</div>		
				<div class="divTableCell width-70px">Price</div>
				<div class="divTableCell width-70px">Amount</div>
				<div class="divTableCell width-50px">LOC </div>
				<div class="divTableCell">Remark</div>
				<div class="divTableCell width-50px">Lab</div> 
				<?php foreach($attribute as $key=>$v): ?>
				<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $v; ?></div>
				<?php endforeach;?>
			</div>	
		<div class="divTableBody" style="height:120px;">
		<?php 
		$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = 0.0; 
		$i=1;
		foreach($product['record'] as  $jData): 
		
			$trp += (float) $jData['rought_pcs'];
			$trc += (float) $jData['rought_carat'];
			$tpp += (float) $jData['polish_pcs'];
			$tpc += (float) $jData['polish_carat'];
			$tc += (float) $jData['cost'];
			$tp += (float) $jData['price'];
			$ta += (float) $jData['amount'];
			
			$class="";
			
			?>
			
			<div class="divTableRow <?php echo $class;?>">	
				<div class="divTableCell"><?php echo $i; ?></div>
				<div class="divTableCell">  <?php echo $jData['sku']?> </div>
				<div class="divTableCell a-right width-50px">  <?php echo $jData['rought_pcs']?></div>
				<div class="divTableCell a-right width-50px">  <?php echo $jData['rought_carat']?> </div>
				<div class="divTableCell a-right width-50px">  <?php echo $jData['polish_pcs']?> </div>
				<div class="divTableCell a-right width-50px">  <?php echo $jData['polish_carat']?>  </div>
				<div class="divTableCell a-right width-70px">  <?php echo $jData['cost']?> </div>
				<div class="divTableCell a-right width-70px">  <?php echo $jData['price']?></div>
				<div class="divTableCell a-right width-70px">  <?php echo $jData['amount']?></div>
				<div class="divTableCell width-50px">  <?php echo $jData['location']?> </div>
				<div class="divTableCell">  <?php echo $jData['remark']?> </div>
				<div class="divTableCell width-50px">  <?php echo $jData['lab']?> </div>
				<?php foreach($attribute as $key=>$v): ?>
				<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo (isset($jData[$key]))?$jData[$key]:'&nbsp;';?></div>
				<?php endforeach;?>
			</div>				
		<?php $i++; endforeach; ?>
			<div class="divTableRow">				
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell a-right width-50px"> <b> <?php echo $trp ?></b></div>
					<div class="divTableCell a-right width-50px">  <b><?php echo $trc ?></b> </div>
					<div class="divTableCell a-right width-50px">  <b><?php echo $tpp?></b> </div>
					<div class="divTableCell a-right width-50px"> <b> <?php echo $tpc?></b>  </div>
					<div class="divTableCell a-right width-70px"> <b> <?php echo $tc?></b> </div>
					<div class="divTableCell a-right width-70px">  <b><?php echo $tp?></b></div>
					<div class="divTableCell a-right width-70px">  <b><?php echo $ta?></b></div>
					<div class="divTableCell width-50px">  </div>
					<div class="divTableCell">  </div>
					<div class="divTableCell width-50px">  </div>	
					<?php foreach($attribute as $key=>$v): ?>
					<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"></div>
					<?php endforeach;?>	
				</div>
			</div>
		</div>
	</div>

<?php else: ?>
<h4 style="text-align:center">No Data found for this Party.</h4>
<?php endif;?>


<?php if(count($data['record'])): ?>
<br style="clear:both">
<p class="blue">
&nbsp; &nbsp; <b>Paid Transaction : </b> 
</p>
	<div class="subform invenotry-cgrid " style="overflow-x: auto;" >
		<div class="divTable" style="width:1060px">
			<div class="divTableHeading">				
				<div class="divTableCell">No</div>								
				<div class="divTableCell" style="width:80px;text-align:center;">Date</div>
				<div class="divTableCell" style="width:210px">Account</div>
				<div class="divTableCell" style="width:210px">Party</div>				
				<div class="divTableCell" style="width:430px">Description</div>
				<div class="divTableCell " style="width:80px;text-align:center;">Amount</div>				
			</div>	
			<div class="divTableBody" style="overflow-y: auto;height:70px;">
			<?php 
				$tcr = $tdr = 0.0; 
				$i=1;
				foreach($data['record'] as $jData):
				$tcr += (float)$jData['amount'];				
			?>
				
				<div class="divTableRow ">					
					<div class="divTableCell"><?php echo $i; ?></div>		
					<div class="divTableCell" style="width:80px"><?php //echo $jData['date']; 					$phpdate = strtotime( $jData['date'] );				echo  date( 'd-m-Y', $phpdate );					?></div>		
					<div class="divTableCell" style="width:210px"><?php echo isset($subgroup[$jData['under_subgroup']])?$subgroup[$jData['under_subgroup']]:"";?></div>		
					<div class="divTableCell" style="width:210px"><?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>					
					<div class="divTableCell" style="width:430px"><?php echo $book[$jData['book']]." - ". $jData['description']; ?></div>
					<div class="divTableCell a-right" style="width:80px"><?php echo $jData['amount'] ?></div>
					
				</div>
			<?php $i++;
			endforeach;
			?>
			<div class="divTableRow">					
				<div class="divTableCell"></div>								
				<div class="divTableCell" style="width:80px;text-align:center;"></div>
				<div class="divTableCell" style="width:210px"></div>
				<div class="divTableCell" style="width:210px"></div>				
				<div class="divTableCell" style="width:430px"><b>Total</b></div>
				<div class="divTableCell a-right" style="width:80px;"><b><?php echo $tcr; ?></b></div>
				
			</div>
			</div>	
			</div>
	</div>

<?php else: ?>	
		<h4 style="text-align:center">No Transaction found.</h4>	
<?php endif;?>

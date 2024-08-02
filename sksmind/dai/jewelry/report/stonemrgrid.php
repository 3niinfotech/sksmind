<?php session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'Helper.php');
$helper  = new Helper($cn);
$attribute = $helper->getAttribute();							
$width50 = $helper->width50();
//$memo = $helper->getInventory('rmemo'); 

if($_POST['type'] =="outward"):
	$data = $helper->getOutwardDetails($_POST['eid']);
elseif($_POST['type'] =="lab"):
	$data = $helper->getLabDetails($_POST['eid']);	
else:
	$data = $helper->getInwardDetails($_POST['eid']);
endif;

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
<?php if($_POST['type'] =="outward"): ?>
	<div class="alldata">
		<ul>
			<li><b>Date :</b><?php echo $data['date'] ?>	</li>
			<li><b>Reference :</b><?php echo $data['reference'] ?></li>
			<li><b>Invoice No :</b><?php echo $data['invoiceno'] ?></li>
			<!--<li><b>Entry No :</b><?php echo $data['entryno'] ?></li>-->
			<li><b>Invoice Date :</b>			<?php 				$phpdate = strtotime( $data['invoicedate'] );				echo  date( 'd-m-Y', $phpdate );			?></li>
			<li><b>Terms :</b><?php echo $data['terms'] ?></li>
			<li><b>Due Date:</b><?php  			if($data['terms'] =='' || $data['terms'] =='0'):				$phpdate = strtotime( $data['invoicedate'] );			else:				$phpdate = strtotime( $data['duedate'] );			endif;			echo  date( 'd-m-Y', $phpdate );						?></li>
			<li><b>Narretion</b><?php echo $data['narretion'] ?></li>
		</ul>
	</div>
<?php elseif($_POST['type'] =="lab"): ?>
	<div class="alldata">
		<ul>
			<li><b>Date :</b><?php 
			$phpdate = strtotime( $data['date'] );
			echo date('d-m-Y',$phpdate);
			?>	</li>
			<li><b>Reference :</b><?php echo $data['reference'] ?></li>
			<li><b>Invoice No :</b><?php echo $data['invoiceno'] ?></li>
			<li><b>Entry No :</b><?php echo $data['entryno'] ?></li>
			<li><b>Narretion</b><?php echo $data['narretion'] ?></li>
		</ul>
	</div>	
<?php else: ?>
	<div class="alldata">
		<ul>
			<li><b>Date :</b><?php echo $data['date'] ?>	</li>
			<li><b>Reference :</b><?php echo $data['reference'] ?></li>
			<li><b>Invoice No :</b><?php echo $data['invoiceno'] ?></li>
		<li><b>Entry No :</b><?php echo $data['entryno'] ?></li>
			<li><b>Invoice Date :</b><?php 						$phpdate = strtotime( $data['invoicedate'] );						echo  date( 'd-m-Y', $phpdate );			?></li>
			<li><b>Terms :</b><?php echo $data['terms'] ?></li>
			<li><b>Due Date:</b><?php  			if($data['terms'] =='' || $data['terms'] =='0'):				$phpdate = strtotime( $data['invoicedate'] );			else:				$phpdate = strtotime( $data['duedate'] );			endif;			echo  date( 'd-m-Y', $phpdate );						?></li>
			<li><b>Narretion</b><?php echo $data['narretion'] ?></li>
		</ul>
	</div>
<?php endif; ?>


<div class="subform invenotry-cgrid ">
		<div class="divTable" >
			<div class="divTableHeading">			
				<div class="divTableCell">No</div>
				<div class="divTableCell">SKU</div>
				<div class="divTableCell width-50px">Pcs</div>
				<div class="divTableCell width-50px">Carat</div>
				<div class="divTableCell width-70px">Price</div>
				<div class="divTableCell width-70px">Amount</div>
				<div class="divTableCell width-50px">Lab</div> 
				<div  class="divTableCell">IGI Code</div>
				<div  class="divTableCell">IGI Color</div>
				<div  class="divTableCell">IGI Clarity</div>
				<div  class="divTableCell">IGI Amount</div>
				<div class="divTableCell width-50px">LOC </div>
				<div class="divTableCell">Remark</div>
			</div>	
		<div class="divTableBody" style="height:150px;">
		<?php 
		$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = 0.0; 
		$i=1;
		foreach($data['record'] as  $jData): 
		
			/* $trp += (float) $jData['rought_pcs'];
			$trc += (float) $jData['rought_carat']; */
			$tpp += (float) $jData['pcs'];
			$tpc += (float) $jData['carat'];
			$tp += (float) ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'];
			$ta += (float) ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'];
			
			$class="";
			
			?>
			
			<div class="divTableRow <?php echo $class;?>">	
				<div class="divTableCell"><?php echo $i; ?></div>
				<div class="divTableCell">  <?php echo $jData['sku']?> </div>
				<div class="divTableCell a-right width-50px">  <?php echo $jData['pcs']?> </div>
				<div class="divTableCell a-right width-50px">  <?php echo $jData['carat']?>  </div>
				<div class="divTableCell a-right width-70px">  <?php echo ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'] ?></div>
				<div class="divTableCell a-right width-70px">  <?php echo ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'] ?></div>
				
				<div class="divTableCell width-50px">  <?php echo $jData['lab']?> </div>
				<div class="divTableCell  "><?php echo $jData['igi_code'];?></div>			
				<div class="divTableCell  "><?php echo $jData['igi_color'];?></div>			
				<div class="divTableCell a-right "><?php echo $jData['igi_clarity'];?></div>		
				<div class="divTableCell a-right "><?php echo $jData['igi_amount'];?></div>	
				<div class="divTableCell width-50px">  <?php echo $jData['location']?> </div>
				<div class="divTableCell">  <?php echo $jData['remark']?> </div>
			</div>				
		<?php $i++; endforeach; ?>
			<div class="divTableRow">				
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell a-right width-50px">  <b><?php echo $tpp?></b> </div>
					<div class="divTableCell a-right width-50px"> <b> <?php echo $tpc?></b>  </div>
					<div class="divTableCell a-right width-70px">  <b><?php echo $tp?></b></div>
					<div class="divTableCell a-right width-70px">  <b><?php echo $ta?></b></div>
					<div class="divTableCell width-50px">  </div>
					<div class="divTableCell">  </div>
					<div class="divTableCell">  </div>
					<div class="divTableCell">  </div>
					<div class="divTableCell">  </div>
					
					<div class="divTableCell width-50px">  </div>
					<div class="divTableCell">  </div>
				</div>
			</div>
		</div>
	</div>

<?php else: ?>
<h4 style="text-align:center">No Memo found for this Party.</h4>
<?php endif;?>
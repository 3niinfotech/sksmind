<?php session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):	
header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'Helper.php');
$helper  = new Helper();
$data = $helper->getBookTransaction($_POST);
include_once($daiDir.'account/party/partyModel.php');
include_once($daiDir.'account/balance/balanceModel.php');
include_once($daiDir.'account/subgroup/subgroupModel.php');

$pmodel  = new partyModel();
$sgmodel  = new subgroupModel();
$party = $pmodel->getOptionList();	
$subgroup = $sgmodel->getOption();	
$i=1;
$Bmodel  = new balanceModel();
$cData = $Bmodel->getAllCurrency();
$book = $helper->getAllBook();
$currency = $helper->getAllCurrency('all');
$book['']="All Book";?>
<?php if(count($data)): ?>	
<?php if($_POST['book'] !="" ){ ?>	
<div class="cgrid-header green" style="padding:left:10px !important;padding-bottom:10px;">
   <div class="color-total"> <b>Book Type : </b> &nbsp;&nbsp;<?php echo $book[$_POST['book']]; ?> &nbsp;&nbsp; |</div>
</div>
<div class="subform invenotry-cgrid " style="overflow-x: auto;" >
   <div class="divTable" style="width:1322px">
      <div class="divTableHeading">
         <div class="divTableCell">No</div>
         <div class="divTableCell" style="width:80px;text-align:center;">Date</div>
         <div class="divTableCell" style="width:210px">Account</div>
         <div class="divTableCell" style="width:210px">Party</div>
         <div class="divTableCell" style="width:80px;text-align:center;">Cheque</div>
         <div class="divTableCell" style="width:450px">Description</div>
         <div class="divTableCell " style="width:80px;text-align:center;">Credit</div>
         <div class="divTableCell " style="width:80px;text-align:center;">Debit</div>
         <div class="divTableCell " style="width:80px;text-align:center;">Balance</div>
      </div>
      <div class="divTableBody" style="overflow-y: auto;">
         <?php 				$tcr = $tdr = 0.0; 				$i=1;				$balance = 0.0;				foreach($data as $jData):								if($jData['type'] == 'cr'):					$tcr += (float)$jData['amount'];					$balance += (float)$jData['amount'];				else:					$tdr += (float)$jData['amount'];					$balance -= (float)$jData['amount'];				endif;								?>								
         <div class="divTableRow ">
            <div class="divTableCell"><?php echo $i; ?></div>
            <div class="divTableCell" style="width:80px">
			<?php
			$phpdate = strtotime( $jData['date'] );	
			echo  date( 'd-m-Y', $phpdate );?></div>
            <div class="divTableCell" style="width:210px"><?php echo isset($subgroup[$jData['under_subgroup']])?$subgroup[$jData['under_subgroup']]:"";?></div>
            <?php if($jData['sale_id'] !=""):?>
				<div class="divTableCell" style="width:210px;cursor:pointer;"  onClick="loadRecord('outward',<?php echo $jData['sale_id']?>)"  > <i class="fa fa-eye"></i> <?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
			<?php elseif($jData['purchase_id'] !=""): ?>
				<div class="divTableCell" style="width:210px;cursor:pointer;"  onClick="loadRecord('inward',<?php echo $jData['purchase_id']?>)"  > <i class="fa fa-eye"></i> <?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
			<?php else: ?>
				<div class="divTableCell" style="width:210px" > <?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
			<?php endif;?>
            <div class="divTableCell" style="width:80px"><?php echo $jData['cheque']; ?></div>
            <div class="divTableCell" style="width:450px"><?php echo $jData['description']; ?></div>
            <div class="divTableCell a-right" style="width:80px"><?php echo ( $jData['type'] == 'cr' )? $jData['amount']:""; ?></div>
            <div class="divTableCell a-right" style="width:80px"><?php echo ( $jData['type'] =='dr' )? $jData['amount']:""; ?></div>
            <div class="divTableCell a-right" style="width:80px"><?php echo $balance; ?></div>
         </div>
         <?php $i++;			endforeach;			?>			
         <div class="divTableRow">
            <div class="divTableCell"></div>
            <div class="divTableCell" style="width:80px;text-align:center;"></div>
            <div class="divTableCell" style="width:210px"></div>
            <div class="divTableCell" style="width:210px"></div>
            <div class="divTableCell" style="width:80px;text-align:center;"></div>
            <div class="divTableCell" style="width:450px"><b>Total</b></div>
            <div class="divTableCell a-right" style="width:80px;"><b><?php echo $tcr; ?></b></div>
            <div class="divTableCell a-right" style="width:80px;"><b><?php echo $tdr; ?></b></div>
            <div class="divTableCell a-right" style="width:80px;"><b><?php echo $tcr - $tdr; ?> </b></div>
         </div>
      </div>
   </div>
   <div class="divTable" style="width:20%;float: right;">
      <div class="divTableHeading">
         <div class="divTableCell "></div>
         <div class="divTableCell ">Currency</div>        
         <div class="divTableCell center ">Balance</div>
      </div>
      <div class="divTableBody" style="overflow-y: auto;height:50px;">
         <div class="divTableRow">
            <div class="divTableCell">1</div>
            <div class="divTableCell"> <b>USD To:</b></div>
            <div class="divTableCell a-right"><?php echo number_format((float)($tcr-$tdr) / (float)$cData[$currency[$_POST['book']]]['USD'],2,'.',''); ?></div>
         </div>
         <div class="divTableRow">
            <div class="divTableCell">2</div>
            <div class="divTableCell"><b>HKD To:</b></div>
            <div class="divTableCell a-right"><?php echo number_format((float)($tcr-$tdr) / (float)$cData[$currency[$_POST['book']]]['HKD'],2,'.',''); ?></div>
         </div>
      </div>
   </div>
</div>
<?php } 	else	{ 	?>		<?php foreach($book as $k=>$v): 		if(!isset($data[$k]))			continue;		?>		
<div class="cgrid-header green" style="padding:left:10px !important;padding-bottom:10px;">
   <div class="color-total"> <b>Book Type : </b> &nbsp;&nbsp;<?php echo $book[$k]; ?> &nbsp;&nbsp; |</div>
</div>
<div class="subform invenotry-cgrid " style="overflow-x: auto;" >
   <div class="divTable" style="width:1322px">
      <div class="divTableHeading">
         <div class="divTableCell">No</div>
         <div class="divTableCell" style="width:6%;text-align:center;">Date</div>
         <div class="divTableCell" style="width:16%">Account</div>
         <div class="divTableCell" style="width:17%">Party</div>
         <div class="divTableCell" style="width:6%;text-align:center;">Cheque</div>
         <div class="divTableCell" style="width:30%">Description</div>
         <div class="divTableCell " style="width:7%;text-align:center;">Credit</div>
         <div class="divTableCell " style="width:7%;text-align:center;">Debit</div>
         <div class="divTableCell " style="width:8%;text-align:center;">Balance</div>
      </div>
      <div class="divTableBody" style="overflow-y: auto;">
         <?php 					$tcr = $tdr = 0.0; 		
		 $i=1;					
		 $balance  =0.0;
		 foreach($data[$k] as $jData):	
		 if($jData['type'] == 'cr'):
			$tcr += (float)$jData['amount'];
			$balance += (float)$jData['amount'];
		else:						
			$tdr += (float)$jData['amount'];
			$balance -= (float)$jData['amount'];
		endif;				?>										
         <div class="divTableRow ">
            <div class="divTableCell"><?php echo $i; ?></div>
            <div class="divTableCell" style="width:6%"><?php
			$phpdate = strtotime( $jData['date'] );	
			echo  date( 'd-m-Y', $phpdate );?></div>
            <div class="divTableCell" style="width:16%"><?php echo isset($subgroup[$jData['under_subgroup']])?$subgroup[$jData['under_subgroup']]:"";?></div>
            
			<?php if($jData['sale_id'] !=""):?>
				<div class="divTableCell" style="width:210px"  onClick="loadRecord('outward',<?php echo $jData['sale_id']?>)"  > <i class="fa fa-eye"></i> <?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
			<?php elseif($jData['purchase_id'] !=""): ?>
				<div class="divTableCell" style="width:210px"  onClick="loadRecord('inward',<?php echo $jData['purchase_id']?>)"  > <i class="fa fa-eye"></i> <?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
			<?php else: ?>
				<div class="divTableCell" style="width:210px" > <?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
			<?php endif;?>
			
            <div class="divTableCell" style="width:6%"><?php echo $jData['cheque']; ?></div>
            <div class="divTableCell" style="width:30%"><?php echo $jData['description']; ?></div>
            <div class="divTableCell a-right" style="width:7%"><?php echo ( $jData['type'] == 'cr' )? $jData['amount']:""; ?></div>
            <div class="divTableCell a-right" style="width:7%"><?php echo ( $jData['type'] =='dr' )? $jData['amount']:""; ?></div>
            <div class="divTableCell a-right" style="width:8%"><?php echo $balance; ?></div>
         </div>
         <?php $i++;				endforeach;				?>				
         <div class="divTableRow">
            <div class="divTableCell"></div>
            <div class="divTableCell" style="width:6%;text-align:center;"></div>
            <div class="divTableCell" style="width:16%"></div>
            <div class="divTableCell" style="width:17%"></div>
            <div class="divTableCell" style="width:6%;text-align:center;"></div>
            <div class="divTableCell" style="width:30%"><b>Total</b></div>
            <div class="divTableCell a-right" style="width:7%;"><b><?php echo $tcr; ?></b></div>
            <div class="divTableCell a-right" style="width:7%;"><b><?php echo $tdr; ?></b></div>
            <div class="divTableCell a-right" style="width:8%;"><b><?php echo $tcr - $tdr; ?> </b></div>
         </div>
      </div>
   </div>
   <div class="divTable" style="width:20%;float: right;">
      <div class="divTableHeading">
         <div class="divTableCell "></div>
         <div class="divTableCell ">Currency</div>
         <div class="divTableCell center ">Balance</div>
      </div>
      <div class="divTableBody" style="overflow-y: auto;height:50px;">
         <div class="divTableRow">
            <div class="divTableCell">1</div>
            <div class="divTableCell"> <b>USD To:</b></div>
            <div class="divTableCell a-right"><?php echo number_format((float)($tcr-$tdr) / (float)$cData[$currency[$k]]['USD'],2,'.',''); ?></div>
         </div>
         <div class="divTableRow">
            <div class="divTableCell">2</div>
            <div class="divTableCell"><b>HKD To:</b></div>
            <div class="divTableCell a-right"><?php echo number_format((float)($tcr-$tdr) / (float)$cData[$currency[$k]]['HKD'],2,'.',''); ?></div>
         </div>
      </div>
   </div>
</div>
<div style="clear:both"></div>
<hr>
<?php endforeach;?> 			<?php } ?><?php else: ?>
<h4 style="text-align:center">OOPPSS !! No transaction found.</h4>
<?php endif;?>


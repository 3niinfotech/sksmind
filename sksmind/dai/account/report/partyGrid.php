<?php session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):	
header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'Helper.php');
$helper  = new Helper();
//$data = $helper->getBookTransaction($_POST);
$data = $helper->getPartyAccountTransaction($_POST);
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

<div class="subform invenotry-cgrid " style="overflow-x: auto;" >
   <div class="divTable" style="width:1322px">
      <div class="divTableHeading">
         <div class="divTableCell">No</div>
         <div class="divTableCell" style="width:6%;text-align:center;">Date</div>
         <div class="divTableCell" style="width:16%">Account</div>
         <div class="divTableCell" style="width:17%">Party</div>         		 
         <div class="divTableCell" style="width:30%">Description</div>
		 <div class="divTableCell" style="width:14%">Book</div>         
         <div class="divTableCell " style="width:7%;text-align:center;">Credit</div>
         <div class="divTableCell " style="width:7%;text-align:center;">Debit</div>
         
      </div>
      <div class="divTableBody" style="overflow-y: auto;">
         <?php 					$tcr = $tdr = 0.0; 					$i=1;					$balance  =0.0;					
		 foreach($data as $jData):					if($jData['type'] == 'cr'):						$tcr += (float)$jData['amount'];						$balance += (float)$jData['amount'];					else:						$tdr += (float)$jData['amount'];						$balance -= (float)$jData['amount'];					endif;				?>										
         <div class="divTableRow ">
            <div class="divTableCell"><?php echo $i; ?></div>
            <div class="divTableCell" style="width:6%"><?php
			$phpdate = strtotime( $jData['date'] );	
			echo  date( 'd-m-Y', $phpdate );?></div>
            <div class="divTableCell" style="width:16%"><?php echo isset($subgroup[$jData['under_subgroup']])?$subgroup[$jData['under_subgroup']]:"";?></div>
            <div class="divTableCell" style="width:17%" <?php if($jData['sale_id'] !=""):?> onClick="loadRecord('outward',<?php echo $jData['sale_id']?>)" <?php endif;?> > <?php if($jData['sale_id'] !=""):?><i class="fa fa-eye"></i> <?php endif?><?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>            
            <div class="divTableCell" style="width:30%"><?php echo $jData['description']; ?></div>
			<div class="divTableCell" style="width:14%"> <?php echo isset($book[$jData['book']])?$book[$jData['book']]:"";?></div>
            <div class="divTableCell a-right" style="width:7%"><?php echo ( $jData['type'] == 'cr' )? $jData['amount']:""; ?></div>
            <div class="divTableCell a-right" style="width:7%"><?php echo ( $jData['type'] =='dr' )? $jData['amount']:""; ?></div>            
         </div>
         <?php $i++;				endforeach;				?>				
         <div class="divTableRow">
            <div class="divTableCell"></div>
            <div class="divTableCell" style="width:6%;text-align:center;"></div>
            <div class="divTableCell" style="width:16%"></div>
            <div class="divTableCell" style="width:17%"></div>            
            <div class="divTableCell" style="width:30%"></div>
			<div class="divTableCell" style="width:14%"><b>Total</b></div>
            <div class="divTableCell a-right" style="width:7%;"><b><?php echo $tcr; ?></b></div>
            <div class="divTableCell a-right" style="width:7%;"><b><?php echo $tdr; ?></b></div>            
         </div>
      </div>
   </div>
</div>
<div style="clear:both"></div>
<hr>
<?php else: ?>
<h4 style="text-align:center">OOPPSS !! No transaction found.</h4>
<?php endif;?>


<?php session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):	
header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'Helper.php');
$helper  = new Helper();
$data = $helper->getAdvanceTransaction($_POST);
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
         <div class="divTableCell" style="width:80px;text-align:center;">Date</div>
         <div class="divTableCell" style="width:210px">Party</div>
         <div class="divTableCell" style="width:210px">Book</div>         
         <div class="divTableCell " style="width:80px;text-align:center;">Amount</div>
		 <div class="divTableCell " style="width:80px;text-align:center;">Used</div>
		 <div class="divTableCell " style="width:80px;text-align:center;">Balance</div>
      </div>
      <div class="divTableBody" style="overflow-y: auto;">
         <?php 				
		 foreach($data as $jData):								
		 
		 ?>								
         <div class="divTableRow ">
            <div class="divTableCell"><?php echo $i; ?></div>
            <div class="divTableCell" style="width:80px">
			<?php
			$phpdate = strtotime( $jData['date'] );	
			echo  date( 'd-m-Y', $phpdate );?></div>
			<div class="divTableCell" style="width:210px"> <?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
            <div class="divTableCell" style="width:210px"><?php echo isset($book[$jData['book']])?$book[$jData['book']]:"";?></div>            
            <div class="divTableCell a-right" style="width:80px"><?php echo $jData['amount']; ?></div>            
			<div class="divTableCell a-right" style="width:80px"><?php echo $jData['use_amount']; ?></div>            
			<div class="divTableCell a-right" style="width:80px"><?php echo $jData['balance_amount']; ?></div>            
         </div>
         <?php $i++;			endforeach;			?>			
         
      </div>
   </div>   
</div>
<?php else: ?>
<h4 style="text-align:center">OOPPSS !! No transaction found.</h4>
<?php endif;?>


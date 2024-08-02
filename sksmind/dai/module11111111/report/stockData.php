<?php include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once('reportHelper.php');
$reportHelper  = new reportHelper();
include_once($daiDir.'Helper.php');
$helper  = new Helper();

$import = $helper->getTotalOfInOut('inward','import');
$purchase = $helper->getTotalOfInOut('inward','purchase');

$export = $helper->getTotalOfInOut('outward','export');
$sale = $helper->getTotalOfInOut('outward','sale');


$inward['tpc'] = (float)$import['tpc'] + (float)$purchase['tpc'];
$inward['tp'] = (float)$import['tp'] + (float)$purchase['tp'];
$inward['ta'] = (float)$import['ta'] + (float)$purchase['ta'];


$outward['tpc'] = (float)$export['tpc'] + (float)$sale['tpc'];
$outward['tp'] = (float)$export['tp'] + (float)$sale['tp'];
$outward['ta'] = (float)$export['ta'] + (float)$sale['ta'];


$stock['tpc'] = (float)$inward['tpc'] - (float)$outward['tpc'];
$stock['tp'] = (float)$inward['tp'] - (float)$outward['tp'];
$stock['ta'] = (float)$inward['ta'] - (float)$outward['ta'];
//echo"<pre>";
//print_r($import);
?>

	
<div class="col-xs-12 col-sm-12 ">								
	<div class="report-ledger-info">
		<h3>Stock Report</h3>
		<p>31-April-2015 &nbsp; <b>TO</b> &nbsp;1-Mar-2016</p>
	</div>
</div>	


<!-- Header -------------------------------------------------- -->
<div class="col-xs-12 col-sm-12 report-content " style="margin-top: 50px;">								
	<div class="col-xs-6 col-sm-6 report-content ">
		<div class="content-header content-row">
			<div class="content-column">Description</div>																			
			<div class="content-column a-right">Carats</div>
			<div class="content-column a-right">Price</div>
			<div class="content-column a-right">Amount</div>
		</div>
	</div>
	<div class="col-xs-6 col-sm-6 report-content ">
		<div class="content-header content-row">
			<div class="content-column">Description</div>																			
			<div class="content-column a-right">Carats</div>
			<div class="content-column a-right">Price</div>
			<div class="content-column a-right">Amount</div>
		</div>
	</div>								
</div>


							<div class="col-xs-12 col-sm-12 report-content ">	
<!-- Left Side -------------------------------------------------------------->	
	<div class="col-xs-6 col-sm-6 report-content ">
		<div class="content-row">
			<div class="content-column"><b>Import</b></div>																			
			<div class="content-column a-right"><?php echo $import['tpc']?></div>
			<div class="content-column a-right"><?php echo $import['tp']?></div>
			<div class="content-column a-right"><?php echo $import['ta']?></div>
		</div>
		<div class="content-row">
			<div class="content-column"><b>Purchase</b></div>																			
			<div class="content-column a-right"><?php echo $purchase['tpc']?></div>
			<div class="content-column a-right"><?php echo $purchase['tp']?></div>
			<div class="content-column a-right"><?php echo $purchase['ta']?></div>
		</div>
	</div>
	
	<!-- Right Side -------------------------------------------------------------->					
	<div class="col-xs-6 col-sm-6 report-content ">
		<div class="content-row">
			<div class="content-column"><b>Export</b></div>																			
			<div class="content-column a-right"><?php echo $export['tpc']?></div>
			<div class="content-column a-right"><?php echo $export['tp']?></div>
			<div class="content-column a-right"><?php echo $export['ta']?></div>
		</div>
		<div class="content-row">
			<div class="content-column"><b>Sale</b></div>																			
			<div class="content-column a-right"><?php echo $sale['tpc']?></div>
			<div class="content-column a-right"><?php echo $sale['tp']?></div>
			<div class="content-column a-right"><?php echo $sale['ta']?></div>
		</div>
	</div>
</div>


<!-- Footer ---------------------------------->	
<div class="col-xs-12 col-sm-12 report-content " >									
	<div class="col-xs-6 col-sm-6 report-content ">
		<div class="content-footer content-row">
			<div class="content-column"><b>Total</b></div>																		
			<div class="content-column a-right"><?php echo $inward['tpc']?></div>
			<div class="content-column a-right"><?php echo $inward['tp']?></div>
			<div class="content-column a-right"><?php echo $inward['ta']?></div>
		</div>	
	</div>
	<div class="col-xs-6 col-sm-6 report-content ">
		<div class="content-footer content-row">
			<div class="content-column"><b>Total</b></div>																		
			<div class="content-column a-right"><?php echo $outward['tpc']?></div>
			<div class="content-column a-right"><?php echo $outward['tp']?></div>
			<div class="content-column a-right"><?php echo $outward['ta']?></div>
		</div>	
	</div>
</div>

<div class="col-xs-12 col-sm-12 report-content " >	
<div class="col-xs-6 col-sm-6 report-content " style="padding-top:20px;padding-bottom:20px;">	
	<div class="content-row" style="color: rgb(38, 121, 181);">
		<div class="content-column"><b>STOCK</b></div>																			
			<div class="content-column a-right trading"><?php echo $stock['tpc']?></div>
			<div class="content-column a-right trading"><?php echo $stock['tp']?></div>
			<div class="content-column a-right trading"><?php echo $stock['ta']?></div>
	</div>
</div>

<div class="col-xs-6 col-sm-6 report-content ">	
	<div class="content-row">
		
	</div>
</div>
</div>	
			

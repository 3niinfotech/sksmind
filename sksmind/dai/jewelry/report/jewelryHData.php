<?php 
session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'jewelry/party/partyModel.php');

include_once('reportHelper.php');
$reportHelper  = new reportHelper($cn);
include_once($daiDir.'jHelper.php');
$jhelper  = new jHelper($cn);
$data = $jhelper ->getDetailBySku($_POST['sku'],'jewelry');

$action = $jhelper->getStoneAction();
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
if(!empty($data)):
$history = $jhelper->getHistoryOfStone($data['id'],'jewelry');
$party['']="";
//$transaction = $helper->getTransactionOfStone($data['id']);

?>

<div class="col-xs-12 col-sm-12 report-content " >
<div class="col-xs-12 col-sm-12 " >
	<?php foreach($jhelper->getJewelryUpdateAttribute() as $k=>$v) :
	if($k =="rapnet" || $k =="discount")
		continue;
	?>
	<div class="history-attribute">
		<div class="attribute-label"><?php echo $v;?></div>
		<div class="attribute-value"><?php echo $data[$k];?></div>
	</div>
	<?php endforeach; ?>
	<div style="clear:both"></div>
	<?php if(!empty($history)): ?>
	<div class="subform invenotry-cgrid " style="overflow-x: auto; padding: 0px 0px 22px 0px;" >
	<hr>
	<h5 style="text-align:center;">History of <b><?php echo $_POST['sku'] ?></b>  : </h5>
	<hr>
	<div class="divTable" style="width:100%; margin:0 auto;">
		<div class="divTableHeading">
			<div class="divTableCell "></div>
			<div class="divTableCell center">Action</div>
			<div class="divTableCell center ">Date</div>
			<div class="divTableCell center ">Invoice</div>
			<div class="divTableCell center " style="width:200px">Party</div>
			<div class="divTableCell center " style="width:390px">Description</div>
			<div class="divTableCell center width-50px">Pcs</div>
			<div class="divTableCell center width-50px">Carat</div>
			<div class="divTableCell center ">Price</div>	
			<div class="divTableCell center ">Amount</div>	
		</div>	

		<div class="divTableBody" style="">	
			<?php $i=0;
			$bcarat = $ccarat = 0.0;
			foreach($history as $trn): 
			$i++;
			
			if($trn['type'] == "cr"):
				$bcarat += (float)$trn['carat'];
				$ccarat += (float)$trn['pcs'];
			else:
				$bcarat -= (float)$trn['carat'];
				$ccarat -= (float)$trn['pcs'];
			endif;
			?>
			<div class="divTableRow">
				<div class="divTableCell"><?php echo $i;?></div>
				<div class="divTableCell"> <b><?php echo $action[$trn['action']]; ?></b></div>
				<div class="divTableCell "><?php  
						$phpdate = strtotime( $trn['date'] );	
						echo  date( 'd-m-Y', $phpdate );				
				?></div>
				<div class="divTableCell "><?php echo $trn['invoice']; ?></div>
				<div class="divTableCell " style="width:200px"><?php echo (isset($party[$trn['party']])) ? $party[$trn['party']]:''; ?></div>
				<div class="divTableCell " style="width:390px"><?php echo $trn['description']; ?></div>								
				<div class="divTableCell a-right width-50px"><?php echo ($trn['type'] == "cr")? $trn['pcs'] : -$trn['pcs']; ?></div>
				<div class="divTableCell a-right width-50px"><?php echo ($trn['type'] == "cr")? $trn['carat'] : -$trn['carat']; ?></div>
				<div class="divTableCell a-right"><?php echo $trn['price']; ?></div>								
				<div class="divTableCell a-right"><?php echo $trn['amount']; ?></div>								
			</div>	
			<?php endforeach;?>
		</div>
	</div>
	</div>
	<?php endif;?>
</div>									
<!-- <div class="col-xs-6 col-sm-6 6" >	
	<?php foreach($history as $k=>$h) :?>
	<div class="col-xs-3 col-sm-3 history-block ">		
		<div class="history-head">
			<div class="history-type"><?php echo $action[$h['action']]?></div>
			<div class="history-date"><?php echo $h['date']?></div>
		</div>																			
		<div class="history-decription">
			<?php if($h['party'] != ""):?>
			<p><b>Party. :</b> <?php echo (isset($party[$h['party']]))?$party[$h['party']]:""; ?></p>
			<?php endif;?>
			<?php if($h['description'] != ""):?>
			<p><b>Note :</b> <?php echo $h['description'] ?></p>
			<?php endif;?>
			<?php if($h['narretion'] != ""):?>
			<p><b>Narration :</b> <?php echo $h['narretion'] ?></p>
			<?php endif;?>
		</div>			
	</div>
	
	<?php endforeach; ?>
	<?php if(empty($history)):?>
		<h4 style="text-align:center">Sorry, No History Available.</h4>
	<?php endif;?>
</div> -->

</div>	
<?php else:?>
	<h4 style="text-align:center">No Data found for this SKU.</h4>
<?php endif;?>

			
<style>
.history-block {
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  border-color: #9abc32 #ccc #ccc;
  border-image: none;
  border-radius: 2px;
  border-style: solid;
  border-width: 5px 1px 1px;
  margin: 10px;
  padding: 0;
  width: 100%;
}
.history-head {
  border-bottom: 1px dashed #ccc;
  float: left;
  padding: 7px;
  width: 100%;
}
.history-type {
  color: #2679b5;
  float: left;
  font-size: 14px;
  font-weight: bold;
  letter-spacing: 1px;
  width: 50%;
}
.history-date {
  color: #666;
  float: left;
  font-size: 14px;
  letter-spacing: 1px;
  text-align: right;
  width: 50%;
}
.history-decription {
  float: left;
  padding: 15px 10px;
  width: 100%;
  height:100px;
}
.history-attribute {
  border-bottom: 1px solid #ccc;
  float: left;
  margin-bottom: 20px;
  margin-right: 3%;
  padding-bottom: 5px;
  width: 22%;
}
.attribute-label {
  color: #2679b5;
  float: left;
  font-size: 14px;
  width: 50%;
}
.attribute-value {
  color: #333;
  float: left;
  text-align: right;
  font-size: 14px;
  width: 50%;
}
.history-decription p {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.invenotry-cgrid .divTableCell {
	
	width: 105px;
}
</style>			

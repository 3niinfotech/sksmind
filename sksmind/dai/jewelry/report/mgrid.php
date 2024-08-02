<?php session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'jHelper.php');
$helper  = new jHelper($cn);
			
$data = $helper->getReportTransfer($_POST);

include_once($daiDir.'jewelry/inventory/inventoryModel.php');
include_once($daiDir.'jewelry/jobwork/jobworkModel.php');
$model  = new jobworkModel($cn);
$imodel  = new inventoryModel($cn);
$firm = $imodel->getAllMainFirm();	
$i=1;
?>
<?php if(count($data)): ?>

<div class="subform invenotry-cgrid ">
		<div class="divTable packet">
			<div class="divTableHeading">				
				<div class="divTableCell">No</div>
				<div class="divTableCell">Type</div>
				<div class="divTableCell">SKU</div>
				<div class="divTableCell">PCS</div>
				<div class="divTableCell">Carat</div>
				<div class="divTableCell">Shape</div>
				<div class="divTableCell">Clarity</div>
				<div class="divTableCell">Color</div>
				<div class="divTableCell">Price</div>
				<div class="divTableCell">Amount</div>
				<div class="divTableCell">Lab</div>
				<div class="divTableCell">IGI Code</div>
				<div class="divTableCell">IGI Color</div>
				<div class="divTableCell">IGI Clarity</div>
				<div class="divTableCell">IGI Amount</div>
				<div class="divTableCell">Date</div>	
				<div class="divTableCell" style="width:8%">Firm</div>
				<div class="divTableCell" style="width:6%">Description</div>	
			</div>	
			<div class="divTableBody">
	<?php 
		foreach($data as $jData):
		
			$phpdate = strtotime($jData['date']);
			$date = date('d-m-Y',$phpdate);
			$main_stone = ($jData['main_stone'] != "")?explode(",",$jData['main_stone']):array();
			$side_stone = ($jData['side_stone'] != "")?explode(",",$jData['side_stone']):array();
	?>	
	<?php 
			foreach($main_stone as $id):
				if($id == '')
				continue;
			$value = $model->getProductDetail($id);
	?>
			<div class="divTableRow ">					
				<div class="divTableCell"><?php echo $i; ?></div>	
				<div class="divTableCell">Main</div>
				<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell  "><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['price'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['amount'];?></div>	<div class="divTableCell  "><?php echo $value['lab'];?></div>
					<div class="divTableCell  "><?php echo $value['igi_code'];?></div>			
					<div class="divTableCell  "><?php echo $value['igi_color'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['igi_clarity'];?></div>		
					<div class="divTableCell a-right "><?php echo $value['igi_amount'];?></div>
					<div class="divTableCell"><?php echo $date; ?></div>		
					<div class="divTableCell" style="width:8%"><?php echo $firm[$jData['tofirm']]; ?></div>		
					<div class="divTableCell" style="width:6%"><?php echo $jData['description']; ?></div>
			</div>
			<?php $i++;?>
		<?php  endforeach; ?>
		<?php 
			foreach($side_stone as $id):
			if($id == '')
				continue;
			$value = $model->getSideProductDetail($id);
		?>
			<div class="divTableRow ">					
				<div class="divTableCell"><?php echo $i; ?></div>
				<div class="divTableCell">Side</div>
				<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell  "><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['price'];?></div>				
					<div class="divTableCell  a-right"><?php echo $value['amount'];?></div>	<div class="divTableCell  "></div>
					<div class="divTableCell  "></div>			
					<div class="divTableCell  "></div>			
					<div class="divTableCell a-right "></div>			
					<div class="divTableCell a-right "></div>
					<div class="divTableCell"><?php echo $date; ?></div>		
					<div class="divTableCell" style="width:8%"><?php echo $firm[$jData['tofirm']]; ?></div>		
					<div class="divTableCell" style="width:6%"><?php echo $jData['description']; ?></div>
			</div>
		<?php $i++;?>
		<?php  endforeach; ?>
		<?php  endforeach; ?>
			<!--<div class="divTableRow">					
					<div class="divTableCell"></div>
					<div class="divTableCell" ></div>
					<div class="divTableCell" style="width:8%"></div>
					<div class="divTableCell" style="width:6%"></div>
			</div>-->
			</div>
		</div>
	</div>
<?php else: ?>
<h4 style="text-align:center">OOPPSS !! There is no data found.</h4>
<?php endif;?>


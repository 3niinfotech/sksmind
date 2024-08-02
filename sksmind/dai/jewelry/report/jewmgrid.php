<?php session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once('reportHelper.php');
$reportHelper  = new reportHelper($cn);
include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$width50 = $helper->width50();
$attribute = $reportHelper->getJewMemoParty();
//$memo = $helper->getInventory('rmemo'); 
$data = $helper->getReportMemoJew($_POST);
/* echo "<pre>";
print_r($data);
exit; */
$width50 = array('tpp','tpc','terms','part');
$width70 = array('date','duedate','invoicedate','invoiceno');
$right = array('tpp','tpc','tp','ta','paid_amount','due_amount');
$jewType = $jhelper->getJewelryType();
$design = $jhelper->getAllDesign();
include_once($daiDir.'jewelry/party/partyModel.php');
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();	
$i=1;
$report = $_POST['report'];

?>
<?php if(count($data)): ?>

<?php if($_POST['type']=="packet"): ?>
<div class="subform invenotry-cgrid ">
		<div class="divTable packet" >
			<div class="divTableHeading">				
				<div class="divTableCell"></div>		
				<div class="divTableCell" style="width:200px">Party</div>		
				<div class="divTableCell">SKU</div>
				<div class="divTableCell">Design</div>
				<div class="divTableCell">Type</div>
				<div class="divTableCell width-50px" >Gold</div>
				<div class="divTableCell" >Metal</div>
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
			<div class="divTableBody">
	<?php 
		$trp = $tsp = $tnw = $tpw = $tc = $tp = $ta = 0.0; 
		$i=1;
		foreach($data as $jData):
		$tc += (float) $jData['gross_weight'];
		$tpw += (float) $jData['pg_weight'];
		$tnw += (float) $jData['net_weight'];
		$tsp += (float) $jData['sell_price'];
		$ta += (float) $jData['total_amount'];
		$sku = $jData['sku'];	
		?>
				
			<div class="divTableRow ">					
				<div class="divTableCell"><?php echo $i; ?></div>		
				<div class="divTableCell" style="width:200px"><?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
					<div class="divTableCell">  <?php echo $sku; ?> </div>
					<div class="divTableCell"><?php echo $jData['jew_design']?></div>
					<div class="divTableCell"><?php echo $jewType[$jData['jew_type']] ?></div>		
					<div class="divTableCell width-50px"><?php echo $jData['gold'] ?></div>			
					<div class="divTableCell"><?php echo $jData['metal'] ?></div>			
					<div class="divTableCell"><?php echo $jData['gold_color'] ?></div>				
					<div class="divTableCell"><?php echo $jData['gross_weight'] ?></div>			<div class="divTableCell"><?php echo $jData['pg_weight'] ?></div>				
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
			<?php $i++;?>
		<?php  endforeach; ?>
			<div class="divTableRow">					
					<div class="divTableCell"></div>
					<div class="divTableCell" style="width:200px"></div>
					<div class="divTableCell"> </div>
					<div class="divTableCell"> </div>
					<div class="divTableCell "> </div>
					<div class="divTableCell width-50px"> </div>
					<div class="divTableCell"> </div>
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
<?php elseif($_POST['type'] == "party"): ?>	
	
	<div class="subform invenotry-cgrid company" style="overflow-x:hidden;">
		<div class="divTable" style="width:100%;">
			<div class="divTableHeading">				
				<div class="divTableCell">No</div>
				<div class="divTableCell"> 
				Invoice
				</div>
				<div class="divTableCell" style="width:200px;">Company</div>
				
				<?php foreach($attribute as $key=>$v): ?>				
				<?php if($key == 'party' || $key == 'entryno'): continue;?>					
				
				<?php else: ?>
				<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?> <?php if(in_array($key,$width70))echo 'width-70px'; ?>"><?php echo $v; ?></div>				<?php endif;?>
				<?php endforeach;?>
			</div>	
		<div class="divTableBody" style="width:100%;">
			<?php 
			$tpp = $tpc =  $tp = $ta = $fa = 0.0; 
			$ftpp = $ftpc =  $ftp = $fta = 0.0; 
			$i=1;
			foreach($data as $jData):		
				$tpp += (float) $jData['tgross_weight'];			
				$tpc += (float) $jData['tpg_weight'];						
				$ta += (float) $jData['tnet_weight'];	
				$fa += (float) $jData['final_amount'];	
			?>
				<div class="divTableRow">
				<div class="divTableCell">  <?php echo $i ?>  </div>
				<div class="divTableCell"> 
					<a onClick="loadRecord(<?php echo $jData['id'] ?>)" style="cursor:pointer"><?php echo $jData['invoiceno'] ?></a>
				</div>
				<div class="divTableCell" style="width:200px;"><?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
				
				<?php foreach($attribute  as $key=>$v): 
				if($key == 'party' || $key == 'entryno')continue; ?>				
				<?php if($key == 'date'): ?>					
				<div class="divTableCell <?php if(in_array($key,$width70))echo 'width-70px'; ?>">		
				<?php 						
				$phpdate = strtotime( $jData[$key] );							
				echo  date( 'd-m-Y', $phpdate );						
				?>					
				</div>				
				<?php elseif($key == 'duedate' ): ?>					
				<div class="divTableCell <?php if(in_array($key,$width70))echo 'width-70px'; ?>">		
				<?php 	
				if($jData['terms']=='' || $jData['terms'] ==0 )							
					$phpdate = strtotime( $jData['invoicedate'] );									
				else
					$phpdate = strtotime( $jData[$key] );												
				echo  date( 'd-m-Y', $phpdate );?></div>					
				<?php else: ?>
				<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?> <?php if(in_array($key,$right))echo 'a-right'; ?> <?php if(in_array($key,$width70))echo 'width-70px'; ?> "><?php echo (isset($jData[$key]))?$jData[$key]:'&nbsp;';?></div>				<?php endif; ?>
				<?php endforeach;?>
				</div>


		<?php $i++; endforeach; ?>		
			<div class="divTableRow">
				<div class="divTableCell"></div>
				<div class="divTableCell"> </div>
				<div class="divTableCell" style="width:200px;"></div>
		   		<?php foreach($attribute as $key=>$v): ?>				
				<?php if($key == 'party' || $key == 'entryno'): continue;?>					
				<?php elseif($key == 'tgross_weight'): ?>
				<div class="divTableCell a-right"><b><?php echo $tpp;?></b></div>
				<?php elseif($key == 'tpg_weight'): ?>
				<div class="divTableCell a-right"><b><?php echo $tpc;?></b></div>
				<?php elseif($key == 'tnet_weight'): ?>
				<div class="divTableCell a-right"><b><?php echo $ta;?></b></div>
				<?php elseif($key == 'final_amount'): ?>
				<div class="divTableCell a-right"><b><?php echo $fa;?></b></div>
				<?php else: ?>
				<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?> <?php if(in_array($key,$width70))echo 'width-70px'; ?>"></div>
				<?php endif;?>
				<?php endforeach;?>
			</div>

			</div>			
		</div>
	</div>
<?php endif; ?>	
<?php else: ?>
<h4 style="text-align:center">OOPPSS !! There is no data found.</h4>
<?php endif;?>


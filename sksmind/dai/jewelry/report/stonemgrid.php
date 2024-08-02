<?php session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once('reportHelper.php');
$reportHelper  = new reportHelper($cn);
include_once($daiDir.'Helper.php');
$helper  = new Helper($cn);
$attribute = $reportHelper->getMemoPackage();
	
					
$width50 = $helper->width50();
//$memo = $helper->getInventory('rmemo'); 
$data = $helper->getReportMemo($_POST);
/* echo "<pre>";
print_r($data);
exit; */
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
				<div class="divTableCell">No</div>								
				<div class="divTableCell" style="width:200px">Company</div>
				<?php foreach($attribute as $key=>$v): ?>				
				<?php if($key == 'party')continue; ?>
				<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $v; ?></div>
				<?php endforeach;?>
			</div>	
			<div class="divTableBody">
	<?php 
		$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = $tta = $ttp = 0.0; 
		$i=1;
		foreach($data as $jData):
			if($report == 'purchase')
			{	
				$carat = ($jData['purchase_carat'] ==0) ? $jData['carat'] : $jData['purchase_carat'];	
				$pcs = ($jData['purchase_pcs'] ==0) ? $jData['pcs'] : $jData['purchase_pcs'];
				$tp = ($jData['purchase_price'] ==0) ? $jData['price'] : $jData['purchase_price'];
				$ta = ($jData['purchase_amount'] ==0) ? $jData['amount'] : $jData['purchase_amount'];
			}
			else
			{
				$carat = $jData['carat'];
				$pcs = $jData['pcs'];
				$tp = ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'];
				$ta = ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'];
			}
			$tpp += (float) $pcs;
			$tpc += (float) $carat;
			$ttp += (float) $tp;
			$tta += (float) $ta;
			$tc += (float) $jData['cost'];
			/* if($report != 'lab' && isset($jData['outward_parent']))
			{
				if($jData['outward_parent'] == 0)
				{
					$sku = $jData['sku'];
				}
				else
				{
					$parentData = $helper->getProductDetail ($jData['outward_parent']);					
					$sku = $parentData['sku'];
				}
			} */
			$sku = $jData['sku'];	
				?>
				
			<div class="divTableRow ">					
				<div class="divTableCell"><?php echo $i; ?></div>		
				<div class="divTableCell" style="width:200px"><?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
				<?php foreach($attribute as $key=>$v): 				if($key == 'party')continue; ?>
					<?php if($key =="sku"):?>
						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $sku;?></div>					
						<?php elseif($key =="pcs"):?>						
						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>">
						<?php echo $pcs;?>
						</div>	
						<?php elseif($key =="carat"):?>						
						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>">
						<?php echo $carat;?></div>						
						<?php elseif($key =="out_date"):?>						
						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>">
						<?php  $phpdate = strtotime( $jData['out_date'] );	
						echo  date( 'd-m-Y', $phpdate );?></div>											
						<?php elseif($key =="price"):?>						
						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>">
						<?php echo $tp; ?></div>	<?php elseif($key =="amount"):?>						
						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>">
						<?php echo $ta; ?></div>
					<?php else: ?>
					<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo (isset($jData[$key]))?$jData[$key]:'&nbsp;';?></div>
					<?php endif; ?>
				<?php endforeach;?>
			</div>
			<?php $i++;?>
		<?php  endforeach; ?>
			<div class="divTableRow">					
					<div class="divTableCell"></div>								
					<div class="divTableCell" style="width:200px"></div>
					
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell "></div>
					<div class="divTableCell width-50px"></div>
					<div class="divTableCell a-right">  <b><?php echo $tpp?></b> </div>
					<div class="divTableCell a-right "> <b> <?php echo $tpc?></b>  </div>
				<!--	<div class="divTableCell a-right "> <b> <?php echo $tc?></b> </div>-->
					<div class="divTableCell a-right ">  <b><?php echo round($tta/$tpc,4) ?></b></div>
					<div class="divTableCell a-right ">  <b><?php echo $tta?></b></div>
					<div class="divTableCell ">  </div>
					<div class="divTableCell">  </div>
					<div class="divTableCell ">  </div>	
					
				</div>
			</div>
		</div>
	</div>
<?php elseif($_POST['type'] == "party"): ?>	
	
	<div class="subform invenotry-cgrid company" style="overflow-x:hidden;">
		<div class="divTable" style="width:100%;">
			<div class="divTableHeading">
			   <div class="divTableCell" style="width:1%">No</div>
			   <?php foreach($reportHelper->getMemoParty() as $key=>$v): ?>				<?php if($key == 'party') : ?>
			   <div class="divTableCell" style="width:20%;"><?php echo $v; ?></div>
			   <?php elseif($key == 'tpp' || $key == 'terms' ): ?>					
			   <div class="divTableCell " style="width:5%;" ><?php echo $v; ?></div>
			   <?php else: ?>					
			   <div class="divTableCell <?php /*if(in_array($key,$width50))echo 'width-50px';*/ ?>" style="width:8.2%;"><?php echo $v; ?></div>
			   <?php endif; ?>
			   <?php endforeach;?>
			   <?php if($report =="memo" || $report =="lab" || $report =="sale" || $report =="export"):?>
			   <!-- <div class="divTableCell width-50px">Action</div> -->
			   <?php endif; ?>
			</div>	
		<div class="divTableBody" style="width:100%;">
			<?php 
			$tpp = $tpc =  $tp = $ta = 0.0; 
			$ftpp = $ftpc =  $ftp = $fta = 0.0; 
			$i=1;
			foreach($data as $jData):
		
				$tpp += (float) $jData['tpp'];			
				$tpc += (float) $jData['tpc'];						
				$ta += (float) $jData['ta'];		
			?>
				<div class="divTableRow">
				   <div class="divTableCell" style="width:1%">  <?php echo $i ?>  </div>
				   <div class="divTableCell" style="width:8.2%"> 
					  <?php if($report =="memo" || $report =="sale"):?>
					  <a onClick="loadRecord('outward',<?php echo $jData['id'] ?>)" style="cursor:pointer"><?php echo $jData['entryno'] ?></a>
					  <?php elseif($report =="purchase" || $report =="import" ): ?>
					  <a onClick="loadRecord('inward',<?php echo $jData['id'] ?>)" style="cursor:pointer"><?php echo $jData['entryno'] ?></a>	
					  <?php elseif($report =="lab"): ?>
					  <a onClick="loadRecord('lab',<?php echo $jData['id'] ?>)" style="cursor:pointer"><?php echo $jData['entryno'] ?></a>	
					  <?php endif; ?>
				   </div>
				   <div class="divTableCell" style="width:20%;"><?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
				   <?php foreach($reportHelper->getMemoParty()  as $key=>$v): if($key == 'party' || $key == 'entryno')continue; ?>				
				   <?php if($key == 'invoicedate'): ?>
				   <div class="divTableCell" style="width:8.2%">						
					  <?php 				
						 $phpdate = strtotime( $jData[$key] );							
						 echo  date( 'd-m-Y', $phpdate );?>
				   </div>
				   <?php elseif($key == 'duedate' ): ?>					
				   <div class="divTableCell" style="width:8.2%">						
					  <?php 					
						 if($jData['terms']=='' || $jData['terms'] ==0 )							
							$phpdate = strtotime( $jData['invoicedate'] );									
						 else							
							$phpdate = strtotime( $jData[$key] );												
							echo  date( 'd-m-Y', $phpdate );?>					
				   </div>
				   <?php elseif($key == 'tpp' || $key == 'terms' ): ?>					
				   <div class="divTableCell a-right" style="width:5%;" >
					  <?php echo (isset($jData[$key]))?$jData[$key]:'&nbsp;';?>
				   </div>
				   <?php elseif($key == 'tp'): ?>					
				   <div class="divTableCell" >
					  <?php echo round($jData['ta']/$jData['tpc'],2);?>
				   </div>
				   <?php else: ?>	
				   <div class="divTableCell " style="width:8.2%">
					  <?php echo (isset($jData[$key]))?$jData[$key]:'&nbsp;';?>
				   </div>
				   <?php endif; ?>
				   <?php endforeach;?>
				   <?php if($report =="memo" || $report =="lab" || $report =="sale" || $report =="export"):?>
				   <!-- <div class="divTableCell width-50px"><a class="ace-icon fa fa-pencil bigger-130" style="cursor:pointer;" href="<?php echo $daiUrl."module/outward/sale.php?id=".$jData['id']; ?>"> </a></div> -->
				   <?php endif; ?>
				</div>


		<?php $i++; endforeach; ?>		
			<div class="divTableRow">
		   <div class="divTableCell" style="width:1%"></div>
		   <?php foreach($reportHelper->getMemoParty() as $key=>$v): ?>				
		   <?php if($key == 'party') : ?>					
				<div class="divTableCell" style="width:20%;"></div>
		   <?php elseif($key == 'terms' ): ?>					
				<div class="divTableCell " style="width:5%;" ></div>
		   <?php elseif($key == 'tpp' ): ?>					
				<div class="divTableCell a-right" style="width:5%;" ><b><?php echo $tpp;?></b></div>
		   <?php elseif($key == 'tpc' ): ?>					
				<div class="divTableCell a-right" style="width:8.2%;" ><b><?php echo $tpc;?></b></div>
		   <?php elseif($key == 'tp' ): ?>					
				<div class="divTableCell a-right" style="width:8.2%;" ><b><?php echo number_format($ta/$tpc,4,".","") ?></b></div>
		   <?php elseif($key == 'final_amount' ): ?>					
				<div class="divTableCell a-right" style="width:8.2%;" ><b><?php echo $ta;?></b></div>
		   <?php else: ?>					
				<div class="divTableCell" style="width:8.2%;"></div>
		   <?php endif; ?>				
		   <?php endforeach;?>			
		</div>

				</div>			
		</div>
	</div>
<?php endif; ?>	
<?php else: ?>
<h4 style="text-align:center">OOPPSS !! There is no data found.</h4>
<?php endif;?>


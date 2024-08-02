<?php session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once('reportHelper.php');
$reportHelper  = new reportHelper();
include_once($daiDir.'Helper.php');
$helper  = new Helper();
$attribute = $reportHelper->getMemoPackage();
unset($attribute['rought_pcs']);							
unset($attribute['rought_carat']);							
$width50 = $helper->width50();
//$memo = $helper->getInventory('rmemo'); 
$data = $helper->getReportMemo($_POST);

include_once($daiDir.'module/party/partyModel.php');
$pmodel  = new partyModel();
$party = $pmodel->getOptionList();	
$i=1;
$report = $_POST['report'];
?>
<?php if(count($data)): ?>

<?php if($_POST['type']=="packet"): ?>
<div class="subform invenotry-cgrid ">
		<div class="divTable packet" >
			<div class="divTableHeading">				
				<div class="divTableCell">No</div>								<div class="divTableCell" style="width:200px">Company</div>
				<?php foreach($attribute as $key=>$v): ?>				<?php if($key == 'party')continue; ?>
				<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $v; ?></div>
				<?php endforeach;?>
			</div>	
			<div class="divTableBody">
	<?php 
		$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = 0.0; 
		$i=1;
		foreach($data as $jData):
			$polish_carat = ($jData['purchase_carat'] ==0) ? $jData['polish_carat'] : $jData['purchase_carat'];		$polish_pcs = ($jData['purchase_pcs'] ==0) ? $jData['polish_pcs'] : $jData['purchase_pcs'];				
			$trp += (float) $jData['rought_pcs'];
			$trc += (float) $jData['rought_carat'];
			$tpp += (float) $polish_pcs;
			$tpc += (float) $polish_carat;
			$tc += (float) $jData['cost'];
			$tp += (float) ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'];			$ta += (float) ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'];
				
			if($jData['outward_parent'] == 0)
			{
				$sku = $jData['sku'];
			}
			else
			{
				$parentData = $helper->getProductDetail ($jData['outward_parent']);					
				$sku = $parentData['sku'];
			}
				
				?>
				
			<div class="divTableRow ">					
				<div class="divTableCell"><?php echo $i; ?></div>		
				<div class="divTableCell" style="width:200px"><?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>
				<?php foreach($attribute as $key=>$v): 				if($key == 'party')continue; ?>
					<?php if($key =="sku"):?>
						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $sku;?></div>					<?php elseif($key =="polish_pcs"):?>						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $polish_pcs;?></div>											<?php elseif($key =="polish_carat"):?>						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $polish_carat;?></div>						<?php elseif($key =="out_date"):?>						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php  $phpdate = strtotime( $jData['out_date'] );						echo  date( 'd-m-Y', $phpdate );?></div>											<?php elseif($key =="price"):?>						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'] ?></div>						<?php elseif($key =="amount"):?>						<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'] ?></div>
					<?php else: ?>
					<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo (isset($jData[$key]))?$jData[$key]:'&nbsp;';?></div>
					<?php endif; ?>
				<?php endforeach;?>
			</div>
			<?php $i++;?>
		<?php  endforeach; ?>
			<div class="divTableRow">					
					<div class="divTableCell"></div>								<div class="divTableCell" style="width:200px"></div>
					
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell width-50px"></div>
					<div class="divTableCell "></div>
					<div class="divTableCell a-right width-50px">  <b><?php echo $tpp?></b> </div>
					<div class="divTableCell a-right "> <b> <?php echo $tpc?></b>  </div>
					<div class="divTableCell a-right "> <b> <?php echo $tc?></b> </div>
					<div class="divTableCell a-right ">  <b><?php echo $tp?></b></div>
					<div class="divTableCell a-right ">  <b><?php echo $ta?></b></div>
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
					<div class="divTableCell" style="width:20%;"><?php echo $v; ?></div>				<?php elseif($key == 'tpp' || $key == 'terms' ): ?>					<div class="divTableCell " style="width:5%;" ><?php echo $v; ?></div>					<?php else: ?>					<div class="divTableCell <?php /*if(in_array($key,$width50))echo 'width-50px';*/ ?>" style="width:8.2%;"><?php echo $v; ?></div>				<?php endif; ?>
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
			foreach($data as $jData):												$tpp += (float) $jData['tpp'];			$tpc += (float) $jData['tpc'];						$ta += (float) $jData['ta'];			?>
	
			<div class="divTableRow">	
				<div class="divTableCell" style="width:1%">  <?php echo $i ?>  </div>
				<div class="divTableCell" style="width:8.2%"> 
				<?php if($report =="memo" ||$report =="close_consign" ||$report =="close_export" || $report =="close_sale" ||$report =="close_memo" || $report =="lab" || $report =="sale" || $report =="export"):?>
					<a onClick="loadRecord('outward',<?php echo $jData['id'] ?>)" style="cursor:pointer"><?php echo $jData['entryno'] ?></a>
				<?php elseif($report =="purchase" || $report =="import" ): ?>
					<a onClick="loadRecord('inward',<?php echo $jData['id'] ?>)" style="cursor:pointer"><?php echo $jData['entryno'] ?></a>				
				<?php endif; ?>
				</div>
				<div class="divTableCell" style="width:20%;"><?php echo isset($party[$jData['party']])?$party[$jData['party']]:"";?></div>			
				<?php foreach($reportHelper->getMemoParty()  as $key=>$v): if($key == 'party' || $key == 'entryno')continue; ?>				<?php if($key == 'invoicedate'): ?>
					<div class="divTableCell" style="width:8.2%">						<?php 						$phpdate = strtotime( $jData[$key] );							echo  date( 'd-m-Y', $phpdate );						?>					</div>				<?php elseif($key == 'duedate' ): ?>					<div class="divTableCell" style="width:8.2%">						<?php 						if($jData['terms']=='' || $jData['terms'] ==0 )							$phpdate = strtotime( $jData['invoicedate'] );													else							$phpdate = strtotime( $jData[$key] );												echo  date( 'd-m-Y', $phpdate );						?>					</div>								<?php elseif($key == 'tpp' || $key == 'terms' ): ?>					<div class="divTableCell a-right" style="width:5%;" ><?php echo (isset($jData[$key]))?$jData[$key]:'&nbsp;';?></div>				<?php else: ?>					<div class="divTableCell " style="width:8.2%"><?php echo (isset($jData[$key]))?$jData[$key]:'&nbsp;';?></div>				<?php endif; ?>
				<?php endforeach;?>
				<?php if($report =="memo" || $report =="lab" || $report =="sale" || $report =="export"):?>
				<!-- <div class="divTableCell width-50px"><a class="ace-icon fa fa-pencil bigger-130" style="cursor:pointer;" href="<?php echo $daiUrl."module/outward/sale.php?id=".$jData['id']; ?>"> </a></div> -->
				<?php endif; ?>
			</div>
		<?php $i++; endforeach; ?>		
			<div class="divTableRow">								<div class="divTableCell" style="width:1%"></div>								<?php foreach($reportHelper->getMemoParty() as $key=>$v): ?>				<?php if($key == 'party') : ?>					<div class="divTableCell" style="width:20%;"></div>				<?php elseif($key == 'terms' ): ?>					<div class="divTableCell " style="width:5%;" ></div>				<?php elseif($key == 'tpp' ): ?>					<div class="divTableCell " style="width:5%;" ><b><?php echo $tpp;?></b></div>							<?php elseif($key == 'tpc' ): ?>					<div class="divTableCell " style="width:8.2%;" ><b><?php echo $tpc;?></b></div>									<?php elseif($key == 'tp' ): ?>					<div class="divTableCell " style="width:8.2%;" ><b><?php echo number_format($ta/$tpc,2,".","") ?></b></div>								<?php elseif($key == 'ta' ): ?>					<div class="divTableCell " style="width:8.2%;" ><b><?php echo $ta;?></b></div>									<?php else: ?>					<div class="divTableCell" style="width:8.2%;"></div>				<?php endif; ?>				<?php endforeach;?>			</div>				</div>			
		</div>
	</div>
<?php endif; ?>	
<?php else: ?>
<h4 style="text-align:center">OOPPSS !! There is no data found.</h4>
<?php endif;?>


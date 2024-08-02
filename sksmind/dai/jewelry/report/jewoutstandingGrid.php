<?php session_start();include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once('reportHelper.php');
$reportHelper  = new reportHelper($cn);
include_once($daiDir.'Helper.php');
$helper  = new Helper($cn);
$attribute = $reportHelper->getJewMemoParty();	
	$attribute['paid_amount'] = "Paid Amt.";			
$attribute['due_amount'] = "Due Amt.";	//print_r($attribute);

$width50 = array('tpp','tpc','terms','part');
$width70 = array('date','duedate','invoicedate','invoiceno');
$right = array('tpp','tpc','tp','ta','paid_amount','due_amount');
$data = $helper->getReportMemoJew($_POST);
include_once($daiDir.'jewelry/party/partyModel.php');
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();	
$i=1;
//$report = $_POST['report'];


?>

<?php if(count($data)): ?>
	<div class="subform invenotry-cgrid " style="overflow-x:hidden;">
		<div class="divTable" style="width:1320px;">
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
		<div class="divTableBody">
			<?php 
			$tpp = $tpc =  $tp = $ta = 0.0; 
			$ftpp = $ftpc =  $ftp = $fta = 0.0; 
			$i=1;
			foreach($data as $jData): 
				if($jData['due_amount'] <= 0)
					continue;									
			?>
	
			<div class="divTableRow  <?php if($jData['due_amount'] == "0.00"):?> infobox-dark infobox-green <?php endif;?>">	
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
			</div>
		</div>
	</div>

<?php else: ?>
<h4 style="text-align:center">OOPPSS!! No Outstanding Payment found.</h4>
<?php endif;?>
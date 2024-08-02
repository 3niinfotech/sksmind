<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'jewelry/party/partyModel.php');
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$attribute = $helper->getAttribute();							
$design = $jhelper->getAllDesign();
//$memo = $helper->getInventory('rmemo'); 
$pid = '';//$_POST['id'];
$data = $jhelper->getJobMemo($_POST,'jewelry');
if($pid =='')
	$pid =0 ;
/* echo"<pre>";
print_r($data);
echo"</pre>"; */
$i=1;
?>
<?php if(count($data)): ?>
<?php foreach($data as $k=>$memo): 

//$main_stone =  $memo['main_stone'];
//$side_stone =  $memo['side_stone'];
//$collet_stone = $memo['collet_stone'];
?>


<div class="col-xs-12 col-sm-12 form-horizontal jewelryMaking" style="<?php echo ($memo['job'] =='collet')?'background:#e8f1f8 !important':''?>" >
<div class="button-group">
	<div class="cgrid-header green" style="width:50%;">
		<div class="color-total"> Job No:  <?php echo $memo['entryno'] ?> &nbsp;&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp;   <span class="blue" ><b><?php echo isset($party[$memo['party']])?$party[$memo['party']]:''; ?></b></span> &nbsp;&nbsp;&nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Date :</b> &nbsp;&nbsp;<?php $phpdate = strtotime( $memo['date'] ); echo  date( 'd-m-Y', $phpdate );?> &nbsp;&nbsp; |</div>		
	</div>	
	
	
	<!-- <button class="btn grid-btn btn-sm btn-danger" type="button" onClick="closeMemo(<?php echo $k ?>)" style="float:right">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close Memo
	</button> 
	<?php if( !$memo['is_returned']): ?>
	<a onclick="deleteJob(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-danger" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Delete
	</a>
		<?php if($memo['job'] =='collet'): ?>
	<button id="return-<?php echo $k ?>"  class="btn grid-btn btn-sm btn-success" type="button" onClick="returnMemo(<?php echo $k ?>,<?php echo $pid; ?>)" style="float:right">
		<i class="ace-icon fa fa-reply bigger-110"></i>
		Return
	</button>
		<?php else: ?>
		<button id="return-<?php echo $k ?>"  class="btn grid-btn btn-sm btn-success" type="button" onClick="returnjewMemo(<?php echo $k ?>,<?php echo $pid; ?>)" style="float:right">
		<i class="ace-icon fa fa-reply bigger-110"></i>
		Return
		</button>
	<?php endif;?>
	<?php if($memo['job'] =='collet'): ?>
	<a onclick="editCollet(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-success" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Edit
	</a>
	<?php else: ?>
	<a onclick="editJewelry(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-success" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Edit
	</a>
	<?php endif;?>
	<?php endif;?>-->
	
	<a href="<?php echo $mainUrl.'pdf/file/jobwork.php?id='.$k.'&type=original' ?>" target="_blank" id="invoice-<?php echo $k ?>" class="btn grid-btn btn-sm btn-info" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Print Original
	</a>
	<a href="<?php echo $mainUrl.'pdf/file/jobwork.php?id='.$k.'&type=customer' ?>" target="_blank" id="invoice-<?php echo $k ?>" class="btn grid-btn btn-sm btn-info" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Print Customer
	</a>
</div>
<?php if($memo['job'] =='collet'): ?>

<div class="col-xs-12 col-sm-12 jewelry-job">

<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Used Stone</h3>
	<div class="subform mainstone invenotry-cgrid main-grid">
		<div class="divTable" style="width:3200px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>
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
				<div class="divTableCell">G Color</div>
				<div  class="divTableCell">G Carat</div>
				<div  class="divTableCell">Gross WT</div>
				<div  class="divTableCell">NET WT</div>
				<div  class="divTableCell">PG WT</div>
				<div  class="divTableCell">Rate</div>
				<div  class="divTableCell">Amount</div>
				<div  class="divTableCell">Other Code</div>
				<div  class="divTableCell">Other Amount</div>
				<div  class="divTableCell">Labour Rate</div>
				<div  class="divTableCell">Labour Amount</div>
				<div  class="divTableCell">Total Amount</div>
				
			</div>	
			<div class="divTableBody" >
				<?php 
					$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
					$j = 0 ;
					foreach($memo['record'] as $k=>$mainData):
					$i = 0 ;$j++;
					?>
					
					<div class="divTableCell" style="width:100% !important;background:#000;font-weight: bold;color:#fff">
					<?php echo "(".$j.")";?><?php if( !$mainData['is_returned']): ?>
					<a onclick="editCollet(<?php echo $k;?>,<?php echo $pid; ?>)" style="margin-left:10px;" href="javascript:void(0);"  class="green"><i class="ace-icon fa fa-pencil bigger-130"></i> Edit</a>
					<a href="javascript:void(0);" id="return-<?php echo $k ?>" style="margin-left:10px;" class="green" onClick="returnMemo(<?php echo $k ?>,<?php echo $pid; ?>)"><i class="ace-icon fa fa-reply bigger-110"></i> Return</a>
					<a onclick="deleteJob(<?php echo $k;?>,<?php echo $pid; ?>)" style="margin-left:10px;" href="javascript:void(0);" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i> Delete</a>
					<?php else: ?>
						<b>&nbsp;&nbsp;Job Returned</b>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?php if($mainData['j_del'] == 1):?>
						<a onclick="deleteJob(<?php echo $k;?>,<?php echo $pid; ?>)" style="margin-left:10px;" href="javascript:void(0);" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i> Delete </a>
						&nbsp;&nbsp;
						<a onclick="editColletReturn(<?php echo $k;?>,<?php echo $pid; ?>)" style="margin-left:10px;" href="javascript:void(0);"  class="green"><i class="ace-icon fa fa-pencil bigger-130"></i> Edit</a>
						&nbsp;&nbsp;
						<?php else: ?>
						<b>You can't delete it because Collet(<?php echo $mainData['sku'] ?>) is on <?php echo $mainData['j_status'] ?></b>
						<?php endif; ?>
					<?php endif; ?>
					</div>
					
					<?php   foreach($mainData['main_stone'] as $value):
					    
					$i++;
					$class ="";
					//$value = $imodel->getDetail($id);
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?>
					</div>
					<!--<div class="divTableCell" style="width:195px">
					<?php if( !$mainData['is_returned']): ?>
					<a onclick="editCollet(<?php echo $k;?>,<?php echo $pid; ?>)" style="margin-left:10px;" href="javascript:void(0);"  class="green"><i class="ace-icon fa fa-pencil bigger-130"></i> Edit</a>
					<a  href="javascript:void(0);" id="return-<?php echo $k ?>" style="margin-left:10px;" class="green" onClick="returnMemo(<?php echo $k ?>,<?php echo $pid; ?>)"><i class="ace-icon fa fa-reply bigger-110"></i> Return</a>
					<a onclick="deleteJob(<?php echo $k;?>,<?php echo $pid; ?>)" style="margin-left:10px;" href="javascript:void(0);" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i> Delete</a>
					<?php else: ?>
						<b>Job Returned</b>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?php if($mainData['j_del'] == 1):?>
						<a onclick="deleteJob(<?php echo $k;?>,<?php echo $pid; ?>)" style="margin-left:10px;" href="javascript:void(0);" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i> Delete </a>
						<?php else: ?>
						<b>You can't delete it because Collet(<?php echo $mainData['sku'] ?>) is on <?php echo $mainData['j_status'] ?></b>
						<?php endif; ?>
					<?php endif; ?>
					</div>-->
					<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell a-right"><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell a-right "><?php echo $value['price'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['amount'];?></div>
					<div class="divTableCell  "><?php echo $value['lab'];?></div>
					<div class="divTableCell  "><?php echo $value['igi_code'];?></div>			
					<div class="divTableCell  "><?php echo $value['igi_color'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['igi_clarity'];?></div>		
					<div class="divTableCell a-right "><?php echo $value['igi_amount'];?></div>
					<div class="divTableCell"><?php echo $value['collet_color'];?></div>		
					<div class="divTableCell a-right"><?php echo $value['collet_kt'];?></div>			
					<div class="divTableCell a-right"><?php echo $value['gross_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['net_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['pg_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['collet_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $value['collet_amount'];?></div>
					<div class="divTableCell "><?php echo $value['other_code'];?></div>
					<div class="divTableCell a-right"><?php echo $value['other_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $value['labour_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $value['labour_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $value['total_amount'];?></div>				
					
				</div>
			<?php 
			$tpcs = (int)$tpcs +  (int)$value['pcs'];
			$tcarat = (float)$tcarat +  (float)$value['carat'];
			$tcost = (float)$tcost +  (float)$value['cost'];
			$tprice = (float)$tprice +  (float)$value['price'];
			$tamount = (float)$tamount +  (float)$value['amount'];
			endforeach; 
			endforeach;
			?>
			
			</div>
		</div>
	</div>
</div>
<!--<div class="form-group col-sm-2" style="margin-right:10px;margin-top:100px;">
	<p><b>Total Carat :</b> <span id="total_carat"><?php echo $tcarat; ?></span> </p>
	<p><b>Total Amount :</b> <span id="total_amount"><?php echo $tamount; ?></span> </p>
</div>-->
</div>

<?php else: ?>

<div class="col-xs-12 col-sm-12 jewelry-job">
<div class="form-group col-sm-12" style="margin-right:10px;">
	<h3>Used Stone</h3>
	<div class="subform mainstone invenotry-cgrid main-grid">
		<div class="divTable" style="width:3235px;" >
			<div class="divTableHeading" >
				
				<div class="divTableCell" style="width:50px !important">
					<label class="pos-rel">
						
					</label>
				</div>
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
				<div  class="divTableCell">G Color</div>
				<div  class="divTableCell">G Carat</div>
				<div  class="divTableCell">Gross WT</div>
				<div  class="divTableCell">NET WT</div>
				<div  class="divTableCell">PG WT</div>
				<div  class="divTableCell">Rate</div>
				<div  class="divTableCell">Amount</div>
				<div  class="divTableCell">Other Code</div>
				<div  class="divTableCell">Other Amount</div>
				<div  class="divTableCell">Labour Rate</div>
				<div  class="divTableCell">Labour Amount</div>
				<div  class="divTableCell">Total Amount</div>
				
			</div>	
			<div class="divTableBody" >
				<?php 
					$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
					$j = 0 ;
					foreach($memo['record'] as $k=>$mainData):
					$i = 0 ;$j++;
					?>
					
					<div class="divTableCell" style="width:100% !important;background:#000;font-weight: bold;color:#fff">
					<?php echo "(".$j.")";?><?php if( !$mainData['is_returned']): ?>
					<a onclick="editJewelry(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);" style="margin-left:10px;" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i> Edit</a>
					<a  href="javascript:void(0);" id="return-<?php echo $k ?>" style="margin-left:10px;" class="green" onClick="returnjewMemo(<?php echo $k ?>,<?php echo $pid; ?>)"><i class="ace-icon fa fa-reply bigger-110"></i> Return</a>
					<a onclick="deleteJob(<?php echo $k;?>,<?php echo $pid; ?>)" style="margin-left:10px;" href="javascript:void(0);" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i> Delete</a>
					<?php else: ?>
						<b>&nbsp;&nbsp;Job Returned</b>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?php if($mainData['j_del'] == 1):?>
						<a onclick="deleteJob(<?php echo $k;?>,<?php echo $pid; ?>)" style="margin-left:10px;" href="javascript:void(0);" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i> Delete </a> &nbsp;&nbsp;(Jewelry : <?php echo $mainData['sku'] ?>)
						<?php else: ?>
						<b>You can't delete it because jewlery(<?php echo $mainData['sku'] ?>) is on <?php echo $mainData['j_status'] ?></b>
						<?php endif; ?>
					<?php endif; ?>
					</div>
					
					<?php   foreach($mainData['main_stone'] as $value):
					    
					$i++;
					$class ="";
					//$value = $imodel->getDetail($id);
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?>
					</div>
					<!--<div class="divTableCell" style="width:195px">
					<?php if( !$mainData['is_returned']): ?>
					<a onclick="editJewelry(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);" style="margin-left:10px;" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i> Edit</a>
					<a  href="javascript:void(0);" id="return-<?php echo $k ?>" style="margin-left:10px;" class="green" onClick="returnjewMemo(<?php echo $k ?>,<?php echo $pid; ?>)"><i class="ace-icon fa fa-reply bigger-110"></i> Return</a>
					<a onclick="deleteJob(<?php echo $k;?>,<?php echo $pid; ?>)" style="margin-left:10px;" href="javascript:void(0);" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i> Delete</a>
					<?php else: ?>
						<b>Job Returned</b>
					<?php endif; ?>
					</div>-->
					<div class="divTableCell"><b>Main</b></div>	
					<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell a-right"><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell a-right "><?php echo $value['price'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['amount'];?></div>	
					<div class="divTableCell  "><?php echo $value['lab'];?></div>
					<div class="divTableCell  "><?php echo $value['igi_code'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['igi_color'];?></div>			
					<div class="divTableCell  a-right"><?php echo $value['igi_clarity'];?></div>		
					<div class="divTableCell a-right "><?php echo $value['igi_amount'];?></div>
					<div class="divTableCell"><?php echo $value['collet_color'];?></div>		
					<div class="divTableCell a-right"><?php echo $value['collet_kt'];?></div>			
					<div class="divTableCell a-right"><?php echo $value['gross_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['net_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['pg_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['collet_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $value['collet_amount'];?></div>
					<div class="divTableCell "><?php echo $value['other_code'];?></div>
					<div class="divTableCell a-right"><?php echo $value['other_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $value['labour_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $value['labour_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $value['total_amount'];?></div>				
					
				</div>
				<?php 
			endforeach; 
			//endforeach;
			?>
			<?php 
					$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
					//foreach($memo['record'] as $k=>$mainData):
					   foreach($mainData['collet_stone'] as $value):
					    
					$i++;
					$class ="";
					//$value = $imodel->getDetail($id);
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?>
					</div>
					<!--<div class="divTableCell" style="width:195px">
					<?php if( !$mainData['is_returned']): ?>
					<a onclick="editJewelry(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="green" style="margin-left:10px;"><i class="ace-icon fa fa-pencil bigger-130"></i> Edit</a>
					<a  href="javascript:void(0);" style="margin-left:10px;" id="return-<?php echo $k ?>"  class="green" onClick="returnjewMemo(<?php echo $k ?>,<?php echo $pid; ?>)"><i class="ace-icon fa fa-reply bigger-110"></i> Return</a>
					<a onclick="deleteJob(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);" style="margin-left:10px;" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i> Delete</a>
					<?php else: ?>
						<b>Job Returned</b>
					<?php endif; ?>
					</div>-->
					<div class="divTableCell"><b>Collet</b></div>	
					<div class="divTableCell  "><?php echo $value['sku'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell a-right"><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell a-right "><?php echo $value['price'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['amount'];?></div>
					<div class="divTableCell  "><?php echo $value['lab'];?></div>
					<div class="divTableCell  "><?php echo $value['igi_code'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['igi_color'];?></div>			
					<div class="divTableCell  a-right"><?php echo $value['igi_clarity'];?></div>		
					<div class="divTableCell a-right "><?php echo $value['igi_amount'];?></div>
					<div class="divTableCell"><?php echo $value['collet_color'];?></div>		
					<div class="divTableCell a-right"><?php echo $value['collet_kt'];?></div>			
					<div class="divTableCell a-right"><?php echo $value['gross_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['net_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['pg_weight'];?></div>
					<div class="divTableCell a-right"><?php echo $value['collet_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $value['collet_amount'];?></div>
					<div class="divTableCell "><?php echo $value['other_code'];?></div>
					<div class="divTableCell a-right"><?php echo $value['other_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $value['labour_rate'];?></div>
					<div class="divTableCell a-right"><?php echo $value['labour_amount'];?></div>
					<div class="divTableCell a-right"><?php echo $value['total_amount'];?></div>				
					
				</div>
				<?php 
			endforeach;?>
			<?php 
					$tpcs = $tcarat = $tcost = $tprice = $tamount = 0.00;
					//foreach($memo['record'] as $k=>$mainData):
					   foreach($mainData['side_stone'] as $value):
					    if($value['outward_parent'] == 0)
					{
						$sku = $value['sku'];
					}
					else
					{
						$parentData = $jhelper->getSideProductDetail($value['outward_parent']);					
						$sku = $parentData['sku'];
					}
					$i++;
					$class ="";
					//$value = $imodel->getDetail($id);
					?>
			
				
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell " style="width:50px !important">
						<?php echo $i;?>
					</div>
					<!--<div class="divTableCell" style="width:195px">
					<?php if( !$mainData['is_returned']): ?>
					<a onclick="editJewelry(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="green" style="margin-left:10px;"><i class="ace-icon fa fa-pencil bigger-130"></i> Edit</a>
					<a  href="javascript:void(0);" style="margin-left:10px;" id="return-<?php echo $k ?>"  class="green" onClick="returnjewMemo(<?php echo $k ?>,<?php echo $pid; ?>)"><i class="ace-icon fa fa-reply bigger-110"></i> Return</a>
					<a onclick="deleteJob(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);" style="margin-left:10px;" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i> Delete</a>
					<?php else: ?>
						<b>Job Returned</b>
					<?php endif; ?>
					</div>-->
					<div class="divTableCell"><b>Side</b></div>	
					<div class="divTableCell  "><?php echo $sku;?></div>
					<div class="divTableCell  a-right"><?php echo $value['pcs'];?></div>
					<div class="divTableCell  a-right"><?php echo $value['carat'];?></div>				
					<div class="divTableCell  "><?php echo $value['shape'];?></div>				
					<div class="divTableCell a-right"><?php echo $value['clarity'];?></div>				
					<div class="divTableCell  "><?php echo $value['color'];?></div>				
					<div class="divTableCell a-right "><?php echo $value['price'];?></div>			
					<div class="divTableCell a-right "><?php echo $value['amount'];?></div>
					<div class="divTableCell  "></div>
					<div class="divTableCell  "></div>			
					<div class="divTableCell a-right "></div>			
					<div class="divTableCell  a-right"></div>		
					<div class="divTableCell a-right "></div>
					<div class="divTableCell"></div>		
					<div class="divTableCell a-right"></div>			
					<div class="divTableCell a-right"></div>
					<div class="divTableCell a-right"></div>
					<div class="divTableCell a-right"></div>
					<div class="divTableCell a-right"></div>
					<div class="divTableCell a-right"></div>
					<div class="divTableCell "></div>
					<div class="divTableCell a-right"></div>
					<div class="divTableCell a-right"></div>
					<div class="divTableCell a-right"></div>
					<div class="divTableCell a-right"></div>				
					
				</div>
			<?php endforeach;?>
			<?php endforeach;?>
			</div>
		</div>
	</div>
</div>

</div>
<?php endif; ?>
<!--<div class="col-sm-12">
	<div class="cgrid-header green">
		<div class="color-total"> <b>Gold :</b>  <span class="blue" ><?php echo $memo['gold'] ?></span> &nbsp;&nbsp;&nbsp;&nbsp;</div>
		<div class="color-total"> <b>Gold Color:</b>  <span class="blue" ><?php echo $memo['gold_color'] ?></span> </div>
		<div class="color-total"> <b>New WT:</b>  <span class="blue" ><?php echo $memo['net_weight'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> </div>
		<div class="color-total"> <b>Rate</b>  <span class="blue" ><?php echo $memo['rate'] ?></span> </div>
		<div class="color-total"> <b>Amount</b>  <span class="blue" ><?php echo $memo['amount'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> </div>
		<div class="color-total"> <b>Total Amount:</b>  <span class="blue" ><?php echo $memo['total_amount'] ?></span> </div>
	</div>	
</div>-->	
</div>
<div style="clear:both"></div>
<br><br>

<?php $i++; endforeach; ?>
<?php else: ?>
<h4 style="text-align:center">No Data found for this Party.</h4>
<?php endif;?>
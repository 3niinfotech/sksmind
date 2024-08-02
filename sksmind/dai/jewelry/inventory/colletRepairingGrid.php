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
$data = $jhelper->getJobMemo($_POST,'collet_repair');
if($pid =='')
	$pid =0 ;
//echo"<pre>";
//print_r($data);
//echo"</pre>";
$i=1;
?>
<?php if(count($data)): ?>
<?php foreach($data as $k=>$memo): 

?>


<div class="col-xs-12 col-sm-12 form-horizontal">
<div class="button-group">
	<div class="cgrid-header green">
		<div class="color-total"> Job No:  <?php echo $memo['entryno'] ?> &nbsp;&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp;   <span class="blue" ><b><?php echo isset($party[$memo['party']])?$party[$memo['party']]:''; ?></b></span> &nbsp;&nbsp;&nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Date :</b> &nbsp;&nbsp;<?php $phpdate = strtotime( $memo['date'] ); echo  date( 'd-m-Y', $phpdate );?> &nbsp;&nbsp; |</div>		
	</div>	
	
	
	<!-- <button class="btn grid-btn btn-sm btn-danger" type="button" onClick="closeMemo(<?php echo $k ?>)" style="float:right">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close Memo
	</button> -->
	<a onclick="deleteRepair(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-danger" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Delete
	</a>
	<a onclick="editSale(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-success" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Edit
	</a>
	<?php if( !$memo['is_returned']): ?>
	<button id="return-<?php echo $k ?>"  class="btn grid-btn btn-sm btn-success" type="button" onClick="returnMemo(<?php echo $k ?>,<?php echo $pid; ?>)" style="float:right">
		<i class="ace-icon fa fa-reply bigger-110"></i>
		Return
	</button>
	<?php endif;?>
	
	<a href="<?php echo $mainUrl.'pdf/file/jobwork.php?id='.$k ?>" target="_blank" id="invoice-<?php echo $k ?>" class="btn grid-btn btn-sm btn-info" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Print Collet
	</a>
</div>

<div class="col-sm-7">
	<div class="subform invenotry-cgrid mform-<?php echo $k?>">
		<div class="divTable" style="width:700px">
			<div class="divTableHeading">
				<div class="divTableCell" style="width:100px !important">SKU</div>
				<div class="divTableCell " style="width:50px !important">Pcs</div>
				<div class="divTableCell " style="width:50px !important">Carat</div>				
				<div class="divTableCell " style="width:70px !important">Price</div>
				<div class="divTableCell " style="width:90px !important">Amount</div>
				<div class="divTableCell " style="width:70px !important">Clarity</div>
				<div class="divTableCell " style="width:90px !important">Color</div>				
				<div class="divTableCell " style="width:90px !important">Shape </div>				
			</div>	
			<div class="outward divTableBody">
				<?php 
				
				$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = 0.0; 
				//$mainStone = explode(",",$memo['main_stone']);
				
				//foreach($memo['products'] as $jData):	
				foreach($memo['record'] as $k=>$mainData):
					   foreach($mainData['main_stone'] as $jData):
				
				//$sprice = ($value['sell_price'] ==0)?$value['price']:$value['sell_price'];
				//	$samount = ($value['sell_amount'] ==0)?$value['amount']:$value['sell_amount'];
					
					$sprice = $jData['price'];
					$samount = $jData['amount'];
				$tpp += (float) $jData['pcs'];
				$tpc += (float) $jData['carat'];
				$tc += (float) $jData['cost'];
				$tp += (float) $sprice;
				$ta += (float) $samount;

				$class="";
				$sku = $jData['sku'];
				
				?>
				
				<div class="divTableRow <?php echo $class;?>">					
					<div class="divTableCell" style="width:100px !important">  <?php echo $sku; ?> </div>
					<div class="divTableCell  a-right " style="width:50px !important">  <?php echo $jData['pcs']?> </div>
					<div class="divTableCell  a-right " style="width:50px !important">  <?php echo $jData['carat']?>  </div>					
					<div class="divTableCell  a-right " style="width:70px !important">  <?php echo $sprice ?></div>
					<div class="divTableCell  a-right  amount" style="width:90px !important">  <?php echo $samount ?></div>
					<div class="divTableCell " style="width:70px !important">  <?php echo $jData['clarity']?> </div>
					<div class="divTableCell" style="width:90px !important">  <?php echo $jData['color']?> </div>
					<div class="divTableCell " style="width:90px !important">  <?php echo $jData['shape']?> </div>
					
				</div>
				<?php endforeach;?>
				<?php endforeach;?>
				<div class="divTableRow">					
					<div class="divTableCell" style="width:100px !important"> <b>Total : <?php //echo count($memo['products']) ?></b></div>
					<div class="divTableCell a-right " style="width:50px !important" >  <b><?php echo $tpp?></b> </div>
					<div class="divTableCell a-right " style="width:50px !important"> <b> <?php echo $tpc?></b>  </div>					
					<div class="divTableCell a-right " style="width:70px !important">  <b><?php echo $tp?></b></div>
					<div class="divTableCell a-right " style="width:90px !important">  <b><?php echo $ta?></b></div>
					<div class="divTableCell " style="width:70px !important">  </div>
					<div class="divTableCell" style="width:90px !important">  </div>
					<div class="divTableCell " style="width:90px !important">  </div>	
					
				</div>
			</div>
		</div>
	</div>
</div>
<!--<div class="col-sm-5">
	<div class="cgrid-header green">
		<div class="color-total"> <b>Gold :</b>  <span class="blue" ><?php echo $memo['gold'] ?></span> &nbsp;&nbsp;&nbsp;&nbsp;</div>
		<div class="color-total"> <b>Gold Color:</b>  <span class="blue" ><?php echo $memo['gold_color'] ?></span> </div><br><br>
		<div class="color-total"> <b>Gold Carat:</b>  <span class="blue" ><?php echo $memo['gold_carat'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> </div>
		<div class="color-total"> <b>Gold Price:</b>  <span class="blue" ><?php echo $memo['gold_price'] ?></span> </div><br><br>
		<div class="color-total"> <b>Labour Charge:</b>  <span class="blue" ><?php echo $memo['labour_charge'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> </div>
		<div class="color-total"> <b>Handling Charge:</b>  <span class="blue" ><?php echo $memo['handling_charge'] ?></span> </div>
	</div>	
</div>	-->
</div>
<div style="clear:both"></div>
<br><br>

<?php $i++; endforeach; ?>
<?php else: ?>
<h4 style="text-align:center">No Data found for this Party.</h4>
<?php endif;?>
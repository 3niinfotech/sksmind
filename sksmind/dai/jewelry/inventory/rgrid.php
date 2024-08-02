<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'module/party/partyModel.php');
$pmodel  = new partyModel($cn);
$party = $pmodel->getOptionList();
include_once($daiDir.'Helper.php');
include_once($daiDir.'jHelper.php');
$helper  = new Helper($cn);
$jhelper  = new jHelper($cn);
$attribute = $helper->getAttribute();							
$design = $jhelper->getAllDesign();
//$memo = $helper->getInventory('rmemo'); 
$pid = $_POST['id'];
$data = $jhelper->getJobRepair($pid);
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
	<button id="return-<?php echo $k ?>"  disabled class="btn grid-btn btn-sm btn-success" type="button" onClick="returnMemo(<?php echo $k ?>,<?php echo $pid; ?>)" style="float:right">
		<i class="ace-icon fa fa-reply bigger-110"></i>
		Return
	</button>
	<?php endif;?>
	
	<a href="<?php echo $mainUrl.'pdf/file/repair.php?id='.$k ?>" target="_blank" id="invoice-<?php echo $k ?>" class="btn grid-btn btn-sm btn-info" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Print Memo
	</a>
</div>

<div class="col-sm-12">
	<div class="subform invenotry-cgrid mform-<?php echo $k?>">
		<div class="divTable" style="width:1000px">
			<div class="divTableHeading">
				<div class="divTableCell">
					<label class="pos-rel">
						<input class="ace" onclick="allCheck(this,<?php echo $k?>)" type="checkbox">
						<span class="lbl"></span>
					</label>
				</div>	
				<div class="divTableCell" style="width:150px !important">SKU</div>
				<div class="divTableCell " style="width:150px !important">Type</div>
				<div class="divTableCell " style="width:150px !important">Gold</div>				
				<div class="divTableCell " style="width:50px !important">Carat</div>
				<div class="divTableCell " style="width:50px !important">Gram</div>
				<div class="divTableCell " style="width:80px !important">Gross Cts</div>				
			</div>	
			<div class="outward divTableBody">
				<?php 
				
				$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = 0.0; 
				//$mainStone = explode(",",$memo['main_stone']);
				//$sideStone = explode(",",$memo['side_stone']);
				foreach($memo['products'] as $jData):	
				
				$class="";
				$sku = $jData['sku'];
				
				?>
				
				<div class="divTableRow <?php echo $class;?>">	
					<div class="divTableCell">
					<label class="pos-rel">
						<input name="products" value="<?php echo $jData['id']?>" class="ace" onclick="totalSelected(this,<?php echo $k; ?>)" type="checkbox">
						<span class="lbl"></span>
					</label>
					</div>
					<div class="divTableCell" style="width:150px !important">  <?php echo $sku; ?> </div>
					<div class="divTableCell  " style="width:150px !important">  <?php echo $jData['jew_type']?> </div>
					<div class="divTableCell   " style="width:150px !important">  <?php echo $jData['gold_color']?>  </div>					
					<div class="divTableCell  a-right " style="width:50px !important">  <?php echo $jData['total_carat'] ?></div>
					<div class="divTableCell  a-right  amount" style="width:50px !important">  <?php echo $jData['gold_gram'] ?></div>
					<div class="divTableCell a-right" style="width:80px !important">  <?php echo $jData['gross_cts']?> </div>
						
				</div>
				<?php endforeach;?>				
				
			</div>
		</div>
	</div>
</div>
	
</div>
<div style="clear:both"></div>
<br><br>

<?php $i++; endforeach; ?>
<?php else: ?>
<h4 style="text-align:center">No Data found for this Party.</h4>
<?php endif;?>
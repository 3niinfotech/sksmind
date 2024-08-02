<?php 
session_start();

include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 
include_once($daiDir.'Helper.php');
$helper  = new Helper($cn);
$attribute = $helper->getAttribute();							
$width50 = $helper->width50();
//$memo = $helper->getInventory('rmemo'); 
$pid = $_POST['id'];
$data = $helper->getInwardPartyMemo($pid,'memo');
//echo"<pre>";
//print_r($data);
//echo"</pre>";
if($pid =='')
	$pid =0 ;

$i=1;
?>
<?php if(count($data)): ?>
<?php foreach($data as $k=>$memo): 


?>

<form class="form-horizontal" method="POST" role="form" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="sendToLab" />
<div class="button-group">
	<div class="cgrid-header green">
		<div class="color-total"> <b><?php echo ucfirst($memo['inward_type']) ?> : </b> &nbsp;&nbsp;<?php echo $i; ?> (<?php echo $memo['entryno'] ?>) &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Date :</b> &nbsp;&nbsp;<?php $phpdate = strtotime( $memo['date'] ); echo  date( 'd-m-Y', $phpdate );?> &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Total Record :</b> &nbsp;&nbsp;<?php echo count($memo['products']) ?> &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Selected :</b> &nbsp;&nbsp;<span id="selected-row-<?php echo $k ?>">0</span> &nbsp;&nbsp; |</div>
	</div>	


	<a onclick="deleteSale(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-danger" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Delete
	</a>
	<a onclick="editSale(<?php echo $k;?>,<?php echo $pid; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-success" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Edit
	</a>
	<button id="return-<?php echo $k ?>" disabled class="btn grid-btn btn-sm btn-success" type="button" onClick="returnMemo(<?php echo $k ?>)" style="float:right">
		<i class="ace-icon fa fa-reply bigger-110"></i>
		Return
	</button>
	
	<button id="sale-<?php echo $k ?>" disabled class="btn grid-btn btn-sm btn-success" type="button" onClick="memoToSale(<?php echo $k;?>,<?php echo $pid; ?>);" style="float:right">
		<i class="ace-icon fa fa-reply bigger-110"></i>
		Purchase
	</button>
	

	
</div>

	<div class="subform invenotry-cgrid mform-<?php echo $k?>">
		<div class="divTable" >
			<div class="divTableHeading">
				
				<div class="divTableCell"><label class="pos-rel">
						<input class="ace" onclick="allCheck(this,<?php echo $k?>)" type="checkbox">
						<span class="lbl"></span>
					</label></div>				
				<div class="divTableCell">Mfg. code</div>
				<div class="divTableCell">D. No.</div>
				<div class="divTableCell">SKU</div>
				<div class="divTableCell width-50px">R.Pcs</div>
				<div class="divTableCell width-50px">R.Carat</div>		
				<div class="divTableCell width-50px">P.Pcs</div>
				<div class="divTableCell width-50px">P.Carat</div>
				<div class="divTableCell width-70px">Cost</div>		
				<div class="divTableCell ">Price</div>
				<div class="divTableCell ">Amount</div>
				<div class="divTableCell width-50px">LOC </div>
				<div class="divTableCell">Remark</div>
				<div class="divTableCell width-50px">Lab</div> 
				<?php foreach($attribute as $key=>$v): ?>
				<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $v; ?></div>
				<?php endforeach;?>
			</div>	
			<div class="outward divTableBody">
				<?php 
				
				$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = 0.0; 
				
				foreach($memo['products'] as $jData):	
				
				
				
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
				
				<div class="outward divTableRow">					
					<div class="divTableCell">
					<label class="pos-rel">
						<input name="products" value="<?php echo $jData['id']?>" class="ace" onclick="totalSelected(<?php echo $k; ?>)" type="checkbox">
						<span class="lbl"></span>
					</label>
					</div>			
					<div class="divTableCell"><?php echo $jData['mfg_code']?></div>
					<div class="divTableCell">  <?php echo $jData['diamond_no']?>  </div>
					<div class="divTableCell">  <?php echo $sku ?> </div>
					<div class="divTableCell a-right width-50px">  <?php echo $jData['rought_pcs']?></div>
					<div class="divTableCell a-right width-50px">  <?php echo $jData['rought_carat']?> </div>
					<div class="divTableCell a-right width-50px">  <?php echo ($jData['purchase_pcs'] ==0)?$jData['polish_pcs']:$jData['purchase_pcs']?> </div>
					<div class="divTableCell a-right width-50px">  <?php echo ($jData['purchase_carat'] ==0)?$jData['polish_carat']:$jData['purchase_carat']?>  </div>
					<div class="divTableCell a-right width-70px">  <?php echo $jData['cost']?> </div>
					<div class="divTableCell a-right ">  <?php echo ($jData['purchase_price'] ==0)?$jData['price']:$jData['purchase_price']; ?></div>
					<div class="divTableCell a-right ">  <?php echo ($jData['purchase_amount'] ==0)?$jData['amount']:$jData['purchase_amount'] ?></div>
					<div class="divTableCell width-50px">  <?php echo $jData['location']?> </div>
					<div class="divTableCell">  <?php echo $jData['remark']?> </div>
					<div class="divTableCell width-50px">  <?php echo $jData['lab']?> </div>
					<?php foreach($attribute as $key=>$v): ?>
					<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo (isset($jData[$key]))?$jData[$key]:'&nbsp;';?></div>
					<?php endforeach;?>
				</div>
				<?php endforeach;?>
				
				<div class="divTableRow">					
					<div class="divTableCell"></div>			
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell"></div>
					<div class="divTableCell a-right width-50px"> </div>
					<div class="divTableCell a-right width-50px"></div>
					<div class="divTableCell a-right width-50px">  <b><?php echo $memo['pcs']?></b> </div>
					<div class="divTableCell a-right width-50px"> <b> <?php echo $memo['carat'] ?></b>  </div>
					<div class="divTableCell a-right width-70px">  </div>
					<div class="divTableCell a-right ">  <b><?php echo ($memo['carat']!=0)?number_format((float)$memo['final_amount']/ (float)$memo['carat'],2,".",""):"0.0"; ?></b></div>
					<div class="divTableCell a-right ">  <b><?php echo $memo['final_amount'] ?></b></div>
					<div class="divTableCell width-50px">  </div>
					<div class="divTableCell">  </div>
					<div class="divTableCell width-50px">  </div>	
					<?php foreach($attribute as $key=>$v): ?>
					<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"></div>
					<?php endforeach;?>	
				</div>
			</div>
		</div>
	</div>

</form>
<div style="clear:both"></div>
<br><br>

<?php $i++; endforeach; ?>
<?php else: ?>
<h4 style="text-align:center">No Data found for this Party.</h4>
<?php endif;?>
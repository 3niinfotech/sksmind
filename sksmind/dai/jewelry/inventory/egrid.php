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
$data = $helper->getPartyMemo($_POST['id'],'export');
//echo"<pre>";
//print_r($data);
//echo"</pre>";
$i=1;
?>
<?php if(count($data)): ?>
<?php foreach($data as $k=>$memo): ?>

<form class="form-horizontal" method="POST" role="form" action="<?php echo $moduleUrl.'outward/outwardController.php'?>">
<input type="hidden" name="fn" value="sendToLab" />
<div class="button-group">
	<div class="cgrid-header green">
		<div class="color-total"> <b>Memo : </b> &nbsp;&nbsp;<?php echo $i; ?> (<?php echo $memo['entryno'] ?>) &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Date :</b> &nbsp;&nbsp;<?php echo $memo['date'] ?> &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Total Record :</b> &nbsp;&nbsp;<?php echo count($memo['products']) ?> &nbsp;&nbsp; |</div>
		<div class="color-total"> <b>Selected :</b> &nbsp;&nbsp;<span id="selected-row-<?php echo $k ?>">0</span> &nbsp;&nbsp; |</div>
	</div>	
	
	<a onclick="deleteSale(<?php echo $k;?>,<?php echo $_POST['id']; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-danger" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Delete Sale
	</a>
	<a onclick="editSale(<?php echo $k;?>,<?php echo $_POST['id']; ?>)" href="javascript:void(0);"  class="btn grid-btn btn-sm btn-success" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Edit Sale
	</a>
	<a href="<?php echo $mainUrl.'pdf/file/invoice.php?id='.$k ?>" target="_blank" id="invoice-<?php echo $k ?>" class="btn grid-btn btn-sm btn-info" type="button" style="float:right">
		<i class="ace-icon fa fa-print bigger-110"></i>
		Print Invoice
	</a>	
	
</div>

	<div class="subform invenotry-cgrid mform-<?php echo $k?>">
		<div class="divTable" >
			<div class="divTableHeading">
				
				<div class="divTableCell"><label class="pos-rel">
						<input class="ace" onclick="calcSelecteds(this)" type="checkbox">
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
				<div class="divTableCell width-70px">Price</div>
				<div class="divTableCell width-70px">Amount</div>
				<div class="divTableCell width-50px">LOC </div>
				<div class="divTableCell">Remark</div>
				<div class="divTableCell width-50px">Lab</div> 
				<?php foreach($attribute as $key=>$v): ?>
				<div class="divTableCell <?php if(in_array($key,$width50))echo 'width-50px'; ?>"><?php echo $v; ?></div>
				<?php endforeach;?>
			</div>	
			<div class="divTableBody">
				<?php 
				
				$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = 0.0; 
				
				foreach($memo['products'] as $jData):	
				$trp += (float) $jData['rought_pcs'];
				$trc += (float) $jData['rought_carat'];
				$tpp += (float) $jData['polish_pcs'];
				$tpc += (float) $jData['polish_carat'];
				$tc += (float) $jData['cost'];
				$tp += (float) $jData['price'];
				$ta += (float) $jData['amount'];
				
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
				
				<div class="divTableRow">					
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
					<div class="divTableCell a-right width-50px">  <?php echo $jData['polish_pcs']?> </div>
					<div class="divTableCell a-right width-50px">  <?php echo $jData['polish_carat']?>  </div>
					<div class="divTableCell a-right width-70px">  <?php echo $jData['cost']?> </div>
					<div class="divTableCell a-right width-70px">  <?php echo $jData['price']?></div>
					<div class="divTableCell a-right width-70px">  <?php echo $jData['amount']?></div>
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
					<div class="divTableCell a-right width-50px"> <b> <?php echo $trp ?></b></div>
					<div class="divTableCell a-right width-50px">  <b><?php echo $trc ?></b> </div>
					<div class="divTableCell a-right width-50px">  <b><?php echo $tpp?></b> </div>
					<div class="divTableCell a-right width-50px"> <b> <?php echo $tpc?></b>  </div>
					<div class="divTableCell a-right width-70px"> <b> <?php echo $tc?></b> </div>
					<div class="divTableCell a-right width-70px">  <b><?php echo $tp?></b></div>
					<div class="divTableCell a-right width-70px">  <b><?php echo $ta?></b></div>
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
<h4 style="text-align:center">No Memo found for this Party.</h4>
<?php endif;?>
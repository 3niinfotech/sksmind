<?php session_start(); ?>
<!DOCTYPE html>
<?php 

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('mybalance',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
} 
?>	
<html lang="en">
<?php include($daiDir."head.php"); ?>	

	<body class="no-skin">
		<?php include($daiDir."account_header.php");?>
		<div class="main-container ace-save-state" id="main-container">
			
			<?php include($daiDir."account_left.php");?>
			<?php include($daiDir."message.php");?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Balance							
							</h1>
						</div><!-- /.page-header -->
						
						<div class="row">
						<?php
							include('balanceModel.php');
							include_once($daiDir.'Helper.php');
							$helper  = new Helper($cn);							
							$model  = new balanceModel($cn);
							$Bdata = $model->getAllData();
							$bookData = $helper->getAllBook();
							$cData = $model->getAllCurrency();
						
							$TUC = $THC =  0.0;
							$currency = $helper->getAllCurrency(); 
							
							?>
						
						<div class="subform invenotry-cgrid " style="overflow-x: auto;">	
						<div class="divTable" style="width:400px;float: left;">
							<h4>Balance Book</h4>
							<div class="divTableHeading">				
								<div class="divTableCell center">No</div>
								<div class="divTableCell center">Book</div>
								<div class="divTableCell center">Currency</div>
								<div class="divTableCell center" style="width:100px;">Balance</div>
								<div class="divTableCell center" style="width:50px;"></div>		
							</div>	
							<form class="form-horizontal" method="POST" role="form" action="<?php echo $daiUrl.'account/balance/balanceController.php'?>">
							<input type="hidden" value="saveBook" name="fn">
							<div class="divTableBody" id="table_body" >
								<?php $i=0; foreach($Bdata as $data): $i++;	?>
								<input name="record[<?php echo $i;?>][id]"  type="hidden"  value="<?php echo $data['id']?>" >
								<div class="divTableRow" id="rowid-<?php echo $data['id']?>" >										
									<div class="divTableCell"><?php echo $i;?> </div>
									<div class="divTableCell"><input name="record[<?php echo $i;?>][book]" disabled class="col-xs-12 " type="text" style="border:0px;height:100%;color:#000 !important" value="<?php echo $data['book']?>" > </div>
									<div class="divTableCell center"><input name="record[<?php echo $i;?>][currency]" disabled class="col-xs-12 center" type="text" style="border:0px;height:100%; color:#000 !important" value="<?php echo $data['currency']?>" ></div>
									<div class="divTableCell a-right " style="width:100px;"><?php echo $data['balance']?></div>									
									<div class="divTableCell " style="width:50px;">
									<a class="green editGroup" href="javascript:void();" onClick="editBook(<?php echo $data['id'];?>)">
										<i class="ace-icon fa fa-pencil bigger-130"></i>
									</a>&nbsp;&nbsp;
									<?php
											$bdetail = "<br/><b>Book</b> : ".$data['book']."<br/><b>Currency : </b>".$data['currency']."<br/><b>Amount : </b>".$data['balance'];
										?>
									<a class="red" href="javascript:void();" onClick="removeEntry('<?php echo $daiUrl.'account/balance/balanceController.php?fn=delete&id='.$data['id']?>','<?php echo $bdetail;?>')">
										<i class="ace-icon fa fa-trash-o bigger-130"></i>
									</a> 
									</div>									
								</div>
								<?php 
								
								$TUC = (float) $TUC + ($data['balance'] * $cData[$data['currency']]['USD'] ) ;
								
								$THC = (float) $THC +  ($data['balance'] * $cData[$data['currency']]['HKD'] );
								
								
								endforeach; ?>		
							</div>
							<div class="divTableBody" style="margin-top:10px">								
								<div class="divTableRow" style="text-align:center">	
									<button class="btn btn-info" type="button" onClick="addNewRow();" style="float:left; margin: 0px 30px 0px 15px; padding: 0px 20px;">
									<i class="ace-icon fa fa-save"></i>
									Add New
									</button>
									
									<button class="btn btn-info" type="submit" style="float:left; margin: 0px; padding: 0px 20px;">
										<i class="ace-icon fa fa-save "></i>
										Save Book
									</button>								
								</div>	
							</div>
							</form>
							
						</div>
						
						<div class="divTable" style="width:400px;float: left; margin-left:50px">
							<form class="form-horizontal" method="POST" role="form" action="<?php echo $daiUrl.'account/balance/balanceController.php'?>">
							<input type="hidden" value="saveCurrency" name="fn">
							<h4>Currency Rate</h4>
							<div class="divTableHeading">				
								<div class="divTableCell "></div>
								<div class="divTableCell " style="width:100px;" >Currency</div>
								<div class="divTableCell center" style="width:90px;">USD</div>
								<div class="divTableCell center" style="width:90px;">HKD</div>	
								<div class="divTableCell center" style="width:50px;"></div>				
							</div>	
							<div class="divTableBody">
								<?php $i=0;foreach($currency as $k=>$v): $i++; ?>
								<div class="divTableRow" id="currencyrate-<?php echo $cData[$v]['id'] ?>" >	
									<div class="divTableCell"><?php echo $i?></div>
									<div class="divTableCell" style="width:100px;"> <b><?php echo $v; ?></b></div>
									<?php if(isset($cData[$v])): ?>	
										<input type="hidden" name ="<?php echo $v?>[id]" value="<?php echo $cData[$v]['id']  ?>" /> 
										<div class="divTableCell center" style="width:90px;"><input name="<?php echo $v; ?>[USD]" disabled value="<?php echo $cData[$v]['USD']  ?>" class="col-xs-12 center" type="text" style="border:0px;height:100%;color:#000 !important"> </div>
										<div class="divTableCell center" style="width:90px;"><input name="<?php echo $v; ?>[HKD]" disabled value="<?php echo $cData[$v]['HKD']  ?>" class="col-xs-12 center" type="text" style="border:0px;height:100%;color:#000 !important"> </div>									
									<?php else:?>
										<div class="divTableCell center"><input name="<?php echo $v; ?>[USD]" class="col-xs-12 center" type="text" style="border:0px;height:100%"> </div>
										<div class="divTableCell center"><input name="<?php echo $v; ?>[HKD]" class="col-xs-12 center" type="text" style="border:0px;height:100%"> </div>
									<?php endif;?>
									<div class="divTableCell " style="width:50px;">
										<a class="green editGroup" href="javascript:void();" onClick="editCurency(<?php echo $cData[$v]['id']  ?>)">
											<i class="ace-icon fa fa-pencil bigger-130"></i>
										</a>&nbsp;&nbsp;
										<?php
											$bdetail = "<br/><b>Currency</b> : ".$v;
										?>
										<a class="red" href="javascript:void();" onClick="removeEntry('<?php echo $daiUrl.'account/balance/balanceController.php?fn=delete&id='.$cData[$v]['id']?>','<?php echo $bdetail ?>')">
											<i class="ace-icon fa fa-trash-o bigger-130"></i>
										</a> 
									</div>
								</div>
								<?php endforeach; ?>
								
							</div>
							
							<div class="divTableBody" style="margin-top:10px">								
								<div class="divTableRow" style="text-align:center">	
									<button class="btn btn-info" type="submit" style=" margin: 0px; padding: 0px 20px;">
									<i class="ace-icon fa fa-save bigger-110"></i>
									Save Currency
								</button>									
								</div>								
							</div>
							</form>
							
							<div class="divTableHeading" style="margin-top:30px;">				
								<div class="divTableCell "></div>
								<div class="divTableCell ">Currency</div>
								<div class="divTableCell center">Total</div>								
							</div>	
							<div class="divTableBody">
								
								<div class="divTableRow">	
									<div class="divTableCell">1</div>
									<div class="divTableCell"> <b>USD</b></div>									
									<div class="divTableCell a-right"><?php echo $TUC;?></div>									
								</div>
								<div class="divTableRow">	
									<div class="divTableCell">1</div>
									<div class="divTableCell"> <b>HKD</b></div>									
									<div class="divTableCell a-right"> <?php echo $THC;?></div>									
								</div>								
							</div>
						</div>
						
						
						
						<form class="form-horizontal" method="POST" role="form" action="<?php echo $daiUrl.'account/balance/balanceController.php'?>">
						<input type="hidden" value="bankTransfer" name="fn">
							<div class="divTable" style="width:400px;float: left; margin-left:50px">
							<h4>Bank Transfer</h4>
							
							<div class="form-group col-sm-12">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-4">From Book</label>
								<div class="col-sm-9">
									<select class="col-xs-10" id="frombook" name="frombook">				
										<option value="">Select From Bank</option>
										<?php foreach($bookData as $k=>$v): ?>
											<option value="<?php echo $k; ?>"><?php echo $v;?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group col-sm-12">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-4">To Book</label>
								<div class="col-sm-9">
									<select class="col-xs-10" id="tobook" name="tobook">				
										<option value="">Select To Bank</option>
										<?php foreach($bookData as $k=>$v): ?>
											<option value="<?php echo $k; ?>"><?php echo $v;?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group col-sm-12">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Amount</label>
								<div class="col-sm-9">
									<input class="input-sm col-sm-10 a-right" id="amount" name="amount" type="text">
								</div>
							</div>
							
							<div class="form-group col-sm-12">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Rate</label>
								<div class="col-sm-9">
									<input class="input-sm col-sm-10 a-right" id="rate" name="rate" type="text">
								</div>
							</div>
							<div class="form-group col-sm-12">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Date</label>
								<div class="col-sm-9">
									<input class="input-sm col-sm-10" id="date" value="" name="date" placeholder="Select Date" type="text">
								</div>
							</div>
							<div class="divTableBody" style="margin-top:10px">								
								<div class="divTableRow" style="text-align:center">	
									<button class="btn btn-info" type="submit" style=" margin: 0px; padding: 0px 20px;">
									<i class="ace-icon fa fa-refresh bigger-110"></i>
									Bank Trnsfer
								</button>									
								</div>								
							</div>
							</div>
						</form>
						</div>
						<div style="clear:both;margin-bottom:50px;"> </div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<?php
			include($daiDir."footer.php");
		?>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="<?php echo  $daiUrl;?>assets/js/jquery-2.1.4.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/bootbox.js"></script>
		<!-- <![endif]-->

		
		
		<script src="<?php echo  $daiUrl;?>assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->

	
	
		<!-- page specific plugin scripts -->
		<script src="<?php echo  $daiUrl;?>assets/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/jquery.dataTables.bootstrap.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/buttons.flash.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/buttons.html5.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/buttons.print.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/buttons.colVis.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/dataTables.select.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/jquery-ui.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo  $daiUrl;?>assets/js/ace-elements.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/ace.min.js"></script>
		<script type="text/javascript">
			jQuery(function($) {
				$( "#date").datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				}).datepicker("setDate", new Date());
			});
				
			$('.reset').click(function(){
				$('#gid').val('');				
			});
			var n = <?php echo count($Bdata); ?>;
			n = n + 1;
			function addNewRow()
			{
				var html ="";
				html += '<div class="divTableBody"><div class="divTableRow"><div class="divTableCell">'+n+'</div>';
				html += '<div class="divTableCell center"><input name="record['+n+'][book]" class="col-xs-12 " type="text" style="border:0px;height:100%"></div>';
				html += '<div class="divTableCell center"><input name="record['+n+'][currency]" class="col-xs-12 center" type="text" style="border:0px;height:100%"></div>';
				html += '<div class="divTableCell center"><input name="record['+n+'][balance]" class="col-xs-12 a-right" type="text" style="border:0px;height:100%"></div>';									
				html += '</div></div>';
				$('#table_body').append(html);
				n++;
			}			
			function removeEntry(url,detail)
			{
					bootbox.confirm("Are you sure you want to delete ?"+detail, function(result) {
					if(result) 
					{
						window.location.href = url;
					}
					});
			}
			function editBook(rid)
			{
				jQuery('#rowid-'+rid+' input').attr('disabled',false);
			}
			function editCurency(rid)
			{
				jQuery('#currencyrate-'+rid+' input').attr('disabled',false);
			}
		</script>

		
	</body>
	
</html>
	
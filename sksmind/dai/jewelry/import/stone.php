<?php session_start(); ?>
<!DOCTYPE html>
<?php 

$t = "";
if(isset($_GET['t']))
{
		$t = $_GET['t'];
}
$roll = $t;
if($t=='memo')
	$roll = "i_memo";

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array($roll,$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}
?>	
<html lang="en">
<?php include($daiDir."head.php"); ?>	

	<body class="no-skin">
		<?php include($daiDir."header_jewelry.php");?>
		<div class="main-container ace-save-state" id="main-container">
			
			<?php include($daiDir."left_jewelry.php");?>
			<?php include($daiDir."message.php");?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<?php  ?>
							<h1 style="float:left">
								Stone - <?php echo ucfirst($t) ?> Transaction								
							</h1>
							<?php if(isset($_GET['pg']) && $_GET['pg'] == "form"): ?>
							
							<button class="btn btn-info" type="button" style="float:right" onClick="location.href='<?php echo $daiUrl;?>jewelry/import/inwardController.php?fn=clearData'">
								<i class="ace-icon fa fa-refresh bigger-110"></i>
								Clear Data
							</button>
							<?php else: ?>
							<button class="btn btn-info" type="button" onClick="location.href='<?php echo $daiUrl;?>module/inward/inward.php?pg=form'" style="float:right">
								<i class="ace-icon fa fa-plus bigger-110"></i>
								Add New Import Entry
							</button>
							<?php endif; ?>
						</div><!-- /.page-header -->
						
						<div class="row">
						<?php
							
							include_once('inwardModel.php');
							include_once($daiDir.'jewelry/party/partyModel.php');
							include_once($daiDir.'Helper.php');
							include_once($daiDir.'jHelper.php');
							
							$model  = new inwardModel($cn);
							$pmodel  = new partyModel($cn);
							$helper = new Helper($cn);
							$jhelper = new jHelper($cn);
							$attribute = $jhelper->getStoneImportAttribute();
							
							$TempData = $jhelper->getTempData();
							
							$groupUrl = $daiDir.'jewelry/import/';
							//$attribute['package'] = "Package";
							$party = $pmodel->getOptionList();
							
							
							$colorAttri = $helper->getJewAttributebyCode('color');
							$clarityAttri = $helper->getJewAttributebyCode('clarity');
							$shapeAttri = $helper->getJewAttributebyCode('shape');		
							
							
							if(isset($_GET['pg']) && $_GET['pg'] == "form")
							{	
								$lid=0;
								if(isset($_GET['id']))
								{
									$lid = $_GET['id'];
								}
								$data =  $model->getData($lid);
								//$dataRecord = $model->getRecordData($data['entryno']);
								$t = "";

								if(isset($_GET['t']))
								{
										$t = $_GET['t'];
								}
								
								if($t=='import')
								{
									include($groupUrl."iform.php");								
								}
								else
								{
									include($groupUrl."form.php");								
								}
							}	
							else
							{
								//include($groupUrl."grid.php");
							}
							
							?>
						</div>
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

		<!-- <![endif]-->

		
		
		<script src="<?php echo  $daiUrl;?>assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->

	
	
		<!-- page specific plugin scripts -->
		<script src="<?php echo  $daiUrl;?>assets/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/jquery.dataTables.bootstrap.min.js"></script>
		

		<script src="<?php echo  $daiUrl;?>assets/js/buttons.colVis.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/dataTables.select.min.js"></script>
		
		
		<script src="<?php echo  $daiUrl;?>assets/js/jquery-ui.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo  $daiUrl;?>assets/js/ace-elements.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/ace.min.js"></script>
		<script type="text/javascript">
			
			var n = <?php echo (count($TempData))? count($TempData):'1';?>;
			n++;
			
			jQuery(function($) {
				
		
				$( "#date, #invoicedate" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});
				 
			
				
				
				$("#terms").on("change", function()
				{
					   var selectedDate = $("#invoicedate").val();

					  // var finalDate = selectedDate.substring(6,11)+"/"+selectedDate.substring(3,6)+"/"+selectedDate.substring(0,2);
					   var date = new Date(selectedDate);

						days = parseInt($("#terms").val(), 10);
					   
						if(!isNaN(date.getTime())){
							date.setDate(date.getDate() + days);
							if(date.toInputFormat() != "NaN/NaN/NaN")
							{
								$("#duedate").val(date.toInputFormat());
							}
							else
							{
								$("#duedate").val('');
							}
						} else {
							alert("Invalid Date");  
						}
					
				});
					
			   Date.prototype.toInputFormat = function()
			   {
			   var yyyy = this.getFullYear().toString();
			   var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
			   var dd  = this.getDate().toString();				
			   return  yyyy  + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0] ) ;  					
			   };

				$('#id-input-file-2').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
				var myTable = 
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					bAutoWidth: false,
					"aoColumns": [
					  { "bSortable": false },
					  null,null,null,null,null,null,null,null,
					  { "bSortable": false }
					],
					"aaSorting": [],
					
					select: {
						style: 'multi'
					}
			    });
				
				
				
				myTable.on( 'select', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
					}
				} );
				myTable.on( 'deselect', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
					}
				} );
			
			
			
			
				/////////////////////////////////
				//table checkboxes
				$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
				
				//select/deselect all rows according to table header checkbox
				$('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$('#dynamic-table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) myTable.row(row).select();
						else  myTable.row(row).deselect();
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
					var row = $(this).closest('tr').get(0);
					if(this.checked) myTable.row(row).deselect();
					else myTable.row(row).select();
				});
			
			
			
				$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
					e.stopImmediatePropagation();
					e.stopPropagation();
					e.preventDefault();
				});
				
				
				
				//And for the first simple table, which doesn't have TableTools or dataTables
				//select/deselect all rows according to table header checkbox
				var active_class = 'active';
				$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$(this).closest('table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
						else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
					var $row = $(this).closest('tr');
					if($row.is('.detail-row ')) return;
					if(this.checked) $row.addClass(active_class);
					else $row.removeClass(active_class);
				});
			
				
			
				/********************************/
				//add tooltip for small view action buttons in dropdown menu
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				
				//tooltip placement on right or left
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
				
				
				
				
				/***************/
				$('.show-details-btn').on('click', function(e) {
					e.preventDefault();
					$(this).closest('tr').next().toggleClass('open');
					$(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
				});
				/***************/
				
				
				
				
				
				/**
				//add horizontal scrollbars to a simple table
				$('#simple-table').css({'width':'2000px', 'max-width': 'none'}).wrap('<div style="width: 1000px;" />').parent().ace_scroll(
				  {
					horizontal: true,
					styleClass: 'scroll-top scroll-dark scroll-visible',//show the scrollbars on top(default is bottom)
					size: 2000,
					mouseWheelLock: true
				  }
				).css('padding-top', '12px');
				*/
			
			});
			
				
				//********************** IMPORT ***********************/
				$('.subform .add-more').on("click", function(){					
					addImportRow();					
				});	
				
				
				var colorAttri ='';
				<?php foreach($colorAttri as $key => $value):	?>						
				colorAttri += '<option value="<?php echo $key?>" <?php echo ($key == '')?"selected":""; ?> ><?php echo $value?></option>';
				<?php endforeach; ?>
				var clarityAttri ='';
				<?php foreach($clarityAttri as $key => $value):	?>						
				clarityAttri += '<option value="<?php echo $key?>" <?php echo ($key == '')?"selected":""; ?> ><?php echo $value?></option>';
				<?php endforeach; ?>
				var shapeAttri ='';
				<?php foreach($shapeAttri as $key => $value):	?>						
				shapeAttri += '<option value="<?php echo $key?>" <?php echo ($key == '')?"selected":""; ?> ><?php echo $value?></option>';
				<?php endforeach; ?>
							
				
				function addImportRow(rid)
				{
					var total = $('.divTableBody .divTableRow').length;
					if(total == rid)
					{
					var row = '<div class="divTableRow" id="rowid-'+n+'">';
					row += '<div class="divTableCell"><i class="delete-more fa fa-times " onClick="removeRow('+n+')" ></i></div>';
					row += '<div class="divTableCell">'+n+'</div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="record['+n+'][mfg_code]" id="mfg-'+n+'" onBlur="addImportRow('+n+')" type="text" ></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12"  name="record['+n+'][sku]" id="sku-'+n+'" onBlur="addImportRow('+n+')" type="text"></div>';
					
					row += '<div class="divTableCell">';
					row += '<select class="" id="color-'+n+'" name="record['+n+'][color]" onchange="changeColor(this.value,'+n+')">';			
					row += colorAttri+'</select></div>';				
					
					row += '<div class="divTableCell">';
					row += '<select id="color_code-'+n+'" name="record['+n+'][color_code]">';				
					row += '<option value="0">All Color Code</option></select></div>';	
					
					row += '<div class="divTableCell">';
					row += '<select class="" id="clarity-'+n+'" name="record['+n+'][clarity]">';			
					row += clarityAttri+'</select></div>';	
					
					row += '<div class="divTableCell">';
					row += '<select class="" id="shape-'+n+'" name="record['+n+'][shape]">';			
					row += shapeAttri+'</select></div>';	
					
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right tni-pcs"  name="record['+n+'][pcs]" type="text" onBlur="calAmount('+n+'); calGst()"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right tni-carat"  name="record['+n+'][carat]" id="pcarat-'+n+'" type="text" onBlur="calAmount('+n+'); calGst()"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record['+n+'][price]" id="price-'+n+'" onBlur="calAmount('+n+'); calGst()" type="text"></div>';
					row += '<div class="divTableCell"><input class=" col-sm-12 a-right tni-amount"  name="record['+n+'][amount]" id="amount-'+n+'" type="text"></div>';
										
					row += '<div class="divTableCell">';
						row += '<select id="location-'+n+'" name="record['+n+'][location]">';
						<?php foreach($helper->getLocation() as $k=>$v): ?>
							row += '<option value="<?php echo $k;?>"><?php echo $v;?></option>';						
						<?php endforeach; ?>
						row += '</select>';				
					row += '</div>';
					
					
					//row += '<div class="divTableCell"><input class=" col-sm-12"  name="record['+n+'][remarks]" type="text"></div>';
					/* <?php foreach($attribute as $key=>$v): ?>
					row += '<div class="divTableCell">';			
						row += '<input class=" col-sm-12"  name="record['+n+'][attr][<?php echo $key?>]" type="text">';
					row += '</div>';
					<?php endforeach; ?> */
			
					row +='</div>';
					n = n + 1;
					$('.subform .divTableBody').append(row);
					}
				}
				function generateSku(rid)
				{
					var mfg = $('#mfg-'+rid).val();
					var dno = $('#dno-'+rid).val();
					var val = mfg;
					if(dno!="")
					{
						val += "-"+dno;
					}					
					$('#sku-'+rid).val(val);
					
				}
				
				function calAmount(rid)
				{
					var price = parseFloat($('#price-'+rid).val());
					var pcarat = parseFloat($('#pcarat-'+rid).val());
					var total  = parseFloat(price * pcarat );
					
					if(!isNaN(total))
					{
						$("#amount-"+rid ).val(total.toFixed(2));
					}
					else
					{
						$("#amount-"+rid ).val(0);
					}		
					totalqty();		
					totalamount();
					totalweight();
				}
				
				
				function totalqty()
				{
					var total =0;
					var total =0.0;
					$(".tni-pcs" ).each(function( index ) 					
					{
					  var qty = parseFloat($( this ).val());
					  if(!isNaN(qty))
					  {
						total = qty + total;						
					  }
					  if(!isNaN(total))
						{
							$('#totalqty').val(total.toFixed(2)); 
						}
						else
						{
							$('#totalqty').val(0); 
						}					  
					});
				}
				function totalweight()
				{					
					var total_weight =0.0;
					$(".tni-carat" ).each(function( index ) 					
					{
					  var qty = parseFloat($( this ).val());
					  if(!isNaN(qty))
					  {
						total_weight = qty + total_weight;						
					  }
					  if(!isNaN(total_weight))
						{
							$('#total_nwt').val(total_weight.toFixed(2)); 
						}
						else
						{
							$('#total_nwt').val(0); 
						}					  
					});
				}
				
				function totalamount()
				{
					var total =0.0;
					$(".tni-amount" ).each(function( index ) 
					{
					  var amount = parseFloat($( this ).val());
					  if(!isNaN(amount))
					  {
						total = amount + total;
					  }
					  if(!isNaN(total))
						{
							$('#totalamount').val(Math.round(total.toFixed(2)));
						}
						else
						{
							$('#totalamount').val(0); 
						}
					});
				}
				
				
				
				
				function calGst()
				{
					var totalamount = parseFloat(jQuery('#totalamount').val());
					if(isNaN(totalamount))
					{		
						totalamount = 0;
					}				 
					var discount = parseFloat(jQuery('#discount').val());    
					var discount_amount = parseFloat( ((discount*totalamount)/100));
				 
					if(isNaN(discount_amount))
					{		
						discount_amount = 0;
					}
					
					var sgst_per = parseFloat(jQuery('#sgst_per').val());    
					var cgst_per = parseFloat(jQuery('#cgst_per').val());
					var igst_per = parseFloat(jQuery('#igst_per').val());
				 
					var sgst_per = parseFloat(jQuery('#sgst_per').val());    
					var cgst_per = parseFloat(jQuery('#cgst_per').val());
					var igst_per = parseFloat(jQuery('#igst_per').val());
				 
					if(isNaN(sgst_per))
					{		
						sgst_per = 0;
					}
				 
					if(isNaN(cgst_per))
					{		
						cgst_per = 0;
					}
				 
					if(isNaN(igst_per))
					{		
						igst_per = 0;
					}				 
					
					var sgst_amount = parseFloat((sgst_per*totalamount)/100);
					jQuery('#sgst_amount').val(sgst_amount.toFixed(2));
				 
					var cgst_amount = parseFloat((cgst_per*totalamount)/100);
					jQuery('#cgst_amount').val(cgst_amount.toFixed(2));
					 
					var igst_amount = parseFloat((igst_per*totalamount)/100);
					jQuery('#igst_amount').val(igst_amount.toFixed(2));
									 
					var total = sgst_amount + cgst_amount + igst_amount + totalamount;
					var t=Math.round(total);
					var r=total-parseInt(total);
					jQuery('#round').val(r.toFixed(2));
					jQuery('#afteramount').val(t);				 
				}
				
				
				
				function removeRow(id)
				{
					$('#rowid-'+id).remove();					
				}
				
				
				function changeColor(vl,rowindex)
				{
					var data = {'id':vl,'fn':'getJewAttributebyCode'};
					jQuery.ajax({
						url: '<?php echo $jewelryUrl.'attribute/attributeController.php'?>', 
						type: 'POST',
						data: data,		
						success: function(result)
						{		
							$('#color_code-'+rowindex).html(result);
						}
					});					
				}
				
		</script>

		
	</body>
	<style>
	.page-header {
	  float: left;
	  margin-bottom: 20px;
	  padding-bottom: 0;
	  width: 100%;
	}
.subform {
	float: left;
	overflow-x: scroll;
	padding: 0px 10px 25px 10px;
	width: 100%;
	overflow: hidden;
}
hr {
  border-top: 1px solid #eee;
  margin-bottom: 15px;
  margin-top: 15px;
}
.divTableFooter {
  border-top: 1px solid #f1f1f1;
  margin-top: 20px;
  padding-top: 10px;
}

.synchro-process {
	position: fixed;
	top: 0px;
	left: 0px;
	z-index: 999999999;
	border-radius: 2px;
	width: 100%;
	background: rgba(0,0,0,0.5) !important;
	height: 100%;
}

.upload-process {
	z-index: 999999999;
	padding: 10px;
	border: 1px solid #ccc;
	width: 300px;
	background: #fff;
	border-radius: 2px;
	margin-bottom: 10px;
	width: 58%;
	float: left;
	position: absolute;
	top: 30%;
	left: 20%;
}
.upload-process .progress {
	margin-bottom: 10px !important;	
}
#close-progress
{
float:right;font-size:18px;
cursor:pointer;
padding-bottom:5px;

}

.divTableCell select {
	height: 26px;
}

	</style>
</html>







	
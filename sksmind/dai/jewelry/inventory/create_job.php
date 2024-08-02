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
								Create Job							
							</h1>
				
							<?php if(isset($_GET['pg']) && $_GET['pg'] == "form"): ?>
							
							<button class="btn btn-info" type="button" onClick="location.href='<?php echo $daiUrl;?>jewelry/inventory/create_job.php'" style="float:right">
								<i class="ace-icon fa fa-reply bigger-110"></i>
								Back
							</button>
							<?php else: ?>
							<button class="btn btn-info" type="button" onClick="location.href='<?php echo $daiUrl;?>jewelry/inventory/create_job.php?pg=form'" style="float:right">
								<i class="ace-icon fa fa-plus bigger-110"></i>
								Add New Job
							</button>
							<?php endif; ?>
						</div><!-- /.page-header -->
						
						<div class="row">
						<?php
							
							include_once($daiDir.'jewelry/jobwork/jobworkModel.php');
							include_once($daiDir.'jewelry/party/partyModel.php');
							include_once($daiDir.'Helper.php');
							include_once($daiDir.'jHelper.php');
							
							$model  = new jobworkModel($cn);
							$pmodel  = new partyModel($cn);
							$helper = new Helper($cn);
							$jhelper = new jHelper($cn);
							$groupUrl = $daiDir.'jewelry/inventory/';
							$party = $pmodel->getOptionList();
							if(isset($_GET['pg']) && $_GET['pg'] == "form")
							{	
								$lid=0;
								if(isset($_GET['id']))
								{
									$lid = $_GET['id'];
								}
								$data =  $model->getDataJOB($lid);
								include($groupUrl."create_job_form.php");								
							}	
							else
							{
								include($groupUrl."create_job_grid.php");
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
			var n=1;
			
			jQuery(function($) {
				
		
				$( "#date, #duedate" ).datepicker({
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
					  null,null,null,null,null,null,null,
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
					
				}
				function removeRow(id)
				{
					$('#rowid-'+id).remove();					
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

	</style>
</html>







	
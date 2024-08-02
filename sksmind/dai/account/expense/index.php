<?php session_start(); ?>
<!DOCTYPE html>
<?php 

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('expense',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
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
							<?php $t = "";
							if(isset($_GET['t']))
							{
									$t = $_GET['t'];
							} ?>
							<h1 style="float:left">
							Expense - <?php echo ucfirst($t) ?> Transaction								
							</h1>
							<?php if(isset($_GET['pg']) && $_GET['pg'] == "form"): ?>
							
							<button class="btn btn-info" type="button" onClick="location.href='<?php echo $daiUrl;?>account/expense/index.php'" style="float:right;margin-left:20px;">
								<i class="ace-icon fa fa-reply bigger-110"></i>
								Data
							</button>
							
							<button class="btn btn-info" type="button" onClick="location.href='<?php echo $daiUrl;?>account/expense/index.php?pg=form'" style="float:right">
								<i class="ace-icon fa fa-plus bigger-110"></i>
								Add New Expense
							</button>
							<?php else: ?>
							<button class="btn btn-info" type="button" onClick="location.href='<?php echo $daiUrl;?>account/expense/index.php?pg=form'" style="float:right">
								<i class="ace-icon fa fa-plus bigger-110"></i>
								Add New Expense
							</button>
							<?php endif; ?>
						</div><!-- /.page-header -->
						
						<div class="row">
						<?php
							
							include_once('expenseModel.php');
							include_once($daiDir.'account/party/partyModel.php');
							include_once($daiDir.'Helper.php');
							
							$model  = new expenseModel($cn);
							$pmodel  = new partyModel($cn);
							$helper = new Helper($cn);
							$attribute = $helper->getAttribute();
							$TempData = $helper->getTempData();
							$groupUrl = $daiDir.'account/expense/';
							$bookData = $helper->getAllBook();
							$currencyData = $helper->getAllCurrency();
							$party = $pmodel->getOptionList();
							if(isset($_GET['pg']) && $_GET['pg'] == "form")
							{	
								$lid=0;
								if(isset($_GET['id']))
								{
									$lid = $_GET['id'];
									
									$data =  $model->getData($lid);								
									include($groupUrl."editform.php");
								}
								else
								{
									$data =  $model->getData($lid);
								
									include($groupUrl."form.php");	
								}								
							}	
							else
							{
								include($groupUrl."grid.php");
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
<div class="dialog-box-container1" id="dialog-box-container1" style="display:none;" >
	
</div>	
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
				
			<?php  if(isset($_GET['id'])): ?>
				$( "#date").datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				});
			<?php else: ?>
			$( "#date").datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					dateFormat: 'yy-mm-dd',
				}).datepicker("setDate", new Date());
			<?php endif; ?>	
				
				
				var myTable = 
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					bAutoWidth: false,
					"aoColumns": [
					  { "bSortable": false },
					  null,null,null,null,null,null,
					  { "bSortable": false }
					],					
					"iDisplayLength": 50,					
					
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
			
			function addParty()
			{
				
				var data = {'id':10};
				jQuery.ajax({
				url: '<?php echo $daiUrl.'account/party/partyForm.php'?>', 
				type: 'POST',
				data: data,		
				success: function(result)
				{		
					 
					jQuery('#dialog-box-container1').html(result);
					jQuery('#dialog-box-container1').show();
				}
				});		
			}
			function closePartyPopup()
			{
				jQuery('#dialog-box-container1').hide();
			}
			
			function removeEntry(url)
			{
					bootbox.confirm("Are you sure you want to delete ?", function(result) {
					if(result) 
					{
						window.location.href = url;
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
	</style>
</html>







	
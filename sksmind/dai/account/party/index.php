<?php session_start(); ?>
<!DOCTYPE html>
<?php 

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('a_party',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
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
							<h1 style="float:left">
								Party								
							</h1>
							<?php if(isset($_GET['pg']) && $_GET['pg'] == "form"): ?>
							
							<button class="btn btn-info" type="button" onClick="location.href='<?php echo $daiUrl;?>account/party'" style="float:right">
								<i class="ace-icon fa fa-reply bigger-110"></i>
								Back
							</button>
							<?php else: ?>
							<button class="btn btn-info" type="button" onClick="location.href='<?php echo $daiUrl;?>account/party/index.php?pg=form'" style="float:right">
								<i class="ace-icon fa fa-plus bigger-110"></i>
								Add New Party
							</button>
							<?php endif; ?>
						</div><!-- /.page-header -->
						
						<div class="row">
						<?php
							include('partyModel.php');							
							include($daiDir.'account/subgroup/subgroupModel.php');
							include($daiDir.'account/group/groupModel.php');
							$Smodel  = new subgroupModel($cn);
							$Gmodel  = new groupModel($cn);
							$Group = $Gmodel->getOption();
							$Subgroup = $Smodel->getOption();
							
							$model  = new partyModel($cn);
							?>
							<?php 
							$groupUrl = $daiDir.'account/party/';
							$modUrl = $daiUrl.'account/party/';
							if(isset($_GET['pg']) && $_GET['pg'] == "form")
							{	
								$lid=0;
								if(isset($_GET['id']))
								{
									$lid = $_GET['id'];
								}
								$data =  $model->getData($lid);
								include($groupUrl."form.php");								
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
			jQuery(function($) {
			
				$('.subform  input').attr("disabled",true);
				$('.subform  select').attr("disabled",true);
				$('.subform  textarea').attr("disabled",true);
				<?php if(isset($_GET['id']) && $_GET['id'] !="" ): ?>
				var tid ="#subform-<?php echo $data['under_group']; ?>";
				$(tid).removeClass('no-display');				  
				$(tid+' input').attr("disabled",false);
				$(tid+' select').attr("disabled",false);
				$(tid+' textarea').attr("disabled",false);

				<?php endif; ?>
				
				 $( "#datepicker" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,					
				}); 
				
				var myTable = 
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					bAutoWidth: false,
					"aoColumns": [
					  { "bSortable": false },
					  null,null,null,null,null,
					  { "bSortable": false }
					],
					"aaSorting": [],
					"iDisplayLength": 50,
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
			
			$('.editGroup').click(function(){
				var gname = $(this).attr('gname');
				var under = $(this).attr('under');
				var gid = $(this).attr('gid');
				
				$('#gname').val(gname);
				$('#gid').val(gid);
				$('#gunder').val(under);
				});
				
			$('.reset').click(function(){
				$('#gid').val('');				
			});	
			$('#gunder').on('change', function() {
				$('.subform  input').attr("disabled",true);
				 $('.subform  select').attr("disabled",true);
				 $('.subform  textarea').attr("disabled",true);
				 // alert( this.value ); // or $(this).val()
				  var v = this.value;
				  var id = '#subform-'+v;
				  $('.subform').addClass('no-display');
				  $(id).removeClass('no-display');
				  
				  $(id+' input').attr("disabled",false);
				 $(id+' select').attr("disabled",false);
				 $(id+' textarea').attr("disabled",false);
				  
				});
				
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
	</style>
</html>







	
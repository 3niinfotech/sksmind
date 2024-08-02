<?php session_start(); ?>
<!DOCTYPE html>
<?php 

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('box',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}
 
?>	
<html lang="en">
<?php include($daiDir."head.php"); ?>	

	<body class="no-skin">
		<?php include($daiDir."header.php");?>
		<div class="main-container ace-save-state" id="main-container">
			
			<?php include($daiDir."left.php");?>
			<?php include($daiDir."message.php");?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">							
							<h1 style="float:left">
								Single To Parcel								
							</h1>
							<button id="addToBox" disabled class="btn btn-info" style="float:right" type="submit">
								<i class="ace-icon fa fa-plus bigger-110"></i>
								Add to Parcel
							</button>
						</div><!-- /.page-header -->
						
						<div class="row">
						<?php
							
							include_once('singleModel.php');
							include_once($daiDir.'Helper.php');
							
							$model  = new singleModel();
							$helper  = new Helper();
							$attribute = $helper->getAttribute();							
							$groupUrl = $daiDir.'module/single/';														
							include_once($groupUrl."tpgrid.php");
							
							?>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<?php
			include($daiDir."footer.php");
		?>
		</div><!-- /.main-container -->
<div id="please-wait" style="display:none">
	<img src="<?php echo  $daiUrl;?>assets/images/loading.gif" />
</div> 
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
			var n = 2;
			
			jQuery(function($) {
				
		
			
				
			   Date.prototype.toInputFormat = function()
			   {
			   var yyyy = this.getFullYear().toString();
			   var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
			   var dd  = this.getDate().toString();				
			   return  yyyy  + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0] ) ;  					
			   };

				
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
			
			
			$("input[name='type']").click(function() {
			$('.box-form  input').attr("disabled",true);
			$('.box-form  select').attr("disabled",true);
			$('.box-form  textarea').attr("disabled",true);
			 // alert( this.value ); // or $(this).val()
			  var v = this.value;
			  var id = '#box-'+v;
			  $('.box-form').addClass('no-display');
			  $(id).removeClass('no-display');
			  
			  $(id+' input').attr("disabled",false);
			 $(id+' select').attr("disabled",false);
			 $(id+' textarea').attr("disabled",false);
			  
			});
				
			
			$('#addToBox').click(function(){
				$('#dialog-box-container').show(100);
			});
			
			$('#close-box').click(function(){
				$('#dialog-box-container').hide(100);
			});
			
			
			function totalSelected()
			{
				var total = parseFloat($('#dynamic-table').find("input[type='checkbox']:checked").length);
				if(total > 0)
				{
					$('#addToBox').attr('disabled',false);
				}
				else
				{
					$('#addToBox').attr('disabled',true);
				}				
				$('#selected-row').html(total);
			}			
			function countCheck(temp)
			{
				totalSelected();				
				if($(temp).is(':checked'))
				{
					$(temp).parents('tr').addClass('active');
				}
				else
				{
					$(temp).parents('tr').removeClass('active');
				}
				calcSelected();				
			}

			function calcSelected()
			{
				// count pcs
				var tp=0;
				$( ".active .pcs" ).each(function( index ) {
				  var values = parseFloat($( this ).html());
				  if(!isNaN(values))
				  {
					tp = parseFloat(tp) + values;		
				  }
				});
				$('#total-pcs').html(tp.toFixed(2));
				$('#box-total-pcs').val(tp.toFixed(2));
				
				// count pcs
				var tc=0;
				$( ".active .carats" ).each(function( index ) {
				  var values = parseFloat($( this ).html());
				  if(!isNaN(values))
				  {
					tc = parseFloat(tc) + values;		
				  }
				});
				$('#total-carat').html(tc.toFixed(2));
				$('#box-total-carat').val(tc.toFixed(2));
				// count price
				tc=0;
				$( ".active .price" ).each(function( index ) {
				  var values = parseFloat($( this ).html());
				  if(!isNaN(values))
				  {
					tc = parseFloat(tc) + values;		
				  }
				});
				$('#total-price').html(tc.toFixed(2));
				$('#box-total-price').val(tc.toFixed(2));
				
				// count amount
				tc=0;
				$( ".active .amount" ).each(function( index ) {
				  var values = parseFloat($( this ).html());
				  if(!isNaN(values))
				  {
					tc = parseFloat(tc) + values;		
				  }
				});
				$('#total-amount').html(tc.toFixed(2));
				$('#box-total-amount').val(tc.toFixed(2));
			}			
			function checkAll(temp)
			{
				if($(temp).is(':checked'))
				{
					$('#dynamic-table tbody tr').addClass('active');
					$('#dynamic-table tbody tr input:checkbox').prop('checked',true);
					
				}
				else
				{
					$('#dynamic-table tbody tr').removeClass('active');
					
					$('#dynamic-table tbody tr input:checkbox').prop('checked',false);
				}
				totalSelected();				
				calcSelected();
			}
			
			function getBoxDetail(val)
			{

					jQuery('#please-wait').show();
					var data = {'fn':'getDetail','id':val};
					jQuery.ajax({
						url: '<?php echo $moduleUrl.'single/singleController.php' ?>', 
						type: 'POST',
						data: data,
						success: function(result)
							{				
								if(result != 1)
								{
									var obj = jQuery.parseJSON(result );						
									
									var pcs = parseFloat(obj.polish_pcs);
									var carat = parseFloat(obj.polish_carat);
									var price = parseFloat(obj.price);
									var amount = parseFloat(obj.amount);
									
									var spcs = parseFloat($('#total-pcs').html());
									var scarat = parseFloat($('#total-carat').html());
									var sprice = parseFloat($('#total-price').html());
									var samount = parseFloat($('#total-amount').html());
									
									var tpcs = parseFloat(pcs) + parseFloat(spcs);
									var tcarat = parseFloat(carat) + parseFloat(scarat);
									var tprice = parseFloat(price) + parseFloat(sprice);
									var tamount = parseFloat(amount) + parseFloat(samount);

									
									var content = '<div class="box-row box-heading">';
									 content +='<div class="box-cell">&nbsp;</div>';
									 content +='<div class="box-cell a-right">Pcs</div>';
									 content +='<div class="box-cell a-right">Carats</div>';
									 content +='<div class="box-cell a-right">Price</div>';
									 content +='<div class="box-cell a-right">Amount</div>';
									 content +='</div>';
									 content +='<div class="box-row">';
									 content +='<div class="box-cell">In Parcel</div>';
									 content +='<div class="box-cell a-right">'+pcs.toFixed(2)+'</div>';
									 content +='<div class="box-cell a-right">'+carat.toFixed(2)+'</div>';
									 content +='<div class="box-cell a-right">'+price.toFixed(2)+'</div>';
									 content +='<div class="box-cell a-right">'+amount.toFixed(2)+'</div>';
									 content +='</div>';
									 content +='<div class="box-row">';
									 content +='<div class="box-cell">Selected</div>';
									 content +='<div class="box-cell a-right">'+spcs.toFixed(2)+'</div>';
									 content +='<div class="box-cell a-right">'+scarat.toFixed(2)+'</div>';
									 content +='<div class="box-cell a-right">'+sprice.toFixed(2)+'</div>';
									 content +='<div class="box-cell a-right">'+samount.toFixed(2)+'</div>';
									 content +='</div>';
									 content +='<div class="box-row box-footer">';
									 content +='<div class="box-cell">Total</div>';
									 content +='<div class="box-cell a-right">'+tpcs.toFixed(2)+'</div>';
									 content +='<div class="box-cell a-right">'+tcarat.toFixed(2)+'</div>';
									 content +='<div class="box-cell a-right">'+tprice.toFixed(2)+'</div>';
									 content +='<div class="box-cell a-right">'+tamount.toFixed(2)+'</div>';
									 content +='</div>';
									
									$('.existing-box').html(content);
									jQuery('#please-wait').hide();
								}
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
  padding: 25px 10px;
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


.checkbox + .checkbox, .radio + .radio {
  margin-top: 0px !important;
}
.checkbox, .radio {
  margin-bottom: 0px !important; 
  margin-top: 0px !important;
}

	</style>
</html>







	
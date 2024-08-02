<?php session_start(); ?>
<!DOCTYPE html>
<?php 

include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('parcel',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
} 

 $sku = '';
if(isset($_GET['sku']))
	$sku = $_GET['sku'];

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
								Parcel To Single / Box								
							</h1>
							<form id="formmixing" style="float:right">
								<input type="text" id="stone" onChange="loadGrid(this.value)" name="sku" value= "<?php echo  $sku; ?>" placeholder="SKU" >
							</form>
						</div><!-- /.page-header -->
						
						<div class="row">
						<?php
							
							include_once('parcelModel.php');
							include_once($daiDir.'Helper.php');
							
							$model  = new parcelModel();
							$helper  = new Helper();
							$attribute = $helper->getAttribute();							
							$groupUrl = $daiDir.'module/parcel/';														
							include_once($groupUrl."tsgrid.php");
							$skus =  $helper->getAllSku();
							?>
						</div>
						
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<?php
			include($daiDir."footer.php");
		?>
		</div><!-- /.main-container -->
	<div class="dialog-box-container" id="dialog-box-container" style="display:none;" >
	<div class="box-container" style="width:1100px" >
	</div>
	</div>
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
			var suggestions = new Array(			 

			  <?php foreach($skus as $k=>$v):?>

			  '<?php echo $v ?>',

			  <?php endforeach;?>''

			  );
			jQuery(function($) {
				
		jQuery( "#stone" ).autocomplete({
					source: suggestions
				});
			$("input[name='btype']").click(function() {
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
			
			function openParcelPopup(id)
			{
				$('#dialog-box-container').hide();
				$('#dialog-parcel-container').show();
				$('#box-id').val(id);	
			}
			function closeParcelPopup()
			{
				$('#dialog-box-container').show();
				$('#dialog-parcel-container').hide();				
			}
			function totalSelected()
			{
				var total = parseFloat($('.invenotry-cgrid ').find("input[type='checkbox']:checked").length);
				if(total > 0)
				{
					$('#addToBox').attr('disabled',false);
					$('#addToParcel').attr('disabled',false);
				}
				else
				{
					$('#addToBox').attr('disabled',true);
					$('#addToParcel').attr('disabled',true);
				}				
				//$('#selected-row').html(total);
			}			
			function countCheck(temp)
			{
				totalSelected();				
				if($(temp).is(':checked'))
				{
					$(temp).parents('.divTableRow').addClass('active');
				}
				else
				{
					$(temp).parents('.divTableRow').removeClass('active');
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
				
				$('#box-total-amount').val(tc.toFixed(2));
			}	
			function addNewRow(rid)
			{
				var total = $('.separate-table .divTableBody .divTableRow').length;
				if(total == rid)
				{
				var row = '<div class="divTableRow" id="rowid-'+n+'">';				
				row += '<div class="divTableCell">'+n+'</div>';
				row += '<div class="divTableCell"><input class=" col-sm-12"  name="record['+n+'][mfg_code]" id="mfg-'+n+'" onBlur="addNewRow('+n+')" type="text" ></div>';
				
				row += '<div class="divTableCell"><input class=" col-sm-12"  name="record['+n+'][sku]" onBlur="addNewRow('+n+')" id="sku-'+n+'" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record['+n+'][polish_pcs]" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record['+n+'][polish_carat]" id="pcarat-'+n+'" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record['+n+'][cost]" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record['+n+'][price]" id="price-'+n+'" onBlur="calAmount('+n+')" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record['+n+'][amount]" id="amount-'+n+'" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12"  name="record['+n+'][location]" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12"  name="record['+n+'][remark]" type="text"></div>';
				row += '<div class="divTableCell"><input class=" col-sm-12"  name="record['+n+'][lab]" type="text"></div>';
				<?php foreach($attribute as $key=>$v): ?>
				row += '<div class="divTableCell">';			
					row += '<input class=" col-sm-12"  name="record['+n+'][attr][<?php echo $key?>]" type="text">';
				row += '</div>';
				<?php endforeach; ?>
		
				row +='</div>';
				n = n + 1;
				$('.subform .separate-table .divTableBody').append(row);
				}
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
			
			function loadBox(id,t)
			{
				
				jQuery('#please-wait').show();
					var data = {'id':id,'type':t,'from':'box'};
					jQuery.ajax({
						url: '<?php echo $moduleUrl.'inventory/boxDetail.php' ?>', 
						type: 'GET',
						data: data,		
						success: function(result)
							{		
								if(result != "")
								{
									jQuery('#dialog-box-container').show();
									$('#dialog-box-container').html('<div class="box-container" >'+result+'</div>');									
									jQuery('#please-wait').hide();
								}
							}
					});
			}
			
			function removeFromBox(id)
			{
				 var checkValues = $('.invenotry-cgrid input[name=products]:checked').map(function()
				{
					return $(this).val();
				}).get();
				
				
				jQuery('#please-wait').show();
					var data = {'fn':'toSingle','id':id,'products':checkValues};
					jQuery.ajax({
						url: '<?php echo $moduleUrl.'parcel/parcelController.php' ?>', 
						type: 'POST',
						data: data,		
						success: function(result)
							{		
								if(result != "")
								{
									var obj = jQuery.parseJSON(result );						
									alert(obj.message);	
									jQuery('#please-wait').hide();
									loadSeprateBox(id,0);
								}
							}
					});
			}			
			function parcelFromBox(id)
			{
				var checkValues = $('.invenotry-cgrid input[name=products]:checked').map(function()
				{
					return $(this).val();
				}).get();
				
				
				var data = $('#form-package').serialize();
				data = data+ "&products="+checkValues;
				
				jQuery('#please-wait').show();
					
					jQuery.ajax({
						url: '<?php echo $moduleUrl.'parcel/parcelController.php' ?>', 
						type: 'POST',
						data: data,		
						success: function(result)
							{		
								if(result != "")
								{
									var obj = jQuery.parseJSON(result );						
									alert(obj.message);	
									jQuery('#please-wait').hide();
									if(obj.status)
									{
										$('#dialog-parcel-container').hide();		
										loadSeprateBox(id,0);
									}
								}
							}
					});
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
									 content +='<div class="box-cell">In Box</div>';
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
			function closeBox()
			{
				$('#dialog-box-container').hide();
			}			
				
				function closeParcelPopup()
			{
			
				$('#dialog-parcel-container').hide();
				$('#dialog-box-container').show();
			}

			function loadSeprateBox(id)
			{
				
				jQuery('#please-wait').show();
					var data = {'id':id,'type':0};
					jQuery.ajax({
						url: '<?php echo $moduleUrl.'inventory/sboxDetail.php' ?>', 
						type: 'GET',
						data: data,		
						success: function(result)
							{		
								if(result != "")
								{
									jQuery('#dialog-box-container').show();
									$('#dialog-box-container').html('<div class="box-container" >'+result+'</div>');									
									jQuery('#please-wait').hide();
								}
							}
					});
			}
			
			function separateFromBox(id)
			{
				var data = jQuery('#box-form').serialize();
				
				
				jQuery('#please-wait').show();
					
					jQuery.ajax({
						url: '<?php echo $moduleUrl.'box/boxController.php' ?>', 
						type: 'POST',
						data: data,		
						success: function(result)
							{		
								if(result != "")
								{
									var obj = jQuery.parseJSON(result );						
									alert(obj.message);	
									jQuery('#please-wait').hide();
									loadSeprateBox(id,0);
								}
							}
					});
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

			function loadGrid(sku)
			{
				
				if(sku != '')
				{
					var url      = window.location.href;
					url += "?sku="+sku;
					window.location.href = url;
				}
			}
			
			
			function totalSelected(temp,id)
			{
				var total = parseFloat($('#seprare-grid').find("input[type='checkbox']:checked").length);
				if(total > 0)
				{
					$('#removeToBox').attr('disabled',false);					
				}
				else
				{
					$('#removeToBox').attr('disabled',true);					
				}				
			
						
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


.divTableCell {
  float: left;
  padding: 0;
  text-align: center;
  width: 109px;
}

.divTableCell:first-child {
  width: 30px !important;
  padding-top: 3px;
}

.divTableHeading, .divTableBody, .divTableFooter {
  float: left;
  width: 100%;
}
.divTableHeading {
  border-bottom: 1px solid #f1f1f1;
  border-top: 1px solid #f1f1f1;
  margin-bottom: 10px;
  margin-top: 10px;
  padding-bottom: 10px;
  padding-top: 5px;
}
.divTableHeading .divTableCell {
  color: #666;
  font-size: 13px;
  font-weight: bold;
  letter-spacing: 1px;
}
.divTableRow {
  float: left;
  margin: 0;
  width: 100%;
}
.divTable {
  font-size: 14px;
}
.a-right {
 
  text-align: right !important;
}
.add-more {
  background-color: #69aa46;
  border-radius: 100%;
  color: #fff;
  font-size: 14px;
  height: 25px;
  padding: 6px 5px 5px;
  width: 25px;
  cursor: pointer;
}

.delete-more {
  background-color: #dd5a43;
  border-radius: 100%;
  color: #fff;
  font-size: 14px;
  height: 22px;
  padding: 4px 5px 5px;
  width: 22px;
  cursor: pointer;
}
.divTableBody .divTableRow .divTableCell input {
  color: #333;
  font-size: 12px;
  padding: 1px 2px 3px;
}

.subform.invenotry-cgrid {
	float: left;
	padding: 0 10px 25px;
	width: 100%;
}
.box-grid .divTable{	
	width: 1800px;
}
.divTable.separate-table {
	width: 2300px;
}
.box-grid .divTableBody {	
	height: 300px;
	overflow-y: scroll;
}

	</style>
</html>







	
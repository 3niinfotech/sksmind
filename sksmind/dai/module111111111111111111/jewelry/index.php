<?php session_start(); ?>
<!DOCTYPE html>
<?php 
include("../../../database.php");
include("../../../variable.php");
include_once("../../../checkResource.php");
$flag = (in_array('all',$userResource) || in_array('inventory',$userResource))  && in_array($_SESSION['companyId'],$companyResource);
if (!isset($_SESSION['username']) || !$flag )
{
	header("Location: ".$mainUrl);	
}
?>	
<html lang="en">
<?php include($daiDir."head.php"); ?>	

	<body class="no-skin">
		<?php include($daiDir."header.php");?>
		<div class="main-container ace-save-state" >
			
			<?php include($daiDir."left.php");?>
			<?php include($daiDir."message.php");?>
			<div class="main-content" id="jmain-container">
				
						<?php
						
							
							$s = "";
							if(isset($_GET['s'])) 
								$s = $_GET['s'];
								
							include_once($daiDir.'Helper.php');
							$helper  = new Helper();												
							$groupUrl = $daiDir.'module/jewelry/';														
							
							?>
							<div id="main-jewelry-grid"></div>
				
			</div><!-- /.main-content -->

		<?php include($daiDir."footer.php"); ?>
		</div><!-- /.main-container -->
<div id="please-wait" style="display:none">
	<img src="<?php echo  $daiUrl;?>assets/images/loading.gif" />
</div>
<div class="synchro-process">

<?php //include($daiDir."Process.php"); ?> 
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
		
<script src="<?php echo  $daiUrl;?>assets/js/bootbox.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/buttons.colVis.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/dataTables.select.min.js"></script>
		
		
		<script src="<?php echo  $daiUrl;?>assets/js/jquery-ui.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo  $daiUrl;?>assets/js/ace-elements.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/ace.min.js"></script>
		<script type="text/javascript">
			
			jQuery(function($) 
			{
				loadGrid();
			
			});
		
			function closeBox()
			{
				$('#dialog-box-container').hide();
			}
			
			function loadGrid(data)
			{
					var data = '';
					jQuery('#please-wait').show();
				
					jQuery.ajax({
						url: '<?php echo $moduleUrl.'jewelry/jgrid.php' ?>', 
						type: 'POST',
						data:data,	
						success: function(result)
							{		
								if(result != "")
								{
									$('#main-jewelry-grid').html(result);									
									jQuery('#please-wait').hide();
								}
							}
					});
			}
			
			function deleteSale(url)
			{
				bootbox.confirm("Are you sure you want to delete ?", function(result) {
					if(result) 
					{
							location.href=url;
					}
				});
			}
			function loadMemoForm(rs,type)
				{
					 var checkValues = $('#jewelry-form input[name=sku]:checked').map(function()
					{
						return $(this).val();
					}).get();
					
					
					if(rs)
					{
						var data = {'id':rs,'type':type};
					}
					else
					{
						var data = {'ids':checkValues,'type':type};
					}
					
					if(checkValues.length)
					{	
						jQuery('#please-wait').show();
						
						jQuery.ajax({
							url: '<?php echo $moduleUrl.'jewelry/sendToOutward.php' ?>', 
							type: 'POST',
							data: data,		
							success: function(result)
								{		
									if(result != "")
									{
										jQuery('#dialog-box-container').show();
										$('#dialog-box-container').html('<div class="box-container-memo" >'+result+'</div>');									
										jQuery('#please-wait').hide();
										
										$( "#date, #invoicedate" ).datepicker({
											showOtherMonths: true,
											selectOtherMonths: false,
											dateFormat: 'yy-mm-dd',
										});
									}
								}
						});
					}
					else
					{
						alert('Please Select Item');
					}
					
				}
		
		</script>

		
	</body>
<style>
#jmain-container{background:#ccc;width:100%;float:left;padding:20px;
min-height:500px;
margin-bottom:70px;

}
.jewelry-block
{
	background: #fff !important;
	width: 100%;
	float: left;
	padding: 20px 100px;
	-webkit-box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.75);
-moz-box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.75);
box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.75);
margin-bottom: 25px;
}
.button-group {
	border-bottom: 1px solid #999 !important;
	float: left;
	margin-bottom: 10px;
	margin-left: 0%;
	width: 100%;
}

.box-container-memo {
    width: 1250px !important;
}

.jewelry-info{
	width:100%;
	float:left;
}
.jewelry-table, .diamond-htable, .diamond-btable
{
	width:100%;	
}

table {
    border-collapse: collapse;
}
table, td, th {
    border: 1px solid #ccc;
}
.diamond-th .diamond-col{text-align:center;}
.jewelry-th
{
	width:100%;
	background:#FFFFCC;
	color:#9C0006;
	text-transform:uppercase;
}
.jewelry-col
{padding:5px;text-align:center;}

.jewelry-col1{width:20%;}
.jewelry-col2,.jewelry-col4{width:10%;}
.jewelry-col3{width:70%;}
.jewelry-body{float:left;width:100%;}

.jewelry-col1 img{width:150px;height:150px;}

.dc1,
.dc2,
.dc3,
.dc7
{width:18%;}

.dc4,
.dc5,
.dc6{width:9%}

.jewelry-col3 {
   vertical-align: top;
   padding:0px;
 }
.diamond-th th{border-bottom:0px;}

.diamond-btable tr:first-child td {
    border-top: 0px !important;
}
.a-right{text-align:right;}
.diamond-col{padding:2px 4px;}
</style>
</html>

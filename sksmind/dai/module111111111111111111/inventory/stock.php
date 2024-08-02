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
		<div class="main-container ace-save-state" id="main-container">
			
			<?php include($daiDir."left.php");?>
			<?php include($daiDir."message.php");?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="row">
						<?php
						
							
							$s = "";
							if(isset($_GET['s'])) 
								$s = $_GET['s'];
								
							
							include_once('inventoryModel.php');
							include_once($daiDir.'Helper.php');
							
							$model  = new inventoryModel();
							$helper  = new Helper();												
							$groupUrl = $daiDir.'module/inventory/';														
							$TotalsiteUpdate = $helper->getSiteUpdated();
							$_SESSION['TotalsiteUpdate'] = count($TotalsiteUpdate);
							
							$TotalRapUpdate = $helper->getRapUpdated();
							$_SESSION['TotalRapUpdate'] = count($TotalRapUpdate);
							
							
							?>
							<div id="main-inventory-grid"></div>
						</div>
					</div><!-- /.page-content -->
				</div>
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
		

		<script src="<?php echo  $daiUrl;?>assets/js/buttons.colVis.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/dataTables.select.min.js"></script>
		
		<script src="<?php echo  $daiUrl;?>assets/js/jquery.gritter.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/jquery-ui.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo  $daiUrl;?>assets/js/ace-elements.min.js"></script>
		<script src="<?php echo  $daiUrl;?>assets/js/ace.min.js"></script>
		<script type="text/javascript">
			var sorttype = 'asc';
			var sortFilter = '';
			//alert($(window).height());
			jQuery(function($) 
			{
				$('.header-li').show();
				
				loadGrid();
				
				$('#white-filter-form input').attr("disabled",true);
				$('#fancy-filter-form input').attr("disabled",true);	

			   Date.prototype.toInputFormat = function()
			   {
			   var yyyy = this.getFullYear().toString();
			   var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
			   var dd  = this.getDate().toString();				
			   return  yyyy  + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0] ) ;  					
			   };
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
			   $('#fancy-search').on('click',function()
			   {				
					$('#fancy-filter-form').toggle();
					$('#white-filter-form').hide();
					$('#white-filter-form input').attr("disabled",true);
					$('#fancy-filter-form input').attr("disabled",false);					
			   });
			    $('#white-search').on('click',function()
				{				
					$('#white-filter-form').toggle();
					$('#fancy-filter-form').hide();
					$('#white-filter-form input').attr("disabled",false);
					$('#fancy-filter-form input').attr("disabled",true);		
			   });
			   
			   $("#packet,#cfrom,#cto").bind("keypress", {}, keypressInBox);
			  

			});
			
		function countCheck(temp)
			{
							
				if($(temp).is(':checked'))
				{
					$(temp).parents('.divTableRow ').addClass('active');
					$(temp).parents('.divTableRow ').addClass('infobox-3nipink');
					
				}
				else
				{
					$(temp).parents('.divTableRow ').removeClass('active');
					$(temp).parents('.divTableRow ').removeClass('infobox-3nipink');
				}
				calcSelected();
							
			}
			function allCheck(temp)
			{
							
				if($(temp).is(':checked'))
				{
					$('.divTableBody input:checkbox').parents('.divTableRow ').addClass('active');
					$('.divTableBody input:checkbox').prop('checked',true);
					$('.divTableBody .divTableRow ').addClass('infobox-3nipink');
				}
				else
				{
					$('.divTableBody input:checkbox').parents('.divTableRow ').removeClass('active');
					$('.divTableBody input:checkbox').prop('checked',false);
					$('.divTableBody .divTableRow ').removeClass('infobox-3nipink');
				}
				calcSelected();
							
			}
			
			function closeBox()
			{
				$('#dialog-box-container').hide();
			}
			function closeProgress()
			{
				$('.synchro-process').hide();
			}
			function loadGrid(data)
			{
					
					jQuery('#please-wait').show();
					var ftype = "";
					if($('#white-filter-form').css('display') == 'none')
					{
					   ftype ='fancy'
					}
					else if($('#fancy-filter-form').css('display') == 'none')
					{
						ftype ='white'
					}
					if($('#fancy-filter-form').css('display') == 'none' && $('#white-filter-form').css('display') == 'none')
					{
						ftype = "";
					}
					
					jQuery.ajax({
						url: '<?php echo $moduleUrl.'inventory/stockgrid.php?form_type=';?>'+ftype, 
						type: 'POST',
						data:data,	
						success: function(result)
							{		
								if(result != "")
								{
									$('#main-inventory-grid').html(result);									
									jQuery('#please-wait').hide();
									$("#packet,#cfrom,#cto").bind("keypress", {}, keypressInBox);
									
									if(ftype =='fancy')
									{
										$('#white-filter-form input').attr("disabled",true);
										$('#fancy-filter-form input').attr("disabled",false);	
									}
									else if(ftype =='fancy')
									{
										$('#white-filter-form input').attr("disabled",false);
										$('#fancy-filter-form input').attr("disabled",true);	
									}
									else if(ftype=="")
									{
										$('#white-filter-form input').attr("disabled",true);
										$('#fancy-filter-form input').attr("disabled",true);	
									}
								}
							}
					});
			}
			function submitFilter()
			{
				//console.log($('#filter-form').serialize());
				
				loadGrid($('#filter-form').serialize());
			}
			function keypressInBox(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if (code == 13) { //Enter keycode                        
				e.preventDefault();

				loadGrid($('#filter-form').serialize());
				}
			};

			function sortForFilter(filter)
			{
				if(filter == sortFilter && sorttype == 'asc')
				{
					sorttype = 'desc';
				}
				else
				{
					sorttype = 'asc';
				}
				sortFilter = filter;
				jQuery('#sortFilter').val(filter);
				jQuery('#sortType').val(sorttype);
				submitFilter();
			}
			
			function saveStock()
			{
				$('#grid-form').submit();
			}
		</script>

		
	</body>
	<style>
	.page-header {
	  float: left;	 
	  padding-bottom: 0;
	  width: 100%;
	}
.subform {
  float: left;
  overflow-x: scroll;
  padding: 5px 10px;
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
  text-align: left;
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
.main-grid .divTableBody{
	height:800px;
	/* overflow-y:scroll; */
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
  padding-top: 15px;
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
.subform.invenotry-cgrid.main-grid {
  padding: 0 10px 0px !important;
  width: 100%;
}
.invenotry-cgrid .divTableHeading {
  margin-bottom: -1px; 
}
.invenotry-cgrid .divTableHeading .divTableCell{
	cursor:pointer !important; 
}
.inventory-color {
  border:0px;
padding:5px;  
  margin-bottom: 1px;
}

.inventory-container {
	margin-top: 4px;
	padding: 3px 0px;
}
/** Filter css  **/
.filter {
  background-color: #f1f1f1;
  border-bottom: 2px solid #ccc;
  float: left;
  padding-left: 10px;
  position: relative;
  width: 100%;
}

#white-filter-form .filter {
  background-color: #f5f5f5;
  }
.f-shape {
  border: 1px solid #ccc;
  float: left;
  width: 118px;
  margin-right:3px;
}
.f-label {
  background-color: #999;
  color: #fff;
  float: left;
  padding: 2px;
  text-align: center;
  width: 100%;
}
.f-ulli {
  float: left;
  height: 76px;
  list-style: outside none none;
  margin-bottom: 5px !important;
  margin-left: 10px;
  overflow-y: scroll;
  width: 91%;
}
.f-ulli > li {
  overflow-x: hidden;
  padding-bottom: 2px;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.f-left {
  float: left;
  width: 15%;
  padding-top: 10px;
}
.f-right {
  float: right;
  width: 85%;
}

.f-package{ float: left;
  width: 45%;}
.f-package input {
  float: left;
  width: 100%;
}
.f-carat input {
	float: left;
	width: 45%;
	margin-bottom: 10px;
}
.f-carat input:first-child{margin-right:2%;}
.f-l-label 
{
 float: left;
font-size: 12px;
font-weight: bold;
letter-spacing: 1px;
text-align: center !important;
width: 100%;
}
.f-memo {
  border-top: 1px solid #ccc;
  float: left;
  margin-top: 10px;
  padding-top: 10px;
  width: 100%;
}
.f-memo > ul {
  float: left;
  margin-left: 0;
  width: 100%;
}
.f-memo li {
  float: left;
  width: 90px;
}
ul {
  list-style: none;
}
.f-button {
	background: #9abc32 none repeat scroll 0 0;
	border: 0 none;
	color: #fff;
	height: 32px;	
	padding: 2px 20px;
	position: absolute;
	text-align: center;
	right: 7%;
	top: -39px;
}
.mail-button {
	background: #9abc32 none repeat scroll 0 0;
	border: 0 none;
	color: #fff;
	height: 32px;	
	padding: 2px 15px;
	position: absolute;
	text-align: center;
	right: 3px;
	top: -39px;
}
.mains-grid .color-total-count {
  color: #9abc32;
  letter-spacing: 0px;
  margin-top: 13px;
}
.inventory-color-cell {
  padding-top: 8px;
}
.inventory-color {
  padding-top: 0 !important;
}
#fancy-filter-form .f-shape {  
  width: 138px;
}
.f-ch-input {
  float: left;
  margin-right: 5px;
}
.inventory-color-cell {
	width: auto;
}
input.ace.bblue[type="checkbox"] + .lbl::before
{ 
  border: 2px solid #6fb3e0; 
}
input.ace.bred[type="checkbox"] + .lbl::before
{ 
  border: 2px solid #d53f40; 
}
input.ace.bgreen[type="checkbox"] + .lbl::before
{ 
  border: 2px solid #9abc32; 
}
.changeform {
  height: 250px;
  overflow-y: scroll;
}

input.ace.bgrey[type="checkbox"] + .lbl::before
{ 
  border: 2px solid #999; 
}

.synchro-process {
	position: fixed;
	bottom: 0px;
	right: 10px;
	z-index: 999999999;
	
	border-radius: 2px;
	width: 320px;
}

.upload-process {
	z-index: 999999999;
	padding: 10px;
	border: 1px solid #ccc;
	width: 300px;
	background: #fff;
	border-radius: 2px;
	margin-bottom:10px;
	width:100%;
	float:left;
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
#main-inventory-grid .divTableCell{
text-align:center;
}
#main-inventory-grid .divTableCell:nth-child(2){
text-align:left;
}
.infobox-3nipink {
    background-color: #CB6FD7 !important;
    border-color: #CB6FD7 !important;
	color:#fff !important;
}


.ni-color{
color:#000!important
}

.infobox-dark .ni-color
{
color:#fff !important;
}



.invenotry-cgrid .divTable {
  position: relative;
}
#packet{
text-transform: uppercase;
position: absolute;
top: -37px;
right: 7%;
}
.invenotry-cgrid .divTableRow, .invenotry-cgrid .divTableHeading {position: relative;}
.fixsku {z-index:1!important}

.invenotry-cgrid .divTable
{
  position: relative;
} 
.form-group{
padding-left: 5px;
padding-right: 5px;}

.inventory-color .btn.btn-info {
	padding: 0px 4px !important;
	margin:0px;
}
.inventory-color .btn.btn-success {
	padding: 0px 4px !important;
	margin:0px;
}
.dialog-box-container {	
	position: fixed;	
}
.sendToOut .divTable {
	width: 2220px;
}
.grid-action
{
font-size:18px;
font-weight:bold;
padding:0px 5px;
cursor:pointer;
opacity:0.3;	
}

.grid-action:hover,
.grid-action.active
{
	opacity:1;	
}

#stockdate {
	position: absolute;
	width: 100px;
	top: 12px;
	right: 215px;
}

#dataChange {
	position: absolute;
	width: 120px;
	top: 12px;
	right: 400px;
}

</style>
</html>

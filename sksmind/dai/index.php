<?php session_start(); ?>
<!DOCTYPE html>
<?php
include("../variable.php"); 
include("../database.php");
include_once("../checkResource.php");

if (!isset($_SESSION['username']) || !in_array($_SESSION['companyId'],$companyResource) )
{
	header("Location: ".$mainUrl);	
	exit;
}

unset($_SESSION['last_inward']);
?>	
<html lang="en">
	<?php include("head.php"); ?>

	<body class="no-skin">
		<?php 
		if($_SESSION['companyId'] == 1)
			include("header.php");
		else
			include("header_jewelry.php");
		?>
		<div class="main-container ace-save-state" id="main-container">
			
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>
			<?php 
			if($_SESSION['companyId'] == 1)
				include("left.php"); 
			else 
				include("left_jewelry.php"); 
			?>
			
			<?php 
			include_once('Helper.php');
			$helper = new Helper($cn);
			
			$icolors = array('item-orange','item-blue','item-pink','item-default','item-green','item-grey','item-purple','item-red');
	
			$party = $helper->getPartyOption();
			$party[''] = '';
			?>
			
			<div class="main-content">
				<div class="main-content-inner">
					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Inventory Dashboard								
							</h1>
						</div><!-- /.page-header -->
	
	
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="alert alert-block alert-success alert-success1">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>

									<i class="ace-icon fa fa-check green"></i>

									Welcome to
									<strong class="green">
									<?php echo $_SESSION['companyname']; ?>								
									</strong>,
									 Diamond Account Inventory Application.
									 
									 
								</div>
			
							<!--	<div class="col-sm-6 dash-left">
									<div class="widget-box transparent" id="recent-box">
										<div class="widget-header">
											<h4 class="widget-title lighter smaller">
												<i class="ace-icon fa fa-rss orange"></i>Sale Due Payments
											</h4>
										</div>

										<div class="widget-body">
											<div class="widget-main padding-2">
												<div class="tab-content padding-2">
													<div id="task-tab" class="tab-pane active">														

														<ul id="tasks2" class="item-list">
															<li class="item-orange clearfix">																	
																<ul class="duepayment">
																	<li style="font-weight:bold">Party</li>
																	<li style="font-weight:bold">Entry No</li>																																				
																	<li style="font-weight:bold" class="t-right">Total</li>
																	<li style="font-weight:bold" class="t-right">Paid</li>
																	<li style="font-weight:bold" class="t-right">Balance</li>
																</ul>
															</li>
															
															<?php 
															
															foreach($helper->dueSalePayment() as $dp) : ?>
															<li class="item-orange clearfix">																	
																<ul class="duepayment">
																	<li><?php echo $party[$dp['party']]?></li>																		
																	<li><?php echo $dp['entryno']?></li>																		
																	<li class="t-right"><?php echo $dp['total']?></li>
																	<li class="t-right"><?php echo $dp['paid_amount']?></li>
																	<li class="t-right"><?php echo $dp['due_amount']?></li>
																</ul>
															</li>
															<?php endforeach;?>
														</ul>
													</div>
												</div>
											</div><!-- /.widget-main -->
									<!--	</div><!-- /.widget-body -->
									<!--</div><!-- /.widget-box -->
									
								<!--	<div class="widget-box transparent" id="recent-box">
										<div class="widget-header">
											<h4 class="widget-title lighter smaller">
												<i class="ace-icon fa fa-rss orange"></i>Purchase Due Payments
											</h4>
										</div>

										<div class="widget-body">
											<div class="widget-main padding-2">
												<div class="tab-content padding-2">
													<div id="task-tab" class="tab-pane active">														

														<ul id="tasks1" class="item-list">
															<li class="item-orange clearfix">																	
																<ul class="duepayment">
																	<li style="font-weight:bold">Party</li>
																	<li style="font-weight:bold">Entry No</li>																																				
																	<li style="font-weight:bold" class="t-right">Total</li>
																	<li style="font-weight:bold" class="t-right">Paid</li>
																	<li style="font-weight:bold" class="t-right">Balance</li>
																</ul>
															</li>
															
															<?php 
															
															foreach($helper->duepurchasePayment() as $dp) : ?>
															<li class="item-orange clearfix">																	
																<ul class="duepayment">
																	<li><?php echo $party[$dp['party']]?></li>																		
																	<li><?php echo $dp['entryno']?></li>																		
																	<li class="t-right"><?php echo $dp['total']?></li>
																	<li class="t-right"><?php echo $dp['paid_amount']?></li>
																	<li class="t-right"><?php echo $dp['due_amount']?></li>
																</ul>
															</li>
															<?php endforeach;?>
														</ul>
													</div>
												</div>
											</div><!-- /.widget-main -->
									<!--	</div><!-- /.widget-body -->
								<!--	</div><!-- /.widget-box -->

									
								<!--</div><!-- /.col -->

							<!--	<?php 
								
								$notes = $helper->getMyNotes();	?>
								<div class="col-xs-6">
									<div class="widget-body">
										<h4 class="smaller lighter green" style="border-bottom: 1px solid;">
											<a href="javascript:void(0);" onclick="addNotes()" class="green">
												<i class="ace-icon fa fa-plus bigger-140"></i>
											</a>
											
											My Task List
										</h4>
										<div id="task-tab" class="tab-pane active" style="height:400px;overflow-y:auto;">
											<ul id="tasks" class="item-list">
												
												<?php foreach($notes as $nt): 
												$status = $nt['status'];
												?>
												<li class="<?php echo $icolors[rand(0,7)];?> clearfix <?php echo ($status)?'selected':'';?>" id="listnote-<?php echo $nt['id'] ?>">												
													
													<label class="inline">
														<input type="checkbox" class="ace  <?php echo ($status)?'Boxchecked':''; ?> " onChange="changeStatus(this,<?php echo $nt['id'] ?>)" />
														<span class="lbl"> <span id="note-<?php echo $nt['id'] ?>" ><?php echo $nt['content']; ?></span>
														</span>
													</label>
													<input class="ace noteTextbox" id="editnote-<?php echo $nt['id'] ?>" type="text"  value="<?php echo $nt['content']; ?>" onBlur="changeTextPrice(this.value,<?php echo $nt['id'] ?>)" >
													<div class="pull-right action-buttons">
														<a href="javascript:void(0);" onclick="changeNote(<?php echo $nt['id'] ?>)" class="blue">
															<i class="ace-icon fa fa-pencil bigger-130"></i>
														</a>

														<span class="vbar"></span>

														<a href="javascript:void(0);" onclick="deleteNote(<?php echo $nt['id'] ?>)" class="red">
															<i class="ace-icon fa fa-trash-o bigger-130"></i>
														</a>

													</div>
													
												</li>
												<?php endforeach; ?>	
													
											</ul>
										</div>
									</div><!-- /.widget-body -->
							<!--	</div>			
								<div class="col-xs-12">
									<div class="widget-body party-dash ">
											<div class="widget-main padding-2">
												<div class="tab-content padding-2">
													<div id="task-tab" class="tab-pane active">														
														<h4 class="smaller lighter green" style="border-bottom: 1px solid;">
															<a href="javascript:void(0);" onclick="addNotes()" class="green">
																<i class="ace-icon fa fa-user bigger-140"></i>
															</a>
															
															My Company List
														</h4>
														<ul id="tasks1" class="item-list" style="height: 300px;overflow-y: auto;">
															<li class="item-orange clearfix">																	
																<ul class="duepayment">
																	<li style="font-weight:bold">Party</li>
																	<li style="font-weight:bold">Address</li>																																				
																	<li style="font-weight:bold" >Email</li>
																	<li style="font-weight:bold" >Number</li>
																	<li style="font-weight:bold" >Person</li>
																</ul>
															</li>
															
															<?php 
															
															foreach($helper->getPartyByUser() as $dp) : ?>
															<li class="item-orange clearfix">																	
																<ul class="duepayment">
																	<li><?php echo $dp['name']?></li>																		
																	<li><?php echo $dp['address']?></li>																		
																	<li ><?php echo $dp['email']?></li>
																	<li ><?php echo $dp['contact_number']?></li>
																	<li ><?php echo $dp['contact_person']?></li>
																</ul>
															</li>
															<?php endforeach;?>
														</ul>
													</div>
												</div>
											</div><!-- /.widget-main -->
									<!--	</div><!-- /.widget-body -->
								<!--	</div><!-- /.widget-box -->
								
				
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<?php
			include("footer.php");
		?>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="assets/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="assets/js/excanvas.min.js"></script>
		<![endif]-->
		<script src="assets/js/jquery-ui.custom.min.js"></script>
		<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
		<script src="assets/js/jquery.easypiechart.min.js"></script>
		<script src="assets/js/jquery.sparkline.index.min.js"></script>
		<script src="assets/js/jquery.flot.min.js"></script>
		<script src="assets/js/jquery.flot.pie.min.js"></script>
		<script src="assets/js/jquery.flot.resize.min.js"></script>

		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>	
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
		var n=10001;
		
jQuery(function($) {
	//Android's default browser somehow is confused when tapping on label which will lead to dragging the task
				//so disable dragging when clicking on label
				var agent = navigator.userAgent.toLowerCase();
				if(ace.vars['touch'] && ace.vars['android']) {
				  $('#tasks').on('touchstart', function(e){
					var li = $(e.target).closest('#tasks li');
					if(li.length == 0)return;
					var label = li.find('label.inline').get(0);
					if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
				  });
				}
			
				$('#tasks').sortable({
					opacity:0.8,
					revert:true,
					forceHelperSize:true,
					placeholder: 'draggable-placeholder',
					forcePlaceholderSize:true,
					tolerance:'pointer',
					stop: function( event, ui ) {
						//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
						$(ui.item).css('z-index', 'auto');
					}
					}
				);
				//$('#tasks').disableSelection();
				$('#tasks input:checkbox').removeAttr('checked').on('click', function(){
					if(this.checked) $(this).closest('li').addClass('selected');
					else $(this).closest('li').removeClass('selected');
				});
			
			
				//show the dropdowns on top or bottom depending on window height and menu position
				$('#task-tab .dropdown-hover').on('mouseenter', function(e) {
					var offset = $(this).offset();
			
					var $w = $(window)
					if (offset.top > $w.scrollTop() + $w.innerHeight() - 100) 
						$(this).addClass('dropup');
					else $(this).removeClass('dropup');
				});	
				
				jQuery('.Boxchecked').click();
				
				setTimeout(function(){ jQuery('.alert-success1').remove() }, 3000);

				loadUsers();
});	
function changeNote(id)
{
		jQuery('#note-'+id).hide();
		jQuery('#editnote-'+id).show();
}

function changeTextPrice(note,id)
{
	var data = {'id':id,'note':note,'fn':'changeNote'};
	jQuery.ajax({
	url: '<?php echo $moduleUrl.'note/noteController.php'?>', 
	type: 'POST',
	data: data,		
	success: function(result)
	{		
		var obj = jQuery.parseJSON(result);
		if(obj.status)
		{
			jQuery('#note-'+id).show();
			jQuery('#editnote-'+id).hide();
			jQuery('#note-'+id).html(note);
		}
	}
	});
		
}

function changeStatus(box,id)
{
	var  status;
	if(jQuery(box).is(':checked'))
	{	status = 1;}
	else
	{	status = 0;}
	
	var data = {'id':id,'status':status,'fn':'changeStatus'};
	jQuery.ajax({
	url: '<?php echo $moduleUrl.'note/noteController.php'?>', 
	type: 'POST',
	data: data,		
	success: function(result)
	{		
		var obj = jQuery.parseJSON(result);
		if(obj.status)
		{
			jQuery('#note-'+id).show();
			jQuery('#editnote-'+id).hide();
			//jQuery('#note-'+id).html(note);
		}
	}
	});
		
}

function addNewNote(note,id)
{
	
	var data = {'content':note,'fn':'addNewNote'};
	jQuery.ajax({
	url: '<?php echo $moduleUrl.'note/noteController.php'?>', 
	type: 'POST',
	data: data,		
	success: function(result)
	{		
		var obj = jQuery.parseJSON(result);
	
		if(obj.status)
		{
	
			jQuery('#note-'+id).show();
			jQuery('#editnote-'+id).hide();
			jQuery('#note-'+id).html(note);
		}
	}
	});
		
}
function deleteNote(id)
{
	if(confirm("Are you sure you want to delete?"))
	{
		var data = {'id':id,'fn':'deleteNote'};
		jQuery.ajax({
		url: '<?php echo $moduleUrl.'note/noteController.php'?>', 
		type: 'POST',
		data: data,		
		success: function(result)
		{		
			var obj = jQuery.parseJSON(result);
			if(obj.status)
			{
				jQuery('#listnote-'+id).hide();			
			}
		}
		});
	}
		
}
function addNotes()
{	
	var html = '<li class="<?php echo $icolors[rand(0,7)];?> clearfix " >';												
	html += '<label class="inline">';
	html += '<input type="checkbox" class="ace" /><span class="lbl"> <span id="note-'+n+'" ></span>';
	html += '</span></label>';
	html += '<input class="ace noteTextbox" id="editnote-'+n+'" type="text"  onBlur="addNewNote(this.value,'+n+')" style="display:block">';
	html += '</li>';
	
	jQuery('#tasks').prepend(html);
	n++;
}	
</script>
<style>
.noteTextbox{border: 1px solid rgb(204, 204, 204); width: 80%; position: absolute; z-index: 99999 ! important; padding: 0px !important; font-size: 13px; display: block;top: 8px;left: 28px;display:none;}

.dash-left .item-list{height:200px; overflow-y:auto;}
.dash-left .item-list > li {
	padding: 0px;
	background-color: #FFF;
	margin-top: -1px;
	position: relative;
}

.party-dash .duepayment > li {
	width: 20%;	
}

.party-dash .item-list > li {
padding: 0px;
}
</style>		
	</body>
</html>







	
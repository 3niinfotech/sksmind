<?php
session_start();
include("../variable.php");
include("../database.php");
include_once("../checkResource.php");
if (!isset($_SESSION['username']))
{
	header("Location: ".$mainUrl);	
}
?>
<html>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<?php 
		include("head.php");
	?>	
	<body>
		<?php
			include("header.php");
			
			
			if (isset($_SESSION['userid']))
			{
			?>
				<div class = "main_container">
				<?php
					if (isset($_SESSION['su_cat']))
					{ ?>
						<div class = "success_div" style = "width:100%; height:50px;"><?php echo $_SESSION['su_cat']; ?></div>
					<?php
						unset($_SESSION['su_cat']);
					}
					?>	
					<?php
					if (isset($_SESSION['error_cat']))
					{ ?>
						<div class = "error_div success" style = "width:100%; height:50px;"><?php echo $_SESSION['error_cat']; ?></div>
					<?php
						unset($_SESSION['error_cat']);
					}
					?>	
					
					<div class="template_heading"><span class="template_left">Email Track Report's</span><span class="template_right"><a style = "margin:0 5px;" class="button round blue" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a></span></div>
					
					<div class = "main mail_email">
						<div class = "main_left customer_list">
								<?php
								    $user_id =  $_SESSION['userid'];
									if($_SESSION['type'] == 'admin')
									{	
										$rs=mysql_query("select * from email_record ORDER BY id DESC");
									}
									else
									{
										$rs=mysql_query("select * from email_record where user_id= ".$user_id."  ORDER BY id DESC");
									}	

									
									$model = array();
									$index = 1;
									$customer_grid = array();
									while ($company_list =  mysql_fetch_assoc($rs))
									{
										$user_rs1 = mysql_query("select * from user where user_id = ".$company_list['user_id']);
										$user_list1 =  mysql_fetch_assoc($user_rs1);
										$user_name = $user_list1['user_name'];
										
										$template = mysql_query("select * from template where id = ".$company_list['template']);
										$template_col =  mysql_fetch_assoc($template);
										$template_name = $template_col['title'];
										
										$no_recipient  = $company_list['emails'];
										$send_date  = $company_list['date'];
										$send_date = date("d F Y H:i:s", strtotime($send_date));
										$record_id  = $company_list['id'];
										
										$c_last_li_html = "";
										
										$c_last_li_html = '<a href = "view-track-report.php?id='.$record_id.'"><span class="fa fa-eye"></span></a>';
										
										if($_SESSION['type'] == 'admin'):
											$c_last_li_html .= "<a class='fa fa-trash' onclick='deleteEmail(\"".$record_id."\")'></a>";
											
										endif;
										
										$checkbox_delete = '<input name="checkboxtrackemail[]" type="checkbox" value="'.$record_id.'" class = "checkboxtrackemail">';
										
										$customer_grid[] = array('mass_delete' => "$checkbox_delete" ,'id' => "$index" ,'template_name' => "$template_name",'no_recipient' => "$no_recipient",'send_date' => "$send_date",'user_name' => "$user_name",'last_li' => "$c_last_li_html");
										$index++;
									}
									$customer_json = json_encode($customer_grid);
								
								?>
							    <div class="container">
									<table class="table table-striped"></table>
								</div> 
								<script>
									jQuery(function(jQuery){
										jQuery('.table').footable({
											"paging": {
												"enabled": true
											},
											"filtering": {
												"enabled": true
											},
											"sorting": {
												"enabled": true
											},
											"columns": jQuery.get("customer/email_record_columns.json"),
											"rows": <?php echo $customer_json; ?>//jQuery.get("customer/rows.json")
										});
									});
								</script>
						</div>
					</div>
				</div>	
			<?php
			}
			else
			{
				echo "user session expired";
				header('Location: index.php');
			}	
		?>
		
		<script>
				function deleteEmail(track_id)
				{
					jQuery.prompt("Are you sure you want to delete this item? It cannot be restored at a later time!", {
						title: "Delete Confirmation",
						buttons: { "Yes, I'm Ready": true, "No, Lets Wait": false },
						submit: function(e,v,m,f){
							if(v)
							{
								var data = {'function':'DeleteTrackRecord','id':track_id};
								jQuery.ajax({
									url: 'controller/MasterController.php', 
									type: 'POST',
									data: data,
									success: function(result)
										{
										   setTimeout(function(){ location.reload();}, 1000);
										}
									});	 
							}	
						}
					});
				}
				
				function massdeleteEmail()
				{
					var values = new Array();
					jQuery.each(jQuery("input[name='checkboxtrackemail[]']:checked"), function() {
						values.push(jQuery(this).val());
					});
					
					if(values.length)
					{
						jQuery.prompt("Are you sure you want to delete this item? It cannot be restored at a later time!", {
						title: "Delete Confirmation",
						buttons: { "Yes, I'm Ready": true, "No, Lets Wait": false },
							submit: function(e,v,m,f){
								if(v)
								{
									var data = {'function':'MassDeleteTrackRecord','massid':values};
									jQuery.ajax({
										url: 'controller/MasterController.php', 
										type: 'POST',
										data: data,
										success: function(result)
											{
											  setTimeout(function(){ location.reload();}, 1000);
											}
										});	 
								}	
							}
						});	
					}	
					else
					{
						jQuery.prompt("Please select at least one record!", {
							title: "Delete Confirmation",
							buttons: { "close": true}
						});	
					}	
				}	
				
		</script>			
		<?php
			include("footer.php");
		?>
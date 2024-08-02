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
					
					<div class="template_heading"><span class="template_left">Email Schedule's</span><span class="template_right"><a style = "margin:0 5px;" class="button round blue" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a><a style = "margin:0 5px;" class="button round blue" href="new-cron-schedule.php">Add New Schedule</a></span></span></div>
					
					<div class = "main mail_email">
						<div class = "main_left customer_list">
								<?php
								    $user_id =  $_SESSION['userid'];
									if($_SESSION['type'] == 'admin')
									{	
										 $rs=mysql_query("SELECT cron_schedule.id,cat_id,country_id,send_all,send_as,date,user_name,title FROM cron_schedule LEFT JOIN user ON user.user_id = cron_schedule.user_id LEFT JOIN template ON cron_schedule.template_id = template.id ORDER BY cron_schedule.id DESC ");
									}
									else
									{
										$rs=mysql_query("SELECT cron_schedule.id,cat_id,country_id,send_all,send_as,date,user_name,title FROM cron_schedule LEFT JOIN user ON user.user_id = cron_schedule.user_id LEFT JOIN template ON cron_schedule.template_id = template.id where cron_schedule.user_id = ".$user_id." ORDER BY cron_schedule.id DESC");
									}	

									$model = array();
									$index = 1;
									$customer_grid = array();
									while ($company_list =  mysql_fetch_assoc($rs))
									{
										
										$template_name = $company_list['title'];
										$no_recipient;
										
										$send_as;
										if($company_list['send_as'] == 1)
										{
											$send_as = "Admin";
										}
										else
										{
											$send_as = $company_list['user_name'];
										}	
										
										if($company_list['send_all'])
										{
											$no_recipient = "All";
										}
										else
										{
												$cat_ids = explode(',', $company_list['cat_id']);
												$total_count = " ";
												foreach($cat_ids as $cat_id)
												{
													$cat_count_rs = mysql_query("select cat_name from catalog where id = ".$cat_id);
													$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
													if($cat_count_list['cat_name'])
													$total_count .= ",<br>".$cat_count_list['cat_name'];
												}	
												$email_id =  $company_list['id']; 
												$no_recipient = substr($total_count,6);
												
												$country_id = explode(',', $company_list['country_id']);
												$total_count1 = " ";
												foreach($country_id as $coun_id)
												{
													$co_count_rs = mysql_query("select * from country where id = ".$coun_id);
													$con_count_list =  mysql_fetch_assoc($co_count_rs);
													if($con_count_list['country_name'])
													$total_count1 .= ",<br>".$con_count_list['country_name'];
												}	
												
												if($total_count != " ")
												{	
													$no_recipient.= $total_count1;
												}	
												else
												{	
													$no_recipient.= substr($total_count1,6);
												}	
										}
											
										$send_date = date("d F Y H:i:s", strtotime($company_list['date']));
										$user_name = $company_list['user_name'];
										$record_id = $company_list['id'];
										
										$c_last_li_html = "";
										
										$c_last_li_html = '<a href = "edit-cron-schedule.php?id='.$record_id.'"><span class="fa fa-pencil-square-o"></span></a>';
										$c_last_li_html .= "<a class='fa fa-trash' onclick='deleteEmail(\"".$record_id."\")'></a>";
											
										$checkbox_delete = '<input name="checkboxtrackemail[]" type="checkbox" value="'.$record_id.'" class = "checkboxtrackemail">';	
										
										$customer_grid[] = array('mass_delete' => "$checkbox_delete" ,'id' => "$index" ,'template_name' => "$template_name",'no_recipient' => "$no_recipient",'send_as' => "$send_as",'user_name' => "$user_name",'send_date' => "$send_date",'last_li' => "$c_last_li_html");
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
											"columns": jQuery.get("customer/schedule_record_columns.json"),
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
								var data = {'function':'DeleteScheduleRecord','id':track_id};
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
									var data = {'function':'MassDeleteScheduleRecord','massid':values};
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
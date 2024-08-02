<?php
session_start();
?>
<html>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<?php 
		include("head.php");
	?>	
	<body>
		<?php
			include("header.php");
			include("database.php");
			
			if (isset($_SESSION['login_user']) && isset($_GET['id']))
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
					
					<div class="template_heading"><span class="template_left">View Report</span><span class="template_right"><a style = "margin:0 5px;" class="button round blue" href="track-report.php">Back</a></span></div>
					
					<div class = "main mail_email">
						<div class = "main_left customer_list">
								<div class = "container_view">
									<?php 
									$email_record_id =  $_GET['id'];
									
									$rs=mysql_query("select * from email_record where id = ".$email_record_id);
									$email_rs =  mysql_fetch_assoc($rs);
									
									$template = mysql_query("select * from template where id = ".$email_rs['template']);
									$template_col =  mysql_fetch_assoc($template);
									$template_name = $template_col['title'];
									
									$user_rs1 = mysql_query("select * from user where user_id = ".$email_rs['user_id']);
									$user_list1 =  mysql_fetch_assoc($user_rs1);
									$user_name = $user_list1['user_name'];
										
									$track_user_id = $email_rs['user_id'];
									$track_template_id = $email_rs['template'];									
										
									$track_record_col = mysql_query("select DISTINCT email_id from track_report where user_id = ".$track_user_id." and template_id = ".$track_template_id." and comp_id = ".$email_record_id." ORDER BY id DESC");
									
									$num_rows = mysql_num_rows($track_record_col);
									
									
									$trck_bounce_co = mysql_query("SELECT * FROM user_record RIGHT JOIN bounce_email ON user_record.email_id = bounce_email.email left join email on bounce_email.email = email.id where comp_id = ".$email_record_id." group by bounce_email.email");
									
									$num_bounce = mysql_num_rows($trck_bounce_co);	
									
									
									?>
									<div class = "section_heading">
										<h2 class= "re_h2"><span><?php echo $email_rs['emails']?></span> Recipients </h2>
										<h2 class = "h2_right">Delivered Time : <span><?php echo $email_rs['date']?></span></h2>
									</div>
									<div class = "section_top_view">
										<h2 class = "h2_left">Template name : <span><?php echo $template_name; ?></span></h2>
										<h2 class = "h2_right">User  Name : <span><?php echo $user_name; ?></span></h2>
									</div>
									<?php
										$open_rat = number_format(($num_rows*100)/$email_rs['emails'],2);
									?>
									<div class = "open_rat_outer">
										<?php echo $open_rat."%"; ?>
										<div class = "open_rat_inner">
											<div class = "open_rate_div" style = "width:<?php echo $open_rat."%"; ?>">
											</div>
										</div>
									</div>
									
									<div class = "section_contant">
										<div class = "section_contant_sub content_open">
											<a class = "open_view_a"><p><?php echo $num_rows; ?></p>
											<p>Opened</p></a>
										</div>
										<div class = "section_contant_sub content_bounce">
											<a class = "bounce_view_a" href = "bounce-track-report.php?id=<?php echo $email_record_id; ?>"><p><?php echo $num_bounce; ?></p>
											<p>Bounced</p></a>
										</div>
									</div>		
								</div>
								<?php
								
									$index = 1;
									$customer_grid = array();
									while ($company_list =  mysql_fetch_assoc($track_record_col))
									{
										$email_track_id = $company_list['email_id'];
										
										$email_track_col = mysql_query("select * from email where id = ".$company_list['email_id']);
										
										$email_track =  mysql_fetch_assoc($email_track_col);
										
										$email_id  = $email_track['email'];
										$c_name  = $email_track['name'];
										
										$track_date_no = mysql_query("select * from track_report where comp_id = ".$email_record_id." and email_id = ".$email_track_id." ORDER BY id DESC");
										
										
										$email_track_date =  mysql_fetch_assoc($track_date_no);
										$no_open = mysql_num_rows($track_date_no);
										
										$last_read = $email_track_date['date'];
										$last_read = date("d F Y H:i:s", strtotime($last_read));
										
										
										$track_grid[] = array('id' => "$index" ,'email_id' => "$email_id",'c_name' => "$c_name",'no_open' => "$no_open",'last_read' => "$last_read");
										$index++; 
									}
									$track_grid_json = json_encode($track_grid);
								 	
									
									/* bounce back colllection */
									
								?>
								
								<div class="container_outer">
									<div class="container class_container_outer_relative">
										<h2 class = "page_sub_title">Open Email</h2>
										<table class="table table-striped"></table>
									</div> 
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
											"columns": jQuery.get("customer/track_record_columns.json"),
											"rows": <?php echo $track_grid_json; ?>//jQuery.get("customer/rows.json")
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
		<?php
			include("footer.php");
		?>
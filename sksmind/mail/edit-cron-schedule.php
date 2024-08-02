<?php
session_start();
?>
<html>
	<?php 
		include("head.php");
	?>	
	<body>
		<?php
			include("header.php");
			include("database.php");
			if (isset($_SESSION['login_user']))
			{
				$schedule_id = $_GET['id'];
				$user_id =  $_SESSION['login_user'];
				if($_SESSION['type'] == 'admin')
				{	
					$schedule_count_rs = mysql_query("select * from cron_schedule where id = ".$schedule_id);
				}
				else
				{
					$schedule_count_rs = mysql_query("select * from cron_schedule where id = ".$schedule_id." and user_id =".$user_id);					
				}
				$schedule_list = mysql_fetch_assoc($schedule_count_rs);	
				$cat_ids = explode(',', $schedule_list['cat_id']);
				$cont_ids = explode(',', $schedule_list['country_id']);
														
			?>
			
				<div class = "main_container">
					<?php
					if (isset($_SESSION['error_cat']))
					{ ?>
						<div class = "error_div"><?php echo $_SESSION['error_cat']; ?> </div>
					<?php
						unset($_SESSION['error_cat']);
					}
					?>	
					<?php
					if (isset($_SESSION['su_cat']))
					{ ?>
						<div class = "success_div"><?php echo $_SESSION['su_cat']; ?> </div>
					<?php
						unset($_SESSION['su_cat']);
					}
					?>
					<div class = "template_heading"><span class="template_left">Change Cron Schedule</span><span class="template_right"><span class="template_right" style = "margin: 0 10px;"><a class="button round blue" href="cron-schedule.php">Back</a></span></div> 
					<div class = "main">
						
						<div class = "template_title"><h2><?php echo $template_title ?></h2></div>
						<div class = "send_mail_outer">
						 <form id="sendemail" method="POST" action = "cron/edit-cron-schedule.php">   
						 	<div class = "company_info_detail_input">
								<div class = "class_country_cat">
									<lable>Select Template <span>Select Date</span></lable>
									<div class = "country_category">
										<ul class = "select_tmp">
											<li>
												<select name="template_id" id  = "template_id" required>
												  <option value="">Select Template </option>	
													<?php 
														
														$template_count_rs = mysql_query("select * from template");
														if(mysql_num_rows($template_count_rs))
														{
															while($row = mysql_fetch_array($template_count_rs)){
																if($row['id'] == $schedule_list['template_id'])
																{ ?>
																	<option value="<?php echo $row['id'] ?>" selected><?php echo $row['title'] ?></option>
																<?php		
																}
																else
																{			
																?>
																	<option value="<?php echo $row['id'] ?>"><?php echo $row['title'] ?></option>
															<?php	
																}
															}	 
														}
														?>
												</select>
											</li>
											<li>	
												<div id="datetimepicker1" class="input-append date">
													<input data-format="dd/MM/yyyy hh:mm:ss" type="text" name = "send_date" id = "send_date" value = "<?php echo $schedule_list['date'] ?>"></input>
													<span class="add-on">
													  <i data-time-icon="icon-time" data-date-icon="icon-calendar">
													  </i>
													</span>
												</div>
											</li>	
										</ul>	
									</div>
								</div>	
								<div class = "class_country_cat">
									<lable>Select All</lable>
									<div class = "country_category">
										<ul>
											<li><input id = "select_all" type="checkbox" name="send_all" value="1" <?php echo ($schedule_list['send_all']==1 ? 'checked' : '');?>>All</li>
										</ul>	
									</div>
								</div>	
								<div class = "class_country_cat">
								<lable>Select Country</lable>
								<div class = "country_category">
									<ul>
									<?php 
										$country_rs=mysql_query("select * from country");
										if(mysql_num_rows($country_rs))
										{
											 while($country_row = mysql_fetch_array($country_rs)){
											?>	 
												<li><input type="checkbox" name="country[]" value="<?php echo $country_row['id'] ?>" <?php if(in_array($country_row['id'], $cont_ids)){ echo 'checked';} ?>><?php echo $country_row['country_name'] ?></li> 
											<?php	
											}	 
											
										}
									?>
									</ul>
								</div>
								</div>
								<div class = "class_country_cat">
									<lable>Select Category</lable>
									<div class = "country_category">	
										<ul>
											<?php 
											$country_rs=mysql_query("select * from catalog");
											if(mysql_num_rows($country_rs))
											{
												 while($country_row = mysql_fetch_array($country_rs)){
												?>	 
													<li><input type="checkbox" name="category[]" value="<?php echo $country_row['id'] ?>" <?php if(in_array($country_row['id'], $cat_ids)){ echo 'checked';} ?>><?php echo $country_row['cat_name'] ?></li>
												<?php	
												
												}	 
											}
										?>
										</ul>
									</div>
								</div>	
										<?php if($_SESSION['type'] != 'admin'): ?>
											<div class = "check_box_mail">
												<input class = "main_send" type="checkbox" name="sendmail" value="yes" <?php echo ($schedule_list['send_as']==1 ? 'checked' : '');?>>Send Mail as admin</input>
												
											</div>
										<?php endif; ?>	
										<input type="hidden" name="schedule_id" value="<?php echo $schedule_id ?>">
									</div>	
									<div class = "error_cat_country" style = "display:none"><h4>select at least one checkbox.</h4></div>
									<div class = "submit_button_div">
										<button class = "submit_button" type="button">Change Schedule</button>
									</div>	
						 </form>   
						</div>
					</div>
				</div>	
				 <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
				<script>
					jQuery(document).ready(function(){
						
						jQuery(function() {
							jQuery('#datetimepicker1').datetimepicker({
								format: 'yyyy-MM-dd hh:mm:ss',
								language: 'pt-BR'
							 });
						});
						
						jQuery("#select_all").click(function(){
						   jQuery('.country_category input:checkbox').not(this).prop('checked', this.checked);
						});
						
						jQuery(".submit_button_div .submit_button").click(function(){
							if(jQuery('.country_category ul li').find('input[type=checkbox]:checked').length == 0)
							{
								jQuery('.error_cat_country').attr("style","display:block!important");
							}
							else
							{
								jQuery('.error_cat_country').attr("style","display:none!important");
								jQuery("#sendemail").submit();
							}	
						  		
						});
					});
				</script>		
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
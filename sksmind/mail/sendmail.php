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
				$id = $_GET['id'];
				$cat_count_rs = mysql_query("select * from template where id = ".$id);
				$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
				$template_content = $cat_count_list['template'];	
				$template_title = $cat_count_list['title'];		
			
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
					<div class = "template_heading"><span class="template_right" style = "margin: 0 10px;"><a class="button round blue" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a></span></div> 
					<div class = "main">
						
						<div class = "template_title"><h2><?php echo $template_title ?></h2></div>
						<div class = "send_mail_outer">
						 <form id="sendemail" method="POST" action = "cron/mailgroup/getgroup.php">   
						 	<div class = "company_info_detail_input">
								<div class = "class_country_cat">
									<lable>Select All</lable>
									<div class = "country_category">
										<ul>
											<li><input id = "select_all" type="checkbox" name="send_all" value="1">All</li>
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
												<li><input type="checkbox" name="country[]" value="<?php echo $country_row['id'] ?>"><?php echo $country_row['country_name'] ?></li>
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
													<li><input type="checkbox" name="category[]" value="<?php echo $country_row['id'] ?>"><?php echo $country_row['cat_name'] ?></li>
												<?php	
												}	 
											}
										?>
										</ul>
									</div>
								</div>	
										<?php if($_SESSION['type'] != 'admin'): ?>
											<div class = "check_box_mail">
												<input class = "main_send" type="checkbox" name="sendmail" value="yes">Send Mail as admin</input>
												
											</div>
										<?php endif; ?>	
										<input type="hidden" name="template_id" value="<?php echo $id ?>">
									</div>	
									<div class = "error_cat_country" style = "display:none"><h4>select at least one checkbox.</h4></div>
									<div class = "submit_button_div">
										<button class = "submit_button" type="button">Send mail</button>
									</div>	
						 </form>   
						</div>
					</div>
				</div>	
				<script>
					jQuery(document).ready(function(){
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

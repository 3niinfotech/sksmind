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
			?>
				<div class = "main_container">
				<?php
					if (isset($_SESSION['su_cat']))
					{ ?>
						<div class = "success_div"><?php echo $_SESSION['su_cat']; ?></div>
					<?php
						unset($_SESSION['su_cat']);
					}
					?>	
					<?php
					if (isset($_SESSION['error_cat']))
					{ ?>
						<div class = "error_div success"><?php echo $_SESSION['error_cat']; ?></div>
					<?php
						unset($_SESSION['error_cat']);
					}
					
						$user_id =  $_GET['id'];
						$cat_count_rs1 = mysql_query("select count(1) from email where user_id= ".$user_id);
						$cat_count_list1 =  mysql_fetch_array($cat_count_rs1);
						$email_count= $cat_count_list1[0];
					?>	
					<div class="template_heading"><span class="template_left">User statistics</span>
						<?php 
						if($email_count > 0)
						{
						?>	
							<span class="template_right"><a class="button round blue" href="export_csv.php?id=<?php echo $_GET['id']; ?>">Export Email</a></span> 
						<?php } ?>
						<span class="template_right" style = "margin: 0 10px;"><a class="button round blue" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a></span>  
						</div>
					<div class = "main mail_email mail_state">
						<div class = "main_left">
							<ul>
								<li class = "li_bold srno">sr. no</li>
								<li class = "li_bold">Name</li>
								<li class = "li_bold li_email">Email</li>
								<li class = "li_bold">Company Name</li>
								<li class = "li_bold">Telephone No</li>
								<li class = "li_bold">In category</li>
								<li class = "li_bold last_li_email">Mail Status</li>
								<li class = "li_bold">No. of mail send</li>
								<li class = "li_bold"></li>
								<!--<li class = "last_li_email"></li>  -->
							</ul>	
								<?php
								    $index = 1;
									if($email_count > 0)
									{	 
										$rs=mysql_query("select * from email where user_id= ".$user_id);
												
										while ($company_list =  mysql_fetch_assoc($rs))
										{
											$cat_id = $company_list['cat_id'];
											$email_id =  $company_list['id']; 
											
											$cat_ids = explode(',', $company_list['cat_id']);
											$cat_count_rs = mysql_query("select cat_name from catalog where id = ".$cat_ids[0]);
											$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
											$total_count = $cat_count_list['cat_name'];
											$total_count = $total_count;
												
											
											$email_count_rs1 = mysql_query("select count(email_id) from user_record where category = ".$cat_ids[0]." and email_id = ".$email_id." and user_id = ".$user_id);
											$email_count_list1 =  mysql_fetch_assoc($email_count_rs1);
											$get_user_id1 = $email_count_list1['count(email_id)'];
											
											?>
												<ul style = "background-color:f1f1f1;">
												<li class = "srno"><?php echo $index; ?></li>
												<li><?php echo $company_list['name']; ?></li>
												<li class = "li_email"><?php echo $company_list['email']; ?></li>
												<li><?php echo $company_list['company_name']; ?></li>
												<li><?php echo $company_list['mo_no']; ?></li>
												<li><?php echo $total_count; ?></li>
												<li class = "last_li_email">
												  <?php 
												  if($get_user_id1)
												  {
													  ?>
														<a class = "fa fa-check"></a>
													  <?php
												  }
												  else
												  {
												  ?>				
													<a class = "fa fa-close"></a>
												   <?php
												  }
												  ?>
												</li>
												<li class = "no_of_mail"><?php echo $get_user_id1; ?></li>
												<li><a onclick="showcat('<?php echo $cat_ids[0].$index; ?>')" class="fa fa-history" aria-hidden="true"></a></li>
												
											<!--	<li class = "last_li_email">
												<?php
												if($_SESSION['type'] == 'admin'):
												?>
													<a href = "edit-email.php?id=<?php echo $email_id; ?>">view/edit</a><a  class="fa fa-trash" onclick="deleteEmail('<?php echo $email_id; ?>')"></a><?php endif; ?>
												</li> -->
												</ul>	
												
											<div class = "show_cat_imail show_cat_imail_<?php echo $cat_ids[0].$index; ?>" style = "display:none">
											<?php 
													
												$cat_rs = mysql_query("select * from user_record where category = ".$cat_ids[0]." and email_id = ".$email_id." and user_id = ".$user_id);
												
												$email_index = 1;
												
												$row_count = mysql_num_rows ($cat_rs);
												if($row_count > 0)
												{
												?> 
													<ul>
														<li class = "email_li_bold">sr. no</li>
														<li class = "email_li_bold">Template</li>
														<li class = "email_li_bold">Date</li>
													</ul>
												<?php
												
													
													while ($cat_rs1 =  mysql_fetch_assoc($cat_rs))
													{
														
														$template_count_rs = mysql_query("select * from template where id = ".$cat_rs1['template']);
														$template_count_list =  mysql_fetch_assoc($template_count_rs);
														$template_name = $template_count_list['title'];
														
													?>
														<ul>
															<li><?php echo $email_index; ?></li>
															<li><?php echo $template_name; ?></li>
															<li><?php echo $cat_rs1['date']; ?></li>
														</ul>
													<?php
														$email_index++;
													}
												 }	 
											?>
										</div>
												
											<?php
												$cat_ids = explode(',', $company_list['cat_id']);
												$total_count = " ";
												foreach($cat_ids as $key => $cat_id)
												{
													if($key != 0)
													{	
														$cat_count_rs = mysql_query("select cat_name from catalog where id = ".$cat_id);
														$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
														$total_count = $cat_count_list['cat_name'];
														$total_count = $total_count;
														
														$email_count_rs1 = mysql_query("select count(email_id) from user_record where category = ".$cat_id." and email_id = ".$email_id." and user_id = ".$user_id);
														$email_count_list1 =  mysql_fetch_assoc($email_count_rs1);
														$get_user_id11 = $email_count_list1['count(email_id)'];
														
														  if($get_user_id11)
														  {
																$send_status = '<a class = "fa fa-check"></a>';
														  }
														  else
														  {
														  	$send_status =  '<a class = "fa fa-close"></a>';
														  }
														 
														echo "<ul><li class = 'srno'></li><li></li><li class='li_email'></li><li></li><li></li><li>".$total_count."</li><li class='last_li_email'>".$send_status."</li><li class = 'no_of_mail'>".$get_user_id11."</li><li><a class='fa fa-history' aria-hidden='true' onclick=\"showcat('".$cat_id.$index."')\"></a></li></ul>";
														
														?>
														<div class = "show_cat_imail show_cat_imail_<?php echo $cat_id.$index; ?>" style = "display:none">
														<?php 
																	
																$cat_rs = mysql_query("select * from user_record where category = ".$cat_id." and email_id = ".$email_id." and user_id = ".$user_id);
																
																$email_index = 1;
																
																$row_count = mysql_num_rows ($cat_rs);
																if($row_count > 0)
																{
																?> 
																	<ul>
																		<li class = "email_li_bold">sr. no</li>
																		<li class = "email_li_bold">Template</li>
																		<li class = "email_li_bold">Date</li>
																	</ul>
																<?php
																
																	
																	while ($cat_rs1 =  mysql_fetch_assoc($cat_rs))
																	{
																		
																		$template_count_rs = mysql_query("select * from template where id = ".$cat_rs1['template']);
																		$template_count_list =  mysql_fetch_assoc($template_count_rs);
																		$template_name = $template_count_list['title'];
																		
																	?>
																		<ul>
																			<li><?php echo $email_index; ?></li>
																			<li><?php echo $template_name; ?></li>
																			<li><?php echo $cat_rs1['date']; ?></li>
																		</ul>
																	<?php
																		$email_index++;
																	}
																 }	 
															?>
														</div>
														<?php
														
													}	
												}
											
											$index++;
										}
									}
									else
									{
										echo "<span style = 'border-bottom: 1px solid #ccc;float: left;
  font-size: 15px;padding: 25px 0; text-align: center; text-transform: uppercase; width: 100%;'>no record found</span>";
									}		
										
								?>
							</ul>
						</div>
					</div>
				</div>	
			<?php
			}
			else
			{
				echo "user session expired";
				header("Location:index.php" );
			}	
		?>	
		<script>
			var cat_id1 = 0;
			function showcat(cat_id)
			{
				
				if (cat_id != cat_id1)
				{	
					jQuery(".show_cat_imail").attr("style","display:none");
					cat_id1 = cat_id;
				}	
				jQuery(".show_cat_imail.show_cat_imail_"+cat_id).toggle();
			} 
		</script>
		
		<?php
			include("footer.php");
		?>

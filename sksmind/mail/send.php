 <?php
 ini_set('max_execution_time', 0);
 include("database.php");
 session_start();
 ini_set("mail.log", "/tmp/mail.log");
ini_set("mail.add_x_header", TRUE);
 
		if (isset($_SESSION['login_user']))
		{
			if(@$_POST["template_id"]!="")
			{
				
				/* select all customer */
				$all_email = array();
				$user_id =  $_SESSION['login_user'];
				if (isset($_POST['send_all'])) {
					if($_SESSION['type'] == 'admin')
					{	
						$send_all = mysql_query("select * from email");
					}
					else
					{
						$send_all = mysql_query("select * from email where user_id= ".$user_id);
					}
					
						if(mysql_num_rows($send_all))
						{
							while($send_all_row = mysql_fetch_array($send_all))
							{
								$all_email[] = $send_all_row['id'];
							}
						}		
				}
				else
				{
					/* select customer by country */
					$country_email = array();
					if (isset($_POST['country'])) {
						foreach ($_POST['country'] as $key => $value)
						{
							if($_SESSION['type'] == 'admin')
							{	
								$country_all = mysql_query("select * from email where country_id= ".$value);
							}
							else
							{
								$country_all = mysql_query("select * from email where country_id= ".$value." and user_id= ".$user_id);
							}
					
							if(mysql_num_rows($country_all))
							{
								while($send_all_row = mysql_fetch_array($country_all))
								{
									$country_email[] = $send_all_row['id'];
								}
							}	
						}	
					}
					
					/* select customer by category */
					$category_email = array();
					if (isset($_POST['category'])) {
						foreach ($_POST['category'] as $key => $value)
						{
							if($_SESSION['type'] == 'admin')
							{	
								$category_all=mysql_query("select * from email where find_in_set(".$value.",cat_id) <> 0");
							}
							else
							{
								$category_all=mysql_query("select * from email where find_in_set(".$value.",cat_id) <> 0 and user_id= ".$user_id);
							}
					
							if(mysql_num_rows($category_all))
							{
								while($send_all_row = mysql_fetch_array($category_all))
								{
									$category_email[] = $send_all_row['id'];
								}
							}	
						}	
					}
					
					$all_email = array_unique(array_merge(array_unique($country_email), array_unique($category_email)));
					
				}	
					
				
				$error = 1;
				if(empty($_POST["template_id"]))
				{
					$error = 0;
				}	
				
				if($error == 0)
				{
					echo "0";
					echo "<script type='text/javascript'> document.location = 'sendmail.php?id=".$_POST["template_id"]."'; </script>";  
					unset($_SESSION['error_cat']);
				}	
				else	
				{	
					$template_id = $_POST["template_id"];
					$cat_id = $_POST["cat_name"];
					//$content = stripslashes($_POST["Editor1"]);
					
					
					
					$template = mysql_query("select * from template where id = ".$template_id);
					$template_row =  mysql_fetch_assoc($template);
					$template_content = $template_row['template'];	
					
					
					$user_id =  $_SESSION['login_user'];
					
					$user_count_rs = mysql_query("select * from user where user_id = ".$user_id);
					$user_count_list =  mysql_fetch_assoc($user_count_rs);
					$user_name = $user_count_list['first_name'];
					$user_email = $user_count_list['user_email'];
					
					$company_name = $user_count_list['company_name'];
					$tel_no = $user_count_list['tel_no'];
					$fax_no = $user_count_list['fax_no'];
					$mobile = $user_count_list['mobile'];
					$rapnet_id = $user_count_list['rapnet_id'];
					$skype_id = $user_count_list['skype_id'];
					$wechat_id = $user_count_list['wechat_id'];
					$qq_id = $user_count_list['qq_id'];
					$user_address = $user_count_list['address'];
					$formality = $user_count_list['formality'];
					$welcommsg = $user_count_list['welcommess'];
					
					
					if (isset($_POST['sendmail'])) {
						$user_count_rs = mysql_query("select * from user where type = 'admin'");
						$user_count_list =  mysql_fetch_assoc($user_count_rs);
						$user_id = $user_count_list['user_id'];
						$user_name = $user_count_list['first_name'];
						$user_email = $user_count_list['user_email'];
						
						$company_name = $user_count_list['company_name'];
						$tel_no = $user_count_list['tel_no'];
						$fax_no = $user_count_list['fax_no'];
						$mobile = $user_count_list['mobile'];
						$rapnet_id = $user_count_list['rapnet_id'];
						$skype_id = $user_count_list['skype_id'];
						$wechat_id = $user_count_list['wechat_id'];
						$qq_id = $user_count_list['qq_id'];
						$user_address = $user_count_list['address'];
						$formality = $user_count_list['formality'];
						$welcommsg = $user_count_list['welcommess'];
					}
					
					
					$footer_content = '
					<table width="100%" cellspacing="0" cellpadding="0" border="0" style="min-width:100%;border-collapse:collapse;table-layout:fixed!important"> 
					<tbody> 
						<tr> 
							<td style="min-width:100%;padding:10px 18px 25px; text-align:center " align="center"> 
								<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:600px!important; border-top-width:2px;border-top-style:solid;border-top-color:#ff00cc;border-collapse:collapse"> 
									<tbody> 
										<tr> 
											<td> 
												<span></span> 
											</td>
										</tr>
										<tr> 
											<td> 
											<table width="100%" cellspacing="0" cellpadding="0" border="0" style="min-width:100%;border-collapse:collapse"> 
												<tbody> 
													<tr> 
														<td valign="top" style="padding-top:9px"> 
															<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="max-width:100%;min-width:100%;border-collapse:collapse"> 
																<tbody> 
																	<tr> 
																		<td valign="top" style="padding: 0px 18px 9px; text-align: center; word-break: break-word; font-family: Helvetica; font-size: 12px; line-height: 150%;"> 
																			<div style="color: #222222; font-size: small; line-height: normal; font-family: arial;">
																				<span style="color:#000000">
																					<strong>
																						<em>
																							<font size="2">BEST REGARDS</font>
																							<font size="4" style="color:#351c75">,</font>
																						</em>
																					</strong>
																				</span>
																			</div>
																			<div style="font-size: small; line-height: normal; font-family: arial; padding:2px 0">
																				<strong><font size="2">'.$company_name.'</font></strong>
																			</div>
																			<div style="font-size: small; line-height: normal; font-family: arial; padding:2px 0">
																				<strong><font size="2">'.$user_name.'</font></strong>
																			</div>
																			<div style="color: #222222; font-size: small; line-height: normal; font-family: arial; ">&nbsp;</div>
																			<div style="color: #222222; font-size: small; line-height: normal; font-family: arial; padding:5px 0">
																			<span style="color:#000000"><strong></strong><strong>Mobile &nbsp;: <a href="tel:'.$mobile.'" value="'.$mobile.'" target="_blank">'.$mobile.'</a></strong></span> </div>
																			<div style="color: #222222; font-size: small; line-height: normal; font-family: arial;padding:5px 0">
																				<span style="color:#000000">
																					<strong>Email Id : '.$user_email.'</strong>
																				</span>
																			</div>
																			<div style="color: #222222; font-size: small; line-height: normal; font-family: arial; padding:5px 0">
																			<span style="color:#000000">
																				<strong>Wechat Id: '.$wechat_id.'</strong>
																			</span>
																		</div>
																		
																		<div style="color: #222222; font-size: small; line-height: normal; font-family: arial; padding:5px 0">
																			<strong>
																				<span style="color:#000000">Skype Id &nbsp;:	<a href="">'.$skype_id.'</a>&nbsp;</span>
																			</strong>
																		</div>
																		<div style="color: #222222; font-size: small; line-height: normal; font-family: arial; padding:5px 0">
																	<span style="color:#000000">
																		<strong>Rapnet No. : '.$rapnet_id.' </strong>
																	</span>
																</div>
																
																<div style="color: #222222; font-size: small; line-height: normal; font-family: arial; padding:5px 0">
																	<span style="color:#000000">
																		<strong>QQ Id : '.$qq_id.'</strong>
																	</span>
																</div>
																<div style="color: #222222; font-family: arial, sans-serif; font-size: small; line-height: normal; padding:5px 0"> 
																	<div>&nbsp;</div>
																		<span style="color:#000000"><strong></strong><strong style="font-size:12.8px">
																			<font size="2" face="arial">'.$user_address.'</font>
																		</strong>
																		</span> 
																</div>
																<div style="color: #222222; font-size: small; 	line-height: normal; font-family: arial; padding:5px 0"> 
																	<div>
																		<span style="color:#000000"><strong></strong><strong>Telephone &nbsp;: <a href="tel:'.$tel_no.'" value="'.$tel_no.'" target="_blank">'.$tel_no.'</a></strong></span> </div></div>
																		
																		<div style="color: #222222; font-size: small; line-height: normal; font-family: arial; padding:5px 0"><span style="color:#000000"><strong>Fax &nbsp; &nbsp; &nbsp; : &nbsp;<a href="tel:'.$fax_no.'" value="'.$fax_no.'" target="_blank">'.$fax_no.'</a></strong></span></div>
																		
																		
																		<div style="color: #000; font-size: small; line-height: normal; font-family: arial; padding:5px 0"><strong><font size="3"><span style="color:#000000"><span style="font-size:15px">Web </span>:</span><a href=" http://www.shreeintl.com/app/home" style="color:#000000;font-weight:normal;text-decoration:underline" target="_blank" > www.shreeintl.com</span></a></font></strong></div>
				</td></tr></tbody> </table> </td></tr></tbody> </table>
							</td>
						</tr>
					</tbody> 
				</table> 
			</td> 
		</tr>
	</tbody>
</table>';		
						date_default_timezone_set('Asia/Kolkata');
						$date = date('Y-m-d H:i:s');
						
						$count = count($all_email);
						
						if(count($all_email))
						{
							$comp_id;
							$sql = "INSERT INTO email_record (category,template,emails,date,user_id) VALUES ('$cat_id','$template_id','$count','$date',".$user_id.")";
							if (mysql_query($sql))
							{			
								$comp_id = mysql_insert_id();	
							}
							
							foreach ($all_email as $key => $value)
							{
								
								$email_result=mysql_query("select * from email where id= ".$value);
								$row =  mysql_fetch_assoc($email_result);
						
								$to = $row['email'];
								$eid = base64_encode('aa3'.$row['id']);
								$tempid = base64_encode('1c3'.$template_id);
								$catid = base64_encode('1d3'.$row['cat_id']);
								$compid = base64_encode('a2b'.$comp_id);
								$user_new_id = base64_encode('1b1'.$user_id);
								
								$cat_id = $row['cat_id'];
								$subject = 'Shree international';
								
								$message = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><img border='0' src='http://sksmdiamonds.in/tracking/trackonline.php?exe=$eid&tmt=$tempid&cde=$catid&usd=$user_new_id&csd=$compid' width='1' height='1' alt='image for email' >";
								
								$message .= $formality.' '.$row['name'].',<br>'.$welcommsg.'<br><br>'.$template_content.$footer_content.'';
								
								$headers  = "MIME-Version: 1.0" . "\r\n";
								$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
								$headers .= "From: ". $user_email. "\r\n";
								$headers .= "Reply-To: ". $user_email. "\r\n";
								$headers .= "X-Mailer: PHP/" . phpversion();
								$headers .= "X-Priority: 1" . "\r\n";

								
								$sentMail = mail($to, $subject, $message, $headers,"-fbounceback@sksmdiamonds.in"); 
								if($sentMail) //output success or failure messages
								{      
									$email_id = $row['id'];
									$sql1 = "INSERT INTO user_record (comp_id,category,template,email_id,date,user_id) VALUES (".$comp_id.",'$cat_id','$template_id',".$email_id.",'$date',".$user_id.")";
									mysql_query($sql1);
								}else{
									die('Could not send mail! Please check your PHP mail configuration.');  
								} 
								
							}
							
							$_SESSION['su_cat']= $count." email successfully Send!!!.";
							echo "<script type='text/javascript'> document.location = 'dashbord.php'; </script>";  
							unset($_SESSION['error_cat']);
							
						}	
						else
						{
							//header('Location: ' . $_SERVER['HTTP_REFERER']);
							session_start();
							$_SESSION['error_cat']= "invalid category. please try again..";
							echo "<script type='text/javascript'> document.location = 'sendmail.php?id=".$_POST["template_id"]."'; </script>";
						}	
				
				}	
					  
			}
			else
			{
				$_SESSION['error_cat']= "invalid template. please try again..";
				echo "<script type='text/javascript'> document.location = 'template.php'; </script>";
			}	
		}	
		else
		{
			$_SESSION['error_cat']= "invalid category. please try again..";
			echo "<script type='text/javascript'> document.location = 'dashbord.php'; </script>";
			//header('Location: index.php');
		}	
		?>
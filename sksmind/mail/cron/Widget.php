<?php

class Widget 
{
	
	public function getFormality($UserId,$sendAdmin = 0)
    {
		if ($sendAdmin) {
			$user_count_rs = mysql_query("select * from user where type = 'admin'");
		}
		else	
		{
			$user_count_rs = mysql_query("select * from user where user_id = ".$UserId);
		}
		$user_count_list =  mysql_fetch_assoc($user_count_rs);
		$formality = $user_count_list['formality'];
		return $formality;
	}	
		
	public function getWelcomMsg($UserId,$sendAdmin = 0)
    {
		if ($sendAdmin) {
			$user_count_rs = mysql_query("select * from user where type = 'admin'");
		}
		else	
		{
			$user_count_rs = mysql_query("select * from user where user_id = ".$UserId);
		}
		$user_count_list =  mysql_fetch_assoc($user_count_rs);
		$welcommess = $user_count_list['welcommess'];
		return $welcommess;
	}	
		
		
	public function getUserEmail($UserId,$sendAdmin = 0)
    {
		if ($sendAdmin) {
			$user_count_rs = mysql_query("select * from user where type = 'admin'");
		}
		else	
		{
			$user_count_rs = mysql_query("select * from user where user_id = ".$UserId);
		}
		$user_count_list =  mysql_fetch_assoc($user_count_rs);
		$user_email = $user_count_list['user_email'];
		return $user_email;
	}		
		
		
	public function getUserAddress($UserId,$sendAdmin = 0)
    {
		if ($sendAdmin) {
			$user_count_rs = mysql_query("select * from user where type = 'admin'");
		}
		else	
		{
			$user_count_rs = mysql_query("select * from user where user_id = ".$UserId);
		}
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
																		
																		
																		<div style="color: #000; font-size: small; line-height: normal; font-family: arial; padding:5px 0"><strong><font size="3"><span style="color:#000000"><span style="font-size:15px">Web </span>:</span><a href="http://shreehk.com/" style="color:#000000;font-weight:normal;text-decoration:underline" target="_blank" > www.shreehk.com</span></a></font></strong></div>
										</td></tr></tbody></table></td></tr></tbody> </table>
									</td>
								</tr>
							</tbody> 
						</table> 
					</td> 
				</tr>
			</tbody>
		</table>';
		
		return $footer_content;
	}	
	
	public function getEMailAddress($userType,$userID,$sendAll,$catId,$countryId)
    {
		$all_email = array();
		
		if($sendAll)
		{	
			if($userType == 'admin')
			{	
				$send_all = mysql_query("select * from email");
			}
			else
			{
				$send_all = mysql_query("select * from email where user_id= ".$userID);
			}
			if(mysql_num_rows($send_all))
			{
				while($send_all_row = mysql_fetch_array($send_all))
				{
					$all_email[] = $send_all_row['id'];
				}
			}
			
			return $all_email;
		}
		else
		{
			/* select customer by country */
			$country_email = array();
			$con_Id = explode(',', $countryId);
			if (count($con_Id)) {
				foreach ($con_Id as $key => $value)
				{
					if($userType == 'admin')
					{	
						$country_all = mysql_query("select * from email where country_id= ".$value);
					}
					else
					{
						$country_all = mysql_query("select * from email where country_id= ".$value." and user_id= ".$userID);
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
			$catId1 = explode(',', $catId);
			if (count($catId1)) {
				foreach ($catId1 as $key => $value)
				{
					if($userType == 'admin')
					{	
						$category_all=mysql_query("select * from email where find_in_set(".$value.",cat_id) <> 0");
					}
					else
					{
						$category_all=mysql_query("select * from email where find_in_set(".$value.",cat_id) <> 0 and user_id= ".$userID);
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
			return $all_email;		
		}	
			
	}	
	
}

	
?>
<?php require_once ("../Widget.php") ; ?>
<?php

ini_set("mail.log", "/tmp/mail.log");
ini_set("mail.add_x_header", TRUE);
include("../../database.php");	
$newWidget = new Widget();
 
	$schedule_result = mysql_query("SELECT mail_schedule.id,email_id,send_as,date,mail_schedule.user_id,type,template_id,title,template FROM mail_schedule LEFT JOIN user ON user.user_id = mail_schedule.user_id LEFT JOIN template ON mail_schedule.template_id = template.id order by id asc limit 1");
	
	$schedule_list =  mysql_fetch_assoc($schedule_result);
	
	date_default_timezone_set('Asia/Kolkata');
	$date = date('Y-m-d H:i:s');
	
	$all_email = array();
	$all_email = explode(",",$schedule_list['email_id']);		
	$schedule_id = $schedule_list['id'];
	$userType = $schedule_list['type'];
	$user_id =$schedule_list['user_id'];
	
	$template_id = $schedule_list['template_id'];
	$template_content = $schedule_list['template'];
	$user_address = $newWidget->getUserAddress($schedule_list['user_id'],$schedule_list['send_as']);
	$user_email = $newWidget->getUserEmail($schedule_list['user_id'],$schedule_list['send_as']);
	$formality = $newWidget->getFormality($schedule_list['user_id'],$schedule_list['send_as']);
	$welcommsg = $newWidget->getWelcomMsg($schedule_list['user_id'],$schedule_list['send_as']);
	
	if(count($all_email))
	{
		$count = count($all_email);
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
			//$to = 'shaileshk.virani@gmail.com';
			$eid = base64_encode('aa3'.$row['id']);
			$tempid = base64_encode('1c3'.$template_id);
			$catid = base64_encode('1d3'.$row['cat_id']);
			$compid = base64_encode('a2b'.$comp_id);
			$user_new_id = base64_encode('1b1'.$user_id);
									
			$cat_id = $row['cat_id'];
			$subject = 'Shree international';
									
			$message = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><img border='0' src='http://sksmdiamonds.in/tracking/trackonline.php?exe=$eid&tmt=$tempid&cde=$catid&usd=$user_new_id&csd=$compid' width='1' height='1' alt='image for email' >";
									
			$message .= $formality.' '.$row['name'].',<br>'.$welcommsg.'<br><br>'.$template_content.$user_address.'';
					
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
	} 
	$delete_schedule = "DELETE FROM mail_schedule WHERE id = ".$schedule_id;
	mysql_query($delete_schedule); 
?>
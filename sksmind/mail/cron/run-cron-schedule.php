<?php require_once ("Widget.php") ; ?>
<?php

ini_set("mail.log", "/tmp/mail.log");
ini_set("mail.add_x_header", TRUE);
include("../database.php");	
$newWidget = new Widget();
 
	$schedule_result = mysql_query("SELECT cron_schedule.id,cat_id,country_id,send_all,send_as,date,cron_schedule.user_id,type,template_id,title,template FROM cron_schedule LEFT JOIN user ON user.user_id = cron_schedule.user_id LEFT JOIN template ON cron_schedule.template_id = template.id");
	
	date_default_timezone_set('Asia/Kolkata');
	$date = date('Y-m-d H:i:s');
	$tie = 0;
	while ($schedule_list =  mysql_fetch_assoc($schedule_result))
	{
		
		$to_time = strtotime($date);
		$from_time = strtotime($schedule_list['date']);
		$is_allow = round(abs($to_time - $from_time) / 60,0);
		
		print_r($is_allow);
	 
		if($is_allow < 20)
		{
			$all_email = array();
			$schedule_id = $schedule_list['id'];
			$userType = $schedule_list['type'];
			$user_id =$schedule_list['user_id'];
			$sendAll =$schedule_list['send_all'];
			$countryId =$schedule_list['country_id'];
			$catId =$schedule_list['cat_id'];
			$template_id = $schedule_list['template_id'];
			$send_as = $schedule_list['send_as'];
			
			$all_email =	$newWidget->getEMailAddress($userType,$user_id,$sendAll,$catId,$countryId);
			$emailGroup = array_chunk($all_email,200);
			foreach($emailGroup as $i => $myemailGroup)
			{
				$mailSchedule = "INSERT INTO mail_schedule (email_id,template_id,user_id,send_as,date) VALUES ('".implode(",",$myemailGroup)."',".$template_id.",".$user_id.",".$send_as.",'$date')";
				//echo $mailSchedule;
				mysql_query($mailSchedule);
			}
			
			$delete_schedule = "DELETE FROM cron_schedule WHERE id = ".$schedule_id;
			mysql_query($delete_schedule);
		}
	}	
	echo "<br>send".$tie ; 
?>
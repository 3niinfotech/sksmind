<?php require_once ("../Widget.php") ; ?>
<?php
session_start();

ini_set("mail.log", "/tmp/mail.log");
ini_set("mail.add_x_header", TRUE);
include("../../database.php");	
$newWidget = new Widget();
 
	if (isset($_SESSION['login_user']))
	{
		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s');
		
		$all_email = array();
		
		$catId = "";
		$con_Id = "";
				
		$userType = $_SESSION['type'];
		$user_id = $_SESSION['login_user'];
		
		if (isset($_POST['send_all'])) {
			$sendAll = $_POST['send_all'];
			$sendAll = 1;
			$all_email =	$newWidget->getEMailAddress($userType,$user_id,1,$catId,$con_Id);
		}
		else
		{	
			$catId = implode(',', $_POST['category']);
			$con_Id = implode(',', $_POST['country']);
			$all_email = $newWidget->getEMailAddress($userType,$user_id,0,$catId,$con_Id);
		}	
		
		$template_id = $_POST['template_id'];
				
		$send_as = 0;		
		if (isset($_POST['sendmail'])) {
			$send_as = 1;
		}
		$count = count($all_email);
		$emailGroup = array_chunk($all_email,200);
		foreach($emailGroup as $i => $myemailGroup)
		{
			$mailSchedule = "INSERT INTO mail_schedule (email_id,template_id,user_id,send_as,date) VALUES ('".implode(",",$myemailGroup)."',".$template_id.",".$user_id.",".$send_as.",'$date')";
			mysql_query($mailSchedule);
		}
		
		$_SESSION['su_cat']= $count." email successfully Ready to Send!!!.";
		header('Location: ../../dashbord.php');
		unset($_SESSION['error_cat']);
	}
	else
	{
		$_SESSION['error_cat']= "invalid category. please try again..";
		header('Location: ../../index.php');
	}	
?>
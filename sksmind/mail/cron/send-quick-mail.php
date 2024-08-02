<?php
session_start();
include("../database.php");
require_once ("Widget.php") ;
$newWidget = new Widget();

if (isset($_SESSION['login_user']))
{
	$user_id = $_SESSION['login_user'];
	$email_to = $_POST['email_to'];
	$subject = $_POST['subject'];
	$send_template = $_POST['send_template'];
	$template_id = $_POST['template_id'];
	$email_message = $_POST['email_message'];
	$send_as = 0;
	
	$message_content = "";
	
	$error = 1;
	if(empty($_POST['subject']))
	{
		$error = 0;
	}
	if(empty($_POST['email_to']))
	{
		$error = 0;
	}
	else
	{
		$email_to = trim($_POST['email_to']);
		if (!filter_var($email_to, FILTER_VALIDATE_EMAIL)) {
				$error = 0;
		}
	}
	if (isset($_POST['send_template']))
	{
		if(empty($_POST['template_id']))
		{
			$error = 0;
			$_SESSION['error_cat']= "Enter message content";
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
		else
		{
			$template = mysql_query("select * from template where id = ".$_POST['template_id']);
			$template_row =  mysql_fetch_assoc($template);
			$message_content = $template_row['template'];	
		}
	}
	else
	{
		if(empty($_POST['email_message']))
		{
			$error = 0;
			$_SESSION['error_cat']= "Enter message content";
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
		else
		{
			$message_content = $_POST['email_message'];
		}
	}
	if (isset($_POST['sendmail']))
	{
		$send_as = 1;
	}	
	
	if($error)
	{
		
		$user_address = $newWidget->getUserAddress($user_id,$send_as);
		$user_email = $newWidget->getUserEmail($user_id,$send_as);
		$formality = $newWidget->getFormality($user_id,$send_as);
		$welcommsg = $newWidget->getWelcomMsg($user_id,$send_as); 
		
		
			$to = $email_to;
			//$subject = $send_template;
			
			$message .= $formality.' <br>'.$welcommsg.'<br><br>'.$message_content.$user_address.'';
			$headers  = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
			$headers .= "From: ". $user_email. "\r\n";
			$headers .= "Reply-To: ". $user_email. "\r\n";
			$headers .= "X-Mailer: PHP/" . phpversion();
			$headers .= "X-Priority: 1" . "\r\n";

			$sentMail = mail($to, $subject, $message, $headers,"-fbounceback@sksmdiamonds.in"); 
			if($sentMail) //output success or failure messages
			{      
				$_SESSION['su_cat']= " Email successfully Send!!!.";
				header("Location: ../dashbord.php");  
				unset($_SESSION['error_cat']);					
			}else{
				$_SESSION['error_cat']= "There are something invalid. please try again.";
				header("Location: ".$_SERVER['HTTP_REFERER']);  
			}
	}	
	else
	{
		$_SESSION['error_cat']= "There are something invalid. please try again.";
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}	
}
else
{
	$_SESSION['error_cat']= "User Session Expire";
	header("Location: index.php");
}	
	
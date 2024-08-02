 <?php
 include("../database.php");
 session_start();

	if (isset($_SESSION['login_user']))
	{
		$schedule_id = 0;
		$template_id = 0;
		$send_all = 0;
		$country = "";
		$category = "";
		$send_date = "";
		$sendmail = 0;
		$error = 1;
		$user_id = $_SESSION['login_user'];
		
		
		
		if (isset($_POST['schedule_id'])) {
			
			$schedule_id = $_POST['schedule_id'];
		}
		else
		{
			$error = 0;
		}
		
		
		if (isset($_POST['template_id'])) {
			
			$template_id = $_POST['template_id'];
		}
		else
		{
			$error = 0;
		}	
		if (isset($_POST['send_date'])) {
			
			$send_date = $_POST['send_date'];
		}
		else
		{
			$error = 0;
		}	
		
		if (isset($_POST['send_all'])) {
			$send_all = $_POST['send_all'];
		}
		
		if (isset($_POST['country'])) {
			
			$country = implode(",", $_POST['country']);
		}
		
		if (isset($_POST['category'])) {
			
			$category = implode(",", $_POST['category']);
		}
		
		if (isset($_POST['sendmail'])) {
			
			$sendmail = 1;
		}
		else
		{
			if($_SESSION['type'] == 'admin'): 
				$sendmail = 1;
			endif; 
		}	
		
		
		
		if($error)
		{
			
			$time = strtotime($send_date);
			$myFormatForView = date("Y-m-d H:i:s", $time);
			
			$sql = "UPDATE cron_schedule SET template_id = ".$template_id.",cat_id ='$category',country_id ='$country',send_all = ".$send_all.",send_as = ".$sendmail.",user_id = ".$user_id.",date = '".$myFormatForView."' WHERE id = ".$schedule_id;
			
			if (mysql_query($sql)) {
				$_SESSION['su_cat']= "Schedules Successfully Saved !!";
				unset($_SESSION['error_cat']); 
						header("Location: ../cron-schedule.php");
			} else {
				$_SESSION['error_cat']= "Ooppss, Error in save. Please correct information. ";
				header("Location: ".$_SERVER['HTTP_REFERER']);
			}  
		}
		else
		{
			header('Location: ../cron-schedule.php');
			$_SESSION['error_cat']= "Ooppss, Error in save. Please correct information. ";
		}	
	}
	else
	{
		$_SESSION['error_cat']= "user session expire ";
		header('Location: ../cron-schedule.php');
	}		
<?php 
session_start();
include("../database.php");


	if(function_exists($_POST['function'])) {
		$_POST['function']();
	}

	
	/* Delete Schedule Record */
	
	function DeleteScheduleRecord()
	{
		
		if (isset($_SESSION['login_user']))
		{
			$error = 1;
			if(empty($_POST['id']))
			{
				$error = 0;
			}	
		
			if($error == 0)
			{
				echo "0";
			}	
			else	
			{	
				$email_id = $_POST['id'];
				
				$sql = "DELETE FROM cron_schedule WHERE id = ".$email_id;
				if (mysql_query($sql)) {
				  echo "1";
				  unset($_SESSION['error_cat']);
				  $_SESSION['su_cat'] = "Record Successfully Deleted...";
				} else {
				  echo "0";
				  unset($_SESSION['su_cat']);
				  $_SESSION['error_cat'] = "Something wrong. ";
				}
			}
		}
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}		
	}	
	
	
	
	/* Mass Delete Schedule Record */
	
	function MassDeleteScheduleRecord()
	{
		
		if (isset($_SESSION['login_user']))
		{
			$error = 1;
			if(empty($_POST['massid']))
			{
				$error = 0;
			}	
		
			if($error == 0)
			{
				echo "0";
			}	
			else	
			{	
				$email_ids = $_POST['massid'];
				$error1 = 1;
				$count = 0;
				foreach ($email_ids as $email_id)
				{
					$sql = "DELETE FROM cron_schedule WHERE id = ".$email_id;
					if (mysql_query($sql)) {
					  $count++;
					} else {
					  $error1 = 0;
					  unset($_SESSION['su_cat']);
					  $_SESSION['error_cat'] = "Something wrong. ";
					}
				}
				
				if($error1 == 0)
				{
					echo "0";
				}
				else
				{
					echo "1";
					unset($_SESSION['error_cat']);
					$_SESSION['su_cat'] = $count." Record Successfully Deleted...";
				}	
			}
		}
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}		
	}	
	
	
	
	/* user email track */
	
	function DeleteTrackRecord()
	{
		if (isset($_SESSION['login_user']))
		{
			$error = 1;
			if(empty($_POST['id']))
			{
				$error = 0;
			}	
			
			if($error == 0)
			{
				echo "0";
			}	
			else	
			{	
				$comp_id  = $_POST['id'];
				
				$sql = "DELETE FROM email_record WHERE id = ".$comp_id;
				if (mysql_query($sql)) {
					$sql_comp = "DELETE FROM track_report WHERE comp_id  = ".$comp_id;
					if (mysql_query($sql_comp)) {
						echo "1";
						unset($_SESSION['error_cat']);
						$_SESSION['su_cat'] = "Record Successfully Deleted...";
					}
					else {
					  echo "0";
					  unset($_SESSION['su_cat']);
					  $_SESSION['error_cat'] = "Something wrong. ";
					}	
				} else {
				  echo "0";
				  unset($_SESSION['su_cat']);
				  $_SESSION['error_cat'] = "Something wrong. ";
				}
			}	
		}
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}		
	}	

	
	
	/* user email track */
	
	function MassDeleteTrackRecord()
	{
		if (isset($_SESSION['login_user']))
		{
			$error = 1;
			if(empty($_POST['massid']))
			{
				$error = 0;
			}	
			
			if($error == 0)
			{
				echo "0";
				$_SESSION['error_cat'] = "Something wrong. ";
			}	
			else	
			{	
				$comp_ids  = $_POST['massid'];
				$error1 = 1;
				$count = 0;
				foreach ($comp_ids as $comp_id)
				{
					$sql = "DELETE FROM email_record WHERE id = ".$comp_id;
					if (mysql_query($sql)) {
						$sql_comp = "DELETE FROM track_report WHERE comp_id  = ".$comp_id;
						if (mysql_query($sql_comp)) {
							$count++;
						}
						else {
						  $error1 = 0;
						  unset($_SESSION['su_cat']);
						  $_SESSION['error_cat'] = "Something wrong. ";
						}	
					} else {
					  $error1 = 0;
					  unset($_SESSION['su_cat']);
					  $_SESSION['error_cat'] = "Something wrong. ";
					}
				}
				
				if($error1 == 0)
				{
					echo "0";
				}
				else
				{
					echo "1";
					unset($_SESSION['error_cat']);
					$_SESSION['su_cat'] = $count." Record Successfully Deleted...";
				}	
			}	
		}
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}		
	}
	
	
	
	
	
	function SaveCountry()
	{
		if (isset($_SESSION['login_user']))
		{
			$error = 1;
			if(empty($_POST['country_name']))
			{
				$error = 0;
			}	
			
			if($error == 0)
			{
				echo "0";
			}	
			else	
			{	
				$new_catname = trim($_POST['country_name']);
				$sql1="SELECT * FROM country WHERE country_name ='$new_catname'";
				$result=mysql_query($sql1);
				$row=mysql_fetch_array($result);
				$count=mysql_num_rows($result);
				if($count==0)
				{
					$cat_name = $_POST['country_name'];
					$sql = "INSERT INTO country(country_name) VALUES ('$new_catname')";
					if (mysql_query($sql)) {
					  $_SESSION['su_cat'] = "Record Successfully Saved...";
					  echo "1";
					} else {
					  echo "0";
					}
				}
				else{
					echo "0";		 
				}				
			}
		}
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}			
	}	
	
	function DeleteCountry()
	{
		if (isset($_SESSION['login_user']))
		{	
			$error = 1;
			if(empty($_POST['id']))
			{
				$error = 0;
			}	
			
			if($error == 0)
			{
				echo "0";
			}	
			else	
			{	
				$cat_id = $_POST['id'];
				
				$sql = "DELETE FROM country WHERE id = ".$cat_id;
				if (mysql_query($sql)) {
					$_SESSION['su_cat'] = "Record Successfully Deleted...";
					//$sql_email = "DELETE FROM email WHERE cat_id = ".$cat_id;
					//if (mysql_query($sql_email)) {
						echo "1";
					///}	
				} else {
				  echo "0";
				}
			}
		}
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}			
	}	
	
	
	
	
	function SaveGroup()
	{
		if (isset($_SESSION['login_user']))
		{		
			$error = 1;
			if(empty($_POST['cat_name']))
			{
				$error = 0;
			}	
			
			if($error == 0)
			{
				echo "0";
			}	
			else	
			{	
				$cat_name = $_POST['cat_name'];
				$sql = "INSERT INTO catalog(cat_name) VALUES ('$cat_name')";
				if (mysql_query($sql)) {
				  echo "1";
				  $_SESSION['su_cat'] = "Record Successfully Saved...";
				} else {
				  echo "0";
				}
			}	
		}
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}		
	}

	
	function SaveEmail()
	{
		$error = 1;
		if(empty($_POST['cat_name']))
		{
			$error = 0;
		}	
		if(empty($_POST['email']))
		{
			$error = 0;
		}
		else
		{
			$email = trim($_POST['email']);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error = 0;
			}
		}	
		if($error == 0)
		{
			echo "0";
		}	
		else	
		{	
			$cat_name = $_POST['cat_name'];
			$email = trim($_POST['email']);
			$sql = "INSERT INTO email(email,cat_id) VALUES ('$email','$cat_name')";
			if (mysql_query($sql)) {
			  echo "1";
			} else {
			  echo "0";
			}
		}	
	}	
	
	function DeleteEmail()
	{
	
		if (isset($_SESSION['login_user']))
		{
			$error = 1;
			if(empty($_POST['id']))
			{
				$error = 0;
			}	
			
			if($error == 0)
			{
				echo "0";
			}	
			else	
			{	
				$email_id = $_POST['id'];
				
				$sql = "DELETE FROM email WHERE id = ".$email_id;
				
				if (mysql_query($sql)) {
					$bounce_email = "DELETE FROM bounce_email WHERE id = ".$email_id;
					mysql_query($bounce_email);
					$track_report = "DELETE FROM track_report WHERE email_id = ".$email_id;
					mysql_query($track_report);
					$_SESSION['su_cat'] = "Email Successfully Deleted.";
				  echo "1";
				} else {
				  echo "0";
				}
			}	
		}
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}	
	}	
	
	/* mass delete email record */
	
	function MassDeleteEmail()
	{
	
		if (isset($_SESSION['login_user']))
		{
			$error = 1;
			if(empty($_POST['massid']))
			{
				$error = 0;
			}	
			
			if($error == 0)
			{
				echo "0";
				unset($_SESSION['su_cat']);
				$_SESSION['error_cat'] = "Something wrong. ";
			}	
			else	
			{	
				$email_ids = $_POST['massid'];
				$error1 = 1;
				$count = 0;
				
				foreach ($email_ids as $email_id)
				{	
					$sql = "DELETE FROM email WHERE id = ".$email_id;
					if (mysql_query($sql)) {
						$bounce_email = "DELETE FROM bounce_email WHERE id = ".$email_id;
						mysql_query($bounce_email);
						$track_report = "DELETE FROM track_report WHERE email_id = ".$email_id;
						mysql_query($track_report);
						$user_record = "DELETE FROM user_record WHERE email_id = ".$email_id;
						mysql_query($user_record);
						$_SESSION['su_cat'] = "Email Successfully Deleted.";
					 $count++;
					} else {
					  $error1 = 0;
					}
			
				}
				if($error1 == 0)
				{
					echo "0";
					unset($_SESSION['su_cat']);
					$_SESSION['error_cat'] = "Something wrong. ";
				}
				else
				{
					echo "1";
					unset($_SESSION['error_cat']);
					$_SESSION['su_cat'] = $count." Record Successfully Deleted...";
				}	
			}	
		}
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}	
	}	
	
	
	
	function DeleteCat()
	{
		if (isset($_SESSION['login_user']))
		{
			$error = 1;
			if(empty($_POST['id']))
			{
				$error = 0;
			}	
			
			if($error == 0)
			{
				echo "0";
			}	
			else	
			{	
				$cat_id = $_POST['id'];
				
				$sql = "DELETE FROM catalog WHERE id = ".$cat_id;
				if (mysql_query($sql)) {
					//$sql_email = "DELETE FROM email WHERE cat_id = ".$cat_id;
					//if (mysql_query($sql_email)) {
						$_SESSION['su_cat'] = "Email Successfully Deleted.";
						echo "1";
					//}	
				} else {
				  echo "0";
				}
			}
		}
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}			
	}	
	
	function DeleteUser()
	{
		if (isset($_SESSION['login_user']))
		{
			$error = 1;
			if(empty($_POST['id']))
			{
				$error = 0;
			}	
			
			if($error == 0)
			{
				echo "0";
			}	
			else	
			{	
				$cat_id = $_POST['id'];
				
				$sql = "DELETE FROM user WHERE user_id = ".$cat_id;
				if (mysql_query($sql)) {
					
					$user_record = "DELETE FROM user_record WHERE user_id = ".$cat_id;
					mysql_query($user_record);
					$track_report = "DELETE FROM track_report WHERE user_id = ".$cat_id;
					mysql_query($track_report);
					$email_record = "DELETE FROM email_record WHERE user_id = ".$cat_id;
					mysql_query($email_record);
					$_SESSION['su_cat'] = "User Successfully Deleted.";
						echo "1";
					
				} else {
				  echo "0";
				}
			}
		}	
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}			
	}

	function DeleteTemplate()
	{
		if (isset($_SESSION['login_user']))
		{
			$error = 1;
			if(empty($_POST['id']))
			{
				$error = 0;
			}	
			
			if($error == 0)
			{
				echo "0";
			}	
			else	
			{	
				$cat_id = $_POST['id'];
				
				$sql = "DELETE FROM template WHERE id = ".$cat_id;
				if (mysql_query($sql)) {
					$sql_email = "DELETE FROM cron_schedule WHERE template_id = ".$cat_id;
					if (mysql_query($sql_email)) {
						$_SESSION['su_cat'] = "Template Successfully Deleted.";
						echo "1";
					}	
				} else {
				  echo "0";
				}
			}
		}	
		else
		{
			$_SESSION['error_cat'] = "User Session Expired.";
			echo "0";
		}		
	}		
//}
/* else
{
	echo "user session expired";
	header('Location: ../index.php');
}	 */
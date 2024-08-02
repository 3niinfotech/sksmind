<?php
 include("../database.php");

session_start();
if (isset($_SESSION['login_user']))
{

	$user_id = $_SESSION['login_user'];
	$fn = $_POST['first_name'];
	$ln = $_POST['last_name'];
	$address = $_POST['address'];
	$mobile = $_POST['mobile'];
	$pass = $_POST['password'];
	$error = 1;
		if(empty($_POST['user_f_name']) || empty($_POST['password']) || empty($_POST['user_email']))
		{
			$error = 0;
		}	
		if(!empty($_POST['user_email']))
		{
			$email = trim($_POST['user_email']);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error = 0;
			}
		}
		
		if($error==1)
		{

			$user_email = trim($_POST['user_email']);
			$user_f_name = addslashes($_POST['user_f_name']);

			
			$sql = "INSERT INTO email(user_name,user_email,first_name,last_name,address,mobile,pass,type) VALUES ('$user_f_name','$user_email','$first_name','$last_name','$address','$mobile','$password','user')";
			
			   if (mysql_query($sql)) {
					session_start();
					$_SESSION['su_cat']= "New User Successfully Saved !!";
					unset($_SESSION['error_cat']); 
						header("Location: user_management.php");
				} else {
						session_start();
						$_SESSION['error_cat']= "Ooppss, Error in save. Please correct information. ";
						 header("Location: ".$_SERVER['HTTP_REFERER']);
				} 
		}
		else
		{
			session_start();
			$_SESSION['error_cat']= "Ooppss, Error in save. Please correct information. ";
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}		
			
			

}
else
{
  header("Location: index.php");
}
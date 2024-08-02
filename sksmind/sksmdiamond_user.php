<?php
include("database.php");

session_start();

//$user_id = $_SESSION['login_user'];
$fn = $_POST['first_name'];	
$pass = md5($_POST['password']);

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

	$sql = "INSERT INTO user(user_name,user_email,first_name,pass,type) VALUES ('$user_f_name','$user_email','$fn',,'$pass','user')";
	
   if (mysqli_query($cn,$sql)) 
   {
		
   }	
}
else
{
	
}		
			

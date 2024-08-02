<?php 
session_start(); 
include('../../variable.php');
include('../../database.php');
	
	$loginid = $_POST['username'];
	$pass = $_POST['password'];

	$myusername=addslashes($_POST['username']);
	$mypassword=addslashes($_POST['password']);
	 
	$sql = "SELECT * FROM user WHERE user_name ='$myusername'";

	$result=mysqli_query($cn,$sql);
	$login_result=mysqli_fetch_array($result);
    $current_id = $login_result['user_id'];
	
	if( md5($mypassword) == $login_result['pass'] )
	{	
		$_SESSION['userid']=$current_id;
		$_SESSION['type']= $login_result['type'];
		$_SESSION['username'] = $login_result['user_name'];
		$_SESSION['company_name'] = $login_result['company_name'];
		$_SESSION['roll'] = $login_result['roll'];
		$_SESSION['sequence'] = rand(100000,999999);
		
		
		$sql = "UPDATE user SET online=1 WHERE  user_id=".$current_id;
		$result=mysqli_query($cn,$sql);
	
		header('Location: ' .$mainUrl.'dashboard.php');
		
		
	}	
		
	else
	{
		$_SESSION['error'] = 'Invalid Username or Password.';
	    header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	

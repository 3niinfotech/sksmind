<?php 
session_start();

include("../database.php");
	
	$loginid = $_POST['loginid'];
	$pass = $_POST['pass'];

	$myusername=addslashes($_POST['loginid']);
	$mypassword=addslashes($_POST['pass']);
	 
	$sql = "SELECT * FROM user WHERE user_name ='$myusername'";

	$result=mysql_query($sql);
	$login_result=mysql_fetch_array($result);
    $current_id = $login_result['user_id'];
	
	if( md5($mypassword) == $login_result['pass'] )
	{
		$_SESSION['login_user']=$current_id;
		$_SESSION['type']= $login_result['type'];
		$_SESSION['user_name'] = $login_result['user_name'];
		echo "success";
	}	
		
	else
	{
		echo "fail";
	}

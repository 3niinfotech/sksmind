<?php 
session_start();

include("../database.php");
	
	$loginid = $_POST['loginid'];
	$pass = $_POST['pass'];

	$myusername=addslashes($_POST['loginid']);
	$mypassword=addslashes($_POST['pass']);
	 
	$sql="SELECT * FROM user WHERE user_name='$myusername' and pass='$mypassword'";

	$result=mysql_query($sql);
	//$row=mysql_fetch_array($result);

        $login_result =  mysql_fetch_assoc($result);
        $current_id = $login_result['user_id'];

	$count=mysql_num_rows($result);
	
	if($count==1)
	{
		$_SESSION['login_user']=$current_id;
		$_SESSION['type']= $login_result['type'];
		echo "success";
	}
	else
	{
		echo "fail";
	}

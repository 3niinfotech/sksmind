<?php 	
	session_start();
	include ('backup.php');
	include_once('database.php');
	if (isset($_SESSION['username']))
	{
		$sql = "UPDATE user SET online=0 WHERE  user_id=".$_SESSION['userid'];
		$result=mysqli_query($cn,$sql);
		unset($_SESSION['userid']);	
		unset($_SESSION['companyname']);			
		unset($_SESSION['companyId']);
		unset($_SESSION['type']);
		unset($_SESSION['username']);		
		unset($_SESSION['sequence']);	
		
		
	}
	header("Location: index.php");
?>
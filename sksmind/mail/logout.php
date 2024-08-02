<?php 
	
	session_start();
	if (isset($_SESSION['login_user']))
	{
		unset($_SESSION['login_user']);
		unset($_SESSION['logout']);
		header("Location: index.php");
	}	
	header("Location: ../index.php");

?>
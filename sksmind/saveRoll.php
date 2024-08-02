<?php
 session_start();
 include("variable.php");
	include("database.php");
	include_once("checkResource.php");
	if (!isset($_SESSION['username']) || !checkResource('company'))
	{
		header("Location: ".$mainUrl);	
	}
	
	$error = $stockData = 0;
	$message = "";
	
	if(empty($_POST["name"]) || empty($_POST["resource"]) )
	{
		$_SESSION['error'] = " Value can't be Blank.";
	}	
	else	
	{	
		$name = $_POST["name"];
		$resource = json_encode($_POST["resource"]);
		$company = json_encode($_POST["company"]);		
				
		
		if(isset($_POST["id"]) && !empty($_POST["id"]) ): 
			$id = $_POST["id"];
			$sql = "update roll set name='$name',resource='$resource',company='$company' where id = $id"; 
		else:
			$sql = "insert into roll (name,resource,company) values('$name','$resource','$company')";
		
		endif;	
		if (mysqli_query($cn,$sql)) 
		{
			$error = 0 ; 			
		}
		else
		{
			$error = 1; 
		}
		
		
		if ($error ==0) 
		{
			$_SESSION['success']= " Successfully Saved !!!";		 
		} 
		else 
		{
		    $_SESSION['error'] = "Oooppss, Error in save !!! ";
		}
		 
	}
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
        
?>
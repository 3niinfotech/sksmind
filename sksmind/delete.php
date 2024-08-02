<?php session_start(); ?>
<?php
 
 include("database.php");

	$error = $stockData = 0;
	$message = "";
	
	
	if(empty($_GET["id"]) || empty($_GET["t"]) )
	{
		$_SESSION['error'] = " Value can't be Blank.";
	}	
	else	
	{	
		
		//$password = md5($_GET["password"]);		
		//$name = $_GET["name"];		
		//$number = $_GET["number"];		
		//$address = $_GET["address"];		
		
		$sql="";
		if(isset($_GET["id"])):
			$sql = "DELETE FROM ".$_GET['t'] ." WHERE id = ".$_GET['id'];		
		endif;
		
		if (mysqli_query($cn,$sql)) 
		{
			$error = 0;
		}
		else
		{
			$error = 1; 
		}
		
		
		if ($error ==0) 
		{
			$_SESSION['success']= " Deleted Successfully !!!";		 
		} 
		else 
		{
		    $_SESSION['error'] = "Oooppss, Error in Delete !!! ";
		}
		 
	}
	header('Location: ' . $_SERVER['HTTP_REFERER']);
        
?>
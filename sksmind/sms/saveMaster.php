 <?php
 session_start();
 include("../database.php");
 include("../variable.php");
if (!isset($_SESSION['username']))
{
	header("Location: ".$mainUrl);
}
	$error = 1;
	if(empty($_POST["name"]))
	{
		$_SESSION['error'] = $_POST["type"]." Name can't be Blank.";
	}	
	else	
	{	
		$name = $_POST["name"];	
		$type = $_POST["type"];		
		
		$sql = "INSERT INTO ".$type."(name) VALUES ('". addslashes($name) ."')"; 
		
		if (mysql_query($sql)) 
		{
			$_SESSION['success']= $type." Successfully Saved !!!";		 
		} 
		else 
		{
		  $_SESSION['error'] = "Oooppss, Error in save !!! ";
		}
		 
	}	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
        
?>
 <?php
 session_start();
 include("database.php");
$utype = $_SESSION['type'];
if($utype =="user")
{
		header("Location: homepage.php");
}
	
	$error = $stockData = 0;
	if(empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["fname"]) || empty($_POST["lname"]) || empty($_POST["email"]) )
	{
		$_SESSION['error'] = " Value can't be Blank.";
	}	
	else	
	{	
		$username = $_POST["username"];	
		$password = md5($_POST["password"]);		
		$fname = $_POST["fname"];		
		$lname = $_POST["lname"];		
		$email = $_POST["email"];		
		$mobile = $_POST["mobile"];
		$address = $_POST["address"];
		$type = "user";
		
			
		$sql = "INSERT INTO user(user_name,user_email,pass,first_name,last_name,mobile,address,type) VALUES ('$username','$email','$password','$fname','$lname','$mobile','$address','$type')"; 
	
		if (mysql_query($sql)) 
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
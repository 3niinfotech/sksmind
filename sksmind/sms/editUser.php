 <?php
 session_start();
 include("../database.php");

	$id = $_SESSION['userid'];
	$error = $stockData = 0;
	$message = "";
	if(isset($_POST["cp"])):
	
	if(empty($_POST["oldpassword"]) || empty($_POST["password"]) )
	{
		$_SESSION['error'] = " Value can't be Blank.";
	}	
	else	
	{	
		$password = md5($_POST["password"]);
		$oldpassword = $_POST["oldpassword"];		
		$sql = "SELECT * FROM user WHERE id = $id"; 

		$result=mysql_query($sql);
		$login_result=mysql_fetch_array($result);
		$current_id = $login_result['id'];
		
		if( md5($oldpassword) == $login_result['password'] )
		{	
			$sql = "update user set password ='$password' where id = $id"; 
		
			if (mysql_query($sql)) 
			{
				
				$message = "Successfully Saved !!!";	
			}
			else
			{
				$error = 1;
				$message = "Ooppss! Error in save."; 
			}
		}
		else
		{
			$error = 1;
			$message = "Old Password Does not Match."; 
		}
		
		if ($error ==0) 
		{
			$_SESSION['success']= $message;		 
		} 
		else 
		{
		    $_SESSION['error'] = $message;
		}
	}
	else:
	
	if(empty($_POST["username"]) || empty($_POST["fname"]) || empty($_POST["lname"]) || empty($_POST["email"]) )
	{
		$_SESSION['error'] = " Value can't be Blank.";
	}	
	else	
	{	
		$username = $_POST["username"];	
		//$password = md5($_POST["password"]);		
		$fname = $_POST["fname"];		
		$lname = $_POST["lname"];		
		$email = $_POST["email"];		
		$mobile = $_POST["mobile"];
		$address = $_POST["address"];		
		
			
		$sql = "update user set user_name ='$username',e_mail='$email',firstname='$fname',lastname='$lname',mobile='$mobile',address='$address' where id = $id"; 
		
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
	endif;	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
        
?>
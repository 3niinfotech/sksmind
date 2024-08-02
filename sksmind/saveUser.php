<?php
 session_start();
 include("database.php");
	
	$error = $stockData = 0;
	$message = "";

	if(empty($_POST["first_name"]) || empty($_POST["username"]) || empty($_POST["last_name"]) || empty($_POST["user_email"]) )
	{
		$_SESSION['error'] = " Value can't be Blank.";
	}	
	else	
	{	
		$username = $_POST["username"];		
		$fname = $_POST["first_name"];		
		$lname = $_POST["last_name"];		
		$email = $_POST["user_email"];		
		$mobile = $_POST["mobile"];		
		$type = 'user';
		$roll = $_POST["roll"];
		$address = $_POST["address"];		
		$password = md5($_POST["password"]);	
			
		if(isset($_POST["id"]) && !empty($_POST["id"]) ): 
			$id = $_POST["id"];
			if($_POST["password"] == "")
				$sql = "update user set user_name='$username',user_email='$email',first_name='$fname',last_name='$lname',mobile='$mobile',address='$address',type='$type',roll='$roll' where user_id = $id"; 
			else
				$sql = "update user set user_name='$username',user_email='$email',first_name='$fname',last_name='$lname',mobile='$mobile',address='$address',type='$type',pass='$password',roll='$roll' where user_id = $id"; 	
		else:
			if($_POST["password"] == "")
				$sql = "insert into user (user_name,user_email,first_name,last_name,mobile,address,roll) values('$username','$email','$fname','$lname','$mobile','$address','$roll') "; 						
			else
				$sql = "insert into user (user_name,user_email,first_name,last_name,mobile,address,pass,roll) values('$username','$email','$fname','$lname','$mobile','$address','$status','$roll') "; 						
		endif;
		
		$msg = '';
		if (mysqli_query($cn,$sql)) 
		{
			$error = 0 ; 			
		}
		else
		{
			$error = 1; 
			$msg = mysqli_error();
		}		
		
		if ($error ==0) 
		{
			$_SESSION['success']= " Successfully Saved !!!";		 
		} 
		else 
		{
		    $_SESSION['error'] = $msg;
		}
		
		 
	}
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
        
?>
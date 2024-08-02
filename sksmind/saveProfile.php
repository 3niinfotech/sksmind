<?php
 session_start();
 include("database.php");
 include("variable.php");

	$id = $_SESSION['userid'];
	$error = $stockData = 0;
	$profile_image = '';
	$message = "";
	
		
	if(empty($_POST["first_name"]) || empty($_POST["last_name"]) || empty($_POST["user_email"]) )
	{
		$_SESSION['error'] = " Value can't be Blank.";
	}	
	else	
	{	
		if(isset($_FILES["profile_image"]["name"]) && $_FILES["profile_image"]["name"] !=""  ) 
		{
			$target_dir = $imgDir;
			$profile_image = basename($_FILES["profile_image"]["name"]);
			$target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			// Check if image file is a actual image or fake image
			
			$check = getimagesize($_FILES["profile_image"]["tmp_name"]);
			if($check !== false) {				
				$uploadOk = 1;
			} else {
				$_SESSION['error'] = "File is not an image.";
				$uploadOk = 0;
			}
		
			// Check if file already exists
			/* if (file_exists($target_file)) {
				$_SESSION['error'] = "Sorry, file already exists.";
				$uploadOk = 0;
			} */
			// Check file size
			if ($_FILES["profile_image"]["size"] > 500000) {
				$_SESSION['error'] = "Sorry, your file is too large.";
				$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				$_SESSION['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 1) 
			{
				if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) 
				{
					
				} else {
					$_SESSION['error'] = "Sorry, there was an error uploading your file.";
				}
			}
			if (!$uploadOk) 
			{
				header('Location: ' . $_SERVER['HTTP_REFERER']);
				exit;
			}	    
		}
		//$password = md5($_POST["password"]);		
		$fname = $_POST["first_name"];		
		$lname = $_POST["last_name"];		
		$email = $_POST["user_email"];		
		$mobile = $_POST["mobile"];
		$address = $_POST["address"];		
		
		if(isset($_POST["password"]) && !empty($_POST["password"]) ): 
			$pass = md5($_POST["password"]);
			$sql = "update user set pass='$pass',user_email='$email',first_name='$fname',last_name='$lname',mobile='$mobile',address='$address',profile_image='$profile_image' where user_id = $id"; 
		else:
			$sql = "update user set user_email='$email',first_name='$fname',last_name='$lname',mobile='$mobile',address='$address',profile_image='$profile_image' where user_id = $id"; 			
		endif;
		
		if (!mysqli_query($cn,$sql)) 
		{
			$error = 0 ; 	
			 $_SESSION['error']= mysqli_error();
		}
		else		
		{
			$_SESSION['success']= " Successfully Saved !!!";		 
		} 
	}
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
        
?>
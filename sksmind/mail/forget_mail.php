<?php session_start(); ?>
<html>
	<?php 
		include("head.php");
	?>	
	<body>
		<?php
			include("header.php");
			include("database.php");
			?>
			<?php
				if($_POST['email']!="")
				{
					$email = trim($_POST['email']);
					if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
						
						$digits = 8;
						$new_password = rand(pow(10, $digits-1), pow(10, $digits)-1);
						$new_pass = md5($new_password);
						
						$to = $email;
						$subject = 'Shree international';
						$message = "your new password: ".$new_password;
						$header = "From: shaileshk.virani@gmail.com\r\n"; 
						$header.= "Cc: shaileshk.virani@gmail.com\r\n";
						$header.= "MIME-Version: 1.0\r\n"; 
						$header.= "Content-Type: text/html; charset=UTF-8\r\n"; 
						$header.= "X-Priority: 1\r\n"; 

						$sentMail = mail($to, $subject, $message, $header); 
						//$sentMail = @mail($recipient_email, $subject, $body, $headers);
						if($sentMail) //output success or failure messages
						{     
							$sql = "UPDATE user SET pass ='$new_pass' WHERE user_email = '$email'";
							if (mysql_query($sql)) {
								
								session_start();
								$_SESSION['su_cat']= "If there is an account associated with ".$email." you will receive an email with a your new password.";
								unset($_SESSION['error_cat']); 
								//header("Location: index.php");
								echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
							}
							else{
								//header('Location: ' . $_SERVER['HTTP_REFERER']);
								unset($_SESSION['su_cat']); 
								session_start();
								$_SESSION['error_cat']= "Enter your account associate email ID";
								echo "<script type='text/javascript'> document.location = 'forget.php'; </script>";
							}		
								
						}else{
								//header('Location: ' . $_SERVER['HTTP_REFERER']);
								unset($_SESSION['su_cat']); 
								session_start();
								$_SESSION['error_cat']= "Enter your account associate email ID";	
								echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
										
						}	
					 }
					 else{
						 header('Location: ' . $_SERVER['HTTP_REFERER']);
						$_SESSION['error_cat']= "Enter invalid email ID";
					 }		
				}
				else{
					header('Location: ' . $_SERVER['HTTP_REFERER']);
					$_SESSION['error_cat']= "Enter your account associate email ID";
				}				
								
				
					
				
			?>
						 
		<?php include("footer.php"); ?>
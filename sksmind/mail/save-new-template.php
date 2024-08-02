 <?php
 session_start();
 include("database.php");

	if (isset($_SESSION['login_user']))
	{
		$error = 1;
		
		if(empty($_POST["templateTitle"]))
		{
			$error = 0;
		}
		if(empty($_POST["templateData"]))
		{
			$error = 0;
		}
		
		if($error)
		{
			$template_title = $_POST["templateTitle"];
			$content = $_POST["templateData"];
			
			$sql = "INSERT INTO template(title,template) VALUES ('$template_title','". addslashes($content) ."')";
			if (mysql_query($sql)) {
				$_SESSION['su_cat']= "Template success fully saved!!!.";
				unset($_SESSION['error_cat']); 
				echo 1;
			} else {
				$_SESSION['error_cat']= "error";
				echo 0;
			}
		}
		else
		{
			$_SESSION['error_cat']= "error";
			echo 0;
		}		
		
	}
	else
	{
		echo 0;
		$_SESSION['error_cat']= "error";
	}		
?>	
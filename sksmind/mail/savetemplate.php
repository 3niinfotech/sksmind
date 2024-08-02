 <?php
 echo "yes";
 exit;
 /*
 include("database.php");
        if(@$_POST["Editor1"]!="")
        {
			$error = 1;
			if(empty($_POST["template_title"]))
			{
				$error = 0;
			}	
			if(empty($_POST["Editor1"]))
			{
				$error = 0;
			}
			if($error == 0)
			{
				echo "0";
			}	
			else	
			{	
				$template_title = $_POST["template_title"];
				$content = stripslashes($_POST["Editor1"]);
				
				$sql = "INSERT INTO template(title,template) VALUES ('$template_title','". addslashes($content) ."')"; 
				
				echo $sql;
				
				if (mysql_query($sql)) {
				 // header('Location: ' . $_SERVER['HTTP_REFERER']);
				} else {
				  echo "0";
				}
			}	
				  
        }   */
		?>
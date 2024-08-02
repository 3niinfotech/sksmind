<?php session_start(); ?>
	<?php 
		include("head.php");
	?>	 

		<?php
			include("database.php");
			?>
			<?php
			
			if (isset($_SESSION['login_user']))
			{
			
				if($_POST['cat_name']!="" && $_POST['cat_id']!="")
				{     
					$new_catname = $_POST['cat_name'];
					$cat_id = $_POST['cat_id'];
					
					$sql1="SELECT * FROM country WHERE country_name ='$new_catname'";
					$result=mysql_query($sql1);
					$row=mysql_fetch_array($result);
					$count=mysql_num_rows($result);
					
					if($count==0)
					{
				
							$sql = "UPDATE country SET country_name ='$new_catname' WHERE id = ".$cat_id;
							if (mysql_query($sql)) {
								//session_start();
								$_SESSION['su_cat']= "Category Successfully Changed.";
								unset($_SESSION['error_cat']); 
     							               echo "<script type='text/javascript'> document.location = 'country.php'; </script>";  
								//header("location:category.php");

							}
							else{
								//header('Location:category.php');
								unset($_SESSION['su_cat']); 
								//session_start();
								$_SESSION['error_cat']= "Something may be wrong..";
								 echo "<script type='text/javascript'> document.location = 'country.php'; </script>";  

							}
					}
					else{
						//header('Location:category.php');
						unset($_SESSION['su_cat']); 
						session_start();
						$_SESSION['error_cat']= "Country Name Already exist";
						 echo "<script type='text/javascript'> document.location = 'country.php'; </script>";  
					}		
				}
				else{
						//header('Location:category.php');
						unset($_SESSION['su_cat']); 
						session_start();
						$_SESSION['error_cat']= "Something may be wrong..";
						 echo "<script type='text/javascript'> document.location = 'country.php'; </script>";  
				}	
				
			}	 
			?>
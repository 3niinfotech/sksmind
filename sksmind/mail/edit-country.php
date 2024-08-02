<?php session_start(); ?>
<html>
	<?php 
		include("head.php");
	?>	
	<body>
		<?php
			include("header.php");
			include("database.php");
			$cat_id = $_GET['id'];
			if (isset($_SESSION['login_user']) && isset($_GET['id']))
			{
			?>
			
			<div class = "main_container">
				<?php
					if (isset($_SESSION['error_cat']))
					{ ?>
						<div class = "error_div success"><?php echo $_SESSION['error_cat']; ?></div>
					<?php
						unset($_SESSION['error_cat']);
					}
					?>	
					<?php
					if (isset($_SESSION['su_cat']))
					{ ?>
						<div class = "success_div"><?php echo $_SESSION['su_cat']; ?></div>
					<?php
						unset($_SESSION['su_cat']);
					}
					?>	
				<div class = "main">						
					 <form id="sendemail" method="POST" action = "change-countryname.php">   
							<div class = "company_info_detail_input"/>
							<?php 
								$sql="SELECT * FROM country WHERE id= ".$cat_id;
								$result=mysql_query($sql);
								$row=mysql_fetch_array($result); 
								
							 ?>
								<p>Current Country Name : <?php echo $row['country_name']?> </p> 
								<lable>Enter New Country name : </lable>									
								<input type="text" name="cat_name" class="round" style="height:30px" required/><input type="hidden" name="cat_id" value = "<?php echo $cat_id; ?>">								
							</div>	
							
							<div class = "submit_button_div">
								<button class = "submit_button" type="submit">Submit</button>
							</div>	
					 </form>   
				</div>
			</div>				 
		<?php include("footer.php"); ?>
		<?php
			}
			else
			{
				echo "user session expired";
			}	
		?>	
		
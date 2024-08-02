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
				<div class = "template_heading"><span class = "template_left">Forget Password</span><span class="template_right" style = "margin: 0 10px;"><a class="button round blue" href="index.php">Back to Login </a></span></div> 
				<div class = "main">						
					 <form id="sendemail" method="POST" action = "forget_mail.php">   
							<div class = "company_info_detail_input forget_password_div">
							<p>enter your account associate email ID</p>
								<lable>Enter Your E-mail Address : </lable>									
								<input type="email" name="email" class="round" style="height:30px" required/>								
							</div>	
							
							<div class = "submit_button_div">
								<button class = "submit_button" type="submit">Submit</button>
							</div>	
					 </form>   
				</div>
			</div>				 
		<?php include("footer.php"); ?>
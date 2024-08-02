
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<?php 
		include("../database.php");
		include("../variable.php");
		
		include("head.php");
		if (isset($_SESSION['username']))
		{
		   header("Location: homepage.php");
		}
		else
		{
			header("Location: ".$mainUrl);
		}		
	?>	
	<body>
		<div class="company-name" style="padding-top:10%;"><img src="images/kapu-logo.png" /></div>
		<div class="form-style-6 login-form" style="margin-top:20px;">
				<p class = "display_errors" style = "display:none">Invalid Username or Password</p>
				<div class="master-title">Login to KMS</div>
				<form >					
					
					<input type="text" name="username" id="login-username" placeholder="Username" />
					<input type="password" name="password" id="login-password" placeholder="Password" />					
					
					<input type="button" id="submit" value="Login" />					
					
				</form>
			</div>
	</body>
	<script>
			jQuery(document).ready(function(){
				jQuery('#submit').click(function(){
					
					var loginid = jQuery('#login-username').val();
					var pass = jQuery('#login-password').val();
					
					jQuery.ajax({
						
						url: "login/login.php", 
						type: 'POST',
						data: { loginid: loginid, pass : pass} ,
						success: function(result)
						{
							if(result == "success")
							{	
								location.reload();
								
							}
							if(result == "fail")
							{
								jQuery('.display_errors').show();
							}		
						}
					});	
				});	
			});
		</script>
</html>

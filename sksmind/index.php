<?php  ini_set('error_reporting', 0); ini_set("display_errors",0); ob_start(); session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<?php 
	 
	include("variable.php");
	include("database.php");
	if (isset($_SESSION['username']))
	{
		header("Location: ".$mainUrl.'dashboard.php');	
	}
	include("head.php");
	?>	
	
	    
	
	<body class="grey lighten-3">


  <?php 
	include("login.php");
	//include("register.php");
	include("forget.php");
		
	?>	
	
  
</body>
<style>
html,
body {
    height: 100%;
}
html {
    display: table;
    margin: auto;
}
body {
    display: table-cell;
    vertical-align: middle;
}
.margin {
  margin: 0 !important;
}
@-moz-keyframes insQ_100 {  from {  outline: 1px solid transparent  } to {  outline: 0px solid transparent }  }
#menufication-top { animation-duration: 0.001s; animation-name: insQ_100; -moz-animation-duration: 0.001s; -moz-animation-name: insQ_100;  } 
</style>

<script>
function showLogin()
{
	jQuery('#register-page').hide();
	jQuery('#login-page').show();
	jQuery('#forget-page').hide();
}
function showRegister()
{
	jQuery('#register-page').show();
	jQuery('#login-page').hide();
	jQuery('#forget-page').hide();
}
function showForget()
{
	jQuery('#register-page').hide();
	jQuery('#login-page').hide();
	jQuery('#forget-page').show();
}
function formsubmit(name)
{
	jQuery('#'+name).submit();
}
</script>
</html>

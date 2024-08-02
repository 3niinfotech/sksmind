<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<?php 
	include("database.php");
	include("head.php");
	?>	
	
	    
	
	<body class="blue">

		<div id="login-page" class="row">
    <div class="col s12 z-depth-6 card-panel">
      <form class="login-form">
        <div class="row">
          <div class="input-field col s12 center">
            <img src="images/logo.png" alt="" class="responsive-img valign profile-image-login">
            <h2>Check Your Email!</h2>
			
			<p class="center login-form-text">We have sent a message to test@gmail.com. <br/>Open it up and click Activate Account. We will take it from there.</p>
			<br>
          </div>
        </div>
       

      </form>
    </div>
  </div>
 
  
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
</html>
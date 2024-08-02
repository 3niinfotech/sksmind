<?php include("variable.php");	?>	

<?php
if (isset($_SESSION['success']))
{ ?>
	<div class = "success_div"><?php echo $_SESSION['success']; ?></div>
<?php
	unset($_SESSION['success']);
}
?>	
<?php
if (isset($_SESSION['error']))
{ ?>
	<div class = "error_div" style = "height:auto"><?php echo $_SESSION['error']; ?></div>
<?php
	unset($_SESSION['error']);
}
?>	

  <div id="login-page" class="row">
    <div class="col s12 z-depth-6 card-panel">
      <form class="login-form" id="login-form" method="post" action="<?php echo $controllerUrl ?>user/login.php">
        <div class="row">
          <div class="input-field col s12 center">
            <img src="images/logo.png" alt="" class="responsive-img valign profile-image-login">
            <p class="center login-form-text">Please enter your username and password for login.</p>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-social-person-outline prefix"></i>
            <input class="validate" id="email" type="text" name="username" >
            <label for="email" data1-error="wrong" data1-success="right" class="center-align">Username</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="password" type="password" name="password" >
            <label for="password">Password</label>
          </div>
        </div>
        <div class="row">          
          <div class="input-field col s12 m12 l12  login-text">
              <input type="checkbox" id="remember-me" />
              <label for="remember-me">Remember me</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <button class="btn waves-effect waves-light col s12" type="sumbit">Login</button>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s6 m6 l6">
           <!-- <p class="margin medium-small"><a href="javascript:void(0);" onClick="showRegister()" >Register Now!</a></p> -->
          </div>
          <div class="input-field col s6 m6 l6">
              <p class="margin right-align medium-small"><a href="javascript:void(0);" onClick="showForget()" >Forgot password?</a></p>
          </div>          
        </div>

      </form>
    </div>
  </div>
  

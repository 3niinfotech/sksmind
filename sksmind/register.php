<?php session_start();include("variable.php");	?>	

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
<div id="register-page"  style="display:none;" class="row">
    <div class="col s12 z-depth-6 card-panel">
      <form id="register-form" class="login-form" method="post" action="<?php echo $controllerUrl ?>user/register.php">
        <div class="row">
          <div class="input-field col s12 center">
            <img src="images/logo.png" alt="" class="responsive-img valign profile-image-login">
            <p class="center login-form-text">Create a free account to send beautiful emails to customers,<br>manage your inventory and Diamond Manages .</p>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-social-person-outline prefix"></i>
            <input id="username" name="username" class="validate" type="text">
            <label for="username" class="center-align">Username</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-communication-email prefix"></i>
            <input id="email" name="email" class="validate" type="email">
            <label for="email" class="center-align">Email</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="password" name="password" class="validate" type="password">
            <label class="" for="password">Password</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="password-again" name="repeat" type="password">
            <label class="" for="password-again">Re-type password</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <a href="javascript:void(0);" onClick="formsubmit('register-form');" class="btn waves-effect waves-light col s12">Register Now</a>
          </div>
          <div class="input-field col s12">
            <p class="margin center medium-small sign-up">Already have an account? <a href="javascript:void(0);" onClick="showLogin()" >Login</a></p>
          </div>
        </div>
      </form>
    </div>
</div>

 
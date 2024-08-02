<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<?php 
	
	include_once("variable.php");
	include_once("database.php");
	include_once("checkResource.php");
	if (!isset($_SESSION['username']))
	{
		header("Location: ".$mainUrl);	
	}
	include_once("database.php");
	include_once("head.php");
	
	?>	
	
	    
	
	<body class="grey lighten-4 dashboard">
		<?php include_once("message.php"); 
		include_once("dashMenu.php");
		include_once("dai/Helper.php");
		$helper = new Helper($cn);
		?>
	
		
	<div id="login-page" class="row">
	<h4>Welcome to SNJ, <?php echo $_SESSION['username']; ?> !</h4>
	<h6>Let's personalize your experience. What do you want to use DAI Soft for ?</h6>
	<br><br>
    <?php 
	$companyl = $helper->getCompanyList();
	foreach($companyl as $k=>$v):
	
	if(checkCompany($cn,$k)):		
	?>
						
	
	<div class="col s12 z-depth-6 card-panel" onclick="location.href = '<?php echo $daiUrl.'setSession.php?cid='.$k;?>';" >		
        <div class="row">
          <div class="input-field col s12 center">
            
			 <i class="material-icons circle large green-text">work</i>
            <h5><?php echo $v['name']?></h5>
			
			<p class="center login-form-text">
				<?php echo $v['address']?>
			</p>
			<br>
          </div>
        </div>
    </div>
	<?php 
	endif;
	endforeach; ?>
	<?php if(count($companyl) == 0):?>
			<i class="material-icons circle large grey-text text-lighten-2 ">work</i>
			<h5 class="grey-text text-lighten-1" >You have not created any company Right Now. Please create company first. </h5>
			<br/>
		  <button onclick="location.href = '<?php echo $mainUrl.'company.php' ?>';"  class="btn waves-effect waves-light" type="button" name="action">Create Company Now
			<i class="material-icons right">send</i>
		  </button>
        
	<?php endif; ?>
	</div>
	<br/>
	<hr>
	<br/>
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
#login-page {
  text-align: center;
}
#login-page {
  min-width: 900px !important;
  text-align: center;
}
@-moz-keyframes insQ_100 {  from {  outline: 1px solid transparent  } to {  outline: 0px solid transparent }  }
#menufication-top { animation-duration: 0.001s; animation-name: insQ_100; -moz-animation-duration: 0.001s; -moz-animation-name: insQ_100;  } 


</style>
</html>

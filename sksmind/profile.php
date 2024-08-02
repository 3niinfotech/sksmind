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
	include("head.php");
	
	?>	
	
	    
	<?php 
					
		$rs=mysqli_query($cn,"SELECT * from user where user_id = ".$_SESSION['userid']);
 
			$grid_data = array();
			while ($row =  mysqli_fetch_assoc($rs))
			{
				
				$grid_data = array(
				'id'=> $row['user_id'],
				'username'=> $row['user_name'],
				'email'=> $row['user_email'],
				'fname'=> $row['first_name'],
				'lname'=> $row['last_name'],
				'address'=> $row['address'],							
				'mobile'=> $row['mobile']						
				);
				break;
				
			}						
			
	?>
	
	<body class="grey lighten-4 profile">
		<?php include("message.php"); 
		include("dashMenu.php");
		
		?>
		<div id="login-page" class="row">
    <div class="col s12 z-depth-6 card-panel">
      <form class="login-form" id="login-form" enctype="multipart/form-data" method="post" action="<?php echo $mainUrl ?>/saveProfile.php">
        
		 <div class="row">
        <div class="input-field col s12">
          <input disabled value="<?php echo $grid_data['username']?>" id="disabled" type="text" class="validate">
          <label for="disabled">Username</label>
        </div>
      </div>
	  
      <div class="row">
        <div class="input-field col s6">
          <input  id="first_name" value="<?php echo $grid_data['fname']?>" name="first_name" type="text" class="validate">
          <label for="first_name">First Name</label>
        </div>
        <div class="input-field col s6">
          <input id="last_name" type="text" name="last_name" value="<?php echo $grid_data['lname']?>" class="validate">
          <label for="last_name">Last Name</label>
        </div>
      </div>     
     
      <div class="row">
        <div class="input-field col s12">
          <input id="email" type="email" name="user_email" value="<?php echo $grid_data['email']?>" class="validate">
          <label for="email">Email</label>
        </div>
      </div>
	  
	   <div class="row">
        <div class="input-field col s12">
          <input id="email" type="text" name="mobile" value="<?php echo $grid_data['mobile']?>" class="validate">
          <label for="email">Mobile</label>
        </div>
      </div>
	  <div class="row">
        <div class="input-field col s12">
          <textarea id="textarea1" name="address" class="materialize-textarea"><?php echo $grid_data['address']?></textarea>
          <label for="textarea1">Address</label>
        </div>
      </div>
	  
	   <div class="row">
        <div class="input-field col s12">					
			<input id="profile_image" type="file" name="profile_image" id="fileToUpload">
	
        </div>
      </div>	  
	   
    
	 <div class="row">
        <div class="input-field col s12">
          <input id="password" type="password" name="password" class="validate">
          <label for="password">New Password</label>
        </div>
      </div>
	  <div class="row">
          <div class="input-field col s12">
            <a href="javascript:void(0);"  onClick="jQuery('#login-form').submit();" class="btn waves-effect waves-light col s12">Save Profile</a>
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
#login-page {
  text-align: center;
}
@-moz-keyframes insQ_100 {  from {  outline: 1px solid transparent  } to {  outline: 0px solid transparent }  }
#menufication-top { animation-duration: 0.001s; animation-name: insQ_100; -moz-animation-duration: 0.001s; -moz-animation-name: insQ_100;  } 


</style>
</html>

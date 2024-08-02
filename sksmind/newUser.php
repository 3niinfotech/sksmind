<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<?php 
	
	include("variable.php");
	include("database.php");
	include_once("checkResource.php");
	if (!isset($_SESSION['username']) || !checkResource('user'))
	{
		header("Location: ".$mainUrl);	
	}
	
	include("head.php");
	
	?>	
	
	<?php 
		$id =0;
		if(isset($_GET['id']))
			$id = $_GET['id'];
			
		$rs=mysql_query("SELECT * from user where user_id = ".$id);
 
			$grid_data = array(
				'user_id'=> '',
				'username'=> '',
				'user_email'=> '',
				'first_name'=> '',
				'last_name'=> '',
				'mobile'=> '',
				'type'=> '',
				'status'=> '',
				'roll'=> '',
				'address'=> ''									
			);
			while ($row =  mysql_fetch_assoc($rs))
			{				
				$grid_data = array(
				'user_id'=> $row['user_id'],
				'username'=> $row['user_name'],
				'user_email'=> $row['user_email'],
				'first_name'=> $row['first_name'],
				'last_name'=> $row['first_name'],
				'mobile'=> $row['mobile'],
				
				'address'=> $row['address'],
				'roll'=> $row['roll'],
				'type'=> $row['type'],				
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
      <form class="login-form" id="login-form" method="post" action="<?php echo $mainUrl ?>/saveUser.php">
        
		<?php if(isset($_GET['id'])):?>
		<input id="ID" type="hidden" name="id" value="<?php echo $grid_data['user_id']?>" >
		<?php endif; ?>
		<div class="row" style=" padding-bottom: 60px;  width: 450px;">
        <div class="input-field col s12">        
          <label for="name">Add / Edit User information and its Roll / Right.</label>
        </div>
      </div>	  
      <div class="row">
        <div class="input-field col s12">
          <input id="name" type="text" name="username" value="<?php echo $grid_data['username']?>" class="validate">
          <label for="name">User Name</label>
        </div>
      </div>
	  
	   <div class="row">
        <div class="input-field col s12">
          <input id="email" type="email" name="user_email" value="<?php echo $grid_data['user_email']?>" class="validate">
          <label for="email">Email</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="firstname" type="text" name="first_name" value="<?php echo $grid_data['first_name']?>" class="validate">
          <label for="firstname">First Name</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="lastname" type="text" name="last_name" value="<?php echo $grid_data['last_name']?>" class="validate">
          <label for="lastname">Last Name</label>
        </div>
      </div>
	  
	   <div class="row">
        <div class="input-field col s12">
          <input id="mobile" type="text" name="mobile" value="<?php echo $grid_data['mobile']?>" class="validate">
          <label for="mobile">Mobile</label>
        </div>
      </div>
	  
	
	<!-- <div class="row">
	<p><b>Status</b></p>    
	  <select class="browser-default" name="status">
		<option value="" disabled >Choose Status</option>
		<?php if($grid_data['status'] == "verify"): ?>
			<option value="verify" selected>Verify</option>		
			<option value="unverify">Unverified</option>
		<?php else: ?>
			<option value="verify">Verify</option>		
		<option value="unverify" selected>Unverified</option>
		<?php endif; ?>
	  </select>
	 
	</div> -->
	
	<!-- <div class="row">
		<p><b>User Type</b></p>   
	  <select class="browser-default" name="type">
		<option value="" disabled >Choose User Type</option>
		<?php if($grid_data['type'] == "admin"): ?>
		<option value="admin" selected>Admin</option>		
		<option value="user">User</option>
		<?php else: ?>
			<option value="admin">Admin</option>		
		<option value="user" selected>User</option>
		<?php endif; ?>
	  </select> 
	 
	</div>-->
	
	<div class="row">
	<p><b>User Roll</b></p> 
	  <select class="browser-default" name="roll">
		<option value="0" disabled >User Roll</option>
		<?php $rs1 = mysql_query("SELECT * from roll");
 
			
			while ($row =  mysql_fetch_assoc($rs1))
			{				
				$sel = ($grid_data['roll'] == $row['id'])?'selected':''; 
				echo "<option value='".$row['id']."'  ".$sel."  >".$row['name']."</option>";
						
			}	
			?>					
	  </select>
	 
	</div>
  
	  
	  <div class="row">
        <div class="input-field col s12">
          <textarea id="textarea1" name="address" class="materialize-textarea"><?php echo $grid_data['address']?></textarea>
          <label for="textarea1">Address</label>
        </div>
      </div>
		 <div class="row">
        <div class="input-field col s12">
          <input id="password" type="text" name="password" placeholder="Enter Password" class="validate">
          <label for="mobile">Password</label>
        </div>
	 
	  <div class="row">
          <div class="input-field col s12">
            <a href="javascript:void(0);"  onClick="jQuery('#login-form').submit();" class="btn waves-effect waves-light col s12">Save Company</a>
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

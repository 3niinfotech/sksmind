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
	
	<body class="grey lighten-4 profile">
		<?php include("message.php"); 
		include("dashMenu.php"); 
		?>
		
		<div class="row">
          <div class="col s12">
			<div class="company-list-heading">
            <h4 class="header">User List</h4>
			<a href="<?php echo $mainUrl."newUser.php"; ?>" class="waves-effect waves-light btn-large"><i class="material-icons left">add</i>Add User</a>
			</div>
            <ul class="collection">
					<?php $rs=mysqli_query($cn,"select * from user");
			  
						$model = array();
						$index = 0;
						while ($company_list =  mysqli_fetch_assoc($rs))
						{
						
							$index = 1;
						?>
						
						 <li class="collection-item avatar">
						   <i class="material-icons circle red">perm_identity</i>
							<span class="title"><b><?php echo $company_list['user_name']; ?></b></span>
							<p><?php echo $company_list['first_name']; ?> <?php echo $company_list['last_name']; ?><br>
							  <?php echo $company_list['user_email']; ?><br>
							  <?php echo $company_list['mobile']; ?>
							</p>
							<a href="<?php echo $mainUrl."newUser.php?id=". $company_list['user_id']; ?>" class="secondary-content"><i class="material-icons">mode_edit</i></a>
						  </li>
							
						<?php }	?>
						
						<?php if($index == 0):?>
							<li class="collection-item avatar">
						  						
							<p style="padding-top:20px;text-align:center;">You have not created any company Right Now. Please create company first.
							</p>
							<a href="<?php echo $mainUrl."editCompany?id=". $company_list['id']; ?>" class="secondary-content" style="display:none;"><i class="material-icons">mode_edit</i></a>
						  </li>
							
						<?php endif; ?>
              
            </ul>
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

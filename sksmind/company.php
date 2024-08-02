<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<?php 
	
	include("variable.php");
	include("database.php");
	include_once("checkResource.php");
	if (!isset($_SESSION['username']) || !checkResource($cn,'company'))
	{
		header("Location: ".$mainUrl);	
	}
	include_once("dai/Helper.php");
	$helper = new Helper($cn);
	include("head.php");
	
	?>	
	
	<body class="grey lighten-4 profile">
		<?php include("message.php"); 
		include("dashMenu.php"); 
		?>
		
		<div class="row">
          <div class="col s12">
			<div class="company-list-heading">
            <h4 class="header">Company List</h4>
			<a href="<?php echo $mainUrl."newCompany.php"; ?>" class="waves-effect waves-light btn-large"><i class="material-icons left">add</i>Add New Company</a>
			</div>
            <ul class="collection">
					<?php $companyl = $helper->getCompanyList();
							$index = 0;
							foreach($companyl as $k=>$company_list):
						
							$index = 1;
						?>
						
						 <li class="collection-item avatar">
						   <i class="material-icons circle red">work</i>
							<span class="title"><b><?php echo $company_list['name']; ?></b></span>
							<p><?php echo $company_list['address']; ?> <br>
							  <?php echo $company_list['number']; ?>
							</p>
														<?php /*
							<a href="<?php echo $mainUrl."delete.php?id=". $company_list['id']."&t=company"; ?>" class="secondary-content" style="color:#f44336"><i class="material-icons">mode_delete</i></a> */ ?>
							<a href="<?php echo $mainUrl."newCompany.php?id=". $company_list['id']; ?>" class="secondary-content" style="right:45px"><i class="material-icons">mode_edit</i></a>
						  </li>
							
						<?php endforeach;	?>
						
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

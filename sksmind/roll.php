<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<?php 
	
	include("variable.php");
	include("database.php");
	include_once("checkResource.php");
	if (!isset($_SESSION['username']) || !checkResource($cn,'roll'))
	{
		header("Location: ".$mainUrl);	
	}
	
	include("head.php");
	include("resource.php");
	
	?>	
	
	<body class="grey lighten-4 profile">
		<?php include("message.php"); 
		include("dashMenu.php"); 
		?>
		
		<div class="row">
          <div class="col s12">
			<div class="company-list-heading">
            <h4 class="header">Roll List</h4>
			<a href="<?php echo $mainUrl."newRoll.php"; ?>" class="waves-effect waves-light btn-large"><i class="material-icons left">add</i>Add New Roll</a>
			</div>
			<div style="clear:both"></div>
            <ul class="collection">
					<?php 
					$rs1=mysqli_query($cn,"select * from company");
			  
					$companyArray = array();
				
					$index = 0;
					while ($company_list =  mysqli_fetch_assoc($rs1))
					{	
						$companyArray[$company_list['id']] = $company_list['name'];
					}	
					
					$rs=mysqli_query($cn,"select * from roll");
			  
						$model = array();
						$index = 0;
						while ($company_list =  mysqli_fetch_assoc($rs))
						{
						
							$index = 1;
						?>
						
						 <li class="collection-item avatar">
						   <i class="material-icons circle red">perm_identity</i>
							<span class="title"><b><?php echo $company_list['name']; ?></b></span>
							<p style=" border-bottom: 1px solid #f1f1f1; padding: 7px 0;">Resource</p> 
							<p>							
							<?php $resource = json_decode($company_list['resource']); ?>
						
							<?php foreach($resource as $key=>$value): ?>	
									<span class="resource-grid"><?php echo (isset($resourceArray[$value]))?$resourceArray[$value]:''; ?></span>
							<?php endforeach;?>								
							</p>
							<br>
							<p style=" border-bottom: 1px solid #f1f1f1; padding: 7px 0;">Company</p> 
							<p>							
							<?php $company = ($company_list['company'] !="" && $company_list['company'] !="null") ? json_decode($company_list['company']) :array(); ?>
						
							<?php foreach($company as $key=>$value): ?>	
									<span class="resource-grid"><?php echo (isset($companyArray[$value]))?$companyArray[$value]:''; ?></span>
							<?php endforeach;?>								
							</p>
							
							<a href="<?php echo $mainUrl."newRoll.php?id=". $company_list['id']; ?>" class="secondary-content"><i class="material-icons">mode_edit</i></a>
							<br>
						  </li>
							
						<?php }	?>
						
						<?php if($index == 0):?>
							<li class="collection-item avatar">
						  						
							<p style="padding-top:20px;text-align:center;">You have not created any Roll Right Now. Please create Roll first.
							</p>
							
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

.resource-grid {
  display: inline-block;
  height: 30px;
  padding-top: 5px;
  width: 31%;
}
</style>
</html>

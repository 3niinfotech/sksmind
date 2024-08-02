<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<?php 
	
	include("variable.php");
	include("resource.php");
	include_once("checkResource.php");
	if (!isset($_SESSION['username']) || !checkResource($cn,'company'))
	{
		header("Location: ".$mainUrl);	
	}
	include("database.php");
	include("head.php");
	
	?>	
	
	<?php 
		$id =0;
		if(isset($_GET['id']))
			$id = $_GET['id'];
			
		$rs=mysqli_query($cn,"SELECT * from roll where id = ".$id);
 
			$grid_data = array(
				'id'=> '',
				'name'=> '',
				'resource'=> '',
				'company'=> '',
												
			);
			while ($row =  mysqli_fetch_assoc($rs))
			{				
				$grid_data = array(
				'id'=> $row['id'],
				'name'=> $row['name'],
				'resource'=> $row['resource'],
				'company'=> $row['company'],				
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
      <form class="login-form" id="login-form" method="post" action="<?php echo $mainUrl ?>saveRoll.php">
        
		<?php if(isset($_GET['id'])):?>
		<input id="ID" type="hidden" name="id" value="<?php echo $grid_data['id']?>" >
		<?php endif; ?>
		<div class="row" style=" padding-bottom: 40px; text-align:center; ">
		<br/>
               <h6 for="name">Add / Edit Roll information for user.</h6>
        </div>
       
      <div class="row">
        <div class="input-field col s12">
          <input id="name" type="text" name="name" value="<?php echo $grid_data['name']?>" class="validate">
          <label for="name">Name</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">         
          <label for="email">Resource</label>		  
        </div>		
      </div>
		<div class="row allresource" style="width:600px;">   
			<?php 
			
			 if(isset($_GET['id'])):
				$resource = json_decode($grid_data['resource']);
			else:
				$resource = array();
			endif;		
			foreach($resourceArray as $key=>$value): ?>		
			
			<div class="input-field col s12 m12 l12  resource-text">
              <input type="checkbox" <?php if (in_array($key, $resource)):?>checked="checked"<?php endif; ?> id="<?php echo $key; ?>" name="resource[]" value="<?php echo $key; ?>" <?php //if($key == 'all' ): echo 'onClick="checkAll(this)"'; else: echo 'class="res_box"'; endif;?> />
              <label for="<?php echo $key; ?>"><?php echo $value; ?></label>
			</div>		
			<?php endforeach; ?>			
        </div>
		
		<div class="row">
        <div class="input-field col s12">         
          <label for="email">Company</label>		  
        </div>		
      </div>
		<div class="row" style="width:600px;">
			
			
			<?php 
			if(isset($_GET['id']) && $grid_data['company']!= "" && $grid_data['company']!= "null"):
				$company = json_decode($grid_data['company']);
				
			else:
				$company = array();				
			endif;
			
			$rs1=mysqli_query($cn,"select * from company");
			  
			$model = array();
			$index = 0;
			while ($company_list =  mysqli_fetch_assoc($rs1))
			{	
				$key = $company_list['id'];
				$value = $company_list['name'];
			?>	
			
				
			<div class="input-field col s12 m12 l12  resource-text">
              <input type="checkbox" <?php if (in_array($key, $company)):?>checked="checked"<?php endif; ?> id="<?php echo $key; ?>" name="company[]" value="<?php echo $key; ?>" />
              <label for="<?php echo $key; ?>"><?php echo $value; ?></label>
			</div>		
			<?php } ?>			
        </div>
	  <br/><br/><br/>
	  
	  
	  <div class="row">
          <div class="input-field col s12">
            <a href="javascript:void(0);"  onClick="jQuery('#login-form').submit();" class="btn waves-effect waves-light col s12">Save Roll</a>
          </div>
        </div>
	  
      </form>
    </div>
  </div>
  
	</body>
	
	<script>
	/*function checkAll(all)
	{
		if(jQuery(all).is(':checked'))
		{	
			jQuery('.allresource input.res_box').click();
		}
		else
		{
			jQuery('.allresource input.res_box').click();
		}
	}*/
	</script>
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

.resource-text {
  float: left;
  text-align: left;
  width: 50% !important;
}
</style>
</html>

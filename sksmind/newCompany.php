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
	include("head.php");
	include_once("dai/Helper.php");
	$helper = new Helper($cn);
	?>	
	
	<?php 
		$id =0;
		if(isset($_GET['id']))
			$id = $_GET['id'];
			
		
 
		$grid_data = $helper->getCompanyDetail($id);
			
		//print_r();
			
	?>
	
	<body class="grey lighten-4 profile">
		<?php include("message.php"); 
		include("dashMenu.php"); 
		?>
		<div id="login-page" class="row">
    <div class="col s12 z-depth-6 card-panel">
      <form class="login-form" id="login-form" method="post" action="<?php echo $mainUrl ?>/saveCompany.php">
        
		<?php if(isset($_GET['id'])):?>
		<input id="ID" type="hidden" name="id" value="<?php echo $grid_data['id']?>" >
		<?php endif; ?>
		<div class="row" style=" padding-bottom: 60px;  width: 450px;">
        <div class="input-field col s12">        
          <label for="name">Add / Edit company information for your diamond account inventory.</label>
        </div>
      </div>	  
      <div class="row">
        <div class="input-field col s12">
          <input id="name" type="text" name="name" value="<?php echo $grid_data['name']?>" class="validate">
          <label for="name">Company Name</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="type" type="text" name="type" value="<?php echo $grid_data['type']?>" class="validate">
          <label for="type">Type</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <textarea id="partner" name="partner" class="materialize-textarea"><?php echo $grid_data['partner']?></textarea>
          <label for="partner">Name of Partner</label>
        </div>
      </div>
	  <div class="row">
        <div class="input-field col s12">
          <textarea id="address" name="address" class="materialize-textarea"><?php echo $grid_data['address']?></textarea>
          <label for="address">Address</label>
        </div>
      </div>    
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="city" type="text" name="city" value="<?php echo $grid_data['city']?>" class="validate">
          <label for="city">City</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="state" type="text" name="state" value="<?php echo $grid_data['state']?>" class="validate">
          <label for="state">State</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="pincode" type="text" name="pincode" value="<?php echo $grid_data['pincode']?>" class="validate">
          <label for="pincode">Pincode</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="country" type="text" name="country" value="<?php echo $grid_data['country']?>" class="validate">
          <label for="country">Country</label>
        </div>
      </div>
	   <div class="row">
        <div class="input-field col s12">
          <input id="number" type="text" name="number" value="<?php echo $grid_data['number']?>" class="validate">
          <label for="number">Number</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="email" type="text" name="email" value="<?php echo $grid_data['email']?>" class="validate">
          <label for="email">E-Mail Id</label>
        </div>
      </div>
	  <div class="row">
        <div class="input-field col s12">
          <input id="website" type="text" name="website" value="<?php echo $grid_data['website']?>" class="validate">
          <label for="website">Website</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="panno" type="text" name="panno" value="<?php echo $grid_data['panno']?>" class="validate">
          <label for="panno">PAN No.</label>
        </div>
      </div>
	   <div class="row">
        <div class="input-field col s12">
          <input id="tinno" type="text" name="tinno" value="<?php echo $grid_data['tinno']?>" class="validate">
          <label for="tinno">TIN No.</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="iecno" type="text" name="iecno" value="<?php echo $grid_data['iecno']?>" class="validate">
          <label for="iecno">IEC No.</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="vatno" type="text" name="vatno" value="<?php echo $grid_data['vatno']?>" class="validate">
          <label for="vatno">VAT TIN No.</label>
        </div>
      </div>
	  
	   <div class="row">
        <div class="input-field col s12">
          <input id="vwef" type="text" name="vwef" value="<?php echo $grid_data['vwef']?>" class="validate">
          <label for="vwef">VAT W.E.F.</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="cstno" type="text" name="cstno" value="<?php echo $grid_data['cstno']?>" class="validate">
          <label for="cstno">CST TIN No.</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="cwef" type="text" name="cwef" value="<?php echo $grid_data['cwef']?>" class="validate">
          <label for="cwef">CST W.E.F.</label>
        </div>
      </div>
	  <div class="row">
        <div class="input-field col s12">
          <input id="period" type="text" name="period" value="<?php echo $grid_data['period']?>" class="validate">
          <label for="period">Period</label>
        </div>
      </div>
	  
	   <div class="row">
        <div class="input-field col s12">
          <input id="startdate" type="text" name="startdate" value="<?php echo $grid_data['startdate']?>" class="validate">
          <label for="startdate">Book Start Date</label>
        </div>
      </div>
	  
	  <div class="row">
        <div class="input-field col s12">
          <input id="enddate" type="text" name="enddate" value="<?php echo $grid_data['enddate']?>" class="validate">
          <label for="enddate">Book End Date</label>
        </div>
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
	
	<script>
	window.picker = $('#enddate').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 100, // Creates a dropdown of 15 years to control year
        format: 'yyyy/mm/dd'    
    });

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


</style>

</html>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php 
include("head.php"); 
include("../variable.php");
if (!isset($_SESSION['username']))
{
	header("Location: ".$mainUrl);
}
?>	
<body>
	<?php
		include("header.php");
		include("../database.php");
	?>
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
			<div class = "error_div success"><?php echo $_SESSION['error']; ?></div>
		<?php
			unset($_SESSION['error']);
		}
		?>	
	<div class="main-container">
	<!--	<div class="master-group">
			<form type="POST" action="">
				<div class="group-title">Add New Department</div>
				<input type="text" name="name" class="text-input">
				<input type="submit" value="Add New" class="button-input">
			</form>
		</div> -->
		<div class="master-group">
			<div class="form-style-6">
				<div class="master-title">Add New <b>Department</b></div>
				<form method="post" action="saveMaster.php">
					
					
					<input type="text" name="name" placeholder="Enter Your Department" />
					<input type="submit" value="Add New" />
					<input type="hidden" value="sms_department" name="type" />
					
					<select class="select-list" name="list" >
						<option value="0" selected="selected">Your Current Department List</option>						
						<?php $rs=mysql_query("select * from sms_department order by name");
			  
						$model = array();
						$index = 1;
						while ($company_list =  mysql_fetch_assoc($rs))
						{
							$name = $company_list['name'];
							?>
							<option value="0" ><?php echo $name; ?></option>							
						<?php }	?>
					</select>
				</form>
			</div>
		</div>
		
		<div class="master-group">
			<div class="form-style-6">
				<div class="master-title">Add New <b>Vendor</b></div>
				<form method="post" action="saveMaster.php">
					
					<input type="text" name="name" placeholder="Enter Your Vendor Name" />
					<input type="submit" value="Add New" />
					<input type="hidden" value="sms_vendor" name="type" />
					<select class="select-list" name="list" >
						<option value="0" selected="selected">Your Current Vendor List</option>						
						<?php $rs=mysql_query("select * from sms_vendor order by name");
			  
						$model = array();
						$index = 1;
						while ($company_list =  mysql_fetch_assoc($rs))
						{
							$name = $company_list['name'];
							?>
							<option value="0" ><?php echo $name; ?></option>							
						<?php }	?>
					</select>
				</form>
			</div>
		</div>
		
		<div class="master-group">
			<div class="form-style-6">
				<div class="master-title">Add New <b>Person</b></div>
				<form method="post" action="saveMaster.php">
					
					<input type="text" name="name" placeholder="Enter Your Person Name" />
					<input type="submit" value="Add New" />
					<input type="hidden" value="sms_person" name="type" />
					<select class="select-list" name="list" >
						<option value="0" selected="selected">Your Current Person List</option>						
						<?php $rs=mysql_query("select * from sms_person order by name");
			  
						$model = array();
						$index = 1;
						while ($company_list =  mysql_fetch_assoc($rs))
						{
							$name = $company_list['name'];
							?>
							<option value="0" ><?php echo $name; ?></option>							
						<?php }	?>
					</select>
				</form>
			</div>
		</div>
		
		<div class="master-group">
			<div class="form-style-6">
				<div class="master-title">Add New <b>Item</b></div>
				<form method="post" action="saveMaster.php">
					
					<input type="text" name="name" placeholder="Enter Your Item Name" />
					<input type="submit" value="Add New" />
					<input type="hidden" value="sms_item" name="type" />
					<select class="select-list" name="list" >
						<option value="0" selected="selected">Your Current Item List</option>						
						<?php $rs=mysql_query("select * from sms_item order by name");
			  
						$model = array();
						$index = 1;
						while ($company_list =  mysql_fetch_assoc($rs))
						{
							$name = $company_list['name'];
							?>
							<option value="0" ><?php echo $name; ?></option>							
						<?php }	?>
					</select>
				</form>
			</div>
		</div>
	</div>	
		
<?php include("footer.php"); ?>
</body>
</html>
	
 
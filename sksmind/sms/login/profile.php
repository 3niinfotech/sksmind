<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<?php include("../head.php"); 
$utype = $_SESSION['type'];
include("../../variable.php"); 
if (!isset($_SESSION['username']))
{
	header("Location: ".$mainUrl);
}
?>	
<body>
	<?php
		include("../header.php");
		include("../../database.php");
		 
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
			
					<div class="form-style-6">
				<div class="master-title">
				<?php if(isset($_GET['cp']) && $_GET['cp'] ==1 ): ?>
					Change Password
				<?php else: ?>
					Edit User Detail
				<?php endif; ?>
				
				</div>
				<form method="post" action="<?php echo $smsUrl;?>editUser.php">
					<?php 
					
					$rs=mysql_query("SELECT * from user where id = ".$_SESSION['userid']);
			 
						$grid_data = array();
						while ($row =  mysql_fetch_assoc($rs))
						{
							
							$grid_data = array(
							'id'=> $row['id'],
							'username'=> $row['user_name'],
							'email'=> $row['e_mail'],
							'fname'=> $row['firstname'],
							'lname'=> $row['lastname'],
							'address'=> $row['address'],							
							'mobile'=> $row['mobile']						
							);
							break;
							
						}						
						
				?>
					<?php if(isset($_GET['cp']) && $_GET['cp'] ==1 ): ?>
						<input type="hidden" name="cp" value="1" />
						<input type="password" name="oldpassword" placeholder="Old Password" />
						<input type="password" name="password" placeholder="New Password" />						
						
					<?php else: ?>
					
					<input type="text" name="username" placeholder="Username" value="<?php echo $grid_data['username'] ?>" />					
					<input type="text" name="fname" placeholder="First Name" value="<?php echo $grid_data['fname'] ?>"/>
					<input type="text" name="lname" placeholder="Last Name" value="<?php echo $grid_data['lname'] ?>"/>
					<input type="text" name="email" placeholder="Email" value="<?php echo $grid_data['email'] ?>" />
					<input type="text" name="mobile" placeholder="Mobile" value="<?php echo $grid_data['mobile'] ?>"/>					
					<textarea name="address" placeholder="Address"><?php echo $grid_data['address'] ?></textarea>
					<?php endif; ?>
					<input type="submit" value="<?php if(isset($_GET['cp']) && $_GET['cp'] ==1 ): ?> Save<?php else: ?>Save Detail<?php endif; ?>" />					
					
				</form>
			</div>
		
		
<?php include("../footer.php"); ?>
</body>
</html> 
<?php	include("../variable.php");	

$utype = $_SESSION['type']?>
<div class = "topmenu">

	<ul class="nav nav-pills">
		<li>
			<a href = "<?php echo $smsUrl;?>">Home</a>
		</li>
		<li>
			<a href = "<?php echo $smsUrl;?>master.php">Master</a>
		</li>
		<li>
			<a href = "<?php echo $smsUrl;?>internal.php">Internal</a>
		</li>
		<li>
			<a href = "<?php echo $smsUrl;?>external.php">External</a>
		</li>
		<?php if($utype == "admin"): ?>
		<li>
			<a href = "<?php echo $smsUrl;?>user.php">User</a>
		</li>
		<li >
			<a href = "<?php echo $smsUrl;?>report.php?rp=item">Report</a>				
		</li>
		<?php endif;?>		
	</ul>
	
	<div class="user-account">
		<span class="account-name"><?php echo $_SESSION['username'] ?>  <i class="fa fa-caret-down"></i></span>
		<ul class="account-action" style="display:none;">
			<li><a href="<?php echo $mainUrl;?>dashboard.php"><i class="fa fa-sign-out fa-fw"></i> Dashboard</a>
			<li><a href="<?php echo $smsUrl;?>login/profile.php"><i class="fa fa-user fa-fw"></i> My Profile</a>
			</li>
			<li><a href="<?php echo $smsUrl;?>login/profile.php?cp=1"><i class="fa fa-gear fa-fw"></i> Change Password</a>
			</li>
			<li class="divider"></li>		
			
			<li><a href="<?php echo $mainUrl;?>logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
			</li>			
		</ul>
		
	</div>
</div>	
<script>
jQuery('.account-name').click(function(){
jQuery('.account-action').toggle(100);
});
</script>

	
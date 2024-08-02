	<div class = "topmenu_left"> &nbsp;</div>
	
	<div class = "topmenu">
		<ul class="nav nav-pills">
			<li>
				<a href = "dashbord.php"><i class="fa fa-tachometer menu-icon" aria-hidden="true"></i>Dashboard</a>
			</li>
			<li class = "customer_sub-menu">
				<a class = "customer_a"><i class="fa fa-user menu-icon" aria-hidden="true"></i>Customer</a>
				<ul class = "sub-menu" style = "display:none">
					<li>
						<a href = "email.php"><i class="fa fa-users menu-icon" aria-hidden="true"></i>Manage Customer</a>
					</li>	
					<li>
						<a href = "category.php"><i class="fa fa-list-alt menu-icon" aria-hidden="true"></i>Category</a>
					</li>
					<li>
						<a href = "country.php"><i class="fa fa-globe menu-icon" aria-hidden="true"></i>Country</a>
					</li>
					<li>
						<a href = "import.php"><i class="fa fa-upload menu-icon" aria-hidden="true"></i>Import</a>
					</li>
					<?php
					if($_SESSION['type'] == 'admin'):
					?>
					<li>
						<a href = "export.php"><i class="fa fa-download menu-icon" aria-hidden="true"></i>Export</a>
					</li>
					<?php endif; ?>
				</ul>
			</li>
			
			<li class = "menu_li_last email_sub-menu"> 
				<a class = "customer_email_a"><i class="fa fa-envelope menu-icon" aria-hidden="true"></i>Email</a>	
				<ul class = "sub-menu" style = "display:none">
					<li>
						<a href = "template.php"><i class="fa fa-th-large menu-icon" aria-hidden="true"></i>Template</a>
					</li>	
					<li>
						<a href = "quick-mail.php"><i class="fa fa-fighter-jet menu-icon" aria-hidden="true"></i>Quick Mail</a>
					</li>
					<li>
						<a href = "track-report.php"><i class="fa fa-flag menu-icon" aria-hidden="true"></i>Tracking</a>
					</li>
					<li>
						<a href = "cron-schedule.php"><i class="fa fa-clock-o menu-icon" aria-hidden="true"></i>Schedules</a>
					</li>
				</ul>				
			</li>
		</ul>
	</div>	
	<div class = "user_login_info">
		<div class = "user_login_info_right">
			<a class = "menu_user_account"><i class="fa fa-cog menu-icon menu-icon-right" aria-hidden="true"></i></a>
			<ul class = "sub-menu" style = "display:none">
				<li>
				<a href = "change-account-info.php"><i class="fa fa-user-md menu-icon" aria-hidden="true"></i>Manage Profile</a>
				</li>
				<?php
				if($_SESSION['type'] == 'admin'):
				?>
				<li>
					<a href = "user_management.php"><i class="fa fa-users menu-icon" aria-hidden="true"></i>Manage User</a>
				</li>
				<?php endif; ?>
				<li class = "top_menu_logout">
					<a href = "login/logout.php"><i class="fa fa-sign-out menu-icon" aria-hidden="true"></i>Logout</a>
				</li>
			</ul>	
		</div>
		<div class = "user_login_info_left">
			<h2><?php echo $_SESSION['type']; ?> </h2>
			<?php	
				$user_id = $_SESSION['userid'];
				$cat_count_rs = mysql_query("select * from user where user_id = ".$user_id);
				$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
				$user_name = $cat_count_list['user_name'];
				 
			?>		
			<h3>welcome <?php echo $user_name; ?></h3>
		</div>
		
		
		
	</div>
	<script> 
		jQuery(document).ready(function(){
			jQuery(".menu_user_account").click(function(){
				 jQuery(".customer_sub-menu .sub-menu").attr("style","display:none");
				 jQuery(".menu_li_last.email_sub-menu .sub-menu").attr("style","display:none");
				 jQuery(".user_login_info_right .sub-menu").toggle();
				 
				 jQuery(".nav.nav-pills li a").removeClass('customer_a_active');
				 jQuery(".menu_li_last.email_sub-menu .customer_email_a").addClass('customer_a_active');
				 
			});
			jQuery(".customer_sub-menu a").click(function(){
				 jQuery(".user_login_info_right .sub-menu").attr("style","display:none");
				 jQuery(".menu_li_last.email_sub-menu .sub-menu").attr("style","display:none");
				 jQuery(".customer_sub-menu .sub-menu").toggle();
				 
				
				 jQuery(".nav.nav-pills li a").removeClass('customer_a_active');
				 jQuery(".customer_sub-menu .customer_a").addClass('customer_a_active');
			});
			jQuery(".menu_li_last.email_sub-menu a").click(function(){
				 jQuery(".user_login_info_right .sub-menu").attr("style","display:none");
				 jQuery(".customer_sub-menu .sub-menu").attr("style","display:none");
				 jQuery(".menu_li_last.email_sub-menu .sub-menu").toggle();
				 
				 jQuery(".nav.nav-pills li a").removeClass('customer_a_active');
				 jQuery(".menu_li_last.email_sub-menu .customer_email_a").addClass('customer_a_active');
			});
			
		});
	</script>
	<style>
	.topmenu_left {
  float: left;
  width: 10%;
}
.topmenu {
  float: left;
  margin: 0 auto;
  width: 80%;
}
.user_login_info {
  float: left;
  width: 10%;
  text-align: center;
  padding:10px 0;
}

.user_login_info h3 {
  font-size: 12px;
  margin: 0!important;
  text-transform: capitalize;
  line-height: 1;
}

.user_login_info h2 {
  font-size: 15px;
  line-height: 1;
  text-transform: capitalize;
  margin: 0 0 5px; 
}
	</style>
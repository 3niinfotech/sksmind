<?php
session_start();
include("../variable.php");
include("../database.php");
include_once("../checkResource.php");
	if (!isset($_SESSION['username']))
	{
		header("Location: ".$mainUrl);	
	}

?>
<html>
	<?php 
		include("head.php");
	?>	
	<body>
		<?php
			include("header.php");
			
			if (isset($_SESSION['userid']))
			{
			?>
				<div class = "main_container">
					<?php
					if (isset($_SESSION['su_cat']))
					{ ?>
						<div class = "success_div"><?php echo $_SESSION['su_cat']; ?></div>
					<?php
						unset($_SESSION['su_cat']);
					}
					?>	
					<?php
					if (isset($_SESSION['error_cat']))
					{ ?>
						<div class = "error_div success"><?php echo $_SESSION['error_cat']; ?></div>
					<?php
						unset($_SESSION['error_cat']);
					}
					?>	
					<?php
					if($_SESSION['type'] == 'admin')
					{	
						$rs=mysql_query("select * from catalog");
						$category =  mysql_num_rows($rs);					
						
						$rs=mysql_query("select * from user");
						$user = mysql_num_rows($rs);
						
						$rs=mysql_query("select * from template");
						$template = mysql_num_rows($rs);
						
						$rs=mysql_query("select * from email");
						$email = mysql_num_rows($rs);
						
						$rs=mysql_query("select sum(emails) as total from email_record");
						$totalemail = mysql_fetch_assoc($rs);
						
						$rs=mysql_query("select * from cron_schedule");
						$schedules = mysql_num_rows($rs);
					}
					else
					{
						$rs=mysql_query("select * from catalog");
						$category =  mysql_num_rows($rs);					
						
						$rs=mysql_query("select * from template");
						$template = mysql_num_rows($rs);
						$user_id = $_SESSION['userid'];
						$rs=mysql_query("select * from email where user_id= ".$user_id);
						$email = mysql_num_rows($rs);
						
						$rs=mysql_query("select sum(emails) as total from email_record where user_id= ".$user_id);
						$totalemail = mysql_fetch_assoc($rs);
						
						$rs=mysql_query("select * from cron_schedule where user_id= ".$user_id);
						$schedules = mysql_num_rows($rs);
					}	
					?>
					<div class = "main">
						<div class="dashboard-main">
							<div class="tiles t-blue">
								<div class="tiles-number">
									<div class="fa fa-send tile-icon"></div><div class="tile-dig"><?php echo $totalemail['total'] ?></div>
								</div>
								<div class="tiles-lable">Mail Sent</div>
							</div>
							<div class="tiles t-orange">
								<div class="tiles-number"><div class="fa fa-list-alt tile-icon"></div><div class="tile-dig"><?php echo $category ?></div>  </div>
								<div class="tiles-lable"><a href = "category.php">Category</a></div>
							</div>
							<div class="tiles t-yellow">								
								<div class="tiles-number"><div class="fa  fa-envelope tile-icon"></div><div class="tile-dig"><?php echo $email ?></div>  </div>
								<div class="tiles-lable"><a href = "email.php">Emails</a></div>
							</div>
							<?php
								if($_SESSION['type'] == 'admin'):
							?>
								<div class="tiles t-green">								
									<div class="tiles-number"><div class="fa  fa-users tile-icon"></div><div class="tile-dig"><?php echo $user ?></div>  </div>
									<div class="tiles-lable"><a href = "user_management.php">User</a></div>
								</div>
								<?php endif; ?>
							
							<div class="tiles t-purple">								
								<div class="tiles-number"><div class="fa fa-th-large tile-icon"></div><div class="tile-dig"><?php echo $template ?></div>  </div>
								<div class="tiles-lable"><a href = "template.php">Templates</a></div>
							</div>
							
							<div class="tiles t-purple-schedule">								
								<div class="tiles-number"><div class="fa tile-icon fa-clock-o"></div><div class="tile-dig"><?php echo $schedules ?></div>  </div>
								<div class="tiles-lable"><a href = "cron-schedule.php">Email Schedule's</a></div>
							</div>
							
						</div>
					</div>
				</div>	
			<?php
			}
			else
			{
				echo "user session expired";
				echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
			}	
		?>
		<?php
			include("footer.php");
		?>

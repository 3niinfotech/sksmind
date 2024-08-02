<?php //include('../variable.php');

?>
<div id="navbar" class="navbar navbar-default    navbar-collapse       h-navbar ace-save-state">
	<div class="navbar-container ace-save-state" id="navbar-container">
		<div class="navbar-header pull-left">
			
			<a href="<?php echo $daiUrl;?>" class="navbar-brand">
				<small>
					<i class="fa fa-envelope"></i>
					E-mail Marketing 
				</small>
			</a>

			<button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
				<span class="sr-only">Toggle user menu</span>

				<img src="<?php echo $daiUrl;?>assets/images/avatars/user.jpg" alt="Profile Photo" />
			</button>

			<button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidebar">
				<span class="sr-only">Toggle sidebar</span>

				<span class="icon-bar"></span>

				<span class="icon-bar"></span>

				<span class="icon-bar"></span>
			</button>
		</div>

		<div class="navbar-buttons navbar-header pull-right  collapse navbar-collapse" role="navigation" >
			<ul class="nav ace-nav" >
				
				<li class="light-blue dropdown-modal user-min">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<img class="nav-user-photo" src="<?php echo $daiUrl;?>assets/images/avatars/user.jpg" alt="Jason's Photo" />
						<span class="user-info">
							<small>Welcome,</small>
							<?php echo $_SESSION['username']; ?>
						</span>

						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						
						<li>
							<a href="<?php echo $mainUrl.'dashboard.php'; ?>">
								<i class="ace-icon fa fa-home"></i>
								Home
							</a>
						</li>

						<li class="divider"></li>

						<li>
							<a href="<?php echo $mainUrl.'logout.php'; ?>">
								<i class="ace-icon fa fa-power-off"></i>
								Logout
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>		
	</div><!-- /.navbar-container -->
</div>

		




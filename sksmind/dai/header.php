<?php //include('../variable.php');

?>
<div id="navbar" class="navbar navbar-default    navbar-collapse       h-navbar ace-save-state">
	<div class="navbar-container ace-save-state" id="navbar-container">
		<div class="navbar-header pull-left">
			
			<a href="<?php echo $daiUrl;?>" class="navbar-brand">
				<small>
					<i class="fa fa-diamond"></i>
					<?php echo $_SESSION['companyname']; ?>
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
				<?php if(in_array('all',$userResource) || in_array('account',$userResource)) : ?>	
				<li class="transparent ">
					<a  href="<?php echo $mainUrl.'dai/account/'; ?>">
						<i class="ace-icon fa fa-money icon-animated-bell"></i>
						Accounting &nbsp;&nbsp;&nbsp;
					</a>
				</li>
				<?php endif;?>
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
						
						<!-- <li>
							<a href="#">
								<i class="ace-icon fa fa-cog"></i>
								Settings
							</a>
						</li>

						<li>
							<a href="profile.html">
								<i class="ace-icon fa fa-user"></i>
								Profile
							</a>
						</li> -->

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

		<nav role="navigation" class="navbar-menu pull-left collapse navbar-collapse">
			<ul class="nav navbar-nav">				
				<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
				<li>
					<a href="<?php echo $daiUrl;?>module/inventory/">
						<i class="ace-icon fa fa-eye bigger-120 white"></i>
						Inventory
					</a>
				</li>
				<?php endif;?>
				<?php if(in_array('all',$userResource) || in_array('gia',$userResource)) : ?>
				<li>
					<a href="<?php echo $daiUrl;?>module/inventory/gia.php">
						<i class="ace-icon fa fa-certificate bigger-120 white"></i>
						GIA-Memo
					</a>
				</li>
				<?php endif; ?>
				
					<?php if(in_array('all',$userResource) || in_array('i_memo',$userResource)) : ?>
				<li>
					<a href="<?php echo $daiUrl;?>module/inventory/inmemo.php">
						<i class="ace-icon fa fa-user bigger-120 white"></i>
						In Memo
					</a>
				</li>
				<?php endif;?>
				
				<?php if(in_array('all',$userResource) || in_array('o_memo',$userResource)) : ?>
				<li>
					<a href="<?php echo $daiUrl;?>module/inventory/memo.php">
						<i class="ace-icon fa fa-user bigger-120 white"></i>
						Out Memo
					</a>
				</li>
				<?php endif;?>
				<?php if(in_array('all',$userResource) || in_array('sale',$userResource)) : ?>
				<li>
					<a href="<?php echo $daiUrl;?>module/inventory/sale.php">
						<i class="ace-icon fa fa-tag bigger-120 white"></i>
						Sale
					</a>
				</li>
				<?php endif;?>
				
				
				<?php if(in_array('all',$userResource) || in_array('purchase',$userResource)) : ?>
				<li>
					<a href="<?php echo $daiUrl;?>module/inventory/purchase.php">
						<i class="ace-icon fa fa-check-square-o  bigger-120 white"></i>
						Purchase
					</a>
				</li>
				<?php endif;?>
			
				<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
				<li>
					<a href="<?php echo $daiUrl;?>module/inventory/stock.php">
						<i class="ace-icon fa fa-eye bigger-120 white"></i>
						Stocktaking
					</a>
				</li>
				<?php endif;?>
			</ul>
		</nav>
	</div><!-- /.navbar-container -->
</div>

		




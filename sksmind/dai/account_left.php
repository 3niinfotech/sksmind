<?php 
//include('../variable.php')

$url =  $_SERVER['REQUEST_URI'];
 
$master = (strpos($url,'attribute') !== false || strpos($url,'party') !== false)?'active':'';
$inventory = (strpos($url,'inventory') !== false || strpos($url,'/single/') !== false || strpos($url,'/box/') !== false || strpos($url,'/parcel/') !== false)?'active':'';
$transaction = (strpos($url,'/inward/') !== false || strpos($url,'/outward/') !== false || strpos($url,'/transfer/') !== false)?'active':'';

?>
<div id="sidebar" class="sidebar      h-sidebar                navbar-collapse collapse          ace-save-state">
<script type="text/javascript">
	try{ace.settings.loadState('sidebar')}catch(e){}
</script>

<?php //include("setting.php");?>
<ul class="nav nav-list">

	<li class="hover">
		<a href="<?php echo $daiUrl.'account/';?>">
			<i class="menu-icon fa fa-tachometer"></i>
			<span class="menu-text"> Dashboard </span>
		</a>

		<b class="arrow"></b>
	</li>

	<li class="<?php echo  $master; ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-desktop"></i>
			<span class="menu-text">
				Master
			</span>

			<b class="arrow fa fa-angle-down"></b>
		</a>

		<b class="arrow"></b>

		<ul class="submenu">
			<?php if(in_array('all',$userResource) || in_array('group',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>account/group/">
					<i class="menu-icon fa fa-caret-right"></i>
					Group					
				</a>				
			</li>
			<?php endif; ?>	
		<?php if(in_array('all',$userResource) || in_array('subgroup',$userResource)) : ?>			
			<li class="hover">
				<a href="<?php echo $daiUrl;?>account/subgroup/">
					<i class="menu-icon fa fa-caret-right"></i>
					Sub Group					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('a_party',$userResource)) : ?>
			<li class="hover">				
				<a href="<?php echo $daiUrl;?>account/party/">
					<i class="menu-icon fa fa-caret-right"></i>
					Party					
				</a>				
			</li>
			<?php endif;?>
		</ul>
	</li>
	<?php if(in_array('all',$userResource) || in_array('expense',$userResource)) : ?>
	<li class="<?php echo  $inventory; ?> hover">
		
		
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-gamepad"></i>
			<span class="menu-text"> Expense </span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<b class="arrow fa fa-angle-down"></b>
		<ul class="submenu">				
			<li class="hover">
				<a href="<?php echo $daiUrl;?>account/expense/index.php?pg=form" class="">
					<i class="menu-icon fa fa-caret-right"></i>
					Expense					
				</a>				
			</li>	
			<li class="hover">
				<a href="<?php echo $daiUrl;?>account/advance/index.php?pg=form" class="">
					<i class="menu-icon fa fa-caret-right"></i>
					Advance					
				</a>				
			</li>		
		</ul>				
	</li>
	<?php endif;?>
	<?php if(in_array('all',$userResource) || in_array('mybalance',$userResource)) : ?>
	<li class="<?php echo  $inventory; ?> hover">
		<a href="<?php echo $daiUrl;?>/account/balance/">
			<i class="menu-icon fa  fa-money"></i>
			<span class="menu-text"> My Balance </span>

			<b class="arrow fa fa-angle-down"></b>
		</a>		
	</li>
	<?php endif;?>
	<?php if(in_array('all',$userResource) || in_array('a_report',$userResource)) : ?>
	<li class="hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-bar-chart"></i>
			<span class="menu-text"> Reports </span>
			<b class="arrow fa fa-angle-down"></b>
		</a>

		<b class="arrow"></b>
		
		<ul class="submenu">
			
			<li class="hover">
				<a href="<?php echo $daiUrl;?>account/report/index.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Transaction					
				</a>				
			</li>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>account/report/party.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Party					
				</a>				
			</li>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>account/report/advance.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Advance Payment					
				</a>				
			</li>
		</ul>
		
	</li>
	<?php endif;?>
	
	
</ul><!-- /.nav-list -->

</div>

<style>
.sidebar.h-sidebar .nav-list > li > a > .menu-icon { 
float: left; 
}
.no-display{display:none;}
</style>			

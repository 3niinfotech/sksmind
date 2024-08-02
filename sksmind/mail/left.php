<?php 
//include('../variable.php')

$url =  $_SERVER['REQUEST_URI'];
 
$master = (strpos($url,'attribute') !== false || strpos($url,'party') !== false)?'active':'';
$inventory = (strpos($url,'inventory') !== false || strpos($url,'/single/') !== false || strpos($url,'/box/') !== false || strpos($url,'/parcel/') !== false)?'active':'';
$transaction = (strpos($url,'/inward/') !== false || strpos($url,'/outward/') !== false || strpos($url,'/transfer/') !== false)?'active':'';

$jewelry = (strpos($url,'jewelry') !== false )?'active':'';
?>
<div id="sidebar" class="sidebar      h-sidebar                navbar-collapse collapse          ace-save-state">
<script type="text/javascript">
	try{ace.settings.loadState('sidebar')}catch(e){}
</script>

<?php //include("setting.php");?>
<ul class="nav nav-list">
	
	<li class="hover">
		<a href="<?php echo $daiUrl;?>">
			<i class="menu-icon fa fa-tachometer"></i>
			<span class="menu-text"> Dashboard </span>
		</a>

		<b class="arrow"></b>
	</li>
	<?php if(in_array('all',$userResource) || in_array('party',$userResource) || in_array('attribute',$userResource) || in_array('bulk',$userResource) || in_array('import',$userResource)) : ?>
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
			<?php if(in_array('all',$userResource) || in_array('party',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $emsUrl;?>module/customer/">
					<i class="menu-icon fa fa-caret-right"></i>
					Customer					
				</a>				
			</li>	
			<?php endif;?>	
			<?php if(in_array('all',$userResource) || in_array('attribute',$userResource)) : ?><li class="hover">
				<a href="<?php echo $daiUrl;?>module/attribute/">
					<i class="menu-icon fa fa-caret-right"></i>
					Category					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
			<li class="hover">				
				<a href="<?php echo $daiUrl;?>importFormat.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Country					
				</a>				
			</li>
			<?php endif;?>
			
		</ul>
	</li>
	<?php endif;?>
	
	<?php if(in_array('all',$userResource) || in_array('inventory',$userResource) || in_array('single',$userResource) || in_array('box',$userResource) || in_array('parcel',$userResource) || in_array('package',$userResource)) : ?>
	<li class="<?php echo  $inventory; ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-envelope"></i>
			<span class="menu-text"> Email </span>

			<b class="arrow fa fa-angle-down"></b>
		</a>

		<b class="arrow"></b>

		<ul class="submenu">
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/inventory/">
					<i class="menu-icon fa fa-caret-right"></i>
					Template					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/inventory/">
					<i class="menu-icon fa fa-caret-right"></i>
					Quick Mail					
				</a>				
			</li>
			<?php endif;?>
		</ul>
	</li>
	<?php endif;?>
	<?php if(in_array('all',$userResource) || in_array('export',$userResource) || in_array('purchase',$userResource) || in_array('sale',$userResource) || in_array('lab',$userResource) || in_array('memo',$userResource) || in_array('i_memo',$userResource) ||  in_array('o_memo',$userResource)||  in_array('import',$userResource)) : ?>
	
	<li class="<?php echo $transaction ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-clock-o"></i>
			<span class="menu-text"> Automation </span>

			<b class="arrow fa fa-angle-down"></b>
		</a>

		<b class="arrow"></b>

		<ul class="submenu">
			<li class="hover">
				<a href="javascript:void(0);" >
					<i class="menu-icon fa fa-caret-right"></i>
					Tracking
				</a>
				<b class="arrow"></b>				
			</li>			
			<li class="hover">
				<a href="javascript:void(0);">
					<i class="menu-icon fa fa-caret-right"></i>
					Schedules
				</a>
				<b class="arrow"></b>				
			</li>			
		</ul>
	</li>
	<?php endif; ?>
	
	<?php if(in_array('all',$userResource) || in_array('export',$userResource) || in_array('purchase',$userResource) || in_array('sale',$userResource) || in_array('lab',$userResource) || in_array('memo',$userResource) || in_array('i_memo',$userResource) ||  in_array('o_memo',$userResource)||  in_array('import',$userResource)) : ?>
	
	<li class="<?php echo $transaction ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-upload"></i>
			<span class="menu-text"> Import / Export </span>

			<b class="arrow fa fa-angle-down"></b>
		</a>

		<b class="arrow"></b>

		<ul class="submenu">
			<li class="hover">
				<a href="javascript:void(0);" >
					<i class="menu-icon fa fa-caret-right"></i>
					Import
				</a>
				<b class="arrow"></b>				
			</li>			
			<li class="hover">
				<a href="javascript:void(0);">
					<i class="menu-icon fa fa-caret-right"></i>
					Export
				</a>
				<b class="arrow"></b>				
			</li>			
		</ul>
	</li>
	<?php endif; ?>
	
	<?php if(in_array('all',$userResource) || in_array('transaction',$userResource) || in_array('stock',$userResource) || in_array('stone',$userResource) || in_array('outstanding',$userResource)) : ?>
	<li class="hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-bar-chart"></i>
			<span class="menu-text"> Reports </span>
			<b class="arrow fa fa-angle-down"></b>
		</a>

		<b class="arrow"></b>
		
		<ul class="submenu">
			<?php if(in_array('all',$userResource) || in_array('transaction',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/report/memo.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Transaction					
				</a>				
			</li>
			<?php endif;?>			
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

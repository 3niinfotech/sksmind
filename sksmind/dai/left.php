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
				<a href="<?php echo $daiUrl;?>module/party/">
					<i class="menu-icon fa fa-caret-right"></i>
					Company					
				</a>				
			</li>	
			<?php endif;?>	
			<?php if(in_array('all',$userResource) || in_array('attribute',$userResource)) : ?><li class="hover">
				<a href="<?php echo $daiUrl;?>module/attribute/">
					<i class="menu-icon fa fa-caret-right"></i>
					Attribute					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
			<li class="hover">				
				<a href="<?php echo $daiUrl;?>importFormat.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Import Formats					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('bulk',$userResource)) : ?>
			<li class="hover">				
				<a href="<?php echo $daiUrl;?>module/bulk/index.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Bulk Update					
				</a>				
			</li>
			<?php endif;?>
			
			<?php if(in_array('all',$userResource) || in_array('rapnet',$userResource)) : ?>
				
				<li class="hover">				
					<a href="<?php echo $daiUrl;?>module/rapnet/rapnetPrice.php">
						<i class="menu-icon fa fa-caret-right"></i>
						Rapnet Price					
					</a>				
				</li>
				<?php endif;?>
		</ul>
	</li>
	<?php endif;?>
	<?php if(in_array('all',$userResource) || in_array('inventory',$userResource) || in_array('single',$userResource) || in_array('box',$userResource) || in_array('parcel',$userResource) || in_array('package',$userResource)) : ?>
	<li class="<?php echo  $inventory; ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-gamepad"></i>
			<span class="menu-text"> Inventory </span>

			<b class="arrow fa fa-angle-down"></b>
		</a>

		<b class="arrow"></b>

		<ul class="submenu">
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/inventory/">
					<i class="menu-icon fa fa-caret-right"></i>
					My Inventory					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('single',$userResource)) : ?>
			<li class="hover">
				<a href="javascript:void(0);" class="dropdown-toggle">
					<i class="menu-icon fa fa-caret-right"></i>
					Single To
				</a>

				<b class="arrow"></b>
				<ul class="submenu">
					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/single/tobox.php">
							<i class="menu-icon fa fa-caret-right"></i>
							Box
						</a>

						<b class="arrow"></b>
					</li>

					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/single/toparcel.php">
							<i class="menu-icon fa fa-caret-right"></i>
							Parcel 
						</a>

						<b class="arrow"></b>
					</li>						
				</ul>	
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('box',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/box/tosingle.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Box To
				</a>

				<b class="arrow"></b>
				<!-- <ul class="submenu">
					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/box/tosingle.php">
							<i class="menu-icon fa fa-caret-right"></i>
							Single
						</a>

						<b class="arrow"></b>
					</li>

					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/box/toparcel.php">
							<i class="menu-icon fa fa-caret-right"></i>
							Parcel 
						</a>

						<b class="arrow"></b>
					</li>						
				</ul> -->	
			</li><?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('parcel',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/parcel/tosingle.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Parcel To
				</a>

				<b class="arrow"></b>
				<!-- <ul class="submenu">
					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/parcel/tosingle.php">
							<i class="menu-icon fa fa-caret-right"></i>
							Single
						</a>

						<b class="arrow"></b>
					</li>

					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/parcel/tobox.php">
							<i class="menu-icon fa fa-caret-right"></i>
							Box 
						</a>

						<b class="arrow"></b>
					</li>						
				</ul>	-->
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('package',$userResource)) : ?>
			<li class="hover">
				<a onClick="loadAddPackage()" href="javascript:void(0);">
					<i class="menu-icon fa fa-caret-right"></i>
					Add To Package 
				</a>
				<b class="arrow"></b>
			</li>
			<?php endif;?>			
		</ul>
	</li>
	<?php endif;?>
	<?php if(in_array('all',$userResource) || in_array('export',$userResource) || in_array('purchase',$userResource) || in_array('sale',$userResource) || in_array('lab',$userResource) || in_array('memo',$userResource) || in_array('i_memo',$userResource) ||  in_array('o_memo',$userResource)||  in_array('import',$userResource)) : ?>
	
	<li class="<?php echo $transaction ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-pencil-square-o"></i>
			<span class="menu-text"> Transaction </span>

			<b class="arrow fa fa-angle-down"></b>
		</a>

		<b class="arrow"></b>

		<ul class="submenu">
			<li class="hover">
				<a href="javascript:void(0);" class="dropdown-toggle">
					<i class="menu-icon fa fa-caret-right"></i>
					Inward
				</a>

				<b class="arrow"></b>
				<ul class="submenu">
					<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/inward/index.php?pg=form&t=import">
							<i class="menu-icon fa fa-caret-right"></i>
							Import
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>
					<?php if(in_array('all',$userResource) || in_array('purchase',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/inward/index.php?pg=form&t=purchase">
							<i class="menu-icon fa fa-caret-right"></i>
							Purchase 
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>
					<?php if(in_array('all',$userResource) || in_array('i_memo',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/inward/index.php?pg=form&t=memo">
							<i class="menu-icon fa fa-caret-right"></i>
							In Memo 
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>
					<?php if(in_array('all',$userResource) || in_array('i_con',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/inward/index.php?pg=form&t=consign">
							<i class="menu-icon fa fa-caret-right"></i>
							In Consignment 
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif;?>
				</ul>	
			</li>			
			<li class="hover">
				<a href="javascript:void(0);" class="dropdown-toggle">
					<i class="menu-icon fa fa-caret-right"></i>
					Outward
				</a>

				<b class="arrow"></b>
				<ul class="submenu">
					<?php if(in_array('all',$userResource) || in_array('export',$userResource)) : ?>
					<li class="hover">
						<a onClick="loadMemoForm(0,'export')" href="javascript:void(0);">
							<i class="menu-icon fa fa-caret-right"></i>
							Export
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>
					<?php if(in_array('all',$userResource) || in_array('sale',$userResource)) : ?>
					<li class="hover">
						<a onClick="loadMemoForm(0,'sale')" href="javascript:void(0);">
							<i class="menu-icon fa fa-caret-right"></i>
							Sale 
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif;?>
					<?php if(in_array('all',$userResource) || in_array('o_memo',$userResource)) : ?>
					<li class="hover">
						<a  onClick="loadMemoForm(0,'memo')" href="javascript:void(0); <?php //echo $daiUrl;?>module/outward/memo.php">
							<i class="menu-icon fa fa-caret-right"></i>
							On Memo 
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif;?>
					<?php if(in_array('all',$userResource) || in_array('lab',$userResource)) : ?>
					<li class="hover">
						<a onClick="loadMemoForm(0,'lab')" href="javascript:void(0);">
							<i class="menu-icon fa fa-caret-right"></i>
							LAB 
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>
					<?php if(in_array('all',$userResource) || in_array('o_con',$userResource)) : ?>
					<li class="hover">
						<a onClick="loadMemoForm(0,'consign')" href="javascript:void(0);">
							<i class="menu-icon fa fa-caret-right"></i>
							Consignment
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>
				</ul>	
			</li>
			
			<?php  if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/inventory/stonedetail.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Stone Update 
				</a>
				<b class="arrow"></b>
			</li>
			<?php endif; ?>
			<?php /* if(in_array('all',$userResource) || in_array('i_memo',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/inventory/inmemo.php">
					<i class="menu-icon fa fa-caret-right"></i>
					In Memo 
				</a>
				<b class="arrow"></b>
			</li>
			<?php endif; */?>
		<!--	<li class="hover">
				<a href="javascript:void(0);" class="dropdown-toggle">
					<i class="menu-icon fa fa-caret-right"></i>
					Branch Transfer
				</a>

				<b class="arrow"></b>
				<ul class="submenu">
					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/branch/inward.php">
							<i class="menu-icon fa fa-caret-right"></i>
							Inward
						</a>

						<b class="arrow"></b>
					</li>

					<li class="hover">
						<a href="<?php echo $daiUrl;?>module/branch/outward.php">
							<i class="menu-icon fa fa-caret-right"></i>
							Outward 
						</a>

						<b class="arrow"></b>
					</li>
				</ul>	
			</li> -->
		</ul>
	</li>
	<?php endif; ?>
	<?php if(in_array('all',$userResource) || in_array('inventory',$userResource) || in_array('single',$userResource) || in_array('box',$userResource) || in_array('parcel',$userResource) || in_array('package',$userResource)) : ?>
	<li class="<?php echo  $jewelry; ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-chain"></i>
			<span class="menu-text"> Jewelry </span>

			<b class="arrow fa fa-angle-down"></b>
		</a>

		<b class="arrow"></b>

		<ul class="submenu">
			
			
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/jewelry/">
					<i class="menu-icon fa fa-caret-right"></i>
					My Jewelry					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('single',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/jewelry/addNew.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Add New
				</a>

				<b class="arrow"></b>				
			</li>
			<?php endif;?>
		</ul>
	</li>
	<?php endif;?>
	
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
			<?php endif; ?>
			<?php if(in_array('all',$userResource) || in_array('stock',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/report/stock.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Stock Report					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('stone',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/report/stonedetail.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Stone Detail					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('outstanding',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>module/report/outstanding.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Outstanding					
				</a>				
			</li>
			<?php endif; ?>
		</ul>		
	</li>
	<?php endif;?>
	<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
	<li class="hover header-li" id="fancy-search" style="display:none">
		<a href="javascript:void(0);">			
			<span class="menu-text"> Fancy Search </span>
		</a>
		<b class="arrow"></b>
	</li>
	<li class="hover header-li" id="white-search" style="display:none">
		<a href="javascript:void(0);">			
			<span class="menu-text"> White Search </span>
		</a>
		<b class="arrow"></b>
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

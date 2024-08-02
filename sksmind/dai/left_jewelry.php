<?php 
//include('../variable.php')

$url =  $_SERVER['REQUEST_URI'];
 
$master = (strpos($url,'attribute') !== false || strpos($url,'party') !== false)?'active':'';
$inventory = (strpos($url,'inventory') !== false || strpos($url,'/single/') !== false || strpos($url,'/box/') !== false || strpos($url,'/parcel/') !== false)?'active':'';
$transaction = (strpos($url,'/inward/') !== false || strpos($url,'/outward/') !== false || strpos($url,'/transfer/') !== false)?'active':'';

$jewelry = (strpos($url,'jewelry') !== false )?'active':'';

$master = '';
$inventory = '';
$transaction = '';

$jewelry = '';
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
			<span class="menu-text">Dashboard </span>
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
				<a href="<?php echo $daiUrl;?>/jewelry/party/">
					<i class="menu-icon fa fa-caret-right"></i>
					Party					
				</a>				
			</li>	
			<?php endif;?>	
			<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
			<li class="hover">				
				<a href="<?php echo $jewelryUrl;?>/importformat/index.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Import Formats					
				</a>				
			</li>
			<?php endif;?>
			<li class="hover">				
				<a href="<?php echo $daiUrl;?>jewelry/jtype/">
					<i class="menu-icon fa fa-caret-right"></i>
					Jewelry Design
				</a>				
			</li>
			<li class="hover">				
				<a href="<?php echo $daiUrl;?>jewelry/design/">
					<i class="menu-icon fa fa-caret-right"></i>
					Stone Color
				</a>				
			</li>
			<li class="hover">				
				<a href="<?php echo $daiUrl;?>jewelry/clarity/">
					<i class="menu-icon fa fa-caret-right"></i>
					Stone Clarity
				</a>				
			</li>
			<li class="hover">				
				<a href="<?php echo $daiUrl;?>jewelry/mmaker/">
					<i class="menu-icon fa fa-caret-right"></i>
					Mamo Maker
				</a>				
			</li>	
			<li class="hover">				
				<a href="<?php echo $daiUrl;?>jewelry/attribute/">
					<i class="menu-icon fa fa-caret-right"></i>
					Jewelry Attributes
				</a>				
			</li>		
		</ul>
	</li>
	<?php endif;?>
	<?php if(in_array('all',$userResource) || in_array('export',$userResource) || in_array('purchase',$userResource) || in_array('sale',$userResource) || in_array('lab',$userResource) || in_array('memo',$userResource) || in_array('i_memo',$userResource) ||  in_array('o_memo',$userResource)||  in_array('import',$userResource)) : ?>
	
	<li class="<?php echo $transaction ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-upload"></i>
			<span class="menu-text"> Import </span>

			<b class="arrow fa fa-angle-down"></b>
		</a>

		<b class="arrow"></b>

		<ul class="submenu">
			<li class="hover">
				<a href="javascript:void(0);" class="dropdown-toggle">
					<i class="menu-icon fa fa-caret-right"></i>
					Main Pizza Stone
				</a>

				<b class="arrow"></b>
				<ul class="submenu">
					<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>jewelry/import/stone.php?pg=form&t=import">
								<i class="menu-icon fa fa-caret-right"></i>
							Import
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>
					<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>jewelry/import/stone.php?pg=form&t=purchase">
							<i class="menu-icon fa fa-caret-right"></i>
							Purchase
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>	
						
				</ul>	
			</li>			
			<li class="hover">
				<a href="javascript:void(0);" class="dropdown-toggle">
					<i class="menu-icon fa fa-caret-right"></i>
					C³ Collet
				</a>

				<b class="arrow"></b>
				<ul class="submenu">
					<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>jewelry/import/collet.php?pg=form&t=import">
								<i class="menu-icon fa fa-caret-right"></i>
							Import
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>
					<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>jewelry/import/collet.php?pg=form&t=purchase">
							<i class="menu-icon fa fa-caret-right"></i>
							Purchase
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>	
						
				</ul>	
			</li>	
			<li class="hover">
				<a href="javascript:void(0);" class="dropdown-toggle">
					<i class="menu-icon fa fa-caret-right"></i>
					Side Stones
				</a>

				<b class="arrow"></b>
				<ul class="submenu">
					<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>jewelry/import/loose.php?pg=form&t=import">
							<i class="menu-icon fa fa-caret-right"></i>
							Import
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>

						<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>jewelry/import/loose.php?pg=form&t=purchase">
							<i class="menu-icon fa fa-caret-right"></i>
							Purchase
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>	
				</ul>	
			</li>
			
			
			
			<li class="hover">
				<a href="javascript:void(0);" class="dropdown-toggle">
					<i class="menu-icon fa fa-caret-right"></i>
					Metal
				</a>

				<b class="arrow"></b>
				<ul class="submenu">
					<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>jewelry/inward/index.php?pg=form&t=import">
							<i class="menu-icon fa fa-caret-right"></i>
							Import
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>

						<?php if(in_array('all',$userResource) || in_array('import',$userResource)) : ?>
					<li class="hover">
						<a href="<?php echo $daiUrl;?>jewelry/inward/index.php?pg=form&t=import">
							<i class="menu-icon fa fa-caret-right"></i>
							Purchase
						</a>

						<b class="arrow"></b>
					</li>
					<?php endif; ?>	
				</ul>	
			</li>
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/jewelry/addNew.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Jewelry Purchase <!-- Add New Jewelry -->					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/all.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Create Task 
				</a>				
			</li>
			<?php endif;?>
			
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="#">
					<i class="menu-icon fa fa-caret-right"></i>
					Purchase Return
				</a>				
			</li>
			<?php endif;?>
			
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="#">
					<i class="menu-icon fa fa-caret-right"></i>
					Create Quotation
				</a>				
			</li>
			<?php endif;?>
			
		</ul>
	</li>
	<?php endif; ?>
	
	
	<?php if(in_array('all',$userResource) || in_array('inventory',$userResource) || in_array('single',$userResource) || in_array('box',$userResource) || in_array('parcel',$userResource) || in_array('package',$userResource)) : ?>
	<li class="<?php echo  $jewelry; ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-chain"></i>
			<span class="menu-text"> Job Work </span>

			<b class="arrow fa fa-angle-down"></b>
		</a>
		<b class="arrow"></b>
		<ul class="submenu">
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/create_job.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Create Job				
				</a>				
			</li>
			<?php endif;?>	
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/jewelryMaking.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Job in Process					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('single',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/colletRepairing.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Repair in Process
				</a>

				<b class="arrow"></b>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('single',$userResource)) : ?>
			<li class="hover">
				<a href="#">
					<i class="menu-icon fa fa-caret-right"></i>
					Complete Jobs
				</a>

				<b class="arrow"></b>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('single',$userResource)) : ?>
			<li class="hover">
				<a href="#">
					<i class="menu-icon fa fa-caret-right"></i>
					Complete Repairs
				</a>

				<b class="arrow"></b>				
			</li>
			<?php endif;?>
		</ul>
	</li>
	<?php endif;?>
	
	
	<?php if(in_array('all',$userResource) || in_array('inventory',$userResource) || in_array('single',$userResource) || in_array('box',$userResource) || in_array('parcel',$userResource) || in_array('package',$userResource)) : ?>
	<li class="<?php echo  $jewelry; ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon  fa fa-certificate"></i>
			<span class="menu-text">Lab</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<b class="arrow"></b>
		<ul class="submenu">
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/lab.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Stone				
				</a>				
			</li>
			<?php endif;?>	
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/jewelry/lab.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Jewelry					
				</a>				
			</li>
			<?php endif;?>		
		</ul>
	</li>
	<?php endif;?>
	
	<?php if(in_array('all',$userResource) || in_array('inventory',$userResource) || in_array('single',$userResource) || in_array('box',$userResource) || in_array('parcel',$userResource) || in_array('package',$userResource)) : ?>
	<li class="<?php echo  $jewelry; ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-chain"></i>
			<span class="menu-text">Consignment</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<b class="arrow"></b>
		<ul class="submenu">
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/consignment.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Stone				
				</a>				
			</li>
			<?php endif;?>	
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/lconsignment.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Jewelry					
				</a>				
			</li>
			<?php endif;?>		
		</ul>
	</li>
	<?php endif;?>
	
	<?php if(in_array('all',$userResource) || in_array('inventory',$userResource) || in_array('single',$userResource) || in_array('box',$userResource) || in_array('parcel',$userResource) || in_array('package',$userResource)) : ?>
	<li class="<?php echo  $jewelry; ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-dollar"></i>
			<span class="menu-text">Sales</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<b class="arrow"></b>
		<ul class="submenu">
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/lsale.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Stone				
				</a>				
			</li>
			<?php endif;?>	
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/sale.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Jewelry					
				</a>				
			</li>
			<?php endif;?>		
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="#">
					<i class="menu-icon fa fa-caret-right"></i>
					Sales Return					
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
				<a href="<?php echo $daiUrl;?>jewelry/inventory/">
					<i class="menu-icon fa fa-caret-right"></i>
					Main Pizza Stone					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/collet.php">
					<i class="menu-icon fa fa-caret-right"></i>
					C³ Collet					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/loose.php/">
					<i class="menu-icon fa fa-caret-right"></i>
					Side Stones					
				</a>				
			</li>
			<?php endif;?>
			
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/">
					<i class="menu-icon fa fa-caret-right"></i>
					Metal					
				</a>				
			</li>
			<?php endif;?>	
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/jewelry/list.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Jewelry List
				</a>				
			</li>
			<?php endif;?>	
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/jewelry/index.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Detailed Jewelry Data					
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
			<span class="menu-text">Data Look Up</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<b class="arrow"></b>
		<ul class="submenu">
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/report/mainHistory.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Main Pizza Stone History					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="#">
					<i class="menu-icon fa fa-caret-right"></i>
					C³ Collet History					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/report/sideHistory.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Side Stones History					
				</a>				
			</li>
			<?php endif;?>	
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="#">
					<i class="menu-icon fa fa-caret-right"></i>
					Metal History				
				</a>				
			</li>
			<?php endif;?>		
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/report/jewelryHistory.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Jewelry History					
				</a>				
			</li>
			<?php endif;?>			
		</ul>
	</li>
	<?php endif;?>			
	
	<?php if(in_array('all',$userResource) || in_array('inventory',$userResource) || in_array('single',$userResource) || in_array('box',$userResource) || in_array('parcel',$userResource) || in_array('package',$userResource)) : ?>
	<li class="<?php echo  $jewelry; ?> hover">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-chain"></i>
			<span class="menu-text"> Update </span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<b class="arrow"></b>
		<ul class="submenu">
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/stonedetail.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Main Pizza Stone Update					
				</a>				
			</li>
			<li class="hover">
				<a href="#">
					<i class="menu-icon fa fa-caret-right"></i>
					C³ Collet Update				
				</a>				
			</li>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/inventory/loosedetail.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Side Stones Update					
				</a>				
			</li>
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">				
				<a href="#">
					<i class="menu-icon fa fa-caret-right"></i>
					Gold Update	</a>				
			</li>
			<?php endif;?>			
			
			<li class="hover">
				<a href="#">
					<i class="menu-icon fa fa-caret-right"></i>
					Jewelry Update					
				</a>				
			</li>
			<?php if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
			<li class="hover">				
				<a href="<?php echo $daiUrl;?>jewelry/bulk/">
					<i class="menu-icon fa fa-caret-right"></i>
					Bulk Update				</a>				
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
				<a href="<?php echo $daiUrl;?>jewelry/report/memo.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Stone Transaction					
				</a>				
			</li>

			<?php endif; ?>
			<?php if(in_array('all',$userResource) || in_array('stock',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/report/jewmemo.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Jewelry Transaction					
				</a>				
			</li>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/report/transfer.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Stone Transfer					
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('stone',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/report/jewoutstanding.php">
					<i class="menu-icon fa fa-caret-right"></i>
					 Jewelry Outstanding			
				</a>				
			</li>
			<?php endif;?>
			<?php if(in_array('all',$userResource) || in_array('outstanding',$userResource)) : ?>
			<li class="hover">
				<a href="<?php echo $daiUrl;?>jewelry/report/outstanding.php">
					<i class="menu-icon fa fa-caret-right"></i>
					Stone Outstanding					
				</a>				
			</li>
			<?php endif; ?>
		</ul>		
	</li>
	<?php endif;?>
	<?php /* if(in_array('all',$userResource) || in_array('inventory',$userResource)) : ?>
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
	<?php endif; */?>
	
</ul><!-- /.nav-list -->

</div>

<style>
.sidebar.h-sidebar .nav-list > li > a > .menu-icon { 
float: left; 
}
.no-display{display:none;}
</style>
<!-- Success Message --> 
<?php if (isset($_SESSION['success'])){ ?>
	
	<div class="alert alert-block alert-success">
	<button type="button" class="close" data-dismiss="alert">
		<i class="ace-icon fa fa-times"></i>
	</button>

	<i class="ace-icon fa fa-check green"></i>	
	<?php echo $_SESSION['success']?>									
	
</div>	
	
<?php	unset($_SESSION['success']); }?>	

<!-- Error Message --> 

<?php if (isset($_SESSION['error'])){ ?>
	<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert">
		<i class="ace-icon fa fa-times"></i>
	</button>

	<strong>
		<i class="ace-icon fa fa-times"></i>		
	</strong>
	<?php echo $_SESSION['error']?>	
	
</div>
<?php 	unset($_SESSION['error']); } ?>
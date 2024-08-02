<?php
if (isset($_SESSION['success']))
{ ?>
	<div class = "success_div"><?php echo $_SESSION['success']; ?></div>
<?php
	unset($_SESSION['success']);
}
?>	
<?php
if (isset($_SESSION['error']))
{ ?>
	<div class = "error_div" style = "height:auto"><?php echo $_SESSION['error']; ?></div>
<?php
	unset($_SESSION['error']);
}
?>	

<?php	
include("variable.php"); 
session_start();
?>
<head>
	<title><?php echo $_SESSION['company_name'];?> | Stock Management System</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<link href="<?php echo $mainurl;?>css/style.css" rel="stylesheet" type="text/css">

	<link href="<?php echo $mainurl;?>css/font-awesome.css" rel="stylesheet" type="text/css">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $mainurl;?>/css/footable.bootstrap.min.css" rel="stylesheet">
	<script src="<?php echo $mainurl;?>/js/jquery.min.js"></script>
	<link rel="stylesheet" href="<?php echo $mainurl;?>/css/jquery-ui.css">
	<script src="<?php echo $mainurl;?>/js/jquery-ui.js"></script>
	
	<script src="<?php echo $mainurl;?>/js/bootstrap.min.js"></script>
	<script src="<?php echo $mainurl;?>/js/ie10-viewport-bug-workaround.js"></script>
	<script src="<?php echo $mainurl;?>/js/moment.min.js"></script>
	<script src="<?php echo $mainurl;?>js/footable.js"></script>
	

	
</head>
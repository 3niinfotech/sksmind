<?php 
session_start();
include("../variable.php");
include_once("../checkResource.php");
include("../database.php");
	
	if (!isset($_SESSION['username']) || !checkCompany($cn,$_GET['cid']))
	{
		header("Location: ".$mainUrl);	
		exit;
	}
	
	
	$rs=mysqli_query($cn,"select * from company where id = ".$_GET['cid']);
			  
	$model = array();
	$index = 0;
	while ($company_list =  mysqli_fetch_assoc($rs))
	{
		//define('CN', $company_list['name']);
		unset($_SESSION['companyname']);
		unset($_SESSION['companyId']);
		$_SESSION['companyId'] = $_GET['cid'];
		$_SESSION['companyname'] = $company_list['name'];
		//session_write_close();
		break;
	}
	header('Location: ' . $daiUrl);
?>	
	
<?php
ini_set('error_reporting', 1); 
ini_set("display_errors",1);
session_start();
include_once ('database.php');
include_once ('variable.php'); 
include_once($daiDir.'Helper.php'); 
include_once($daiDir.'module/rapnet/rapModel.php');
//echo $daiDir;


try
{
	$model = new rapModel($cn);		
	$rs = $model->rapnetPrice($cn);
}
catch(Exception $e)
{
	echo $e->getMessage();
}
?>

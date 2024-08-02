<?php
//include('../../../../../variable.php');

include('../../../database.php');
include('rapnetModel.php');
session_start();


if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{
	$_POST['fn']();
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	
	$_GET['fn']();
}



function rapnetPrice()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	$model = new rapnetModel();		
	$rs = $model->rapnetPrice();
	
	if($rs)
	{
		$error = 0;
		$message = "Successfully Update Rapnet Price !!!";
		$response['id'] = $rs;
	}
	else
	{
		$error = 1;
		$message = $rs;
	}	
	
	if ($error == 0)		
		$response['status'] = 1; 
	else
		$response['status'] = 0;
		
	
	$response['message'] = $message;
	echo json_encode($response);
	//header('Location: ' . $_SERVER['HTTP_REFERER']);
}
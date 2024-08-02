<?php
//include('../../../../../variable.php');

include('../../../database.php');
include('designModel.php');
session_start();
global $cn;

if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{
	$_POST['fn']($cn);
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	
	$_GET['fn']($cn);
}

function saveGroup($cn)
{
	$error =0;
	$message = "";
	
	if(empty($_POST["name"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new designModel($cn);
		
		if(isset($_POST["id"]) && empty($_POST["id"]))
		{
			$rs = $model->saveData($_POST);
		}
		else
		{
			$rs = $model->updateData($_POST);
		}		
		
		if($rs)
		{
			$error = 0;
			$message = "Successfully Saved !!!";
		}
		else
		{
			$error = 1;
			$message = mysql_error($cn);
		}		
		
	}
	
	
	if ($error == 0) 	
		$_SESSION['success']= $message;		 
	else
		$_SESSION['error'] = $message;	
		
	
	//echo $_SESSION['error'];
	header('Location: ' . $_SERVER['HTTP_REFERER']);	
}

function loadPrice($cn)
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if(empty($_POST["id"]) )
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new designModel($cn);
		
		$rs = $model->loadPrice($_POST['id']);
		
		if($rs)
		{
			$error = 0;
			$response['price'] = $rs;
		}
		else
		{
			$error = 1;
			$message = "Design Not Found.";
		}		
		
	}
	
	
	if ($error == 0)		
		$response['status'] = 1; 
	else
		$response['status'] = 0;
		
	
	$response['message'] = $message;
	echo json_encode($response);
	//header('Location: ' . $_SERVER['HTTP_REFERER']);
}
function delete($cn)
{
	$error =0;
	$message = "";
	
	if(empty($_GET["id"]) )
	{
		$message = "Record Can't found.";
		$error = 1;
	}	
	else	
	{	
		$model = new designModel($cn);	
		$rs = $model->delete($_GET['id']);
		if($rs)
		{
			$error = 0;
			$message = "Delete Record Successfully !!!";
		}
		else
		{
			$error = 1;
			$message = mysql_error($cn);
		}		
		
	}
	
	
	if ($error == 0) 	
		$_SESSION['success']= $message;		 
	else
		$_SESSION['error'] = $message;	
		
	
	//echo $_SESSION['error'];
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
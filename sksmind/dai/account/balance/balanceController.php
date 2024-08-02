<?php
ob_start();
session_start();
//include('../../../../../variable.php');

include('../../../database.php');
include('balanceModel.php');
global $cn;

if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{
	$_POST['fn']($cn);
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	
	$_GET['fn']($cn);
}

function saveCurrency($cn)
{
	$error =0;
	$message = "";	
	$model = new balanceModel($cn);		
	$rs = $model->saveCurrency($_POST);		
	if($rs==1)
	{
		$error = 0;
		$message = "Successfully Saved !!!";
	}
	else
	{
		$error = 1;
		$message = $rs;
	}				
	
	if ($error == 0) 	
		$_SESSION['success']= $message;		 
	else
		$_SESSION['error'] = $message;	
		
	header('Location: ' . $_SERVER['HTTP_REFERER']);	
}

function saveBook($cn)
{
	$error =0;
	$message = "";	
	$model = new balanceModel($cn);
		
	$rs = $model->saveBook($_POST);		
	if($rs == 1)
	{
		$error = 0;
		$message = "Successfully Saved !!!";
	}
	else
	{
		$error = 1;
		$message = $rs;
	}				
	
	if ($error == 0) 	
		$_SESSION['success']= $message;		 
	else
		$_SESSION['error'] = $message;	
		
	header('Location: ' . $_SERVER['HTTP_REFERER']);	
}
function bankTransfer($cn)
{
	$error =0;
	$message = "";	
	$model = new balanceModel($cn);
	if(empty($_POST['frombook']) || empty($_POST['tobook']) || empty($_POST['amount']) || empty($_POST['rate']))	
	{
		$error = 1;
		$message = 'Value Can not Blank';
	}
	else
	{
		$rs = $model->bankTransfer($_POST);		
		if($rs == 1)
		{
			$error = 0;
			$message = "Successfully Saved !!!";
		}
		else
		{
			$error = 1;
			$message = $rs;
		}
	}
	
	if ($error == 0) 	
		$_SESSION['success']= $message;		 
	else
		$_SESSION['error'] = $message;	
		
	header('Location: ' . $_SERVER['HTTP_REFERER']);	
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
		$model = new balanceModel($cn);	
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
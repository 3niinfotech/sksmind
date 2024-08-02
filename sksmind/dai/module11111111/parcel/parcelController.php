<?php
//include('../../../../../variable.php');

include('../../../database.php');
include('parcelModel.php');
session_start();

if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{
	$_POST['fn']();
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	
	$_GET['fn']();
}

function boxToParcel()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	if(empty($_POST["products"]) || (($_POST["type"])=='existing' && empty($_POST["boxcode"])) || (($_POST["type"])=='new' && empty($_POST["newbox"])) )
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new parcelModel();
		$rs = $model->boxToParcel($_POST);			
		if($rs == 1 )
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
		$response['status'] = 1; 
	else
		$response['status'] = 0;	
		
	
	$response['message'] = $message;
	echo json_encode($response);
}

function toSingle()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	if(empty($_POST["products"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new parcelModel();
		$rs = $model->toSingle($_POST);			
		if($rs == 1 )
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
		$response['status'] = 1; 
	else
		$response['status'] = 0;	
		
	
	$response['message'] = $message;
	echo json_encode($response);	
	//header('Location: ' . $_SERVER['HTTP_REFERER']);	
}



function delete()
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
		$model = new parcelModel();	
		$rs = $model->delete($_GET['id'],$_GET['eid']);
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

function getDetail()
{
	$model = new parcelModel();	
	$rs = $model->getDetail($_POST['id']);
	echo json_encode($rs);	
}
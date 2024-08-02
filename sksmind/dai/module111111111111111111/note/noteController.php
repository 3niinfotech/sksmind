<?php
//include('../../../../../variable.php');

include('../../../database.php');
require_once('../../Helper.php');
include('noteModel.php');

session_start();

if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{
	$_POST['fn']();
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	
	$_GET['fn']();
}



function changeNote()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if( empty($_POST["id"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new noteModel();
		
		$rs = $model->changeNote($_POST);
		
		if($rs == 1)
		{
			$error = 0;
			$message = "Successfully Updated !!!";			
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

function changeStatus()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if( empty($_POST["id"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new noteModel();
		
		$rs = $model->changeStatus($_POST);
		
		if($rs == 1)
		{
			$error = 0;
			$message = "Successfully Updated !!!";			
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

function addNewNote()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if( empty($_POST["content"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new noteModel();
		
		$rs = $model->addNewNote($_POST);
		
		if($rs == 1)
		{
			$error = 0;
			$message = "Successfully Updated !!!";			
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



function deleteNote()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if( empty($_POST["id"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new noteModel();
		
		$rs = $model->deleteNote($_POST);
		
		if($rs == 1)
		{
			$error = 0;
			$message = "Successfully Updated !!!";			
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

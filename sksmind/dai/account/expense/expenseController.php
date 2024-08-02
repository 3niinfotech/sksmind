<?php
//include('../../../../../variable.php');

include('../../../database.php');
include('expenseModel.php');
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

function savePart($cn)
{
	$response['status'] = 1;
	$response['message'] = "";
	$error =0;
	$message = "";	
	if($_POST["amount"]=="" || empty($_POST["party"]) || empty($_POST["book"]) )
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new expenseModel($cn);
		$rs = $model->savePart($_POST);		
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

function savePartJew($cn)
{
	$response['status'] = 1;
	$response['message'] = "";
	$error =0;
	$message = "";	
	if($_POST["amount"]=="" || empty($_POST["party"]) || empty($_POST["book"]) )
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new expenseModel($cn);
		$rs = $model->savePartJew($_POST);		
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

function save($cn)
{
	
		$error =0;
		$message = "";
		
		if($_POST["amount"]=="" || empty($_POST["party"]))
		{
			$message = "Value can't be Blank.";
			$error = 1;
		}	
		else	
		{	
			$model = new expenseModel($cn);
			
			if(isset($_POST["id"]))
			{
				$rs = $model->updateData($_POST);
			}
			else
			{
				$rs = $model->saveData($_POST);
			}		
			
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
			$_SESSION['success']= $message;		 
		else
			$_SESSION['error'] = $message;	
			
		
		//echo $_SESSION['error'];
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	
}

function changeType($cn)
{
	$error =0;
	$message = "";
	
	if($_POST["type"]=="")
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new expenseModel($cn);
		
		if(isset($_POST["id"]))
		{
			$rs = $model->changeType($_POST);
		}			
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
		$_SESSION['success']= $message;		 
	else
		$_SESSION['error'] = $message;	
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);	
}

function changeBook($cn)
{
	$error =0;
	$message = "";
	
	if($_POST["book"]=="")
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new expenseModel($cn);
		
		if(isset($_POST["id"]))
		{
			$rs = $model->changeBook($_POST);
		}			
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
		$model = new expenseModel($cn);	
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
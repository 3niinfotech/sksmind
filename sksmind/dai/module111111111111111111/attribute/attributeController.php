<?php
//include('../../../../../variable.php');

include('../../../database.php');
include('attributeModel.php');
session_start();

if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{
	$_POST['fn']();
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	
	$_GET['fn']();
}

function save()
{
	$error =0;
	$message = "";
	
	if(empty($_POST["name"]) || empty($_POST["code"]) || empty($_POST["type"]) )
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new attributeModel();

		if(isset($_POST["id"]))
		{
			$rs = $model->updateData($_POST);
		}
		else
		{
			$rs = $model->addData($_POST);
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
		$model = new attributeModel();	
		$rs = $model->deleteData($_GET['id']);
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
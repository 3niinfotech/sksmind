<?php
//include('../../../../../variable.php');
session_start();
include('../../../database.php');
include('attributeModel.php');
include_once('../../Helper.php');

global $cn;
if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{
	$_POST['fn']($cn);
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	
	$_GET['fn']($cn);
}

function save($cn)
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
		$model = new attributeModel($cn);

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
		$model = new attributeModel($cn);	
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




function getJewAttributebyCode($cn)
{
	$helper = new Helper($cn);	
	$attrcode = 'color_code_'.$_POST['id'];
	$semesterList = $helper->getJewAttributebyCode($attrcode);	
	$html ='<option value="0">All Color Code</option>';
	foreach($semesterList as $k=>$v)
	{
		$html .= '<option value="'.$k.'">'.$v.'</option>';
	}
	echo $html;	
}
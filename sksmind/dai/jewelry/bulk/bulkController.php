<?php
//include('../../../../../variable.php');

include('../../../database.php');
require_once('../../Helper.php');
include('bulkModel.php');
require_once('../../Classes/PHPExcel.php');
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

function save($cn)
{
	if(isset($_POST["import"]))
	{
		if ( isset($_FILES["filename"]) && isset($_FILES["filename"]["name"]) && $_FILES["filename"]["name"]!=""  ) 
		{
			//if there was an error uploading the file
			if ($_FILES["filename"]["error"] > 0)
			{
			
				$_SESSION['error'] = "Return Code: " . $_FILES["filename"]["error"] . "<br />";
				header('Location: ' . $_SERVER['HTTP_REFERER']);	
				exit;
			}
			else 
			{
				$path =  $_POST['daiUrl'];
				$date = Date("dmYHis");
				//$fname = "import_".$date.".xlsx";
				
				$fname = "import.xls";
				
				if (file_exists($_FILES["filename"]["name"])) 
				{			
					unlink($_FILES["filename"]["name"]);
				}
				move_uploaded_file($_FILES["filename"]["tmp_name"],  $path.$fname);
				$uploadedStatus = 1;
				$model = new bulkModel($cn);
				
				$rs = $model->importData($_POST['type'],$path.$fname);
				
				if($rs == 1 )
				{
					$error = 0;
					$message = "Import Successfully !!!";
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
		}
		else 
		{
			
			$error = 1;
			$message = "No file selected!! Please Select file. <br />";
			if ($error == 0) 	
				$_SESSION['success']= $message;		 
			else
				$_SESSION['error'] = $message;		
			
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit;		
		}
	}	
}

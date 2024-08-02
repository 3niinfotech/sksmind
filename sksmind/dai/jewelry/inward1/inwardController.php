<?php
//include('../../../../../variable.php');

include('../../../database.php');
include('inwardModel.php');
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
				$model = new inwardModel($cn);
				
				$rs = $model->importData($path.$fname);
				
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
	else
	{
		$error =0;
		$message = "";
		unset($_POST['daiUrl']);
		if(empty($_POST["reference"]) || empty($_POST["invoiceno"]) || empty($_POST["party"]))
		{
			$message = "Value can't be Blank.";
			$error = 1;
		}	
		else	
		{	
			$model = new inwardModel($cn);
			
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
}

function deletePurchase($cn)
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";

	if(empty($_POST["id"]) )
	{
		$message = "Record Can't found.";
		$error = 1;
	}	
	else	
	{	
		$model = new inwardModel($cn);		
		$rs = $model->deletePurchase($_POST['id']);
		if($rs)
		{
			$error = 0;
			$message = "Delete Record Successfully !!!";
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
function updateInward($cn)
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if(empty($_POST["invoiceno"]) || empty($_POST["party"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new inwardModel($cn);
		$rs = $model->updateInward($_POST);
		
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
		$response['status'] = 1; 
	else
		$response['status'] = 0;	
	
	$response['message'] = $message;
	echo json_encode($response);
}
function updatePrice($cn)
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if(empty($_POST["product"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new inwardModel($cn);
		
		$rs = $model->updatePrice($_POST);
		
		if(is_numeric($rs) || $rs == true )
		{
			$error = 0;
			$message = "Successfully Price Updated !!!";
			//$response['id'] = $rs;
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


function savePackage($cn)
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if(empty($_POST["product"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new inwardModel($cn);
		
		$rs = $model->savePackage($_POST);
		
		if($rs == 1)
		{
			$error = 0;
			$message = "Successfully Add to Package  !!!";			
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

function updateSinglePrice($cn)
{	
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if(empty($_POST["id"]) || empty($_POST["price"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new inwardModel($cn);
			
		if(isset($_POST['type']) &&  $_POST['type']=='memo')
		{
			$rs = $model->updateSinglePriceForIn($_POST);
		}
		else
		{
			$rs = $model->updateSinglePrice($_POST);
		}
		
		if($rs)
		{
			$error = 0;
			$message = "Successfully Price Updated !!!";
			$response['id'] = $rs;
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
		$model = new inwardModel($cn);	
		$rs =0;
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

function returnMemo($cn)
{
	
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if(empty($_POST["id"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new inwardModel($cn);		
		$rs = $model->returnMemo($_POST);		
		if($rs)
		{
			$error = 0;
			$message = "Memo Return Successfully !!!";
			$response['id'] = $rs;
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

function memoTo($cn)
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if(empty($_POST["invoicedate"]) || empty($_POST["party"])  )
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new inwardModel($cn);
		
		$rs = $model->memoTo($_POST);
		
		if(!is_numeric($rs) && $rs != true )
		{
			$error = 1;
			$message = $rs;				
		}
		else
		{
			$error = 0;
			$message = "Successfully Send to Purchase !!!";
			$response['id'] = $rs;
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

function clearData($cn)
{
	$rs = mysql_query("TRUNCATE TABLE dai_temp");
	if(!$rs)
	{
		$rs = mysql_error();
		$_SESSION['error'] = $rs;	
	}
	else
	{
		$_SESSION['success'] = 'Clear all Data Successfully.';	
	}
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	
}





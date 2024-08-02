<?php
//include('../../../../../variable.php');

//include_once('../../../variable.php');
include_once('../../../database.php');
include_once('outwardModel.php');
session_start();


if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{
	$_POST['fn']();
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	
	$_GET['fn']();
}



function sendTo()
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
		if(isset($_POST["on_payment"]) && ($_POST["book"] == "" || $_POST["paid_amount"] == "") )
		{
			$message = "Please select Book and Amount.";
			$error = 1;
		}	
		else
		{
			if(isset($_POST["on_payment"]) && ($_POST["paid_amount"] >  $_POST["due_amount"]) ) 
			{
			$message = "Paid amount greater than Amount";
			$error = 1;
			}	
			else
			{
			$model = new outwardModel();
			
			$rs = $model->sendTo($_POST);
			
				if(!is_numeric($rs))
				{
					$error = 1;
					$message = $rs;				
				}
				else
				{
					$error = 0;
					$message = "Successfully Send to ".$_POST['type']." !!!";
					$response['id'] = $rs;
				}
			}
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

function sendToExport()
{
	
	$error =0;
	$message = "";
	if(empty($_POST["reference"]) || empty($_POST["party"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new outwardModel();
		$rs = $model->sendTo($_POST);
		
		if($rs)
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


function updateOutward()
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
		$model = new outwardModel();
		$rs = $model->updateOutward($_POST);
		
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

function deleteSale()
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
		$model = new outwardModel();
		$rs = $model->deleteSale($_POST);
		
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


function save()
{

	$error =0;
	$message = "";
	if(empty($_POST["reference"]) || empty($_POST["party"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new outwardModel();
		
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
		$model = new outwardModel();	
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

function closeMemo()
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
		$model = new outwardModel();		
		$rs = $model->closeMemo($_POST['id']);		
		if($rs)
		{
			$error = 0;
			$message = "Memo Close Successfully !!!";
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

function closeGia()
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
		$model = new outwardModel();		
		$rs = $model->closeGia($_POST['id']);		
		if($rs)
		{
			$error = 0;
			$message = "GIA Memo Close Successfully !!!";
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

function returnMemo()
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
		$model = new outwardModel();		
		$rs = $model->returnMemo($_POST);		
		if($rs)
		{
			$error = 0;
			$message = "Memo Close Successfully !!!";
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

function saleMemo()
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
		if((isset($_POST["on_payment"]) && $_POST['bdate'] == '') || (isset($_POST["on_payment"]) && $_POST['paid_amount'] == '') )
		{
			$message = "Value can't be Blank.";
			$error = 1;
		}
		else
		{			
			$model = new outwardModel();		
			$rs = $model->saleMemo($_POST);		
			if((is_numeric($rs) && $rs !=0 ) || $rs == true)
			{
				$error = 0;
				$message = "Memo Sale Successfully !!!";
				$response['id'] = $rs;
			}
			else
			{
				$error = 1;
				$message = $rs;
			}
		}
	}	
	if ($error == 0)		
		$response['status'] = 1; 
	else
		$response['status'] = 0;	
	
	$response['message'] = $message;
	echo json_encode($response);	
}


function saveGia()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if(empty($_POST["record"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new outwardModel();		
		$rs = $model->saveGia($_POST);		
		if($rs==1)
		{
			$error = 0;
			$message = "GIA Record Save Successfully !!!";
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
function hold()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if(empty($_POST["ids"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new outwardModel();		
		$rs = $model->hold($_POST);		
		if($rs)
		{
			$error = 0;
			$message = "Successfully Put Hold / UnHold !!!";
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
	//header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function saveCharge()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if(empty($_POST["sale_id"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new outwardModel();		
		$rs = $model->saveCharge($_POST);		
		if($rs == 1)
		{
			$error = 0;
			$message = "Charge Save Successfully !!!";
			
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

function memoTo()
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
		if(isset($_POST["on_payment"]) && ($_POST["book"] == "" || $_POST["paid_amount"] == "") )
		{
			$message = "Please select Book and Amount.";
			$error = 1;
		}	
		else
		{
			if(isset($_POST["on_payment"]) && ($_POST["paid_amount"] >  $_POST["due_amount"]) ) 
			{
			$message = "Paid amount greater than Amount";
			$error = 1;
			}	
			else
			{
			$model = new outwardModel();
			
			$rs = $model->memoTo($_POST);
			
				if(!is_numeric($rs) && $rs != true )
				{
					$error = 1;
					$message = $rs;				
				}
				else
				{
					$error = 0;
					$message = "Successfully Send to ".$_POST['type']." !!!";
					$response['id'] = $rs;
				}
			}
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

function memoToReturn()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
		
	$model = new outwardModel();

	$rs = $model->memoToReturn($_POST);

	if(!is_numeric($rs) && $rs != true )
	{
		$error = 1;
		$message = $rs;				
	}
	else
	{
		$error = 0;
		$message = "Memo Return sucessfully !!!";
		$response['id'] = $rs;
	}
	if ($error == 0)		
		$response['status'] = 1; 
	else
		$response['status'] = 0;
		
	
	$response['message'] = $message;
	echo json_encode($response);
	//header('Location: ' . $_SERVER['HTTP_REFERER']);
}



function chat()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	if(empty($_POST["message"]) && isset($_FILES["attachement"]["name"]) && $_FILES["attachement"]["name"] =="" )
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
	$imgDir = $_POST['url'];
		$profile_image = "";
		if(isset($_FILES["attachement"]["name"]) && $_FILES["attachement"]["name"] !=""  ) 
		{
			$target_dir = $imgDir.'attachement/';
			
			$profile_image = basename($_FILES["attachement"]["name"]);
			$target_file = $target_dir . basename($_FILES["attachement"]["name"]);
		
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			// Check if image file is a actual image or fake image
			
			/* $check = getimagesize($_FILES["attachement"]["tmp_name"]);
			if($check !== false) {				
				$uploadOk = 1;
			} else {
				$_SESSION['error'] = "File is not an image.";
				$uploadOk = 0;
			} */
		
			// Check if file already exists
			/* if (file_exists($target_file)) {
				$_SESSION['error'] = "Sorry, file already exists.";
				$uploadOk = 0;
			} */
			// Check file size
			/* if ($_FILES["attachement"]["size"] > 500000) {
				$_SESSION['error'] = "Sorry, your file is too large.";
				$uploadOk = 0;
			} */
			// Allow certain file formats
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 1) 
			{
				if (move_uploaded_file($_FILES["attachement"]["tmp_name"], $target_file)) 
				{
					$error = 0;
				} else {
					$error = 1;
					$message = "Sorry, there was an error uploading your file.";
				}
			}
			if (!$uploadOk) 
			{
				header('Location: ' . $_SERVER['HTTP_REFERER']);
				exit;
			}	    
		}
		if($error == 0)
		{
			$_POST['attachement'] = $profile_image;
			unset($_POST['url']);
			$model = new outwardModel();		
			$rs = $model->chat($_POST);	
			
			if($rs == 'no')
			{
				$error = 1;
				$message = "There is Problem !!!";			
			}
			else
			{
				$error = 0;
				$message = $rs;
			}
		}
	}	
	if ($error == 0)		
		$response['status'] = 1; 
	else
		$response['status'] = 0;	
	
	$response['message'] = $message;
	echo json_encode($response);	
}
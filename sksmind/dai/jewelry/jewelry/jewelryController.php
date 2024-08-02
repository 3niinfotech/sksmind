<?php 

session_start(); 
ob_start();
//include('../../../../../variable.php');

include('../../../database.php');
include('jewelryModel.php');
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
	
	if(empty($_POST["sku"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new jewelryModel($cn);
		
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
		$model = new jewelryModel($cn);	
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
function deleteRepair($cn)
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
		$model = new jewelryModel($cn);	
		$rs = $model->deleteRepair($_POST['id']);
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

function skuData($cn)
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if( empty($_POST["sku"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new jewelryModel($cn);		
		$message = $model->skuData($_POST["sku"]);					
	}	
	
	if ($error == 0)		
		$response['status'] = 1; 
	else
		$response['status'] = 0;
		
	$response['message'] = $message;
	echo json_encode($response);
	//header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function memoToReturn($cn)
{
	
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	if( empty($_POST["sku"]) || empty($_POST["return_date"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{
		$model = new jobworkModel($cn);

		$rs = $model->memoToReturn($_POST);

		if($rs == true || $rs == 1)
		{
			$error = 0;
			$message = "Memo Return sucessfully !!!";
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

function repairToReturn($cn)
{
	
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	if( empty($_POST["date"]) )
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{
		$model = new jewelryModel($cn);

		$rs = $model->repairToReturn($_POST);

		if($rs)
		{
			$error = 0;
			$message = "Memo Return sucessfully !!!";
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



function consignToReturn($cn)
{
	
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	$model = new jewelryModel($cn);

	$rs = $model->consignToReturn($_POST);

	if($rs)
	{
		$error = 0;
		$message = "Consingment Return sucessfully !!!";
		//$response['id'] = $rs;
	}
	else
	{
		$error = 1;
		$message = $rs;				
	}
	
	if ($error == 0)		
		$response['status'] = 1; 
	else
		$response['status'] = 0;
		
	
	$response['message'] = $message;
	echo json_encode($response);
	//header('Location: ' . $_SERVER['HTTP_REFERER']);
}


function saleJewelry($cn)
{
		
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	if( empty($_POST["date"]) || empty($_POST["final_amount"]) || empty($_POST["party"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{
		$model = new jewelryModel($cn);

		$rs = $model->saleJewelry($_POST);

		if(is_numeric($rs) || $rs == true)
		{
			$error = 0;
			$message = "Sale sucessfully !!!";
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
function memoTosaleJewelry($cn)
{
		
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	if( empty($_POST["date"]) || empty($_POST["final_amount"]) || empty($_POST["party"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{
		$model = new jewelryModel($cn);

		$rs = $model->memoTosaleJewelry($_POST);

		if(is_numeric($rs) || $rs == true)
		{
			$error = 0;
			$message = "Sale sucessfully !!!";
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

function updateSale($cn)
{
		
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	if( empty($_POST["date"]) || empty($_POST["party"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{
		$model = new jewelryModel($cn);

		$rs = $model->updateSale($_POST);

		if(is_numeric($rs) || $rs == true)
		{
			$error = 0;
			$message = "Sale sucessfully !!!";
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


function colletToReturn($cn)
{
	
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	if( empty($_POST["date"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{
		$model = new jewelryModel($cn);

		$rs = $model->colletToReturn($_POST);

		if(is_numeric($rs) || $rs == true)
		{
			$error = 0;
			$message = "Collet Return sucessfully !!!";
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


function checkSku($cn)
{
	
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	if( empty($_POST["sku"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{
		$model = new jewelryModel($cn);

		$rs = $model->checkSku($_POST['sku']);

		if($rs == true || $rs == 1)
		{
			$error = 0;
			$message = "";
			//$response['id'] = $rs;
			
		}
		else
		{
			$error = 1;
			$message = ' SKU is already exist. Please Enter Other Sku.';				
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
		$model = new jewelryModel($cn);
		
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
function deleteSale($cn)
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
		$model = new jewelryModel($cn);
		$rs = $model->deleteSale($_POST);
		
		if($rs == 1)
		{
			$error = 0;
			$message = "Successfully Deleted !!!";
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
function saveCharge($cn)
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
		$model = new jewelryModel($cn);		
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
function sendToLab($cn)
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if(empty($_POST["date"]) || empty($_POST["party"]))
		
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{
        if(isset($_POST['id']))	
		{
			$model = new jewelryModel($cn);		
			$rs = $model->updatesendToLab($_POST);
		
			if(!is_numeric($rs))
			{
				$error = 1;
				$message = $rs;				
			}
			else
			{
				$error = 0;
				$message = "Update Successfully Send to Lab Work!!!";
				$response['id'] = $rs;
			}
		}
		else
		{
			$model = new jewelryModel($cn);		
			$rs = $model->sendToLab($_POST);
		
			if(!is_numeric($rs))
			{
				$error = 1;
				$message = $rs;				
			}
			else
			{
				$error = 0;
				$message = "Successfully Send to Lab Work!!!";
				$response['id'] = $rs;
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
function saveGia($cn)
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
		$model = new jewelryModel($cn);		
		$rs = $model->saveGia($_POST);		
		if($rs==1)
		{
			$error = 0;
			$message = "IGI Lab Record Save Successfully !!!";
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
function updateLab($cn)
{
		
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	
	if( empty($_POST["date"]) || empty($_POST["party"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{
		$model = new jewelryModel($cn);

		$rs = $model->updateLab($_POST);

		if(is_numeric($rs) || $rs == true)
		{
			$error = 0;
			$message = "Update lab sucessfully !!!";
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
function deleteLab($cn)
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
		$model = new jewelryModel($cn);
		$rs = $model->deleteLab($_POST);
		
		if($rs == 1)
		{
			$error = 0;
			$message = "Successfully Deleted !!!";
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
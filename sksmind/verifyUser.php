<?php 
session_start(); 
include('variable.php');
include('database.php');
$data = $_GET;

$error =0;
$message ='';

$mid = base64_decode($data['mid']);
$vs = $data['vs'];

$sql = "SELECT * FROM user WHERE member_id ='$mid'";

$result = mysqli_query($cn,$sql);
$stockData = mysqli_fetch_array($result);

if(!empty($stockData) && $stockData['status'] == 'new')		
{
	if(md5($stockData['e_mail']) == $vs)
	{
		$sql = "update user set status='verify' WHERE id=".$stockData['id'];
		
		if (!mysqli_query($cn,$sql)) 
		{
			$error = 1;
			$message = "Error in Verifying. Please, Verifying Again";
		}
	}
	else
	{
		$error = 1;
		$message = "Invalid Your email Address, Please verify Again.";
	}
}
else
{
	$message ="Verify Link has been Expired.";
	$error = 1;
}

if($error)
{
	$_SESSION['error'] = $message;
	header('Location: ' .$mainUrl);
}
else
{
	$_SESSION['success'] = $message;
	header('Location: ' .$mainUrl.'registerStep.php');
}

?>


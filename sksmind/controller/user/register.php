<?php 
session_start(); 
include('../../variable.php');
include('../../database.php');
?>
<?php
$data = $_POST;

$error =0;
$message ='';

if($data['password'] == $data['repeat'])
{
	$un = $data['username'];
	$ps = md5($data['password']);
	$email = $data['email'];
	$mid = rand(0,9999999999);
	$varify = md5($email);
	$encodeMId= base64_encode($mid);
	
	$VerfyUrl = $mainUrl.'verifyUser.php?mid='.$encodeMId.'&vs='.$varify;
	$cDate = date("Y-m-d H:i:s");
	$date=date_create($cdate);
	date_add($cdate,date_interval_create_from_date_string("30 days"));
	$eDate = date_format($date,"Y-m-d H:i:s");
	
	$sql = "INSERT INTO user(user_name,e_mail,password,member_id,verify_key,type,status,created_at,expired_at,subscription) VALUES ('$un','$email','$ps','$mid','$VerfyUrl','customer','new','$cDate','$eDate','trial')"; 
	
	
	if (mysqli_query($cn,$cn,$sql)) 
	{
			$message = "Registration Done Successfully !!";
	} 
	else
	{
	  $error = 1;
	$message =mysqli_errno() . ": " . mysqli_error();
    }
}
else
{
	$error =1;
    $message ='Password Dose not Match';
}

if($error)
{
	$_SESSION['error'] = $message;
	 header('Location: ' . $_SERVER['HTTP_REFERER']);
}
else
{
	$_SESSION['success'] = $message;
	header('Location: ' .$mainUrl.'registerVerify.php');
}

?>

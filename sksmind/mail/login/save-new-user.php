<?php
 include("../database.php");

session_start();
if (isset($_SESSION['login_user']))
{

	//$user_id = $_SESSION['login_user'];
	$fn = $_POST['first_name'];
	
	$address = $_POST['address'];
	$mobile = $_POST['mobile'];
	$pass = md5($_POST['password']);
	
	$company_name = $_POST['company_name'];
	$tel_no = $_POST['tel_no'];
	$fax_no = $_POST['fax_no'];
	$rapnet_id = $_POST['rapnet_id'];
	$skype_id = $_POST['skype_id'];
	$wechat_id = $_POST['wechat_id'];
	$qq_id = $_POST['qq_id'];
	$welcommess = $_POST['welcommess'];
	$formality = $_POST['formality'];
	
	$cod_mo_no = $_POST['cod_mo_no'];
	$cod_comp_no = $_POST['cod_comp_no'];
	$cod_fax_no = $_POST['cod_fax_no'];
	
	$mobile = '+'.$cod_mo_no.' '.$mobile;
	$tel_no = '+'.$cod_comp_no.' '.$tel_no;
	$fax_no = '+'.$cod_fax_no.' '.$fax_no;
	
	
	$error = 1;
		if(empty($_POST['user_f_name']) || empty($_POST['password']) || empty($_POST['user_email']))
		{
			$error = 0;
		}	
		if(!empty($_POST['user_email']))
		{
			$email = trim($_POST['user_email']);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error = 0;
			}
		}
		
		if($error==1)
		{

			$user_email = trim($_POST['user_email']);
			$user_f_name = addslashes($_POST['user_f_name']);

			$sql = "INSERT INTO user(user_name,user_email,first_name,address,mobile,pass,type,company_name,tel_no,fax_no,rapnet_id,skype_id,wechat_id,qq_id,welcommess,formality) VALUES ('$user_f_name','$user_email','$fn','$address','$mobile','$pass','user','$company_name','$tel_no','$fax_no','$rapnet_id','$skype_id','$wechat_id','$qq_id','$welcommess','$formality')";
			
			   if (mysql_query($sql)) {
					session_start();
					$_SESSION['su_cat']= "New User Successfully Saved !!";
					unset($_SESSION['error_cat']); 
						header("Location: ../user_management.php");
				} else {
						session_start();
						$_SESSION['error_cat']= "Ooppss, There is error in save. Please correct information. ";
						 header("Location: ".$_SERVER['HTTP_REFERER']);
				} 
		}
		else
		{
			session_start();
			$_SESSION['error_cat']= "Ooppss, Error in save. Please correct information. ";
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}		
			
			

}
else
{
  header("Location: index.php");
}
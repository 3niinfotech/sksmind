<?php
 include("../database.php");

session_start();
if (isset($_SESSION['login_user']))
{

	$user_id = $_SESSION['temp_edit_user'];
	if (isset($_SESSION['temp_edit_user']))
	{ 
		unset($_SESSION['temp_edit_user']);
	}
	
	$fn = $_POST['first_name'];

	$address = $_POST['address'];
	$mobile = $_POST['mobile'];
	
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
		if(empty($_POST['user_f_name']))
		{
			$error = 0;
		}	
		if(empty($_POST['user_email']))
		{
			$error = 0;
		}
		else
		{
			$email = trim($_POST['user_email']);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error = 0;
			}
		}
		
		
		if(!empty($_POST['user_c_pass']))
		{
			
			
			if(!empty($_POST['user_c_pass']))
			{
				$current_pass = addslashes($_POST['user_c_pass']);
				$user_new_pass = addslashes($_POST['user_new_pass']);
				$user_email = trim($_POST['user_email']);
				$user_f_name = addslashes($_POST['user_f_name']);
				$current_pass =md5($current_pass);
				
				
				
				$user_new_pass =md5($user_new_pass);
				$sql="SELECT * FROM user WHERE pass='$current_pass' and user_id= ".$user_id;

				$result=mysql_query($sql);
				$row=mysql_fetch_array($result);

				$count=mysql_num_rows($result);
				
				
				if($count==1)
				{
					if($error==1)
					{	
						$sql = "UPDATE user SET user_name='$user_f_name',user_email	='$user_email',pass	='$user_new_pass',first_name='$fn',mobile='$mobile',address='$address',company_name='$company_name',tel_no='$tel_no',fax_no='$fax_no',rapnet_id='$rapnet_id',skype_id='$skype_id',wechat_id='$wechat_id',qq_id='$qq_id',welcommess='$welcommess',formality='$formality' WHERE user_id= ".$user_id;
						
					   if (mysql_query($sql)) {
							
							session_start();
							$_SESSION['su_cat']= "Password Successfully Changed.";
							unset($_SESSION['error_cat']); 
							header("Location:../user_management.php ");
						} else {
							 session_start();
							 $_SESSION['error_cat']= "error";
							header("Location: ".$_SERVER['HTTP_REFERER']);
						} 
					}
					else
					{
						session_start();
						$_SESSION['error_cat']= "error";
						header("Location: ".$_SERVER['HTTP_REFERER']);
					}	
				}
				else
				{
					session_start();
					$_SESSION['error_cat']= "Invalid Current Password.";
					header("Location: ".$_SERVER['HTTP_REFERER']);
				}		
			}
		}
		else
		{

			if($error==1)
			{

				$user_email = trim($_POST['user_email']);
				$user_f_name = addslashes($_POST['user_f_name']);

				$sql = "UPDATE user SET user_name='$user_f_name',user_email ='$user_email',first_name='$fn', last_name='$ln', mobile='$mobile', address='$address',company_name='$company_name',tel_no='$tel_no',fax_no='$fax_no',rapnet_id='$rapnet_id',skype_id='$skype_id',wechat_id='$wechat_id',qq_id='$qq_id',welcommess='$welcommess',formality='$formality' WHERE user_id = ".$user_id;
				
				if (mysql_query($sql)) {
						session_start();
						$_SESSION['su_cat']= "User info Successfully saved";
						unset($_SESSION['error_cat']); 
							header("Location:../user_management.php ");
					} else {
							session_start();
							$_SESSION['error_cat']= "error";
							 header("Location: ".$_SERVER['HTTP_REFERER']);
					} 
			}
			else
			{
				session_start();
				$_SESSION['error_cat']= "error";
				header("Location: ".$_SERVER['HTTP_REFERER']);
			}		
			
		}	

}
else
{
  header("Location: index.php");
}
<?php
include("../../variable.php");
 include("../../database.php");

session_start();
if (isset($_SESSION['userid']))
{
	$name = $_POST['name'];
	$company_name = $_POST['company_name'];
	$mo_no = $_POST['mo_no'];
	$cod_mo_no = $_POST['cod_mo_no'];
	$company_no = $_POST['company_no'];
	$cod_comp_no = $_POST['cod_comp_no'];
	$fax_no = $_POST['fax_no'];
	$company_add = $_POST['company_add'];
	$cat_ids = $_POST['cat_id'];
	$country_id = $_POST['country_id'];
	
	$rapnet_id = $_POST['rapnet_id'];
	$skype_id = $_POST['skype_id'];
	$wechat_id = $_POST['wechat_id'];
	$qq_id = $_POST['qq_id'];
	$cod_fax_no = $_POST['cod_fax_no'];
	
	$mo_no = '+'.$cod_mo_no.' '.$mo_no;
	$company_no = '+'.$cod_mo_no.' '.$company_no;
	$fax_no = '+'.$cod_fax_no.' '.$fax_no;
	
	$error = 1;
		
		if(empty($_POST['country_id']))
		{
			$error = 0;
		}
		else
		{
			$country_id = $_POST['country_id'];
			$country_count_rs = mysql_query("select COUNT(id) from country where id = ".$country_id);
			$country_count_list =  mysql_fetch_assoc($country_count_rs);
			$country_count = $country_count_list['COUNT(id)'];
			if($country_count == 0)
			{
				$error = 0;
			}
		}	
	
	
	
		if(empty($_POST['name']))
		{
			$error = 0;
		}	
		if(empty($_POST['cat_id']))
		{
			$error = 0;
		}
		else
		{
			$cat_ids = $_POST['cat_id'];
			foreach($cat_ids as $cat_id)
			{
				$cat_count_rs = mysql_query("select COUNT(id) from catalog where id = ".$cat_id);
				$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
				$cat_count = $cat_count_list['COUNT(id)'];
			
				if($cat_count == 0)
				{
					$error = 0;
				}
			}
			
		}	
		if(empty($_POST['email']))
		{
			$error = 0;
		}
		else
		{
			$email = trim($_POST['email']);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error = 0;
			}
		}
		
		if($error==1)
		{
			$email_count = 0;
            $email = trim($_POST['email']);
			$user_id = $_SESSION['userid'];
			$cat_count_rs = mysql_query("select COUNT(email) from email where email = '$email'");
			$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
			$email_count = $cat_count_list['COUNT(email)'];
			$cat_id = implode(',', $cat_ids);
			
				if($email_count == 0)
				{ 
					$sql = "INSERT INTO email(name,email,company_name,mo_no,company_no,fax_no,rapnet_id,skype_id,wechat_id,qq_id,company_add,cat_id,country_id,user_id) VALUES ('$name','$email','$company_name','$mo_no','$company_no','$fax_no','$rapnet_id','$skype_id','$wechat_id','$qq_id','$company_add','$cat_id','$country_id',".$user_id.")";
				 
						 if (mysql_query($sql)) {
							session_start();
							$_SESSION['su_cat']= "Client info Successfully saved";
							unset($_SESSION['error_cat']); 
							header("Location: ../email.php");
						  } else {
								session_start();
								$_SESSION['error_cat']= "invalid, something wrong. try again...";
								header("Location: ".$_SERVER['HTTP_REFERER']);
						  } 
				 }
				 else
				 {
					session_start();
					$_SESSION['error_cat']= "This ".$email." email already exists. Enter new email.";
					header("Location: ".$_SERVER['HTTP_REFERER']);
				 }	 
			}
			else
			{
				session_start();
				$_SESSION['error_cat']= "invalid, something wrong. try again..";
				header("Location: ".$_SERVER['HTTP_REFERER']);
			}		

}
else
{
  header("Location:".$emsUrl);
}
<?php
/************************ YOUR DATABASE CONNECTION START HERE   ****************************/

session_start();
include("database.php");
$uploadedStatus = 0;

	$type = $_POST['type'];
	if ( isset($_FILES["file"])) {
	//if there was an error uploading the file
	if ($_FILES["file"]["error"] > 0) {
	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
	}
	else 
	{
	if (file_exists($_FILES["file"]["name"])) {
	unlink($_FILES["file"]["name"]);
	}
	$storagename = "importData.xlsx";
	move_uploaded_file($_FILES["file"]["tmp_name"],  $storagename);
	$uploadedStatus = 1;
	}
	} else {
	echo "No file selected <br />";
	}

/************************ YOUR DATABASE CONNECTION END HERE  ****************************/

if($uploadedStatus==1)
{
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
include 'PHPExcel/IOFactory.php';

// This is the file path to be uploaded.
$inputFileName = 'importData.xlsx'; 

try {
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
} catch(Exception $e) {
	die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}


$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet

$count = 0;
$msg="";
$email_msg = "";
$email_error = 1;
$cat_msg = "";
$cat_error = 1;
$user_msg = "";
$user_error = 1;
$error_msg="";
$error = 1;
if($type == "category")
{	
	for($i=2;$i<=$arrayCount;$i++)
	{
		$category = trim($allDataInSheet[$i]["A"]);
		
		$query = "SELECT cat_name FROM catalog WHERE cat_name = '".$category."'";
		$sql = mysql_query($query);
		$recResult = mysql_fetch_array($sql);
		$existName = $recResult["cat_name"];
		
		if($existName == "")
		{
			$insertTable= mysql_query("insert into catalog (cat_name) values('".$category."');");
			$count++;
		}		
	}
	$msg = $count ." Category Imported Successfully.";	
 }
 else
 {
		for($i=2;$i<=$arrayCount;$i++)
		{
			$email = trim($allDataInSheet[$i]["A"]);
			$name = trim($allDataInSheet[$i]["B"]);
			$company_name = trim($allDataInSheet[$i]["C"]);
			$mo_no = trim($allDataInSheet[$i]["D"]);
			$company_no = trim($allDataInSheet[$i]["E"]);
			$fax_no = trim($allDataInSheet[$i]["F"]);
			$rap_id = trim($allDataInSheet[$i]["G"]);
			$sky_id = trim($allDataInSheet[$i]["H"]);
			$wechat_id = trim($allDataInSheet[$i]["I"]);
			$qq_id = trim($allDataInSheet[$i]["J"]);
			$company_add = trim($allDataInSheet[$i]["K"]);
			$category = trim($allDataInSheet[$i]["L"]);
			$country = trim($allDataInSheet[$i]["M"]);
			$user_name = trim($allDataInSheet[$i]["N"]);

			//echo $category;
			if($i != $arrayCount || ($email != "" && $name != ""))
			{
				$cat_names = explode(',', $category);
				$cat_ids = array();
				$index_cat_id = 0;
				$cat_count = 1;
				
				/* Email Validation */ 
				if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
				  $email_msg .= "<b><p>row $i in $email Email Is Invalid.<br></p></b>";
				  $email_error = 0;
				} 
				
				/* Category validation */
				
				foreach($cat_names as $cat_id)
				{
					$cat_name = trim($cat_id);
					$cat_count_rs = mysql_query("select COUNT(id) from catalog where cat_name = '$cat_name'");
					$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
					$cat_count = $cat_count_list['COUNT(id)'];
					
					if($cat_count == 0)
					{
						$error = 0;
						$cat_msg .= "<b><p>row $i in $cat_name Category Is Invalid.<br></p></b>";
						$cat_error = 0;
					}
				}
				
				
				/* country validation */
				
				
					$country = trim($country);
					
					if($country != '')
						{
						$country_name_rs = mysql_query("select COUNT(id) from country where country_name = '$country'");
					
						$country_name_list =  mysql_fetch_assoc($country_name_rs);
						$country_count = $country_name_list['COUNT(id)'];
					
						if($country_count == 0)
						{
							$error = 0;
							$cat_msg .= "<b><p>row $i in $country country Is Invalid.<br></p></b>";
							$cat_error = 0;
						}
					}
				
				
				
				/* User Validation validation */
				
				$user_count_rs = mysql_query("select COUNT(user_id) from user where user_name = '$user_name'");
				$user_count_list =  mysql_fetch_assoc($user_count_rs);
				$user_count = $user_count_list['COUNT(user_id)'];
				if($user_count == 0)
				{
					$error = 0;
					$user_msg .= "<b><p>row $i in $user_name User Name Is Invalid.<br></p></b>";
					$user_error = 0;
				}
			}	
		}
		$error_msg = $email_msg."<br>".$cat_msg."<br>".$user_msg;
		
		
		if($error == 1 && $email_error == 1 && $cat_error == 1 && $user_error == 1)
		{
			$simple_error = "";
			
			for($i=2;$i<=$arrayCount;$i++)
			{
				$email = trim($allDataInSheet[$i]["A"]);
				$name = trim($allDataInSheet[$i]["B"]);
				$company_name = trim($allDataInSheet[$i]["C"]);
				$mo_no = trim($allDataInSheet[$i]["D"]);
				$company_no = trim($allDataInSheet[$i]["E"]);
				$fax_no = trim($allDataInSheet[$i]["F"]);
				$rap_id = trim($allDataInSheet[$i]["G"]);
				$sky_id = trim($allDataInSheet[$i]["H"]);
				$wechat_id = trim($allDataInSheet[$i]["I"]);
				$qq_id = trim($allDataInSheet[$i]["J"]);
				$company_add = trim($allDataInSheet[$i]["K"]);
				$category = trim($allDataInSheet[$i]["L"]);
				$country = trim($allDataInSheet[$i]["M"]);
				$user_name = trim($allDataInSheet[$i]["N"]);

				if($i != $arrayCount || ($email != "" && $name != ""))
				{
					//echo $category;
					$cat_names = explode(',', $category);
					$cat_ids = array();
					$index_cat_id = 0;
					$cat_count = 1;
					
					/* Category validation */
					
					foreach($cat_names as $cat_id)
					{
						$cat_name = trim($cat_id);
						$cat_count_rs = mysql_query("select COUNT(id) from catalog where cat_name = '$cat_name'");
						$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
						$cat_count = $cat_count_list['COUNT(id)'];
						
						if($cat_count == 0)
						{
							$error = 0;
						}
						else
						{
							$cat_count_rs = mysql_query("select * from catalog where cat_name = '$cat_name'");
							$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
							$cat_id = $cat_count_list['id'];
							$cat_ids[$index_cat_id]=$cat_id;
							$index_cat_id++;
						}	
					}
					$cat_id1 = implode(',', $cat_ids);
					
					
					/* country validation */
					
					
					$country = trim($country);
					if($country != ''){
						$country_name_rs = mysql_query("select COUNT(id) from country where country_name = '$country'");
					
					
						$country_name_list =  mysql_fetch_assoc($country_name_rs);
						$country_count = $country_name_list['COUNT(id)'];
					
					
						if($country_count == 0)
						{
							$error = 0;
							$cat_msg .= "<b><p>row $i in $country Category Is Invalid.<br></p></b>";
							$cat_error = 0;
						}
						else
						{
							$country_name_rs = mysql_query("select * from country where country_name = '$country'");
							$country_name_list =  mysql_fetch_assoc($country_name_rs);
							$country_id = $country_name_list['id'];
						}
					}					
					
					
					
					/* User Validation validation */
					
					$user_count_rs = mysql_query("select COUNT(user_id) from user where user_name = '$user_name'");
					$user_count_list =  mysql_fetch_assoc($user_count_rs);
					$user_count = $user_count_list['COUNT(user_id)'];
						if($user_count == 0)
						{
							$error = 0;
						}
						else
						{
							$user_count_rs = mysql_query("select * from user where user_name = '$user_name'");
							$user_count_list =  mysql_fetch_assoc($user_count_rs);
							$user_id = $user_count_list['user_id'];
						}	
				
				
					if($error)
					{
						$insertTable= mysql_query("insert into email (email,name,company_name,mo_no,company_no,fax_no,company_add,cat_id,user_id,rapnet_id,skype_id,wechat_id,qq_id,country_id) values('".strtolower($email)."','".$name."','".$company_name."','".$mo_no."','".$company_no."','".$fax_no."','".$company_add."','".$cat_id1."',".$user_id.",'".$rap_id."','".$sky_id."','".$wechat_id."','".$qq_id."','".$country_id."');");
				
						if(!$insertTable)
						{
							$simple_error .= "<b><p>row $i record not inserted. <br></p></b>";
						}
						else
						{
							$count++;
						}		
					}
					else
					{
						
						$msg = "Something may be wrong1.";	
						unset($_SESSION['su_cat']);
						$_SESSION['error_cat']= $msg;
						header('Location: import.php');
					}	
				}	
				
				
			}
			
			$msg = $count ." Email Imported Successfully.";	
			
			unset($_SESSION['error_cat']);
			$_SESSION['su_cat']= $msg;
			
			if($simple_error != "")
			{
				$_SESSION['error_cat'] = $simple_error;
			}	
			echo $_SESSION['error_cat'];
			header('Location: import.php');
		}
		else
		{
			echo $error;
			echo $email_error." ".$cat_error." ".$cat_error;
			echo $error_msg;
			unset($_SESSION['su_cat']); 
			$_SESSION['error_cat']= $error_msg;
			header('Location: import.php');
		}	
	}
 }
?>
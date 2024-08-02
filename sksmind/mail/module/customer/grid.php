<?php 

 $user_id =  $_SESSION['userid'];
	if($_SESSION['type'] == 'admin')
	{	
		$rs=mysql_query("select * from email");
	}
	else
	{
		$rs=mysql_query("select * from email where user_id= ".$user_id);
	}		
	$model = array();
	$index = 1;
	$customer_grid = array();
	while ($company_list =  mysql_fetch_assoc($rs))
	{
		
		$cat_ids = explode(',', $company_list['cat_id']);
		$total_count = " ";
		foreach($cat_ids as $cat_id)
		{
			$cat_count_rs = mysql_query("select cat_name from catalog where id = ".$cat_id);
			$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
			if($cat_count_list['cat_name'])
			$total_count .= ",<br>".$cat_count_list['cat_name'];
		}	
		$email_id =  $company_list['id']; 
		$total_count = substr($total_count,6);
		if( $company_list['country_id'] != ''):
		$counry_rs = mysql_query("select * from country where id = ".$company_list['country_id']);
		$country_list =  mysql_fetch_assoc($counry_rs);
		$country_name = $country_list['country_name'];
		else:
			$country_name = '';
		endif;
		
		$user_rs1 = mysql_query("select * from user where user_id = ".$company_list['user_id']);
		$user_list1 =  mysql_fetch_assoc($user_rs1);
		$user_name = $user_list1['user_name'];
		
		$c_name  = $company_list['name'];
		$c_email =$company_list['email'];
		$c_company_name  =$company_list['company_name'];
		$c_mo_no =$company_list['mo_no'];
		$c_last_li_html = "";
		
		$c_last_li_html = '<a href = "edit-email.php?id='.$email_id.'"><span class="fa fa-eye grid-icon"></span><span class="fa fa-pencil-square-o grid-icon"></span></a>';
		
		if($_SESSION['type'] == 'admin'):
			$c_last_li_html .= "<a class='fa fa-trash grid-icon red' onclick='deleteEmail(\"".$email_id."\")'></a>";
			
		endif;
		
		$checkbox_delete = '<input name="checkboxtrackemail[]" type="checkbox" value="'.$email_id.'" class = "checkboxtrackemail">';	
		
		$customer_grid[] = array('mass_delete' => "$checkbox_delete" ,'id' => "$index" ,'name' => "$c_name",'email' => "$c_email",'company_name' => "$c_company_name",'mo_no' => "$c_mo_no",'in_category' => "$total_count",'in_country' => "$country_name",'last_li' => "$c_last_li_html");
		$index++;
	}
	$customer_json = json_encode($customer_grid);
?>
<div class="container mail-customer">
	<table class="table table-striped"></table>
</div> 
<?php
session_start();
include("../variable.php");
include("../database.php");
include_once("../checkResource.php");
if (!isset($_SESSION['username']))
{
	header("Location: ".$mainUrl);	
}
?>
<html>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<?php 
		include("head2.php");
	?>	
	<body>
		<?php
			include("header2.php");			
			
			if (isset($_SESSION['userid']))
			{
			?>
				<div class = "main_container">
				<?php
					if (isset($_SESSION['su_cat']))
					{ ?>
						<div class = "success_div" style = "width:100%; height:50px;"><?php echo $_SESSION['su_cat']; ?></div>
					<?php
						unset($_SESSION['su_cat']);
					}
					?>	
					<?php
					if (isset($_SESSION['error_cat']))
					{ ?>
						<div class = "error_div success" style = "width:100%; height:50px;"><?php echo $_SESSION['error_cat']; ?></div>
					<?php
						unset($_SESSION['error_cat']);
					}
					?>	
					
					<div class="template_heading"><span class="template_left">Customer List</span><span class="template_right"><a style = "margin:0 5px;" class="button round blue" href="<?php //echo $_SERVER['HTTP_REFERER']; ?>">Back</a><a style = "margin:0 5px;" class="button round blue" href="save-email.php">Add New Email</a></span></div>
					
					<div class = "main mail_email">
						<div class = "main_left customer_list">
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
										
										$c_last_li_html = '<a href = "edit-email.php?id='.$email_id.'"><span class="fa fa-eye"></span><span class="fa fa-pencil-square-o"></span></a>';
										
										if($_SESSION['type'] == 'admin'):
											$c_last_li_html .= "<a class='fa fa-trash' onclick='deleteEmail(\"".$email_id."\")'></a>";
											
										endif;
										
										$checkbox_delete = '<input name="checkboxtrackemail[]" type="checkbox" value="'.$email_id.'" class = "checkboxtrackemail">';	
										
										$customer_grid[] = array('mass_delete' => "$checkbox_delete" ,'id' => "$index" ,'name' => "$c_name",'email' => "$c_email",'company_name' => "$c_company_name",'mo_no' => "$c_mo_no",'in_category' => "$total_count",'in_country' => "$country_name",'create_user' => "$user_name",'last_li' => "$c_last_li_html");
										$index++;
									}
									$customer_json = json_encode($customer_grid);
								?>
							
								<div class="container">
									<table class="table table-striped"></table>
								</div> 
								<script>
									jQuery(function(jQuery){
										jQuery('.table').footable({
											"paging": {
												"enabled": true,
												"size": 20
											},
											"filtering": {
												"enabled": true
											},
											"sorting": {
												"enabled": true
											},
											"columns": jQuery.get("customer/columns.json"),
											"rows": <?php echo $customer_json; ?>//jQuery.get("customer/rows.json")
										});
									});
								</script>
						</div>
					</div>
				</div>	
			<?php
			}
			else
			{
				echo "user session expired";
				$_SESSION['error_cat'] = "User Session Expired.";
				header('Location: index.php');
			}	
		?>
		
		<script>
				function deleteEmail(email_id)
				{
					jQuery.prompt("Are you sure you want to delete this item? It cannot be restored at a later time!", {
						title: "Delete Confirmation",
						buttons: { "Yes, I'm Ready": true, "No, Lets Wait": false },
						submit: function(e,v,m,f){
							if(v)
							{
								var data = {'function':'DeleteEmail','id':email_id};
								jQuery.ajax({
									url: 'controller/MasterController.php', 
									type: 'POST',
									data: data,
									success: function(result)
										{
										   setTimeout(function(){ location.reload();}, 1000);
										}
									});	
							}	
						}
					});
				}
				
				function massdeleteEmail()
				{
					var values = new Array();
					jQuery.each(jQuery("input[name='checkboxtrackemail[]']:checked"), function() {
						values.push(jQuery(this).val());
					});
					
					if(values.length)
					{
						jQuery.prompt("Are you sure you want to delete this item? It cannot be restored at a later time!", {
						title: "Delete Confirmation",
						buttons: { "Yes, I'm Ready": true, "No, Lets Wait": false },
							submit: function(e,v,m,f){
								if(v)
								{
									var data = {'function':'MassDeleteEmail','massid':values};
									jQuery.ajax({
										url: 'controller/MasterController.php', 
										type: 'POST',
										data: data,
										success: function(result)
											{
											  setTimeout(function(){ location.reload();}, 1000);
											}
										});	 
								}	
							}
						});	
					}	
					else
					{
						jQuery.prompt("Please select at least one record!", {
							title: "Delete Confirmation",
							buttons: { "close": true}
						});	
					}	
				}	
		</script>			
		<?php
			include("footer.php");
		?>	
		
		
		
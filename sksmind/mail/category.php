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
	<?php 
		include("head.php");
	?>	
	<body>
		<?php
			include("header.php");
			
			if (isset($_SESSION['userid']))
			{
					if (isset($_SESSION['error_cat']))
					{ ?>
						<div class = "error_div success" style="float: left;"><?php echo $_SESSION['error_cat']; ?></div>
					<?php
						unset($_SESSION['error_cat']);
					}
					?>	
					<?php
					if (isset($_SESSION['su_cat']))
					{ ?>
						<div class = "success_div" style="float: left;"><?php echo $_SESSION['su_cat']; ?></div>
					<?php
						unset($_SESSION['su_cat']);
					}
					?>		
		
				<div class = "main_container">
					<div class = "template_heading account_heading"><span class = "template_left">Category List</span> <span class="template_right"><a class="button round blue" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a></span></div> 
					<div class = "main">
						<div class = "main_left cat_mail_left">
							<ul>
								<li class = "li_bold">sr. no</li>
								<li class = "li_bold">Category name</li>
								<li class = "li_bold">No. of Email</li>
								<li></li>
							</ul>
							
								<?php
								
									$rs=mysql_query("select * from catalog");
			  
									$model = array();
									$index = 1;
								if(mysql_num_rows($rs))
								{	
									while ($company_list =  mysql_fetch_assoc($rs))
									{
										$cat_id = $company_list['id'];
										$user_id =  $_SESSION['userid'];
										
										
										
										if($_SESSION['type'] == 'admin')
										{	
											$cat_count_rs = mysql_query("select COUNT(cat_id) from email where find_in_set(".$cat_id.",cat_id) <> 0");
										}
										else
										{
											$cat_count_rs = mysql_query("select COUNT(cat_id) from email where cat_id = ".$cat_id." and user_id = ".$user_id);
										}		
										
										$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
										$total_count = $cat_count_list['COUNT(cat_id)'];
										?><ul>
											<li><?php echo $index; ?></li>
											<li><?php echo $company_list['cat_name']; ?></li>
											<li><?php echo $total_count; ?></li>
											<li>
												<a class="fa fa-chevron-down" onclick="showcat('<?php echo $cat_id; ?>')"	></a>
											<?php
												if($_SESSION['type'] == 'admin'):
													?><a class="fa fa-pencil" onclick="editCat('<?php echo $cat_id; ?>')"></a> <a class="fa fa-trash" onclick="deleteCat('<?php echo $cat_id; ?>')"></a>
													<?php endif; ?>
											 </li>
										</ul>
										<div class = "show_cat_imail show_cat_imail_<?php echo $cat_id; ?>" style = "display:none">
											<?php 
												if($_SESSION['type'] == 'admin')
												{	
													$cat_rs = mysql_query("select * from email where find_in_set(".$cat_id.",cat_id) <> 0");
												}
												else
												{
													$cat_rs = mysql_query("select * from email where cat_id = ".$cat_id." and user_id = ".$user_id);
												}
												$email_index = 1;
												
												$row_count = mysql_num_rows ( $cat_rs );
												if($row_count > 0)
												{
												?> 
													<ul>
														<li class = "email_li_bold">sr. no</li>
														<li class = "email_li_bold">Client Name</li>
														<li class = "email_li_bold">Client Email</li>
													</ul>
												<?php
												
													
													while ($cat_rs1 =  mysql_fetch_assoc($cat_rs))
													{
													?>
														<ul>
															<li><?php echo $email_index; ?></li>
															<li><?php echo $cat_rs1['name']; ?></li>
															<li><?php echo $cat_rs1['email']; ?></li>
														</ul>
													<?php
														$email_index++;
													}
												 }		
											?>
										</div>
										<?php
										$index++;
									}
								}
								else
								{
									echo '<div style = "float:left; width:100%; text-align:center; font-weight:bold">No Record Found.</div>';
								}
								?>
							</ul>
						</div>
						<?php
						if($_SESSION['type'] == 'admin')
						{  ?>
							<div class = "main_right">
								<div>
									<form>
										<div class = "company_info_field">	
											<div class = "cat_detail_lable">
												Add New Category
											</div>
											<div class = "cat_detail_lable_input">
												<input type="text" name="cat_name" id = "cat_name">
											</div>
										</div>
										<div>
											<img id ="cat_ajax_loder" src = "images/ajax-loader.gif" style ="display:none"/>
										</div>
										<div>
											<a id = "add_cat" class="button round blue" >Add Category</a>
										</div>
									</form>
								</div>
							</div>
						<?php
						} ?>
						
						
					</div>
				</div>	
			<?php
			}
			else
			{
				echo "user session expired";
				$_SESSION['error_cat'] = "User Session Expired.";
				header("Location:index.php");
			}	
		?>
		
		<script>
				jQuery(document).ready(function(){
					jQuery('#add_cat').click(function(){
						var cat_name = jQuery( ".cat_detail_lable_input #cat_name" ).val()
						if(cat_name == "")
						{
							jQuery( ".cat_detail_lable_input #cat_name").css("border","1px solid #f00");
						}
						else
						{	
							jQuery("#cat_ajax_loder").attr("style","display:block!important");
							var data = {'function':'SaveGroup','cat_name':cat_name};
							jQuery.ajax({
								url: 'controller/MasterController.php', 
								type: 'POST',
								data: data,
								success: function(result)
								{
									if(result == 0)
									{
										jQuery( ".cat_detail_lable_input #cat_name").css("border","1px solid #f00");
									}
									else
									{
										jQuery( ".cat_detail_lable_input #cat_name").css("border","1px solid #0F0");
										jQuery("#cat_ajax_loder").attr("style","display:none!important");
										setTimeout(function(){ location.reload();}, 1000);
									}		
								}
							});
						}		
					});		
				});	
				function deleteCat(cat_id)
				{
					jQuery.prompt("Are you sure you want to delete this item? It cannot be restored at a later time!", {
						title: "Delete Confirmation",
						buttons: { "Yes, I'm Ready": true, "No, Lets Wait": false },
						submit: function(e,v,m,f){
							if(v)
							{
								var data = {'function':'DeleteCat','id':cat_id};
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
				function editCat(cat_id)
				{
					location.href = "edit-category.php?id="+cat_id;
				} 
				var cat_id1 = 0;
				function showcat(cat_id)
				{
					//jQuery(".show_cat_imail").attr("style","display:none");
					//jQuery(".show_cat_imail.show_cat_imail_"+cat_id).attr("style","display:block");
					if (cat_id != cat_id1)
					{	
						jQuery(".show_cat_imail").attr("style","display:none");
						cat_id1 = cat_id;
					}	
					jQuery(".show_cat_imail.show_cat_imail_"+cat_id).toggle();
				} 
				
				
		</script>			
		<?php
			include("footer.php");
			
		?>
 
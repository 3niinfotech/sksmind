<?php
session_start();
?>
<html>
	<?php 
		include("head.php");
	?>	
	<body>
		<?php
			include("header.php");
			include("database.php");
			if (isset($_SESSION['login_user']))
			{
			?>
				<div class = "main_container">
					 <?php
					if (isset($_SESSION['su_cat']))
					{ ?>
						<div class = "success_div"><?php echo $_SESSION['su_cat']; ?></div>
					<?php
						unset($_SESSION['su_cat']);
					}
					?>	
					<?php
					if (isset($_SESSION['error_cat']))
					{ ?>
						<div class = "error_div success"><?php echo $_SESSION['error_cat']; ?></div>
					<?php
						unset($_SESSION['error_cat']);
					}
					
					?>	
					<div class = "template_heading"><span class = "template_left">User List</span><span class = "template_right"><a href = "new-user.php" class="button round blue" >Add New User</a></span></div> 
					<div class = "main">
						<div class = "main_user_left">
							<ul>
								<li class = "li_bold">User Name</li>
								<li class = "li_bold">Full Name</li>
								<li class = "li_bold">Company Name</li>
								<li class = "li_bold">Email</li>
								<li class = "li_bold">Mobile</li>
								<li></li>
							</ul>
							
								<?php
								
									$rs=mysql_query("select * from user where type != 'admin'");
			  
									$model = array();
									$index = 1;
									while ($company_list =  mysql_fetch_assoc($rs))
									{ 
										$user_id = $company_list['user_id'];
										?><ul>
											<li><?php echo $company_list['user_name'] ?></li>
											<li><?php echo $company_list['first_name']; ?></li>
											<li><?php echo $company_list['company_name']; ?></li>
											<li><?php echo $company_list['user_email']; ?></li>
											<li><?php echo $company_list['mobile']; ?></li>
											<li><a class="fa fa-picture-o" href = "user-statatic.php?id=<?php echo $user_id; ?>"></a><a class="fa fa-pencil" href = "edit-user_management.php?id=<?php echo $user_id; ?>"></a> <a class="fa fa-trash" onclick="deleteCat('<?php echo $company_list['user_id']; ?>')"></a>
											</li>
											</ul>
										<?php
										
										
									}
								?>
							</ul>
						</div>
						
					</div>
				</div>	
			<?php
			}
			else
			{
				echo "user session expired";
				header("Location: index.php");
			}	
		?>
		
		<script>
			function deleteCat(cat_id)
			{
				jQuery.prompt("Are you sure you want to delete this item? It cannot be restored at a later time!", {
					title: "Delete Confirmation",
					buttons: { "Yes, I'm Ready": true, "No, Lets Wait": false },
					submit: function(e,v,m,f){
						if(v)
						{
							var data = {'function':'DeleteUser','id':cat_id};
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
		</script>			
		<?php
			include("footer.php");
		?>

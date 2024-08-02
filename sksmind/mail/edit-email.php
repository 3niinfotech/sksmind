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
			?>
				<?php 
					
					$cat_count_rs = mysql_query("select * from email where id = ".$_GET['id']);
				    $cat_count_list =  mysql_fetch_assoc($cat_count_rs);
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
						<div class = "error_div success"><?php echo $_SESSION['error_cat']; ?> </div>
					<?php
						unset($_SESSION['error_cat']);
					}
					?>	
					<div class = "template_heading account_heading"><span class = "template_left">Edit Email Information</span> <span class="template_right"><a class="button round blue" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a></span></div> 
						<form method="post" action="controller/email-info.php">
							<div class = "main">
								<div class = "account_info_div">
								 <div class = "customer_drid">
										<label class="required" for="firstname"><em>*</em>Client Name</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="name" id = "name" placeholder="Client Name" required value="<?php echo $cat_count_list['name']; ?>">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname"><em>*</em>Email</label>
										<div class = "cat_detail_lable_input">
											<input type="email" name="email" id = "email" placeholder="Email" required value="<?php echo $cat_count_list['email']; ?>">
										</div>
									</div>	
										
									<div class = "customer_drid">
										<label class="required" for="firstname">Company Name</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="company_name" id = "company_name" placeholder="Company Name"  value="<?php echo $cat_count_list['company_name']; ?>">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname">Mobile No.</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="mo_no" id = "mo_no" placeholder="Mobile No"  value="<?php echo $cat_count_list['mo_no']; ?>">
											<input type="hidden" name="cod_mo_no" id = "cod_mo_no" value="">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname">Tel No.</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="company_no" id = "company_no" placeholder="Tel no"  value="<?php echo $cat_count_list['company_no']; ?>">
											<input type="hidden" name="cod_comp_no" id = "cod_comp_no" value="">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname">Fax no</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="fax_no" id = "fax_no" placeholder="Fax no"  value="<?php echo $cat_count_list['fax_no']; ?>">
											<input type="hidden" name="cod_fax_no" id = "cod_fax_no" value="">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname">Rapnet Id</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="rapnet_id" id = "rapnet_id" placeholder="Rapnet Id"  value="<?php echo $cat_count_list['rapnet_id']; ?>">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname">Skype Id</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="skype_id" id = "skype_id" placeholder="Skype Id"  value="<?php echo $cat_count_list['skype_id']; ?>">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname">Wechat Id</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="wechat_id" id = "wechat_id" placeholder="Wechat Id"  value="<?php echo $cat_count_list['wechat_id']; ?>">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										
										<label class="required" for="firstname">QQ Id</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="qq_id" id = "qq_id" placeholder="QQ Id"  value="<?php echo $cat_count_list['qq_id']; ?>">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname">Company Address</label>
										<div class = "cat_detail_lable_input">
											<textarea name="company_add" id = "company_add" placeholder="Address" ><?php echo $cat_count_list['company_add']; ?></textarea>
										</div>
									</div>	
									<div class = "customer_drid">
										<label class="required" for="firstname">Category</label>
										<div class = "cat_detail_lable_input">
											<select name="cat_id[]" id  = "cat_id" multiple="multiple">
												<option value="">Select Category </option>	
												<?php 
												$rs1=mysql_query("select * from catalog");
												if(mysql_num_rows($rs1))
												{
													while($row = mysql_fetch_array($rs1)){
												?>	 
												<?php 
														$cat_ids = explode(',', $cat_count_list['cat_id']);
														if(in_array($row['id'], $cat_ids))
														{ 
														 ?>
															<option value="<?php echo $row['id'] ?>" selected><?php echo $row['cat_name'] ?></option>
														<?php	   
														 }	
														else
														{	
														?>
															<option value="<?php echo $row['id'] ?>"><?php echo $row['cat_name'] ?></option>
															<?php	
														}
													}	 
												}
													?>
											</select>
										</div>
									</div>		
									<div class = "customer_drid">	
										<label class="required" for="firstname">Country</label>
											<div class = "cat_detail_lable_input">
												<select name="country_id" id  = "country_id" required>
													<option value="">Select Country </option>	
													<?php 
													
													
													$rs1=mysql_query("select * from country");
													if(mysql_num_rows($rs1))
													{
														while($row = mysql_fetch_array($rs1)){
													?>	 
														<?php 
														
														   if($row['id'] == $cat_count_list['country_id'])
														   { 
														   ?>
															   <option value="<?php echo $row['id'] ?>" selected><?php echo $row['country_name'] ?></option>
														   <?php	   
														   }	
															else
															{	
															?>
																<option value="<?php echo $row['id'] ?>"><?php echo $row['country_name'] ?></option>
															<?php	
															}
														}	 
													}
													?>
												</select>
											</div>
										</div>	
										<?php
											if($_SESSION['type'] == 'admin'):
											?>
												<div class = "customer_drid">
													<label class="required" for="firstname">Assign to </label>
													<div class = "cat_detail_lable_input">
														<select name="user_id" id  = "user_id" required>
															<option value="">Select User </option>	
															<?php 
															
															
															$rs1=mysql_query("select * from user");
															if(mysql_num_rows($rs1))
															{
																while($row = mysql_fetch_array($rs1)){
															?>	 
																<?php 
																
																   if($row['user_id'] == $cat_count_list['user_id'])
																   { 
																   ?>
																	   <option value="<?php echo $row['user_id'] ?>" selected><?php echo $row['user_name'] ?></option>
																   <?php	   
																   }	
																	else
																	{	
																	?>
																		<option value="<?php echo $row['user_id'] ?>"><?php echo $row['user_name'] ?></option>
																	<?php	
																	}
																}	 
															}
															?>
														</select>
													</div>
												</div>	
											<?php endif; ?>
									
								</div>
								<input type="hidden" name="email_id" value="<?php echo $_GET['id']; ?>">
								<div class="buttons-set">
								   <button class="button validation-passed save-account" title="Save" type="submit"><span><span>Save Change</span></span></button>
								</div>
							</div>
						</form>		
					</div>		
					<script>
						jQuery(document).ready(function(){
							jQuery("#mo_no").intlTelInput({
								separateDialCode: true,
								nationalMode: true,
								utilsScript: "js/utils.js",
							}); 
							jQuery("#mo_no").intlTelInput("setNumber", "<?php echo $cat_count_list['mo_no']; ?>");
						});	
						
						jQuery("#mo_no").on("countrychange", function(e, countryData) {
							jQuery('#cod_mo_no').val(countryData.dialCode);
						});
						
						jQuery(document).ready(function(){
							jQuery("#company_no").intlTelInput({
								separateDialCode: true,
								nationalMode: true,
								utilsScript: "js/utils.js"
							}); 
							
							jQuery("#company_no").intlTelInput("setNumber", "<?php echo $cat_count_list['company_no']; ?>");
							
							jQuery("#fax_no").intlTelInput({
								separateDialCode: true,
								nationalMode: true,
								utilsScript: "js/utils.js"
							}); 
							jQuery("#fax_no").intlTelInput("setNumber", "<?php echo $cat_count_list['fax_no']; ?>");
							
						});	
				
						jQuery("#company_no").on("countrychange", function(e, countryData) {
							jQuery('#cod_comp_no').val(countryData.dialCode);
						});
						
						jQuery("#fax_no").on("countrychange", function(e, countryData) {
							jQuery('#cod_fax_no').val(countryData.dialCode);
						});
						
						setTimeout(function(){ 
							var mo_no_auto = jQuery("#mo_no").intlTelInput("getSelectedCountryData");
							jQuery('#cod_mo_no').val(mo_no_auto.dialCode);
							
							var company_no_auto = jQuery("#company_no").intlTelInput("getSelectedCountryData");
							jQuery('#cod_comp_no').val(company_no_auto.dialCode);
							
							var cod_fax_no_auto = jQuery("#fax_no").intlTelInput("getSelectedCountryData");
							jQuery('#cod_fax_no').val(cod_fax_no_auto.dialCode);
						}, 1000);
				
					</script>	
			<?php
			}
			else
			{
				echo "user session expired";
				header('Location: index.php');
			}	
		?>		
		<?php
			include("footer.php");
		?>

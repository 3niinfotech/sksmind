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
			<?php
				$cat_count_rs = mysql_query("select * from user where user_id = ".$_SESSION['login_user']);
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
						<div class = "error_div success"><?php echo $_SESSION['error_cat']; ?></div>
					<?php
						unset($_SESSION['error_cat']);
					}
					?>	
					<div class = "template_heading account_heading"><span class = "template_left">Edit Account Information</span><span class="template_right"><a class="button round blue" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a></span></div> 
					
					<form method="post" action="login/account-info.php">
					<div class = "main">
						
					<div class = "account_info_div">
						<div class = "customer_drid">
							<label class="required" for="firstname"><em>*</em>User Name</label>
							<div class = "cat_detail_lable_input">
								<input type="text" name="user_f_name" id = "user_f_name" placeholder="User Name" required value="<?php echo $cat_count_list['user_name']; ?>">
							</div>
						</div>
						<div class = "customer_drid">						
							<label class="required" for="firstname"><em>*</em>Email</label>
							<div class = "cat_detail_lable_input">
								<input type="email" name="user_email" id = "user_email" placeholder="Email" required value="<?php echo $cat_count_list['user_email']; ?>">
							</div>
						</div>
						<div class = "customer_drid">
							<label class="required" for="firstname">First Name</label>
							<div class = "cat_detail_lable_input">
								<input type="text" name="first_name" id = "first_name" placeholder="First Name"  value="<?php echo $cat_count_list['first_name']; ?>">
							</div>
						</div>
						<div class = "customer_drid">
							<label class="required" for="firstname">Company Name</label>
							<div class = "cat_detail_lable_input">
								<input type="text" name="company_name" id = "company_name" placeholder="company_name"  value="<?php echo $cat_count_list['company_name']; ?>" maxlength="50">
							</div>
						</div>
						<div class = "customer_drid">
							<label class="required" for="firstname">Mobile Number</label>
							<div class = "cat_detail_lable_input">
								<input type="text" name="mobile" id = "mobile" placeholder="Mobile"  value="<?php echo $cat_count_list['mobile']; ?>" maxlength="15">
								<input type="hidden" name="cod_mo_no" id = "cod_mo_no" value="">
							</div>
						</div>
						<div class = "customer_drid">		

							<label class="required" for="firstname">Telephone No.</label>
							<div class = "cat_detail_lable_input">
								<input type="text" name="tel_no" id = "tel_no" placeholder="Telephone No"  value="<?php echo $cat_count_list['tel_no']; ?>" maxlength="50">
								<input type="hidden" name="cod_comp_no" id = "cod_comp_no" value="">
							</div>
						</div>
						<div class = "customer_drid">

							<label class="required" for="firstname">Fax No.</label>
							<div class = "cat_detail_lable_input">
								<input type="text" name="fax_no" id = "fax_no" placeholder="Fax No"  value="<?php echo $cat_count_list['fax_no']; ?>" maxlength="50">
								<input type="hidden" name="cod_fax_no" id = "cod_fax_no" value="">
							</div>
						</div>
						<div class = "customer_drid">
							<label class="required" for="firstname">Rapnet Id</label>
							<div class = "cat_detail_lable_input">
								<input type="text" name="rapnet_id" id = "rapnet_id" placeholder="Rapnet Id"  value="<?php echo $cat_count_list['rapnet_id']; ?>" maxlength="50">
							</div>
						</div>
						<div class = "customer_drid">
							<label class="required" for="firstname">Skype Id</label>
							<div class = "cat_detail_lable_input">
								<input type="text" name="skype_id" id = "skype_id" placeholder="Skype Id"  value="<?php echo $cat_count_list['skype_id']; ?>" maxlength="50">
							</div>
						</div>
						<div class = "customer_drid">
							<label class="required" for="firstname">Wechat Id</label>
							<div class = "cat_detail_lable_input">
								<input type="text" name="wechat_id" id = "wechat_id" placeholder="Wechat Id"  value="<?php echo $cat_count_list['wechat_id']; ?>" maxlength="50">
							</div>
						</div>
						<div class = "customer_drid">
							<label class="required" for="firstname">QQ Id</label>
							<div class = "cat_detail_lable_input">
								<input type="text" name="qq_id" id = "qq_id" placeholder="QQ Id"  value="<?php echo $cat_count_list['qq_id']; ?>" maxlength="50">
							</div>
						</div>
						<div class = "customer_drid">

							<label class="required" for="firstname">Address</label>
							<div class = "cat_detail_lable_input">
								<textarea name="address" id = "address" placeholder="Address" ><?php echo $cat_count_list['address']; ?></textarea>
							</div>
						</div>
						<div class = "customer_drid">
							<label class="required" for="firstname">Welcome Message</label>
							<div class = "cat_detail_lable_input">
								<textarea name="welcommess" id = "welcommess" placeholder="Welcome Message" ><?php echo $cat_count_list['welcommess']; ?></textarea>
							</div>
						</div>
						<div class = "customer_drid">
							<label class="required" for="firstname">Formality</label>
							<div class = "cat_detail_lable_input">
								<input type="text" name="formality" id = "formality" placeholder="Formality"  value="<?php echo $cat_count_list['formality']; ?>" maxlength="50">
							</div>
						</div>
					</div>
					<!-- <div class = "template_heading account_heading1"><span class = "template_left">Change Password</span></div>  -->
						<div class = "change_checkbox_pass">
							 <input type="checkbox" class="checkbox" title="Change Password" onclick="setPasswordForm(this.checked)" value="1" id="change_password" name="change_password"><label for="change_password">Change Password</label>
							</div>
							<div class = "account_info_div account_info_password" style = "display:none">
							
							<div class = "customer_drid">
								<label class="required" for="firstname"><em>*</em>Current Password</label>
								<div class = "cat_detail_lable_input">
									<input type="text" name="user_c_pass" id = "user_c_pass" placeholder="Current Password">
								</div>
							</div>
							<div class = "customer_drid">
								<label class="required" for="firstname"><em>*</em>New Password</label>
								<div class = "cat_detail_lable_input">
									<input type="text" name="user_new_pass" id = "user_new_pass" placeholder="New Password">
								</div>
							</div>
							</div>
							<div class="buttons-set">
							   <button class="button validation-passed save-account" title="Save" type="submit"><span><span>Save Change</span></span></button>
							</div>
						</div>
				</form>
				</div>	
			<?php
			}
			else
			{
				echo "user session expired";
			}	
		?>	
             <script>
				function setPasswordForm(arg){
					if(arg)
					{
						jQuery(".account_info_div.account_info_password").attr("style","display:block!important");
                        jQuery(".account_info_div.account_info_password input").prop('required',true);
					}
					else
					{
						jQuery(".account_info_div.account_info_password").attr("style","display:none!important");
						jQuery(".account_info_div.account_info_password input").prop('required',false);
					}	
				}
				
				jQuery(document).ready(function(){
					jQuery("#mobile").intlTelInput({
						separateDialCode: true,
						nationalMode: true,
						utilsScript: "js/utils.js",
					}); 
					jQuery("#mobile").intlTelInput("setNumber", "<?php echo $cat_count_list['mobile']; ?>");
					
					
					jQuery("#tel_no").intlTelInput({
						separateDialCode: true,
						nationalMode: true,
						utilsScript: "js/utils.js"
					}); 
					jQuery("#tel_no").intlTelInput("setNumber", "<?php echo $cat_count_list['tel_no']; ?>");
					
					jQuery("#fax_no").intlTelInput({
						separateDialCode: true,
						nationalMode: true,
						utilsScript: "js/utils.js"
					}); 
					jQuery("#fax_no").intlTelInput("setNumber", "<?php echo $cat_count_list['fax_no']; ?>");
				});

				jQuery("#mobile").on("countrychange", function(e, countryData) {
					jQuery('#cod_mo_no').val(countryData.dialCode);
				});
						
				jQuery("#tel_no").on("countrychange", function(e, countryData) {
					jQuery('#cod_comp_no').val(countryData.dialCode);
				});
					
				jQuery("#fax_no").on("countrychange", function(e, countryData) {
					jQuery('#cod_fax_no').val(countryData.dialCode);
				});			
						
				setTimeout(function(){ 
					var mo_no_auto = jQuery("#mobile").intlTelInput("getSelectedCountryData");
					jQuery('#cod_mo_no').val(mo_no_auto.dialCode);
							
					var company_no_auto = jQuery("#tel_no").intlTelInput("getSelectedCountryData");
					jQuery('#cod_comp_no').val(company_no_auto.dialCode);
					
					var cod_fax_no_auto = jQuery("#fax_no").intlTelInput("getSelectedCountryData");
					jQuery('#cod_fax_no').val(cod_fax_no_auto.dialCode);
					
				}, 1000);	
				
		      </script>	
		<?php
			include("footer.php");
		?>

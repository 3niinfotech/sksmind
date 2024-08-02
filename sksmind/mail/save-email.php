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
						<div class = "template_heading account_heading"><span class = "template_left">Add Email Information</span><span class="template_right"><a class="button round blue" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a></span></div> 
						<form method="post" action="controller/save-email-info.php">
							<div class = "main">
								<div class = "account_info_div">
								
									<div class = "customer_drid">
										<label class="required" for="firstname"><em>*</em>Client Name</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="name" id = "name" placeholder="Client Name" required value="">
										</div>
									</div>		
									<div class = "customer_drid customer_drid_odd">		
										<label class="required" for="firstname"><em>*</em>Email</label>
										<div class = "cat_detail_lable_input">
											<input type="email" name="email" id = "email" placeholder="Email" required value="">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname">Company Name</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="company_name" id = "company_name" placeholder="Company Name"  value="">
										</div>
									</div>		
									<div class = "customer_drid customer_drid_odd">
										<label class="required" for="firstname">Mobile No.</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="mo_no" id = "mo_no" placeholder="Mobile No"  value="">
											<input type="hidden" name="cod_mo_no" id = "cod_mo_no" value="">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname">Tel no.</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="company_no" id = "company_no" placeholder="Tel no"  value="">
											<input type="hidden" name="cod_comp_no" id = "cod_comp_no" value="">
										</div>
									</div>		
									<div class = "customer_drid customer_drid_odd">	
										<label class="required" for="firstname">Fax no</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="fax_no" id = "fax_no" placeholder="Fax no"  value="">
											<input type="hidden" name="cod_fax_no" id = "cod_fax_no" value="">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname">Rapnet Id</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="rapnet_id" id = "rapnet_id" placeholder="Rapnet Id"  value="">
										</div>
									</div>		
									<div class = "customer_drid customer_drid_odd">	
										<label class="required" for="firstname">Skype Id</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="skype_id" id = "skype_id" placeholder="Skype Id"  value="">
										</div>
									</div>	
										
									<div class = "customer_drid">	
										<label class="required" for="firstname">Wechat Id</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="wechat_id" id = "wechat_id" placeholder="Wechat Id"  value="">
										</div>
									</div>		
									<div class = "customer_drid customer_drid_odd">	
										
										<label class="required" for="firstname">QQ Id</label>
										<div class = "cat_detail_lable_input">
											<input type="text" name="qq_id" id = "qq_id" placeholder="QQ Id"  value="">
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
															<option value="<?php echo $row['id'] ?>"><?php echo $row['country_name'] ?></option>
														<?php	
															
														}	 
													}
													?>
												</select>
											</div>
										</div>		
										<div class = "customer_drid customer_drid_odd">
											<label class="required" for="firstname">Category</label>
											<div class = "cat_detail_lable_input">
												<select name="cat_id[]" id  = "cat_id" multiple="multiple" required>
													<option value="">Select Category </option>	
													<?php 
													$rs1=mysql_query("select * from catalog");
													if(mysql_num_rows($rs1))
													{
														while($row = mysql_fetch_array($rs1)){
													?>	 
														<option value="<?php echo $row['id'] ?>"><?php echo $row['cat_name'] ?></option>
														<?php	
															
														}	 
													}
													?>
												</select>
											</div>
										</div>	
										<div class = "customer_drid caddress">	
											<label class="required" for="firstname">Company Address</label>
											<div class = "cat_detail_lable_input">
												<textarea name="company_add" id = "company_add" placeholder="Address" ></textarea>
											</div>
										</div>	
									
								</div>
								<?php $id = (isset($_GET['id'])) ? $_GET['id'] : ''; ?>
								<input type="hidden" name="email_id" value="<?php echo $id; ?>">
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
				header('Location: index.php');
			}	
		?>
		
		<script>
				jQuery(document).ready(function(){
					jQuery("#mo_no").intlTelInput({
						separateDialCode: true,
						nationalMode: true,
						utilsScript: "js/utils.js"
					}); 
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
					
					jQuery("#fax_no").intlTelInput({
						separateDialCode: true,
						nationalMode: true,
						utilsScript: "js/utils.js"
					}); 
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
			include("footer.php");
		?>

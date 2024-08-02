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
	<script data-require="angular.js@1.3.9" data-semver="1.3.9" src="js/angular.js"></script>
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
					<div class = "template_heading account_heading"><span class = "template_left">Send Quick Mail</span><span class="template_right" style = "margin: 0 10px;"><a class="button round blue" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a></span></div> 
					
					<form method="post" action="cron/send-quick-mail.php">
						<div class = "main">
						<div class = "account_info_div" ng-app="app" ng-controller="ctrl">
								<div class = "customer_drid">	
									<label class="required" for="firstname"><em>*</em>To</label>
									<div class = "cat_detail_lable_input">
										<input ng-keyup="complete()" type="email" name="email_to" id = "email_to" placeholder="Email" required >
									</div>
								</div>	
								<div class = "customer_drid customer_drid_right">
									<label class="required" for="firstname">Subject</label>
									<div class = "cat_detail_lable_input">
										<input type="text" name="subject" id = "subject" placeholder="Subject" required>
									</div>
								</div>	
								<div class = "customer_drid customer_drid_checkbox"> 
									<input type="checkbox" class="checkbox" title="Send Template" onclick="setSendTemplate(this.checked)" value="1" id="send_template" name="send_template"><label for="send_template">Send template</label>
								</div>
								<div class = "customer_drid customer_drid_template_select" style = "display:none">	
									<select name="template_id" id  = "template_id">
										<option value="-1">Select Template </option>	
										<?php 
											$template_count_rs = mysql_query("select * from template");
											if(mysql_num_rows($template_count_rs))
											{
												while($row = mysql_fetch_array($template_count_rs)){
											?>	 
												<option value="<?php echo $row['id'] ?>"><?php echo $row['title'] ?></option>
											<?php	
												}	 
											}
											?>
									</select>
								</div>
								<div class = "customer_drid customer_drid_hundred customer_drid_message">
									<label class="required" for="firstname">Message</label>
									<div class = "cat_detail_lable_input">
										<textarea name="email_message" id = "email_message" placeholder="Message" required></textarea>
									</div>
								</div>	
								<div class = "customer_drid customer_drid_sendmail">	
									<?php if($_SESSION['type'] != 'admin'): ?>
										<div class = "check_box_mail">
											<input class = "main_send" type="checkbox" name="sendmail" value="yes">Send Mail as admin</input>
										</div>
									<?php endif; ?>	
								</div>
							</div>
							<div class="buttons-set">
							   <button class="button validation-passed save-account" title="Save" type="submit"><span><span>Send</span></span></button>
							</div>
						</div>
					</form>
				</div>	
				
				<?php
					/* 	$user_id =  $_SESSION['userid'];
						if($_SESSION['type'] == 'admin')
						{	
							$rs_email = mysql_query("select email from email order by id ASC");
						}
						else
						{
							$rs_email = mysql_query("select email from email where user_id= ".$user_id);
						}		
						$customer_grid = array();
						while ($customer_grid_list =  mysql_fetch_assoc($rs_email))
						{
							$customer_grid[] = $customer_grid_list['email'];
						}
									
					echo count($customer_grid);	
					print_r($customer_grid); */
					?>
				
				<script>
					function setSendTemplate(arg){
						if(arg)
						{
							jQuery(".customer_drid.customer_drid_template_select").attr("style","display:block!important");
							jQuery(".customer_drid.customer_drid_template_select .template_id").prop('required',true);
							jQuery(".customer_drid.customer_drid_hundred.customer_drid_message").attr("style","display:none!important");
							jQuery(".customer_drid.customer_drid_hundred.customer_drid_message textarea").prop('required',false);
						}
						else
						{
							jQuery(".customer_drid.customer_drid_template_select").attr("style","display:none!important");
							jQuery(".customer_drid.customer_drid_template_select .template_id").prop('required',false);
							jQuery(".customer_drid.customer_drid_hundred.customer_drid_message").attr("style","display:block!important");
							jQuery(".customer_drid.customer_drid_hundred.customer_drid_message textarea").prop('required',true);
						}	
					}
					
						var app=angular.module('app',[]);
						 app.controller('ctrl',function($scope){
						 $scope.availableTags = [
							  "ActionScript",
							  "AppleScript",
							  "Asp",
							  "BASIC",
							  "C",
							  "C++",
							  "Clojure",
							  "COBOL",
							  "ColdFusion",
							  "Erlang",
							  "Fortran",
							  "Groovy",
							  "Haskell",
							  "Java",
							  "JavaScript",
							  "Lisp",
							  "Perl",
							  "PHP",
							  "Python",
							  "Ruby",
							  "Scala",
							  "Scheme"
							];
							$scope.complete=function(){
							  console.log($scope.availableTags);
							$( "#email_to" ).autocomplete({
							  source: $scope.availableTags
							});
							} 
						});
					
				</script>	
				
			<?php
			}
			else
			{
				echo "user session expired";
				header("Location: index.php");
			}	
		?>	
		<?php
			include("footer.php");
		?>		
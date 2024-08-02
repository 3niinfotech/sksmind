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
						<div class = "error_div success" style = "height:auto"><?php echo $_SESSION['error_cat']; ?></div>
					<?php
						unset($_SESSION['error_cat']);
					}
					?>	
					<div class = "template_heading account_heading"><span class = "template_left">Import Category or Email</span><span class="template_right"><a class="button round blue" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a></span></div> 
					<div class = "main">
						 <form id="sendemail" method="POST" action="importdata.php" enctype="multipart/form-data">   
								<div class = "company_info_detail_input import_export">
									<lable>Select Import Type : </lable>
									<select name="type" id  = "type" required>
										<option value="">Select type </option>	
										
										<option value="category">Category</option>
										<option value="email">Customer</option>
										
									</select>
									<input type="file" name="file" id="file" />
								</div>	
								<div class = "submit_button_div">
									<button class = "submit_button" type="submit">Import</button>
								</div>	
						 </form>   
					</div>
				</div>	
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

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
						<div class = "error_div success">Something may be wrong </div>
					<?php
						unset($_SESSION['error_cat']);
					}
					?>	
					<div class = "template_heading account_heading"><span class = "template_left">Export Customer or Category</span><span class="template_right"><a class="button round blue" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a></span></div> 
					<div class = "main">
						 <form id="sendemail" method="POST" action="exportdata.php" enctype="multipart/form-data">   
								<div class = "company_info_detail_input import_export">
									<lable>Select Export Type : </lable>
									<select name="type" id  = "type" required>
										<option value="">Select type </option>	
										
										<option value="category">Category</option>
										<option value="customer">Customer</option>
										
									</select>
								</div>	
								<div class = "submit_button_div">
									<button class = "submit_button" type="submit">Export</button>
								</div>	
						 </form>   
					</div>
				</div>	
			<?php
			}
			else
			{
				header('Location: index.php');
				echo "user session expired";
			}	
		?>	
		<?php
			include("footer.php");
		?>
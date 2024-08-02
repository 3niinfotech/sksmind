<?php
session_start();
require_once "cuteeditor_files/include_CuteEditor.php";
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
					<div class = "main">
						<div class = "template_heading"><span class = "template_left">Template List</span></div> 
						
						<div class = "template_view">
							<ul>
								<?php 
									$rs1=mysql_query("select * from template");
									if(mysql_num_rows($rs1))
									{
										while($row = mysql_fetch_array($rs1)){
										?>	 
											<li> 
												<div class = "template_content"><?php echo $row['template'] ?></div>
												<div class = "template_name"><a href = "sendmail.php?id=<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></div>
											</li>
										<?php	
										}	 
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
			}	
		?>		
		<?php
			include("footer.php");
		?>

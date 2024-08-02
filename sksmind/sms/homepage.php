<html>
	<?php 
		include("../database.php");
		include("../variable.php");
		if (!isset($_SESSION['username']))
		{
			header("Location: ".$mainUrl);
		}
		include("head.php");
		
		
		
	?>	
	<body>
		<?php
			include("header.php");
		?>
		<div class="company-name"><img src="images/kapu-logo.png" /><br/><br/> &nbsp;&nbsp;&nbsp;Kapu Gems Management System</div>
		
	
		<?php
			include("footer.php");
		?>
	</body>
</html>



	
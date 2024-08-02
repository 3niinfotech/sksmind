<?php
session_start();
//require_once "cuteeditor_files/include_CuteEditor.php";
?>
<html>
<head>
	<title>Shree International (HK) Ltd.</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="css/template.css" rel="stylesheet" type="text/css">
	<link href="css/font-awesome.css" rel="stylesheet" type="text/css">
		<script src="js/jquery.min.js"></script>
</head>

	<?php 
		//include("head.php");
	?>	
	<body>
		<?php
			//include("header.php");
			include("database.php");
			if (isset($_SESSION['login_user']))
			{
			?>
				<div class = "main_container">
                                       <?php
					if (isset($_SESSION['su_cat']))
					{ ?>
						<div class = "success_div" style = "background: #008000 none repeat scroll 0 0;color: #fff;
  font-size: 15px !important;height: 40px;padding-left: 2%; padding-top: 15px; width: 98%;"><?php echo $_SESSION['su_cat']; ?></div>
					<?php
						unset($_SESSION['su_cat']);
					}
					?>	
					<?php
					if (isset($_SESSION['error_cat']))
					{ ?>
						<div class = "error_div success" style = "background: #f00 none repeat scroll 0 0;color: #fff;
  font-size: 15px !important; height: 40px;  padding-left: 2%;  padding-top: 15px;  width: 98%;"><?php echo $_SESSION['error_cat']; ?></div>
					<?php
						unset($_SESSION['error_cat']);
					}
					
						$template_id = $_GET['id'];
						$rs1=mysql_query("select * from template where id = ".$template_id);
						$template_content =  mysql_fetch_assoc($rs1);
						$template_name = $template_content['title'];
					?>	
					<div class = "main">
						<div class = "template_heading"><span class = "template_left">Template - <?php echo $template_name; ?></span><span class = "template_right"><a href = "dashbord.php" class="button round blue btn-left t-blue" >Dashboard</a><a href = "template.php" class="button round blue btn-left t-orange" >Template List</a><a href = "edittemplate.php?id=<?php echo $template_id ?>"class="button round blue btn-left t-green">Edit</a><a href = "sendmail.php?id=<?php echo $template_id ?>"class="button round blue btn-left">Send</a><a href = "newtemplate.php" class="button round blue" >Add New Template</a></span></div> 
						 
						<div class = "template_view" style = "width:100%">
							<div class = "template_content1"><?php echo $template_content['template'] ?></div>
						</div>
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
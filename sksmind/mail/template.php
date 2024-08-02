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
<head>
	<title>Shree International (HK) Ltd.</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="css/template.css" rel="stylesheet" type="text/css">
	<link href="css/font-awesome.css" rel="stylesheet" type="text/css">
		<script src="js/jquery.min.js"></script>
		<link href="css/jquery-impromptu.css" rel="stylesheet">
		<script src="js/jquery-impromptu.js"></script>
</head>

	<?php 
		//include("head.php");
	?>	
	<body>
		<?php
			//include("header.php");
			
			if (isset($_SESSION['userid']))
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
					
					?>	
					
					<div class = "main">
						<div class = "template_heading"><span class = "template_left">Template List</span><span class = "template_right"><a href = "dashbord.php" class="button round blue btn-left" >Dashboard</a><a href = "newtemplate.php" class="button round blue" >Add New Template</a></span></div> 
						
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
												<div class = "template_name">
												<a href = "edittemplate.php?id=<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></div>
												
												<div class="tempate-action" style="display:none">
													<a href = "template-view.php?id=<?php echo $row['id'] ?>" class="btn-action t-orange fa fa-eye"></a>
													<a href = "edittemplate.php?id=<?php echo $row['id'] ?>" class="btn-action t-blue fa fa-pencil"></a>
													<a class="btn-action t-red fa fa-trash" onclick="deleteTemplate('<?php echo $row['id'] ?>')" ></a>
													<a href = "sendmail.php?id=<?php echo $row['id'] ?>"class="btn-action t-green fa fa-send"></a>
												</div>
												
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
				header('Location: index.php');
			}	
		?>
		
		<script>
							
				function deleteTemplate(cat_id)
				{
					jQuery.prompt("Are you sure you want to delete this item? It cannot be restored at a later time!", {
						title: "Delete Confirmation",
						buttons: { "Yes, I'm Ready": true, "No, Lets Wait": false },
						submit: function(e,v,m,f){
							if(v)
							{
								var data = {'function':'DeleteTemplate','id':cat_id};
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
			//include("footer.php");
		?>
<style>

</style>
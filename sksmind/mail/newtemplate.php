<?php
session_start();
?>
<html>
	<?php 
		include("head.php");
	?>	
	<script src="ckeditor/ckeditor.js"></script>
	<script src="ckeditor/samples/js/sample.js"></script>
	<link rel="stylesheet" href="ckeditor/samples/css/samples.css">
	<link rel="stylesheet" href="ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
	<body>
		<?php
			include("header.php");
			include("database.php");
			if (isset($_SESSION['login_user']))
			{
			
			?>
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
				<div style = "margin-top:20px;" class = "template_heading"><span class = "template_left">Add New Template</span><span class="template_right" style = "margin: 0 10px;"><a class="button round blue" href="template.php">Back</a><a  style = "margin-left:8px" title="Save" class="button round btn-left save-template">Save</a></span></div>
				
				
				<div class = "main_container" style = "padding-bottom:80px">
					<div class = "main template_list">
						<div class = "div_button_set">
							<input type="text" name="template_title" class = "template_title" id = "template_title" placeholder = "Template Title" required>
						</div>	
						<div class = "template_list template_list_onepage">
							<div id="editor">
								
							</div>	
						</div>
					</div>
					<div class = "template_error_msg" style = "display:none"><p>Enter a Tamplate Name and content</p></div>
				</div>	
			<?php
			}
			else
			{
				echo "user session expired";
			}	
		?>	
		<script>
			initSample();
			jQuery(document).ready(function(){
				jQuery('.button.round.btn-left.save-template').click(function(){
					
					var templateTitle = jQuery('#template_title').val();
					var templateData = CKEDITOR.instances['editor'].getData();
					var ok = 1;	
					if(templateTitle == "")
					{
						jQuery('#template_title').attr("style","border:1px solid #f00");
						ok = 0;
						jQuery('.template_error_msg').attr("style","display:block");
					}
					else
					{
						jQuery('#template_title').attr("style","border:1px solid #27ae60");
					}	
					if(templateData == "")
					{
						ok = 0;
						jQuery('.template_error_msg').attr("style","display:block");
					}
					
					if(ok == 1)
					{
						jQuery('.template_error_msg').attr("style","display:none");
							jQuery.ajax({
								url: "save-new-template.php", 
								type: 'POST',
								data: {templateTitle : templateTitle,templateData : templateData} ,
								success: function(result)
								{
									if(result)
									{	
										window.location.href = "template.php";
									}
									else
									{
										location.reload();
									}		
								}
						});	 
					}
				});	
			});
		</script>	
		<?php
			include("footer.php");
		?>
		
<style>
.div_button_set {
  float: left;
  width: 100%;
  padding: 10px 0;
}
	.template_list.template_list_onepage{width:100%; float:left;}
	.button.validation-passed.save-account {
  float: right;
}
.main.template_list{padding-top:0!important}
#template_title {
  float: left;
  max-width: 500px;
  width: 80%;
}
.btn-left {
  background: none repeat scroll 0 0 #27ae60;
  color:#fff!important
}
.template_error_msg > p {
  border-bottom: 1px solid #f00;
  color: #f00;
  font-size: 12px;
  font-weight: bold;
  padding: 30px 0 5px;
  text-transform: uppercase;
}
.template_error_msg {
  color: #f00 !important;
  float: left;
  text-align: center;
  width: 100%;
}
</style>		
		
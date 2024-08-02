<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<?php include("head.php"); 
$utype = $_SESSION['type'];
include("../variable.php");
if (!isset($_SESSION['username']))
{
	header("Location: ".$mainUrl);
}

if($utype =="user")
{
		header("Location: index.php");
}

?>	
<body>
	<?php
		include("header.php");
		include("../database.php");
		 
	?>
	<?php
		if (isset($_SESSION['success']))
		{ ?>
			<div class = "success_div"><?php echo $_SESSION['success']; ?></div>
		<?php
			unset($_SESSION['success']);
		}
		?>	
		<?php
		if (isset($_SESSION['error']))
		{ ?>
			<div class = "error_div success"><?php echo $_SESSION['error']; ?></div>
		<?php
			unset($_SESSION['error']);
		}
		?>	
	<div class="main-container">
			<div class="entry-group" >
					<div class="form-style-6">
				<div class="master-title">Add New User</div>
				<form method="post" action="saveUser.php">
					
					<input type="text" name="username" placeholder="Username" />
					<input type="password" name="password" placeholder="Password" />
					<input type="text" name="fname" placeholder="First Name" />
					<input type="text" name="lname" placeholder="Last Name" />
					<input type="text" name="email" placeholder="Email" />
					<input type="text" name="mobile" placeholder="Mobile" />					
					<textarea name="address" placeholder="Address"></textarea>
					<input type="submit" value="Add User" />					
					
				</form>
			</div>
		</div>
		<div class="datagrid">
			<?php $rs=mysql_query("SELECT * from user");
			  
						$model = array();
						$index = 1;
						$grid_data = array();
						while ($row =  mysql_fetch_assoc($rs))
						{
							
							$temp = array(
							'id'=> $row['id'],
							'username'=> $row['user_name'],
							'email'=> $row['e_mail'],
							'fname'=> $row['firstname'],
							'lname'=> $row['lastname'],
							'address'=> $row['address'],							
							'mobile'=> $row['mobile']						
							);
							$grid_data[] = $temp; 
							
						}
						$customer_json = json_encode($grid_data);						
						
				?>
			<div class="external-title">User List</div>	
			<table class="table table-striped"></table>
		</div>	
<?php include("footer.php"); ?>
</body>
<script>
	jQuery('#itemlist').on('change', function() {
	getItemStock( this.value ); // or $(this).val()
	});
	jQuery(function(jQuery){
		
		jQuery('.table').footable({
			"paging": {
				"enabled": true
			},
			"filtering": {
				"enabled": true
			},
			"sorting": {
				"enabled": true
			},
			"columns": jQuery.get("customer/userColumns.json"),
			"rows": <?php echo $customer_json; ?>//jQuery.get("customer/rows.json")
		});
	});
	function getItemStock(i_id)
	{
		jQuery('.is-message').hide();
		var data = {'function':'getStock','id':i_id};
		jQuery.ajax({
			url: 'controller/ItemController.php', 
			type: 'POST',
			data: data,
			success: function(result)
				{				
					if(result != 1)
					{
						var obj = jQuery.parseJSON(result );						
						jQuery('.is-message').show();
						jQuery('.is-message').html(obj.s);
						jQuery('#i-price').val(obj.p);
						
					}
				}
		});	
	}
</script>
</html> 
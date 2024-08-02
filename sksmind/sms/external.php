<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<?php include("head.php");
include("../variable.php");
if (!isset($_SESSION['username']))
{
	header("Location: ".$mainUrl);
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
				<div class="master-title">External Entry</div>
				<form method="post" action="saveExternal.php">
					
					<select class="" name="vendor" >
						<option value="0" selected="selected">Select Vendor</option>						
						<?php $rs=mysql_query("select * from sms_vendor order by name");
			  
						$model = array();
						$index = 1;
						while ($company_list =  mysql_fetch_assoc($rs))
						{
							$name = $company_list['name'];
							$id = $company_list['id'];
							?>
							<option value="<?php echo $id?>" ><?php echo $name; ?></option>							
						<?php }	?>
					</select>
					<select class="" name="item" >
						<option value="0" selected="selected">Select Item</option>						
						<?php $rs=mysql_query("select * from sms_item order by name");
			  
						$model = array();
						$index = 1;
						while ($company_list =  mysql_fetch_assoc($rs))
						{
							$name = $company_list['name'];
							$id = $company_list['id'];
							?>
							<option value="<?php echo $id?>" ><?php echo $name; ?></option>							
						<?php }	?>
					</select>
					
					<input type="text" name="qty" placeholder="Enter Quantity" />
					<input type="text" name="price" placeholder="Enter Price" />
					<input type="text" name="chalan" placeholder="Enter Chalan No." />
					<textarea name="remark" placeholder="Remark"></textarea>
					<input type="submit" value="Add Entry" />					
					
				</form>
			</div>
		</div>
		<div class="datagrid">
			<?php $rs=mysql_query("SELECT e.id,qty,price,stock,chalan,date,v.name vname,i.name iname FROM sms_external e LEFT JOIN sms_vendor v ON e.vendor = v.id LEFT JOIN sms_item i ON e.item = i.id ORDER BY e.id DESC ");
			  
						$model = array();
						$index = 1;
						$grid_data = array();
						while ($row =  mysql_fetch_assoc($rs))
						{
							
							$temp = array(
							'id'=> $row['id'],
							'vendor'=> $row['vname'],
							'item'=> $row['iname'],
							'qty'=> $row['qty'],
							'price'=> $row['price'],
							'chalan'=> $row['chalan'],
							'stock'=> $row['stock'],
							'date'=> $row['date'],
							);
							$grid_data[] = $temp; 
							
						}
						$customer_json = json_encode($grid_data);
						
				?>
			<div class="external-title">External Entry</div>	
			<table class="table table-striped"></table>
		</div>	
<?php include("footer.php"); ?>
</body>
<script>
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
			"columns": jQuery.get("customer/externalColumns.json"),
			"rows": <?php echo $customer_json; ?>//jQuery.get("customer/rows.json")
		});
	});
</script>
</html> 
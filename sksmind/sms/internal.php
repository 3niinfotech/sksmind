<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<?php include("head.php"); 
include("../variable.php");
if (!isset($_SESSION['username']))
{
	header("Location: ".$mainUrl);
}?>	
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
				<div class="master-title">Internal Entry</div>
				<form method="post" action="saveInternal.php">
					
					<select class="" name="department" >
						<option value="0" selected="selected">Select Department</option>						
						<?php $rs=mysql_query("select * from sms_department order by name");
			  
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
					<select class="" name="person" >
						<option value="0" selected="selected">Select Person</option>						
						<?php $rs=mysql_query("select * from sms_person order by name");
			  
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
					
					<select class="" name="item" id="itemlist">
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
					<p style="display:none" class="is-message"></p>
					<input type="text" name="qty" placeholder="Quantity" />
					<input type="text" name="price" id="i-price" placeholder="Enter Price per/pc" />					
					<textarea name="remark" placeholder="Remark"></textarea>
					<input type="submit" value="Add Entry" />					
					
				</form>
			</div>
		</div>
		<div class="datagrid">
			<?php $rs=mysql_query("SELECT e.id,qty,price,inhouse,date,d.name dname,p.name pname, i.name iname FROM sms_internal e LEFT JOIN sms_department d ON e.department = d.id LEFT JOIN sms_item i ON e.item = i.id LEFT JOIN sms_person p ON e.person = p.id ORDER BY e.id DESC ");
			  
						$model = array();
						$index = 1;
						$grid_data = array();
						while ($row =  mysql_fetch_assoc($rs))
						{
							
							$temp = array(
							'id'=> $row['id'],
							'department'=> $row['dname'],
							'person'=> $row['pname'],
							'item'=> $row['iname'],
							'qty'=> $row['qty'],
							'price'=> $row['price'],							
							'inhouse'=> $row['inhouse'],
							'date'=> $row['date'],
							);
							$grid_data[] = $temp; 
							
						}
						$customer_json = json_encode($grid_data);						
						
				?>
			<div class="external-title">Internal Entry</div>	
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
			"columns": jQuery.get("customer/internalColumns.json"),
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
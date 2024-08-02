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
	
		<div class="report-left" >
			<div class="form-style-6">
			<ul class="">
				<li>
					<a href = "report.php?rp=item">Item</a>
				</li>
				<li>
					<a href = "report.php?rp=person">Person</a>
				</li>
				<li>
					<a href = "report.php?rp=department">Department</a>
				</li>
				<li>
					<a href = "report.php?rp=vendor">Vendor</a>
				</li>
				<li >
					<a href = "report.php?rp=stock">Stock</a>				
				</li> 
			</ul>
			</div>
		</div>
		<div class="report-right">
			
			<?php $rp = $_GET['rp'];
			$Cname ="item";	
			?>
			
			<?php 
			if(isset($rp) && $rp !="stock" ): 
			$Cname = ucfirst($rp); 
			?>
			
			<div class="report-head">
				<span> Show Your <?php echo $Cname ?> Report :</span>
				<select class="" name="item" id="<?php echo $rp ?>list">
					<option value="-1" selected="selected">Select <?php echo $Cname ?></option>
					<option value="0">All <?php echo $Cname ?></option>					
					<?php $rs=mysql_query("select * from sms_".$rp." order by name");
		  
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
			</div>
			<?php endif; ?>
			
			<div class="report-content">	
				
			</div>
			<div class="report-grid" >				
				<table class="table table-striped"></table>
			</div>
			
			
		</div>	
<?php include("footer.php"); ?>
</body>
<script>
	jQuery('#<?php echo $rp ?>list').on('change', function() {
		jQuery('.report-content').html('<p class="please-wait"><img src="images/loading.gif" class="loading-image"><br/><span>Please Wait...</span></p>');
		jQuery('.table').html('');
		getItemData(this.value, 'get<?php echo $Cname ?>Data','<?php echo $rp ?>'); // or $(this).val()
	});
	
	function getItemData(i_id,func,col)
	{
	
		var data = {'function':func,'id':i_id};
		jQuery.ajax({
			url: 'controller/ItemController.php', 
			type: 'POST',
			data: data,
			success: function(result)
				{				
					if(result != 1)
					{
						if(result != "No Data Found")
						{
							var obj = jQuery.parseJSON(result);						
							jQuery('.is-message').show();
							jQuery('.report-content').html(obj.d);
							//jQuery('#i-price').val(obj.p);
							loadGrid(obj.g,col);
						}
						else
						{
							jQuery('.report-content').html('<p>No Data Found</p>');
						}
						
					}
				}
		});	
	}
	<?php if(isset($rp) && $rp =="stock" ):
		$sql = mysql_query("select e.id, i.name iname, stock from sms_external e LEFT JOIN sms_item i ON e.item = i.id");
			$dataR = array();
			$i=1;
			while ($row = mysql_fetch_assoc($sql))
			{
				$temp['id'] = $i;
				$temp['item'] = $row['iname'];
				$temp['stock'] = $row['stock'];
				$dataR[] = $temp;
				$i++;
				
			}

	?>
	
	loadGrid('<?php echo json_encode($dataR); ?>','stock');
	<?php endif;?>
	
	function loadGrid(gridData,col)
	{
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
			"columns": jQuery.get("customer/"+col+"Columns.json"),
			"rows": jQuery.parseJSON(gridData)//jQuery.get("customer/rows.json")
		});
	}
	
</script>

</html> 
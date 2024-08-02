 <?php
 session_start();
 include("../database.php");

	$error = $stockData = 0;
	if(empty($_POST["vendor"]) || empty($_POST["item"]) || empty($_POST["qty"]) || empty($_POST["price"]) || empty($_POST["chalan"]) )
	{
		$_SESSION['error'] = " Value can't be Blank.";
	}	
	else	
	{	
		$vendor = $_POST["vendor"];	
		$item = $_POST["item"];		
		$qty = $_POST["qty"];		
		$price = $_POST["price"];		
		$remark = $_POST["remark"];		
		$chalan = $_POST["chalan"];
		$date = date("Y-m-d H:i:s");
		$stock = $qty;
		
		$sql = "SELECT * FROM sms_external WHERE item ='$item'";

		$result = mysql_query($sql);
		$stockData = mysql_fetch_array($result);
		
		if(empty($stockData))		
		{
			
			$sql = "INSERT INTO sms_external(vendor,item,qty,price,chalan,remark,date,stock) VALUES ('$vendor','$item',0,$price,'$chalan','$remark','$date',0)"; 
		
			if (mysql_query($sql)) 
			{
				$error = 0 ; 
				$sql = "SELECT * FROM sms_external WHERE item ='$item'";
				$result = mysql_query($sql);
				$stockData = mysql_fetch_array($result);
			}
			else
			{
				$error = 1; 
			}
		}
		
		$stock = $stock + $stockData['stock'];
		$qty = $qty + $stockData['qty'];
		
		$sql = "update sms_external set stock='$stock', qty='$qty' WHERE item ='$item'";
		
		if (mysql_query($sql)) 
		{
			$error = 0; 
		}
		else
		{
			$error = 1; 
		}
		$sid= $stockData['id'];
		$sql = "INSERT INTO sms_stock(external,qty,price,date) VALUES ($sid,$qty,$price,'$date')"; 
		
		if (mysql_query($sql)) 
		{
			$error = 0 ; 		
		}
		else
		{
			$error = 1; 
		}
		
		
		if ($error ==0) 
		{
			$_SESSION['success']= " Successfully Saved !!!";		 
		} 
		else 
		{
		    $_SESSION['error'] = "Oooppss, Error in save !!! ";
		}
		 
	}	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
        
?>
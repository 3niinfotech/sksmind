 <?php
 session_start();
 include("../database.php");
 include("../variable.php");
if (!isset($_SESSION['username']))
{
	header("Location: ".$mainUrl);
}
	try
	{
	
	if(empty($_POST["department"]) || empty($_POST["person"]) || empty($_POST["item"]) || empty($_POST["qty"]) || empty($_POST["price"]) )
	{
		$_SESSION['error'] = " Value can't be Blank.";
	}	
	else	
	{	
		$department = $_POST["department"];	
		$person = $_POST["person"];	
		$item = $_POST["item"];		
		$qty = $_POST["qty"];		
		$price = $_POST["price"];		
		$remark = $_POST["remark"];				
		$date = date("Y-m-d H:i:s");
		$stock = $qty;
			
		$sqlStock = mysql_query("select stock,id from sms_external where item=".$item);
		$avilable = 0; 
		$uid = 0;
		while ($row =  mysql_fetch_assoc($sqlStock))
		{
			$avilable = $row['stock'];
			$uid=$row['id'];
			break;
		}
		$remaining = (int)$avilable - (int)$qty;
		if($avilable > 0 && $remaining >= 0)
		{
			$stock = $remaining;	
			$sql = "INSERT INTO sms_internal (department,person,item,qty,price,remark,date,inhouse) VALUES ('$department','$person','$item',$qty,$price,'$remark','$date',$stock)"; 
			
			if (mysql_query($sql)) 
			{
				$update = "update sms_external set stock=".$remaining." where id=".$uid;
				if (mysql_query($update)) 
				{
					$_SESSION['success']= " Successfully Saved !!!";
				}					 
			} 
			else 
			{
			  $_SESSION['error'] = "Oooppss, Error in save !!! ";
			}
		}
		else 
		{
		  $_SESSION['error'] = "There is no Stock of this item, Please Update Stock";
		}
		 
	}	
	}
	catch(Exception $e)
	{
		$_SESSION['error'] = $e->getMessage();
	}
	header('Location: ' . $_SERVER['HTTP_REFERER']);
        
?>
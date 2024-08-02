<?php
 session_start();
 include("database.php");
 include("dai/Helper.php");

	$error = $stockData = 0;
	$message = "";
	
	
	if(empty($_POST["name"]) || empty($_POST["number"]) || empty($_POST["address"]) )
	{
		$_SESSION['error'] = " Value can't be Blank.";
	}	
	else	
	{	
		
		//$password = md5($_POST["password"]);		
		//$name = $_POST["name"];		
		//$number = $_POST["number"];		
		//$address = $_POST["address"];		
		$helper = new Helper($cn);
		
		if(isset($_POST["id"])):
			$data = $helper->getUpdateString($_POST);
			$sql = "UPDATE company SET ".$data." WHERE id=".$_POST['id'];		
		else:
			$data = $helper->getInsertString($_POST);
			$sql = "INSERT INTO company (". $data[0].") VALUES (".$data[1].")";		
		endif;
		
		if (mysqli_query($cn,$sql)) 
		{
			$error = 0 ; 
			if(!isset($_POST["id"]))
			{
				$rs=mysqli_query($cn,"SELECT id FROM company ORDER BY id DESC LIMIT 1");
 
				$cid = 0;
				while ($row =  mysqli_fetch_assoc($rs))
				{	
					$cid = $row['id'];
					break;
				}
				$increData = array(
				'company'=>$cid,
				'purchase'=>'Purchase-1',
				'sale'=> 'Sale-1',
				'payment'=>'Payment-1',
				'receipt'=>'Receipt-1',
				'journal'=>'Journal-1',
				'contra'=>'Contra-1',
				);
				$data = $helper->getInsertString($increData);
				$sql = "INSERT INTO dai_incrementid (". $data[0].") VALUES (".$data[1].")";	
				
				$t = mysqli_query($cn,$sql);
			}
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
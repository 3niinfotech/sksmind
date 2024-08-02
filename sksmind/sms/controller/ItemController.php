<?php 
session_start();
include("../../database.php");


	if(function_exists($_POST['function'])) {
		$_POST['function']();
	}
	
	function getStock()
	{
		$error = 0;
		$avilable = 0; 
		$price = 0;
		
		if(empty($_POST['id']))
		{
			$error = 1;
		}		
		else	
		{	
			$i_id = $_POST['id'];	
		
			$sql = mysql_query("select stock, price from sms_external where item = ".$i_id);
			
			while ($row = mysql_fetch_assoc($sql))
			{
				$avilable = $row['stock'];
				$price=$row['price'];
				break;
			} 
			$avilable = "Item In Stock : ".$avilable; 
			
		}
		if($error)
		{
			echo "1";
		}
		else
		{			
			$temp = array('s'=>$avilable  ,'p'=>$price);
			echo json_encode($temp);
		}
		
	}

	function getItemData()
	{
		$error = 0;
		$data = ""; 
		$gridData = array();
		
		if(!isset($_POST['id']))
		{
			
			$error = 1;
		}		
		else	
		{	
			$i_id = $_POST['id'];	
			$sql ="";
			if($i_id != 0)
			{
				$sql = mysql_query("SELECT e.id, i.name 'Item Name' ,v.name 'Vendor Name',chalan 'Chalan No.', qty 'Quantity',(SELECT sum(qty) from sms_internal where item=".$i_id.") used, stock 'In Stock',price, date FROM sms_external e LEFT JOIN sms_vendor v ON e.vendor = v.id LEFT JOIN sms_item i ON e.item = i.id  where item = ".$i_id);
				$rowData =0;
				while ($row = mysql_fetch_assoc($sql))
				{
					$rowData = $row;
					break;
				}
				if(!empty($rowData))
				{
					
					foreach($rowData  as $key=>$value)
					{
						if($key == "id" )continue;
						
						$data .='<div class="content-block">';
						$data .='<div class="block-title">'.ucfirst($key).'</div>';
						$data .='<div class="block-Value">'.ucfirst($value).'</div>';
						$data .='</div>';
					}
					
					$data .='<div class="purchase-report"><p>Purchase Record</p>';
					$data .='<ul class="first-ul">	<li>Sr. No</li>	<li>Qty</li><li>Price</li>	<li>Date</li></ul>';
					
					
					
					$sql = mysql_query("SELECT * from sms_stock where external=".$rowData['id']);
					$rowData =0;
					$i=1;
					while ($row = mysql_fetch_assoc($sql))
					{
						$data .='<ul>';
						$data .='<li>'.$i.'</li>';
						$data .='<li>'.$row['qty'].'</li>';
						$data .='<li>'.$row['price'].'</li>';
						$data .='<li>'.$row['date'].'</li>	';					
						$data .='</ul>';
						$i++;
					}
					$data .='</div>';
				}
				
				$sql = mysql_query("SELECT e.id,qty,price,inhouse,date,d.name dname,p.name pname,i.name iname, remark FROM sms_internal e LEFT JOIN sms_department d ON e.department = d.id LEFT JOIN sms_person p ON e.person = p.id LEFT JOIN sms_item i ON e.item = i.id where item = ".$i_id." ORDER BY e.id DESC");
			}
			else
			{	
				$data ="";
				$sql = mysql_query("SELECT e.id,qty,price,inhouse,date,d.name dname,p.name pname,i.name iname, remark FROM sms_internal e LEFT JOIN sms_department d ON e.department = d.id LEFT JOIN sms_person p ON e.person = p.id LEFT JOIN sms_item i ON e.item = i.id ORDER BY e.id DESC");
			}	
			$i=1;
			while ($row = mysql_fetch_assoc($sql))
			{
				$temp1 = array(
				'id'=> $i,
				'department'=> $row['dname'],
				'person'=> $row['pname'],	
				'item'=> $row['iname'],					
				'qty'=> $row['qty'],
				'price'=> $row['price'],					
				'date'=> $row['date'],
				'remark'=> $row['remark'],
				);
				$i++;			
				$gridData[] = $temp1;				
			}
		}
		if($error)
		{
			echo "No Data Found";
		}
		else
		{	
			$temp['d'] = $data;
			$temp['g'] = json_encode($gridData);
			
			echo json_encode($temp);
		}
		
	}


	function getDepartmentData()
	{
		$error = 0;
		$data = ""; 
		$gridData = array();
		
		if(empty($_POST['id']))
		{
			$error = 1;
		}		
		else	
		{	
			$i_id = $_POST['id'];
			
			$sql = mysql_query("SELECT e.id,qty,price,inhouse,date,p.name pname, i.name iname, remark FROM sms_internal e LEFT JOIN sms_item i ON e.item = i.id LEFT JOIN sms_person p ON e.person = p.id where department=".$i_id." ORDER BY e.id DESC");
			$i=1;
			while ($row = mysql_fetch_assoc($sql))
			{
				$temp1 = array(
							'id'=> $i,
							'item'=> $row['iname'],
							'person'=> $row['pname'],							
							'qty'=> $row['qty'],
							'price'=> $row['price'],
							'date'=> $row['date'],
							'remark'=> $row['remark'],
							);
				$i++;			
				$gridData[] = $temp1;				
			}
			
			
		}
		if($error)
		{
			echo "No Data Found";
		}
		else
		{	
			$temp['d'] = "";
			$temp['g'] = json_encode($gridData);
			
			echo json_encode($temp);
		}
		
	}


	function getPersonData()
	{
		$error = 0;
		$data = ""; 
		$gridData = array();
		
		if(empty($_POST['id']))
		{
			$error = 1;
		}		
		else	
		{	
			$i_id = $_POST['id'];
			
			$sql = mysql_query("SELECT e.id,qty,price,inhouse,date,d.name dname, i.name iname, remark FROM sms_internal e LEFT JOIN sms_item i ON e.item = i.id LEFT JOIN sms_department d ON e.department = d.id where person=".$i_id." ORDER BY e.id DESC");
			$i=1;
			while ($row = mysql_fetch_assoc($sql))
			{
				$temp1 = array(
							'id'=> $i,
							'item'=> $row['iname'],
							'department'=> $row['dname'],							
							'qty'=> $row['qty'],
							'price'=> $row['price'],
							'date'=> $row['date'],
							'remark'=> $row['remark'],
							);
				$i=1;			
				$gridData[] = $temp1;				
			}
			
			
		}
		if($error)
		{
			echo "No Data Found";
		}
		else
		{	
			$temp['d'] = "";
			$temp['g'] = json_encode($gridData);
			
			echo json_encode($temp);
		}
		
	}

	function getVendorData()
	{
		$error = 0;
		$data = ""; 
		$gridData = array();
		
		if(empty($_POST['id']))
		{
			$error = 1;
		}		
		else	
		{	
			$i_id = $_POST['id'];
			
			$sql = mysql_query("SELECT e.id,qty,price,stock,date,i.name iname, remark, chalan FROM sms_external e LEFT JOIN sms_item i ON e.item = i.id  where vendor=".$i_id." ORDER BY e.id DESC");
			$i=1;
			while ($row = mysql_fetch_assoc($sql))
			{
				$temp1 = array(
							'id'=> $i,
							'item'=> $row['iname'],
							'qty'=> $row['qty'],
							'price'=> $row['price'],
							'date'=> $row['date'],
							'remark'=> $row['remark'],
							'stock'=> $row['stock'],
							'chalan'=> $row['chalan'],
							);
				$i=1;			
				$gridData[] = $temp1;				
			}
			
			
		}
		if($error)
		{
			echo "No Data Found";
		}
		else
		{	
			$temp['d'] = "";
			$temp['g'] = json_encode($gridData);
			
			echo json_encode($temp);
		}
		
	}	
	
	
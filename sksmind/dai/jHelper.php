<?php 

//include_once("../database.php");
//include_once("../variable.php");

class jHelper
{
	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn = $db;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	
	function getInsertString($post)
	{
		$nameString = $valueString = "";
		$i=0;
		$total = count($post);
		foreach($post as $key=>$value)
		{
			$i++;			
			if($key == 'id' || $key == 'fn' ||  $key == 'on_payment' )
				continue;
			
			$nameString .= $key;			
			$valueString .= "'".mysqli_real_escape_string($this->conn,$value)."'";
			
			if($i != $total)
			{
				$nameString .=",";
				$valueString .=",";
			}
		}
		$temp = array(
			0 => $nameString,
			1 => $valueString,
			);
		return $temp;	
	}
	
	function getUpdateString($post)
	{
		$valueString = "";
		$i=0;
		$total = count($post);
		foreach($post as $key=>$value)
		{
			$i++;			
			if($key == 'id' || $key == 'company' || $key == 'fn')
				continue;
			
			$valueString .= $key."='".mysqli_real_escape_string($this->conn,$value)."'";			
			
			if($i != $total)
			{
				$valueString .=",";
			}
		}
		
		return $valueString;	
	}
	public function getImportAttribute()
	{
		$data = array();
		$data['sku'] ='Sku'; 
		$data['pcs'] ='Pcs'; 
		$data['carat'] ='Carat'; 		
		$data['price'] ='Price'; 
		$data['amount'] ='Amount'; 
		$data['color'] ='Color'; 					
		$data['clarity'] ='Clarity'; 
		$data['location'] ='Location'; 
		return $data;
	}
	
	public function getStoneImportAttribute()
	{
		$data = array();
		$data['date'] ='date'; 
		$data['mfg_code'] = 'Kapan Code'; 
		$data['sku'] ='Code';
		$data['color'] ='Color'; 			
		$data['color_code'] ='Color code';
		$data['clarity'] ='Clarity'; 	
		$data['shape'] ='Shape'; 	
		$data['pcs'] ='PCS'; 	
		$data['carat'] ='Weight';
		$data['height'] ='Height';
		$data['width'] ='Width';
		$data['length'] ='Length';
		$data['price'] ='Rate';
		$data['amount'] ='Amount';
		$data['location'] ='Location'; 	
		$data['remarks'] ='Remarks'; 	
		$data['rcts'] ='Carat'; 							
		$data['rheight'] ='Height';	
		$data['rwidth'] ='width'; 							
		$data['rlength'] ='length'; 			
		return $data;
	}
	
	public function getColletImportAttribute()
	{
		$data = array();
		$data['date'] ='date'; 
		$data['mfg_code'] = 'Kapan Code'; 
		$data['sku'] ='Code';
		$data['color'] ='Color'; 			
		$data['color_code'] ='Color code';
		$data['clarity'] ='Clarity'; 	
		$data['shape'] ='Shape'; 	
		$data['pcs'] ='PCS'; 	
		$data['carat'] ='Weight';
		$data['height'] ='Height';
		$data['width'] ='Width';
		$data['length'] ='Length';
		$data['price'] ='Rate';
		$data['amount'] ='Amount';
		$data['cost'] ='Cost Price';
		$data['cost_amount'] ='Cost Amount';
		$data['report_no'] ='Report No';
		$data['remarks'] ='Remarks'; 	
		$data['collet_kt'] ='Gold carat'; 	
		$data['collet_color'] ='Gold Color'; 	
		$data['gross_weight'] ='Gross Weight'; 	
		$data['net_weight'] ='Net Weight'; 	
		$data['pg_weight'] ='Pg Weight'; 	
		$data['collet_rate'] ='Collet Rate'; 	
		$data['collet_amount'] ='Collet Amount'; 	
		$data['other_code'] ='Other Code'; 	
		$data['other_amount'] ='Other Amount';
		$data['labour_rate'] ='Labour Rate'; 
		$data['labour_amount'] ='Labour Amount'; 
		$data['percentage'] ='Percentage'; 
		$data['total_amount'] ='Total Amount';							
		$data['total_amount_cost'] ='Collet Cost Amount';							
		return $data;
	}
	
	
	public function getSideProductDetail($id)
    {
		$data = array();
		$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product WHERE id=".$id);
	
		while($row1 = mysqli_fetch_assoc($rs1))
		{
			$data =  $row1;					
		}	
		return  $data;			
    }
	public function getDetailBySkuForUpdate($sku,$type)
	{
		$data = array();
		if($type == 'main')
		{	
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE sku='$sku'");
				
			while($row = mysqli_fetch_assoc($rs))
			{
				$data = $row;
				break;
			}
			if(empty($data))
			{
				$field = mysqli_num_fields( $rs );   
				for ( $i = 0; $i < $field; $i++ ) {		   
					$data[mysqli_fetch_field_direct( $rs, $i )] = "";		   
				}
				$data['record']=array();
			}
			else
			{
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_product_detail WHERE product_id=".$row['id']);
				while($row = mysqli_fetch_assoc($rs))
				{
					$data['record'][] =  $row;
				}		
				
			}
		}
		else
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product WHERE sku='$sku'");
				
			while($row = mysqli_fetch_assoc($rs))
			{
				$data = $row;
				break;
			}
		}
		return $data;
	}
	
	public function getStoneUpdateAttribute($type=""){
	
		$data = array();
		$data['mfg_code'] ='Kapan';
		$data['sku'] ='Sku';
		$data['pcs'] ='Pcs'; 
		$data['carat'] ='Carat'; 
		$data['price'] ='Price'; 
		$data['amount'] ='Amount'; 
		$data['color'] ='Color'; 
		$data['color_code'] ='Color Code'; 
		$data['clarity'] ='Clarity';		
		$data['shape'] ='Shape'; 
		$data['remarks'] ='Remarks'; 
		if($type =="")
		{	
			$data['igi_code'] ='IGI Code'; 										
			$data['igi_color'] ='IGI Color'; 										
			$data['igi_clarity'] ='IGI Clarity'; 										
			$data['igi_price'] ='IGI Price'; 										
			$data['igi_amount'] ='IGI Amount'; 										
			$data['igi_date'] ='IGI Date'; 										
			$data['lab'] ='Lab'; 
		}
		return $data;
	} 
	
	public function getLooseImportAttribute()
	{
		$data = array();
		$data['date'] ='date'; 		
		$data['sku'] ='Code';
		$data['color'] ='Color'; 					
		$data['clarity'] ='Clarity'; 	
		$data['shape'] ='Shape'; 	
		$data['pcs'] ='PCS'; 	
		$data['carat'] ='Weight';		
		$data['price'] ='Rate'; 							
		$data['amount'] ='Amount'; 							
		$data['gst'] ='GST'; 							
		$data['final_amount'] ='Final Amount'; 									
											
			
		return $data;
	}
	
	public function getJewelryUpdateAttribute()
	{
		$data = array();
		$data['sku'] ='SKU'; 
		$data['jew_design'] = 'Design'; 
		$data['jew_type'] ='Type';
		$data['gold'] ='Gold'; 			
		$data['gold_color'] ='Gold Color'; 			
		$data['gold_carat'] ='Gold Carat';
		$data['gross_weight'] ='Gross Weight'; 	
		$data['net_weight'] ='Net Weight'; 	
		$data['pg_weight'] ='Pg Weight'; 	
		$data['rate'] ='Rate';
		$data['amount'] ='Amount';
		$data['other_code'] ='Other Code';
		$data['other_amount'] ='Other Amount'; 	
		$data['labour_rate'] ='Labour Rate'; 							
		$data['labour_amount'] ='Labour Amount'; 							
		$data['total_amount'] ='Total Amount'; 							
		$data['sell_price'] ='Sell Price'; 							
									
								
											
			
		return $data;
	}
	public function getToImportData()
    {
		$data = array();
		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_temp WHERE user=".$_SESSION['sequence']);
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row; 
		}
		return  $data;			
    }
	public function getTempData()
	{
		$seq = $_SESSION['sequence'];
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_temp WHERE user =".$seq." and code=0");
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function getlooseTempData()
	{
		$seq = $_SESSION['sequence'];
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_temp_loose  WHERE user =".$seq." and code=0");
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function addHistory($post)
    {
		$post['user'] = $_SESSION['userid'];
		if(isset($post['description']))
		{
			$post['description'] = strtoupper($post['description']);
		}	
		
		$data = $this->getInsertString($post);	
		$sql1 = "INSERT INTO jew_history (". $data[0].") VALUES (".$data[1].")";
		
		$rs1 = mysqli_query($this->conn,$sql1);
		if(!$rs1)
		{
			$rs1 = mysqli_error();
		}
		return  $rs1;			
    }
	public function getInventoryAttribute()
	{
		$data = array();
		
		
		$data['mfg_code'] ='Kapan';
		$data['sku'] ='Sku';
		$data['pcs'] ='Pcs'; 
		$data['carat'] ='Carat'; 
		$data['cost'] ='Cost Price'; 
		$data['price'] ='Price'; 
		$data['amount'] ='Amount';
		$data['igi_code'] = 'Report No.';
		$data['color'] ='Color'; 
		$data['color_code'] ='Color Code'; 
		$data['clarity'] ='Clarity';		
		$data['shape'] ='Shape'; 
		$data['measurement'] ='Measurement'; 
		$data['location'] ='Location';
		$data['remarks'] ='Remarks'; 
		
			
		return  $data;
	}
	
	public function getColletInventoryAttribute()
	{
		$data = array();
		$data['mfg_code'] ='Kapan';
		$data['sku'] ='Sku';
		$data['pcs'] ='Pcs'; 
		$data['carat'] ='Carat'; 
		$data['price'] ='Price'; 
		$data['amount'] ='Amount';
		$data['cost_amount'] ='D. Cost Amt';
		$data['color'] ='Color'; 
		$data['clarity'] ='Clarity';
		$data['shape'] ='Shape';
		$data['report_no'] ='Report No.'; 
		$data['collet_kt'] ='Gold Carat '; 
		$data['collet_color'] ='Gold Color'; 
		$data['gross_weight'] ='Gross Weight'; 
		$data['net_weight'] ='Net Weight'; 
		$data['pg_weight'] ='Pg Weight'; 
		$data['collet_rate'] ='Gold rate'; 
		$data['collet_amount'] ='Gold Amount'; 
		$data['other_code'] ='Other Code'; 
		$data['other_amount'] ='Other Amount'; 
		$data['labour_rate'] ='Labour Rate'; 
		$data['labour_amount'] ='Labour Amt'; 
		$data['total_amount'] ='Total Amount'; 
		$data['total_amount_cost'] ='C. Cost Amt'; 
		return  $data;
	}
	
	public function getLabData($post)
    {
		$data = array();
		$party = '';
		if( isset($post['party']) && $post['party']!="" && $post['party'] != 0 )
			$party = " and party=".$post['party'] ;
		
		$invoice = '';
		if( isset($post['invoice']) && $post['invoice']!="")
			$invoice = " and invoiceno=".$post['invoice'] ;
		
		$start = $post['start'];
		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_lab WHERE company=".$_SESSION['companyId']." and is_returned=0 and deleted = 0 ".$party.$invoice." ORDER BY date desc,id desc LIMIT ".$start.",10");
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$temp = array();
			if($row['products_receive'] =='')
				continue;
			foreach(explode(',',$row['products_receive']) as $k=>$id )
			{
				if($id =='')
				continue;
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id=".$id);
			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					$temp['products'][$id] =  $row1;					
				}
			}
			
			$temp['date'] = $row['date'];	
			$temp['entryno'] = $row['entryno'];	
			$temp['invoiceno'] = $row['invoiceno'];
			$temp['party'] = $row['party'];
			$temp['reference'] = $row['reference'];
			
			$data[$row['id']] =  $temp;
		}		
		return  $data;			
    }
	public function getJewelryLabData($post)
    {
		$data = array();
		$party = '';
		if( isset($post['party']) && $post['party']!="" && $post['party'] != 0 )
			$party = " and party=".$post['party'] ;
		
		$invoice = '';
		if( isset($post['invoice']) && $post['invoice']!="")
			$invoice = " and invoiceno=".$post['invoice'] ;
		
		$start = $post['start'];
		
		$rs = mysqli_query($this->conn,"SELECT * FROM jewelry_lab WHERE company=".$_SESSION['companyId']." and is_returned=0 and deleted = 0 ".$party.$invoice." ORDER BY date desc,id desc LIMIT ".$start.",10");
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$temp = array();
			if($row['products_receive'] =='')
				continue;
			foreach(explode(',',$row['products_receive']) as $k=>$id )
			{
				if($id =='')
				continue;
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_jewelry WHERE id=".$id);
			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					$temp['products'][$id] =  $row1;					
				}
			}
			
			$temp['date'] = $row['date'];	
			$temp['entryno'] = $row['entryno'];	
			$temp['invoiceno'] = $row['invoiceno'];
			$temp['party'] = $row['party'];
			$temp['reference'] = $row['reference'];
			
			$data[$row['id']] =  $temp;
		}		
		return  $data;			
    }
	/* public function getColletInventoryAttribute()
	{
		$data = array();
		
		
		$data['mfg_code'] ='Kapan';
		$data['sku'] ='Sku';
		$data['pcs'] ='Pcs'; 
		$data['carat'] ='Carat';
		$data['price'] ='Price';
		$data['amount'] ='Amount';		
		$data['color'] ='Color'; 
		$data['clarity'] ='Clarity';		
		$data['shape'] ='Shape'; 
		$data['collet_gram'] ='G Gram';
		$data['collet_rate'] ='G Rate';
		$data['collet_amount'] ='G Amount';
		$data['after_collet_gram'] ='Gram';
		$data['after_collet_amount'] ='F Amount';
			
		return  $data;
	} */
	
	public function getJewelryType()
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_type WHERE deleted=0");			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row['name'];
		}	
		return  $data;
	}
	public function getGoldType()
	{
		$data = array();
		$data['10'] = '10K';
		$data['14'] = '14K';
		$data['16'] = '16K';
		$data['18'] = '18K';
		$data['22'] = '22K';
		$data['24'] = '24K';
		return $data;
	}
	public function getGoldColor()
	{
		$data = array();
		$data['Yellow'] = 'Yellow';
		$data['White'] = 'White';
		$data['Rose'] = 'Rose';
		
		return $data;
	}
	
	public function getJobMemo($post)
    {
		$data = array();
		$party = $type = $entryno = '';
		
		if( $post['party'] !="" && $post['party'] != 0 )
			$party = " and party=".$post['party'] ;
		
		if( $post['type'] !="" )
			$type = " and is_returned=".$post['type'] ;
		
		if(  $post['entryno'] !="" )
			$entryno = " and entryno= '".$post['entryno']."' " ;
		
			
		//$jobtype = " and job = '$job' ";
		$jobtype = "";
		$rs = mysqli_query($this->conn,"SELECT * FROM create_job WHERE deleted = 0 and company=".$_SESSION['companyId']."  ".$party.$jobtype.$type.$entryno." ORDER BY date desc,id desc");
	
		while($row = mysqli_fetch_assoc($rs))
		{
			$rs1 = mysqli_query($this->conn,"select * from jew_job where deleted = 0 and company=".$_SESSION['companyId']." and entryno ='".$row['id']."'");
			$temp = array();
			
			while($rows = mysqli_fetch_assoc($rs1))
			{	
				$main_stone =  $rows['main_stone'];
				$side_stone =  $rows['side_stone'];
				$collet_stone =  $rows['collet_stone'];
				
				if(isset($rows['jewelry_id']) && $rows['jewelry_id'] != 0)
				{	
					$jewd = mysqli_query($this->conn,"select * from jew_jewelry WHERE id=".$rows['jewelry_id']);
					while($jd=mysqli_fetch_array($jewd))
					{
						if($jd['outward'] == '')
						{
							$rows['j_del'] = 1;
						}
						else
						{
							$rows['j_del'] = 0;
							$rows['j_status'] = $jd['outward'];
						}
						$rows['sku'] = $jd['sku'];
					}
				}
				else
				{
					$rows['j_del'] = 1;
				}
			
			
			$rows['main_stone'] = array();
			$rows['side_stone'] = array();
			$rows['collet_stone'] = array();
			foreach(explode(',',$main_stone) as $k=>$id )
			{
				if($id == '')
					continue;
				
				$rs2 = mysqli_query($this->conn,"SELECT * FROM jew_product p  LEFT JOIN jew_collet c ON p.id = c.product_id WHERE c.type='collet_send' and c.deleted = 0 and p.company=".$_SESSION['companyId']." and p.id=".$id);
			
				while($row1 = mysqli_fetch_assoc($rs2))
				{
					if($row1['is_collet'] == 1 && ($row1['outward'] == 'jewelry_making' || $row1['outward'] == 'jewelry'))
					{	
						$rows['j_status'] = 'Jewelry';
						$rows['j_del'] = 0;
						$rows['sku'] = $row1['sku'];
					}	
					$rows['main_stone'][$id] =   (array)$row1;
				}
			}
			foreach(explode(',',$collet_stone) as $k=>$id )
			{
				if($id == '')
					continue;
				$rs2 = mysqli_query($this->conn,"SELECT * FROM jew_product p  LEFT JOIN jew_collet c ON p.id = c.product_id WHERE (c.type='collet_receive') and c.deleted = 0 and p.company=".$_SESSION['companyId']." and p.id=".$id);
			
				while($row1 = mysqli_fetch_assoc($rs2))
				{
					$rows['collet_stone'][$id] =   (array)$row1;					
				}
			}
			
			foreach(explode(',',$side_stone) as $k=>$id )
			{
				if($id == '')
					continue;
				$rs2 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE id=".$id);
			
				while($row1 = mysqli_fetch_assoc($rs2))
				{
					$rows['side_stone'][$id] =   (array)$row1;					
				}
			}
			$temp[$rows['id']] = (array)$rows;		
			
			}
			$row['record'] =  $temp;
			$data[$row['id']] =  $row;
		}
		return  $data;			
    }
	public function getJobRepair($id)
    {
		$data = array();
		$party = '';
		if( $id !="" && $id != 0 )
			$party = " and party=".$id ;
			
		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_repair WHERE deleted = 0 and status=1 and company=".$_SESSION['companyId']."  ".$party." ORDER BY date desc,id desc");
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$temp = array();
			$p =  $row['products'];
			unset($row['products']);	
			$temp = (array)$row;
			$temp['products'] = array();
			foreach(explode(',',$p) as $k=>$id )
			{
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_jewelry WHERE id=".$id);
			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					$temp['products'][$id] =   (array)$row1;					
				}
			}
		
		
			$data[$row['id']] =  $temp;
		}		
		return $data;			
    }
	
	
	public function getAllDesign()
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_design WHERE deleted=0");			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row['name'];
		}	
		return  $data;
	}
	public function getAllMemoMaker()
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_memomaker WHERE deleted=0");			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row['name'];
		}	
		return  $data;
	}
	
	public function getJobworkDetail($Jid)
	{
		$data = array();
		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_job WHERE deleted=0 and company=".$_SESSION['companyId']."  and entryno=".$Jid);
		$temp=array();
		while($rows = mysqli_fetch_assoc($rs))
			{	
				$tamt = 0;
				$party = $rows['party'];
				$main_stone =  $rows['main_stone'];
				$side_stone =  $rows['side_stone'];
				$collet_stone =  $rows['collet_stone'];
				$tamt = $rows['total_amount'];
			
				
				$rows['main_stone'] = array();
				$rows['side_stone'] = array();
				$rows['collet_stone'] = array();
			
				foreach(explode(',',$main_stone) as $k=>$id )
				{
					if($id == '')
						continue;
					
					$rs2 = mysqli_query($this->conn,"SELECT * FROM jew_product p  LEFT JOIN jew_collet c ON p.id = c.product_id WHERE c.type='collet_send' and c.deleted = 0 and p.company=".$_SESSION['companyId']." and p.id=".$id);
				
					while($row1 = mysqli_fetch_assoc($rs2))
					{
						$tamt += $row1['total_amount'];
						$rows['main_stone'][$id] =   (array)$row1;
					}
				}
				foreach(explode(',',$collet_stone) as $k=>$id )
				{
					if($id == '')
						continue;
					$rs2 = mysqli_query($this->conn,"SELECT * FROM jew_product p  LEFT JOIN jew_collet c ON p.id = c.product_id WHERE c.type='collet_receive' and c.deleted = 0 and p.company=".$_SESSION['companyId']." and p.id=".$id);
				
					while($row1 = mysqli_fetch_assoc($rs2))
					{
						$tamt += $row1['total_amount'];
						$rows['collet_stone'][$id] =   (array)$row1;					
					}
				}
				
				foreach(explode(',',$side_stone) as $k=>$id )
				{
					if($id == '')
						continue;
					$rs2 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE id=".$id);
				
					while($row1 = mysqli_fetch_assoc($rs2))
					{
						$rows['side_stone'][$id] =   (array)$row1;					
					}
				}
				$rows['tamt'] = $tamt;
				$temp[$rows['id']] = (array)$rows;		
			}
		$data['record']=$temp;
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_party WHERE id=".$party);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data['p_name'] = $row['name'];
			$data['p_address'] = $row['address'];
			$data['p_pincode'] = $row['pincode'];
			$data['p_country'] = $row['country'];
			$data['p_contact'] = $row['contact_number'];
			$data['p_fax'] = $row['fax'];
			$data['contact_person'] = $row['contact_person'];
		}
		$rs = mysqli_query($this->conn,"SELECT * FROM create_job WHERE company=".$_SESSION['companyId']." and id=".$Jid );
		while($row = mysqli_fetch_assoc($rs))
		{
			$data['entryno'] = $row['entryno'];
			$data['date'] = $row['date'];
			$data['party'] = $row['party'];
			$data['job'] = $row['job'];
			$data['is_returned'] = $row['is_returned'];
		}
		/*  echo "<pre>";
		print_r($data);
		exit; */ 
		return $data;	
	}
	
	public function getAllJewelry($ids)
	{
		$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_jewelry WHERE company=".$_SESSION['companyId']." and id IN (".$ids.") ORDER BY sku");
		
		$alldata = array();
		while($row1 = mysqli_fetch_assoc($rs1))
		{
			
			/* $rs1 = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." WHERE  jewelry_id = ".$row['id'] );
		
			$temp = array();
			while($row1 = mysqli_fetch_assoc($rs1))
			{
				$temp[$row1['id']] = $row1;
			} */
			
			
			$data = $row1;
			$data['collet'] = array();
			$data['main'] = array();
			$data['side'] = array();
			if($data['main_stone'] !=''):
				
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE p.id IN (".$data['main_stone'].")" );
				
				while($row = mysqli_fetch_assoc($rs))
				{
					$data['main'][] = $row;
				}
			endif;
			
			if($data['collet_stone'] !=''):
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p  LEFT JOIN jew_collet c ON p.id = c.product_id WHERE c.type='collet_receive' and p.id IN (".$data['collet_stone'].")");
				
				while($row = mysqli_fetch_assoc($rs))
				{
					$data['collet'][] = $row;
				}
			endif;
			
			if($data['side_stone'] !=''):
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE p.id IN (".$data['side_stone'].")" );
				
				while($row = mysqli_fetch_assoc($rs))
				{
					$data['side'][] = $row;
				}
			endif;
			//$row['record'] = $temp;
			$extra = $this->getExtraStone($row1['id']);
			if(!empty($extra))
			{	
				$data['side_pcs'] = $extra['side_pcs'];
				$data['side_carat'] = $extra['side_carat'];
				$data['side_price'] = $extra['side_price'];
				$data['side_amount'] = $extra['side_amount'];
			}
			else
			{
				$data['side_carat'] = 0;
			}

			$alldata[] =  $data;
			
		}		
		return  $alldata;			
	}
	public function getExtraStone($id)
    {
		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_job WHERE jewelry_id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		return $data;
	}	
	public function getRepairDetail($id)
	{
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_repair WHERE company=".$_SESSION['companyId']." and id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_party WHERE id=".$data['party']);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data['p_name'] = $row['name'];
			$data['p_address'] = $row['address'];
			$data['p_pincode'] = $row['pincode'];
			$data['p_country'] = $row['country'];
			$data['p_contact'] = $row['contact_number'];
			$data['p_fax'] = $row['fax'];
			$data['contact_person'] = $row['contact_person'];
		}
		$record = array();
		$i=1;
		$pcss =0;
		$carats = 0.0;
		foreach(explode(',',$data['products']) as $pid)
		{
			
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_jewelry  WHERE id=".$pid);
			$temp = array();
			while($row = mysqli_fetch_assoc($rs))
			{
				$temp = $row;
			}
			
			$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_repair_product WHERE  repair_id = $id and product_id=".$pid);			
			while($row1 = mysqli_fetch_assoc($rs1))
			{
				$temp['sidepcs'] = $row1['side_pcs'];
				$temp['sidecarat'] = $row1['side_carat'];
				$temp['sideprice'] = $row1['side_price'];
				$temp['comment'] = $row1['comment'];
			}
			
			$record[$i] = $temp;
			$i++;
		}
		//$data['tcarat'] = $carats;
		//$data['tpcs'] = $pcss;
		$data['record'] = $record;
		return $data;	
	}
	public function getAllSale($id,$type)
    {
		$data = array();
		$party = '';
		if( $id !="" && $id != 0 )
			$party = " and party=".$id ;
		$out = '';	
		if( $type !="" )
			$out = " and type='".$type."'";
		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_sale WHERE deleted=0 and status=1 and products !='' and company=".$_SESSION['companyId'].$party .$out. " ORDER BY date desc,id desc");
	
		while($row = mysqli_fetch_assoc($rs))
		{
			$temp = array();
			foreach(explode(',',$row['products']) as $k=>$id )
			{
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_jewelry p WHERE id=".$id);
			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					$temp['products'][$id] =  $row1;					
				}
			}
			
			$temp['date'] = $row['date'];	
			$temp['invoiceno'] = $row['invoiceno'];			
			$temp['party'] = $row['party'];
			$data[$row['id']] =  $temp;
		}		
		return  $data;			
    }
	
	public function getAllLooseSale($id,$type)
    {
		$data = array();
		$party = '';
		$t='on_'.$type;
		if( $id !="" && $id != 0 )
			$party = " and party=".$id ;
		$out = '';	
		if( $type !="" )
			$out = " and type='".$type."'";
		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_outward WHERE deleted=0 and status='$t' and company=".$_SESSION['companyId'].$party .$out. " ORDER BY date desc,id desc");
	
		while($row = mysqli_fetch_assoc($rs))
		{
			$temp = array();
			if($row['side_stone'] != "")
			{
				foreach(explode(',',$row['side_stone']) as $k=>$id )
				{
					if($id == "")
					continue;	
					$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE id=".$id);
				
					while($row1 = mysqli_fetch_assoc($rs1))
					{
						$temp['side_stone'][$id] =  $row1;					
					}
				}
			}
			if($row['main_stone'] != "")
			{
				foreach(explode(',',$row['main_stone']) as $k=>$id )
				{
					if($id == "")
					continue;	
					$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE id=".$id);
				
					while($row1 = mysqli_fetch_assoc($rs1))
					{
						$temp['main_stone'][$id] =  $row1;					
					}
				}
			}
			if($row['collet_stone'] != "")
			{
				foreach(explode(',',$row['collet_stone']) as $k=>$id )
				{
					if($id == "")
					continue;	
					$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE id=".$id);
				
					while($row1 = mysqli_fetch_assoc($rs1))
					{
						$temp['collet_stone'][$id] =  $row1;					
					}
				}
			}
			$temp['date'] = $row['invoicedate'];	
			$temp['invoiceno'] = $row['invoiceno'];			
			$temp['party'] = $row['party'];
			$data[$row['id']] =  $temp;
		}		
		return  $data;			
    }
	
	public function getReportTransfer($post)
    {
		$fd = $post['cfrom'];
		$td = $post['cto'];
		$data = array();
		$party = $adate ='';
		
		if(isset($post['party']) and $post['party']!='' )		
			$party= "and tofirm='".$post['party']."'";
				
		if($fd !="" && $td !="" )
		{
			$adate = " and date between '".$fd."' and '".$td."'";
			
		}
		else if($fd !="")
		{
			$td1 = '2050/12/31';
			$adate = " and date between '".$fd."' and '".$td1."'";
			
		}
		else if($td !="")
		{
			$fd1 = '2010/01/01';
			$adate = " and date between '".$fd1."' and '".$td."'";			
		}
		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_transfer WHERE deleted=0 ".$party.$adate." ORDER BY date desc,id desc");
		
		while($row = mysqli_fetch_assoc($rs))
		{
			if($post['type'] == 'Main')
			{
				$row['side_stone'] = '';
			}
			elseif($post['type'] == 'Side')
			{
				$row['main_stone'] = '';
			}
			$data[] =  $row;
		}		
		return  $data;			
    }
	
	public function getSaleDetail($id)
	{
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_sale WHERE company=".$_SESSION['companyId']." and id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_party WHERE id=".$data['party']);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data['p_name'] = $row['name'];
			$data['p_address'] = $row['address'];
			$data['p_pincode'] = $row['pincode'];
			$data['p_country'] = $row['country'];
			$data['p_contact'] = $row['contact_number'];
			$data['p_fax'] = $row['fax'];
			$data['contact_person'] = $row['contact_person'];
		}
		$record = array();
		$i=1;
		$pcss =0;
		$carats = 0.0;
		foreach(explode(',',$data['products']) as $pid)
		{
			
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_jewelry p WHERE id=".$pid);
			$temp = array();
			while($row = mysqli_fetch_assoc($rs))
			{
				$jobdata = $this->getJobExtraStoneData($id);
				if(!empty($jobdata))
				{	
					$row['side_pcs'] = $jobdata['side_pcs'];
					$row['side_carat'] = $jobdata['side_carat'];
				}
				else
				{
					$row['side_pcs'] = 0.00;
					$row['side_carat'] = 0.00;
				}
				$collet = $main = $side = array();
			foreach( explode(",",$row['main_stone']) as $k=>$id)
			{
				if($id =='')
					continue;
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id = ".$id );			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					/* $main[] = $row1; */
					$row['main_pcs'] = $row['main_pcs'] + $row1['pcs'];
					$row['main_carat'] = $row['main_carat'] + $row1['carat'];
				}
			}
			foreach( explode(",",$row['collet_stone']) as $k=>$id)
			{
				if($id =='')
					continue;
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id = ".$id );			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
		
					/* $collet[] = $row1; */
					$row['collet_pcs'] = $row['collet_pcs'] + $row1['pcs'];
					$row['collet_carat'] = $row['collet_carat'] + $row1['carat'];
				}
			}
			foreach( explode(",",$row['side_stone']) as $k=>$id)
			{
				if($id =='')
					continue;
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product WHERE id = ".$id );			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					$row['side_pcs'] = $row['side_pcs'] + $row1['pcs'];
					$row['side_carat'] = $row['side_carat'] + $row1['carat'];
					/* $side[] = $row1; */
				}
			}
			$temp = $row;
			/* $temp['collet_stone'] = $collet;
			$temp['main_stone'] = $main;
			$temp['side_stone'] = $side; */
			}
			$record[$i] = $temp;
			$i++;
		}
		/* $data['tcarat'] = $carats;
		$data['tpcs'] = $pcss; */
		$data['record'] = $record;
/* 		echo "<pre>";
		print_r($data);
		exit; */
		return $data;	
	}
	public function getAllSkuOf($type)
    {
		$data = array();
		if($type == 'main')
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE company=".$_SESSION['companyId']);
			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data[] = $row['sku']; 
			}
		}
		if($type == 'side')
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product WHERE  company=".$_SESSION['companyId']);
			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data[] = $row['sku']; 
			}
		}
		if($type == 'jewelry')
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_jewelry WHERE company=".$_SESSION['companyId']);
			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data[] = $row['sku']; 
			}
		}
		return  $data;			
    }
	
	public function getDetailBySku($sku,$type)
    {
		$data = array();
		$sku = trim($sku);
		if($type == 'main')
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE sku='".$sku."'");
			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data = $row;
				if($row['outward'] != 'transfer')
				break;
			}
		}
		if($type == 'side')
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product WHERE sku='".$sku."'");
			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data = $row;
				if($row['outward'] != 'transfer')
				break;
			}
		}
		if($type == 'jewelry')
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_jewelry WHERE sku='".$sku."'");
			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data = $row; 
				break;
			}
		}
		return  $data;			
    }
	
	public function getHistoryOfStone($id,$type)
    {
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_history WHERE product_id=".$id." and for_history ='$type' ORDER BY date");
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row; 			
		}
		return  $data;			
    }	
	public function getStoneAction()
	{
		$data = array(
			'import'=>'Import',
			'purchase'=>'Purchase',
			'stone_update'=>'stone updated',
			'lab'=>'Send To Lab',
			'job_lab'=>'Send To Lab',
			'lab_return'=>'Lab Return',
			'lab_remove'=>'Lab Remove',
			'job_lab_return'=>'Lab Return',
			'memo'=>'Memo',
			'sale'=>'Sale',
			'sale_return'=>'Sale Return',
			'sale_close'=>'Sale Close',
			'close_sale'=>'Sale Close',
			'close_memo'=>'Memo Close',
			'close_lab'=>'Lab Close',
			'memo_return'=>'Memo Return',
			'memo_close'=>'Memo Close',
			'lab_close'=>'Lab Close',
			'hold'=>'Hold',
			'unhold'=>'Unhold',	
			'price_change'=>'Price Changed',	
			'unboxing'=>'Unboxing',
			'boxing'=>'Boxing',
			'to_box'=>'To Box',
			'from_box'=>'From Box',
			'export'=>'Export',
			'export_close'=>'Export Close',			
			'consign'=>'Consignment',
			'consign_close'=>'Consign Close',
			'sku_change'=>'Sku Change',
			'purchase_delete'=>'Purchase Delete',			
			'job'=>'Job',			
			'collet_return'=>'Collet Return',			
			'jewelry_return'=>'Jewelry Return',			
			'job_jewelry'=>'Job Jewelry',			
			'job_return'=>'Job Completed',			
			'in_jewelry'=>'In Jewelry',	
			'collet_making'=>'Collet Making',	
			'job_collet'=>'Job Collet',	
			'stone_transfer'=>'Stone Transfer',	
			'close_collet'=>'Collet Close',	
			'close_jewelry'=>'Jewelry Close',	
			'collet_repair'=>'Collet Repair',	
			'collet_repair_close'=>'Col Repair Close',	
			'collet_repair_edit'=>'Col Repair Edit',	
			
			
		);
		
		return $data;
	}		
	
	public function getExportAttribute()
	{
		$data = array();
		
		$data['mfg_code'] ='Kapan'; 
		$data['sku'] ='sku'; 		
		$data['pcs'] ='Pcs'; 
		$data['carat'] ='Carat'; 
		$data['cost'] ='Cost Price'; 
		$data['price'] ='Price'; 
		$data['amount'] ='Amount';	
		$data['igi_code'] = 'Report No.';
		$data['shape'] ='Shape';		
		$data['color'] ='Color'; 
		$data['clarity'] ='Clarity';		
		$data['location'] ='LOC'; 
		$data['measurement'] ='Measurement';		
		$data['remarks'] ='Remark'; 					
		return  $data;
	}
	public function InventoryCalClass()
	{
		return array('carat'=>'carats','pcs'=>'pcs','price'=>'price','amount'=>'amount');
	}
	public function getPartyDetail($id)
	{
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_party WHERE id=".$id);			
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}	
		return  $data;		
	}	
	
	public function getMemoDetail($id)
	{
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_outward WHERE id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_party WHERE id=".$data['party']);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data['p_name'] = $row['name'];
			$data['p_address'] = $row['address'];
			$data['p_pincode'] = $row['pincode'];
			$data['p_country'] = $row['country'];
			$data['p_contact'] = $row['contact_number'];
			$data['p_fax'] = $row['fax'];
			$data['contact_person'] = $row['contact_person'];
		}
		$record = array();
		$i=1;
		$pcss =0;
		$carats = 0.0;
		foreach(explode(',',$data['collet_stone']) as $pid)
		{
			if($pid == '')
				continue;
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE id=".$pid);
			$temp = array();
			while($row = mysqli_fetch_assoc($rs))
			{
				$temp = $row;
				$pcss += (int)$row['pcs'];
				$carats += (float)$row['carat'];
			}
			$record[$i] = $temp;
			$i++;
		}
		foreach(explode(',',$data['main_stone']) as $pid)
		{
			if($pid == '')
				continue;
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE id=".$pid);
			$temp = array();
			while($row = mysqli_fetch_assoc($rs))
			{
				
				$temp = $row;
				
				$pcss += (int)$row['pcs'];
				$carats += (float)$row['carat'];
				
			}
			$record[$i] = $temp;
			$i++;
		}
		foreach(explode(',',$data['side_stone']) as $pid)
		{
			if($pid == '')
				continue;
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE id=".$pid);
			$temp = array();
			while($row = mysqli_fetch_assoc($rs))
			{
				$temp = $row;
				
				$pcss += (int)$row['pcs'];
				$carats += (float)$row['carat'];
				
			}
			$record[$i] = $temp;
			$i++;
		}
		$data['carat'] = $carats;
		$data['pcs'] = $pcss;
		$data['record'] = $record;
		return $data;	
	}
	public function getLooseMemoDetail($id)
	{
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_outward WHERE id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		$record = array();
		$i=1;
		$pcss =0;
		$carats = 0.0;
		foreach(explode(',',$data['collet_stone']) as $pid)
		{
			if($pid == '')
				continue;
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE id=".$pid);
			$temp = array();
			while($row = mysqli_fetch_assoc($rs))
			{
				$temp = $row;
				$pcss += (int)$row['pcs'];
				$carats += (float)$row['carat'];
			}
			$record['collet_stone'][$pid] = $temp;
			$i++;
		}
		foreach(explode(',',$data['main_stone']) as $pid)
		{
			if($pid == '')
				continue;
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE id=".$pid);
			$temp = array();
			while($row = mysqli_fetch_assoc($rs))
			{
				
				$temp = $row;
				
				$pcss += (int)$row['pcs'];
				$carats += (float)$row['carat'];
				
			}
			$record['main_stone'][$pid] = $temp;
			$i++;
		}
		foreach(explode(',',$data['side_stone']) as $pid)
		{
			if($pid == '')
				continue;
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE id=".$pid);
			$temp = array();
			while($row = mysqli_fetch_assoc($rs))
			{
				$temp = $row;
				
				$pcss += (int)$row['pcs'];
				$carats += (float)$row['carat'];
				
			}
			$record['side_stone'][$pid] = $temp;
			$i++;
		}
		$data['carat'] = $carats;
		$data['pcs'] = $pcss;
		$data['record'] = $record;
		return $data;	
	}
	public function getJobExtraStoneData($id)
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_job WHERE jewelry_id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		return $data;
	}
	public function getDatajewelry($id)
    {
		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_jewelry WHERE id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$jobdata = $this->getJobExtraStoneData($id);
			if(!empty($jobdata))
			{	
				$row['side_pcs'] = $jobdata['side_pcs'];
				$row['side_carat'] = $jobdata['side_carat'];
			}
			else
			{
				$row['side_pcs'] = 0.00;
				$row['side_carat'] = 0.00;
			}	
			$data = $row;
		}

		if(empty($data))
		{
			$field = mysqli_num_fields( $rs );
   
			for ( $i = 0; $i < $field; $i++ ) {
		   
				$data[mysqli_fetch_field_direct( $rs, $i )] = "";
			}
			$data['record'] = array();
		}	
		else
		{
			$collet = $main = $side = array();
			foreach( explode(",",$data['main_stone']) as $k=>$id)
			{
				if($id =='')
					continue;
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id = ".$id );			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					$main[] = $row1;
				}
			}
			foreach( explode(",",$data['collet_stone']) as $k=>$id)
			{
				if($id =='')
					continue;
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id = ".$id );			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
		
					$collet[] = $row1;
				}
			}
			foreach( explode(",",$data['side_stone']) as $k=>$id)
			{
				if($id =='')
					continue;
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product WHERE id = ".$id );			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
		
					$side[] = $row1;
				}
			}
			
			$data['collet_stone'] = $collet;
			$data['main_stone'] = $main;
			$data['side_stone'] = $side;
		}		
		
		return  $data;	
					
    }
	public function num2words($num, $c=1) 
	 {
		$ZERO = 'zero';
		$MINUS = 'minus';
		$lowName = array(
			 /* zero is shown as "" since it is never used in combined forms */
			 /* 0 .. 19 */
			 "", "one", "two", "three", "four", "five",
			 "six", "seven", "eight", "nine", "ten",
			 "eleven", "twelve", "thirteen", "fourteen", "fifteen",
			 "sixteen", "seventeen", "eighteen", "nineteen");
	 
		$tys = array(
			 /* 0, 10, 20, 30 ... 90 */
			 "", "", "twenty", "thirty", "forty", "fifty",
			 "sixty", "seventy", "eighty", "ninety");
	 
		$groupName = array(
			 /* We only need up to a quintillion, since a long is about 9 * 10 ^ 18 */
			 /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
			 "", "hundred", "thousand", "million", "billion",
			 "trillion", "quadrillion", "quintillion");
	 
		$divisor = array(
			 /* How many of this group is needed to form one of the succeeding group. */
			 /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
			 100, 10, 1000, 1000, 1000, 1000, 1000, 1000) ;
	 
		$num = str_replace(",","",$num);
		$num = number_format($num,2,'.','');
		$cents = substr($num,strlen($num)-2,strlen($num)-1);
		$num = (int)$num;
	 
		$s = "";
	 
		if ( $num == 0 ) $s = $ZERO;
		$negative = ($num < 0 );
		if ( $negative ) $num = -$num;
		// Work least significant digit to most, right to left.
		// until high order part is all 0s.
		for ( $i=0; $num>0; $i++ ) {
		   $remdr = (int)($num % $divisor[$i]);
		   $num = $num / $divisor[$i];
		   // check for 1100 .. 1999, 2100..2999, ... 5200..5999
		   // but not 1000..1099,  2000..2099, ...
		   // Special case written as fifty-nine hundred.
		   // e.g. thousands digit is 1..5 and hundreds digit is 1..9
		   // Only when no further higher order.
		   if ( $i == 1 /* doing hundreds */ && 1 <= $num && $num <= 5 ){
			   if ( $remdr > 0 ){
				   $remdr = ($num * 10);
				   $num = 0;
			   } // end if
		   } // end if
		   if ( $remdr == 0 ){
			   continue;
		   }
		   $t = "";
		   if ( $remdr < 20 ){
			   $t = $lowName[$remdr];
		   }
		   else if ( $remdr < 100 ){
			   $units = (int)$remdr % 10;
			   $tens = (int)$remdr / 10;
			   $t = $tys [$tens];
			   if ( $units != 0 ){
				   $t .= "-" . $lowName[$units];
			   }
		   }else {
			   $t = $this->num2words($remdr, 0);
		   }
		   $s = $t." ".$groupName[$i]." ".$s;
		   $num = (int)$num;
		} // end for
		$s = trim($s);
		if ( $negative ){
		   $s = $MINUS . " " . $s;
		}
	 
	//  if ($c == 1) $s .= " and $cents/100";
	 
		///$s .= " dollars1 ";
		if ($c == 1) {
			$s .= " rupees";
			if ($cents == 0) { 
			//$s .= " exactly"; 
			} 
			else {
				$pence = (int)substr("$cents",1);
				$centavos = $lowName[$pence];
				$dimes = (int)substr("$cents",0,1);
				$diez_centavos = $tys[$dimes];
				$s .= " and $diez_centavos $centavos paisa";
			}
		}
	 //$s .=" Only";
	 
		return $s;
	}
}

<?php 

//include_once("../database.php");
//include_once("../variable.php");

class Helper
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
	public function getTempData()
	{
		$seq = $_SESSION['sequence'];
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_temp  WHERE user =".$seq." and code=0");
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function getProductDetail($id)
    {
		$data = array();
		$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id=".$id);
	
		while($row1 = mysqli_fetch_assoc($rs1))
		{
			$data =  $row1;					
		}	
		return  $data;			
    }
	public function getAttribute($all = 0)
	{
		$data = array();
		if($all)
		{
			$data['mfg_code'] ='Mfg.Code'; 
			$data['diamond_no'] ='D.No';  
			$data['sku'] ='Sku'; 
			$data['pcs'] ='Pcs'; 
			$data['carat'] ='Carat'; 	
			$data['price'] ='Price'; 
			$data['amount'] ='Amount'; 
			$data['igi_code'] ='IGI Code';
			$data['igi_color'] ='IGI Color';
			$data['igi_clarity'] ='IGI Clarity';
			$data['igi_price'] ='IGI Price';
			$data['igi_amount'] ='IGI Amount';
			$data['main_color'] ='Main Color'; 
			$data['location'] ='LOC'; 
			$data['lab'] ='Lab'; 
			
		}
		
		unset($data['package']);
		if($all)
		{
			$data['remark'] ='Remark'; 
		}
		return  $data;
	}
	
	public function getImportAttribute($all = 0)
	{
		$data = array();
		if($all)
		{
			$data['mfg_code'] ='Mfg.Code'; 
			$data['diamond_no'] ='D.No';  
			$data['sku'] ='Sku'; 
			$data['rought_pcs'] ='R.Pcs'; 
			$data['rought_carat'] ='R.Carat'; 
			$data['polish_pcs'] ='P.Pcs'; 
			$data['polish_carat'] ='P.Carat'; 
			$data['cost'] ='Cost'; 		
			$data['price'] ='Price'; 
			$data['amount'] ='Amount'; 
			$data['main_color'] ='Main Color'; 
			$data['location'] ='LOC'; 			
			$data['lab'] ='Lab'; 
			
		}
		$rs = mysqli_query($this->conn,"SELECT code,name FROM dai_attribute WHERE company=".$_SESSION['companyId']." ORDER BY short_order" );			
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['code']] = $row['name'];
		}	
		unset($data['package']);
		if($all)
		{
			$data['remark'] ='Remark'; 
		}		
		return  $data;
	}
	
	public function getInventoryAttribute()
	{
		$data = array();
		
		$data['mfg_code'] ='Mfg.Code'; 			
		$data['sku'] ='Sku'; 
		$data['lab'] ='Lab'; 
		$data['report_no'] ='Certificate.';
		$data['shape'] ='Shape';		
		$data['polish_pcs'] ='Pcs'; 
		$data['polish_carat'] ='Carat'; 
		$data['main_color'] ='Full Color'; 
		$data['clarity'] ='Clarity';
		$data['cost'] ='Cost'; 
		$data['rapnet'] ='Rapnet';
		$data['discount'] ='Discount'; 
		if($_SESSION['companyId'] == 3)
		{
			$data['rap_price'] ='Rap'; 
		}
		$data['price'] ='Price'; 
		$data['amount'] ='Amount';
		$data['size'] ='Size';
		$data['f_intensity'] ='Fluorescence';		
		$data['cut'] ='Cut';		
		$data['polish'] ='Polish';
		$data['symmentry'] ='Symm';
		$data['table_pc'] ='Table';
		$data['depth_pc'] ='Depth';		
		$data['mesurment'] ='Msurmnt';		
		$data['gridle'] ='Gridle';
		$data['intensity'] ='Intensity';			
		$data['overtone'] ='Overtone';
		$data['color'] ='Color';
		$data['location'] ='LOC'; 
		$data['package'] ='Package';	
		$data['remark'] ='Remark';	
			
		return  $data;
	}
	
	public function getUpdateAttribute()
	{
		$data = array();
		
		$data['mfg_code'] ='Mfg.Code'; 			
		$data['sku'] ='Sku'; 
		$data['lab'] ='Lab'; 
		$data['polish_pcs'] ='Pcs'; 
		$data['polish_carat'] ='Carat'; 
		$data['main_color'] ='Full Color'; 
		$data['cost'] ='Cost';
		$data['price'] ='Price'; 
		$data['amount'] ='Amount';
		$data['location'] ='LOC'; 
		$data['remark'] ='Remark';
		
		$data['record']['report_no'] ='Certificate.';
		$data['record']['shape'] ='Shape';
		$data['record']['clarity'] ='Clarity';
		$data['record']['size'] ='Size';
		$data['record']['f_intensity'] ='Fluorescence';		
		$data['record']['cut'] ='Cut';		
		$data['record']['polish'] ='Polish';
		$data['record']['symmentry'] ='Symm';
		$data['record']['table_pc'] ='Table';
		$data['record']['depth_pc'] ='Depth';		
		$data['record']['mesurment'] ='Msurmnt';		
		$data['record']['gridle'] ='Gridle';
		$data['record']['intensity'] ='Intensity';			
		$data['record']['overtone'] ='Overtone';
		$data['record']['color'] ='Color';
		
		$data['record']['package'] ='Package';	
			
			
		return  $data;
	}
	
	public function getExportAttribute()
	{
		$data = array();
		
		$data['sku'] ='Sku'; 
		$data['lab'] ='Lab'; 
		$data['report_no'] ='Certificate';
		$data['shape'] ='Shape';		
		$data['polish_pcs'] ='Pcss'; 
		$data['polish_carat'] ='Carat'; 
		$data['main_color'] ='Color'; 
		$data['clarity'] ='Clarity';		
		$data['rapnet'] ='Rapnet'; 
		$data['discount'] ='Discount'; 
		$data['price'] ='Price'; 
		$data['amount'] ='Amount';
		$data['size'] ='Size';
		$data['f_intensity'] ='Fluorescence';		
		$data['cut'] ='Cut';		
		$data['polish'] ='Polish';
		$data['symmentry'] ='Symm';
		$data['table_pc'] ='Table';
		$data['depth_pc'] ='Depth';		
		$data['mesurment'] ='Msurmnt';		
		$data['gridle'] ='Gridle';
		$data['location'] ='LOC'; 
		$data['package'] ='Package';	
		
			
		return  $data;
	}
	
	public function getAttributeField($all = 0)
	{
		$data = array();
		if($all)
		{
			$data['mfg_code'] =''; 
			$data['diamond_no'] ='';  
			$data['sku'] =''; 
			$data['rought_pcs'] =0.0;
			$data['rought_carat'] =0.0;
			$data['polish_pcs'] =0.0;; 
			$data['polish_carat'] =0.0;
			$data['cost'] =0.0;
			$data['price'] =0.0;
			$data['amount'] =0.0;
			$data['location'] =''; 
			$data['remark'] ='';  
			$data['lab'] =''; 
			
		}
		$rs = mysqli_query($this->conn,"SELECT code,name FROM dai_attribute WHERE company=".$_SESSION['companyId']." ORDER BY short_order" );			
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data['record'][$row['code']] = '';
		}	
		return  $data;
	}
	
	
	 public function getInventory($of)
    {
		if($of == "lab") 
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE company=".$_SESSION['companyId']." and lab='' and outward='' ");
		}
		else if($of == "gia") 
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE company=".$_SESSION['companyId']." and outward='lab'");
		}
		else if($of == "export" || $of == "sale" ) 
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE company=".$_SESSION['companyId']." and (outward='' || outward='memo') ");
		}
		else if($of == "memo" ) 
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE company=".$_SESSION['companyId']." and send_to_lab=0 and outward='' ");
		}
		else if($of == "rmemo" ) 
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE company=".$_SESSION['companyId']." and send_to_lab=0 and outward='memo' ");
		}
		else
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id  WHERE company=".$_SESSION['companyId']." ORDER BY lab desc");
		}
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] =  $row;
		}
		
		return  $data;			
    }	
	
	 public function getProfuctGroup($of)
    {
		if($of == "box") 
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product WHERE company=".$_SESSION['companyId']." and (outward='' || outward='memo') and group_type='box' and visibility=1 ORDER BY lab desc");
		}	
		if($of == "parcel") 
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product WHERE company=".$_SESSION['companyId']." and (outward='' || outward='memo') and group_type='parcel' and visibility=1  ORDER BY lab desc");
		}	
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row['sku'];
		}
		return  $data;			
    }	
	
	public function getPartyMemo($post,$type="memo")
    {
		$data = array();
		$party = '';
		if( isset($post['party']) && $post['party']!="" && $post['party'] != 0 )
			$party = " and party=".$post['party'] ;
		
		$invoice = '';
		if( isset($post['invoice']) && $post['invoice']!="")
			$invoice = " and invoiceno=".$post['invoice'] ;
		
		$start = $post['start'];
		
		if($type=="memo")
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_outward WHERE company=".$_SESSION['companyId']." and type in('memo','consign') and status in('on_memo','on_consign') ".$party.$invoice." ORDER BY date desc,id desc LIMIT ".$start.",10");
		else if($type=="sale")	
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_outward WHERE company=".$_SESSION['companyId']." and type in('sale','export') and status in('on_sale','on_export') ".$party.$invoice." ORDER BY date desc,id desc LIMIT ".$start.",10");
	
		while($row = mysqli_fetch_assoc($rs))
		{
			$temp = array();
			foreach(explode(',',$row['products']) as $k=>$id )
			{
				$rs1 = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE id=".$id);
			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					$temp['products'][$id] =  $row1;					
				}
			}
			
			$temp['date'] = $row['date'];	
			$temp['entryno'] = $row['entryno'];
			$temp['type'] = $row['type'];				
			$temp['invoiceno'] = $row['invoiceno'];
			$temp['party'] = $row['party'];
			$temp['reference'] = $row['reference'];
			$data[$row['id']] =  $temp;
		}		
		return  $data;			
    }
	
	public function getInwardPartyMemo($id,$type="purchase")
    {
		$data = array();
		$party = '';
		if( $id !="" && $id != 0 )
			$party = " and party=".$id ;
			
		if($type=="purchase")
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_inward WHERE deleted = 0 and company=".$_SESSION['companyId']." and inward_type in('import','purchase','consign')  ".$party." ORDER BY date desc,id desc");
		elseif($type=="memo")
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_inward WHERE deleted = 0 and company=".$_SESSION['companyId']." and inward_type in('memo','consign')  ".$party." ORDER BY date desc,id desc");
		
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$temp = array();
			$temp = $row;
			$temp['products'] = array();
			$rs1 = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE inward_id=".$row['id']);
		
			while($row1 = mysqli_fetch_assoc($rs1))
			{
				$temp['products'][$row1['id']] =  $row1;					
			}
			
			if(count($temp['products'])==0)
				continue;
			
		/* 	$temp['date'] = $row['date'];	
			$temp['entryno'] = $row['entryno'];
			$temp['type'] = $row['inward_type'];				
			$temp['invoiceno'] = $row['invoiceno'];
			$temp['party'] = $row['party'];
			$temp['reference'] = $row['reference']; */
			$data[$row['id']] =  $temp;
			
		}		
		return  $data;			
    }
	
	
	public function getOutwardDetails($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_outward WHERE company=".$_SESSION['companyId']." and id =".$id);
		
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$row['record'] = array();
			if($row['side_stone'] != "")
			{	
				foreach(explode(',',$row['side_stone']) as $k=>$pid )
				{
					if($pid == '')
						continue;
					
					$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product WHERE id=".$pid);
					while($row1 = mysqli_fetch_assoc($rs1))
					{
						$row1['igi_code'] = "";
						$row1['lab'] = "";
						$row1['igi_color'] = "";
						$row1['igi_clarity'] = "";
						$row1['igi_price'] = 0.00;
						$row1['igi_amount'] = 0.00;
						$row['record'][$pid] =  $row1;			
					}
				}
			}
			if($row['main_stone'] != "")
			{	
				foreach(explode(',',$row['main_stone']) as $k=>$pid )
				{
					if($pid == '')
						continue;
					
					$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id=".$pid);
					while($row1 = mysqli_fetch_assoc($rs1))
					{
						$row['record'][$pid] =  $row1;			
					}
				}
			}
			/* if( $row['products'] =="" && $row['return_products'] !='' )						
			{
				foreach(explode(',',$row['return_products']) as $k=>$pid )
				{
					$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product id=".$pid);
					while($row1 = mysqli_fetch_assoc($rs1))
					{
						$row['record'][$pid] =  $row1;			
					}
				}
			} */
			$data =  $row;
		}		
		return  $data;			
    }

	public function getJewDetails($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_sale WHERE company=".$_SESSION['companyId']." and id =".$id);
		
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$row['record'] = array();
			foreach(explode(',',$row['products']) as $k=>$pid )
				{
					if($pid == '')
						continue;
					
					$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_jewelry WHERE id=".$pid);
					while($row1 = mysqli_fetch_assoc($rs1))
					{
						$row['record'][$pid] =  $row1;			
					}
				}
			$data =  $row;
		}		
		return  $data;			
    }
	public function getInwardDetails($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_inward WHERE company=".$_SESSION['companyId']." and id =".$id);
		
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
		}
		$data['record'] = array();
		if($data['inward_type'] =='purchase'):
			foreach(explode(",",$data['products']) as $k=>$iid)
			{
				if($data['import_for'] == 'stone')
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id=".$iid);
				else
				$rs1 = mysqli_query($this->conn,"SELECT *,sku as side FROM jew_loose_product WHERE id=".$iid);	
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					if(isset($row1['side']))
				{
					$row1['igi_code'] = "";
					$row1['lab'] = "";
					$row1['igi_color'] = "";
					$row1['igi_clarity'] = "";
					$row1['igi_price'] = 0.00;
					$row1['igi_amount'] = 0.00;
				}	
					$data['record'][$row1['id']] =  $row1;			
				}
			}
		else:
			if($data['import_for'] == 'stone')
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE inward_id=".$id);
				else
				$rs1 = mysqli_query($this->conn,"SELECT *,sku as side FROM jew_loose_product WHERE inward_id=".$id);
			while($row1 = mysqli_fetch_assoc($rs1))
			{
				if(isset($row1['side']))
				{
					$row1['igi_code'] = "";
					$row1['lab'] = "";
					$row1['igi_color'] = "";
					$row1['igi_clarity'] = "";
					$row1['igi_price'] = 0.00;
					$row1['igi_amount'] = 0.00;
				}	
				$data['record'][$row1['id']] =  $row1;			
			}

		endif;
		return  $data;			
    }
	public function getLabDetails($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_lab WHERE company=".$_SESSION['companyId']." and id =".$id);
		
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
		}
		$data['record'] = array();
			foreach(explode(",",$data['products']) as $k=>$iid)
			{
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id=".$iid);
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					$data['record'][$row1['id']] =  $row1;			
				}
			}
	
		return  $data;			
    }
	public function getReportMemo($post)
    {
		$fd = $post['cfrom'];
		$td = $post['cto'];
		$data = array();
		$party = $adate = $invoice = "";
		$report = $post['report'];
		if($post['type'] =='' || $report =='' )
			return $data;
			
		if(isset($post['party']) and $post['party']!=0 )		
			$party= " and party='".$post['party']."'";
		
		
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
		
		/*$memo = "";
		$status = "";		
		if(isset($post['memo']))
		{ 
			$memo = " and (";
			$status = " and (";
			$count = count($post['memo']) - 1;
			foreach($post['memo'] as $k=>$v)
			{	
				$memo .= " type = '".$v."' ";
				$status .= " status = 'on_".$v."' ";				
				if($count != $k )
				{
					$memo .= " || ";
					$status .= " || ";
				}
			}
			$memo .= ")";
			$status .= ")";
		}
		else
		{
			$memo =" and ( type = 'memo' || type = 'lab' ) and ( status = 'on_memo' || status = 'on_lab' ) "; 
		}*/
		
		$gia = "";
		$nong = "";		
		if(isset($post['gia']))
		{ 
			$gia = " and lab = 'IGI'";	
		}
		
		if(isset($post['invoice']) && $post['invoice']!='')
		{ 
			$invoice = " and invoiceno = '".$post['invoice']."'";	
		}
		
		if(isset($post['non-gia']))
		{ 
			$nong = " and lab = ''";	
		}
		if(isset($post['gia']) && isset($post['non-gia']) )
		{
			$gia = " and ( lab = 'IGI' or lab = '')";	
			$nong = "";
		}
		//check type of report customer want
		if($report =="memo" || $report =="sale")
		{	
			$inward = " and type='".$report."'";
			
			if($report == "sale" || $report =="export" || $report == "close_sale" )	
				$inward = " and type in('sale','export') ";
			
			if($report == "memo" || $report == "close_memo" || $report =="consign" )	
				$inward = " and type in('memo','consign') ";
			if($report == "sale" || $report == "memo")
			$status = " and (status <> 'close_sale' && status <> 'close_consign') ";
			
			if($report == "close_memo")	
				$status = " and status in('close_memo','close_consign') ";
			
			if($report == "close_sale")	
				$status = " and status in('sale_close','close_export') ";
			
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_outward WHERE side_stone<>'' or main_stone<>'' and company=".$_SESSION['companyId'].$status.$inward.$party.$adate.$invoice." ORDER BY date desc,id desc");
			
			//check display type of the report //packet or party				
			if($post['type']=='packet')
			{	
				while($row = mysqli_fetch_assoc($rs))
				{
					$mproducts = $row['main_stone'];
					if($mproducts != "")
					{	
						unset($row['main_stone']);				
						foreach(explode(',',$mproducts) as $k=>$id )
						{
							$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id=".$id.$gia.$nong);
							while($row1 = mysqli_fetch_assoc($rs1))
							{
								$row1['party'] = $row['party'];
								$row1['out_date'] = $row['date'];
								$row1['entryno'] = $row['invoiceno'];
								$data[] =  $row1;
							}
						}
					}
					$products = $row['side_stone'];
					if($products != "")
					{		
						foreach(explode(',',$products) as $k=>$id )
						{
							$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product WHERE id=".$id.$gia.$nong);
							while($row1 = mysqli_fetch_assoc($rs1))
							{
								$row1['igi_code'] = "";
								$row1['lab'] = "";
								$row1['igi_color'] = "";
								$row1['igi_clarity'] = "";
								$row1['igi_price'] = 0.00;
								$row1['igi_amount'] = 0.00;
								$row1['party'] = $row['party'];
								$row1['out_date'] = $row['date'];
								$row1['entryno'] = $row['invoiceno'];
								$data[] =  $row1;
							}
						}
					}
					/* if( ($report == "close_memo" || $report == "close_sale") &&  $products =="" && $row['return_products'] !='' )						
					{
						foreach(explode(',',$row['return_products']) as $k=>$id )
						{
							$rs1 = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE id=".$id.$gia.$nong);
							while($row1 = mysqli_fetch_assoc($rs1))
							{
								$row1['party'] = $row['party'];
								$row1['out_date'] = $row['date'];
								$row1['entryno'] = $row['entryno'];
								$data[] =  $row1;
							}
						}
					} */
				}
			}
			else
			{
				while($row = mysqli_fetch_assoc($rs))
				{
					$products = $row['side_stone'];
					$tpp = $tpc =  $tp = $ta = 0.0; 
					if($products != "")
					{	
						foreach(explode(',',$products) as $k=>$id )
						{
							$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product WHERE id=".$id.$gia.$nong);
							while($jData = mysqli_fetch_assoc($rs1))
							{
								
								$tpp += (float) $jData['pcs'];
								$tpc += (float) $jData['carat'];			
								$tp += (float) ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'];
								$ta += (float) ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'];

								
							}
						}
					}
					$mproducts = $row['main_stone'];
					$tpp = $tpc =  $tp = $ta = 0.0; 
					if($mproducts != "")
					{	
						foreach(explode(',',$mproducts) as $k=>$id )
						{
							$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id=".$id.$gia.$nong);
							while($jData = mysqli_fetch_assoc($rs1))
							{
								$tpp += (float) $jData['pcs'];
								$tpc += (float) $jData['carat'];			
								$tp += (float) ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'];
								$ta += (float) ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'];

								
							}
						}
					}
					/* if( ($report == "close_memo" || $report == "close_sale") &&  $mproducts =="" || $products =="" && $row['return_products'] !='' )						
					{
						foreach(explode(',',$row['return_products']) as $k=>$id )
						{
							$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE id=".$id.$gia.$nong);
							while($jData = mysqli_fetch_assoc($rs1))
							{
								$tpp += (float) $jData['polish_pcs'];
								$tpc += (float) $jData['polish_carat'];			
								$tp += (float) ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'];
								$ta += (float) ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'];
							}
						}
					}		 */			
					$row['tpp'] =  $tpp;
					$row['tpc'] =  $tpc;
					$row['tp'] =  $tp;
					$row['ta'] =  $ta;
					$row['final_amount'] =  $ta;
					$row['entryno'] = $row['invoiceno'];
					$data[] =  $row;					
				}	
			}
		}
		elseif($report =="lab")
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_lab WHERE company=".$_SESSION['companyId'].$party.$adate.$invoice." ORDER BY date desc,id desc");
			if($post['type']=='packet')
			{	
				while($row = mysqli_fetch_assoc($rs))
				{
					$products = $row['products'];
					if($products != "")
					{
						foreach(explode(',',$products) as $k=>$id )
						{
							$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id=".$id.$gia.$nong);
							while($row1 = mysqli_fetch_assoc($rs1))
							{
								$row1['party'] = $row['party'];
								$row1['out_date'] = $row['date'];
								$row1['entryno'] = $row['entryno'];
								$data[] =  $row1;
							}
						}
					}
				}
			}
			else
			{
				while($row = mysqli_fetch_assoc($rs))
				{
					$products = $row['products'];
					$tpp = $tpc =  $tp = $ta = 0.0; 
					if($products != "")
					{	
						foreach(explode(',',$products) as $k=>$id )
						{
							$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id=".$id.$gia.$nong);
							while($jData = mysqli_fetch_assoc($rs1))
							{
								$tpp += (float) $jData['pcs'];
								$tpc += (float) $jData['carat'];			
								$tp += (float) ($jData['sell_price'] ==0)?$jData['price']:$jData['sell_price'];
								$ta += (float) ($jData['sell_amount'] ==0)?$jData['amount']:$jData['sell_amount'];

							}
						}
					}
					$row['tpp'] =  $tpp;
					$row['tpc'] =  $tpc;
					$row['tp'] =  $tp;
					$row['ta'] =  $ta;
					$row['final_amount'] =  $ta;
					$row['invoicedate'] = $row['date'];
					$row['duedate'] = $row['date'];
					$row['terms'] = 0;
					$data[] =  $row;	
				}
			}			
		}
		elseif($report =="purchase" || $report =="import")
		{
			$outward = " and inward_type in ('purchase','import') ";
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_inward WHERE deleted=0 and company=".$_SESSION['companyId'].$outward.$party.$adate.$invoice." ORDER BY date desc,id desc");
		
			if($post['type']=='packet')
			{	
				while($row = mysqli_fetch_assoc($rs))
				{
					$id = $row['id']; 
						
					if($row['import_for'] == 'stone')
					{	
						$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product where inward_id=".$id.$gia.$nong);
					}
					else
					{	
						$rs1 = mysqli_query($this->conn,"SELECT *,sku as side FROM jew_loose_product where inward_id=".$id.$gia.$nong);
					}
				
					while($row1 = mysqli_fetch_assoc($rs1))
					{
							if(isset($row1['side']))
							{
								$row1['igi_code'] = "";
								$row1['lab'] = "";
								$row1['igi_color'] = "";
								$row1['igi_clarity'] = "";
								$row1['igi_price'] = 0.00;
								$row1['igi_amount'] = 0.00;
							}	
						$row1['party'] = $row['party'];
						$row1['out_date'] = $row['date'];
						$row1['entryno'] = $row['entryno'];
						$data[] =  $row1;
					}					
				}
			}
			else
			{
				while($row = mysqli_fetch_assoc($rs))
				{
					$id = $row['id'];
					$tpp = $tpc =  $tp = $ta = 0.0; 	
					if($row['import_for'] == 'stone')
					{	
						$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_product where inward_id=".$id.$gia.$nong);
					}
					else
					{	
						$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product where inward_id=".$id.$gia.$nong);
					}
					
					while($jData = mysqli_fetch_assoc($rs1))
					{
						$polish_carat = ($jData['purchase_carat'] ==0) ? $jData['carat'] : $jData['purchase_carat'];
						$polish_pcs = ($jData['purchase_pcs'] ==0) ? $jData['pcs'] : $jData['purchase_pcs'];
		
		
						$tpp += (float) $polish_pcs;
						$tpc += (float) $polish_carat;			
						$tp += (float) ($jData['purchase_price'] ==0) ? $jData['price'] : $jData['purchase_price'];
						$ta += (float) ($jData['purchase_amount'] ==0) ? $jData['amount'] : $jData['purchase_amount'];
					}
					
					if($tpc>0 && $tp>0 &&  $ta>0)
					{
						if($row['pcs'] == 0)
							$row['tpp'] =  $tpp;
						else
							$row['tpp'] =  $row['pcs'];
						
						if($row['carat'] == 0)
							$row['tpc'] =  $tpc;
						else
							$row['tpc'] =  $row['carat'];					
						
						$row['tp'] =  $tp;
						$row['ta'] =  $ta;
						$data[] =  $row;
						
					}
				}	
			}
		}		
		return  $data;			
    }
	
	public function getReportMemoJew($post)
    {
		$fd = $post['cfrom'];
		$td = $post['cto'];
		$data = array();
		$party = $adate = $invoice = $report = $status = $inward = "";
		if(isset($post['report']))
		{			
	    $report = $post['report'];
		if($post['type'] =='' || $report =='' )
			return $data;		
		}	
		if(isset($post['party']) and $post['party']!=0 )		
			$party= " and party='".$post['party']."'";
		
		
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
		
		if(isset($post['invoice']) && $post['invoice']!='')
		{ 
			$invoice = " and invoiceno = '".$post['invoice']."'";	
		}
		
		if($report =="memo" || $report =="sale")
		{	
			$inward = " and type='".$report."'";
			
			if($report == "sale" || $report =="export" || $report == "close_sale" )	
				$inward = " and type in('sale','export') ";
			
			if($report == "memo" || $report == "close_memo" || $report =="consign" )	
				$inward = " and type in('memo','consign') ";
			if($report == "sale" || $report == "memo")
			$status = " and (status <> '0') ";
		}		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_sale WHERE deleted = 0 and company=".$_SESSION['companyId'].$status.$inward.$party.$adate.$invoice." ORDER BY date desc,id desc");
		$sku = array();
		if($post['type']=='packet')
		{	
				while($row = mysqli_fetch_assoc($rs))
				{
					$products = $row['products'];
					if($products != "")
					{	
						foreach(explode(',',$products) as $k=>$id )
						{
							$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_jewelry WHERE id=".$id);
							while($row1 = mysqli_fetch_assoc($rs1))
							{
								$row1['party'] = $row['party'];
								$row1['out_date'] = $row['date'];
								$row1['entryno'] = $row['invoiceno'];
								$row1['collet'] = array();
								$row1['main'] = array();
								$row1['side'] = array();
								if($row1['main_stone'] != ''){
									
									$rs2 = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE p.id IN (".$row1['main_stone'].")" );
									
									while($row2 = mysqli_fetch_assoc($rs2))
									{
										$row1['main'][] = $row2;
									}
							}
								
								if($row1['collet_stone'] != ''){
									$rs3 = mysqli_query($this->conn,"SELECT * FROM jew_product p  LEFT JOIN jew_collet c ON p.id = c.product_id WHERE c.type='collet_receive' and p.id IN (".$row1['collet_stone'].")");
									
									while($row3 = mysqli_fetch_assoc($rs3))
									{
										$row1['collet'][] = $row3;
									}
							}
								
								if($row1['side_stone'] != ''){
									$rs4 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE p.id IN (".$row1['side_stone'].")" );
									
									while($row4 = mysqli_fetch_assoc($rs4))
									{
										$row1['side'][] = $row4;
									}
							}
								//$row['record'] = $temp;
								$extra = $this->getJobExtraStoneData($id);
								if(!empty($extra))
								{	
									$row1['side_pcs'] = $extra['side_pcs'];
									$row1['side_carat'] = $extra['side_carat'];
									$row1['side_price'] = $extra['side_price'];
									$row1['side_amount'] = $extra['side_amount'];
								}
								else
								{
									$row1['side_carat'] = 0;
								}
								$data[] =  $row1;
							}
						}
					}
					/* if( ($report == "close_memo" || $report == "close_sale") &&  $products =="" && $row['return_products'] !='' )						
					{
						foreach(explode(',',$row['return_products']) as $k=>$id )
						{
							$rs1 = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE id=".$id.$gia.$nong);
							while($row1 = mysqli_fetch_assoc($rs1))
							{
								$row1['party'] = $row['party'];
								$row1['out_date'] = $row['date'];
								$row1['entryno'] = $row['entryno'];
								$data[] =  $row1;
							}
						}
					} */
				}
		}
		else
		{		
			while($row = mysqli_fetch_assoc($rs))
			{
						$products = $row['products'];
						unset($row['products']);
						$tpp = $tpc =  $tp = $ta = 0.0; 
						if($products != "")
						{	
							foreach(explode(',',$products) as $k=>$id )
							{
								$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_jewelry WHERE id=".$id);
								while($jData = mysqli_fetch_assoc($rs1))
								{
									$tpp += (float) $jData['gross_weight'];
									$tpc += (float) $jData['pg_weight'];			
									$tp += (float) $jData['net_weight'];
									$sku[] = $jData['sku'];
									
								}
							}
						}
						$row['tgross_weight'] =  $tpp;
						$row['tpg_weight'] =  $tpc;
						$row['tnet_weight'] =  $tp;
						$sk = implode(",",$sku);
						$row['sku'] = $sk;
						$data[] =  $row;			
			}
		}		
			return  $data;			
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
	public function getPartyGia($post)
    {
		$data = array();
		$party = '';
		if( isset($post['party']) && $post['party']!="" && $post['party'] != 0 )
			$party = " and party=".$post['party'] ;
		
		$invoice = '';
		if( isset($post['invoice']) && $post['invoice']!="")
			$invoice = " and invoiceno=".$post['invoice'] ;
		
		$start = $post['start'];
		
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_outward WHERE company=".$_SESSION['companyId']." and type='lab' and status='on_lab' ".$party ." ORDER BY date desc,id desc LIMIT ".$start.",10");
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$temp = array();
			if($row['products'] =='')
				continue;
			foreach(explode(',',$row['products']) as $k=>$id )
			{
				$rs1 = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE id=".$id);
			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					$temp['products'][$id] =  $row1;					
				}
			}
			
			$temp['date'] = $row['date'];	
			$temp['entryno'] = $row['entryno'];
			$temp['type'] = $row['type'];				
			$temp['invoiceno'] = $row['invoiceno'];
			$temp['party'] = $row['party'];
			$temp['reference'] = $row['reference'];
			
			$data[$row['id']] =  $temp;
		}		
		return  $data;			
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
	
	public function getParentDetail($id)
    {
		$data = array();
		$data['carat'] = 0;
		$data['pcs'] = 0;
		$rs1 = mysqli_query($this->conn,"SELECT sum(p.polish_carat) carat, sum(polish_pcs) pcs  FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE outward='memo' and p.outward_parent =".$id);
	
		while($row1 = mysqli_fetch_assoc($rs1))
		{
			$data =  $row1;					
		}	
		return  $data;			
    }	
	public function getGiaReport($rn)
	{
		$data = array();
		
		try{
			
			/*$url = "http://www.gia.edu/otmm_wcs_int/proxy-report/?ReportNumber=".$rn."&url=https://myapps.gia.edu/ReportCheckPOC/pocservlet?ReportNumber=".$rn;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);           
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			
			$oXML = new SimpleXMLElement($response);	
			$temp = (array)$oXML->REPORT_DTLS->REPORT_DTL;
			
			$message = $oXML->REPORT_DTLS->REPORT_DTL->MESSAGE;*/
			
			
			$url = "https://www.gia.edu/otmm_wcs_int/proxy-report/?ReportNumber=$rn&url=https://myapps.gia.edu/ReportCheckPOC/pocservlet?ReportNumber=$rn";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_HEADER, 0);           

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $response = curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

	//print_r($httpCode);

	
	$ret = "";
	$length = strlen($response);
	
    for ($i=0; $i < $length; $i++)
    {
        $current = ord($response{$i});
		//echo "<br>".$current;
        if($current != 176)
        {
            $ret .= chr($current);
        }
        else
        {
            $ret .= " ";
        }
    }
	
	$oXML = new SimpleXMLElement($response);		
	$temp = (array)$oXML->REPORT_DTLS->REPORT_DTL;

	$message = $oXML->REPORT_DTLS->REPORT_DTL->MESSAGE;

			
			if($message == '')
			{
				foreach($temp as $key=>$t){
					if($t=="")
						continue;
						
					
					$t = trim($t);	
					if(strtolower($key) =="measurement")
					{	
						$t = str_replace("mm","",$t);
						$t = str_replace(" ","",$t);
						$data['mesurment'] = trim($t);
					}
					if(strtolower($key) =="ident_tbl_measurements")
					{	
						$t = str_replace("mm","",$t);
						$t = str_replace(" ","",$t);
						$data['mesurment'] = trim($t);
					}
					if(strtolower($key) =="length")
					{	
						$t = str_replace("mm","",$t);
						$t = str_replace(" ","",$t);
						$data['mesurment'] = trim($t);
					}					
					else if(strtolower($key) =="fluo_intensity_code")	
						$data['f_intensity'] = $t;
					else if(strtolower($key) =="cut_code")	
						$data['cut'] = $t;	
					else if(strtolower($key) =="depth_pct")	
						$data['depth_pc'] = $t;
					else if(strtolower($key) =="ident_tbl_weight")	
						$data['weight'] = trim(str_replace("carat","",$t));
					else if(strtolower($key) =="table_pct")	
						$data['table_pc'] = $t;
					else if(strtolower($key) =="symmetry_code")	
						$data['symmentry'] = $t;
					else if(strtolower($key) =="girdle_code")	
						$data['gridle'] = $t;
					else if(strtolower($key) =="polish_code")	
						$data['polish'] = $t;
					else if(strtolower($key) =="shape")
					{	
						$shape = explode("~",$t);
						if(isset($shape[1]))
						{
							$shape[1] = str_replace("Brilliant","",$shape[1]);
							$data['shape'] = trim($shape[1]);	
						}
						else
						{
							$shape[0] = str_replace("Brilliant","",$shape[0]);
							$data['shape'] = trim($shape[0]);
						}
						
						if($data['shape'] == "Cut-Cornered Rectangular Modified")
							$data['shape'] = 'Radiant';
							
						if($data['shape'] == "Modified Rectangular" || $data['shape'] == "Modified Square")
							$data['shape'] = 'Cushion Modified';
							
						if($data['shape'] == "Cushion Rose Cut")
							$data['shape'] = 'Rose';	
							
					}
					else if(strtolower($key) =="ident_tbl_shape")
					{	
						$shape = explode("~",$t);
						if(isset($shape[1]))
						{
							$shape[1] = str_replace("Brilliant","",$shape[1]);
							$data['shape'] = trim($shape[1]);	
						}
						else
						{
							$shape[0] = str_replace("Brilliant","",$shape[0]);
							$data['shape'] = trim($shape[0]);
						}
						if($data['shape'] == "Cut-Cornered Rectangular Modified")
							$data['shape'] = 'Radiant';
							
						if($data['shape'] == "Modified Rectangular" || $data['shape'] == "Modified Square")
							$data['shape'] = 'Cushion Modified';
							
						if($data['shape'] == "Cushion Rose Cut")
							$data['shape'] = 'Rose';
					}
					else if(strtolower($key) =="color")
					{	
						$t = str_replace("Natural","",$t);						
						$t = str_replace("Even","",$t);
						$t = str_replace("~","",$t);						
						$data['color'] = trim($t);	
						
					}						
					else
						$data[strtolower($key)] = $t ;
				}				
				$data['message'] = '';
			}
			else
			{
				$data['message'] = 'Please check your entries and try again.';
			}
			return $data;			
		}
		catch(Exception $e){
		
			$data['message'] = $e->getMessage().'Please check your entries and try again.';
			
			return $data;
		}
	}
	
	
	public function getDetail($id,$t='')
    {
		$data = array();
		if($t=='all')
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p  WHERE company=".$_SESSION['companyId']." and id=".$id);
			while($row = mysqli_fetch_assoc($rs))
			{
				$rs1 = mysqli_query($this->conn,"SELECT * FROM  dai_product_value WHERE product_id=".$id);
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					 $row['record'] = $row1;
				}				
				$data =  $row;
			}
		}
		else
		{
			$rs = mysqli_query($this->conn,"SELECT polish_pcs,polish_carat,price,amount FROM dai_product WHERE company=".$_SESSION['companyId']." and id=".$id);
			while($row = mysqli_fetch_assoc($rs))
			{
				$data = $row;
			}	
		}				
		return  $data;			
    }
	public function toBoxOrParcel($post)
	{
		try
		{
		
		$type = $post['type'];
		if($post['btype'] == 'existing')
		{
			$id = $post['boxcode'];
			$edata = $this->getDetail($id,'all');
					
			foreach($post['products'] as $k => $v)
			{
				$data = $this->getDetail($v,'all');
				
				$edata['rought_pcs']  += $data['rought_pcs'];
				$edata['rought_carat']  += $data['rought_carat'];
				$edata['polish_pcs']  += $data['polish_pcs'];
				$edata['polish_carat']  += $data['polish_carat'];
				$edata['cost']  += $data['cost'];
				$edata['price']  += $data['price'];
				$edata['amount']  += $data['amount'];
				if($edata['record']['shape']  != $data['record']['shape'])
				{
					$edata['record']['shape'] = 'MIX';
				}
				if($edata['record']['color']  != $data['record']['color'])
				{
					$edata['record']['color'] = 'MIX';
				}
				if($edata['record']['clarity']  != $data['record']['clarity'])
				{
					$edata['record']['clarity'] = 'MIX';
				}
				if($edata['record']['polish']  != $data['record']['polish'])
				{
					$edata['record']['polish'] = 'MIX';
				}
				if($edata['record']['symmentry']  != $data['record']['symmentry'])
				{
					$edata['record']['symmentry'] = 'MIX';
				}
				if($type=="box")
					$sql = "UPDATE dai_product SET box_id='".$edata['id']."'  WHERE id=".$data['id'];		
				else
					$sql = "UPDATE dai_product SET parcel_id='".$edata['id']."'  WHERE id=".$data['id'];		
					
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
				}				
			}
			
			if($rs)
			{	
				$record = $edata['record'];
				unset($edata['record']);
				$edata['sku'] = $post['esku'];
				if($type=="box")
				{
					$edata['box_products'] = ($edata['box_products'] !="")? $edata['box_products'].",". implode(",",$post['products']) : implode(",",$post['products']);
					$edata['parcel_products']  ="";
				}
				else
				{
					$edata['parcel_products'] = ($edata['parcel_products'] !="")? $edata['parcel_products'].",". implode(",",$post['products']) : implode(",",$post['products']);
					$edata['box_products'] ="";
				}
			
				$data = $this->getUpdateString($edata);	
				$sql = "UPDATE dai_product SET ".$data." WHERE id=".$edata['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();				
				}
				else
				{
					$data = $this->getUpdateString($record);	
					$sql = "UPDATE dai_product_value SET ".$data." WHERE product_id=".$edata['id'];		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();		
					}
				}				
			}
		}
		else
		{
			$edata = $this->getAttributeField(1);
			$edata['mfg_code'] =  $post['newbox'];
			$edata['sku'] = $post['sku'];
			$i=1;
			foreach($post['products'] as $k => $v)
			{
				$data = $this->getDetail($v,'all');
				
				$edata['rought_pcs']  += $data['rought_pcs'];
				$edata['rought_carat']  += $data['rought_carat'];
				$edata['polish_pcs']  += $data['polish_pcs'];
				$edata['polish_carat']  += $data['polish_carat'];
				$edata['cost']  += $data['cost'];
				$edata['price']  += $data['price'];
				$edata['amount']  += $data['amount'];
				if($type=="box")
					$edata['remark'] = "New Box created at ".date("Y-m-d");
				else
					$edata['remark'] = "New Parcel created at ".date("Y-m-d");;
				
				if($i==1)
				{
					$edata['location'] = $data['location'];
					$edata['lab'] = $data['lab'];
					$edata['record']['shape'] = $data['record']['shape'];
					$edata['record']['color'] = $data['record']['color'];
					$edata['record']['clarity'] = $data['record']['clarity'];
					$edata['record']['polish'] = $data['record']['polish'];
					$edata['record']['symmentry'] = $data['record']['symmentry'];
					$i++;
					continue;

				}
				
				
				if($edata['location']  != $data['location'])
				{
					$edata['location'] = 'MIX';
				}
				if($edata['lab']  != $data['lab'])
				{
					$edata['lab'] = 'MIX';
				}
				
				if($edata['record']['shape']  != $data['record']['shape'])
				{
					$edata['record']['shape'] = 'MIX';
				}
				if($edata['record']['color']  != $data['record']['color'])
				{
					$edata['record']['color'] = 'MIX';
				}
				if($edata['record']['clarity']  != $data['record']['clarity'])
				{
					$edata['record']['clarity'] = 'MIX';
				}
				if($edata['record']['polish']  != $data['record']['polish'])
				{
					$edata['record']['polish'] = 'MIX';
				}
				if($edata['record']['symmentry']  != $data['record']['symmentry'])
				{
					$edata['record']['symmentry'] = 'MIX';
				}
			}
			
			$edata['date'] = date("Y-m-d H:i:s");
			$edata['company'] = $_SESSION['companyId'];
			$edata['user'] = $_SESSION['userid'];
			if($type=="box")
			{
				$edata['group_type'] = 'box';
				$edata['box_products'] = implode(",",$post['products']);
				$edata['parcel_products'] = "";
			}
			else
			{
				$edata['group_type'] = 'parcel';
				$edata['parcel_products'] = implode(",",$post['products']);
				$edata['box_products'] = "";				
			}	
					
			$attr = $edata['record'];
			$edata['main_color'] = (isset($attr['color'])) ? $attr['color'] : '';				
			unset($edata['record']);
			$data = $this->getInsertString($edata);	
			$sql = "INSERT INTO dai_product (". $data[0].") VALUES (".$data[1].")";		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)			
			{
				return mysqli_error();				
			}
			else
			{
				$pid = mysqli_insert_id($this->conn);
				$attr['product_id'] = $pid;
				
				
				$data = $this->getInsertString($attr);	
				$sql = "INSERT INTO dai_product_value (". $data[0].") VALUES (".$data[1].")";		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)			
				{
					return mysqli_error();					
				}
				
				foreach($post['products'] as $k => $v)
				{
					if($type=="box")
						$sql = "UPDATE dai_product SET box_id='".$pid ."' WHERE id=".$v;	
					else
						$sql = "UPDATE dai_product SET parcel_id='".$pid ."' WHERE id=".$v;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						return mysqli_error();
										
					}
				}	
			}
		}
		return $rs;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	public function getShape()
	{
		$temp = array(
		'round'=>'Round',
		'cushion'=>'Cushion',
		'oval'=>'Oval',
		'heart'=>'Heart',
		'marquise'=>'Marquise',
		'emerald'=>'Emerald',
		'radiant'=>'Radiant',
		'pear'=>'Pear',
		'rose'=>'Rose',
		'princess'=>'Princess',
		'other'=>'Other',
		);
		return $temp;
	}
	
	public function getColor($t)
	{
		$temp = array();
		if($t=='f'):
			$temp['Yellow']='Yellow';
			$temp['Blue']='Blue';
			$temp['Pink']='Pink';
			$temp['Green']='Green';
			$temp['Orange']='Orange';
			$temp['Gray']='Gray';
			$temp['Purple']='Purple';
			$temp['Violet']='Violet';
			$temp['Brown']='Brown';
			
		else:
			$j= 68; 
			for($i =1;$i<=23;$i++ ): 
				$temp[chr($j)] = chr($j); 
				$j++;
			endfor;
		endif;	
		return $temp;
	}
	public function getClarity()
	{
		$temp = array(
		'FL'=>'Fl',
		'IF'=>'IF',
		'VVS1'=>'VVS1',
		'VVS2'=>'VVS2',	
		'VS1'=>'VS1',	
		'VS2'=>'VS2',			
		'VI1'=>'VI1',
		'VI2'=>'VI2',		
		'I1'=>'I1',
		'I2'=>'I2',
		'I3'=>'I3',
		);
		return $temp;
	}
	public function getPolish()
	{
		$temp = array(
		'Ex'=>'EX',
		'VG'=>'VG',
		'G'=>'G',
		'F'=>'F',		
		);
		return $temp;
	}
	
	public function getFlsIntensity()
	{
		$temp = array(
		'EX'=>'EX',
		'N'=>'N',
		'ST'=>'ST',
		'VSTB'=>'VSTB',
		'NON'=>'NON',
		'F'=>'F',
		'MB'=>'MB',
		'SB'=>'SB',		
		);
		return $temp;
	}
	public function getIntensity()
	{
		$temp = array(
		'Faint'=>'Faint',
		'Very Light'=>'Very Light',
		'Light'=>'Light',
		'Fancy Light'=>'Fancy Light',
		'Fancy'=>'Fancy',
		'Fancy Intense'=>'Fancy Intense',
		'Fancy Vivid'=>'Fancy Vivid',
		'Fancy Deep'=>'Fancy Deep',		
		'Fancy Dark'=>'Fancy Dark',		
		);
		return $temp;
	}
	public function getOvertone()
	{
		$temp = array(
		'Bluish'=>'Bluish',
		'Brownish'=>'Brownish',
		'Grayish'=>'Grayish',
		'Greenish'=>'Greenish',
		'None'=>'None',
		'Orangey'=>'Orangey',
		'Pinkish'=>'Pinkish',
		'Purplish'=>'Purplish',		
		'Reddish'=>'Reddish',
		'Yellowish'=>'Yellowish',		
		);
		return $temp;
	}
	
	public function getOutwardType()
	{
		$temp = array(
		'gia'=>'GIA',
		'memo'=>'Memo',
		'lab'=>'Lab',
		'nong'=>'NON',			
		);
		return $temp;
	}
	public function getGroupType()
	{
		$temp = array(
		'single'=>'Single',
		'box'=>'Box',
		'parcel'=>'Parcel',			
		);
		return $temp;
	}
	
	public function getPackage()
	{
		$temp = array();
		
		$rs = mysqli_query($this->conn,"SELECT distinct(package)  FROM dai_product_value");
		while($row = mysqli_fetch_assoc($rs))
		{
			if($row['package'] =="")
				continue;
			$temp[$row['package']] = $row['package'];
		}
		return $temp;
	}
	
	public function getLocation()
	{
		$temp = array();		
		/* $rs = mysqli_query($this->conn,"SELECT distinct(location)  FROM dai_product");
		while($row = mysqli_fetch_assoc($rs))
		{
			if($row['location'] =="")
				continue;
			$temp[$row['location']] = $row['location'];
		} */		
		$temp['BOX-1'] = "BOX-1";
		$temp['BOX-2'] = "BOX-2";		
		$temp['BOX-3'] = "BOX-3";
		$temp['BOX-4'] = "BOX-4";		
		$temp['BOX-5'] = "BOX-5";
		$temp['BOX-6'] = "BOX-6";		
		$temp['BOX-7'] = "BOX-7";
		$temp['BOX-8'] = "BOX-8";
		$temp['BOX-9'] = "BOX-9";
		$temp['BOX-10'] = "BOX-10";		
		$temp['BOX-11'] = "BOX-11";
		$temp['BOX-12'] = "BOX-12";
		$temp['BOX-13'] = "BOX-13";
		$temp['BOX-14'] = "BOX-14";		
		$temp['BOX-15'] = "BOX-15";
		$temp['BOX-16'] = "BOX-16";
		$temp['BOX-17'] = "BOX-17";
		$temp['BOX-18'] = "BOX-18";		
		$temp['BOX-19'] = "BOX-19";
		$temp['BOX-20'] = "BOX-20";
		
		return $temp;
	}
	
	
	
	public function getJewAttributebyCode($att_code ="color")
	{
		$aid = 0;
		$temp = array();
		$rs = mysqli_query($this->conn,"SELECT id FROM jew_attribute WHERE company=".$_SESSION['companyId']." and code= '".$att_code."'" );
		$aid = "";
		while($row = mysqli_fetch_assoc($rs))
		{
			$aid = $row['id'];
		}				
		$temp = array();		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_attribute_value WHERE attribute_id= '".$aid."'");
		while($row = mysqli_fetch_assoc($rs))
		{
			$temp[$row['code']] = $row['label'];
		}			
		return $temp;
	}
	
	
	public function width50()
	{
		return array('lab','size','polish_pcs','clarity','polish','symmentry','cut','table_pc','depth_pc','location');
	}
	public function width70()
	{
		return array('rap_price','polish_carat','cost','price','amount','rapnet','discount');
	}
	public function right()
	{
		return array('rought_pcs','rought_carat','polish_carat','polish_pcs','cost','price','amount');
	}
	public function InventoryCalClass()
	{
		return array('polish_carat'=>'carats','polish_pcs'=>'pcs','price'=>'price','amount'=>'amount');
	}
	
	public function getMemoDetail($id)
	{
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_outward WHERE id=".$id);
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
			
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE id=".$pid);
			$temp = array();
			while($row = mysqli_fetch_assoc($rs))
			{
				$temp['pcs'] = $row['polish_pcs'];
				$temp['carat'] = $row['polish_carat'];
				$temp['price'] = $row['price'];
				$temp['amount'] = $row['amount'];
				$temp['sku'] = ($row['sku'] !='')?$row['sku']:$row['mfg_code'];
				$temp['color'] = $row['color'];
				$temp['clarity'] = $row['clarity'];
				$temp['size'] = $row['size'];
				$temp['main_color'] = $row['main_color'];
				$temp['size'] = $row['size'];
				$temp['report_no'] = $row['report_no'];
				$temp['shape'] = $row['shape'];
				$temp['outward_parent'] = $row['outward_parent'];
				$temp['sell_price'] = $row['sell_price'];
				$temp['sell_amount'] = $row['sell_amount'];
				
				$pcss += (int)$row['polish_pcs'];
				$carats += (float)$row['polish_carat'];
				
			}
			$record[$i] = $temp;
			$i++;
		}
		$data['carat'] = $carats;
		$data['pcs'] = $pcss;
		$data['record'] = $record;
		return $data;	
	}
	
	public function getTotalOfInOut($inout,$report)
    {
		$data = array();
		$tpp = $tpc =  $tp = $ta = 0.0; 	
		if($inout =="outward")
		{	
			$inward = " and type='".$report."'";			
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_outward WHERE company=".$_SESSION['companyId'].$inward." ORDER BY date desc");
			while($row = mysqli_fetch_assoc($rs))
			{
				$products = $row['products'];
				unset($row['products']);
				
				foreach(explode(',',$products) as $k=>$id )
				{
					$rs1 = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE id=".$id);
					while($jData = mysqli_fetch_assoc($rs1))
					{
						$tpp += (float) $jData['polish_pcs'];
						$tpc += (float) $jData['polish_carat'];			
						$tp += (float) $jData['price'];
						$ta += (float) $jData['amount'];						
					}
				}				
			}				
		}
		elseif($inout =="inward")
		{
			$outward = " and inward_type='".$report."'";		
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_inward WHERE company=".$_SESSION['companyId'].$outward ." ORDER BY date desc");
			while($row = mysqli_fetch_assoc($rs))
			{
				$id = $row['id'];
				
				$rs1 = mysqli_query($this->conn,"SELECT sum(p.polish_pcs) polish_pcs,sum(p.polish_carat) polish_carat,sum(p.price) price,sum(p.amount) amount  FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE p.inward_id=".$id);
				while($jData = mysqli_fetch_assoc($rs1))
				{
					$tpp += (float) $jData['polish_pcs'];
					$tpc += (float) $jData['polish_carat'];			
					$tp += (float) $jData['price'];
					$ta += (float) $jData['amount'];
				}
			}
		}
		$row['tpp'] =  $tpp;
		$row['tpc'] =  $tpc;
		$row['tp'] =  $tp;
		$row['ta'] =  $ta;
		$data =  $row;	
		return  $data;			
    }
	public function getDetailBySku($sku)
    {
		$data = array();
		$sku = trim($sku);
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE sku='".$sku."'");
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row; 
			break;
		}
		return  $data;			
    }	
	public function getDetailBySkuForUpdate($sku)
    {
		$data = array();
		$sku = trim($sku);
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_product WHERE sku='".$sku."'");
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$rs1 = mysqli_query($this->conn,"SELECT * FROM dai_product_value WHERE product_id=".$row['id']);
		
			while($row1 = mysqli_fetch_assoc($rs1))
			{
					$row['record'] = $row1;
			}			
			$data = $row; 
			break;
		}
		return  $data;			
    }	
	public function getHistoryOfStone($id)
    {
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_history WHERE product_id=".$id." ORDER BY date");
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row; 			
		}
		return  $data;			
    }	
	public function getTransactionOfStone($id)
    {
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_boxhistory WHERE product_id=".$id);
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row; 			
		}
		return  $data;			
    }
	public function getAllSku()
    {
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_jewelry WHERE visibility = 1 and outward = ''  and company=".$_SESSION['companyId']);
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row['sku']; 
		}
		return  $data;			
    }
	public function getAllSkuReport()
    {
		$data = array();
		
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE visibility = 1 and polish_carat <>0 and p.box_id='' and p.parcel_id='' and p.outward <> 'sale' and p.outward <> 'export' and outward_parent =0  and company=".$_SESSION['companyId']);
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row['sku'];
			$data[] = $row['report_no'];
			
		}
		return  $data;			
    }
	public function addHistory($post)
    {
		$post['user'] = $_SESSION['userid'];
		if(isset($post['description']))
		{
			$post['description'] = strtoupper($post['description']);
		}	
		
		$data = $this->getInsertString($post);	
		$sql1 = "INSERT INTO dai_history (". $data[0].") VALUES (".$data[1].")";
		
		$rs1 = mysqli_query($this->conn,$sql1);
		if(!$rs1)
		{
			$rs1 = mysqli_error();
		}
		return  $rs1;			
    }
	public function getStoneAction()
	{
		$data = array(
			'import'=>'Import',
			'purchase'=>'Purchase',
			'lab'=>'Send To Lab',
			'lab_return'=>'Lab Return',
			'memo'=>'Memo',
			'sale'=>'Sale',
			'sale_return'=>'Sale Return',
			'sale_close'=>'Sale Close',
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
			'in_jewelry'=>'In Jewelry',			
		);
		
		return $data;
	}	
	public function getAllShipping()
	{
		$data = array(
			'FedEx'=>'FedEx',
			'G4S'=>'G4S',
			'Brinks'=>'Brinks',			
			'Malca Amit'=>'Malca Amit',
			'Express Arrival'=>'Express Arrival',
			'Ferrari'=>'Ferrari'				
		);		
		return $data;
	}
	public function getAllOrigin()
	{
		$data = array(
			'India'=>'India',
			'Belgium'=>'Belgium',							
		);		
		return $data;
	}
	public function getSiteUpdated()
    {
		$data = array();
		
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_product WHERE site_upload = 0");
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row['id']; 
		}
		return  $data;			
    }
	
	public function getRapUpdated()
    {
		$data = array();
		
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_product WHERE rapnet_upload = 0");
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row['id']; 
		}
		return  $data;			
    }
	public function getRShape($s)
	{
		$shape = strtolower($s);
		
		$shapeArray = array('Round','Cushion','Oval','Heart','Marquise','Emerald','Radiant','Pear','Asscher','Princess','Trilliant','Rose','Shield','Square');
		foreach($shapeArray as $k=>$v )
		{
			
			if (strpos($shape, strtolower($v)) !== false) {
				return strtolower($v);
			}
		}
		return "";
	}
	public function getRColor($col)
	{
		$col1 = strtolower($col);
		$return = "";
		if (strpos($col1, 'fancy') !== false) 
		{
			return "";
		}
		else
		{
			if($col1=="")
				return "";
				
			return $col1[0];
		}
		
	}
	public function RapnetPrice($shape,$color,$clarity,$carat,$price)
	{
		$data['price'] =''; 
		$data['discount'] ='N/A'; 
		$rColor = $this->getRColor($color);
		$rShape = $this->getRShape($shape);
		
		$clarity  = strtolower($clarity);
		if($rColor != "")		
		{
			if($rShape != 'round')
				$rShape = 'pear';
			
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_rapnetprice WHERE shape='".$rShape."' and color='".$rColor."' and clarity='".$clarity."' and low_size <= ".$carat." and high_size >= ".$carat);
			
			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data['price'] = $row['caratprice']; 				
				$data['discount'] = number_format((($price*100)/$row['caratprice'])-100,2);
			}
		}
		return $data;
	}
	public function getBookTransaction($post)
    {
		$fd = $post['cfrom'];
		$td = $post['cto'];
		$data = array();
		if($post['book'] == '')
				return $data;
		$party = $adate = $book  = $currency = $sgroup = $group = "" ;
	
		if(isset($post['party']) and $post['party']!=0 )		
			$party= " and party=".$post['party'];
			
		if(isset($post['under_group']) and $post['under_group']!=0 )		
			 $sgroup = " and under_subgroup=".$post['under_group'];	
		
		if(isset($post['group']) and $post['group']!=0 )		
			 $group = " and under_group=".$post['group'];	
			 
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
		
		if($post['book'] == '')
		{
			foreach($this->getAllBook() as $k=>$v)
			{
				$book = " and book ='".$k."'";
				$rs = mysqli_query($this->conn,"SELECT * FROM acc_transaction WHERE deleted = 0 and company=".$_SESSION['companyId'].$party.$adate.$book.$sgroup.$group." ORDER BY date");
					
				while($row = mysqli_fetch_assoc($rs))
				{
					$data[$k][] = $row;
				}
			}
		}
		else
		{
			$book = " and book ='".$_POST['book']."'";
			$rs = mysqli_query($this->conn,"SELECT * FROM acc_transaction WHERE deleted = 0 and company=".$_SESSION['companyId'].$party.$adate.$book.$sgroup.$group." ORDER BY date");
				
			while($row = mysqli_fetch_assoc($rs))
			{
				$data[] = $row;
			}
		}
		return  $data;			
    }
	
	public function getAdvanceTransaction($post)
    {
		$data = array();
		$party = "" ;
	
		if(isset($post['party']) and $post['party']!=0 )		
			$party= " and party=".$post['party'];
			
		$rs = mysqli_query($this->conn,"SELECT * FROM acc_advance WHERE deleted = 0 and company=".$_SESSION['companyId'].$party." ORDER BY date");
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		return  $data;			
    }
	
	public function getAllCurrency($t="")
	{
		//$data = array('USD'=>'USD','HKD'=>'HKD','RMB'=>'RMB','BAHT'=>'BAHT');
		$data = array();	
		if($t=="all")
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM `dai_book`");
		}
		else
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM `dai_book` group by currency");
		}		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row['currency'];
		}		
		return $data;
	}
	public function getAllBook()
	{
		//$data = array('cash'=>'Cash','bank'=>'Bank','credit'=>'Credit');
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_book WHERE company = ".$_SESSION['companyId']);
		$data = array();		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row['book'].'-'.$row['currency'];
		}
		return $data;
	}
	public function getInvetoryTransaction($post)
    {
	
		$data = array();
		$type ="" ;
		if($_POST['type'] =="outward")
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_outward WHERE company=".$_SESSION['companyId']." and id=".$post['eid']);
		else
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_inward WHERE company=".$_SESSION['companyId']." and id=".$post['eid']);
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$row['record'] = array();
			$data = $row;
		}
		
		if($_POST['type'] =="outward")
			$type = " and sale_id ='".$_POST['eid']."'";
		else	
			$type = " and purchase_id ='".$_POST['eid']."'";
	
		
		$rs = mysqli_query($this->conn,"SELECT * FROM acc_transaction WHERE deleted = 0 and company=".$_SESSION['companyId'].$type." ORDER BY date");
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$data['record'][] = $row;
		}
		return  $data;			
    }
	public function getInvetoryTransactionJew($post)
    {
		$data = array();
		$type ="" ;
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_sale WHERE company=".$_SESSION['companyId']." and id=".$post['eid']);
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$row['record'] = array();
			$data = $row;
		}
		
		$type = " and jew_sale_id ='".$_POST['eid']."'";
	
		
		$rs = mysqli_query($this->conn,"SELECT * FROM acc_transaction WHERE deleted = 0 and company=".$_SESSION['companyId'].$type." ORDER BY date");
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$data['record'][] = $row;
		}
		return  $data;			
    }
	public function getToImportData()
    {
		$data = array();
		
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_temp WHERE user=".$_SESSION['sequence']);
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row; 
		}
		return  $data;			
    }
	public function getMyNotes()
	{
		$data = array();
		
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_note WHERE user=".$_SESSION['userid']." and company=".$_SESSION['companyId'] ." ORDER BY status" );
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row; 
		}
		return  $data;	
	}
	
	public function dueSalePayment()
	{
		$data = array();	
		$date = date("Y-m-d");
		
		$sdate = strtotime("+7 day", strtotime($date));
		$ndate = date('Y-m-d', $sdate);

		
		
		if($_SESSION['type'] == 'admin')
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_outward WHERE duedate between '$date' and '$ndate' and due_amount<>0 and type='sale' and products<>'' and company=".$_SESSION['companyId']." ORDER BY entryno");
		else
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_outward WHERE duedate between '$date' and '$ndate' and due_amount<>0 and type='sale' and products<>'' and company=".$_SESSION['companyId']." and user = ".$_SESSION['userid']." ORDER BY entryno");
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$products = $row['products'];
			
			if($products != "")
			{	
				$rs1 = mysqli_query($this->conn,"SELECT sum(amount) amount FROM dai_product WHERE id in ('".$products."') ");
				while($jData = mysqli_fetch_assoc($rs1))
				{
					$row['total'] =  $jData['amount'];
					$data[] =  $row;
				}				
			}		
			
		}	
		return $data;		
	}
	public function duePurchasePayment()
	{
		$data = array();	
		$date = date("Y-m-d");
		
		$sdate = strtotime("+7 day", strtotime($date));
		$ndate = date('Y-m-d', $sdate);

		if($_SESSION['type'] == 'admin')
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_inward WHERE duedate between '$date' and '$ndate' and due_amount<>0 and inward_type='purchase' and company=".$_SESSION['companyId']);
		else
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_inward WHERE duedate between '$date' and '$ndate' and due_amount<>0 and inward_type='purchase' and company=".$_SESSION['companyId']." and user = ".$_SESSION['userid']);
		
		while($row = mysqli_fetch_assoc($rs))
		{
			
			$rs1 = mysqli_query($this->conn,"SELECT sum(amount) amount FROM dai_product WHERE inward_id =".$row['id']);
			while($jData = mysqli_fetch_assoc($rs1))
			{
				$row['total'] =  $jData['amount'];
				$data[] =  $row;
			}				
					
			
		}	
		return $data;
	}
	public function getPartyOption()
	{
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_party WHERE type='inventory' and company=".$_SESSION['companyId']." ORDER BY name");			
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row['name'];
		}	
		return  $data;		
	}	
	public function getCompanyList()
	{
		$rs=mysqli_query($this->conn,"select * from company");
			  
		$data = array();
		$index = 0;
		while ($company_list =  mysqli_fetch_assoc($rs))
		{		
			$data[$company_list['id']]  = $company_list;		
		}
		return $data;
	}
	public function getCompanyDetail($id)
	{
		$rs=mysqli_query($this->conn,"SELECT * from company where id = ".$id);
 
		$grid_data = array();
		while ($row =  mysqli_fetch_assoc($rs))
		{				
			$grid_data = $row ; 
			break;				
		}
		if(empty($grid_data))
		{
			$field = mysqli_num_fields( $rs );
   
			for ( $i = 0; $i < $field; $i++ ) {
		   
				$grid_data[mysqli_fetch_field_direct( $rs, $i )] = "";
		   
			}
		}
		return $grid_data;	
	}
	public function getPartyByUser()
	{
		$data = array();
	
		if($_SESSION['type'] == 'admin')
			$rs=mysqli_query($this->conn,"SELECT * from dai_party where type='inventory' ");
		else
			$rs=mysqli_query($this->conn,"SELECT * from dai_party where type='inventory' and user = ".$_SESSION['userid']);
		
		while ($row =  mysqli_fetch_assoc($rs))
		{				
			$data[] = $row ; 
							
		}
		return $data;
	}		
	public function getDataBySku($sku)
	{
		$sku = trim($sku);
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE visibility=1 and sku='".$sku."' ");
		//$rs = mysqli_query($this->conn,"SELECT * FROM dai_product WHERE visibility=1 and  sku='".$sku."' ");
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
			break;
		}		
		return  $data;			    	
	}
	public function getGoldType()
	{
		$data = array();
		$data['14K GOLD'] = '14K GOLD';
		$data['16K GOLD'] = '16K GOLD';
		$data['18K GOLD'] = '18K GOLD';
		$data['10K GOLD'] = '10K GOLD';
		return $data;
	}
	public function getAllInvoiceOfParty($pid)
	{
		
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_outward WHERE  type in ('sale','export') and  status in ('on_sale','on_export') and party='".$pid."' ");
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			if($row['due_amount'] > 0)
			$data[$row['invoiceno']] =  $row['invoiceno'];			
		}		
		return  $data;			    	
	}
	
	public function getAllStockDate()
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_stockmanage");
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] =  $row['stockdate'];			
		}		
		return  $data;			    	
	}
	
	public function getStockDateData($id)
	{
		$data = array();
		if($id == '' || $id == 0)
			return $data;
			
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_stockmanage WHERE id=".$id);
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  (array)json_decode($row['data'],true);			
		}		
		return  $data;			    	
	}
	
	public function getPartyAccountTransaction($post)
    {
		$fd = $post['cfrom'];
		$td = $post['cto'];
		$data = array();
		$party = $adate = $book  = $currency = $sgroup = $group = "" ;
	
		if(isset($post['party']) && $post['party']!=0 )		
			$party= " and party=".$post['party'];
			
		if(isset($post['under_group']) && $post['under_group']!=0 )		
			 $sgroup = " and under_subgroup=".$post['under_group'];	
		
		if(isset($post['group']) && $post['group']!=0 )		
			 $group = " and under_group=".$post['group'];	
			 
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
		
		
		$book = "";
		if(isset($post['book']) && $post['book']!="" )
		{
			$book = " and book ='".$_POST['book']."'";
		}
		
		$rs = mysqli_query($this->conn,"SELECT * FROM acc_transaction WHERE deleted = 0 and company=".$_SESSION['companyId'].$party.$adate.$book.$sgroup.$group." ORDER BY date");
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		
		return  $data;			
    }
	public function getAllUser()
	{
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM user ");
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['user_id']] = $row;
		}
		return $data;
	}
	public function getUserDetail($id =0)
	{
		
		$data = array();
		
		if($id == 0 || $id == '')
			return $array;
		
		$rs = mysqli_query($this->conn,"SELECT * FROM user where user_id=".$id);
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		
		return $data;
	}
	
	public function getChatHistory($from,$to)
	{
		$data = array();
		
		$rs = mysqli_query($this->conn,"SELECT * FROM chat_history where ( sender = ".$from." and  receiver =".$to." ) || (sender = ".$to." and  receiver = ".$from.") ORDER BY date");
			
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function getLiveChatHistory($from,$to)
	{
		$data = array();		
		
		$rs = mysqli_query($this->conn,"SELECT * FROM chat_history where (sender = ".$to." and  receiver = ".$from.") and new = 1 ORDER BY date");			
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function setChatViewed($id)
	{
		$sql = "UPDATE chat_history SET new=0 WHERE  id=".$id;
		$rs = mysqli_query($this->conn,$sql);
	}
	public function getDateDifferenece($date)
	{
		
			$date1 = new DateTime($date);		 
			$date2 = new DateTime(date("Y-m-d H:i:s"));
			
			$interval = $date1->diff($date2);
			$years = $interval->format('%y');
			$months = $interval->format('%m');
			$days = $interval->format('%d');
			$hours = $interval->format('%h');
			$minute = $interval->format('%i');
			$second = $interval->format('%s');
			if($years!=0){
				$ago = $years.' years ago';
			}elseif($months != 0)
			{
				$ago = $months.' months ago';
			}
			elseif($days != 0)
			{
				$ago = $days.' days ago';
			}
			elseif($hours != 0)
			{
				$ago = $hours.' hours ago';
			}
			elseif($minute != 0)
			{
				$ago = $minute.' minutes ago';
			}
			else
			{
				$ago = $second.' seconds ago';
			}
			return $ago; 
	}
	
	
	public function getNewMessageCount($user)
	{
		$data = '';		
		
		$rs = mysqli_query($this->conn,"SELECT count(*) cnt FROM chat_history where sender = ".$user." and new = 1");			
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row['cnt'];
		}
		return $data;
	}
	
	
}
<?php
include('../../../variable.php');
include_once('../../../database.php');
include_once('../../jHelper.php');
include_once('../../Helper.php');
include_once ($daiDir.'Classes/PHPExcel/IOFactory.php');
class inwardModel
{
    public $table;
	public $table_product;
	public $table_product_value;
	public $table_product_detail;
	public $jhelper;
	public $helper;
	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn = $db;
            $this->table  = "jew_inward";
			$this->table_product  = "jew_product";			
			$this->table_product_detail  = "jew_product_detail";			
			$this->helper  = new Helper($db);
			$this->jhelper  = new jHelper($db);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getAllData()
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." ORDER BY date desc");
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] =  $row;
		}		
		return  $data;			
    }
	// get single Ledger Data
	public function getData($id)
    {
		
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." and id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
	
		if(empty($data))
		{
			$field = mysqli_num_fields( $rs );
   
			for ( $i = 0; $i < $field; $i++ ) {
		   
				$data[mysqli_fetch_field_direct( $rs, $i )] = "";
		   
			}
		}	
		else
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE p.inward_id=".$data['id'] );
			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data['record'][] = $row;
			}
		}		
		return  $data;	
					
    }
	
	public function getInwardData($id)
    {
		
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." and id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}	
		return  $data;	
					
    }
	public function getRecordData($id)
    {
		
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." WHERE  inward_id = '".$id."'" );
		
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row;
		}
		return  $data;			
    }
	
	public function getIncrementEntry($of = "")
	{
		if($of=="")
			return "";
		$rs = mysqli_query($this->conn,"SELECT ".$of." FROM jew_incrementid WHERE company=".$_SESSION['companyId'] );
			
		$data = "";
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row[$of];
			break;
		}
		return $data;
	}
	
	// ADD new Ledger
    public function saveData($post)
    {
		/* echo '<pre>';
		print_r($post);
		exit; */
		$iTotal = $iCarat = $iPcs = 0;
		$cid = $_SESSION['companyId'];
		$post['user'] = $_SESSION['userid'];
		
		if($post['terms'] =='' || $post['terms'] == 0)
			$post['duedate'] = $post['invoicedate'];
		
		$post['date'] = $post['invoicedate'];
		$post['company'] = $cid;
		$incre_id = $this->getIncrementEntry('inward');
		$reference = $this->getIncrementEntry('reference');
		$post['entryno'] = $incre_id;
		$post['import_for'] = 'stone';
		$post['reference'] = $reference;
		if(isset($post['record']))
		{
			$record = $post['record'];
			unset($post['record']);
		}
		unset($post['btnsave']);
		unset($post['import']);
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
		}
		else
		{
			
			$lid = mysqli_insert_id($this->conn);
			//$_SESSION['last_inward'] = $lid; 
				
			$temp = explode("-",$incre_id);
			$temp[1] = $temp[1] + 1;
			$setNewid = $temp[0]."-".$temp[1];
			$reference++;
			$sql = "UPDATE jew_incrementid  SET inward='$setNewid',reference='$reference' WHERE company=".$_SESSION['companyId'];		
			$rs = mysqli_query($this->conn,$sql);
			//print_r($record);
			$i=1;
			
			$iProducts = array();
			$iTotal = 0.0;	
			
			if($post['inward_type'] == "import")
			{
			$sqlj = mysqli_query($this->conn,"SELECT * FROM jew_temp WHERE user=".$_SESSION['sequence']);
		
			while($row = mysqli_fetch_assoc($sqlj))
			{
				$data = $row; 
				
				$value = $data['value'];
				$r = (array)json_decode($value);
				
				if($r['sku']=="" || $r['carat']=="" )
					continue;
				$pDetail = array();
				if(isset($r['record']))
				{	
					$pDetail = (array)$r['record'];
					unset($r['record']);
				}
				
				$SkuData = $this->helper->getDataBySku($r['sku']);
				if(!empty($SkuData))
					continue;
					
				if($r['amount'] == "")
				$r['amount'] = $r['price'] * $r['carat'];
				$iTotal += (float)$r['amount'];
				$iCarat += (float)$r['carat'];
				
				if($r['pcs'] !='')
					$iPcs += (float)$r['pcs'];
				
			 
				$cid = $_SESSION['companyId'];	
				
				//$r['date'] = date("Y-m-d H:i:s");
				$r['inward_id'] = $lid;
				$r['company'] = $cid;
				$r['purchase_pcs'] =$r['pcs'];
				$r['purchase_carat'] =$r['carat'];
				$r['purchase_price'] =$r['price'];
				$r['purchase_amount'] =$r['amount'];
				
				$r['user'] = $_SESSION['userid'];
				$pc = $r['pcs'];
				$group = "";
				
				if($pc == 1 || $pc == 1.00)
				{	$group = "single"; }
				else if($pc > 1)
				{	$group = "box"; }
				else if($pc == "" || $pc==0)
				{
					$group = "parcel"; 
				}
				
					
				$r['group_type'] = 'single';
				$r['visibility'] = 1;
				
				$data2 = $this->helper->getInsertString($r);	
				$sql1 = "INSERT INTO ".$this->table_product ." (". $data2[0].") VALUES (".$data2[1].")";		
			
				$rs = mysqli_query($this->conn,$sql1);
				if(!$rs)			
				{
					return mysqli_error();
					
				}
				else
				{
					$pid = mysqli_insert_id($this->conn);	
					
					$iProducts[] = $pid; 
					
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = $post['inward_type'];
					$history['party'] = $post['party'];		
					$history['narretion'] = $post['narretion'];
					$history['date'] = $post['invoicedate'];
					$history['description'] = "New Stone ".$post['inward_type']." with reference no is ".$post['reference'];
					$history['pcs'] = $r['pcs'];
					$history['carat'] = $r['carat'];
					$history['amount'] = $r['amount'];
					$history['price'] = $r['price'];
					$history['sku'] = $r['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $post['invoiceno'];
					$history['entry_from'] = 'inward';
					$history['entryno'] = $lid;	
					$history['for_history'] = 'main';	
					$rs = $this->jhelper->addHistory($history);	


					foreach($pDetail as $k=>$pd)
					{
						if($pd == "")
							continue;
						$pd = (array)$pd;
						$pd['product_id'] = $pid;
						$data1 = $this->helper->getInsertString($pd);	
						$sql1 = "INSERT INTO ".$this->table_product_detail." (". $data1[0].") VALUES (".$data1[1].")";		
					
						$rs1 = mysqli_query($this->conn,$sql1);
						if(!$rs1)			
						{
							return mysqli_error();
							
						}
					}
				}					
				
			}
			}
			else
			{
				foreach($record as $r)
				{
					
				
					if($r['sku']=="" || $r['carat']=="" )
						continue;
					
					//$pDetail = (array)$r['record'];
					//unset($r['record']);
					
					//$SkuData = $this->helper->getDataBySku($r['sku']);
					
					$iTotal += (float)$r['amount'];
					$iCarat += (float)$r['carat'];
					
					if($r['pcs'] !='')
						$iPcs += (float)$r['pcs'];
					
				 
					$cid = $_SESSION['companyId'];	
					
					//$r['date'] = date("Y-m-d H:i:s");
					$r['inward_id'] = $lid;
					$r['company'] = $cid;
					$r['purchase_pcs'] =$r['pcs'];
					$r['purchase_carat'] =$r['carat'];
					$r['purchase_price'] =$r['price'];
					$r['purchase_amount'] =$r['amount'];
					
					$r['user'] = $_SESSION['userid'];
					$pc = $r['pcs'];
					$group = "";
					
					if($pc == 1 || $pc == 1.00)
					{	$group = "single"; }
					else if($pc > 1)
					{	$group = "box"; }
					else if($pc == "" || $pc==0)
					{
						$group = "parcel"; 
					}
					
						
					$r['group_type'] = "single";
					$r['visibility'] = 1;
					
					
					$data2 = $this->helper->getInsertString($r);	
					$sql1 = "INSERT INTO jew_product (". $data2[0].") VALUES (".$data2[1].")";		
				
					$rs = mysqli_query($this->conn,$sql1);
					if(!$rs)			
					{
						return mysqli_error();
						
					}
					else
					{
						$pid = mysqli_insert_id($this->conn);	
						
						$iProducts[] = $pid; 
						
						$history =array();
						$history['product_id'] = $pid;
						$history['action'] = $post['inward_type'];
						$history['party'] = $post['party'];		
						$history['narretion'] = $post['narretion'];
						$history['date'] = $post['invoicedate'];
						$history['description'] = "Loose Stone ".$post['inward_type']." with reference no is ".$post['reference'];
						$history['pcs'] = $r['pcs'];
						$history['carat'] = $r['carat'];
						$history['amount'] = $r['amount'];
						$history['price'] = $r['price'];
						$history['sku'] = $r['sku'];
						$history['type'] = 'cr';
						$history['invoice'] = $post['invoiceno'];
						$history['entry_from'] = 'inward';
						$history['entryno'] = $lid;
						$history['for_history'] = 'main';			
						$rs = $this->jhelper->addHistory($history);	

					}					
					
				}
			}
				
			//$sql = "UPDATE ".$this->table." SET products='".implode(",",$iProducts)."', due_amount=$iTotal,final_amount=$iTotal,carat=$iCarat,pcs=$iPcs  WHERE id=".$lid;		
			$sql = "UPDATE ".$this->table." SET products='".implode(",",$iProducts)."',carat=$iCarat, pcs=$iPcs, due_amount=$iTotal, final_amount = $iTotal  WHERE id=".$lid;		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();					
			}
			
			$sql = "DELETE FROM jew_temp WHERE code=0 and user = ".$_SESSION['sequence'];
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)			
				$rs = mysqli_error();	
		}
		return $rs;
    }
	
	public function colletSaveData($post)
    {
		$iTotal = $iCarat = $iPcs = 0;
		$cid = $_SESSION['companyId'];
		$post['user'] = $_SESSION['userid'];
		
		if($post['terms'] =='' || $post['terms'] == 0)
			$post['duedate'] = $post['invoicedate'];
		
		$post['date'] = $post['invoicedate'];
		$post['company'] = $cid;
		$incre_id = $this->getIncrementEntry('inward');
		$reference = $this->getIncrementEntry('reference');
		$post['entryno'] = $incre_id;
		$post['import_for'] = 'stone';
		$post['reference'] = $reference;
		if(isset($post['record']))
		{
			$record = $post['record'];
			unset($post['record']);
		}
		if(isset($post['collet']))
		{
			$colleArray = array();
			$colleArray = $post['collet'];
			unset($post['collet']);
		}
		unset($post['btnsave']);
		unset($post['import']);
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
		}
		else
		{
			
			$lid = mysqli_insert_id($this->conn);
			//$_SESSION['last_inward'] = $lid; 
				
			$temp = explode("-",$incre_id);
			$temp[1] = $temp[1] + 1;
			$setNewid = $temp[0]."-".$temp[1];
			$reference++;
			$sql = "UPDATE jew_incrementid  SET inward='$setNewid',reference='$reference' WHERE company=".$_SESSION['companyId'];		
			$rs = mysqli_query($this->conn,$sql);
			//print_r($record);
			$i=1;
			
			$iProducts = array();
			$iTotal = 0.0;	
			
			if($post['inward_type'] == "import")
			{
			$sqlj = mysqli_query($this->conn,"SELECT * FROM jew_temp WHERE user=".$_SESSION['sequence']);
		
			while($row = mysqli_fetch_assoc($sqlj))
			{
				$data = $row; 
				
				$value = $data['value'];
				$r = (array)json_decode($value);
				
				if($r['sku']=="" || $r['carat']=="" )
					continue;
				$pDetail = array();
				if(isset($r['record']))
				{	
					$pDetail = (array)$r['record'];
					unset($r['record']);
				}
				$SkuData = $this->helper->getDataBySku($r['sku']);
				if(!empty($SkuData))
					continue;
					
				if($r['amount'] == "")
				$r['amount'] = $r['price'] * $r['carat'];
				$iTotal += (float)$r['amount'];
				$iCarat += (float)$r['carat'];
				
				if($r['pcs'] !='')
					$iPcs += (float)$r['pcs'];
				
			 
				$cid = $_SESSION['companyId'];	
				
				//$r['date'] = date("Y-m-d H:i:s");
				$r['inward_id'] = $lid;
				$r['company'] = $cid;
				$r['purchase_pcs'] =$r['pcs'];
				$r['purchase_carat'] =$r['carat'];
				$r['purchase_price'] =$r['price'];
				$r['purchase_amount'] =$r['amount'];
				if($r['report_no'] != "")
				{	
					$r['lab'] = 'IGI';
					$r['igi_code'] = $r['report_no'];
				}
				
				$r['user'] = $_SESSION['userid'];
				$pc = $r['pcs'];
				$group = "";
				
				if($pc == 1 || $pc == 1.00)
				{	$group = "single"; }
				else if($pc > 1)
				{	$group = "box"; }
				else if($pc == "" || $pc==0)
				{
					$group = "parcel"; 
				}
				
					
				$r['group_type'] = 'single';
				$r['visibility'] = 1;
				$r['is_collet'] = 1;
				$r['outward'] = 'collet';
				
				$data2 = $this->helper->getInsertString($r);	
				$sql1 = "INSERT INTO ".$this->table_product ." (". $data2[0].") VALUES (".$data2[1].")";		
			
				$rs = mysqli_query($this->conn,$sql1);
				if(!$rs)			
				{
					return mysqli_error();
					
				}
				else
				{
					$pid = mysqli_insert_id($this->conn);	
					
					$iProducts[] = $pid; 
					
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = $post['inward_type'];
					$history['party'] = $post['party'];		
					$history['narretion'] = $post['narretion'];
					$history['date'] = $post['invoicedate'];
					$history['description'] = "New Collet Stone ".$post['inward_type']." with reference no is ".$post['reference'];
					$history['pcs'] = $r['pcs'];
					$history['carat'] = $r['carat'];
					$history['amount'] = $r['amount'];
					$history['price'] = $r['price'];
					$history['sku'] = $r['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $post['invoiceno'];
					$history['entry_from'] = 'inward';
					$history['entryno'] = $lid;	
					$history['for_history'] = 'main';	
					$rs = $this->jhelper->addHistory($history);	
					
					
					$pDetail['product_id'] = $pid;
					$pDetail['sku'] = $r['sku'];
					$pDetail['user'] = $post['user'];
					$pDetail['company'] = $cid;
					$pDetail['type'] = 'collet_receive';
					$pDetail['date'] = $post['invoicedate'];
					$data1 = $this->helper->getInsertString($pDetail);	
					$sql1 = "INSERT INTO jew_collet (". $data1[0].") VALUES (".$data1[1].")";		
					$rs1 = mysqli_query($this->conn,$sql1);
					if(!$rs1)			
					{
						return mysqli_error();
						
					}
					
				}					
				
			}
			}
			else
			{
				$s=0;
				foreach($record as $r)
				{
					$s++;
					if($r['sku']=="" || $r['carat']=="" )
					{	
						unset($colleArray[$s]);
						continue;
					}	
					
					//$pDetail = (array)$r['record'];
					//unset($r['record']);
					
					//$SkuData = $this->helper->getDataBySku($r['sku']);
					
					$iTotal += (float)$r['amount'];
					$iCarat += (float)$r['carat'];
					
					if($r['pcs'] !='')
						$iPcs += (float)$r['pcs'];
					
				 
					$cid = $_SESSION['companyId'];	
					
					//$r['date'] = date("Y-m-d H:i:s");
					$r['inward_id'] = $lid;
					$r['company'] = $cid;
					$r['purchase_pcs'] =$r['pcs'];
					$r['purchase_carat'] =$r['carat'];
					$r['purchase_price'] =$r['price'];
					$r['purchase_amount'] =$r['amount'];
					if($r['report_no'] != "")
					{	
						$r['lab'] = 'IGI';
						$r['igi_code'] = $r['report_no'];
					}
					$r['user'] = $_SESSION['userid'];
					$pc = $r['pcs'];
					$group = "";
					
					if($pc == 1 || $pc == 1.00)
					{	$group = "single"; }
					else if($pc > 1)
					{	$group = "box"; }
					else if($pc == "" || $pc==0)
					{
						$group = "parcel"; 
					}
					
						
					$r['group_type'] = "single";
					$r['visibility'] = 1;
					$r['is_collet'] = 1;
					$r['outward'] = 'collet';
					
					$data2 = $this->helper->getInsertString($r);	
					$sql1 = "INSERT INTO jew_product (". $data2[0].") VALUES (".$data2[1].")";		
				
					$rs = mysqli_query($this->conn,$sql1);
					if(!$rs)			
					{
						return mysqli_error();
						
					}
					else
					{
						$pid = mysqli_insert_id($this->conn);	
						
						$iProducts[] = $pid; 
						
						$history =array();
						$history['product_id'] = $pid;
						$history['action'] = $post['inward_type'];
						$history['party'] = $post['party'];		
						$history['narretion'] = $post['narretion'];
						$history['date'] = $post['invoicedate'];
						$history['description'] = "Collet Stone ".$post['inward_type']." with reference no is ".$post['reference'];
						$history['pcs'] = $r['pcs'];
						$history['carat'] = $r['carat'];
						$history['amount'] = $r['amount'];
						$history['price'] = $r['price'];
						$history['sku'] = $r['sku'];
						$history['type'] = 'cr';
						$history['invoice'] = $post['invoiceno'];
						$history['entry_from'] = 'inward';
						$history['entryno'] = $lid;
						$history['for_history'] = 'main';			
						$rs = $this->jhelper->addHistory($history);	
						
						$colleArray[$s]['product_id'] = $pid;
						$colleArray[$s]['sku'] = $r['sku'];
						$colleArray[$s]['user'] = $post['user'];
						$colleArray[$s]['company'] = $cid;
						$colleArray[$s]['type'] = 'collet_receive';
						$colleArray[$s]['date'] = $post['invoicedate'];
					}					
					
				}
				
				foreach($colleArray as $collet)
				{
						$data1 = $this->helper->getInsertString($collet);	
						$sql1 = "INSERT INTO jew_collet (". $data1[0].") VALUES (".$data1[1].")";		
						$rs1 = mysqli_query($this->conn,$sql1);
						if(!$rs1)			
						{
							return mysqli_error();
						}	
				}	
			}
				
			//$sql = "UPDATE ".$this->table." SET products='".implode(",",$iProducts)."', due_amount=$iTotal,final_amount=$iTotal,carat=$iCarat,pcs=$iPcs  WHERE id=".$lid;		
			$sql = "UPDATE ".$this->table." SET products='".implode(",",$iProducts)."',carat=$iCarat, pcs=$iPcs, due_amount=$iTotal, final_amount = $iTotal  WHERE id=".$lid;		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();					
			}
			
			$sql = "DELETE FROM jew_temp WHERE code=0 and user = ".$_SESSION['sequence'];
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)			
				$rs = mysqli_error();	
		}
		return $rs;
    }
	
	public function updateData($post)
    {
		$record = $post['record'];
		unset($post['record']);
		
		$data = $this->helper->getUpdateString($post);	
	
	    $sql = "UPDATE ".$this->table." SET ".$data.",site_upload=1,rapnet_upload=1 WHERE id=".$post['id'];		
	    $rs = mysqli_query($this->conn,$sql);
	    if(!$rs)
		{
			$rs = mysqli_error();
			
		}
		else
		{
			foreach($record as $r)
			{
				$rdata = $this->helper->getUpdateString($r);	
				$sql = "UPDATE ".$this->table_detail." SET ".$rdata." WHERE id=".$r['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					break;
				}
			}
		}		
		return $rs;
    }

   
    public function delete($id,$eid)
    {
		foreach($this->getRecordData($eid) as $rd)
		{
			$sql = "DELETE FROM ".$this->table_detail ." WHERE id = ".$rd['id'];
			$rs = mysqli_query($this->conn,$sql);
		}
        $sql = "DELETE FROM ".$this->table ." WHERE id = ".$id;
        $rs = mysqli_query($this->conn,$sql);
	    return $rs;
    }
	
	public function stoneSave($inputFileName)
    {
		$attribute = $this->jhelper->getStoneImportAttribute();
	
		try {
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		$sql = "TRUNCATE table jew_temp";
		$rs = mysqli_query($this->conn,$sql);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet

		$seq = $_SESSION['sequence'];
		for($i=2;$i<=$arrayCount;$i++)
		{
			$c = 'A'; 
			$temp = array();
			$count =1;
			foreach($attribute as $k=>$value)
			{
			//echo "<pre>";
			//print_r($allDataInSheet[$i]);
				//echo "<br>$c => $k =>". $allDataInSheet[$i][$c];
				if($c =='C' && $allDataInSheet[$i][$c] =='')
				{
					$t = $allDataInSheet[$i]['A'];
					$t .= ($allDataInSheet[$i]['B'] !='') ? '-'.$allDataInSheet[$i]['B']:''; 
					$temp[$k] = $t;
				}
				elseif($c == 'P' && $allDataInSheet[$i]['H'] > 1 && $allDataInSheet[$i]['P'] != "" )
				{
					//$temp[$k] = $allDataInSheet[$i][$c];
					$hVal = (int)$allDataInSheet[$i]['H'];
					$jr = 0;
					$record = array();
					$rc = $c;
					while($jr < $hVal)
					{
						//$rc = $c;
						//$rc++;
						$record[$jr]['carat'] = $allDataInSheet[$i][$rc];
						$rc++;
						$record[$jr]['height'] = $allDataInSheet[$i][$rc];
						$rc++;
						$record[$jr]['width'] = $allDataInSheet[$i][$rc];
						$rc++;
						$record[$jr]['length'] = $allDataInSheet[$i][$rc];
						$rc++;
						$jr ++;
					}
					$temp['record'] = $record;
					break;
				}
				else
				{
					if($count <=15)
						$temp[$k] = $allDataInSheet[$i][$c];
					
				}
				$count++;
				$c++;
			}
			
			$post['user'] = $seq;
			$post['no'] = $i;
			$post['code'] = 0;
			$post['value'] = json_encode($temp);
			
			$data = $this->helper->getInsertString($post);	
			$sql = "INSERT INTO jew_temp (". $data[0].") VALUES (".$data[1].")";
				
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
		}
			
	}
	
	public function colletSave($inputFileName)
    {
		$attribute = $this->jhelper->getColletImportAttribute();
	
		try {
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		$sql = "TRUNCATE table jew_temp";
		$rs = mysqli_query($this->conn,$sql);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet

		$seq = $_SESSION['sequence'];
		for($i=2;$i<=$arrayCount;$i++)
		{
			$c = 'A'; 
			$temp = $record = array();
			$count =1;
			foreach($attribute as $k=>$value)
			{
			//echo "<pre>";
			//print_r($allDataInSheet[$i]);
				//echo "<br>$c => $k =>". $allDataInSheet[$i][$c];
				if($c =='C' && $allDataInSheet[$i][$c] =='')
				{
					$t = $allDataInSheet[$i]['A'];
					$t .= ($allDataInSheet[$i]['B'] !='') ? '-'.$allDataInSheet[$i]['B']:''; 
					$temp[$k] = $t;
				}
				elseif($count > 18 && $count <= 32)
				{
					$record[$k] = $allDataInSheet[$i][$c];
					$temp['record'] = $record;
				}
				else
				{
					if($count <=18)
						$temp[$k] = $allDataInSheet[$i][$c];
					
				}
				$count++;
				$c++;
			}
			
			$post['user'] = $seq;
			$post['no'] = $i;
			$post['code'] = 0;
			$post['value'] = json_encode($temp);
			
			$data = $this->helper->getInsertString($post);	
			$sql = "INSERT INTO jew_temp (". $data[0].") VALUES (".$data[1].")";
				
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
		}
			
	}
	
	
	public function looseSave($inputFileName)
    {
			
		$attribute = $this->jhelper->getLooseImportAttribute();
	
		try {
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		$sql = "TRUNCATE table jew_temp_loose";
		$rs = mysqli_query($this->conn,$sql);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet

		$seq = $_SESSION['sequence'];
		for($i=2;$i<=$arrayCount;$i++)
		{
			$c = 'A'; 
			$temp = array();
			foreach($attribute as $k=>$value)
			{
				$temp[$k] = $allDataInSheet[$i][$c];				
				$c++;
			}
			
			$post['user'] = $seq;
			$post['no'] = $i;
			$post['code'] = 0;
			$post['value'] = json_encode($temp);
			
			/* echo "<pre>";
			print_r($temp);
			exit; */
			$data = $this->helper->getInsertString($post);	
			$sql = "INSERT INTO jew_temp_loose (". $data[0].") VALUES (".$data[1].")";
				
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
		}
			
	}
	public function getDetail($id,$t = 'main')
    {
		$data = array();		
		if( $t =='main')
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_product WHERE id=".$id);
		else
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product WHERE id=".$id);
		
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
		}				
		return  $data;			
    }
	public function updatePrice($post)
	{
		if($post['type'] == 'main')
		{
			foreach($post['product'] as $pid=>$price)
			{
				$data = $this->getDetail($pid);
				$amount  = $data['carat'] * $price['price'];
				$price1 = $price['price'];
				$cost = $price['cost'];
				$oldPrice = $data['price'];
				if($cost == "")
					$cost = 0;
				
				$sql = "UPDATE ".$this->table_product." SET cost=$cost,price=$price1,amount=$amount WHERE id=".$pid;		
				
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				//echo $rs;
				$history = array();
				$history['product_id'] = $pid;
				$history['action'] = 'price_change';
				$history['date'] = date("Y-m-d H:i:s");
				$history['narretion'] = "cost price or base price are changed.";
				$history['description'] = "Old Price :  ".$oldPrice ." , New Price :  ".$price1;
				$history['for_history'] = 'main';	
				$rs = $this->jhelper->addHistory($history);
			}
		}
		else
		{
			foreach($post['product'] as $pid=>$price)
			{
				$data = $this->getDetail($pid,'side');
				$amount  = $data['carat'] * $price['price'];
				$price1 = $price['price'];
				
				$oldPrice = $data['price'];
				
				
				$sql = "UPDATE jew_loose_product SET price=$price1,amount=$amount WHERE id=".$pid;		
				
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				//echo $rs;
				$history = array();
				$history['product_id'] = $pid;
				$history['action'] = 'price_change';
				$history['date'] = date("Y-m-d H:i:s");
				$history['narretion'] = "cost price or base price are changed.";
				$history['description'] = "Old Price :  ".$oldPrice ." , New Price :  ".$price1;
				$history['for_history'] = 'side';	
				$rs = $this->jhelper->addHistory($history);
			}
		}
		return $rs;
	}
	
	public function savePackage($post)
	{
		foreach($post['product'] as $pid=>$pac)
		{
			$sql = "UPDATE ".$this->table_product_value." SET package='$pac' WHERE product_id=".$pid;		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}			
		}
		return $rs;
	}
	
	public function updateSinglePrice($post)
	{
		$pid = $post['id'];
		$price = $post['price'];
		
		$data = $this->getDetail($pid);
		if($data['price']  == $price)
			return 1;
		
		$amount  = $data['carat'] * $price;
		$oldPrice = $data['price'];
		//$oldPrice = ($data['sell_price']=='0')?$data['price']:$data['sell_price'];
		
		//$sql = "UPDATE ".$this->table_product." SET sell_price=$price,sell_amount=$amount WHERE id=".$pid;		
		$sql = "UPDATE ".$this->table_product." SET price=$price,amount=$amount WHERE id=".$pid;		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		//echo $rs;
		$history = array();
		$history['product_id'] = $pid;
		$history['action'] = 'price_change';
		$history['date'] = date("Y-m-d H:i:s");
		$history['price'] = $price;
		$history['description'] = "for Sell Or Memo Old Price :  ".$oldPrice ." , New Price :  ".$price;
		$history['for_history'] = "main";
		$rs = $this->helper->addHistory($history);
		
		return $rs;
	}
	
	
	public function updateSinglePriceForIn($post)
	{
		$pid = $post['id'];
		$price = $post['price'];
		
		$data = $this->getDetail($pid);
		if($data['price']  == $price)
			return 1;
		
		$amount  = $data['carat'] * $price;
		$oldPrice = $data['price'];
		
		$sql = "UPDATE ".$this->table_product." SET price=$price,amount=$amount,site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		//echo $rs;
		$history = array();
		$history['product_id'] = $pid;
		$history['action'] = 'price_change';
		$history['date'] = date("Y-m-d H:i:s");
		$history['price'] = $price;
		$history['description'] = "for Sell Or Memo Old Price :  ".$oldPrice ." , New Price :  ".$price;
		$rs = $this->helper->addHistory($history);
		
		return $rs;
	}
	public function updateInward($post)
	{
		
		$record = $post['record'];
		$product = $post['products'];
		unset($post['record']);
		unset($post['products']);
		
		
		$final_pcs = $diff_carat = $diff_pcs = $final_carat= $tc = $tp = $ta = 0;
		
		$inproduct = array();
		foreach($record as $rec)
		{
			if(isset($rec['id']))
			{
				$pid = $rec['id'];
				$pdata = $this->getDetail($pid);
				
				if($pdata['purchase_carat'] != $rec['carat'])
				{
					$diff_carat = (float)$rec['carat'] - (float)$pdata['purchase_carat'];				
					$final_carat = (float)$pdata['purchase_carat'] + $diff_carat;
					$rec['purchase_carat'] = $final_carat;
					$rec['carat'] = (float)$pdata['carat'] + $diff_carat;									
				}
				else
				{
					unset($rec['carat']);
				}
				
				if($pdata['purchase_pcs'] != $rec['pcs'])
				{
					$diff_pcs = (float)$rec['pcs'] - (float)$pdata['purchase_pcs'];
					$final_pcs = (float)$pdata['purchase_pcs'] + (float)$diff_pcs;
					$rec['purchase_pcs'] = $final_pcs;
					$rec['pcs'] = (float)$pdata['pcs'] + $diff_pcs;				
				}
				else
				{
					unset($rec['pcs']);
				}
				$tc += (float)$final_carat;
				$tp += (float)$final_pcs;
				$ta += (float)$rec['purchase_amount'];

				/* $data = $this->helper->getUpdateString($rec);		
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
				} */
				
				$inproduct[] = $rec['id'];
			}
			else
			{
				$r = $rec;
				
				if($r['sku']=="" || $r['carat']=="" || $r['price']=="" || $r['amount']=="")
					continue;
					
				$SkuData = $this->helper->getDataBySku($r['sku']);
					
				$ta += (float)$r['amount'];
				$tc += (float)$r['carat'];
				
				if($r['pcs'] !='')
					$tp += (float)$r['pcs'];
				
			 
				$cid = $_SESSION['companyId'];	
				
				$r['date'] = date("Y-m-d H:i:s");
				$r['inward_id'] = $post['id'];
				$r['company'] = $cid;
				$r['purchase_pcs'] =$r['pcs'];
				$r['purchase_carat'] =$r['carat'];
				$r['purchase_price'] =$r['price'];
				$r['purchase_amount'] =$r['amount'];
				
				$r['user'] = $_SESSION['userid'];
				$pc = $r['pcs'];
				$group = "";
				
				if($pc == 1 || $pc == 1.00)
				{	$group = "single"; }
				else if($pc > 1)
				{	$group = "box"; }
				else if($pc == "" || $pc==0)
				{
					$group = "parcel"; 
				}
				
					
				$r['group_type'] = $group;
				$r['site_upload'] = 1;
				$r['rapnet_upload'] = 1;
	
				/* $attr = $r['attr'];				
				
				unset($r['attr']); */
				if(empty($SkuData))
				{	
					$r['visibility'] = 1;
				}
				else
				{
					$r['visibility'] = 0;
					$r['parent_id'] = $SkuData['id'];
					$child =  $SkuData['child_count'];			
					$child++; 
					$SkuData['child_count'] = $child;
					$r['sku'] = $r['sku'].'-'.$child;
				}
				
				$data = $this->helper->getInsertString($r);	
				$sql = "INSERT INTO ".$this->table_product ." (". $data[0].") VALUES (".$data[1].")";		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)			
				{
					$rs = mysqli_error();
					break;
				}
				else
				{
					$pid = mysqli_insert_id($this->conn);	
					
					$inproduct[] = $pid;
					
					$attr = array();
					$attr['product_id'] = $pid;				
					
					$data = $this->helper->getInsertString($attr);	
					$sql = "INSERT INTO ".$this->table_product_value ." (". $data[0].") VALUES (".$data[1].")";		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)			
					{
						$rs = mysqli_error();
						break;
					}
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = $post['inward_type'];
					$history['party'] = $post['party'];		
					$history['narretion'] = $post['narretion'];
					$history['date'] = $post['invoicedate'];
					$history['description'] = "New Stone ".$post['inward_type']." with reference no is ".$post['reference'];
					$history['pcs'] = $r['pcs'];
					$history['carat'] = $r['carat'];
					$history['amount'] = $r['amount'];
					$history['price'] = $r['price'];
					$history['sku'] = $r['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $post['invoiceno'];
					$history['entry_from'] = 'inward';
					$history['entryno'] = $post['id'];	
					$rs = $this->helper->addHistory($history);							
				}
				
				if(!empty($SkuData))
				{
					
					if($SkuData['group_type'] == 'box' || $SkuData['group_type'] == 'parcel')
					{
						
						$SkuData['pcs'] += (float)$r['pcs'];
						$SkuData['carat'] += (float)$r['carat'];
						//$SkuData['carat'];
						//$iTotal += $r['amount'];
						//$iProducts[] = $SkuData['id'];
						
						$sql = "UPDATE dai_product SET pcs=".$SkuData['pcs'].", carat=".$SkuData['carat'].",child_count=".$SkuData['child_count']."   WHERE id=".$SkuData['id'];		
						$rs = mysqli_query($this->conn,$sql);
						if(!$rs)
						{
							echo mysqli_error();					
						}
												
						$history =array();
						$history['product_id'] =$SkuData['id'];
						$history['action'] = $post['inward_type'];
						$history['party'] = $post['party'];		
						$history['narretion'] = $post['narretion'];
						$history['date'] = $post['invoicedate'];
						$history['description'] = "New Stone ".$post['inward_type']." with reference no is ".$post['reference'];
						$history['pcs'] = $r['pcs'];
						$history['carat'] = $r['carat'];
						$history['amount'] = $r['amount'];
						$history['price'] = $r['price'];
						$history['sku'] = $r['sku'];
						$history['type'] = 'cr';
						$history['invoice'] = $post['invoiceno'];
						$history['entry_from'] = 'inward';
						$history['entryno'] = $post['id'];							
						$rs = $this->helper->addHistory($history);
						if(!is_numeric($rs) && $rs!=1)
						{
							return $rs;	
						}	
					}	
				}	
				
			}
				
		}
		
		foreach($product as $k=>$iid)
		{
			if(!in_array($iid,$inproduct))
			{
				$sql = "UPDATE ".$this->table_product." SET visibility=0,inward_id=0 WHERE id=".$iid;
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
				}
			}			
		}
		
		$post['final_amount'] = $ta;
		$post['carat'] = $tc;		
		$post['pcs'] = $tp;
		$post['products'] = implode(",",$inproduct);
		$post['due_amount'] = $ta - (float)$post['paid_amount'];
		
		$data = $this->helper->getUpdateString($post);		
		$sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$post['id'];
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();
		}
		
		
		return $rs;
	}
	public function getProductDetail($id)
    {
		$data = array();
		
			$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." p WHERE p.company=".$_SESSION['companyId']." and p.id=".$id);
			while($row = mysqli_fetch_assoc($rs))
			{
				
				$rs1 = mysqli_query($this->conn,"SELECT * FROM  ".$this->table_product_value ." WHERE product_id=".$id);
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					 $row['record'] = $row1;
				}
				
				$data =  $row;
			}
		
		return  $data;			
    }
	public function returnMemo($post)
	{	
		//print_r($post);
		$products = $post['products'];
		try
		{
			$in_data = $this->getInwardData($post['id']);
			$inProducts = explode(",",$in_data['products']);
			$ReturnProducts = $updateProduct = array();
			
			if($in_data['return_products'] !="")
				$ReturnProducts = explode(",",$in_data['return_products']);			
			
			foreach($inProducts as $k=>$pid)
			{
				if (in_array($pid, $products))
				{
					$product = $this->getDetail($pid);
					
					if($product['outward'] !='' )
						continue;
					
					$ReturnProducts[] = $pid;
					$sql = "UPDATE ".$this->table_product." SET visibility=0,inward_id='' WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						return mysqli_error();						
					}
				}
				else
				{
					$updateProduct[]  = $pid;
				}
			}
			
			$sql = "UPDATE ".$this->table." SET return_products='".implode(",",$ReturnProducts)."',products='".implode(",",$updateProduct)."' WHERE id=".$post['id'];		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				return mysqli_error();
				
			}
			return $rs;
		}
		catch(Exception $e)
		{
				return $e->getMessage();	
		}
	}
	
	public function memoTo($post)
	{
		/* echo "<pre>";
		print_r($post);
		exit; */
		
		$PurchID = 0;
		$rs = mysqli_query($this->conn,"SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = 'dai_inward'");
		while($row = mysqli_fetch_assoc($rs))
		{
			$PurchID = $row['auto_increment'];
		}
		
		$memoId = $post['memo_id'];
		unset($post['memo_id']);
		$type = $post['inward_type'];
		
		if($post['term'] =='' || $post['term'] == 0)
			$post['duedate'] = $post['invoicedate'];
		
		$post['date'] = date("Y-m-d H:i:s");
		$incre_id = $this->getIncrementEntry('inward');
		
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$post['entryno'] = $incre_id;	
		
		$products = $post['products'];
		
		$record = array();
		if(isset($post['record']))
		{
			$record = $post['record'];
			unset($post['record']);
		}
		$balance = array();
	
		//unset($post['on_payment']);		
		//$post['products'] = implode(",",$products);
		
			
		$OutProducts = array();
		$amount = $iCarat = $iPcs = 0;
		//check price and amount are blank or greter than exiesting box / parcel for carat
		//if yes then return message to outward page
		foreach($products as $k=>$value)
		{
			$pid = $value['id'];
			$edata = $this->getProductDetail($pid);			
			if(isset($record[$pid]) && $record[$pid]['carat'] !="" && $record[$pid]['carat'] != $edata['carat'] )
			{
			
			//$edata = $this->getProductDetail($pid);
			
			if( $record[$pid]['price'] =="" || $record[$pid]['amount'] =="" )
					return "Please Enter price and amount of carat for SKU : ".$edata['sku'];
		
			if( $record[$pid]['pcs'] > $edata['pcs'] &&  $edata['group_type'] == 'box'  )
					return  "Pcs is exceed than stock PCS For SKU : ".$edata['sku'];
					
			if($record[$pid]['carat'] > $edata['carat'] )
					return  "Carat is exceed than stock carat For SKU : ".$edata['sku'];
				
			}			
		}
		
		// itterate all product which seleted for outward
		foreach($products as $k=>$value)
		{
			$pid = $value['id'];
			$edata = $this->getProductDetail($pid);
			
			//check carat and pcs of outward data with existing data if both are diferent than create new products
			if(isset($record[$pid]) && $record[$pid]['carat'] !="" && $record[$pid]['carat'] != $edata['carat'] )
			{
				if( $record[$pid]['price'] =="" || $record[$pid]['amount'] =="" )
					return "Please Enter price and amount of carat";
				
				//create seperation from box / parcel for outward	
				$rid = $this->separateMemoToPurchase($record[$pid],$edata,$type,$post,$PurchID);
				if(is_numeric($rid))
				{
					$OutProducts[] =  $rid;
					$amount += (float)$record[$pid]['amount']; 
					$iCarat += (float)$record[$pid]['carat']; 
					if($record[$pid]['pcs'] !='')
						$iPcs += (float)$record[$pid]['pcs']; 
				}
				else
				{				
					return $rid;
				}
			}
			else
			{
				
				// if both are equal than just update to outward type
				$pdata = $this->getProductDetail($pid);
				
				if(isset($record[$pid]))
					$price = $record[$pid]['price'];				
				else
					$price = $value['price'];				
				
				$amount = $pdata['carat'] * $price;	

				$iCarat += (float)$pdata['carat']; 
				if($pdata['pcs'] !='')
					$iPcs += (float)$pdata['pcs']; 
					
				$OutProducts[] =  $pid;
				$sql = "UPDATE ".$this->table_product." SET purchase_price='$price',purchase_amount='$amount',price='$price',amount='$amount',inward_id='$PurchID',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$history =array();
			
				
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = $type;
				$history['party'] = $post['party'];		
				$history['narretion'] = $post['narretion'];
				$history['date'] = $post['invoicedate'];
				$history['description'] = " Stone Memo To Purchase with reference no is ".$post['reference'];
				$history['pcs'] = 0;
				$history['carat'] = 0;
				$history['amount'] = $amount;
				$history['price'] = $price;
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $post['invoiceno'];
				
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				//$amount += (float)($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount']; 		
			}		
		}
		
		$post['due_amount'] = $amount;
		$post['final_amount'] = $amount;		
		$post['paid_amount'] =0;
		$post['pcs'] =$iPcs;
		$post['carat'] =$iCarat;
		
		if(empty($OutProducts))
			return "Please select item or enter required value for box/parcel ";
			
		$post['products'] = implode(",",$OutProducts);
		
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		
		$Oid =  mysqli_insert_id($this->conn);
		$temp = explode("-",$incre_id);
		$temp[1] = $temp[1] + 1;
		$setNewid = $temp[0]."-".$temp[1];
		
		$sql = "UPDATE dai_incrementid  SET inward='$setNewid' WHERE company=".$_SESSION['companyId'];		
		$rs = mysqli_query($this->conn,$sql);
		
		if(!$rs)
		{
			return mysqli_error();			
		}
		else
		{
			$rs = $Oid;				
		}
		
		$memoData = $this->getInwardData($memoId);
		$mp = array();
		foreach(explode(",",$memoData['products']) as $k=>$pid)
		{
			if(!in_array($pid,$OutProducts))
				{
					$mp[] = $pid;
					continue;
				}
		}
		if(empty($mp))
			$sql = "UPDATE ".$this->table." SET products='' WHERE  id=".$memoId;		
		else
			$sql = "UPDATE ".$this->table." SET products='".implode(",",$mp)."' WHERE  id=".$memoId;		
				
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}				
		return $rs; 
	}
	
	public function separateMemoToPurchase($v,$edata,$type,$post,$PurchID)
	{
		$prid = "";
		
		$maindata = $edata;
		
		$child = $maindata['child_count'] + 1;
		
		$edata['child_count'] = $child;
		
		$v['sku'] = $maindata['sku'].'-'.$child;
			
		$tc = $tp = 0;
		$skus[] = $v['sku'];			
		$tp += (float)$v['pcs'];
		$tc += (float)$v['carat'];
		
		
			
		if($edata['group_type']  =='box' )
			$edata['pcs']  -= $v['pcs'];
		
		$edata['carat']  -= $v['carat'];
		
		$v['purchase_carat'] = $v['carat'];
		$v['purchase_pcs'] = $v['pcs'];
		
		$v['date'] = date("Y-m-d H:i:s");
		$cid = $_SESSION['companyId'];	
		$v['company'] = $cid;
		$v['user'] = $_SESSION['userid'];
	
		$pc = $v['pcs'];
		$group = "";
		
		if($pc == 1 || $pc == 1.00)
		{	$group = "single"; }
		else if($pc > 1)
		{	$group = "box"; }
		else if($pc == "" || $pc==0)
		{
			$group = "parcel"; 
		}		
			
		$v['group_type'] = $group;
		$v['site_upload'] = 1;
		$v['rapnet_upload'] = 1;
		$v['is_uploadsite'] = 0;
		$v['is_uploadrapnet'] = 0;
		
		$v['transaction'] = 'cr';
		$v['inward_id'] = $PurchID;
		
		$v['visibility'] = 1;
		$v['parent_id'] = $maindata['id'];
		$v['attr'] = $edata['record'];
		
		$v['purchase_price'] = $v['price'];
		$v['purchase_amount'] = $v['amount'];
		
		$attr = (array)$v['attr'];		
		unset($v['attr']);
		$data = $this->helper->getInsertString($v);	
		$sql = "INSERT INTO dai_product (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)			
		{
			$rs = mysqli_error();
			return $rs;
		}
		else
		{
			$prid = mysqli_insert_id($this->conn);					
			$attr['product_id'] = $prid;
				unset($attr['pid']);
			$data = $this->helper->getInsertString($attr);	
			$sql = "INSERT INTO dai_product_value (". $data[0].") VALUES (".$data[1].")";		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)			
			{
				$rs = mysqli_error();
				return $rs;
			}
			
			$history =array();
			$history['product_id'] = $prid;
			$history['action'] = 'unboxing';			
			$history['party'] = $post['party'];		
			$history['narretion'] = $post['narretion'];
			$history['date'] = $post['invoicedate'];
			$history['description'] = "Memo to Purchase for Unboxing from ".$edata['sku']." with  carat :".$v['carat'];
			$history['pcs'] = $v['pcs'];
			$history['carat'] = $v['carat'];
			$history['amount'] = $v['amount'];
			$history['price'] = $v['price'];
			$history['sku'] = $v['sku'];
			$history['type'] = 'cr';
			$history['invoice'] = $post['invoiceno'];
			$history['entry_from'] = 'inward';
			
			$rs = $this->helper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}
			
			//$record = $edata['record'];
			unset($edata['record']);
				
				
			$data = $this->helper->getUpdateString($edata);	
			$sql = "UPDATE dai_product SET ".$data." WHERE id=".$edata['id'];		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();				
			}
			
			
			$history =array();
			$history['product_id'] =$maindata['id'];
			$history['action'] = $type;
			$history['party'] = $post['party'];		
			$history['narretion'] = $post['narretion'];
			$history['date'] = $post['invoicedate'];
			$history['description'] = $type." from Memo carat :".$edata['carat'];
			$history['pcs'] = $v['pcs'];
			$history['carat'] = $v['carat'];
			$history['amount'] = $v['amount'];
			$history['price'] = $v['price'];
			$history['sku'] = $edata['sku'];
			$history['type'] = 'dr';
			$history['invoice'] = $post['invoiceno'];
			$history['entry_from'] = 'inward';			
			$rs = $this->helper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}	
			return $prid;
		}			
	}
	
	public function deletePurchase($id)
    {
		$inData = $this->getInwardData($id);
		
		$deleteProducts = array();
			$remianProduct = array();
		$products = explode(",",$inData['products']);
		
		if($inData['products'] !="" )
		{
			foreach($products as $k=>$rid)
			{
				if($rid==0 || $rid=='')
					continue;
				
				$product = $this->getDetail($rid);
				
				if($product['outward'] !='')
				{
					$remianProduct[] = $rid;
					continue;
				}
				
				if($product['visibility'] ==0 && $product['parent_id'] !=0 )
				{
					$parent = $this->getDetail($product['parent_id']);
					$carat = $parent['carat'] - $product['carat'];
					
					$pcs = 0;
					
					if($parent['group_type'] == 'box')
						$pcs = $parent['pcs'] - $product['pcs'];
					
					$sql = "UPDATE dai_product SET carat=$carat,pcs=$pcs WHERE id=".$product['parent_id'];		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						return mysqli_error();				
					}
					
					$history =array();
					$history['product_id'] =$parent['id'];
					$history['action'] = 'purchase_delete';
					$history['party'] = $inData['party'];		
					$history['narretion'] = $inData['narretion'];
					$history['date'] = $inData['invoicedate'];
					$history['description'] = "Purchase Delete";
					$history['pcs'] = $product['pcs'];
					$history['carat'] = $product['carat'];
					$history['amount'] = $product['amount'];
					$history['price'] = $product['price'];
					$history['sku'] = $parent['sku'];
					$history['type'] = 'dr';
					$history['invoice'] = $inData['invoiceno'];
					$history['entry_from'] = 'inward';			
					$rs = $this->helper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
				}
				else
				{
					$sql = "UPDATE dai_product SET visibility=0 WHERE id=".$rid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						return mysqli_error();				
					}
				}			
				$deleteProducts[] = $rid;			
			}
		}
		else
		{
			$products = $this->getRecordData($id);
		
			foreach($products as $rid=>$prdata)
			{
				if($rid==0 || $rid=='')
					continue;
				
				$product = $this->getDetail($rid);
				
				if($product['outward'] !='')
				{
					$remianProduct[] = $rid;
					continue;
				}
				
				if($product['visibility'] ==0 && $product['parent_id'] !=0 )
				{
					$parent = $this->getDetail($product['parent_id']);
					$carat = $parent['carat'] - $product['carat'];
					
					$pcs = 0;
					
					if($parent['group_type'] == 'box')
						$pcs = $parent['pcs'] - $product['pcs'];
					
					$sql = "UPDATE dai_product SET carat=$carat,pcs=$pcs WHERE id=".$product['parent_id'];		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						return mysqli_error();				
					}
					
					$history =array();
					$history['product_id'] =$parent['id'];
					$history['action'] = 'purchase_delete';
					$history['party'] = $inData['party'];		
					$history['narretion'] = $inData['narretion'];
					$history['date'] = $inData['invoicedate'];
					$history['description'] = "Purchase Delete";
					$history['pcs'] = $product['pcs'];
					$history['carat'] = $product['carat'];
					$history['amount'] = $product['amount'];
					$history['price'] = $product['price'];
					$history['sku'] = $parent['sku'];
					$history['type'] = 'dr';
					$history['invoice'] = $inData['invoiceno'];
					$history['entry_from'] = 'inward';			
					$rs = $this->helper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
				}
				else
				{
					$sql = "UPDATE dai_product SET visibility=0 WHERE id=".$rid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						return mysqli_error();				
					}
				}			
				$deleteProducts[] = $rid;			
			}	
		}		
		$sql = "UPDATE ".$this->table." SET products='".implode(",",$remianProduct)."',return_products='".implode(",",$deleteProducts)."',deleted = 1 WHERE  id=".$id;		
		
        $rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();				
		}		
	    return $rs;
    }
	
	// ADD new Ledger
    public function looseSaveData($post)
    {
		/* echo "<pre>";
		print_r($post);		
		exit;  */
		$iTotal = $iCarat = $iPcs = 0;
		$cid = $_SESSION['companyId'];
		$post['user'] = $_SESSION['userid'];
		
		if($post['terms'] =='' || $post['terms'] == 0)
			$post['duedate'] = $post['invoicedate'];
		
		$post['date'] = $post['invoicedate'];
		$post['company'] = $cid;
		$incre_id = $this->getIncrementEntry('inward');
		$reference = $this->getIncrementEntry('reference');
		$post['entryno'] = $incre_id;
		$post['import_for'] = 'loose';
		$post['reference'] = $reference;
		$record = $post['record'];
		
		unset($post['record']);
		unset($post['btnsave']);
		unset($post['import']);
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
		}
		else
		{
			$lid = mysqli_insert_id($this->conn);
			//$_SESSION['last_inward'] = $lid; 
			
				
				
			$temp = explode("-",$incre_id);
			$temp[1] = $temp[1] + 1;
			$setNewid = $temp[0]."-".$temp[1];
			$reference++;
			$sql = "UPDATE jew_incrementid  SET inward='$setNewid',reference='$reference' WHERE company=".$_SESSION['companyId'];		
			$rs = mysqli_query($this->conn,$sql);
			//print_r($record);
			$i=1;
			
			$iProducts = array();
			$iTotal = 0.0;	
			if($post['inward_type'] == "import")
			{
				$sqlj = mysqli_query($this->conn,"SELECT * FROM jew_temp_loose WHERE user=".$_SESSION['sequence']);
			
				while($row = mysqli_fetch_assoc($sqlj))
				{
					$data = $row; 
					$value = $data['value'];
					$r = (array)json_decode($value);
					
					if($r['sku']=="" || $r['carat']=="" )
						continue;
					
					//$pDetail = (array)$r['record'];
					//unset($r['record']);
					
					//$SkuData = $this->helper->getDataBySku($r['sku']);
					
					$iTotal += (float)$r['amount'];
					$iCarat += (float)$r['carat'];
					
					if($r['pcs'] !='')
						$iPcs += (float)$r['pcs'];
					
				 
					$cid = $_SESSION['companyId'];	
					
					//$r['date'] = date("Y-m-d H:i:s");
					$r['inward_id'] = $lid;
					$r['company'] = $cid;
					$r['purchase_pcs'] =$r['pcs'];
					$r['purchase_carat'] =$r['carat'];
					$r['purchase_price'] =$r['price'];
					$r['purchase_amount'] =$r['amount'];
					
					$r['user'] = $_SESSION['userid'];
					$pc = $r['pcs'];
					$group = "";
					
					if($pc == 1 || $pc == 1.00)
					{	$group = "single"; }
					else if($pc > 1)
					{	$group = "box"; }
					else if($pc == "" || $pc==0)
					{
						$group = "parcel"; 
					}
					
						
					$r['group_type'] = $group;
					$r['visibility'] = 1;
					
					
					$data2 = $this->helper->getInsertString($r);	
					$sql1 = "INSERT INTO jew_loose_product (". $data2[0].") VALUES (".$data2[1].")";		
				
					$rs = mysqli_query($this->conn,$sql1);
					if(!$rs)			
					{
						return mysqli_error();
						
					}
					else
					{
						$pid = mysqli_insert_id($this->conn);	
						
						$iProducts[] = $pid; 
						
						$history =array();
						$history['product_id'] = $pid;
						$history['action'] = $post['inward_type'];
						$history['party'] = $post['party'];		
						$history['narretion'] = $post['narretion'];
						$history['date'] = $post['invoicedate'];
						$history['description'] = "Loose Stone ".$post['inward_type']." with reference no is ".$post['reference'];
						$history['pcs'] = $r['pcs'];
						$history['carat'] = $r['carat'];
						$history['amount'] = $r['amount'];
						$history['price'] = $r['price'];
						$history['sku'] = $r['sku'];
						$history['type'] = 'cr';
						$history['invoice'] = $post['invoiceno'];
						$history['entry_from'] = 'inward';
						$history['entryno'] = $lid;
						$history['for_history'] = 'side';			
						$rs = $this->jhelper->addHistory($history);	

					}					
					
				}
			
			}
			
			else
			{
				
				foreach($record as $r)
				{
					
				
					if($r['sku']=="" || $r['carat']=="" )
						continue;
					
					//$pDetail = (array)$r['record'];
					//unset($r['record']);
					
					//$SkuData = $this->helper->getDataBySku($r['sku']);
					
					$iTotal += (float)$r['amount'];
					$iCarat += (float)$r['carat'];
					
					if($r['pcs'] !='')
						$iPcs += (float)$r['pcs'];
					
				 
					$cid = $_SESSION['companyId'];	
					
					//$r['date'] = date("Y-m-d H:i:s");
					$r['inward_id'] = $lid;
					$r['company'] = $cid;
					$r['purchase_pcs'] =$r['pcs'];
					$r['purchase_carat'] =$r['carat'];
					$r['purchase_price'] =$r['price'];
					$r['purchase_amount'] =$r['amount'];
					
					$r['user'] = $_SESSION['userid'];
					$pc = $r['pcs'];
					$group = "";
					
					if($pc == 1 || $pc == 1.00)
					{	$group = "single"; }
					else if($pc > 1)
					{	$group = "box"; }
					else if($pc == "" || $pc==0)
					{
						$group = "parcel"; 
					}
					
						
					$r['group_type'] = $group;
					$r['visibility'] = 1;
					
					
					$data2 = $this->helper->getInsertString($r);	
					$sql1 = "INSERT INTO jew_loose_product (". $data2[0].") VALUES (".$data2[1].")";		
				
					$rs = mysqli_query($this->conn,$sql1);
					if(!$rs)			
					{
						return mysqli_error();
						
					}
					else
					{
						$pid = mysqli_insert_id($this->conn);	
						
						$iProducts[] = $pid; 
						
						$history =array();
						$history['product_id'] = $pid;
						$history['action'] = $post['inward_type'];
						$history['party'] = $post['party'];		
						$history['narretion'] = $post['narretion'];
						$history['date'] = $post['invoicedate'];
						$history['description'] = "Loose Stone ".$post['inward_type']." with reference no is ".$post['reference'];
						$history['pcs'] = $r['pcs'];
						$history['carat'] = $r['carat'];
						$history['amount'] = $r['amount'];
						$history['price'] = $r['price'];
						$history['sku'] = $r['sku'];
						$history['type'] = 'cr';
						$history['invoice'] = $post['invoiceno'];
						$history['entry_from'] = 'inward';
						$history['entryno'] = $lid;
						$history['for_history'] = 'side';			
						$rs = $this->jhelper->addHistory($history);	

					}					
					
				}
			}
			$sql = "UPDATE ".$this->table." SET products='".implode(",",$iProducts)."', due_amount=$iTotal,final_amount=$iTotal,carat=$iCarat,pcs=$iPcs  WHERE id=".$lid;		
			//$sql = "UPDATE ".$this->table." SET products='".implode(",",$iProducts)."', carat=$iCarat,pcs=$iPcs  WHERE id=".$lid;		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();					
			}
			
			$sql = "DELETE FROM jew_temp WHERE code=0 and user = ".$_SESSION['sequence'];
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)			
				$rs = mysqli_error();
			
			
				
		}	
		
		return $rs;
    }
}

<?php
include('../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
include_once ($daiDir.'Classes/PHPExcel/IOFactory.php');
class inwardModel
{
    public $table;
	public $table_product;
	public $table_product_value;
	public $helper;
	 function __construct()
    {
        try {
            $this->table  = "dai_inward";
			$this->table_product  = "dai_product";
			$this->table_product_value  = "dai_product_value";
			$this->helper  = new Helper;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getAllData()
    {
		$rs = mysql_query("SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." ORDER BY date desc");
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data[] =  $row;
		}		
		return  $data;			
    }
	// get single Ledger Data
	public function getData($id)
    {
		
		$rs = mysql_query("SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." and id=".$id );
			
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data = $row;
		}
	
		if(empty($data))
		{
			$field = mysql_num_fields( $rs );
   
			for ( $i = 0; $i < $field; $i++ ) {
		   
				$data[mysql_field_name( $rs, $i )] = "";
		   
			}
		}	
		else
		{
			$rs = mysql_query("SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE p.inward_id=".$data['id'] );
			
			while($row = mysql_fetch_assoc($rs))
			{
				$data['record'][] = $row;
			}
		}		
		return  $data;	
					
    }
	
	public function getInwardData($id)
    {
		
		$rs = mysql_query("SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." and id=".$id );
			
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data = $row;
		}	
		return  $data;	
					
    }
	public function getRecordData($id)
    {
		
		$rs = mysql_query("SELECT * FROM ".$this->table_product ." WHERE  inward_id = '".$id."'" );
		
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data[$row['id']] = $row;
		}
		return  $data;			
    }
	
	public function getIncrementEntry($of = "")
	{
		if($of=="")
			return "";
		$rs = mysql_query("SELECT ".$of." FROM dai_incrementid WHERE company=".$_SESSION['companyId'] );
			
		$data = "";
		while($row = mysql_fetch_assoc($rs))
		{
			$data = $row[$of];
			break;
		}
		return $data;
	}
	// ADD new Ledger
    public function saveData($post)
    {
		//echo "<pre>";
		//print_r($post);		
		//exit;
		$iTotal = $iCarat = $iPcs = 0;
		$cid = $_SESSION['companyId'];
		$post['user'] = $_SESSION['userid'];
		
		if($post['term'] =='' || $post['term'] == 0)
			$post['duedate'] = $post['invoicedate'];
		
		$post['date'] = $post['invoicedate'];
		$post['company'] = $cid;
		$incre_id = $this->getIncrementEntry('inward');
		$reference = $this->getIncrementEntry('reference');
		$post['entryno'] = $incre_id;
		$post['reference'] = $reference;
		$record = $post['record'];
		
		unset($post['record']);
		unset($post['btnsave']);
		unset($post['import']);
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
		}
		else
		{
			$lid = mysql_insert_id();
			$_SESSION['last_inward'] = $lid; 
			
			/* $sql = "DELETE FROM dai_temp WHERE code=0 and user = ".$_SESSION['sequence'];
			$rs = mysql_query($sql);
			if(!$rs)			
				$rs = mysql_error();
				*/
				
			
			$temp = explode("-",$incre_id);
			$temp[1] = $temp[1] + 1;
			$setNewid = $temp[0]."-".$temp[1];
			$reference++;
			$sql = "UPDATE dai_incrementid  SET inward='$setNewid',reference='$reference' WHERE company=".$_SESSION['companyId'];		
			$rs = mysql_query($sql);
			//print_r($record);
			$i=1;
			if( $post['inward_type'] == 'import' )
			{
				/* foreach($record as $r)
				{
					$seq = $_SESSION['sequence'];
					$post1['user'] = $seq;
					$post1['no'] = $i;
					$post1['code'] = 1;
					$post1['value'] = json_encode($r);
					
					$data = $this->helper->getInsertString($post1);	
					$sql = "INSERT INTO dai_temp (". $data[0].") VALUES (".$data[1].")";
						
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();
						return $rs;
					}
					$i++;
				}	*/
			}
			else
			{
				$iProducts = array();
				$iTotal = 0.0;	
				foreach($record as $r)
				{
					if($r['sku']=="" || $r['polish_carat']=="" || $r['price']=="" || $r['amount']=="")
						continue;
					
					$SkuData = $this->helper->getDataBySku($r['sku']);
					
					$iTotal += (float)$r['amount'];
					$iCarat += (float)$r['polish_carat'];
					
					if($r['polish_pcs'] !='')
						$iPcs += (float)$r['polish_pcs'];
					
				 
					$cid = $_SESSION['companyId'];	
					
					$r['date'] = date("Y-m-d H:i:s");
					$r['inward_id'] = $lid;
					$r['company'] = $cid;
					$r['purchase_pcs'] =$r['polish_pcs'];
					$r['purchase_carat'] =$r['polish_carat'];
					$r['purchase_price'] =$r['price'];
					$r['purchase_amount'] =$r['amount'];
					
					$r['user'] = $_SESSION['userid'];
					$pc = $r['polish_pcs'];
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
		
					$attr = $r['attr'];				
					
					unset($r['attr']);
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
					$rs = mysql_query($sql);
					if(!$rs)			
					{
						$rs = mysql_error();
						break;
					}
					else
					{
						$pid = mysql_insert_id();	
						
						$iProducts[] = $pid; 
						$attr['product_id'] = $pid;				
						
						$data = $this->helper->getInsertString($attr);	
						$sql = "INSERT INTO ".$this->table_product_value ." (". $data[0].") VALUES (".$data[1].")";		
						$rs = mysql_query($sql);
						if(!$rs)			
						{
							$rs = mysql_error();
							break;
						}
						$history =array();
						$history['product_id'] = $pid;
						$history['action'] = $post['inward_type'];
						$history['party'] = $post['party'];		
						$history['narretion'] = $post['narretion'];
						$history['date'] = $post['invoicedate'];
						$history['description'] = "New Stone ".$post['inward_type']." with reference no is ".$post['reference'];
						$history['pcs'] = $r['polish_pcs'];
						$history['carat'] = $r['polish_carat'];
						$history['amount'] = $r['amount'];
						$history['price'] = $r['price'];
						$history['sku'] = $r['sku'];
						$history['type'] = 'cr';
						$history['invoice'] = $post['invoiceno'];
						$history['entry_from'] = 'inward';
						$history['entryno'] = $lid;	
						$rs = $this->helper->addHistory($history);							
					}
					
					if(!empty($SkuData))
					{
						
						if($SkuData['group_type'] == 'box' || $SkuData['group_type'] == 'parcel')
						{
							
							$SkuData['polish_pcs'] += (float)$r['polish_pcs'];
							$SkuData['polish_carat'] += (float)$r['polish_carat'];
							//$SkuData['polish_carat'];
							//$iTotal += $r['amount'];
							//$iProducts[] = $SkuData['id'];
							
							$sql = "UPDATE dai_product SET polish_pcs=".$SkuData['polish_pcs'].", polish_carat=".$SkuData['polish_carat'].",child_count=".$SkuData['child_count']."   WHERE id=".$SkuData['id'];		
							$rs = mysql_query($sql);
							if(!$rs)
							{
								echo mysql_error();					
							}
													
							$history =array();
							$history['product_id'] =$SkuData['id'];
							$history['action'] = $post['inward_type'];
							$history['party'] = $post['party'];		
							$history['narretion'] = $post['narretion'];
							$history['date'] = $post['invoicedate'];
							$history['description'] = "New Stone ".$post['inward_type']." with reference no is ".$post['reference'];
							$history['pcs'] = $r['polish_pcs'];
							$history['carat'] = $r['polish_carat'];
							$history['amount'] = $r['amount'];
							$history['price'] = $r['price'];
							$history['sku'] = $r['sku'];
							$history['type'] = 'cr';
							$history['invoice'] = $post['invoiceno'];
							$history['entry_from'] = 'inward';
							$history['entryno'] = $lid;							
							$rs = $this->helper->addHistory($history);
							if(!is_numeric($rs) && $rs!=1)
							{
								return $rs;	
							}	
						}	
					}
					
				}
				$sql = "UPDATE ".$this->table." SET products='".implode(",",$iProducts)."', due_amount=$iTotal,final_amount=$iTotal,carat=$iCarat,pcs=$iPcs  WHERE id=".$lid;		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();					
				}
				
				/*
					$sql = "DELETE FROM dai_temp WHERE user = ".$_SESSION['sequence'];
					$rs = mysql_query($sql);
					if(!$rs)			
						$rs = mysql_error();
									*/
			}	
		}	
		return $rs;
    }
	
	
	public function updateData($post)
    {
		$record = $post['record'];
		unset($post['record']);
		
		$data = $this->helper->getUpdateString($post);
	
	
	    $sql = "UPDATE ".$this->table." SET ".$data.",site_upload=1,rapnet_upload=1 WHERE id=".$post['id'];		
	    $rs = mysql_query($sql);
	    if(!$rs)
		{
			$rs = mysql_error();
			
		}
		else
		{
			foreach($record as $r)
			{
				$rdata = $this->helper->getUpdateString($r);	
				$sql = "UPDATE ".$this->table_detail." SET ".$rdata." WHERE id=".$r['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
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
			$rs = mysql_query($sql);
		}
        $sql = "DELETE FROM ".$this->table ." WHERE id = ".$id;
        $rs = mysql_query($sql);
	    return $rs;
    }
	
	public function importData($inputFileName)
    {
			
		$attribute = $this->helper->getAttribute(1);
		$attribute['package'] = "Package";
		try {
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		$sql = "TRUNCATE table dai_temp";
		$rs = mysql_query($sql);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet

		$seq = $_SESSION['sequence'];
		for($i=2;$i<=$arrayCount;$i++)
		{
			$c = 'A'; 
			$temp = array();
			foreach($attribute as $k=>$value)
			{
				
				if($c =='C' && $allDataInSheet[$i][$c] =='')
				{
					$t = $allDataInSheet[$i]['A'];
					$t .= ($allDataInSheet[$i]['B'] !='') ? '-'.$allDataInSheet[$i]['B']:''; 
					$temp[$k] = $t;
				}
				else
				{
					$temp[$k] = $allDataInSheet[$i][$c];
				}
				$c++;
			}
			//print_r($temp);
			$post['user'] = $seq;
			$post['no'] = $i;
			$post['code'] = 0;
			$post['value'] = json_encode($temp);
			
			
			$data = $this->helper->getInsertString($post);	
			$sql = "INSERT INTO dai_temp (". $data[0].") VALUES (".$data[1].")";
				
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
				return $rs;
			}
		}
			
	}
	public function getDetail($id)
    {
		$data = array();		
		$rs = mysql_query("SELECT * FROM dai_product WHERE id=".$id);
		while($row = mysql_fetch_assoc($rs))
		{
			$data =  $row;
		}				
		return  $data;			
    }
	public function updatePrice($post)
	{
		$rs = 0;
		
		foreach($post['product'] as $pid=>$price)
		{
			if($price['price']=='' && $price['cost']=='' && $price['rap_price']=='')
				continue;
				
			$data = $this->getDetail($pid);
			$amount  = $data['polish_carat'] * $price['price'];
			$price1 = $price['price'];
			$cost = $price['cost'];
			$oldPrice = $data['price'];
			$scost = $sprice = $samount = $srap = $srapamount= '';
			if($cost != "")
			{
				$scost = ' cost ='.$cost.', ';
			}
			if($price1 != "")
			{
				$sprice = ' price ='.$price1.', ';
				
				$samount = ' amount ='.$amount.', ';
			}
			if($price['rap_price'] != "")
			{
				$srap = ' rap_price ='.$price['rap_price'].', ';
				$tmp = $data['polish_carat'] * $price['rap_price'];
				$srapamount = ' rap_amount ='.$tmp.', ';
			}
			
			$sql = "UPDATE ".$this->table_product." SET ". $scost.$sprice.$samount.$srap.$srapamount." site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
				return $rs;
			}
			//echo $rs;
			if($price1 != "")
			{
			$history = array();
			$history['product_id'] = $pid;
			$history['action'] = 'price_change';
			$history['date'] = date("Y-m-d H:i:s");
			$history['narretion'] = "cost price or base price are changed.";
			$history['description'] = "Old Price :  ".$oldPrice ." , New Price :  ".$price1;
			$rs = $this->helper->addHistory($history);
			}
		}
		return $rs;
	}
	
	public function savePackage($post)
	{
		foreach($post['product'] as $pid=>$pac)
		{
			$sql = "UPDATE ".$this->table_product_value." SET package='$pac' WHERE product_id=".$pid;		
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
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
		
		$amount  = $data['polish_carat'] * $price;
		$oldPrice = ($data['sell_price']=='0')?$data['price']:$data['sell_price'];
		
		$sql = "UPDATE ".$this->table_product." SET sell_price=$price,sell_amount=$amount,site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
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
	
	
	public function updateSinglePriceForIn($post)
	{
		$pid = $post['id'];
		$price = $post['price'];
		
		$data = $this->getDetail($pid);
		if($data['price']  == $price)
			return 1;
		
		$amount  = $data['polish_carat'] * $price;
		$oldPrice = $data['price'];
		
		$sql = "UPDATE ".$this->table_product." SET price=$price,amount=$amount,site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
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
				
				if($pdata['purchase_carat'] != $rec['polish_carat'])
				{
					$diff_carat = (float)$rec['polish_carat'] - (float)$pdata['purchase_carat'];				
					$final_carat = (float)$pdata['purchase_carat'] + $diff_carat;
					$rec['purchase_carat'] = $final_carat;
					$rec['polish_carat'] = (float)$pdata['polish_carat'] + $diff_carat;									
				}
				else
				{
					unset($rec['polish_carat']);
				}
				
				if($pdata['purchase_pcs'] != $rec['polish_pcs'])
				{
					$diff_pcs = (float)$rec['polish_pcs'] - (float)$pdata['purchase_pcs'];
					$final_pcs = (float)$pdata['purchase_pcs'] + (float)$diff_pcs;
					$rec['purchase_pcs'] = $final_pcs;
					$rec['polish_pcs'] = (float)$pdata['polish_pcs'] + $diff_pcs;				
				}
				else
				{
					unset($rec['polish_pcs']);
				}
				$tc += (float)$final_carat;
				$tp += (float)$final_pcs;
				$ta += (float)$rec['purchase_amount'];

				/* $data = $this->helper->getUpdateString($rec);		
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;
				$rs = mysql_query($sql);
				if(!$rs)
				{
					return mysql_error();
				} */
				
				$inproduct[] = $rec['id'];
			}
			else
			{
				$r = $rec;
				
				if($r['sku']=="" || $r['polish_carat']=="" || $r['price']=="" || $r['amount']=="")
					continue;
					
				$SkuData = $this->helper->getDataBySku($r['sku']);
					
				$ta += (float)$r['amount'];
				$tc += (float)$r['polish_carat'];
				
				if($r['polish_pcs'] !='')
					$tp += (float)$r['polish_pcs'];
				
			 
				$cid = $_SESSION['companyId'];	
				
				$r['date'] = date("Y-m-d H:i:s");
				$r['inward_id'] = $post['id'];
				$r['company'] = $cid;
				$r['purchase_pcs'] =$r['polish_pcs'];
				$r['purchase_carat'] =$r['polish_carat'];
				$r['purchase_price'] =$r['price'];
				$r['purchase_amount'] =$r['amount'];
				
				$r['user'] = $_SESSION['userid'];
				$pc = $r['polish_pcs'];
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
				$rs = mysql_query($sql);
				if(!$rs)			
				{
					$rs = mysql_error();
					break;
				}
				else
				{
					$pid = mysql_insert_id();	
					
					$inproduct[] = $pid;
					
					$attr = array();
					$attr['product_id'] = $pid;				
					
					$data = $this->helper->getInsertString($attr);	
					$sql = "INSERT INTO ".$this->table_product_value ." (". $data[0].") VALUES (".$data[1].")";		
					$rs = mysql_query($sql);
					if(!$rs)			
					{
						$rs = mysql_error();
						break;
					}
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = $post['inward_type'];
					$history['party'] = $post['party'];		
					$history['narretion'] = $post['narretion'];
					$history['date'] = $post['invoicedate'];
					$history['description'] = "New Stone ".$post['inward_type']." with reference no is ".$post['reference'];
					$history['pcs'] = $r['polish_pcs'];
					$history['carat'] = $r['polish_carat'];
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
						
						$SkuData['polish_pcs'] += (float)$r['polish_pcs'];
						$SkuData['polish_carat'] += (float)$r['polish_carat'];
						//$SkuData['polish_carat'];
						//$iTotal += $r['amount'];
						//$iProducts[] = $SkuData['id'];
						
						$sql = "UPDATE dai_product SET polish_pcs=".$SkuData['polish_pcs'].", polish_carat=".$SkuData['polish_carat'].",child_count=".$SkuData['child_count']."   WHERE id=".$SkuData['id'];		
						$rs = mysql_query($sql);
						if(!$rs)
						{
							echo mysql_error();					
						}
												
						$history =array();
						$history['product_id'] =$SkuData['id'];
						$history['action'] = $post['inward_type'];
						$history['party'] = $post['party'];		
						$history['narretion'] = $post['narretion'];
						$history['date'] = $post['invoicedate'];
						$history['description'] = "New Stone ".$post['inward_type']." with reference no is ".$post['reference'];
						$history['pcs'] = $r['polish_pcs'];
						$history['carat'] = $r['polish_carat'];
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
				$rs = mysql_query($sql);
				if(!$rs)
				{
					return mysql_error();
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
		$rs = mysql_query($sql);
		if(!$rs)
		{
			return mysql_error();
		}
		
		
		return $rs;
	}
	public function getProductDetail($id)
    {
		$data = array();
		
			$rs = mysql_query("SELECT * FROM ".$this->table_product ." p WHERE p.company=".$_SESSION['companyId']." and p.id=".$id);
			while($row = mysql_fetch_assoc($rs))
			{
				
				$rs1 = mysql_query("SELECT * FROM  ".$this->table_product_value ." WHERE product_id=".$id);
				while($row1 = mysql_fetch_assoc($rs1))
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
					$rs = mysql_query($sql);
					if(!$rs)
					{
						return mysql_error();						
					}
				}
				else
				{
					$updateProduct[]  = $pid;
				}
			}
			
			$sql = "UPDATE ".$this->table." SET return_products='".implode(",",$ReturnProducts)."',products='".implode(",",$updateProduct)."' WHERE id=".$post['id'];		
			$rs = mysql_query($sql);
			if(!$rs)
			{
				return mysql_error();
				
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
		$rs = mysql_query("SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = 'dai_inward'");
		while($row = mysql_fetch_assoc($rs))
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
			if(isset($record[$pid]) && $record[$pid]['polish_carat'] !="" && $record[$pid]['polish_carat'] != $edata['polish_carat'] )
			{
			
			//$edata = $this->getProductDetail($pid);
			
			if( $record[$pid]['price'] =="" || $record[$pid]['amount'] =="" )
					return "Please Enter price and amount of carat for SKU : ".$edata['sku'];
		
			if( $record[$pid]['polish_pcs'] > $edata['polish_pcs'] &&  $edata['group_type'] == 'box'  )
					return  "Pcs is exceed than stock PCS For SKU : ".$edata['sku'];
					
			if($record[$pid]['polish_carat'] > $edata['polish_carat'] )
					return  "Carat is exceed than stock carat For SKU : ".$edata['sku'];
				
			}			
		}
		
		// itterate all product which seleted for outward
		foreach($products as $k=>$value)
		{
			$pid = $value['id'];
			$edata = $this->getProductDetail($pid);
			
			//check carat and pcs of outward data with existing data if both are diferent than create new products
			if(isset($record[$pid]) && $record[$pid]['polish_carat'] !="" && $record[$pid]['polish_carat'] != $edata['polish_carat'] )
			{
				if( $record[$pid]['price'] =="" || $record[$pid]['amount'] =="" )
					return "Please Enter price and amount of carat";
				
				//create seperation from box / parcel for outward	
				$rid = $this->separateMemoToPurchase($record[$pid],$edata,$type,$post,$PurchID);
				if(is_numeric($rid))
				{
					$OutProducts[] =  $rid;
					$amount += (float)$record[$pid]['amount']; 
					$iCarat += (float)$record[$pid]['polish_carat']; 
					if($record[$pid]['polish_pcs'] !='')
						$iPcs += (float)$record[$pid]['polish_pcs']; 
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
				
				$amount = $pdata['polish_carat'] * $price;	

				$iCarat += (float)$pdata['polish_carat']; 
				if($pdata['polish_pcs'] !='')
					$iPcs += (float)$pdata['polish_pcs']; 
					
				$OutProducts[] =  $pid;
				$sql = "UPDATE ".$this->table_product." SET purchase_price='$price',purchase_amount='$amount',price='$price',amount='$amount',inward_id='$PurchID',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
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
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
			return $rs;
		}
		
		$Oid =  mysql_insert_id();
		$temp = explode("-",$incre_id);
		$temp[1] = $temp[1] + 1;
		$setNewid = $temp[0]."-".$temp[1];
		
		$sql = "UPDATE dai_incrementid  SET inward='$setNewid' WHERE company=".$_SESSION['companyId'];		
		$rs = mysql_query($sql);
		
		if(!$rs)
		{
			return mysql_error();			
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
				
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
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
		$tp += (float)$v['polish_pcs'];
		$tc += (float)$v['polish_carat'];
		
		
			
		if($edata['group_type']  =='box' )
			$edata['polish_pcs']  -= $v['polish_pcs'];
		
		$edata['polish_carat']  -= $v['polish_carat'];
		
		$v['purchase_carat'] = $v['polish_carat'];
		$v['purchase_pcs'] = $v['polish_pcs'];
		
		$v['date'] = date("Y-m-d H:i:s");
		$cid = $_SESSION['companyId'];	
		$v['company'] = $cid;
		$v['user'] = $_SESSION['userid'];
	
		$pc = $v['polish_pcs'];
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
		$rs = mysql_query($sql);
		if(!$rs)			
		{
			$rs = mysql_error();
			return $rs;
		}
		else
		{
			$prid = mysql_insert_id();					
			$attr['product_id'] = $prid;
				unset($attr['pid']);
			$data = $this->helper->getInsertString($attr);	
			$sql = "INSERT INTO dai_product_value (". $data[0].") VALUES (".$data[1].")";		
			$rs = mysql_query($sql);
			if(!$rs)			
			{
				$rs = mysql_error();
				return $rs;
			}
			
			$history =array();
			$history['product_id'] = $prid;
			$history['action'] = 'unboxing';			
			$history['party'] = $post['party'];		
			$history['narretion'] = $post['narretion'];
			$history['date'] = $post['invoicedate'];
			$history['description'] = "Memo to Purchase for Unboxing from ".$edata['sku']." with  carat :".$v['polish_carat'];
			$history['pcs'] = $v['polish_pcs'];
			$history['carat'] = $v['polish_carat'];
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
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();				
			}
			
			
			$history =array();
			$history['product_id'] =$maindata['id'];
			$history['action'] = $type;
			$history['party'] = $post['party'];		
			$history['narretion'] = $post['narretion'];
			$history['date'] = $post['invoicedate'];
			$history['description'] = $type." from Memo carat :".$edata['polish_carat'];
			$history['pcs'] = $v['polish_pcs'];
			$history['carat'] = $v['polish_carat'];
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
					$carat = $parent['polish_carat'] - $product['polish_carat'];
					
					$pcs = 0;
					
					if($parent['group_type'] == 'box')
						$pcs = $parent['polish_pcs'] - $product['polish_pcs'];
					
					$sql = "UPDATE dai_product SET polish_carat=$carat,polish_pcs=$pcs WHERE id=".$product['parent_id'];		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						return mysql_error();				
					}
					
					$history =array();
					$history['product_id'] =$parent['id'];
					$history['action'] = 'purchase_delete';
					$history['party'] = $inData['party'];		
					$history['narretion'] = $inData['narretion'];
					$history['date'] = $inData['invoicedate'];
					$history['description'] = "Purchase Delete";
					$history['pcs'] = $product['polish_pcs'];
					$history['carat'] = $product['polish_carat'];
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
					$rs = mysql_query($sql);
					if(!$rs)
					{
						return mysql_error();				
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
					$carat = $parent['polish_carat'] - $product['polish_carat'];
					
					$pcs = 0;
					
					if($parent['group_type'] == 'box')
						$pcs = $parent['polish_pcs'] - $product['polish_pcs'];
					
					$sql = "UPDATE dai_product SET polish_carat=$carat,polish_pcs=$pcs WHERE id=".$product['parent_id'];		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						return mysql_error();				
					}
					
					$history =array();
					$history['product_id'] =$parent['id'];
					$history['action'] = 'purchase_delete';
					$history['party'] = $inData['party'];		
					$history['narretion'] = $inData['narretion'];
					$history['date'] = $inData['invoicedate'];
					$history['description'] = "Purchase Delete";
					$history['pcs'] = $product['polish_pcs'];
					$history['carat'] = $product['polish_carat'];
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
					$rs = mysql_query($sql);
					if(!$rs)
					{
						return mysql_error();				
					}
				}			
				$deleteProducts[] = $rid;			
			}	
		}		
		$sql = "UPDATE ".$this->table." SET products='".implode(",",$remianProduct)."',return_products='".implode(",",$deleteProducts)."',deleted = 1 WHERE  id=".$id;		
		
        $rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();				
		}		
	    return $rs;
    }
}

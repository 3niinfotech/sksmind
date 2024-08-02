<?php 

include_once("../database.php");
include_once("../variable.php");
include_once("Helper.php");

class uploadHelper
{
	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn=$db;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	
	public function getData($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_inward WHERE company=".$_SESSION['companyId']." and id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}		
		return  $data;			
    }
	public function importData($data2)
	{
		$data1 = array_map('trim', $data2);
		$pid = 0;
		unset($data2);
		$helper = new Helper();
		$attribute = $helper->getAttribute();
		$attribute['package'] = "package";
		$value = $data1['value'];
		$r = (array)json_decode($value);
		
		$lid = $_SESSION['last_inward'];
	
		$inwardData = $this->getData($lid);
		
		if($lid ==0 || $lid =='')
		{
			return 'Inward Id dose not Found.';
		}
		$attr = array();
		$pcs = $carat = 0;
		
		foreach($attribute as $key=>$v)
		{
				$attr[$key] = $r[$key];
				unset($r[$key]);
		}
		if(empty($r) && $r['sku']=="" && $r['amount']=="" && $r['polish_carat']=="" )
				return 'sku empty';
		
		$SkuData = $helper->getDataBySku($r['sku']);
			
		$carat = $r['polish_carat'];
		$pcs = $r['polish_pcs'];
		
		$cid = $_SESSION['companyId'];	
			
		$r['date'] = date("Y-m-d H:i:s");
		$r['inward_id'] = $lid;
		$r['company'] = $cid;
		$r['user'] = $_SESSION['userid'];
		$r['sku'] = ($r['sku'] =='')?$r['mfg_Code']:$r['sku'];
		$pc = $r['polish_pcs'];
		$r['purchase_pcs'] = $r['polish_pcs'];
		$r['purchase_carat'] = $r['polish_carat'];
		$r['purchase_price'] =$r['price'];
		$r['purchase_amount'] =$r['amount'];
		$group = "";
		
		if($pc == 1 || $pc == 1.00)
		{	$group = "single"; }
		else if($pc > 1)
		{	$group = "box"; }
		else if($pc == "" || $pc==0)
		{
			$group = "parcel"; 
		}
		
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
		
		$r['group_type'] = $group;
		$r['site_upload'] = 1;
		$r['rapnet_upload'] = 1;
		
		if(isset($r['main_color']) && $r['main_color'] == '')
			$r['main_color'] = $attr['color'];
			
	//	$attr = (array)$r['attr'];		
		//unset($r['attr']);
		$data = $helper->getInsertString($r);	
		$sql = "INSERT INTO dai_product (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)			
		{
			$rs = mysqli_error();
			return $rs;
		}
		else
		{
			if(isset($attr['clarity']))
				$attr['clarity'] = str_replace('+','',$attr['clarity']);
			
			$pid = mysqli_insert_id();					
			$attr['product_id'] = $pid;							
			$data = $helper->getInsertString($attr);	
			$sql = "INSERT INTO dai_product_value (". $data[0].") VALUES (".$data[1].")";		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)			
			{
				$rs = mysqli_error();
				return $rs;
			}
			$history =array();
			$history['product_id'] = $pid;
			$history['action'] = 'import';										
			$history['date'] = $inwardData['invoicedate'];
			$history['description'] = "New Stone Import using CSV from ".$inwardData['place'];
			$history['pcs'] = $r['polish_pcs'];
			$history['carat'] = $r['polish_carat'];
			$history['amount'] = $r['amount'];
			$history['price'] = $r['price'];
			$history['sku'] = $r['sku'];
			$history['type'] = 'cr';
			$history['party'] = $inwardData['party'];
			$history['invoice'] = $inwardData['invoiceno'];
			$history['entry_from'] = 'inward';
			$history['entryno'] = $lid;		
			$rs = $helper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}

		}	
		
		if(!empty($SkuData))
		{
			//print_r($SkuData);
			
			if($SkuData['group_type'] == 'single')
			{
				$sql = "DELETE FROM dai_temp WHERE no=".$data1['no'];
				$rs = mysqli_query($this->conn,$sql);
				return $rs;
			}	
			else
			{
				$SkuData['polish_pcs'] += (float)$r['polish_pcs'];
				$SkuData['polish_carat'] += (float)$r['polish_carat'];
				//$SkuData['polish_carat'];
				
				$sql = "UPDATE dai_product SET polish_pcs=".$SkuData['polish_pcs'].", polish_carat=".$SkuData['polish_carat'].",child_count=".$SkuData['child_count']."   WHERE id=".$SkuData['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();					
				}
				$history['product_id'] = $SkuData['id'];
				$history['action'] = 'import';										
				$history['date'] = $inwardData['invoicedate'];
				$history['description'] = "New Stone Import using CSV from ".$inwardData['place'];
				$history['pcs'] = $r['polish_pcs'];
				$history['carat'] = $r['polish_carat'];
				$history['amount'] = $r['amount'];
				$history['price'] = $r['price'];
				$history['sku'] = $SkuData['sku'];
				$history['type'] = 'cr';	
				$history['party'] = $inwardData['party'];
				$history['invoice'] = $inwardData['invoiceno'];	
				$history['entry_from'] = 'inward';
				$history['entryno'] = $lid;	
				$rs = $helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
								
			}
		}
		
		$sql = "DELETE FROM dai_temp WHERE no=".$data1['no'];
		$rs = mysqli_query($this->conn,$sql);	
		
		$pcs += $inwardData['pcs'];
		$carat += $inwardData['carat'];	
		$psid = $inwardData['products'].','.$pid;
		$amount = $r['amount'] + $inwardData['final_amount'];	
		$sql = "UPDATE dai_inward SET pcs=".$pcs.", carat=".$carat.",products='".$psid."',final_amount=".$amount.",due_amount=".$amount." WHERE id=".$inwardData['id'];		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();					
		}
		
	}
}
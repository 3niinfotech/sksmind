<?php
include('../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
include_once ($daiDir.'Classes/PHPExcel/IOFactory.php');
class jewelryModel
{
    public $table;
	public $table_product;
	public $table_product_value;
	public $helper;
	 function __construct()
    {
        try {
            $this->table  = "dai_jewelry";
			$this->table_product  = "dai_jewelry_products";
			
			$this->helper  = new Helper;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getAllData()
    {
		$rs = mysql_query("SELECT * FROM ".$this->table ." ORDER BY sku");
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			
			$rs1 = mysql_query("SELECT * FROM ".$this->table_product ." WHERE  jewelry_id = ".$row['id'] );
		
			$temp = array();
			while($row1 = mysql_fetch_assoc($rs1))
			{
				$temp[$row1['jid']] = $row1;
			}
			$row['record'] = $temp;		
			$data[$row['id']] =  $row;			
		}		
		return  $data;			
    }
	// get single Data
	public function getData($id)
    {
		
		$rs = mysql_query("SELECT * FROM ".$this->table ." WHERE id=".$id );
			
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
			$data['record'] = array();
		}	
		else
		{
			$rs1 = mysql_query("SELECT * FROM ".$this->table_product ." WHERE  id = ".$data['id'] );
			
			while($row = mysql_fetch_assoc($rs))
			{
				$data['record'][] = $row;
			}
		}		
		return  $data;	
					
    }
	
	public function getOutwardData($id)
    {
		$rs = mysql_query("SELECT * FROM dai_outward WHERE company=".$_SESSION['companyId']." and id=".$id );
			
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
			$data['record'] = array();
		}
		else
		{
			$rs = mysql_query("SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE p.id IN (".$data['products'].")" );
			
			while($row = mysql_fetch_assoc($rs))
			{
				$data['record'][] = $row;
			}
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
		//$cid = $_SESSION['companyId'];
		//$post['user'] = $_SESSION['userid'];
		
		//$post['company'] = $cid;
		
		$record = $post['record'];
		
		unset($post['record']);
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysql_query($sql);
		if(!$rs)
		{
			return mysql_error();
		}
		else
		{
			$lid = mysql_insert_id();
			foreach($record as $r)
			{
				if($r['sku']=="" || $r['carat']=="" || $r['price']=="" || $r['total_amount']=="")
					continue;
				
				$r['jewelry_id'] = $lid;				
				$data = $this->helper->getInsertString($r);	
				$sql = "INSERT INTO ".$this->table_product ." (". $data[0].") VALUES (".$data[1].")";		
				$rs = mysql_query($sql);
				if(!$rs)			
				{
					return mysql_error();					
				}
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

   
    public function delete($id)
    {
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
		foreach($post['product'] as $pid=>$price)
		{
			$data = $this->getDetail($pid);
			$amount  = $data['polish_carat'] * $price['price'];
			$price1 = $price['price'];
			$cost = $price['cost'];
			$oldPrice = $data['price'];
			if($cost == "")
				$cost = 0;
			
			$sql = "UPDATE ".$this->table_product." SET cost=$cost,price=$price1,amount=$amount,site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
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
			$history['narretion'] = "cost price or base price are changed.";
			$history['description'] = "Old Price :  ".$oldPrice ." , New Price :  ".$price1;
			$rs = $this->helper->addHistory($history);
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
		$history['description'] = "Old Price :  ".$oldPrice ." , New Price :  ".$price;
		$rs = $this->helper->addHistory($history);
		
		return $rs;
	}
	
	public function updateInward($post)
	{
		unset($post['record']);
		unset($post['products']);
		$data = $this->helper->getUpdateString($post);		
		$sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$post['id'];
		$rs = mysql_query($sql);
		if(!$rs)
		{
			return mysql_error();
		}
		return $rs;
	}
	
	public function skuData($sku)
    {
		$data = array();	
			
		$rs = mysql_query("SELECT * FROM dai_product p LEFT JOIN dai_product_value pv ON p.id = pv.product_id WHERE sku='".$sku."'");
		while($row = mysql_fetch_assoc($rs))
		{
			$data =  $row;
		}	

		if(empty($data))
		{
			$field = mysql_num_fields( $rs );   
			for ( $i = 0; $i < $field; $i++ ) 
			{
				$data[mysql_field_name( $rs, $i )] = "";
			}			
		}		
		return  $data;			
    }
	public function getProductDetail($id)
    {
		
		$data = array();		
		if($id == "" )
			return $data;
	
		$rs = mysql_query("SELECT * FROM ".$this->table ." p LEFT JOIN ".$this->table_product ." pv ON p.id = pv.jewelry_id WHERE p.id=".$id);
		while($row = mysql_fetch_assoc($rs))
		{
			$data =  $row;
		}				
		return  $data;			
    }
}
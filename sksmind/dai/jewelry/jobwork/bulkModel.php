<?php
//include('../../../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
class bulkModel
{
    public $table;
	public $table_product;
	public $table_product_value;
	public $helper;
	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn = $db;
            $this->table  = "dai_inward";
			$this->table_product  = "dai_product";
			$this->table_product_value  = "dai_product_value";
			$this->helper  = new Helper($db);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	

	public function getDetail($id,$t='')
    {
		$data = array();		
		if($id == "" )
			return $data;
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE company=".$_SESSION['companyId']." and id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
		}				
		return  $data;			
    }
	public function getDetailBySku($sku)
    {
		$data = array();		
		
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." WHERE sku='".$sku."'");
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
			break;
		}				
		return  $data;			
    }
	public function importData($type,$inputFileName)
    {
			
		$attribute = $this->helper->getAttribute(1);
		
		try {
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}


		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
		$rs = "";
		
		if($type == "price")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$price = $allDataInSheet[$i]['B'];
				 
				$sku = trim($sku);
				$data = $this->getDetailBySku($sku);
				
				if($price=="" || $price==0)
				{
					$amount = 0;
					$price = 0;
				}
				else
				{
					$amount = (float)$data['polish_carat'] * (float)$price;
				}
				
				$sql = "UPDATE ".$this->table_product." SET price=$price,amount=$amount,site_upload=0,rapnet_upload=0 WHERE sku='".$sku."'";		
				
				
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			}
		}	
		elseif($type == "location")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$loc = $allDataInSheet[$i]['B'];
				
				$sql = "UPDATE ".$this->table_product." SET location='$loc',site_upload=0,rapnet_upload=0 WHERE sku='".$sku."'";		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			}
		}
		elseif($type == "intensity")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$int = trim(ucwords($allDataInSheet[$i]['B']));
				$over = trim(ucwords($allDataInSheet[$i]['C']));
				$color = trim(ucwords($allDataInSheet[$i]['D']));
				
				$sku = trim($sku);
				$data = $this->getDetailBySku($sku);
				
				if(empty($data))
					continue;
				if($color == "")	
					$sql = "UPDATE ".$this->table_product_value." SET intensity='$int',overtone='$over',color='$color' WHERE product_id=".$data['id'];		
				else	
					$sql = "UPDATE ".$this->table_product_value." SET intensity='$int',overtone='$over' WHERE product_id=".$data['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				
				$sql = "UPDATE ".$this->table_product." SET site_upload=0,rapnet_upload=0 WHERE id=".$data['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				
			}
		}
		return $rs;		
	}	
}


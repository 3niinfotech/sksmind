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
	

	public function getDetail($id,$t='')
    {
		$data = array();		
		if($id == "" )
			return $data;
		$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE company=".$_SESSION['companyId']." and id=".$id);
		while($row = mysql_fetch_assoc($rs))
		{
			$data =  $row;
		}				
		return  $data;			
    }
	public function getDetailBySku($sku)
    {
		$data = array();		
		//echo "SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and visibility=1 and sku LIKE '".$sku."'";
		
		
		$rs = mysql_query("SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and visibility=1 and sku LIKE '".$sku."'");
		while($row = mysql_fetch_assoc($rs))
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
		$rs = "Something Wrong !!!";
		
		if($type == "price")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$price = $allDataInSheet[$i]['B'];
				$cost = $allDataInSheet[$i]['C']; 
				$sku = trim($sku);
				$data = $this->getDetailBySku($sku);
				$oldPrice = $data['price'];
				if($price=="" || $price==0)
				{
					continue;
				}
				else
				{
					$amount = (float)$data['polish_carat'] * (float)$price;
				}
				
				if($cost == "")
					$sql = "UPDATE ".$this->table_product." SET price=$price,amount=$amount,site_upload=0,rapnet_upload=0 WHERE sku='".$sku."'";		
				else	
					$sql = "UPDATE ".$this->table_product." SET cost=$cost,price=$price,amount=$amount,site_upload=0,rapnet_upload=0 WHERE sku='".$sku."'";		
				
				
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
				$history = array();
				$history['product_id'] = $data['id'];
				$history['action'] = 'price_change';
				$history['date'] = date("Y-m-d H:i:s");
				$history['narretion'] = "cost price or base price are changed.";
				$history['description'] = "Old Price :  ".$oldPrice ." , New Price :  ".$price;
				$history['price'] = $price;
				$history['amount'] = $amount;
				$rs = $this->helper->addHistory($history);
				
			}
		}	
		elseif($type == "location")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$loc = $allDataInSheet[$i]['B'];
				
				$sql = "UPDATE ".$this->table_product." SET location='$loc',site_upload=0,rapnet_upload=0 WHERE sku='".$sku."'";		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
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
					
				if($color != "")	
					$sql = "UPDATE ".$this->table_product_value." SET intensity='$int',overtone='$over',color='$color' WHERE product_id=".$data['id'];		
				else	
					$sql = "UPDATE ".$this->table_product_value." SET intensity='$int',overtone='$over' WHERE product_id=".$data['id'];		
					
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
				
				$sql = "UPDATE ".$this->table_product." SET site_upload=0,rapnet_upload=0 WHERE id=".$data['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
				
			}
		}		
		elseif($type == "package")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = trim($allDataInSheet[$i]['A']);
				$package = trim(ucwords($allDataInSheet[$i]['B']));
				
				$data = $this->getDetailBySku($sku);
				
				if(empty($data))
					continue;
					
				$sql = "UPDATE ".$this->table_product_value." SET package='$package' WHERE product_id=".$data['id'];		
					
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}				
				/*$sql = "UPDATE ".$this->table_product." SET site_upload=0,rapnet_upload=0 WHERE id=".$data['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}*/
				
			}
		}
		elseif($type == "sku")
		{
			
			
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = trim($allDataInSheet[$i]['A']);
			
				$newsku = trim(ucwords($allDataInSheet[$i]['B']));
				
				
				$data = $this->getDetailBySku($sku);
				$Newdata = $this->getDetailBySku($newsku);
			
				$oldSku = $data['sku'];
				
				if(empty($data) || $sku == $newsku || !empty($Newdata))
				{
					continue;
				}	
				
				$sql = "UPDATE ".$this->table_product." SET sku='$newsku' WHERE id=".$data['id'];		
					
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}				
				$history = array();
				$history['product_id'] = $data['id'];
				$history['action'] = 'sku_change';
				$history['date'] = date("Y-m-d H:i:s");
				$history['narretion'] = "Sku Changed";
				$history['description'] = "Old Sku :  ".$oldSku ." , New Sku :  ".$newsku;
				$rs = $this->helper->addHistory($history);				
			}			
		}
		elseif($type == "shape")
		{
			
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = trim($allDataInSheet[$i]['A']);
				$shape = trim(ucwords($allDataInSheet[$i]['B']));
				$color = trim(ucwords($allDataInSheet[$i]['C']));
				$clarity = trim(ucwords($allDataInSheet[$i]['D']));
				$size = trim(ucwords($allDataInSheet[$i]['E']));
				
				$data = $this->getDetailBySku($sku);
				
				if(empty($data))
					continue;
					
				$sql = "UPDATE ".$this->table_product." SET main_color='$color' WHERE id=".$data['id'];							
				$rs = mysql_query($sql);
				
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}				
				$sql = "UPDATE ".$this->table_product_value." SET shape='$shape',color='$color',clarity='$clarity',size='$size' WHERE product_id=".$data['id'];							
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
			}
		}
		elseif($type == "gia")
		{
			
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = trim($allDataInSheet[$i]['A']);
				$gia = trim(ucwords($allDataInSheet[$i]['B']));
				
				$ProductData = $this->getDetailBySku($sku);
				
				if(empty($ProductData))
					continue;
					
				$gData = $this->helper->getGiaReport(trim($gia));
			
				if($gData['message']!="")
				{
					continue;
				}	
			
				$color = $gData['color'];
				$pcarat = ($gData['weight'] !='' & $gData['weight'] !=0 ) ? $gData['weight'] : $ProductData['polish_carat'];
				$amount = $pcarat * $ProductData['price'];
				$sql = "UPDATE ".$this->table_product." SET lab='GIA',main_color='$color',polish_carat='$pcarat', amount='$amount',outward='',site_upload=0,rapnet_upload=0,is_uploadsite=1,is_uploadrapnet=1,visibility=1 WHERE id=".$ProductData['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					return mysql_error();				
				}
				else
				{
					$attr = $this->helper->getAttributeField();
					$data = array();
					foreach($attr['record'] as $ak=>$av)
					{
						if($ak == 'size' || $ak == 'color')
							continue;
							
						if(isset($gData[$ak]))	
							$data[$ak] = $gData[$ak];
						
					}
					//$data['intensity'] = $v['intensity'];
					//$data['overtone'] = $v['overtone'];
					//$data['color'] = $v['color'];
					$value = $this->helper->getUpdateString($data);	
					$sql = "UPDATE ".$this->table_product_value." SET ".$value." WHERE product_id=".$ProductData['id'];;		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();
						
					}				
				}
			}
		}
		else if($type == "rap_price")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$price = $allDataInSheet[$i]['B'];
				
				$sku = trim($sku);
				$data = $this->getDetailBySku($sku);
				$oldPrice = $data['rap_price'];
				if($price=="" || $price==0)
				{
					continue;
				}
				else
				{
					$amount = (float)$data['polish_carat'] * (float)$price;
				}
				
					
				$sql = "UPDATE ".$this->table_product." SET rap_price=$price,rap_amount=$amount,site_upload=0,rapnet_upload=0 WHERE sku='".$sku."'";		
				
				
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
				$history = array();
				$history['product_id'] = $data['id'];
				$history['action'] = 'price_change';
				$history['date'] = date("Y-m-d H:i:s");
				$history['narretion'] = "Rap price are changed.";
				$history['description'] = "Old Price :  ".$oldPrice ." , New Price :  ".$price;
				$history['price'] = $price;
				$history['amount'] = $amount;
				$rs = $this->helper->addHistory($history);
				
			}
		}
		return $rs;		
	}	
}


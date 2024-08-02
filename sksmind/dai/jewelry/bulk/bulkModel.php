<?php
//include('../../../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
include_once('../../jHelper.php');
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
            $this->table  = "jew_inward";
			$this->table_product  = "jew_product";
			$this->table_loose_product  = "jew_loose_product";
			$this->helper  = new Helper($db);
			$this->jhelper  = new jHelper($db);
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
		//echo "SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and visibility=1 and sku LIKE '".$sku."'";
		
		
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and visibility=1 and sku LIKE '".$sku."'");
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
			break;
		}				
		return  $data;			
    }
	public function getDetailBySideSku($sku)
    {
		$data = array();		
		//echo "SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and visibility=1 and sku LIKE '".$sku."'";
		
		
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_loose_product ." WHERE company=".$_SESSION['companyId']." and visibility=1 and sku LIKE '".$sku."'");
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
		$rs = "Something Wrong !!!";
		$skuarr = $spid = array();
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
					$amount = (float)$data['carat'] * (float)$price;
				}
				
				if($cost == "")
					$sql = "UPDATE ".$this->table_product." SET price=$price,amount=$amount WHERE sku='".$sku."'";		
				else	
					$sql = "UPDATE ".$this->table_product." SET cost=$cost,price=$price,amount=$amount WHERE sku='".$sku."'";		
				
				
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				/* $history = array();
				$history['product_id'] = $data['id'];
				$history['action'] = 'price_change';
				$history['date'] = date("Y-m-d H:i:s");
				$history['narretion'] = "cost price or base price are changed.";
				$history['description'] = "Old Price :  ".$oldPrice ." , New Price :  ".$price;
				$history['price'] = $price;
				$history['amount'] = $amount;
				//$history['sku'] = $sku;
				$history['for_history'] = 'main';
				$rs = $this->jhelper->addHistory($history); */
			}
				
		}
		else if($type == "cost_measurement")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$price = $allDataInSheet[$i]['B'];
				$measurement = $allDataInSheet[$i]['C']; 
				$sku = trim($sku);
				$data = $this->getDetailBySku($sku);
				
				$oldPrice = $data['price'];
				if($price=="" && $measurement=="")
				{
					continue;
				}
				if($price == "" || $price == 0)
					$sql = "UPDATE ".$this->table_product." SET measurement='$measurement' WHERE sku='".$sku."'";	
				else if($measurement == "")	
					$sql = "UPDATE ".$this->table_product." SET cost=$price WHERE sku='".$sku."'";
				else
					$sql = "UPDATE ".$this->table_product." SET cost=$price,measurement='$measurement' WHERE sku='".$sku."'";		
				
				
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			}
				
		}
		elseif($type == "collet_cost_amount")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$amount = $allDataInSheet[$i]['B'];
				
				$sql = "UPDATE jew_collet SET total_amount_cost='$amount' WHERE type='collet_receive' and sku='".$sku."'";		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			}
		}
		elseif($type == "jew_name")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$name = $allDataInSheet[$i]['B'];
				
				$sql = "UPDATE jew_jewelry SET name='$name' WHERE sku='".$sku."'";		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			}
		}	
		elseif($type == "jew_price")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$askamount = $allDataInSheet[$i]['B'];
				$sellamount = $allDataInSheet[$i]['C'];
				
				if($askamount == "" && $sellamount=="")
				continue;
			
				$sql = "UPDATE jew_jewelry SET sell_price='$sellamount',selling_price='$askamount' WHERE sku='".$sku."'";		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			}
		}
		elseif($type == "igi_update")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$lab = $allDataInSheet[$i]['B'];
				$code = $allDataInSheet[$i]['C'];
				$color = $allDataInSheet[$i]['D'];
				$clarity = $allDataInSheet[$i]['E'];
				$amount = $allDataInSheet[$i]['F'];
				
				$sql = "UPDATE ".$this->table_product." SET lab='$lab',igi_code='$code',report_no='$code',color='$color',igi_color='$color',igi_clarity='$clarity',clarity='$clarity',igi_amount=$amount WHERE sku='".$sku."'";		
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
				
				$sql = "UPDATE ".$this->table_product." SET location='$loc' WHERE sku='".$sku."'";		
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
					
				if($color != "")	
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
					
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}				
				/*$sql = "UPDATE ".$this->table_product." SET site_upload=0,rapnet_upload=0 WHERE id=".$data['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
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
				/* $skuarr[] = $sku;
				$spid[] = $data['id']; */
				$Newdata = $this->getDetailBySku($newsku);
			
				$oldSku = $data['sku'];
				
				if(empty($data) || $sku == $newsku || !empty($Newdata))
				{
					continue;
				}	
				
				$sql = "UPDATE ".$this->table_product." SET sku='$newsku' WHERE id=".$data['id'];		
					
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}				
				$history = array();
				$history['product_id'] = $data['id'];
				$history['action'] = 'sku_change';
				$history['date'] = date("Y-m-d H:i:s");
				$history['narretion'] = "Sku Changed";
				$history['description'] = "Old Sku :  ".$oldSku ." , New Sku :  ".$newsku;
				$history['for_history'] = "main";
				$rs = $this->jhelper->addHistory($history);			
			}
				/* $alsku = implode(",",$skuarr);
				$alspid = implode(",",$spid);
				$track = array();
				$track['product_id'] = $alspid;
				$track['action'] = 'sku_change';
				$track['date'] = date("Y-m-d H:i:s");
				$track['description'] = "Sku Changed of $alsku";
				$track['company'] = $_SESSION['companyId'];
				$track['user'] = $_SESSION['userid'];
				$rs = $this->jhelper->addUserTrack($track);	 */
		}
		elseif($type == "side_sku")
		{
			
			
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = trim($allDataInSheet[$i]['A']);
			
				$newsku = trim(ucwords($allDataInSheet[$i]['B']));
				
				
				$data = $this->getDetailBySideSku($sku);
				/* $skuarr[] = $sku;
				$spid[] = $data['id']; */
				$Newdata = $this->getDetailBySideSku($newsku);
			
				$oldSku = $data['sku'];
				
				if(empty($data) || $sku == $newsku || !empty($Newdata))
				{
					continue;
				}	
				
				$sql = "UPDATE ".$this->table_loose_product." SET sku='$newsku' WHERE id=".$data['id'];		
					
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}				
				$history = array();
				$history['product_id'] = $data['id'];
				$history['action'] = 'sku_change';
				$history['date'] = date("Y-m-d H:i:s");
				$history['narretion'] = "Sku Changed";
				$history['description'] = "Old Sku :  ".$oldSku ." , New Sku :  ".$newsku;
				$history['for_history'] = "side";
				$rs = $this->jhelper->addHistory($history);			
			}
				/* $alsku = implode(",",$skuarr);
				$alspid = implode(",",$spid);
				$track = array();
				$track['product_id'] = $alspid;
				$track['action'] = 'sku_change';
				$track['date'] = date("Y-m-d H:i:s");
				$track['description'] = "Sku Changed of $alsku";
				$track['company'] = $_SESSION['companyId'];
				$track['user'] = $_SESSION['userid'];
				$rs = $this->jhelper->addUserTrack($track);	 */
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
				$rs = mysqli_query($this->conn,$sql);
				
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}				
				$sql = "UPDATE ".$this->table_product_value." SET shape='$shape',color='$color',clarity='$clarity',size='$size' WHERE product_id=".$data['id'];							
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
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
					
				$gData = $this->jhelper->getGiaReport(trim($gia));
			
				if($gData['message']!="")
				{
					continue;
				}	
			
				$color = $gData['color'];
				$pcarat = ($gData['weight'] !='' & $gData['weight'] !=0 ) ? $gData['weight'] : $ProductData['polish_carat'];
				$amount = $pcarat * $ProductData['price'];
				$sql = "UPDATE ".$this->table_product." SET lab='GIA',main_color='$color',polish_carat='$pcarat', amount='$amount',outward='',site_upload=0,rapnet_upload=0,is_uploadsite=1,is_uploadrapnet=1,visibility=1 WHERE id=".$ProductData['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();				
				}
				else
				{
					$attr = $this->jhelper->getAttributeField();
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
					$value = $this->jhelper->getUpdateString($data);	
					$sql = "UPDATE ".$this->table_product_value." SET ".$value." WHERE product_id=".$ProductData['id'];;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						
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
				$skuarr[] = $sku;
				$spid[] = $data['id'];
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
				
				
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
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
				$rs = $this->jhelper->addHistory($history);	
			}
				$alsku = implode(",",$skuarr);
				$alspid = implode(",",$spid);
				$track = array();
				$track['product_id'] = $alspid;
				$track['action'] = 'price_change';
				$track['date'] = date("Y-m-d H:i:s");
				$track['description'] = "Rap price changed of $alsku";
				$track['company'] = $_SESSION['companyId'];
				$track['user'] = $_SESSION['userid'];
				$rs = $this->jhelper->addUserTrack($track);	
		}
		else if($type == "group")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$main = strtoupper($allDataInSheet[$i]['B']);
				$sub = strtoupper($allDataInSheet[$i]['C']);
				
				$sku = trim($sku);
				$data = $this->getDetailBySku($sku);
				
				if(empty($data))
					continue;
					
				$sql = "UPDATE ".$this->table_product." SET main_group='$main',sub_group='$sub' WHERE id='".$data['id']."'";		
				
				
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				
				
			}
		}
		else if($type == "sku-pair")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$pair = trim($allDataInSheet[$i]['B']);
				
				
				$sku = trim($sku);
				$data = $this->getDetailBySku($sku);
				
				if(!empty($data))
				{
					
					$sql = "UPDATE ".$this->table_product." SET pair='$pair',site_upload=0,rapnet_upload=0 WHERE id='".$data['id']."'";												
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
				}
				
				$data1 = $this->getDetailBySku($pair);
				if(!empty($data1))
				{
					
					$sql = "UPDATE ".$this->table_product." SET pair='$sku',site_upload=0,rapnet_upload=0  WHERE id='".$data1['id']."'";												
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
				}
				
			}
		}
		
		else if($type == "bgm-eyeclean")
		{
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = $allDataInSheet[$i]['A'];
				$bgm = trim($allDataInSheet[$i]['B']);
				$eye = trim($allDataInSheet[$i]['C']);
				
				
				$sku = trim($sku);
				$data = $this->getDetailBySku($sku);
				
				if(!empty($data))
				{
					
					$sql = "UPDATE ".$this->table_product_value." SET bgm='$bgm',eyeclean='$eye' WHERE product_id='".$data['id']."'";												
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
				}
				
				
			}
		}
		elseif($type == "csv-gia")
		{
		
			for($i=2;$i<=$arrayCount;$i++)
			{
				$sku = trim($allDataInSheet[$i]['F']);				
				$report = trim($allDataInSheet[$i]['D']);				
				$json_value = json_encode($allDataInSheet[$i]);
				
				$temp['sku'] = $sku;
				$temp['report'] = $report;
				$temp['value'] = $json_value;
				
				$get = "SELECT * FROM dai_gia WHERE sku ='$sku'";
				$rs1 = mysqli_query($this->conn,$get);				
				$gid = '';
				while($row = mysqli_fetch_assoc($rs1))
				{
					$gid = $row['id'];
					break;
				}				
				
				if($gid == '')
				{
					$data = $this->jhelper->getInsertString($temp);	
				
			
					$sql = "INSERT INTO dai_gia (". $data[0].") VALUES (".$data[1].")";	
					$rs = mysqli_query($this->conn,$sql);
						
					if(!$rs)
					{
						return mysqli_error();				
					}
				
				}
				else
				{
					$values = $this->jhelper->getUpdateString($temp);	
					$sql = "UPDATE dai_gia SET ".$values." WHERE id=".$gid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						return mysqli_error();				
					}
				}
				/* $ProductData = $this->getDetailBySku($sku);
			
				if(empty($ProductData))
					continue;
					
				
				$temp = explode(",",$allDataInSheet[$i]['N']);
				
				$color = (isset($temp[1])) ? ($temp[1]) : $allDataInSheet[$i]['N'] ;
				
				$shape = $allDataInSheet[$i]['H'];
				$shape = str_replace("*","",$shape);
				
				if($shape == 'OMB')
					$shape='Oval';				
				
				if($shape == 'CMB')
					$shape='Cushion';
				
				if($shape == 'HB')
					$shape='Heart';
				
				if($shape == 'PB')
					$shape='Pear';
				
				
				$pcarat = trim($allDataInSheet[$i]['L']);
				$amount = $pcarat * $ProductData['price'];
				
				$up['lab'] = 'GIA';
				$up['main_color'] = $color;
				$up['polish_carat'] = $pcarat;
				$up['amount'] = $amount;
				$up['site_upload'] = 0;
				$up['rapnet_upload'] = 0;
				$up['is_uploadsite'] = 1;
				$up['is_uploadrapnet'] = 1;
						
				
				$values = $this->helper->getUpdateString($up);	
				$sql = "UPDATE ".$this->table_product." SET ".$values." WHERE id=".$ProductData['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();				
				}
				else
				{
					
					$data = array();
					$data['shape'] =  trim($shape);
					$data['color'] =  trim($color);
					$data['report_no'] =  trim($allDataInSheet[$i]['D']);
					$data['clarity'] =  trim($allDataInSheet[$i]['O']);
					$data['cut'] =  trim($allDataInSheet[$i]['Q']);
					$data['polish'] =  trim($allDataInSheet[$i]['R']);
					$data['symmentry'] =  trim($allDataInSheet[$i]['S']);
					$data['f_intensity'] =  trim($allDataInSheet[$i]['T']);
					$data['gridle'] =  trim($allDataInSheet[$i]['V']);
					$data['mesurment'] =  trim($allDataInSheet[$i]['I']).'*'.trim($allDataInSheet[$i]['J']).'*'.trim($allDataInSheet[$i]['K']);
					$data['depth_pc'] =  trim($allDataInSheet[$i]['Y']);
					$data['table_pc'] =  trim($allDataInSheet[$i]['Z']);
					
					$value = $this->helper->getUpdateString($data);	
					$sql = "UPDATE ".$this->table_product_value." SET ".$value." WHERE product_id=".$ProductData['id'];;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						
					}				
				} */
			}
		}
		return $rs;		
	}	
}


<?php
//include('../../../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
class boxModel
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
     public function getMyInventory($sku)	
    {
		$sku = trim($sku);
		if( $sku !='')
			$sku = " and sku like'%".$sku."%'";
		
		$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE p.company=".$_SESSION['companyId']." and (p.outward='' || p.outward='memo' || p.outward='lab') and p.group_type='box' and visibility=1 and polish_carat > 0 ".$sku." ORDER BY sku");
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data[] =  $row;
		}
		
		return  $data;			
    }

	public function getDetail($id,$t='')
    {
		$data = array();
		if($t=='all')
		{
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
		}
		else
		{
			$rs = mysql_query("SELECT polish_pcs,polish_carat,price,amount FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and id=".$id);
			while($row = mysql_fetch_assoc($rs))
			{
				$data = $row;
			}	
		}				
		return  $data;			
    }	
	public function toSingle($post)
	{
		
		try
		{
			$rs = 1;			
			$id = $post['id'];
			$edata = $this->getDetail($id,'all');
			
			$products = $post['products'];
			$bp = array();	
			foreach(explode(',',$edata['box_products'] ) as $k => $v)
			{
				$data = $this->getDetail($v,'all');
				if(!in_array($v,$products))
				{
					$bp[] = $v;
					continue;
				}
				$edata['rought_pcs']  -= $data['rought_pcs'];
				$edata['rought_carat']  -= $data['rought_carat'];
				$edata['polish_pcs']  -= $data['polish_pcs'];
				$edata['polish_carat']  -= $data['polish_carat'];
				$edata['cost']  -= $data['cost'];
				$edata['price']  -= $data['price'];
				$edata['amount']  -= $data['amount'];
				

				$sql = "UPDATE ".$this->table_product." SET box_id=''  WHERE id=".$data['id'];		
						
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					break;					
				}
				
				$history =array();
				$history['product_id'] = $v;
				$history['action'] = 'from_box';	
				$history['type'] = 'cr';
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Importing by Unboxing from ".$edata['sku']." with  carat :".$v['polish_carat'];
				$history['pcs'] = $data['polish_pcs'];
				$history['carat'] = $data['polish_carat'];
				$history['amount'] = $data['amount'];
				$history['price'] = $data['price'];
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				
				$history = array();
				$history['product_id'] = $edata['id'];				
				$history['action'] = "unboxing";
				$history['type'] = 'dr';
				$history['description'] = "Unboxing for Sku : ".$data['sku'];
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = $data['polish_pcs'];
				$history['carat'] = $data['polish_carat'];
				$history['amount'] = $data['amount'];
				$history['price'] = $data['price'];
				$history['sku'] = $edata['sku'];				
				
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
			}
			
			if($rs)
			{	
				$record = $edata['record'];
				unset($edata['record']);
				
				
				$edata['box_products'] = implode(",",$bp);
				$edata['parcel_products']  ="";
				
			
				$data = $this->helper->getUpdateString($edata);	
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$edata['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();				
				}
				else
				{
					$data = $this->helper->getUpdateString($record);	
					$sql = "UPDATE ".$this->table_product_value." SET ".$data." WHERE product_id=".$edata['id'];		
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
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	
	public function toSeparateSingle($post)
	{
		$rs = 1;			
		$id = $post['id'];
		$edata = $this->getDetail($id,'all');
			
		if($edata['box_products'] !="" && $edata['parcel_products'] !="" )
			return  "You can not separate this box / parcel.";
			
		try
		{
			$tc = $tp = 0;
			$skus= array();
			foreach($post['record'] as $k => $v)
			{
				$tp += (float)$v['polish_pcs'];
				$tc += (float)$v['polish_carat'];
			}
			
			if($tc > $edata['polish_carat'])
				return "Carat are exceed than Stock Carat.";
			
			if($tp > $edata['polish_pcs'] && $edata['group_type']=="box")
				return "Pcs are exceed than Stock Carat.";
				
			foreach($post['record'] as $k => $v)
			{	
				$v['sku'] = trim($v['sku']);
				if($v['sku'] =="" || $v['polish_carat'] =="" || $v['price'] =="" || $v['amount'] =="") 
					continue;
				$flag = 0;
				$SkuData = $this->helper->getDataBySku($v['sku']);
				if(empty($SkuData))
				{	
					$skus[$k] = $v['sku'];			
					if($edata['group_type']=="box")
						$edata['polish_pcs']  -= $v['polish_pcs'];
					
					$edata['polish_carat']  -= $v['polish_carat'];
					
					
					foreach($edata['record'] as $k => $val)
					{
						if( isset($v['attr'][$k]) && $v['attr'][$k] == '' )
						{
							$v['attr'][$k] = $val ;
						}						
					}
					
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
					$v['parent_id'] = $edata['id'];
					$v['transaction'] = 'dr';
					$v['visibility'] = 1;
							
						
					$attr = (array)$v['attr'];	
					$v['main_color'] = (isset($attr['color'])) ? $attr['color'] : '';						
					unset($v['attr']);
					unset($attr['pid']);
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
						$flag = 1;
						$pid = mysql_insert_id();					
						$attr['product_id'] = $pid;	
											
						$data = $this->helper->getInsertString($attr);	
						$sql = "INSERT INTO dai_product_value (". $data[0].") VALUES (".$data[1].")";		
						$rs = mysql_query($sql);
						if(!$rs)			
						{
							$rs = mysql_error();
							return $rs;
						}
						$history =array();
						$history['product_id'] = $pid;
						$history['action'] = 'from_box';	
						$history['type'] = 'cr';
						$history['date'] = date("Y-m-d H:i:s");
						$history['description'] = "Importing by Unboxing from ".$edata['sku']." with  carat :".$v['polish_carat'];
						$history['pcs'] = $v['polish_pcs'];
						$history['carat'] = $v['polish_carat'];
						$history['amount'] = $v['amount'];
						$history['price'] = $v['price'];
						$history['sku'] = $v['sku'];
						$rs = $this->helper->addHistory($history);					
					}
					$history = array();
				/*	$history['product_id'] = $edata['id'];				
					$history['action'] = "unboxing";
					$history['type'] = 'dr';
					$history['description'] = "Unboxing for Sku : ".$v['sku'];
					$history['date'] = date("Y-m-d H:i:s");				
					$history['pcs'] = $v['polish_pcs'];
					$history['carat'] = $v['polish_carat'];
					$history['amount'] = $v['amount'];
					$history['price'] = $v['price'];
					$history['sku'] = $edata['sku'];				
					
					$rs = $this->helper->addHistory($history); 
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					} */
				}
				else
				{
						
					if($SkuData['group_type'] == 'box' || $SkuData['group_type'] == 'parcel'  )
					{
						if($edata['group_type']=="box")
						$edata['polish_pcs']  -= $v['polish_pcs'];
					
						$edata['polish_carat']  -= $v['polish_carat'];
					
					$SkuData['polish_pcs'] += (float)$v['polish_pcs'];
					$SkuData['polish_carat'] += (float)$v['polish_carat'];
					$SkuData['amount'] =  $SkuData['polish_carat']  * $SkuData['price'];
					
					$sql = "UPDATE dai_product SET amount=".$SkuData['amount'].",polish_pcs=".$SkuData['polish_pcs'].", polish_carat=".$SkuData['polish_carat']."   WHERE id=".$SkuData['id'];		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						return mysql_error();					
					}
					$history['product_id'] = $SkuData['id'];
					$history['action'] = 'boxing';										
					$history['date'] = date("Y-m-d H:i:s");;
					$history['description'] = "boxing from sku : ".$edata['sku'];
					$history['pcs'] = $v['polish_pcs'];
					$history['carat'] = $v['polish_carat'];
					$history['amount'] = $v['amount'];
					$history['price'] = $v['price'];
					$history['sku'] = $SkuData['sku'];
					$history['type'] = 'cr';	
					
					$rs = $this->helper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
					$flag = 1;
					}					
				}
				if($flag == 0)
					continue;
				
				$record = $edata['record'];
				unset($edata['record']);
				
				$data = $this->helper->getUpdateString($edata);	
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$edata['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();				
				}
				$history['product_id'] = $edata['id'];
				$history['action'] = 'unboxing';										
				$history['date'] = date("Y-m-d H:i:s");;
				$history['description'] = "unboxing for sku : ".$v['sku'];
				$history['pcs'] = $v['polish_pcs'];
				$history['carat'] = $v['polish_carat'];
				$history['amount'] = $v['amount'];
				$history['price'] = $v['price'];
				$history['sku'] = $edata['sku'];
				$history['type'] = 'dr';	
				
				$rs = $this->helper->addHistory($history);
						
			}
			
			return $rs;		
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	public function boxToParcel($post)
	{
		try
		{
			$rs = 1;			
			$id = $post['id'];
			$edata = $this->getDetail($id,'all');
			
			$products = explode(',',$post['products']);
			$bp = array();	
			foreach(explode(',',$edata['box_products'] ) as $k => $v)
			{
				$data = $this->getDetail($v,'all');
				if(!in_array($v,$products))
				{
					$bp[] = $v;
					continue;
				}
				$edata['rought_pcs']  -= $data['rought_pcs'];
				$edata['rought_carat']  -= $data['rought_carat'];
				$edata['polish_pcs']  -= $data['polish_pcs'];
				$edata['polish_carat']  -= $data['polish_carat'];
				$edata['cost']  -= $data['cost'];
				$edata['price']  -= $data['price'];
				$edata['amount']  -= $data['amount'];
				

				$sql = "UPDATE ".$this->table_product." SET box_id=''  WHERE id=".$data['id'];		
						
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					break;					
				}

				$history =array();
				$history['product_id'] = $v;
				$history['action'] = 'from_box';	
				$history['type'] = 'cr';
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Importing by Unboxing from ".$edata['sku']." with  carat :".$v['polish_carat'];
				$history['pcs'] = $data['polish_pcs'];
				$history['carat'] = $data['polish_carat'];
				$history['amount'] = $data['amount'];
				$history['price'] = $data['price'];
				$rs = $this->helper->addHistory($history);
					
				$history = array();
				$history['product_id'] = $edata['id'];				
				$history['action'] = "unboxing";
				$history['type'] = 'dr';
				$history['description'] = "Unboxing for Sku : ".$data['sku'];
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = $data['polish_pcs'];
				$history['carat'] = $data['polish_carat'];
				$history['amount'] = $data['amount'];
				$history['price'] = $data['price'];
				$history['sku'] = $edata['sku'];				
				
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
			}
			
			if($rs)
			{	
				$record = $edata['record'];
				unset($edata['record']);
				
				
				$edata['box_products'] = implode(",",$bp);
				$edata['parcel_products']  ="";
				
			
				$data = $this->helper->getUpdateString($edata);	
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$edata['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();				
				}
				else
				{
					unset($record['product_id']);
					$data = $this->helper->getUpdateString($record);	
					$sql = "UPDATE ".$this->table_product_value." SET ".$data." WHERE product_id=".$edata['id'];		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();
						break;							
					}
				}				
			}
			$post['products'] = $products ;
			$rs = $this->helper->toBoxOrParcel($post);	
			
			return $rs;		
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
}




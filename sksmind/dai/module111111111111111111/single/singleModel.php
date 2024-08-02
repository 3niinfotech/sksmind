<?php
//include('../../../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
class singleModel
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
		
		$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE p.company=".$_SESSION['companyId']." and (p.outward='' || p.outward='memo' || p.outward='lab') and p.group_type='single' and p.box_id='' and p.parcel_id='' and visibility=1 ".$sku."  ORDER BY p.lab desc,p.sku");
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
			$rs = mysql_query("SELECT * FROM ".$this->table_product ." p  WHERE p.company=".$_SESSION['companyId']." and p.id=".$id);
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
	public function toBox($post)
	{
		try
		{
		
		$type = $post['type'];
		if($post['btype'] == 'existing')
		{
			$id = $post['boxcode'];
			$edata = $this->getDetail($id,'all');
			if($post['esku'] != '')
					$edata['sku'] = $post['esku'];
				
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
					$sql = "UPDATE ".$this->table_product." SET box_id='".$edata['id']."'  WHERE id=".$data['id'];		
				else
					$sql = "UPDATE ".$this->table_product." SET parcel_id='".$edata['id']."'  WHERE id=".$data['id'];		
					
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					break;					
				}
				
				
				$history = array();
				$history['product_id'] = $v;				
				$history['action'] = "to_box";
				$history['type'] = 'dr';
				$history['description'] = "To Boxing in Sku : ".$edata['sku'];
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = $data['polish_pcs'];
				$history['carat'] = $data['polish_carat'];
				$history['amount'] = $data['amount'];
				$history['price'] = $data['price'];
				$history['sku'] = $data['sku'];				
				
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
					
				$history = array();
				$history['product_id'] = $edata['id'];				
				$history['action'] = "boxing";
				$history['type'] = 'cr';
				$history['description'] = "Boxing of Sku : ".$data['sku'];
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

				if($post['esku'] != '')
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
		}
		else
		{
			$edata = $this->helper->getAttributeField(1);
			
			$edata['mfg_code'] =  $post['newbox'];
			
			$edata['sku'] = $post['sku'];
			if($post['sku'] == '')
				$edata['sku'] = $edata['mfg_code'];
			
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
			unset($edata['record']);
			$data = $this->helper->getInsertString($edata);	
			$sql = "INSERT INTO ".$this->table_product ." (". $data[0].") VALUES (".$data[1].")";		
			$rs = mysql_query($sql);
			if(!$rs)			
			{
				$rs = mysql_error();				
			}
			else
			{
				$pid = mysql_insert_id();
				$attr['product_id'] = $pid;
				
				$data = $this->helper->getInsertString($attr);	
				$sql = "INSERT INTO ".$this->table_product_value ." (". $data[0].") VALUES (".$data[1].")";		
				$rs = mysql_query($sql);
				if(!$rs)			
				{
					$rs = mysql_error();
					break;
				}
				
				foreach($post['products'] as $k => $v)
				{
					$data = $this->getDetail($v,'all');
					
					if($type=="box")
						$sql = "UPDATE ".$this->table_product." SET box_id='".$pid ."' WHERE id=".$v;	
					else
						$sql = "UPDATE ".$this->table_product." SET parcel_id='".$pid ."' WHERE id=".$v;		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();
						break;					
					}
					
					$history = array();
					$history['product_id'] = $v;				
					$history['action'] = "to_box";
					$history['type'] = 'dr';
					$history['description'] = "To Boxing in Sku : ".$edata['sku'];
					$history['date'] = date("Y-m-d H:i:s");				
					$history['pcs'] = $data['polish_pcs'];
					$history['carat'] = $data['polish_carat'];
					$history['amount'] = $data['amount'];
					$history['price'] = $data['price'];
					$history['sku'] = $data['sku'];				
					
					$rs = $this->helper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
					
					$history = array();
					$history['product_id'] = $pid;				
					$history['action'] = "boxing";
					$history['type'] = 'cr';
					$history['description'] = "Boxing of Sku : ".$post['sku'];
					$history['date'] = date("Y-m-d H:i:s");				
					$history['pcs'] = $data['polish_pcs'];
					$history['carat'] = $data['polish_carat'];
					$history['amount'] = $data['amount'];
					$history['price'] = $data['price'];
					$history['sku'] = $data['sku'];				
					
					$rs = $this->helper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
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
}


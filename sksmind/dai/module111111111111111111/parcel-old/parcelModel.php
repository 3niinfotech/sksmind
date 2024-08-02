<?php
//include('../../../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
class parcelModel
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
    public function getMyInventory()
    {
		$rs = mysql_query("SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and (outward='' || outward='memo' || outward='lab') and group_type='parcel' ORDER BY lab desc");
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$rs1 = mysql_query("SELECT * FROM ".$this->table_product_value ." WHERE product_id =".$row['id']);
			
			while($row1 = mysql_fetch_assoc($rs1))
			{
				$row[$row1['code']] = $row1['value'];
			}
			$data[] =  $row;
		}
		
		return  $data;			
    }

	public function getDetail($id,$t='')
    {
		$data = array();
		if($t=='all')
		{
			$rs = mysql_query("SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and id=".$id);
			while($row = mysql_fetch_assoc($rs))
			{
				$rs1 = mysql_query("SELECT * FROM ".$this->table_product_value ." WHERE product_id =".$row['id']);
				
				while($row1 = mysql_fetch_assoc($rs1))
				{
					$row['record'][$row1['code']] = $row1['value'];
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
			foreach(explode(',',$edata['parcel_products'] ) as $k => $v)
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
				

				$sql = "UPDATE ".$this->table_product." SET parcel_id=''  WHERE id=".$data['id'];		
						
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					break;					
				}
				
			}
			
			if($rs)
			{	
				$record = $edata['record'];
				unset($edata['record']);
				
				
				$edata['parcel_products'] = implode(",",$bp);
				$edata['box_products']  ="";
				
			
				$data = $this->helper->getUpdateString($edata);	
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$edata['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();				
				}
				else
				{
					foreach($record as $k=>$v)
					{
						$sql = "UPDATE ".$this->table_product_value." SET value='".$v."' WHERE product_id=".$edata['id']." and code='".$k."'";		
						$rs = mysql_query($sql);
						if(!$rs)
						{
							$rs = mysql_error();
							break;							
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


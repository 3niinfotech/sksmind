<?php
include_once('../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
class outwardModel
{
    public $table;
	public $table_product;
	public $table_product_value;
	public $lab;
	public $helper;
	 function __construct()
    {
        try {
            $this->table  = "dai_outward";
			$this->table_product  = "dai_product";
			$this->table_product_value  = "dai_product_value";
			$this->lab  = "dai_lab";
			
			$this->helper  = new Helper;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getMyInventory()
    {
		$rs = mysql_query("SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." ORDER BY lab desc");
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
	public function sendToLab($post)
	{
		$post['date'] = date("Y-m-d H:i:s");
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;
		$post['user'] = $_SESSION['userid'];
		$post['user'] = $_SESSION['username'];
		$products = $post['id'];
		unset($post['id']);
		$post['products'] = implode(",",$products);
		
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->lab ." (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
			break;
		}
		else
		{
			foreach($products as $k=>$pid)
			{
				$sql = "UPDATE ".$this->table_product." SET send_to_lab=1,site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
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
	public function getParty($id)
	{
		$rs = mysql_query("SELECT * FROM dai_party WHERE id=".$id );
			
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data = $row;
		}
		return $data;
	}
	public function sendTo($post)
	{
		$type = $post['type'];
		
		$post['date'] = date("Y-m-d H:i:s");
		$incre_id = $this->getIncrementEntry('outward');
		$invoice = $this->getIncrementEntry('invoice');
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$post['entryno'] = $incre_id;	
		$post['invoiceno'] = $invoice;			
		$products = $post['products'];
		
		$record = array();
		if(isset($post['record']))
		{
			$record = $post['record'];
			unset($post['record']);
		}
		$balance = array();
	
		//unset($post['on_payment']);		
		$post['products'] = implode(",",$products);
		
			
		$OutProducts = array();
		$amount = 0;
		//check price and amount are blank or greter than exiesting box / parcel for carat
		//if yes then return message to outward page
		foreach($products as $k=>$pid)
		{
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
		foreach($products as $k=>$pid)
		{
			$edata = $this->getProductDetail($pid);
			
			//check carat and pcs of outward data with exiesting data if both are diferent than create new products
			if(isset($record[$pid]) && $record[$pid]['polish_carat'] !="" && ($edata['group_type'] =='box' || $edata['group_type'] =='parcel')  )
			{
				if( $record[$pid]['price'] =="" || $record[$pid]['amount'] =="" )
					return "Please Enter price and amount of carat";
				
				//create seperation from box / parcel for outward	
				$rid = $this->separateSale($record[$pid],$edata,$type,$post);
				if(is_numeric($rid))
				{
					$OutProducts[] =  $rid;
					$amount += (float)$record[$pid]['amount']; 
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
				$OutProducts[] =  $pid;
				$sql = "UPDATE ".$this->table_product." SET outward='$type',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
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
				$history['description'] = " Stone ".$type."  with reference no is ".$post['reference'];
				$history['pcs'] = $pdata['polish_pcs'];
				$history['carat'] = $pdata['polish_carat'];
				$history['amount'] = ($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] == 0)?$pdata['price']:$pdata['sell_price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['invoice'] = $post['invoiceno'];
				
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				$amount += (float)($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount']; 		
			}		
		}
		$post['due_amount'] = $amount;
		$post['final_amount'] = $amount;
		if(isset($post['on_payment']) && $type=="sale")
		{
			$balance['book'] = $post['book'];
			$balance['cheque'] = $post['cheque'];			
			$balance['date'] = $post['bdate'];
			$balance['amount'] = $post['paid_amount']; 				
			$post['due_amount'] = (float)$post['due_amount'] - (float)$post['paid_amount'];
			if($post['due_amount'])
				$balance['description'] = 'Part Payment Received of Invoice No:';
			else
				$balance['description'] = 'Full Payment Received of Invoice No:';
				
				
			$post['part'] = 0;
		}
		else
		{
			$post['paid_amount'] =0;
		}
		if(isset($post['shipping_charge']) && $post['shipping_charge'] !="" && is_numeric($post['shipping_charge']))
		{
			$post['due_amount'] += (float)$post['shipping_charge'];
			$post['final_amount'] += (float)$post['shipping_charge'];
		}
		
		$charge = 0;
		$baseDueAmounts = $post['due_amount'];
		
		if(isset($post['less_percent']) && $post['less_percent'] !='')
		{	
			if($post['less_percent'] < 0)
				$baseDueAmounts -= (float)abs($post['less_amount']);
			else
				$baseDueAmounts += (float)abs($post['less_amount']);
		}
		if(isset($post['other_less_percent']) && $post['other_less_percent'] !='')
		{
			if($post['other_less_percent'] < 0)
				$baseDueAmounts -= (float)abs($post['other_less_amount']);
			else
				$baseDueAmounts += (float)abs($post['other_less_amount']);
		}
		if(isset($post['charge']) && $post['charge'] != '')
			$charge = (float)$post['charge'];
	
		$baseDueAmounts -= (float)$charge;
		$post['due_amount'] = $baseDueAmounts;
		
		unset($post['book']);
		unset($post['currency']);		
		unset($post['products']);
		unset($post['bdate']);
		unset($post['cheque']);
		
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
		$invoice++;
		$sql = "UPDATE dai_incrementid  SET outward='$setNewid',invoice='$invoice' WHERE company=".$_SESSION['companyId'];		
		$rs = mysql_query($sql);
		
		if(isset($post['on_payment']) && $type=="sale")
		{
			$invoice--;
			$balance['party'] = $post['party']; 
			//$balance['date'] = $post['bdate']; 
			$balance['type'] = 'cr'; 
			$balance['company'] = $_SESSION['companyId']; 
			
			$balance['description'] = $balance['description'].$invoice; 
			$pData = $this->getPartyByName('PAYMENT');
			if(!empty($pData))
			{
				$balance['under_group'] = $pData['under_group']; 
				$balance['under_subgroup'] = $pData['under_subgroup'];
			}
			$balance['sale_id'] = $Oid;				
			
			$bal = $this->getBalance($balance['book']);
			$book ="";
			$bal = (float)$bal + (float)$balance['amount'];
			$balance['balance'] = $bal;
			if(isset($balance['description']))
			{
				$balance['description'] = strtoupper($balance['description']);
			}
			$data = $this->helper->getInsertString($balance);	
			$sql = "INSERT INTO acc_transaction (". $data[0].") VALUES (".$data[1].")";		
			
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
				return $rs;
			}
			else
			{
				$book = "balance = ".$bal;					
				$sql = "UPDATE dai_book SET ".$book." WHERE id=".$balance['book'];					
				$rs = mysql_query($sql);
				
			}
		}
		if(!$rs)
		{
			$rs = mysql_error();
			return $rs;
		}
		else
		{
			$rs = $Oid;				
		}
		return $rs;
	}
	public function getPartyByName($name)
	{
		$rs = mysql_query("SELECT * FROM dai_party WHERE name like '".$name."'" );
			
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data = $row;
			break;
		}
		return $data;
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
	public function separateSale($v,$edata,$type,$post)
	{
		$prid = "";
		//$edata = $this->getProductDetail($pid);
		$child = $edata['child_count'] + 1;
		
		$edata['child_count'] = $child;
		
		$v['sku'] = $edata['sku'].'-'.$child;
			
		$tc = $tp = 0;
		$skus[] = $v['sku'];			
		$tp += (float)$v['polish_pcs'];
		$tc += (float)$v['polish_carat'];
		
		
			
		if($edata['group_type']  =='box' )
			$edata['polish_pcs']  -= $v['polish_pcs'];
		
		$edata['polish_carat']  -= $v['polish_carat'];
		

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
		$v['outward_parent'] = $edata['id'];
		$v['main_color'] = $edata['main_color'];
		$v['location'] = $edata['location'];
		$v['remark'] = $edata['remark'];
		$v['transaction'] = 'dr';
		
		$v['outward'] = $type;
		
		if($type == 'sale' || $type == 'export')
			$v['visibility'] = 0;
		else
			$v['visibility'] = 1;
		
		$v['attr'] = $edata['record'];
		if(isset($v['sell_price']))
		{
			$v['price'] = $v['sell_price'];
			$v['amount'] = $v['sell_amount'];
		}
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
			$history['description'] = $type." with Unboxing from ".$edata['sku']." with  carat :".$v['polish_carat'];
			$history['pcs'] = $v['polish_pcs'];
			$history['carat'] = $v['polish_carat'];
			$history['amount'] = $v['amount'];
			$history['price'] = $v['price'];
			$history['sku'] = $v['sku'];
			$history['type'] = 'cr';
			$history['invoice'] = $post['invoiceno'];
			$history['entry_from'] = 'outward';
			
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
			$history['product_id'] =$edata['id'];
			$history['action'] = $type;
			$history['party'] = $post['party'];		
			$history['narretion'] = $post['narretion'];
			$history['date'] = $post['invoicedate'];
			$history['description'] = $type." with carat :".$tc;
			$history['pcs'] = $v['polish_pcs'];
			$history['carat'] = $v['polish_carat'];
			$history['amount'] = $v['amount'];
			$history['price'] = $v['price'];
			$history['sku'] = $edata['sku'];
			$history['type'] = 'dr';
			$history['invoice'] = $post['invoiceno'];
			$history['entry_from'] = 'outward';			
			$rs = $this->helper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}	
			return $prid;
		}			
	}
	public function getBalance($book)
	{
		$rs = mysql_query("SELECT * FROM dai_book WHERE id=".$book);
			
		$data = "0";
		while($row = mysql_fetch_assoc($rs))
		{
			$data = $row['balance'];
		}
		return $data;
	}
	public function getIncrementEntry($of)
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
	public function getRecordData($id)
    {
		$rs = mysql_query("SELECT * FROM ".$this->table_product ." WHERE  entryno= '".$id."'" );
		
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data[] = $row;
		}
	
		if(empty($data))
		{
			$field = mysql_num_fields( $rs );   
			for ( $i = 0; $i < $field; $i++ ) {		   
				$data[mysql_field_name( $rs, $i )] = "";		   
			}
		}	
		return  $data;			
    }
	public function closeMemo($id)
	{
		$sql = "UPDATE ".$this->table ." SET status='close_memo' WHERE id=".$id;		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
			break;
		}
		else
		{
			$data = $this->getData($id);
			foreach(explode(",",$data['products']) as $k=>$pid)
			{
				$sql = "UPDATE ".$this->table_product." SET outward='',site_upload=0,rapnet_upload=0 WHERE  outward='memo' and id=".$pid;		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					break;
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'close_memo';
				$history['party'] = $data['party'];		
				$history['narretion'] = $data['narretion'];
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Memo Closed of Entry no:".$data['entryno']." with invoice no is ".$data['invoiceno'];
				$rs = $this->helper->addHistory($history);
				if(!$rs)
						break;
			}
		}	
			return $rs;
	}
	
	public function closeGia($id)
	{
		$sql = "UPDATE ".$this->table ." SET status='close_lab' WHERE id=".$id;		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
			break;
		}
		else
		{
			$data = $this->getData($id);
			foreach(explode(",",$data['products']) as $k=>$pid)
			{
				$sql = "UPDATE ".$this->table_product." SET outward='',site_upload=0,rapnet_upload=0 WHERE  outward='lab' and id=".$pid;		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					break;
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'close_lab';
				$history['party'] = $data['party'];		
				$history['narretion'] = $data['narretion'];
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Lab Closed of Entry no:".$data['entryno'];
				$rs = $this->helper->addHistory($history);
				if(!$rs)
						break;
			}
		}	
			return $rs;
	}
	
	public function returnMemo($post)
	{	
		$rs = '';
		try{
			$mdata = $this->getData($post['id']);
			$products = $post['products'];
			$returnProduct = array();
			if($mdata['return_products'] != '')
			{	
				$returnProduct = explode(",",$mdata['return_products']);
			}
			$mp = array();
			foreach(explode(",",$mdata['products']) as $k=>$pid)
			{
				if(!in_array($pid,$products ))
				{
					$mp[] = $pid;
					continue;
				}
				
				$pdata = $this->getProductDetail($pid);
				if($pdata['outward_parent']!="" && $pdata['outward_parent']!=0)
				{
					$edata = $this->getProductDetail($pdata['outward_parent']);
					
					if($edata['group_type'] == "box")
						$edata['polish_pcs']  += $pdata['polish_pcs'];
					
					$edata['polish_carat']  += $pdata['polish_carat'];
					
					$edata['outward'] = "";
					unset($edata['record']);
					$data = $this->helper->getUpdateString($edata);	
					
					$sql = "UPDATE dai_product SET ".$data." WHERE id=".$edata['id'];		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();				
					}
					
					$history =array();
					$history['product_id'] =$edata['id'];
					$history['action'] = 'memo_return';
					$history['party'] = $mdata['party'];		
					$history['narretion'] = $mdata['narretion'];
					$history['date'] = date("Y-m-d H:i:s");
					$history['description'] = "Stone Memo return with reference no is ".$mdata['reference'];
					$history['pcs'] = $pdata['polish_pcs'];
					$history['carat'] = $pdata['polish_carat'];
					$history['amount'] = ($pdata['sell_amount'] =='' || $pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] =='' || $pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'] ;
					$history['sku'] = $edata['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $mdata['invoiceno'];
					$history['entry_from'] = 'outward';
					$history['entryno'] = $mdata['id'];							
					$rs = $this->helper->addHistory($history);
							
					
					$sql = "UPDATE ".$this->table_product." SET outward='',visibility=0,outward_parent=0 WHERE  id=".$pid;		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();
						break;
					}
					
				}				
				else
				{
				
					$sql = "UPDATE ".$this->table_product." SET outward='',site_upload=0,rapnet_upload=0 WHERE  outward='memo' and id=".$pid;		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();
						break;
					}
						
					$history =array();
					$history['product_id'] =$pid;
					$history['action'] = 'memo_return';
					$history['party'] = $mdata['party'];		
					$history['narretion'] = $mdata['narretion'];
					$history['date'] = date("Y-m-d H:i:s");
					$history['description'] = "Stone Memo return with reference no is ".$mdata['reference'];
					$history['pcs'] = $pdata['polish_pcs'];
					$history['carat'] = $pdata['polish_carat'];
					$history['amount'] = ($pdata['sell_amount'] =='' || $pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] =='' || $pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'] ;
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $mdata['invoiceno'];
					$history['entry_from'] = 'outward';
					$history['entryno'] = $mdata['id'];							
					$rs = $this->helper->addHistory($history);	
				}
				$returnProduct[] = $pid;
			}
		
			if($rs)
			{	
				$returnProduct	= array_unique($returnProduct);
				if(empty($mp))
					$sql = "UPDATE ".$this->table." SET products='',status='close_memo',return_products='".implode(",",$returnProduct)."' WHERE  id=".$post['id'];		
				else
					$sql = "UPDATE ".$this->table." SET products='".implode(",",$mp)."',return_products='".implode(",",$returnProduct)."' WHERE  id=".$post['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					break;
				}
			}
		}
		catch(Exception $e)
		{
			$rs = $e->getMessage();	
		}		
	
		return $rs;
	}
	
	public function saveGia($post)
	{
		$rs = 0;
		
		$outid = $post['outid'];
		$oData = $this->getData($outid);
		$oProducts = explode(",",$oData['products']);
		
		foreach($post['record'] as $k=>$v)
		{
			if(($key = array_search($k, $oProducts)) !== false) {
				unset($oProducts[$key]);
			}
			$ProductData = $this->getProductDetail($k);
			if($v['report'] =='')
			{
				$sql = "UPDATE ".$this->table_product." SET outward='',visibility=1 WHERE id=".$k;		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
				else
				{
					$history =array();
					$history['product_id'] = $k;
					$history['action'] = 'lab_return';
					$history['date'] = date("Y-m-d H:i:s");
					$history['description'] = "Lab Return with No Certificate:";
					$history['type'] = 'cr';
					$history['pcs'] = $ProductData['polish_pcs'];
					$history['carat'] = $ProductData['polish_carat'];
					$history['price'] = $ProductData['price'];
					$history['amount'] = $ProductData['amount'];
					$rs = $this->helper->addHistory($history);
				}
				
				continue;
			}
			$gData = $this->helper->getGiaReport(trim($v['report']));
			
			if($gData['message']!="")
			{
				return $gData['message'] ;
			}	
			
			$color = $gData['color'];
			$pcarat = ($gData['weight'] !='' & $gData['weight'] !=0 ) ? $gData['weight'] : $ProductData['polish_carat'];
			$amount = $pcarat * $ProductData['price'];
			$sql = "UPDATE ".$this->table_product." SET lab='GIA',sku='".$v['sku']."',mfg_code='".$v['mfg_code']."',main_color='$color',polish_carat='$pcarat', amount='$amount',outward='',site_upload=0,rapnet_upload=0,is_uploadsite=1,is_uploadrapnet=1,visibility=1 WHERE id=".$k;		
			$rs = mysql_query($sql);
			if(!$rs)
			{
				return mysql_error();				
			}
			else
			{
				$history =array();
				$history['product_id'] = $k;
				$history['action'] = 'lab_return';
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Lab Return With Certificate no:".$gData['report_no'];
				$history['type'] = 'cr';
				$history['pcs'] = $ProductData['polish_pcs'];
				$history['carat'] = $pcarat;
				$history['amount'] = $amount;
				$history['price'] = $ProductData['price'];
				$history['invoice'] = $oData['invoiceno'];
				$history['party'] = $oData['party'];
				
				$rs = $this->helper->addHistory($history);				
				if(!is_numeric($rs) && $rs!=1)
				{
					return mysql_error();	
				}
						
				$attr = $this->helper->getAttributeField();
				$data = array();
				foreach($attr['record'] as $ak=>$av)
				{
					if($ak == 'size' || $ak == 'color')
						continue;
						
					if(isset($gData[$ak]))	
						$data[$ak] = $gData[$ak];
					
				}
				$data['intensity'] = $v['intensity'];
				$data['overtone'] = $v['overtone'];
				$data['color'] = $v['color'];
				$value = $this->helper->getUpdateString($data);	
				$sql = "UPDATE ".$this->table_product_value." SET ".$value." WHERE product_id=".$k;		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
			}
		}	
		if(empty($oProducts))
			$sql = "UPDATE ".$this->table." SET products='',status='close_lab' WHERE  id=".$outid;		
		else
			$sql = "UPDATE ".$this->table." SET products='".implode(",",$oProducts)."' WHERE  id=".$outid;		
		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
			break;
		}
		return $rs;
	}
	
	public function hold($post)
	{
		$status = $post['status'];
		foreach($post['ids'] as $k=>$v)
		{
			$sql = "UPDATE ".$this->table_product." SET hold=".$status.",site_upload=0,rapnet_upload=0 WHERE id=".$v;		
			
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
				return $rs;
			}
			
			$history =array();
			$history['product_id'] = $v;
			$history['action'] = ($status)?'hold':'unhold';						
			$history['date'] = date("Y-m-d H:i:s");
			$history['description'] = ($status)?"Stone put on Hold for while.":"Stone put unhold and show in inventory.";
			$rs = $this->helper->addHistory($history);
		}			
		return $rs;
	}
	
	public function saleMemo($post)
	{	
		$rs = '';
		try{
			$data = $this->getData($post['id']);
			$products = explode(',',$post['products']);
			$mp = array();
			$sp = array();
			foreach(explode(",",$data['products']) as $k=>$pid)
			{
				if(!in_array($pid,$products ))
				{
					$mp[] = $pid;
					continue;
				}
				$sql = "UPDATE ".$this->table_product." SET outward='sale',site_upload=0,rapnet_upload=0 WHERE  outward='memo' and id=".$pid;		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
				$sp[] = $pid;				
			}
		
			if($rs)
			{	
				if(empty($mp))
					$sql = "UPDATE ".$this->table." SET products='',status='close_memo' WHERE  id=".$post['id'];		
				else
					$sql = "UPDATE ".$this->table." SET products='".implode(",",$mp)."' WHERE  id=".$post['id'];		
				
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
				$incre_id = $this->getIncrementEntry('outward');
				$invoice = $this->getIncrementEntry('invoice');
				$cid = $_SESSION['companyId'];
				
				$data['entryno'] = $incre_id;	
				$data['invoiceno'] = $invoice;
				$data['date'] =  date("Y-m-d H:i:s");
				$data['products'] =  implode(",",$sp);
				$data['status'] =  'on_sale';
				$data['type'] =  'sale';
				$data['invoicedate'] =  $post['invoicedate'];
				$data['terms'] =  $post['terms'];
				$data['duedate'] =  $post['duedate'];
				$balance = array();
				unset($data['record']);
				$Oid  = 0;
				if(isset($post['on_payment']))
				{
					$balance['book'] = $post['book']; 
					$balance['date'] = $post['bdate'];
					$balance['amount'] = $post['paid_amount'];
					$data['paid_amount'] = $post['paid_amount'];
					$data['due_amount'] = (float)$post['due_amount'] - (float)$post['paid_amount'];
					if($data['due_amount'] > 0)
						$balance['description'] = 'Part Payment Received of Invoice No:';
					else
						$balance['description'] = 'Full Payment Received of Invoice No:';
					
				}
				else
				{
					$data['paid_amount'] =0;
					$data['due_amount'] = (float)$post['due_amount'];
					$data['final_amount'] = $data['due_amount'];
				}			
								
				$data1 = $this->helper->getInsertString($data);	
				$sql = "INSERT INTO ".$this->table ." (". $data1[0].") VALUES (".$data1[1].")";		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
				else
				{
					$Oid =  mysql_insert_id();
					$temp = explode("-",$incre_id);
					$temp[1] = $temp[1] + 1;
					$setNewid = $temp[0]."-".$temp[1];
					$invoice++;
					$sql = "UPDATE dai_incrementid  SET outward='$setNewid',invoice='$invoice' WHERE company=".$_SESSION['companyId'];		
					$rs = mysql_query($sql);
				}
				$data = $this->getData($Oid);
				foreach(explode(",",$data['products']) as $k=>$pid)
				{
				
				$history =array();
				$history['product_id'] =$pid;
				$history['action'] = 'sale';
				$history['party'] = $data['party'];		
				$history['narretion'] = $data['narretion'];
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Stone Sale from Memo, New Entry No :".$data['entryno']." invoice No : ".$data['invoiceno'];
				/*$history['pcs'] = $pdata['polish_pcs'];
				$history['carat'] = $pdata['polish_carat'];
				$history['amount'] = $pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];*/
				$history['type'] = 'dr';
				$history['invoice'] = $data['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $data['id'];							
				$rs = $this->helper->addHistory($history);
					
				if(!$rs)
					return $rs;
				}
				
				if(isset($post['on_payment']))
				{
					$invoice--;
					$balance['party'] = $data['party']; 
					//$balance['date'] = $post['bdate']; 
					$balance['type'] = 'cr'; 
					$balance['company'] = $_SESSION['companyId']; 
					
					$balance['description'] = $balance['description'].$invoice; 
					$pData = $this->getParty($data['party']);
					$balance['under_group'] = $pData['under_group']; 
					$balance['under_subgroup'] = $pData['under_subgroup'];
					$balance['sale_id'] = $Oid;				
					
					$bal = $this->getBalance($balance['book']);
					$book ="";
					$bal = (float)$bal + (float)$balance['amount'];
					$balance['balance'] = $bal;
					
					$data1 = $this->helper->getInsertString($balance);	
					$sql = "INSERT INTO acc_transaction (". $data1[0].") VALUES (".$data1[1].")";		
					
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();
						return $rs;
					}
					else
					{
						$book = "balance = ".$bal;					
						$sql = "UPDATE dai_book SET ".$book." WHERE id=".$balance['book'];					
						$rs = mysql_query($sql);
						
					}
				}
			}
		}
		catch(Exception $e)
		{
			$rs = $e->getMessage();	
		}		
	
		return $rs;
	}
	public function getAmount($id)
	{
		$rs = mysql_query("SELECT amount FROM dai_product WHERE id=".$id);
			
		$data = "0";
		while($row = mysql_fetch_assoc($rs))
		{
			$data = $row['amount'];
		}
		return $data;
	}
	public function updateOutward($post)
	{
		$saleProducts = explode(",",$post['sproducts']);
		$post['date'] = date("Y-m-d H:i:s");
		$rs = 0;	
		$products = $post['products'];		
		$otype = $post['type'];
		unset($post['type']);
		//$post['products'] = implode(",",$products);
		/*print_r($products);
		print_r($saleProducts );
		print_r($post['record']);
	//	
		exit;
		*/
		unset($post['sproducts']);
		$record = $post['record'];
		unset($post['record']);
		// check product deleted from outward than put in stock inventory
		foreach($saleProducts as $k=>$pid)
		{
			if(in_array($pid,$products))
				continue;
			
			$pdata = $this->getProductDetail($pid);
		
			
			if($pdata['outward_parent']!="" && $pdata['outward_parent']!=0)
			{
				$edata = $this->getProductDetail($pdata['outward_parent']);
				
				if($edata['group_type'] == "box")
					$edata['polish_pcs']  += $pdata['polish_pcs'];
				
				$edata['polish_carat']  += $pdata['polish_carat'];
				
				$edata['outward'] = "";
				unset($edata['record']);
				$data = $this->helper->getUpdateString($edata);	
				
				$sql = "UPDATE dai_product SET ".$data." WHERE id=".$edata['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					return mysql_error();				
				}
			
				$history =array();
				$history['product_id'] = $edata['id'];
				$history['action'] = $otype.'_return';
				$history['party'] = $post['party'];		
				$history['narretion'] = $post['narretion'];
				$history['date'] = date("Y-m-d H:i:s");;
				$history['description'] = "Stone ".$otype." return with reference no is ".$post['reference'];
				$history['pcs'] = $pdata['polish_pcs'];
				$history['carat'] = $pdata['polish_carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $post['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $post['id'];							
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				$sql = "UPDATE ".$this->table_product." SET outward='',visibility=0,outward_parent=0 WHERE  id=".$pid;		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					return mysql_error();
				}				
			}
			else
			{
				$sql = "UPDATE ".$this->table_product." SET outward='',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
				
				$rs = mysql_query($sql);
				if(!$rs)
				{
					return mysql_error();				
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = $otype.'_return';
				$history['party'] = $post['party'];		
				$history['narretion'] = $post['narretion'];
				$history['date'] = date("Y-m-d H:i:s");;
				$history['description'] = "Stone ".$otype." return with reference no is ".$post['reference'];
				$history['pcs'] = $pdata['polish_pcs'];
				$history['carat'] = $pdata['polish_carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $post['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $post['id'];							
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}				
			}
		}
		
		
		$amount = 0.0;
		$temp = array();
		foreach($record as $k=>$pdata)
		{
			if($pdata['sku'] == "" && $pdata['polish_carat'] == "")
				continue;
			
			$Tpcs = 0;
			$Tcarat = 0.0;	
			$pid  = $pdata['id'];
			
			$amount += (float)($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				
			$BaseData = array();
			if(in_array($pid,$saleProducts))
			{
				$oldData =  $this->getProductDetail($pid);
				if($oldData['outward_parent']!="" && $oldData['outward_parent']!=0)
				{
					$BaseData =  $this->getProductDetail($oldData['outward_parent']);					
					if($BaseData['group_type'] =='box')
					{
						$Tpcs = $oldData['polish_pcs'] - $pdata['polish_pcs'];
						$BaseData['polish_pcs'] += $Tpcs;						
					}
					$Tcarat = $oldData['polish_carat'] - $pdata['polish_carat'];
					$BaseData['polish_carat'] += $Tcarat;				
				}
			}
			else
			{
				$oldData =  $this->getProductDetail($pid);
				if($oldData['group_type']=="box" || $oldData['group_type']=="parcel")
				{
					$BaseData =  $this->getProductDetail($pid);
					$rid= $this->separateSale($pdata,$BaseData,$otype,$post);
					
					if(is_numeric($rid))
					{
						$products[] =  $rid;
						unset($products[$pid]);
						if(($key = array_search($pid, $products)) !== false) {
							unset($products[$key]);
						}
						$temp = $pdata;
						$pdata = array();
						$BaseData = array();
					}
					else
					{				
						return $rid;
					}
					/*
					if($BaseData['group_type'] =='box')
						 $BaseData['polish_pcs'] += ($oldData['polish_pcs'] - $pdata['polish_pcs']);
						
					$BaseData['polish_carat'] += ($oldData['polish_carat'] - $pdata['polish_carat']);					*/
				}
			}	
			
			if(!empty($pdata))
			{	
				//$attr = $pdata['attr'];
				//unset($pdata['attr']);
				$pdata['outward'] = $otype;
				$pdata['site_upload'] = 0;
				$pdata['rapnet_upload'] = 0;
				
				//$sql = "UPDATE ".$this->table_product." SET outward='sale',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
				$data = $this->helper->getUpdateString($pdata);		
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;
				$rs = mysql_query($sql);				
				if(!$rs)
				{
					return mysql_error();
				}
				
				$history = array();
				$history['product_id'] = $pid;				
				$history['action'] = $otype;
				$history['type'] = 'dr';
				$history['description'] = "Stone updated";									
				$history['party'] = $post['party'];		
				$history['narretion'] = $post['narretion'];
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = $pdata['polish_pcs'];
				$history['carat'] = $pdata['polish_carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $pdata['sku'];				
				$history['invoice'] = $post['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $post['id'];							
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
			}
			else
			{
				 $pdata  = $temp;
			}
			if(!empty($BaseData))
			{
				unset($BaseData['record']);
				$data = $this->helper->getUpdateString($BaseData);		
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$BaseData['id'];
				$rs = mysql_query($sql);
				
				if(!$rs)
				{
					return mysql_error();
				}
				if(!abs($Tcarat) == 0)
				{
				
				$history = array();
				$history['product_id'] = $BaseData['id'];
				if($Tcarat < 0):
					$history['action'] = $otype;
					$history['type'] = 'dr';
					$history['description'] = "Stone ".$otype." with reference no is ".$post['reference'];	
				elseif($Tcarat > 0):
					$history['action'] = $otype.'_return';
					$history['type'] = 'cr';
					$history['description'] = "Stone ".$otype." return with reference no is ".$post['reference'];
				else:
					$history['action'] = $otype;
					$history['type'] = 'cr';
					$history['description'] = "Stone detail updated";
				endif;				
				$history['party'] = $post['party'];		
				$history['narretion'] = $post['narretion'];
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = abs($Tpcs);
				$history['carat'] = abs($Tcarat);
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $BaseData['sku'];				
				$history['invoice'] = $post['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $post['id'];							
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				
				}
			}				
				
		}
		
		if(isset($post['shipping_charge']) && $post['shipping_charge'] != '' )
		{
			$amount += (float)$post['shipping_charge'];
		}
		
		$post['final_amount'] = $amount;
		$post['due_amount'] = (float) $amount - (float)$post['paid_amount'];
		
		$post['products'] = implode(",",$products);
		
		if(empty($products))
			$post['status'] = "close_".$otype; 
		
		$data = $this->helper->getUpdateString($post);		
		$sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$post['id'];
		$rs = mysql_query($sql);
		if(!$rs)
		{
			return mysql_error();
		}
		return $rs;
	}
	
	public function getDataBySku($sku)
    {
		$data = array();
		//echo "SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE p.sku='".$sku."' and outward=''";	
		$rs = mysql_query("SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE p.sku='".$sku."' and outward=''" );
		while($row = mysql_fetch_assoc($rs))
		{
			$data = $row;
			break;
		}
		
		return  $data;			
    }
	
	public function deleteSale($post)
    {
		$id = $post['id'];
		$rs=0;
		$Odata = $this->getData($id);
		
		$otype = $Odata['type'];
		foreach($Odata['record'] as $k=>$pdata)
		{
			
			$pid  = $pdata['id'];			
			$BaseData = array();
			
			if($pdata['outward_parent']!="" && $pdata['outward_parent']!=0)
			{
				$BaseData =  $this->getProductDetail($pdata['outward_parent']);					
				if($BaseData['group_type'] =='box')
					 $BaseData['polish_pcs'] += $pdata['polish_pcs'];
					
				$BaseData['polish_carat'] += $pdata['polish_carat'];				
			}
			
			if(!empty($pdata))
			{	
				if($pdata['outward_parent']!="" && $pdata['outward_parent']!=0)
				{
					$tdata['visibility'] = 0;
					$tdata['outward_parent'] = 0;
				}
				$tdata['outward'] = '';
				
				$tdata['site_upload'] = 0;
				
				$tdata['rapnet_upload'] = 0;
			
				
				//$sql = "UPDATE ".$this->table_product." SET outward='sale',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
				$data = $this->helper->getUpdateString($tdata);		
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;
				$rs = mysql_query($sql);
				
				if(!$rs)
				{
					return mysql_error();
				}
				
				$history = array();
				$history['product_id'] = $pid;				
				$history['action'] = $otype."_close";
				$history['type'] = 'cr';
				$history['description'] = $otype." Closing / deleting  ";							
				$history['party'] = $Odata['party'];		
				$history['narretion'] = $Odata['narretion'];
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = $pdata['polish_pcs'];
				$history['carat'] = $pdata['polish_carat'];
				$history['amount'] = ($pdata['sell_amount'] == 0 || $pdata['sell_amount'] == '') ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] == 0 || $pdata['sell_price'] == '') ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $pdata['sku'];				
				$history['invoice'] = $Odata['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $Odata['id'];							
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
			}
			
			if(!empty($BaseData))
			{
				unset($BaseData['record']);
				$data = $this->helper->getUpdateString($BaseData);		
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$BaseData['id'];
				$rs = mysql_query($sql);
				
				if(!$rs)
				{
					return mysql_error();
				}				
				$history = array();
				$history['product_id'] = $BaseData['id'];				
				$history['action'] = $otype."_close";
				$history['type'] = 'cr';
				$history['description'] = $otype." Closing / deleting  ";							
				$history['party'] = $Odata['party'];		
				$history['narretion'] = $Odata['narretion'];
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = $pdata['polish_pcs'];
				$history['carat'] = $pdata['polish_carat'];
				$history['amount'] = ($pdata['sell_amount'] == 0 || $pdata['sell_amount'] == '') ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] == 0 || $pdata['sell_price'] == '') ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $BaseData['sku'];				
				$history['invoice'] = $Odata['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $Odata['id'];							
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
			}
		}
		$close = "close_".$otype;
		$sql = "UPDATE ".$this->table." SET status='".$close."' WHERE id=".$id;
		$rs = mysql_query($sql);
		
		if(!$rs)
		{
			return mysql_error();
		}
		return $rs;
    }
	public function getInwardData($id)
    {
		$rs = mysql_query("SELECT * FROM dai_inward WHERE company=".$_SESSION['companyId']." and id=".$id );
			
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data = $row;
		}			
		return  $data;			
    }
	public function saveCharge($post)
	{
		$id  = $post['sale_id'];
		$data =array();
		
		if($post['type'] == 'outward')
			$data =	$this->getData($id);
		else	
			$data =	$this->getInwardData($id);
		
		$baseDueAmount = $data['due_amount'];
				
		if($data['less_percent'] < 0 )
			$baseDueAmount += (float)$data['less_amount'];
		else
			$baseDueAmount -= (float)$data['less_amount'];
		
		
		if($data['other_less_percent'] < 0 )
			$baseDueAmount += (float)$data['other_less_amount'];
		else
			$baseDueAmount -= (float)$data['other_less_amount'];
		
				
		$baseDueAmount += (float)$data['charge'];
		
		
		if($post['less_percent'] < 0 )
			$baseDueAmount -= (float)abs($post['less_amount']);
		else
			$baseDueAmount += (float)abs($post['less_amount']);
		
		if($post['other_less_percent'] < 0 )
			$baseDueAmount -= (float)abs($post['other_less_amount']);
		else
			$baseDueAmount += (float)abs($post['other_less_amount']);
		
		
		if($post['charge'] != '')
			$charge = (float)$post['charge'];
	
		$baseDueAmount -= (float)$charge;
		
	
		$save['due_amount'] = $baseDueAmount;
		$save['less_percent'] = $post['less_percent'];
		$save['less_amount'] = abs($post['less_amount']);
		$save['charge'] = $charge;
		$save['other_less_percent'] = $post['other_less_percent'];
		$save['other_less_amount'] = abs($post['other_less_amount']);
		
		$data1 = $this->helper->getUpdateString($save);	
		
		if($post['type'] == 'outward')
			$sql = "UPDATE ".$this->table." SET ".$data1." WHERE id=".$id;
		else
			$sql = "UPDATE dai_inward SET ".$data1." WHERE id=".$id;
		$rs = mysql_query($sql);		
		if(!$rs)
		{
			return mysql_error();
		}
		return $rs;		
	}
	
	
	public function memoTo($post)
	{
		
		$memoId = $post['memo_id'];
		unset($post['memo_id']);
		$type = $post['type'];
		
		$post['date'] = date("Y-m-d H:i:s");
		$incre_id = $this->getIncrementEntry('outward');
		$invoice = $this->getIncrementEntry('invoice');
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$post['entryno'] = $incre_id;	
		$post['invoiceno'] = $invoice;			
		$products = $post['products'];
		
		$record = array();
		if(isset($post['record']))
		{
			$record = $post['record'];
			unset($post['record']);
		}
		$balance = array();
	
		//unset($post['on_payment']);		
		$post['products'] = implode(",",$products);
		
			
		$OutProducts = array();
		$amount = 0;
		//check price and amount are blank or greter than exiesting box / parcel for carat
		//if yes then return message to outward page
		foreach($products as $k=>$pid)
		{
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
		foreach($products as $k=>$pid)
		{
			$edata = $this->getProductDetail($pid);
			
			//check carat and pcs of outward data with exiesting data if both are diferent than create new products
			if(isset($record[$pid]) && $record[$pid]['polish_carat'] !="" && $record[$pid]['polish_carat'] != $edata['polish_carat'] )
			{
				if( $record[$pid]['price'] =="" || $record[$pid]['amount'] =="" )
					return "Please Enter price and amount of carat";
				
				//create seperation from box / parcel for outward	
				$rid = $this->separateMemoToSale($record[$pid],$edata,$type,$post);
				if(is_numeric($rid))
				{
					$OutProducts[] =  $rid;
					$amount += (float)$record[$pid]['amount']; 
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
				$OutProducts[] =  $pid;
				$sql = "UPDATE ".$this->table_product." SET outward='$type',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
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
				$history['description'] = " Stone ".$type."  with reference no is ".$post['reference'];
				$history['pcs'] = $pdata['polish_pcs'];
				$history['carat'] = $pdata['polish_carat'];
				$history['amount'] = ($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] == 0)?$pdata['price']:$pdata['sell_price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['invoice'] = $post['invoiceno'];
				
				$rs = $this->helper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				$amount += (float)($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount']; 		
			}		
		}
		
		$post['due_amount'] = $amount;
		$post['final_amount'] = $amount;
		if(isset($post['on_payment']) && $type=="sale")
		{
			$balance['book'] = $post['book'];
			$balance['cheque'] = $post['cheque'];			
			$balance['date'] = $post['bdate'];
			$balance['amount'] = $post['paid_amount']; 				
			$post['due_amount'] = (float)$post['due_amount'] - (float)$post['paid_amount'];
			if($post['due_amount'])
				$balance['description'] = 'Part Payment Received of Invoice No:';
			else
				$balance['description'] = 'Payment Received of Invoice No:';
				
				
			$post['part'] = 0;
		}
		else
		{
			$post['paid_amount'] =0;
		}
		if(isset($post['shipping_charge']) && $post['shipping_charge'] !="" && is_numeric($post['shipping_charge']))
		{
			$post['due_amount'] += (float)$post['shipping_charge'];
			$post['final_amount'] += (float)$post['shipping_charge'];
		}
		
		$charge = 0;
		$baseDueAmounts = $post['due_amount'];
		
		if(isset($post['less_percent']) && $post['less_percent'] !='')
		{	
			if($post['less_percent'] < 0)
				$baseDueAmounts -= (float)abs($post['less_amount']);
			else
				$baseDueAmounts += (float)abs($post['less_amount']);
		}
		if(isset($post['other_less_percent']) && $post['other_less_percent'] !='')
		{
			if($post['other_less_percent'] < 0)
				$baseDueAmounts -= (float)abs($post['other_less_amount']);
			else
				$baseDueAmounts += (float)abs($post['other_less_amount']);
		}
		if(isset($post['charge']) && $post['charge'] != '')
			$charge = (float)$post['charge'];
	
		$baseDueAmounts -= (float)$charge;
		$post['due_amount'] = $baseDueAmounts;
		
		unset($post['book']);
		unset($post['currency']);		
		unset($post['products']);
		unset($post['bdate']);
		unset($post['cheque']);
		
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
		$invoice++;
		$sql = "UPDATE dai_incrementid  SET outward='$setNewid',invoice='$invoice' WHERE company=".$_SESSION['companyId'];		
		$rs = mysql_query($sql);
		
		if(isset($post['on_payment']) && $type=="sale")
		{
			$invoice--;
			$balance['party'] = $post['party']; 
			//$balance['date'] = $post['bdate']; 
			$balance['type'] = 'cr'; 
			$balance['company'] = $_SESSION['companyId']; 
			
			$balance['description'] = $balance['description'].$invoice; 
			$pData = $this->getPartyByName('PAYMENT');
			if(!empty($pData))
			{
				$balance['under_group'] = $pData['under_group']; 
				$balance['under_subgroup'] = $pData['under_subgroup'];
			}
			$balance['sale_id'] = $Oid;				
			
			$bal = $this->getBalance($balance['book']);
			$book ="";
			$bal = (float)$bal + (float)$balance['amount'];
			$balance['balance'] = $bal;
			if(isset($balance['description']))
			{
				$balance['description'] = strtoupper($balance['description']);
			}
			$data = $this->helper->getInsertString($balance);	
			$sql = "INSERT INTO acc_transaction (". $data[0].") VALUES (".$data[1].")";		
			
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
				return $rs;
			}
			else
			{
				$book = "balance = ".$bal;					
				$sql = "UPDATE dai_book SET ".$book." WHERE id=".$balance['book'];					
				$rs = mysql_query($sql);				
			}
		}
		if(!$rs)
		{
			$rs = mysql_error();
			return $rs;
		}
		else
		{
			$rs = $Oid;				
		}
		
		$memoData = $this->getData($memoId);
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
			$sql = "UPDATE ".$this->table." SET products='',status='close_memo' WHERE  id=".$memoId;		
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
	
	public function separateMemoToSale($v,$edata,$type,$post)
	{
		$prid = "";
		if($edata['outward_parent'] != '')
			$maindata = $this->getProductDetail($edata['outward_parent']);
		else
			$maindata = $edata;
		
		$child = $edata['child_count'] + 1;
		
		$edata['child_count'] = $child;
		
		$v['sku'] = $maindata['sku'].'-'.$child;
			
		$tc = $tp = 0;
		$skus[] = $v['sku'];			
		$tp += (float)$v['polish_pcs'];
		$tc += (float)$v['polish_carat'];
		$fromcarat = $edata['polish_carat'];
		
			
		if($edata['group_type']  =='box' )
			$edata['polish_pcs']  -= $v['polish_pcs'];
		
		$edata['polish_carat']  -= $v['polish_carat'];
		

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
		$v['outward_parent'] = $maindata['id'];
		$v['transaction'] = 'dr';
		
		$v['outward'] = $type;
		
		if($type == 'sale' || $type == 'export')
			$v['visibility'] = 0;
		else
			$v['visibility'] = 1;
		
		$v['attr'] = $edata['record'];
		if(isset($v['sell_price']))
		{
			$v['price'] = $v['sell_price'];
			$v['amount'] = $v['sell_amount'];
		}
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
			$history['description'] = $type." with Unboxing from ".$edata['sku']." with  carat :".$v['polish_carat'];
			$history['pcs'] = $v['polish_pcs'];
			$history['carat'] = $v['polish_carat'];
			$history['amount'] = $v['amount'];
			$history['price'] = $v['price'];
			$history['sku'] = $v['sku'];
			$history['type'] = 'cr';
			$history['invoice'] = $post['invoiceno'];
			$history['entry_from'] = 'outward';
			
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
			$history['description'] = $type." from Memo carat :".$fromcarat;
			$history['pcs'] = $v['polish_pcs'];
			$history['carat'] = $v['polish_carat'];
			$history['amount'] = $v['amount'];
			$history['price'] = $v['price'];
			$history['sku'] = $edata['sku'];
			$history['type'] = 'dr';
			$history['invoice'] = $post['invoiceno'];
			$history['entry_from'] = 'outward';			
			$rs = $this->helper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}	
			return $prid;
		}			
	}
	
	public function memoToReturn($post)
	{
		$memoId = $post['memo_id'];
		unset($post['memo_id']);
		$memoData = $this->getData($memoId);
		$record = array();
		if(isset($post['record']))
		{
			$record = $post['record'];
			unset($post['record']);
		}
		$rs = 0;
		$OutProducts = array();
		$balance = array();
		$products = $post['products'];
		foreach($products as $k=>$pid)
		{
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
		foreach($products as $k=>$pid)
		{
			$edata = $this->getProductDetail($pid);
			
			//check carat and pcs of outward data with exiesting data if both are diferent than create new products
			if(isset($record[$pid]) && $record[$pid]['polish_carat'] !="" && $record[$pid]['polish_carat'] != $edata['polish_carat'] )
			{
				$parentdata = $this->getProductDetail($edata['outward_parent']);
				
				$parentdata['polish_carat'] += $record[$pid]['polish_carat'];
				$edata['polish_carat'] -= $record[$pid]['polish_carat'];
				
				if($edata['group_type'] == 'box')
					$edata['polish_pcs'] -=  $record[$pid]['polish_pcs'];
				
				if($parentdata['group_type'] == 'box')
					$parentdata['polish_pcs'] +=  $record[$pid]['polish_pcs'];
				
				$sql = "UPDATE ".$this->table_product." SET polish_pcs=".$parentdata['polish_pcs'].",polish_carat=".$parentdata['polish_carat']." WHERE id=".$parentdata['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
				
				$sql = "UPDATE ".$this->table_product." SET polish_pcs=".$edata['polish_pcs'].",polish_carat=".$edata['polish_carat']." WHERE id=".$edata['id'];		
				$rs = mysql_query($sql);
				if(!$rs)
				{
					$rs = mysql_error();
					return $rs;
				}
				
				$history =array();
				$history['product_id'] = $parentdata['id'];
				$history['action'] = 'memo_return';
				$history['party'] = $memoData['party'];		
				$history['narretion'] = $memoData['narretion'];
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Stone Memo return with reference no is ".$memoData['reference'];
				$history['pcs'] =$record[$pid]['polish_pcs'];
				$history['carat'] = $record[$pid]['polish_carat'];
				$history['amount'] = ($edata['sell_amount'] =='' || $edata['sell_amount'] ==0) ? $edata['amount'] : $edata['sell_amount'];
				$history['price'] = ($edata['sell_price'] =='' || $edata['sell_price'] ==0) ? $edata['price'] : $edata['sell_price'] ;
				$history['sku'] = $edata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $memoData['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $memoData['id'];							
				$rs = $this->helper->addHistory($history);
			}
			else
			{
				
				// if both are equal than just update to outward type
				$pdata = $this->getProductDetail($pid);
				
				if($pdata['outward_parent'] !='' && $pdata['outward_parent'] !=0)
				{
					$OutProducts[] =  $pid;
					$parentdata = $this->getProductDetail($pdata['outward_parent']);
					
					if($parentdata['group_type'] == 'box')
						$parentdata['polish_pcs'] +=  $pdata['polish_pcs'];
				
					$parentdata['polish_carat'] +=  $pdata['polish_carat'];
					
					$sql = "UPDATE ".$this->table_product." SET outward='',visibility=0 WHERE id=".$pid;		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();
						return $rs;
					}
					
					$sql = "UPDATE ".$this->table_product." SET polish_pcs=".$parentdata['polish_pcs'].",polish_carat=".$parentdata['polish_carat']." WHERE id=".$parentdata['id'];		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();
						return $rs;
					}
					
					$history =array();
					$history['product_id'] = $parentdata['id'];
					$history['action'] = 'memo_return';
					$history['party'] = $memoData['party'];		
					$history['narretion'] = $memoData['narretion'];
					$history['date'] = date("Y-m-d H:i:s");
					$history['description'] = "Memo return with reference no is ".$memoData['reference'];
					$history['pcs'] = $pdata['polish_pcs'];
					$history['carat'] = $pdata['polish_carat'];
					$history['amount'] = ($pdata['sell_amount'] =='' || $pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] =='' || $pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'] ;
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $memoData['invoiceno'];
					$history['entry_from'] = 'outward';
					$history['entryno'] = $memoData['id'];							
					$rs = $this->helper->addHistory($history);
					
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
				}
				else
				{
					$OutProducts[] =  $pid;
					$sql = "UPDATE ".$this->table_product." SET outward='',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();
						return $rs;
					}
					
					$history =array();
					$history['product_id'] =$pid;
					$history['action'] = 'memo_return';
					$history['party'] = $memoData['party'];		
					$history['narretion'] = $memoData['narretion'];
					$history['date'] = date("Y-m-d H:i:s");
					$history['description'] = "Stone Memo return with reference no is ".$memoData['reference'];
					$history['pcs'] = $pdata['polish_pcs'];
					$history['carat'] = $pdata['polish_carat'];
					$history['amount'] = ($pdata['sell_amount'] =='' || $pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] =='' || $pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'] ;
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $memoData['invoiceno'];
					$history['entry_from'] = 'outward';
					$history['entryno'] = $memoData['id'];							
					$rs = $this->helper->addHistory($history);
					
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
				}					
			}		
		}
		
		foreach(explode(",",$memoData['products']) as $k=>$pid)
		{
			if(!in_array($pid,$OutProducts))
				{
					$mp[] = $pid;
					continue;
				}
		}
		if(empty($mp))
			$sql = "UPDATE ".$this->table." SET products='',status='close_memo' WHERE  id=".$memoId;		
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
	
	public function chat($post)
	{
		
		$sdate = date("Y-m-d H:i:s");
		$post['date'] = $sdate;
		$post['new'] = 1;
		$url = $post['urlpath'];
		unset($post['urlpath']);
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO chat_history (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			return 'no';			
		}
		$current_user = $this->helper->getUserDetail($post['sender']);
		$cimage = ($current_user['profile_image'] != '' ) ? $current_user['profile_image'] :'default.png' ;
		$message = '<div class="direct-chat-msg right">
		<div class="direct-chat-info clearfix">
		<span class="direct-chat-name pull-right">';
		$message .= $current_user['first_name'];
		$message .=	'</span><span class="direct-chat-timestamp pull-left">';	
		$message .= $this->helper->getDateDifferenece($sdate);
		$message .=	'</span> </div>	'; 
		//$message .=	 'jeet';
		$message .= '<img class="direct-chat-img" src="'.$url.$cimage.'" >';
		 if($post['attachement'] != ''): 			
			 $message .=	 '<div class="direct-chat-text"> <a href="'. $url.'attachement/'.$post['attachement'].'" target="_blank" >'.$post['attachement'].'</a></div></div>';
		else:
		 $message .=	 '<div class="direct-chat-text">'.$post['message'].'</div></div>';
		endif;					
		
		return $message;
	}
}
<?php
include_once('../../../variable.php');
include_once('../../../database.php');
include_once('../../jHelper.php');
include_once('../../Helper.php');
class outwardModel
{
    public $table;
	public $table_product;
	public $table_product_value;
	public $lab;
	public $helper;
	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn = $db;
            $this->table  = "jew_outward";
			$this->table_product  = "jew_product";
			$this->table_loose_product  = "jew_loose_product";
			$this->table_product_value  = "dai_product_value";
			$this->lab  = "dai_lab";
			
			$this->helper  = new Helper($db);
			$this->jhelper  = new jHelper($db);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getMyInventory()
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." ORDER BY lab desc");
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$rs1 = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product_value ." WHERE product_id =".$row['id']);
			
			while($row1 = mysqli_fetch_assoc($rs1))
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
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			//break;
		}
		else
		{
			foreach($products as $k=>$pid)
			{
				$sql = "UPDATE ".$this->table_product." SET send_to_lab=1,site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					//break;
				}
			}
		}
		return $rs;
	}
	public function getParty($id)
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_party WHERE id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		return $data;
	}
	public function sendTo($post)
	{
		/* echo "<pre>";
		print_r($post);
		exit;   */
		$type = $post['type'];
		
		$post['date'] = $post['invoicedate'];
		$invoice = $this->getIncrementEntry('memo_invoice');
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$post['invoiceno'] = $invoice;			
		$main_stone  = ($post['main_stone'] != "")?explode(",",$post['main_stone']):array();
		$side_stone  = ($post['side_stone'] != "")?explode(",",$post['side_stone']):array();
		$collet_stone = ($post['collet_stone'] != "")?explode(",",$post['collet_stone']):array();
		$record = $mrecord = array();
		
		if(empty($side_stone) && empty($main_stone) && empty($collet_stone))
			return "Please select item or enter required value for sale ";
		
		if(isset($post['record']))
		{
			$record = $post['record'];
			unset($post['record']);
		}
		if(isset($post['mrecord']))
		{
			$mrecord = $post['mrecord'];
			unset($post['mrecord']);
		}
		if(isset($post['crecord']))
		{
			$crecord = $post['crecord'];
			unset($post['crecord']);
		}
		$balance = array();
		
		//unset($post['on_payment']);		
		//$post['products'] = explode(",",$side_stone);
		
			
		$OutProducts = array();
		$amount = 0;
		//check price and amount are blank or greter than exiesting box / parcel for carat
		//if yes then return message to outward page
		
		
		if(!empty($side_stone))
		{
			
			
		
			foreach($side_stone as $k=>$pid)
			{
				$edata = $this->getSideProductDetail($pid);			
				if(isset($record[$pid]) && $record[$pid]['carat'] !="" && $record[$pid]['carat'] != $edata['carat'] )
				{
				
				//$edata = $this->getProductDetail($pid);
				
				if( $record[$pid]['price'] =="" || $record[$pid]['amount'] =="" )
						return "Please Enter price and amount of carat for SKU : ".$edata['sku'];
			
				if( $record[$pid]['pcs'] > $edata['pcs'])
						return  "Pcs is exceed than stock PCS For SKU : ".$edata['sku'];
						
				if($record[$pid]['carat'] > $edata['carat'] )
						return  "Carat is exceed than stock carat For SKU : ".$edata['sku'];
					
				}			
			}
			
			// itterate all product which seleted for outward
			foreach($side_stone as $k=>$pid)
			{
				$edata = $this->getSideProductDetail($pid);
				
				//check carat and pcs of outward data with exiesting data if both are diferent than create new products
				if(isset($record[$pid]) && $record[$pid]['carat'] !="" )
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
					$pdata = $this->getSideProductDetail($pid);
					$OutProducts[] =  $pid;
					$sql = "UPDATE jew_loose_product SET outward='$type',visibility = 0 WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					$history =array();
				
					
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = $type;
					$history['party'] = $post['party'];		
					$history['date'] = $post['invoicedate'];
					$history['description'] = " Stone ".$type."  with invoice no is ".$post['invoiceno'];
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = ($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] == 0)?$pdata['price']:$pdata['sell_price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'dr';
					$history['invoice'] = $post['invoiceno'];
					$history['entry_from'] ='product';
					$history['for_history'] = 'side';
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
					$amount += (float)($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount']; 		
				}		
			}
		}
		if(!empty($main_stone))
		{
			
			foreach($mrecord as $k=>$r)
			{
					if($type == 'sale' || $type == 'export')
						$visi = 0;
					else
						$visi = 1;
					/* $sql = "UPDATE ".$this->table_product." SET outward='$type',visibility='$visi' WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					} */
					$r['outward'] = $type;
					$r['visibility'] = $visi;
					$r['sell_price'] = $r['price'];
					$r['sell_amount'] = $r['amount'];
					unset($r['price']);
					unset($r['amount']);
					$data = $this->helper->getUpdateString($r);	
					
					$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$k;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					$pdata = $this->getProductDetail($k);
					$history =array();
				
					
					$history =array();
					$history['product_id'] = $k;
					$history['action'] = $type;
					$history['party'] = $post['party'];		
					$history['date'] = $post['invoicedate'];
					$history['description'] = " Stone ".$type."  with invoice no is ".$post['invoiceno'];
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = ($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] == 0)?$pdata['price']:$pdata['sell_price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'dr';
					$history['invoice'] = $post['invoiceno'];
					$history['entry_from'] ='product';
					$history['for_history'] = 'main';
			
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
					$amount += (float)($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount']; 	
			}
		}
		
		if(!empty($collet_stone))
		{
			foreach($crecord as $k=>$r)
			{
					if($type == 'sale' || $type == 'export')
						$visi = 0;
					else
						$visi = 1;
				
					$r['outward'] = $type;
					$r['visibility'] = $visi;
					$data = $this->helper->getUpdateString($r);	
					
					$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$k;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					$pdata = $this->getProductDetail($k);
					$history =array();
				
					
					$history =array();
					$history['product_id'] = $k;
					$history['action'] = $type;
					$history['party'] = $post['party'];		
					$history['date'] = $post['invoicedate'];
					$history['description'] = "Collet Stone ".$type."  with invoice no is ".$post['invoiceno'];
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = $pdata['sell_amount'];
					$history['price'] = $pdata['price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'dr';
					$history['invoice'] = $post['invoiceno'];
					$history['entry_from'] ='product';
					$history['for_history'] = 'main';
			
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
					$amount += (float)($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount']; 	
			}
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
		$post['final_amount'] = $amount;
		if(isset($post['on_payment']) && $type=="sale")
		{
			$balance['book'] = $post['book'];
			$balance['cheque'] = $post['cheque'];			
			$balance['date'] = $post['bdate'];
			$balance['amount'] = $post['paid_amount']; 				
			$post['due_amount'] = (float)$post['due_amount'] - (float)$post['paid_amount'];
			if($post['due_amount'])
				$balance['description'] = 'Part Payment Received of Invoice No: '.$invoice;
			else
				$balance['description'] = 'Full Payment Received of Invoice No: '.$invoice;
				
				
			$post['part'] = 0;
		}
		else
		{
			$post['paid_amount'] = 0;
		}
		if(isset($post['shipping_charge']) && $post['shipping_charge'] !="" && is_numeric($post['shipping_charge']))
		{
			$post['due_amount'] += (float)$post['shipping_charge'];
			$post['final_amount'] += (float)$post['shipping_charge'];
		}
			
		unset($post['book']);
		unset($post['currency']);		
		//unset($post['products']);
		unset($post['bdate']);
		unset($post['cheque']);
			
		$post['side_stone'] = implode(",",$OutProducts);
		$post['main_stone'] = implode(",",$main_stone);
		$post['collet_stone'] = implode(",",$collet_stone);
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		
		$Oid = mysqli_insert_id($this->conn);
		/* $temp = explode("-",$incre_id);
		$temp[1] = $temp[1] + 1;
		$setNewid = $temp[0]."-".$temp[1]; */
		$invoice++;
		$sql = "UPDATE jew_incrementid  SET memo_invoice='$invoice' WHERE company=".$_SESSION['companyId'];		
		$rs = mysqli_query($this->conn,$sql);
		
		if(isset($post['on_payment']) && $type=="sale")
		{
			$invoice--;
			$balance['party'] = $post['party']; 
			$balance['date'] = $post['invoicedate']; 
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
			
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
			else
			{
				$book = "balance = ".$bal;					
				$sql = "UPDATE dai_book SET ".$book." WHERE id=".$balance['book'];					
				$rs = mysqli_query($this->conn,$sql);
				
			}
		}
		if(!$rs)
		{
			$rs = mysqli_error();
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
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_party WHERE name like '".$name."'" );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
			break;
		}
		return $data;
	}
	public function getProductDetail($id)
    {
		$data = array();
		
			$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." p WHERE p.company=".$_SESSION['companyId']." and p.id=".$id);
			while($row = mysqli_fetch_assoc($rs))
			{	
				$data =  $row;
			}
		
		return  $data;			
    }
	public function getSideProductDetail($id)
    {
		$data = array();
		
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE p.company=".$_SESSION['companyId']." and p.id=".$id);
			while($row = mysqli_fetch_assoc($rs))
			{
				$data =  $row;
			}
		
		return  $data;			
    }	
	public function getDetail($id, $t='main')
    {
		$data = array();		
		if($id == "" )
			return $data;
		
		if($t =='main')
			$rs = mysqli_query($this->conn,"SELECT *,p.sku as msku FROM ".$this->table_product ." p  LEFT JOIN jew_collet c ON p.id = c.product_id WHERE p.company=".$_SESSION['companyId']." and p.id=".$id);
		else
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p   WHERE company=".$_SESSION['companyId']." and id=".$id);
		
		while($row = mysqli_fetch_assoc($rs))
		{
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
		$tp += (float)$v['pcs'];
		$tc += (float)$v['carat'];
		
		
			
		$edata['pcs'] -= $v['pcs'];
		
		$edata['carat'] -= $v['carat'];
		$edata['amount'] = ($edata['carat'] * $edata['price']);

		$v['date'] = date("Y-m-d H:i:s");
		$cid = $_SESSION['companyId'];	
		$v['company'] = $cid;
		$v['user'] = $_SESSION['userid'];
	
		$pc = $v['pcs'];
		
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
		
		
		$v['outward_parent'] = $edata['id'];
		$v['outward'] = $type;
		
		if($type == 'sale' || $type == 'export')
			$v['visibility'] = 0;
		else
			$v['visibility'] = 1;
		
		if(isset($v['price']))
		{
		$v['sell_price'] = $v['price'];
		$v['sell_amount'] = $v['amount'];
		}	
		/* echo "<pre>";
		print_r($v);
		exit; */
		$data = $this->helper->getInsertString($v);	
		$sql = "INSERT INTO jew_loose_product (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)			
		{
			$rs = mysqli_error();
			return $rs;
		}
		else
		{
			
			$prid =mysqli_insert_id($this->conn);					
			$history =array();
			$history['product_id'] = $prid;
			$history['action'] = 'unboxing';			
			$history['party'] = $post['party'];		
			$history['date'] = $post['invoicedate'];
			$history['description'] = $type." with Unboxing from ".$edata['sku']." with  carat :".$v['carat'];
			$history['pcs'] = $v['pcs'];
			$history['carat'] = $v['carat'];
			$history['amount'] = $v['sell_amount'];
			$history['price'] = $v['sell_price'];
			$history['sku'] = $v['sku'];
			$history['type'] = 'cr';
			$history['invoice'] = $post['invoiceno'];
			$history['entry_from'] = 'outward';
			$history['for_history'] = 'side';
			
			$rs = $this->jhelper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}
			
			//$record = $edata['record'];
			//unset($edata['record']);
				
				
			$data = $this->helper->getUpdateString($edata);	
			$sql = "UPDATE jew_loose_product SET ".$data." WHERE id=".$edata['id'];		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();				
			}
			
			
			$history =array();
			$history['product_id'] =$edata['id'];
			$history['action'] = $type;
			$history['party'] = $post['party'];		
			$history['narretion'] = $post['narretion'];
			$history['date'] = $post['invoicedate'];
			$history['description'] = $type." with carat :".$tc;
			$history['pcs'] = $v['pcs'];
			$history['carat'] = $v['carat'];
			$history['amount'] = $v['sell_amount'];
			$history['price'] = $v['sell_price'];
			$history['sku'] = $edata['sku'];
			$history['type'] = 'dr';
			$history['invoice'] = $post['invoiceno'];
			$history['entry_from'] = 'outward';			
			$history['for_history'] = 'side';			
			$rs = $this->jhelper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}	
			return $prid;
		}			
	}
	public function getBalance($book)
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_book WHERE id=".$book);
			
		$data = "0";
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row['balance'];
		}
		return $data;
	}
	public function getIncrementEntry($of)
	{
		if($of=="")
			return "";
		$rs = mysqli_query($this->conn,"SELECT ".$of." FROM jew_incrementid WHERE company=".$_SESSION['companyId'] );
			
		$data = "";
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row[$of];
			break;
		}
		return $data;
	}
	public function getData($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." and id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		
		if(empty($data))
		{
			$field = mysqli_num_fields( $rs );
   
			for ( $i = 0; $i < $field; $i++ ) {
		   
				$data[mysqli_fetch_field_direct( $rs, $i )] = "";
		   
			}
			$data['record'] = array();
		}
		/* else
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE p.id IN (".$data['products'].")" );
			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data['record'][] = $row;
			}
		}		 */
		return  $data;			
    }
	public function getRecordData($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." WHERE  entryno= '".$id."'" );
		
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row;
		}
	
		if(empty($data))
		{
			$field = mysqli_num_fields( $rs );   
			for ( $i = 0; $i < $field; $i++ ) {		   
				$data[mysqli_fetch_field_direct( $rs, $i )] = "";		   
			}
		}	
		return  $data;			
    }
	
	public function closeMemo($id)
	{
		$sql = "UPDATE ".$this->table ." SET status='close_memo' WHERE id=".$id;		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			//break;
		}
		else
		{
			$data = $this->getData($id);
			foreach(explode(",",$data['products']) as $k=>$pid)
			{
				$sql = "UPDATE ".$this->table_product." SET outward='',site_upload=0,rapnet_upload=0 WHERE  outward='memo' and id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					//break;
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'close_memo';
				$history['party'] = $data['party'];		
				$history['narretion'] = $data['narretion'];
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Memo Closed of Entry no:".$data['entryno']." with invoice no is ".$data['invoiceno'];
				$rs = $this->helper->addHistory($history);
				//if(!$rs)
						//break;
			}
		}	
			return $rs;
	}
	
	public function closeGia($id)
	{
		$sql = "UPDATE ".$this->table ." SET status='close_lab' WHERE id=".$id;		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			//break;
		}
		else
		{
			$data = $this->getData($id);
			foreach(explode(",",$data['products']) as $k=>$pid)
			{
				$sql = "UPDATE ".$this->table_product." SET outward='',site_upload=0,rapnet_upload=0 WHERE  outward='lab' and id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					//break;
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'close_lab';
				$history['party'] = $data['party'];		
				$history['narretion'] = $data['narretion'];
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Lab Closed of Entry no:".$data['entryno'];
				$rs = $this->helper->addHistory($history);
				//if(!$rs)
						//break;
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
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();				
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
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						//break;
					}
					
				}				
				else
				{
				
					$sql = "UPDATE ".$this->table_product." SET outward='',site_upload=0,rapnet_upload=0 WHERE  outward='memo' and id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						//break;
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
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					//break;
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
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
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
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				return mysqli_error();				
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
					return mysqli_error();	
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
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			}
		}	
		if(empty($oProducts))
			$sql = "UPDATE ".$this->table." SET products='',status='close_lab' WHERE  id=".$outid;		
		else
			$sql = "UPDATE ".$this->table." SET products='".implode(",",$oProducts)."' WHERE  id=".$outid;		
		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			//break;
		}
		return $rs;
	}
	
	public function hold($post)
	{
		$status = $post['status'];
		foreach($post['ids'] as $k=>$v)
		{
			$sql = "UPDATE ".$this->table_product." SET hold=".$status.",site_upload=0,rapnet_upload=0 WHERE id=".$v;		
			
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
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
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
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
				
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
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
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				else
				{
					$Oid = mysqli_insert_id($this->conn);
					$temp = explode("-",$incre_id);
					$temp[1] = $temp[1] + 1;
					$setNewid = $temp[0]."-".$temp[1];
					$invoice++;
					$sql = "UPDATE dai_incrementid  SET outward='$setNewid',invoice='$invoice' WHERE company=".$_SESSION['companyId'];		
					$rs = mysqli_query($this->conn,$sql);
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
					
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					else
					{
						$book = "balance = ".$bal;					
						$sql = "UPDATE dai_book SET ".$book." WHERE id=".$balance['book'];					
						$rs = mysqli_query($this->conn,$sql);
						
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
		$rs = mysqli_query($this->conn,"SELECT amount FROM dai_product WHERE id=".$id);
			
		$data = "0";
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row['amount'];
		}
		return $data;
	}
	public function updateOutward($post)
	{
		/*  echo "<pre>";
		print_r($post);
		exit; */
		$smain_stone  = ($post['smain_stone'] != "")?explode(",",$post['smain_stone']):array();
		$sside_stone  = ($post['sside_stone'] != "")?explode(",",$post['sside_stone']):array();
		$scollet_stone  = ($post['scollet_stone'] != "")?explode(",",$post['scollet_stone']):array();
		$post['date'] = date("Y-m-d H:i:s");
		$rs = 0;
		$main_stone  = (isset($post['main_stone']) != "")?$post['main_stone']:array();
		$side_stone  = (isset($post['side_stone']) != "")?$post['side_stone']:array();
		$collet_stone  = (isset($post['collet_stone']) != "")?$post['collet_stone']:array();
		$otype = $post['type'];
		unset($post['type']);
		unset($post['diamond']);
		//$post['products'] = implode(",",$products);
		/*print_r($products);
		print_r($saleProducts );
		print_r($post['record']);
	//	
		exit;
		*/
		$record = $mrecord = array();
		unset($post['sside_stone']);
		unset($post['smain_stone']);
		unset($post['scollet_stone']);
		
		// check product deleted from outward than put in stock inventory
		if(!empty($sside_stone))
		{
		foreach($sside_stone as $k=>$pid)
		{
			if(in_array($pid,$side_stone))
				continue;
			
			$pdata = $this->getSideProductDetail($pid);
		
			
			if($pdata['outward_parent']!="" && $pdata['outward_parent']!=0)
			{
				$edata = $this->getSideProductDetail($pdata['outward_parent']);
				
		
				$edata['pcs']  += $pdata['pcs'];
				
				$edata['carat']  += $pdata['carat'];
				
				$edata['amount'] = ($edata['price'] * $edata['carat']);
				
				$edata['outward'] = "";
				unset($edata['record']);
				$data = $this->helper->getUpdateString($edata);	
				
				$sql = "UPDATE jew_loose_product SET ".$data." WHERE id=".$edata['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();				
				}
			
				$history =array();
				$history['product_id'] = $edata['id'];
				$history['action'] = $otype.'_return';
				$history['party'] = $post['party'];		
				$history['date'] = date("Y-m-d H:i:s");;
				$history['description'] = "Stone ".$otype." return with invoice no is ".$post['invoiceno'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $post['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $post['id'];		
				$history['for_history'] = 'side';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				$sql = "UPDATE jew_loose_product SET outward='',visibility=0,outward_parent=0 WHERE  id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
				}				
			}
			else
			{
				$sql = "UPDATE jew_loose_product SET outward='',visibility=1 WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();				
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = $otype.'_return';
				$history['party'] = $post['party'];		
				$history['date'] = date("Y-m-d H:i:s");;
				$history['description'] = "Stone ".$otype." return with invoice no is ".$post['invoiceno'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $post['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $post['id'];
				$history['for_history'] = 'side';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}				
			}
		}
		
		}
		$amount = 0.0;
		$temp = array();
		if(isset($post['record']))
		{
			$record = $post['record'];
			unset($post['record']);
		foreach($record as $k=>$pdata)
		{
			if($pdata['sku'] == "" && $pdata['carat'] == "")
				continue;
			
			$Tpcs = 0;
			$Tcarat = 0.0;	
			$pid  = $pdata['id'];
			
			$amount += (float)($pdata['sell_amount']);
				
			$BaseData = array();
			if(in_array($pid,$sside_stone))
			{
				$oldData =  $this->getSideProductDetail($pid);
				if($oldData['outward_parent']!="" && $oldData['outward_parent']!=0)
				{
					$BaseData =  $this->getSideProductDetail($oldData['outward_parent']);					
						$Tpcs = $oldData['pcs'] - $pdata['pcs'];
						$BaseData['pcs'] += $Tpcs;						
					$Tcarat = $oldData['carat'] - $pdata['carat'];
					$BaseData['carat'] += $Tcarat;				
					$BaseData['amount'] = $BaseData['carat'] * $BaseData['price'];				
				}
			}
			else
			{
					$oldData =  $this->getSideProductDetail($pid);
					$BaseData =  $this->getSideProductDetail($pid);
					$rid= $this->separateSale($pdata,$BaseData,$otype,$post);
					
					if(is_numeric($rid))
					{
						$side_stone[] =  $rid;
						unset($side_stone[$pid]);
						if(($key = array_search($pid, $side_stone)) !== false) {
							unset($side_stone[$key]);
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
			
			if(!empty($pdata))
			{	
				//$attr = $pdata['attr'];
				//unset($pdata['attr']);
				$pdata['outward'] = $otype;
				$pdata['visibility'] = 0;

				
				//$sql = "UPDATE ".$this->table_product." SET outward='sale',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
				$data = $this->helper->getUpdateString($pdata);		
				$sql = "UPDATE jew_loose_product SET ".$data." WHERE id=".$pid;
				$rs = mysqli_query($this->conn,$sql);				
				if(!$rs)
				{
					return mysqli_error();
				}
				
				$history = array();
				$history['product_id'] = $pid;				
				$history['action'] = $otype;
				$history['type'] = 'dr';
				$history['description'] = "Stone updated";									
				$history['party'] = $post['party'];		
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $pdata['sku'];				
				$history['invoice'] = $post['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $post['id'];	
				$history['for_history'] = 'side';
				$rs = $this->jhelper->addHistory($history);
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
				$sql = "UPDATE jew_loose_product SET ".$data." WHERE id=".$BaseData['id'];
				$rs = mysqli_query($this->conn,$sql);
				
				if(!$rs)
				{
					return mysqli_error();
				}
				if(!abs($Tcarat) == 0)
				{
				
				$history = array();
				$history['product_id'] = $BaseData['id'];
				if($Tcarat < 0):
					$history['action'] = $otype;
					$history['type'] = 'dr';
					$history['description'] = "Stone ".$otype." with invoice no is ".$post['invoiceno'];	
				elseif($Tcarat > 0):
					$history['action'] = $otype.'_return';
					$history['type'] = 'cr';
					$history['description'] = "Stone ".$otype." return with invoice no is ".$post['invoiceno'];
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
				$history['for_history'] = 'side';
				$history['entryno'] = $post['id'];							
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				
				}
			}				
				
		}
		}
		if(!empty($smain_stone))
		{
		foreach($smain_stone as $k=>$pid)
		{
			if(in_array($pid,$main_stone))
				continue;
					$visi = 1;
					$r['outward'] = '';
					$r['visibility'] = $visi;
					$data = $this->helper->getUpdateString($r);	
					
					$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					$pdata = $this->getProductDetail($pid);
					$history =array();
				
					
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = 'close_'.$otype;
					$history['party'] = $post['party'];		
					$history['date'] = $post['invoicedate'];
					$history['description'] = "Stone ".$otype." return with invoice no is ".$post['invoiceno'];
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = ($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] == 0)?$pdata['price']:$pdata['sell_price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $post['invoiceno'];
					$history['entry_from'] ='outward';
					$history['for_history'] = 'main';
			
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
		}
		}
		if(isset($post['mrecord']))
		{	
			$mrecord = $post['mrecord'];
			unset($post['mrecord']);
			foreach($mrecord as $k=>$pdata)
			{
				if($pdata['sku'] == "" && $pdata['carat'] == "")
					continue;
				$pid  = $pdata['id'];
				if($otype == 'sale' || $otype == 'export')
						$visi = 0;
					else
						$visi = 1;
					
					$pdata['outward'] = $otype;
					$pdata['visibility'] = $visi;
					$data = $this->helper->getUpdateString($pdata);	
					
					$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = $otype;
					$history['party'] = $post['party'];		
					$history['date'] = $post['invoicedate'];
					$history['description'] = " Stone Updated";
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = ($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] == 0)?$pdata['price']:$pdata['sell_price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'dr';
					$history['invoice'] = $post['invoiceno'];
					$history['entry_from'] ='outward';
					$history['for_history'] = 'main';
			
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
					$amount += (float)($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount']; 	
			}
		}
		if(!empty($scollet_stone))
		{
		foreach($scollet_stone as $k=>$pid)
		{
			if(in_array($pid,$collet_stone))
				continue;
					$visi = 1;
					$r['outward'] = 'collet';
					$r['visibility'] = $visi;
					$data = $this->helper->getUpdateString($r);	
					
					$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					$pdata = $this->getProductDetail($pid);
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = 'close_'.$otype;
					$history['party'] = $post['party'];		
					$history['date'] = $post['invoicedate'];
					$history['description'] = "Collet Stone ".$otype." return with invoice no is ".$post['invoiceno'];
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = $pdata['sell_amount'];
					$history['price'] = $pdata['sell_price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $post['invoiceno'];
					$history['entry_from'] ='outward';
					$history['for_history'] = 'main';
			
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
		}
		}
		if(isset($post['crecord']))
		{	
			$crecord = $post['crecord'];
			unset($post['crecord']);
			foreach($crecord as $k=>$pdata)
			{
				if($pdata['sku'] == "" && $pdata['carat'] == "")
					continue;
				$pid  = $pdata['id'];
				if($otype == 'sale' || $otype == 'export')
						$visi = 0;
					else
						$visi = 1;
					
					$pdata['outward'] = $otype;
					$pdata['visibility'] = $visi;
					$data = $this->helper->getUpdateString($pdata);	
					
					$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = $otype;
					$history['party'] = $post['party'];		
					$history['date'] = $post['invoicedate'];
					$history['description'] = "Collet Stone Updated";
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = $pdata['sell_amount'];
					$history['price'] = $pdata['sell_price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'dr';
					$history['invoice'] = $post['invoiceno'];
					$history['entry_from'] ='outward';
					$history['for_history'] = 'main';
			
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
					$amount += (float)($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount']; 	
			}
		}
		if(isset($post['shipping_charge']) && $post['shipping_charge'] != '' )
		{
			$amount += (float)$post['shipping_charge'];
		}
		
		$post['final_amount'] = $amount;
		$post['due_amount'] = (float) $amount - (float)$post['paid_amount'];
		
		$post['side_stone'] = implode(",",$side_stone);
		$post['main_stone'] = implode(",",$main_stone);
		$post['collet_stone'] = implode(",",$collet_stone);
		
	/* 	if(empty($side_stone))
			$post['status'] = "close_".$otype;  */
		
		$data = $this->helper->getUpdateString($post);		
		$sql = "UPDATE jew_outward SET ".$data." WHERE id=".$post['id'];
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();
		}
		return $rs;
	}
	
	public function getDataBySku($sku,$type)
    {
		if($type == "main")
		{
			$t = "jew_product";
		}
		else
		{
			$t = "jew_loose_product";
		}
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$t." WHERE sku='".$sku."' and outward=''");
		while($row = mysqli_fetch_assoc($rs))
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
		$smain_stone  = ($Odata['main_stone'] != "")?explode(",",$Odata['main_stone']):array();
		$sside_stone  = ($Odata['side_stone'] != "")?explode(",",$Odata['side_stone']):array();
		$scollet_stone  = ($Odata['collet_stone'] != "")?explode(",",$Odata['collet_stone']):array();
		if(!empty($sside_stone))
		{
		foreach($sside_stone as $k=>$pid)
		{
	
			$pdata = $this->getSideProductDetail($pid);
		
			
			if($pdata['outward_parent']!="" && $pdata['outward_parent']!=0)
			{
				$edata = $this->getSideProductDetail($pdata['outward_parent']);
				
		
				$edata['pcs']  += $pdata['pcs'];
				
				$edata['carat']  += $pdata['carat'];
				
				$edata['amount'] = ($edata['price'] * $edata['carat']);
				
				$edata['outward'] = "";
				
				$data = $this->helper->getUpdateString($edata);	
				
				$sql = "UPDATE jew_loose_product SET ".$data." WHERE id=".$edata['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();				
				}
			
				$history =array();
				$history['product_id'] = $edata['id'];
				$history['party'] = $Odata['party'];		
				$history['date'] = date("Y-m-d H:i:s");;
				$history['action'] = $otype."_close";
				$history['type'] = 'cr';
				$history['description'] = $otype." Closing / deleting  ";	
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $edata['sku'];
				$history['invoice'] = $Odata['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $Odata['id'];		
				$history['for_history'] = 'side';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				$history =array();
				$history['product_id'] = $pdata['id'];
				$history['party'] = $Odata['party'];		
				$history['date'] = date("Y-m-d H:i:s");;
				$history['action'] = $otype."_close";
				$history['type'] = 'dr';
				$history['description'] = $otype." Closing / deleting  ";	
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $pdata['sku'];
				$history['invoice'] = $Odata['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $Odata['id'];		
				$history['for_history'] = 'side';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				$sql = "UPDATE jew_loose_product SET outward='',visibility=0,outward_parent=0 WHERE  id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
				}				
			}
			else
			{
				$sql = "UPDATE jew_loose_product SET outward='',visibility=1 WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();				
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['party'] = $Odata['party'];		
				$history['date'] = date("Y-m-d H:i:s");
				$history['action'] = $otype."_close";
				$history['type'] = 'cr';
				$history['description'] = $otype." Closing / deleting  ";	
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $pdata['sku'];
				$history['invoice'] = $Odata['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $Odata['id'];
				$history['for_history'] = 'side';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}				
			}
			
		}
		}
		if(!empty($smain_stone))
		{
		foreach($smain_stone as $k=>$pid)
		{
					$visi = 1;
					$r['outward'] = '';
					$r['visibility'] = $visi;
					$data = $this->helper->getUpdateString($r);	
					
					$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					$pdata = $this->getProductDetail($pid);
					
					$history =array();
					$history['product_id'] = $pid;
					$history['party'] = $Odata['party'];		
					$history['date'] = date("Y-m-d H:i:s");
					$history['action'] = $otype."_close";
					$history['type'] = 'cr';
					$history['description'] = $otype." Closing / deleting  ";	
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = ($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] == 0)?$pdata['price']:$pdata['sell_price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $Odata['invoiceno'];
					$history['entry_from'] ='outward';
					$history['for_history'] = 'main';
			
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
		}
		}
		if(!empty($scollet_stone))
		{
		foreach($scollet_stone as $k=>$pid)
		{
					$visi = 1;
					$r['outward'] = 'collet';
					$r['visibility'] = $visi;
					$data = $this->helper->getUpdateString($r);	
					
					$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					$pdata = $this->getProductDetail($pid);
					
					$history =array();
					$history['product_id'] = $pid;
					$history['party'] = $Odata['party'];		
					$history['date'] = date("Y-m-d H:i:s");
					$history['action'] = $otype."_close";
					$history['type'] = 'cr';
					$history['description'] = $otype." Closing / deleting  ";	
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = $pdata['sell_amount'];
					$history['price'] = $pdata['sell_price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $Odata['invoiceno'];
					$history['entry_from'] ='outward';
					$history['for_history'] = 'main';
			
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
		}
		}
		$close = "close_".$otype;
		$sql = "UPDATE ".$this->table." SET status='".$close."' WHERE id=".$id;
		$rs = mysqli_query($this->conn,$sql);
		
		if(!$rs)
		{
			return mysqli_error();
		}
		return $rs;
    }
	public function getInwardData($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_inward WHERE company=".$_SESSION['companyId']." and id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
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
			$sql = "UPDATE jew_inward SET ".$data1." WHERE id=".$id;
		$rs = mysqli_query($this->conn,$sql);		
		if(!$rs)
		{
			return mysqli_error();
		}
		return $rs;		
	}
	
	
	public function memoTo($post)
	{
		/* echo "<pre>";
		print_r($post);
		exit; */
		$memoId = $post['memo_id'];
		unset($post['memo_id']);
		$type = $post['type'];
		$post['date'] = date("Y-m-d H:i:s");
		$invoice = $this->getIncrementEntry('invoice');
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$post['invoiceno'] = $invoice;			
		
		
		$record = $mrecord = array();
		
		$balance = array();
	
		//unset($post['on_payment']);
		
		
			
		$OutProducts = array();
		$amount = 0;
		//check price and amount are blank or greter than exiesting box / parcel for carat
		//if yes then return message to outward page
		if($post['side_stone'] != "")
		{	
			$record = $post['record'];
			unset($post['record']);
			$products = explode(",",$post['side_stone']);
			foreach($products as $k=>$pid)
			{
				$edata = $this->getSideProductDetail($pid);			
				if(isset($record[$pid]) && $record[$pid]['carat'] !="" && $record[$pid]['carat'] != $edata['carat'] )
				{
				
				//$edata = $this->getProductDetail($pid);
				
				if( $record[$pid]['price'] =="" || $record[$pid]['amount'] =="" )
						return "Please Enter price and amount of carat for SKU : ".$edata['sku'];
			
				if( $record[$pid]['pcs'] > $edata['pcs'] &&  $edata['group_type'] == 'box'  )
						return  "Pcs is exceed than stock PCS For SKU : ".$edata['sku']."df".$edata['pcs'];
						
				if($record[$pid]['carat'] > $edata['carat'] )
						return  "Carat is exceed than stock carat For SKU : ".$edata['sku'];
					
				}			
			}
			
			// itterate all product which seleted for outward
			foreach($products as $k=>$pid)
			{
				$edata = $this->getSideProductDetail($pid);
				
				//check carat and pcs of outward data with exiesting data if both are diferent than create new products
				if(isset($record[$pid]) && $record[$pid]['carat'] !="" && $record[$pid]['carat'] != $edata['carat'] )
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
				$temp = $record[$pid];
				
				$pdata = $this->getSideProductDetail($pid);
				$OutProducts[] =  $pid;
				$sql = "UPDATE ".$this->table_loose_product." SET outward='$type',sell_price=".$temp['price'].",sell_amount=".$temp['amount'] ." WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$history =array();
			
				
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = $type;
				$history['party'] = $post['party'];		
				$history['narretion'] = $post['narretion'];
				$history['date'] = $post['invoicedate'];
				$history['description'] = " Stone ".$type." FROM MEMO with PCS:".$temp['pcs'].", carat: ".$temp['carat'];		
				$history['amount'] = $temp['amount'];
				$history['price'] = $temp['price'];
				$history['pcs'] = $temp['pcs'];
				$history['carat'] = $temp['carat'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['for_history'] = 'side';
				$history['invoice'] = $post['invoiceno'];
				
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				$amount += (float)$temp['amount']; 		 		
				}		
			}
			$post['side_stone'] = implode(",",$OutProducts);
			$memoData = $this->getData($memoId);
			$sp = array();
			foreach(explode(",",$memoData['side_stone']) as $k=>$pid)
			{
				if(!in_array($pid,$OutProducts))
					{
						$sp[] = $pid;
						continue;
					}
			}
			if(empty($sp))
			{
				$sre = "";
				if($memoData['return_side'] != "")
					$sre = $memoData['return_side'].','.$memoData['side_stone'];
				else
					$sre = $memoData['side_stone'];
				$sql = "UPDATE ".$this->table." SET side_stone='',return_side=".$sre." WHERE  id=".$memoId;
				
			}
			else
			{
				$sre = "";
				if($memoData['return_side'] != "")
					$sre = $memoData['return_side'].','.implode(",",$OutProducts);
				else
					$sre = implode(",",$OutProducts);
				$sql = "UPDATE ".$this->table." SET side_stone='".implode(",",$sp)."',return_side='".$sre."' WHERE  id=".$memoId;
			}	
					
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
		}
		if($post['main_stone'] != "")
		{
			$mrecord = $post['mrecord'];
			unset($post['mrecord']);
			$mproduct = explode(",",$post['main_stone']);
			foreach($mproduct as $k=>$pid)
			{
					
				$temp = $mrecord[$pid];
				$pdata = $this->getProductDetail($pid);
				$OutProducts[] =  $pid;
				$sql = "UPDATE ".$this->table_product." SET outward='$type',sell_price=".$temp['price'].",sell_amount=".$temp['amount']." WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = $type;
				$history['party'] = $post['party'];		
				$history['narretion'] = $post['narretion'];
				$history['date'] = $post['invoicedate'];
				$history['description'] = " Stone ".$type." FROM MEMO with PCS: ".$pdata['pcs'].", carat: ".$pdata['carat'];			
				$history['amount'] = $temp['amount'];
				$history['price'] = $temp['price'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['for_history'] = 'main';
				$history['invoice'] = $post['invoiceno'];
				
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				$amount += (float)$temp['amount']; 		
			}
			$memoData = $this->getData($memoId);
			$mp = array();
			foreach(explode(",",$memoData['main_stone']) as $k=>$pid)
			{
				if(!in_array($pid,$mproduct))
					{
						$mp[] = $pid;
						continue;
					}
			}
			if(empty($mp))
			{
				$sre = "";
				if($memoData['return_main'] != "")
					$sre = $memoData['return_main'].','.$memoData['main_stone'];
				else
					$sre = $memoData['main_stone'];
				$sql = "UPDATE ".$this->table." SET main_stone='',return_main=".$sre." WHERE id=".$memoId;
			}
			else
			{
				$sre = "";
				if($memoData['return_main'] != "")
					$sre = $memoData['return_main'].','.implode(",",$mproduct);
				else
					$sre = implode(",",$mproduct);
				$sql = "UPDATE ".$this->table." SET main_stone='".implode(",",$mp)."',return_main='".$sre."' WHERE id=".$memoId;
			}	
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
		}
		if($post['collet_stone'] != "")
		{
			$crecord = $post['crecord'];
			unset($post['crecord']);
			$cproduct = explode(",",$post['collet_stone']);
			foreach($cproduct as $k=>$pid)
			{
					
				$temp = $crecord[$pid];
				$pdata = $this->getProductDetail($pid);
				$OutProducts[] =  $pid;
				$sql = "UPDATE ".$this->table_product." SET outward='$type',sell_amount=".$temp['sell_amount']." WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = $type;
				$history['party'] = $post['party'];		
				$history['narretion'] = $post['narretion'];
				$history['date'] = $post['invoicedate'];
				$history['description'] = "Collet Stone ".$type." FROM MEMO with PCS: ".$pdata['pcs'].", carat: ".$pdata['carat'];			
				$history['amount'] = $temp['sell_amount'];
				$history['price'] = $pdata['price'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['for_history'] = 'main';
				$history['invoice'] = $post['invoiceno'];
				
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				$amount += (float)$temp['sell_amount']; 		
			}
			$memoData = $this->getData($memoId);
			$mp = array();
			foreach(explode(",",$memoData['collet_stone']) as $k=>$pid)
			{
				if(!in_array($pid,$cproduct))
					{
						$mp[] = $pid;
						continue;
					}
			}
			if(empty($mp))
			{
				$sre = "";
				if($memoData['return_collet'] != "")
					$sre = $memoData['return_collet'].','.$memoData['collet_stone'];
				else
					$sre = $memoData['collet_stone'];
				$sql = "UPDATE ".$this->table." SET collet_stone='',return_collet=".$sre." WHERE  id=".$memoId;	
			}
			else
			{
				$sre = "";
				if($memoData['return_main'] != "")
					$sre = $memoData['return_main'].','.implode(",",$cproduct);
				else
					$sre = implode(",",$cproduct);
				$sql = "UPDATE ".$this->table." SET collet_stone='".implode(",",$mp)."',return_collet='".$sre."' WHERE id=".$memoId;
			}
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
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
		
		
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		
		$Oid = mysqli_insert_id($this->conn);
		$invoice++;
		$sql = "UPDATE jew_incrementid SET invoice='$invoice' WHERE company=".$_SESSION['companyId'];		
		$rs = mysqli_query($this->conn,$sql);
		
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
			
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
			else
			{
				$book = "balance = ".$bal;					
				$sql = "UPDATE dai_book SET ".$book." WHERE id=".$balance['book'];					
				$rs = mysqli_query($this->conn,$sql);				
			}
		}
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		else
		{
			$rs = $Oid;				
		}
		$memoData = $this->getData($memoId);
		if($memoData['side_stone'] == "" && $memoData['main_stone'] == "" && $memoData['collet_stone'] == "")
		{
			$rs = mysqli_query($this->conn,"update ".$this->table." set status='close_consign' where id=".$memoId);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
		}	
				
		return $rs;
	}
	
	public function separateMemoToSale($v,$edata,$type,$post)
	{
		$prid = "";
		if($edata['outward_parent'] != '')
			$maindata = $this->getSideProductDetail($edata['outward_parent']);
		else
			$maindata = $edata;
		
		$child = $maindata['child_count'] + 1;
		
		//$edata['child_count'] = $child;
		$Q = mysqli_query($this->conn,"UPDATE jew_loose_product SET child_count='$child' where id=".$maindata['id']);
		
		$v['sku'] = $maindata['sku'].'-'.$child;
			
		$tc = $tp = 0;
		$skus[] = $v['sku'];			
		$tp += (float)$v['pcs'];
		$tc += (float)$v['carat'];
		$fromcarat = $edata['carat'];
		
			
		if($edata['group_type']  =='box' )
			$edata['pcs']  -= $v['pcs'];
		
		$edata['carat']  -= $v['carat'];
		$edata['sell_amount'] = $edata['carat'] * $edata['sell_price'];

		$v['date'] = date("Y-m-d H:i:s");
		$cid = $_SESSION['companyId'];	
		$v['company'] = $cid;
		$v['user'] = $_SESSION['userid'];
	
		$pc = $v['pcs'];
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
		$v['outward_parent'] = $maindata['id'];
		$v['outward'] = $type;
		
		if($type == 'sale' || $type == 'export')
			$v['visibility'] = 0;
		else
			$v['visibility'] = 1;
		
		$v['sell_price']=$v['price'];
		$v['sell_amount']=$v['amount'];
		
		/* $attr = (array)$v['attr'];		
		unset($v['attr']); */
		$data = $this->helper->getInsertString($v);	
		$sql = "INSERT INTO jew_loose_product (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)			
		{
			$rs = mysqli_error();
			return $rs;
		}
		else
		{
			$prid =mysqli_insert_id($this->conn);					
			
			$history =array();
			$history['product_id'] = $prid;
			$history['action'] = 'unboxing';			
			$history['party'] = $post['party'];		
			$history['narretion'] = $post['narretion'];
			$history['date'] = $post['invoicedate'];
			$history['description'] = $type." with Unboxing from ".$edata['sku']." with  carat :".$v['carat'];
			$history['pcs'] = $v['pcs'];
			$history['carat'] = $v['carat'];
			$history['amount'] = $v['amount'];
			$history['price'] = $v['price'];
			$history['sku'] = $v['sku'];
			$history['type'] = 'cr';
			$history['invoice'] = $post['invoiceno'];
			$history['entry_from'] = 'outward';
			$history['for_history'] = 'side';
			
			$rs = $this->jhelper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}
			
			//$record = $edata['record'];
			//unset($edata['record']);
				
				
			$data = $this->helper->getUpdateString($edata);	
			$sql = "UPDATE jew_loose_product SET ".$data." WHERE id=".$edata['id'];		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();				
			}
			
			
			$history =array();
			$history['product_id'] =$maindata['id'];
			$history['action'] = $type;
			$history['party'] = $post['party'];		
			$history['narretion'] = $post['narretion'];
			$history['date'] = $post['invoicedate'];
			$history['description'] = $type." from Memo carat :".$fromcarat;
			$history['pcs'] = $v['pcs'];
			$history['carat'] = $v['carat'];
			$history['amount'] = $v['amount'];
			$history['price'] = $v['price'];
			$history['sku'] = $edata['sku'];
			$history['type'] = 'dr';
			$history['invoice'] = $post['invoiceno'];
			$history['entry_from'] = 'outward';		
			$history['for_history'] = 'side';
			$rs = $this->jhelper->addHistory($history);
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
		$OutProducts = $mProducts = array();
		$balance = array();
		if(isset($post['side_stone']))
		{	
		$products = $post['side_stone'];
		foreach($products as $k=>$pid)
		{
			$edata = $this->getSideProductDetail($pid);			
			if(isset($record[$pid]) && $record[$pid]['carat'] !="" && $record[$pid]['carat'] != $edata['carat'] )
			{
			
			//$edata = $this->getProductDetail($pid);
			
			if( $record[$pid]['price'] =="" || $record[$pid]['amount'] =="" )
					return "Please Enter price and amount of carat for SKU : ".$edata['sku'];
		
			if( $record[$pid]['pcs'] > $edata['pcs'] &&  $edata['group_type'] == 'box'  )
					return  "Pcs is exceed than stock PCS For SKU : ".$edata['sku'];
					
			if($record[$pid]['carat'] > $edata['carat'] )
					return  "Carat is exceed than stock carat For SKU : ".$edata['sku'];
				
			}			
		}
		
		// itterate all product which seleted for outward
		foreach($products as $k=>$pid)
		{
			$edata = $this->getSideProductDetail($pid);
			
			//check carat and pcs of outward data with exiesting data if both are diferent than create new products
			if(isset($record[$pid]) && $record[$pid]['carat'] !="" && $record[$pid]['carat'] != $edata['carat'] )
			{
				
				$parentdata = $this->getSideProductDetail($edata['outward_parent']);
				
				$parentdata['carat'] += $record[$pid]['carat'];
				$edata['carat'] -= $record[$pid]['carat'];
				
				if($edata['group_type'] == 'box')
					$edata['pcs'] -=  $record[$pid]['pcs'];
				
				if($parentdata['group_type'] == 'box')
					$parentdata['pcs'] +=  $record[$pid]['pcs'];
				
				$parentdata['amount'] = $parentdata['carat'] * $parentdata['price'];
				$sql = "UPDATE ".$this->table_loose_product." SET pcs=".$parentdata['pcs'].",carat=".$parentdata['carat'].",amount=".$parentdata['amount']." WHERE id=".$parentdata['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$edata['sell_amount'] = $edata['carat'] * $edata['sell_price'];
				$edata['amount'] = $edata['carat'] * $edata['price'];
				$sql = "UPDATE ".$this->table_loose_product." SET pcs=".$edata['pcs'].",carat=".$edata['carat'].",sell_amount=".$edata['sell_amount'].",amount=".$edata['amount']." WHERE id=".$edata['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$sellamt = $record[$pid]['carat'] * $edata['sell_price'];
				$history =array();
				$history['product_id'] = $parentdata['id'];
				$history['action'] = 'memo_return';
				$history['party'] = $memoData['party'];		
				$history['narretion'] = $post['narretion'];
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Stone Consign return with reference no is ".$memoData['reference'];
				$history['pcs'] =$record[$pid]['pcs'];
				$history['carat'] = $record[$pid]['carat'];
				$history['amount'] = ($edata['sell_amount'] =='' || $edata['sell_amount'] ==0) ? $edata['amount'] : $sellamt;
				$history['price'] = ($edata['sell_price'] =='' || $edata['sell_price'] ==0) ? $edata['price'] : $edata['sell_price'] ;
				$history['sku'] = $edata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $memoData['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['for_history'] = 'side';
				$history['entryno'] = $memoData['id'];							
				$rs = $this->jhelper->addHistory($history);
			}
			else
			{
				
				// if both are equal than just update to outward type
				$pdata = $this->getSideProductDetail($pid);
				if($pdata['outward_parent'] !='' && $pdata['outward_parent'] !=0)
				{
					$OutProducts[] =  $pid;
					$parentdata = $this->getSideProductDetail($pdata['outward_parent']);
					
					if($parentdata['group_type'] == 'box')
						$parentdata['pcs'] +=  $pdata['pcs'];
				
					$parentdata['carat'] +=  $pdata['carat'];
					
					$sql = "UPDATE ".$this->table_loose_product." SET outward='',visibility=0 WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					$parentdata['amount'] = $parentdata['carat'] * $parentdata['price'];
					$sql = "UPDATE ".$this->table_loose_product." SET pcs=".$parentdata['pcs'].",carat=".$parentdata['carat'].",amount=".$parentdata['amount']." WHERE id=".$parentdata['id'];		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					
					$history =array();
					$history['product_id'] = $parentdata['id'];
					$history['action'] = 'memo_return';
					$history['party'] = $memoData['party'];		
					$history['narretion'] = $memoData['narretion'];
					$history['date'] = date("Y-m-d H:i:s");
					$history['description'] = "Consign return with reference no is ".$memoData['reference'];
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = ($pdata['sell_amount'] =='' || $pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] =='' || $pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'] ;
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $memoData['invoiceno'];
					$history['entry_from'] = 'outward';
					$history['entryno'] = $memoData['id'];
					$history['for_history'] = 'side';
					$rs = $this->jhelper->addHistory($history);
					
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
				}
									
			}		
		}
		foreach(explode(",",$memoData['side_stone']) as $k=>$pid)
		{
			if(!in_array($pid,$OutProducts))
				{
					$sp[] = $pid;
					continue;
				}
		}
		if(empty($sp))
		{
			$sre = "";
			if($memoData['return_side'] != "")
				$sre = $memoData['return_side'].','.$memoData['side_stone'];
			else
				$sre = $memoData['side_stone'];
			$sql = "UPDATE ".$this->table." SET side_stone='',return_side=".$sre." WHERE  id=".$memoId;
			
		}
		else
		{
			$sre = "";
			if($memoData['return_side'] != "")
				$sre = $memoData['return_side'].','.implode(",",$OutProducts);
			else
				$sre = implode(",",$OutProducts);
			$sql = "UPDATE ".$this->table." SET side_stone='".implode(",",$sp)."',return_side='".$sre."' WHERE  id=".$memoId;
		}		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			
			$rs = mysqli_error();
			return $rs;
		}
	}
		if(isset($post['main_stone']))
		{	
			
			foreach($post['main_stone'] as $pid)
			{
						$pdata = $this->getProductDetail($pid);
						$mProducts[] =  $pid;
						$sql = "UPDATE ".$this->table_product." SET outward='',visibility=1 WHERE id=".$pid;		
						$rs = mysqli_query($this->conn,$sql);
						if(!$rs)
						{
							$rs = mysqli_error();
							return $rs;
						}
						
						$history =array();
						$history['product_id'] =$pid;
						$history['action'] = 'memo_return';
						$history['party'] = $memoData['party'];		
						$history['narretion'] = $memoData['narretion'];
						$history['date'] = date("Y-m-d H:i:s");
						$history['description'] = "Stone Consign return with reference no is ".$memoData['reference'];
						$history['pcs'] = $pdata['pcs'];
						$history['carat'] = $pdata['carat'];
						$history['amount'] = ($pdata['sell_amount'] =='' || $pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
						$history['price'] = ($pdata['sell_price'] =='' || $pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'] ;
						$history['sku'] = $pdata['sku'];
						$history['type'] = 'cr';
						$history['invoice'] = $memoData['invoiceno'];
						$history['entry_from'] = 'outward';
						$history['entryno'] = $memoData['id'];							
						$history['for_history'] = 'main';							
						$rs = $this->jhelper->addHistory($history);
						
						if(!is_numeric($rs) && $rs!=1)
						{
							return $rs;	
						}
			}
			foreach(explode(",",$memoData['main_stone']) as $k=>$pid)
			{
				if(!in_array($pid,$mProducts))
					{
						$mp[] = $pid;
						continue;
					}
			}
			if(empty($mp))
			{
				$sre = "";
				if($memoData['return_main'] != "")
					$sre = $memoData['return_main'].','.$memoData['main_stone'];
				else
					$sre = $memoData['main_stone'];
				$sql = "UPDATE ".$this->table." SET main_stone='',return_main=".$sre." WHERE id=".$memoId;
			}
			else
			{
				$sre = "";
				if($memoData['return_main'] != "")
					$sre = $memoData['return_main'].','.implode(",",$mProducts);
				else
					$sre = implode(",",$mProducts);
				$sql = "UPDATE ".$this->table." SET main_stone='".implode(",",$mp)."',return_main='".$sre."' WHERE id=".$memoId;
			}		
					
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				
				$rs = mysqli_error();
				return $rs;
			}
		}
		if(isset($post['collet_stone']))
		{	
			
			foreach($post['collet_stone'] as $pid)
			{
						$pdata = $this->getProductDetail($pid);
						$cProducts[] =  $pid;
						$sql = "UPDATE ".$this->table_product." SET outward='collet',visibility=1 WHERE id=".$pid;		
						$rs = mysqli_query($this->conn,$sql);
						if(!$rs)
						{
							$rs = mysqli_error();
							return $rs;
						}
						
						$history =array();
						$history['product_id'] =$pid;
						$history['action'] = 'memo_return';
						$history['party'] = $memoData['party'];		
						$history['narretion'] = $memoData['narretion'];
						$history['date'] = date("Y-m-d H:i:s");
						$history['description'] = "Collet Stone Consign return with reference no is ".$memoData['reference'];
						$history['pcs'] = $pdata['pcs'];
						$history['carat'] = $pdata['carat'];
						$history['amount'] = $pdata['sell_amount'];
						$history['price'] = ($pdata['sell_price'] =='' || $pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'] ;
						$history['sku'] = $pdata['sku'];
						$history['type'] = 'cr';
						$history['invoice'] = $memoData['invoiceno'];
						$history['entry_from'] = 'outward';
						$history['entryno'] = $memoData['id'];							
						$history['for_history'] = 'main';							
						$rs = $this->jhelper->addHistory($history);
						
						if(!is_numeric($rs) && $rs!=1)
						{
							return $rs;	
						}
			}
			foreach(explode(",",$memoData['collet_stone']) as $k=>$pid)
			{
				if(!in_array($pid,$cProducts))
					{
						$cp[] = $pid;
						continue;
					}
			}
			if(empty($cp))
			{
				$sre = "";
				if($memoData['return_collet'] != "")
					$sre = $memoData['return_collet'].','.$memoData['collet_stone'];
				else
					$sre = $memoData['collet_stone'];
				$sql = "UPDATE ".$this->table." SET collet_stone='',return_collet=".$sre." WHERE id=".$memoId;
			}
			else
			{
				$sre = "";
				if($memoData['return_collet'] != "")
					$sre = $memoData['return_collet'].','.implode(",",$cProducts);
				else
					$sre = implode(",",$cProducts);
				$sql = "UPDATE ".$this->table." SET collet_stone='".implode(",",$cp)."',return_collet='".$sre."' WHERE id=".$memoId;
			}		
					
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				
				$rs = mysqli_error();
				return $rs;
			}
		}
		$memoData = $this->getData($memoId);
		if($memoData['main_stone'] == "" && $memoData['side_stone'] == "" && $memoData['collet_stone'] == "")
		{
			$sql = "UPDATE ".$this->table." SET status='close_consign' WHERE  id=".$memoId;
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				
				$rs = mysqli_error();
				return $rs;
			}
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
		$rs = mysqli_query($this->conn,$sql);
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
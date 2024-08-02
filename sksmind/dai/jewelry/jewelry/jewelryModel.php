<?php
include('../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
include_once('../../jHelper.php');
include_once ($daiDir.'Classes/PHPExcel/IOFactory.php');
class jewelryModel
{
    public $table;
	public $table_sale;
	public $table_product;
	public $jhelper;
	public $helper;
	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn = $db;
            $this->table  = "jew_jewelry";
            $this->table_sale  = "jew_sale";
			$this->table_product  = "jew_product";			
			$this->helper  = new Helper($db);
			$this->jhelper  = new jHelper($db);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getAllData($post)
    {
		$sku=$memo=$stype="";
		if(isset($post['sku']) && $post['sku']!="")
		{ 
			$i=0;
			$post['sku'] = str_replace(' ', '', $post['sku']);
			$tem= explode(',', $post['sku']);
		
			if(count($tem) != 1)
			{
				$skua = implode("','",$tem);
				$sku .= " and ( sku IN ('".$skua."') or igi_code IN ('".$skua."'))";
			}
			else
			{
				$sku = " and ( sku LIKE '%".$post['sku']."%' or igi_code LIKE '%".$post['sku']."%')";
			}
		}
		if(isset($post['memo']))
		{ 
			$count = count($post['memo']) - 1;
			foreach($post['memo'] as $k=>$v)
			{	
				if($v == 'memo')
				{
					$memo .= " and outward = 'consign' ";
				}
			}
			
		}
		if(isset($post['stype']))
		{ 
			$stype = " and (";
			$count = count($post['stype']) - 1;
			foreach($post['stype'] as $k=>$v)
			{
				if($count == $k )
					$stype .= " lab = '".$v."'"; 
				else
					$stype .= " lab = '".$v."' ||"; 
			}
			$stype .= " ) ";
		}
		
		$rs1 = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." and (((outward='' or outward='lab') and visibility = 1) or (outward='consign' and visibility = 0)) and deleted=0 ".$sku.$memo.$stype." ORDER BY sku");
		
		$alldata = array();
		while($row1 = mysqli_fetch_assoc($rs1))
		{
			
			/* $rs1 = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." WHERE  jewelry_id = ".$row['id'] );
		
			$temp = array();
			while($row1 = mysqli_fetch_assoc($rs1))
			{
				$temp[$row1['id']] = $row1;
			} */
			
			
			$data = $row1;
			$data['collet'] = array();
			$data['main'] = array();
			$data['side'] = array();
			if($data['main_stone'] !=''):
				
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE p.id IN (".$data['main_stone'].")" );
				
				while($row = mysqli_fetch_assoc($rs))
				{
					$data['main'][] = $row;
				}
			endif;
			
			if($data['collet_stone'] !=''):
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p  LEFT JOIN jew_collet c ON p.id = c.product_id WHERE c.type='collet_receive' and p.id IN (".$data['collet_stone'].")");
				
				while($row = mysqli_fetch_assoc($rs))
				{
					$data['collet'][] = $row;
				}
			endif;
			
			if($data['side_stone'] !=''):
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE p.id IN (".$data['side_stone'].")" );
				
				while($row = mysqli_fetch_assoc($rs))
				{
					$data['side'][] = $row;
				}
			endif;
			//$row['record'] = $temp;
			$extra = $this->getExtraStone($row1['id']);
			if(!empty($extra))
			{	
				$data['side_pcs'] = $extra['side_pcs'];
				$data['side_carat'] = $extra['side_carat'];
				$data['side_price'] = $extra['side_price'];
				$data['side_amount'] = $extra['side_amount'];
			}
			else
			{
				$data['side_carat'] = 0;
			}

			$alldata[] =  $data;
			
		}		
		return  $alldata;			
    } 
	
		
	public function getJewelryForRepair($ids)
    {
		$jids = implode(",",$ids);
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE id in(".$jids.") ");
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] =  $row;			
		}		
		return  $data;			
    }
	public function getExtraStone($id)
    {
		
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_job WHERE jewelry_id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		return $data;
	}	
	// get single Data
	public function getData($id)
    {
		
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$jobdata = $this->getExtraStone($id);
			if(!empty($jobdata))
			{	
				$row['side_pcs'] = $jobdata['side_pcs'];
				$row['side_carat'] = $jobdata['side_carat'];
				$row['side_price'] = $jobdata['side_price'];
				$row['side_amount'] = $jobdata['side_amount'];
			}
			else
			{
				$row['side_pcs'] = 0.00;
				$row['side_carat'] = 0.00;
				$row['side_price'] = 0.00;
				$row['side_amount'] = 0.00;
			}	
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
		else
		{
			$collet = $main = $side = array();
			foreach( explode(",",$data['main_stone']) as $k=>$id)
			{
				if($id =='')
					continue;
				$rs1 = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." WHERE id = ".$id );			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
		
					$main[] = $row1;
				}
			}
			foreach( explode(",",$data['collet_stone']) as $k=>$id)
			{
				if($id =='')
					continue;
				$rs1 = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." WHERE id = ".$id );			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
		
					$collet[] = $row1;
				}
			}
			foreach( explode(",",$data['side_stone']) as $k=>$id)
			{
				if($id =='')
					continue;
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product WHERE id = ".$id );			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
		
					$side[] = $row1;
				}
			}
			
			$data['collet_stone'] = $collet;
			$data['main_stone'] = $main;
			$data['side_stone'] = $side;
		}		
		
		return  $data;	
					
    }
	
	public function getJobworkData($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_job WHERE company=".$_SESSION['companyId']." and id=".$id );
			
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
		else
		{
			if($data['main_stone'] !=''):
				
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE p.id IN (".$data['main_stone'].")" );
				
				while($row = mysqli_fetch_assoc($rs))
				{
					$data['record'][] = $row;
				}
			endif;
			
			if($data['collet_stone'] !=''):
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE p.id IN (".$data['collet_stone'].")" );
				
				while($row = mysqli_fetch_assoc($rs))
				{
					$data['record'][] = $row;
				}
			endif;
			
			if($data['side_stone'] !=''):
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE p.id IN (".$data['side_stone'].")" );
				
				while($row = mysqli_fetch_assoc($rs))
				{
					$data['record'][] = $row;
				}
			endif;
		}		
		return  $data;			
    }
	public function getIncrementEntry($of = "")
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
	// ADD new Ledger
   
	public function saveData($post)
    {
		/* echo "<pre>";
		print_R($post);
		exit; */
		$cid = $_SESSION['companyId'];
		$post['user'] = $_SESSION['userid'];
		$post['company'] = $cid;
		if(isset($post['mrecord']))
		{	
			$mrecord = $post['mrecord'];
			unset($post['mrecord']);
		}
		if(isset($post['srecord']))
		{	
			$srecord = $post['srecord'];
			unset($post['srecord']);
		}
		$inward['user'] = $post['user'];
		$inward['company'] = $post['company'];
		$inward['party'] = $post['party'];
		$inward['date'] = date('Y-m-d');
		unset($post['party']);
		$inward['invoicedate'] = $post['invoicedate'];
		unset($post['invoicedate']);
		$inward['invoiceno'] = $post['invoiceno'];
		unset($post['invoiceno']);
		$inward['inward_type'] = $post['inward_type'];
		unset($post['inward_type']);
		$inward['final_amount']= $inward['due_amount'] = $post['total_amount'];
		$post['final_amount']= $post['due_amount'] = $post['total_amount'];
		$post['visibility']= 1;
		if($post['igi_code'] != "")
			$post['lab'] = 'IGI';
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO jew_jewelry(". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)			
		{
			return mysqli_error();					
		}
		else
		{	
			$jewid = mysqli_insert_id($this->conn);
			$history =array();
			$history['action'] = $inward['inward_type'];
			$history['product_id'] = $jewid;
			$history['party'] = $inward['party'];		
			$history['date'] = $inward['invoicedate'];
			$history['description'] = "New jewelry ".$inward['inward_type']."";
			$history['type'] = 'cr';
			$history['carat'] = $post['gross_weight'];
			$history['amount'] =$post['total_amount'];
			$history['sku'] = $post['sku'];
			$history['invoice'] = $inward['invoiceno'];
			$history['entry_from'] ='jewelry';
			$history['for_history'] = 'jewelry';	
			$rs = $this->jhelper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}
		}
		$inward['products'] = $jewid;
		$in = $this->helper->getInsertString($inward);
		$sql = "INSERT INTO jewelry_inward (". $in[0].") VALUES (".$in[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();
		}
		else
		{
			if(!empty($mrecord))
			{
				foreach($mrecord as $r)
				{
					if($r['sku']=="" || $r['carat']=="")
						continue;
					
					$SkuData = $this->helper->getDataBySku($r['sku']);
				if(!empty($SkuData))
					continue;
					
				if($r['amount'] == "")
				$r['amount'] = $r['price'] * $r['carat'];
				$cid = $_SESSION['companyId'];	
				
				//$r['date'] = date("Y-m-d H:i:s");
				$r['company'] = $cid;
				$r['purchase_pcs'] =$r['pcs'];
				$r['purchase_carat'] =$r['carat'];
				$r['purchase_price'] =$r['price'];
				$r['purchase_amount'] =$r['amount'];	
				$r['user'] = $_SESSION['userid'];
				$pc = $r['pcs'];
				$group = "";
				
				if($pc == 1 || $pc == 1.00)
				{	$group = "single"; }
				else if($pc > 1)
				{	$group = "box"; }
				else if($pc == "" || $pc==0)
				{
					$group = "parcel"; 
				}
					
				$r['group_type'] = 'single';
				$r['visibility'] = 0;
				$r['outward'] = 'jewelry';
					
					$r['jewelry_id'] = $jewid;					
					$data = $this->helper->getInsertString($r);	
					$sql = "INSERT INTO ".$this->table_product ." (". $data[0].") VALUES (".$data[1].")";		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)			
					{
						return mysqli_error();					
					}
					else
					{
						$pid = mysqli_insert_id($this->conn);	
						
						$iProducts[] = $pid; 
						
						$history =array();
						$history['product_id'] = $pid;
						$history['action'] = $post['inward_type'];
						$history['party'] = $post['party'];		
						$history['narretion'] = $post['narretion'];
						$history['date'] = $post['invoicedate'];
						$history['description'] = "New Stone import from jewelry ".$post['inward_type'];
						$history['pcs'] = $r['pcs'];
						$history['carat'] = $r['carat'];
						$history['amount'] = $r['amount'];
						$history['price'] = $r['price'];
						$history['sku'] = $r['sku'];
						$history['type'] = 'cr';
						$history['invoice'] = $post['invoiceno'];
						$history['entry_from'] = 'jewlery '.$post['inward_type'];
						$history['for_history'] = 'main';	
						$rs = $this->jhelper->addHistory($history);	
					}

				}
			}
			if(!empty($srecord))
			{
				foreach($srecord as $r)
				{
					if($r['sku']=="" || $r['carat']=="")
						continue;
					
					/* $SkuData = $this->helper->getDataBySku($r['sku']);
				if(!empty($SkuData))
					continue; */
					
				if($r['amount'] == "")
				$r['amount'] = $r['price'] * $r['carat'];
				$cid = $_SESSION['companyId'];	
				
				//$r['date'] = date("Y-m-d H:i:s");
				$r['company'] = $cid;
				$r['purchase_pcs'] =$r['pcs'];
				$r['purchase_carat'] =$r['carat'];
				$r['purchase_price'] =$r['price'];
				$r['purchase_amount'] =$r['amount'];	
				$r['user'] = $_SESSION['userid'];
				$pc = $r['pcs'];
				$group = "";
				
				if($pc == 1 || $pc == 1.00)
				{	$group = "single"; }
				else if($pc > 1)
				{	$group = "box"; }
				else if($pc == "" || $pc==0)
				{
					$group = "parcel"; 
				}
					
				$r['group_type'] = 'single';
				$r['visibility'] = 0;
				$r['outward'] = 'jewelry';
				$r['jewelry_id'] = $jewid;					
					$data = $this->helper->getInsertString($r);	
					$sql = "INSERT INTO jew_loose_product(". $data[0].") VALUES (".$data[1].")";		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)			
					{
						return mysqli_error();					
					}
					else
					{
						$pid = mysqli_insert_id($this->conn);	
						
						$sProducts[] = $pid; 
						
						$history =array();
						$history['product_id'] = $pid;
						$history['action'] = $post['inward_type'];
						$history['party'] = $post['party'];		
						$history['narretion'] = $post['narretion'];
						$history['date'] = $post['invoicedate'];
						$history['description'] = "New Stone import from jewelry ".$post['inward_type'];
						$history['pcs'] = $r['pcs'];
						$history['carat'] = $r['carat'];
						$history['amount'] = $r['amount'];
						$history['price'] = $r['price'];
						$history['sku'] = $r['sku'];
						$history['type'] = 'cr';
						$history['invoice'] = $post['invoiceno'];
						$history['entry_from'] = 'jewlery '.$post['inward_type'];
						$history['for_history'] = 'loose';	
						$rs = $this->jhelper->addHistory($history);	
					}

				}
			}
			$main = $side = "";
			if(!empty($iProducts))
				$main = implode(",",$iProducts);
			if(!empty($sProducts))
				$side = implode(",",$sProducts);
			$sql = "UPDATE jew_jewelry SET main_stone='$main',side_stone='$side' WHERE id=".$jewid;		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();					
			}
		}
		
		return $rs;
    }
   
   public function updateData($post)
    { 
		/*  echo "<pre>";
		print_r($post);
		exit;  */
		 $spcs = $post['side_pcs'];
		 unset($post['side_pcs']);
		 $scarat = $post['side_carat'];
		 unset($post['side_carat']);
		 $sprice = $post['side_price'];
		 unset($post['side_price']);
		 $samount = $post['side_amount'];
		 unset($post['side_amount']);
		$srecord = $mrecord = $crecord = array();
		if(isset($post['crecord']))
		{
			$crecord = $post['crecord'];
			unset($post['crecord']);
			foreach($crecord as $key=>$value)
			{
				if($key == "")
				continue;	
				$amount = $value['price']*$value['carat'];
				$color = $value['color'];
				$clarity = $value['clarity'];
				mysqli_query("update jew_product set price=".$value['price'].",amount=".$amount.",color='$color',clarity='$clarity' where id=".$key);
				mysqli_query("update jew_collet set total_amount=".$value['total_amount'].",total_amount_cost=".$value['total_amount']." where type='collet_receive' and product_id=".$key);
			}
		}	
		if(isset($post['mrecord']))
		{
			$mrecord = $post['mrecord'];
			unset($post['mrecord']);
			foreach($mrecord as $key=>$value)
			{
				if($key == "")
				continue;	
				$amount = $value['price']*$value['carat'];
				$color = $value['color'];
				$clarity = $value['clarity'];
				$rs = mysqli_query($this->conn,"update jew_product set price=".$value['price'].",color='$color',clarity='$clarity',amount=".$amount." where id=".$key);
			}
		}
		if(isset($post['srecord']))
		{
			$srecord = $post['srecord'];
			unset($post['srecord']);
			foreach($srecord as $value)
			{
				if($value['id'] == "")
				continue;	
				$amount = $value['price']*$value['carat'];
				$color = $value['color'];
				$clarity = $value['clarity'];
				$rs = mysqli_query($this->conn,"update jew_loose_product set price=".$value['price'].",amount=".$amount.",color='$color',clarity='$clarity' where id=".$value['id']);
			}
		}
		mysqli_query("update jew_job set side_price=".$sprice.",side_amount=".$samount." where jewelry_id=".$post['id']);
		$data = $this->helper->getUpdateString($post);		
		$sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$post['id'];
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();
		}
		return $rs;
    }
   
   	public function saleJewelry($post)
    {

		$lid = 0;
		$invoice = $this->getIncrementEntry('invoice');
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$jtype = $post['type'];	
		$post['invoiceno'] = $invoice;			
		$post['status'] = '1';			
		$products = $post['products'];		
		unset($post['products']);
		
		$record = $post['record'];		
		unset($post['record']);
		$tm = 0;
		foreach($record as $k=>$jdata)			
		{
			$fp = $jdata['amount'];
			$tm += $fp;
			$sql = "UPDATE ".$this->table." SET sell_price=$fp WHERE id=".$k;		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				return mysqli_error();
			}
		}
		$post['products'] = implode(",",$products);	
		$post['final_amount'] = $tm;
		$post['due_amount'] = $post['final_amount'];		
		
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table_sale ." (". $data[0].") VALUES (".$data[1].")";
		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();
		}
		else
		{
			$lid = mysqli_insert_id($this->conn);	
			$invoice++;
			$sql = "UPDATE jew_incrementid  SET invoice='$invoice' WHERE company=".$_SESSION['companyId'];		
			$rs = mysqli_query($this->conn,$sql);
			
			foreach($products as $k=>$jid)			
			{
				$sql = "UPDATE ".$this->table." SET outward_id=$lid,visibility=0,outward='$jtype' WHERE id=".$jid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
				}
				$pdata = $this->getData($jid);
				$history =array();
				$history['product_id'] = $jid;
				$history['action'] = $jtype;
				$history['party'] = $post['party'];		
				$history['date'] = $post['date'];
				$history['description'] = $jtype." Jewelry ";
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['invoice'] = $invoice;
				$history['entry_from'] = 'jewelry';
				$history['for_history'] = 'jewelry';
				$history['entryno'] = $lid;	
				$history['carat'] = $pdata['gross_weight'];	
				$history['amount'] = $pdata['sell_price'];						
				$rs = $this->jhelper->addHistory($history);
			}
		}	
		return $lid;
    }
	
	public function memoTosaleJewelry($post)
    {
		/* echo "<pre>";
		print_r($post);
		exit; */
		$memo_id = $post['memo_id'];
		unset($post['memo_id']);
		$lid = 0;
		$invoice = $this->getIncrementEntry('invoice');
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$jtype = $post['type'];	
		$post['invoiceno'] = $invoice;			
		$post['status'] = '1';			
		$products = $post['products'];		
		unset($post['products']);
		$post['products'] = implode(",",$products);	
		
		$record = $post['record'];		
		unset($post['record']);
		
		$tm = 0;
		foreach($record as $k=>$jdata)			
		{
			$fp = $jdata['amount'];
			$tm += $fp;
			$sql = "UPDATE ".$this->table." SET sell_price=$fp WHERE id=".$k;		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				return mysqli_error();
			}
		}
		$post['final_amount'] = $tm;
		$post['due_amount'] = $post['final_amount'];
		
		
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table_sale ." (". $data[0].") VALUES (".$data[1].")";
		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();
		}
		else
		{
			$lid = mysqli_insert_id($this->conn);	
			$invoice++;
			$sql = "UPDATE jew_incrementid  SET invoice='$invoice' WHERE company=".$_SESSION['companyId'];		
			$rs = mysqli_query($this->conn,$sql);
			
			foreach($products as $k=>$jid)			
			{
				$sql = "UPDATE ".$this->table." SET outward_id=$lid,visibility=0,outward='sale' WHERE id=".$jid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
				}
				$pdata = $this->getData($jid);
				$history =array();
				$history['product_id'] = $jid;
				$history['action'] = $jtype;
				$history['party'] = $post['party'];		
				$history['date'] = $post['date'];
				$history['description'] = $jtype." Jewelry ";
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['invoice'] = $invoice;
				$history['entry_from'] = 'jewelry consign';
				$history['for_history'] = 'jewelry';
				$history['entryno'] = $lid;	
				$history['carat'] = $pdata['gross_weight'];	
				$history['amount'] = $pdata['sell_price'];						
				$rs = $this->jhelper->addHistory($history);
			}
		}
		
		$memoData = $this->getSaleData($memo_id);
		
		$removeProduct = explode(",",$post['products']);
		$reaminProduct = array();
		foreach( explode(",",$memoData['products']) as $k=>$jid)
		{
			if(!in_array($jid, $removeProduct))
			{
				$reaminProduct[] = $jid;
			}			
		}
		if(!empty($memoData['return_products'])){
			$tmp['return_products'] = $memoData['return_products'].','.implode(",",$removeProduct);
		}
		else{
			$tmp['return_products'] = implode(",",$removeProduct);
		}
		$tmp['products'] = 	implode(",",$reaminProduct);
		if(empty($reaminProduct))
			$tmp['status'] = '0';
			
		$data = $this->helper->getUpdateString($tmp);		
		$sql = "UPDATE jew_sale SET ".$data." WHERE id=".$memo_id;
		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();
		}
			
		return $lid;
    }
	
	
    public function delete($id)
    {
		$sql = "DELETE FROM ".$this->table ." WHERE id = ".$id;
        $rs = mysqli_query($this->conn,$sql);
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
				
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
		}
			
	}
	public function getDetail($id)
    {
		$data = array();		
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_product WHERE id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
		}				
		return  $data;			
    }
	
	public function savePackage($post)
	{
		foreach($post['product'] as $pid=>$pac)
		{
			$sql = "UPDATE ".$this->table_product_value." SET package='$pac' WHERE product_id=".$pid;		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
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
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
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
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();
		}
		return $rs;
	}
	
	public function skuData($sku)
    {
		$data = array();	
			
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_product p LEFT JOIN dai_product_value pv ON p.id = pv.product_id WHERE sku='".$sku."'");
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
		}	

		if(empty($data))
		{
			$field = mysqli_num_fields( $rs );   
			for ( $i = 0; $i < $field; $i++ ) 
			{
				$data[mysqli_fetch_field_direct( $rs, $i )] = "";
			}			
		}		
		return  $data;			
    }
	public function getProductDetail($id)
    {
		
		$data = array();		
		if($id == "" )
			return $data;
	
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." p WHERE p.id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
		}				
		return  $data;			
    }
	
	public function checkSku($sku)
    {
		
		$data = array();		
		if($sku == "" )
			return 0;
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." p WHERE p.sku='".$sku."'");
		while($row = mysqli_fetch_assoc($rs))
		{
			
			$data =  $row;
		}
		
		
		if(empty($data))
			return 1;
		else
			return 0;
		
    }
	
	public function memoToReturn($post)
	{
		
		$memoId = $post['memo_id'];
		
		$memoData = $this->getJobworkData($memoId);
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;
		$post['visibility'] = 1;		
		$rs = 0;
		
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			echo mysqli_error();
			exit;
			
		}
		else
		{
			$lid = mysqli_insert_id($this->conn);
			$products = explode(",",$post['collet_stone']);
			foreach($products as $k=>$pid)
			{
					if($pid == '')
						continue;
					
					$sql = "UPDATE ".$this->table_product." SET outward='in_jewelry', jewelry_id = $lid  WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = 'in_jewelry';
					$history['party'] = $memoData['party'];		
					$history['narretion'] = $memoData['narretion'];
					//$history['date'] = date("Y-m-d H:i:s");
					$history['date'] = $post['date'];
					$history['description'] = "Collet in jewelry and code is ".$post['sku'];
					
					$history['type'] = 'dr';
					$history['invoice'] = $memoData['entryno'];
					$history['entry_from'] = 'jewelry';
					$history['entryno'] = $lid;	
					$history['for_history'] = 'main';		
					$rs = $this->jhelper->addHistory($history);
			}
			
			
			$products = explode(",",$post['main_stone']);
			foreach($products as $k=>$pid)
			{
					if($pid == '')
						continue;
					
					$sql = "UPDATE ".$this->table_product." SET outward='in_jewelry', jewelry_id = $lid  WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = 'in_jewelry';
					$history['party'] = $memoData['party'];		
					$history['narretion'] = $memoData['narretion'];
					$history['date'] = $post['date'];
					$history['description'] = "Main Stone in jewelry and code is ".$post['sku'];
					
					$history['type'] = 'dr';
					$history['invoice'] = $memoData['entryno'];
					$history['entry_from'] = 'jewelry';
					$history['entryno'] = $lid;							
					$rs = $this->jhelper->addHistory($history);
			}
			
			$products = explode(",",$post['side_stone']);
			foreach($products as $k=>$pid)
			{
					if($pid == '')
						continue;
					$sql = "UPDATE jew_loose_product SET outward='in_jewelry', jewelry_id = $lid  WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					
					$history =array();
					$history['product_id'] = $pid;
					$history['action'] = 'in_jewelry';
					$history['party'] = $memoData['party'];		
					$history['narretion'] = $memoData['narretion'];
					$history['date'] = $post['date'];
					$history['description'] = "Side Stone in jewelry and code is ".$post['sku'];
					
					$history['type'] = 'dr';
					$history['invoice'] = $memoData['entryno'];
					$history['entry_from'] = 'jewelry';
					$history['entryno'] = $lid;	
					$history['for_history'] = 'side';						
					$rs = $this->jhelper->addHistory($history);
			}
			
			
			$history =array();
			$history['product_id'] = $lid;
			$history['action'] = 'jewelry';
			$history['party'] = $memoData['party'];		
			$history['narretion'] = $memoData['narretion'];
			$history['date'] = $post['date'];
			$history['description'] = "Ready Jewelry Return from maker.";
			$history['sku'] = $post['sku'];
			$history['type'] = 'cr';
			$history['invoice'] = $memoData['entryno'];
			$history['entry_from'] = 'jewelry';
			$history['entryno'] = $lid;	
			$history['for_history'] = 'jewelry';		
			$rs = $this->jhelper->addHistory($history);
		}

		$sql = "UPDATE jew_job SET is_returned = 1  WHERE id=".$memoId;		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		
		return $rs;	
	}

	public function colletToReturn($post)
	{
		/* echo "<pre>";
		print_r($post);
		exit;*/ 
		$memoId = $post['memo_id'];
		$products = $post['product'];
		$memoData = $this->getJobworkData($memoId);
		$cid = $_SESSION['companyId'];
		$rs = 0;
		
			
		//$products = explode(",",$post['products']);
		foreach($products as $k=>$pdata)
		{
				$pdata['outward'] = 'collet';				
				$pdata['collet_date'] = $post['date'];
				$pdata['visibility'] = 1;
				$pdata['is_collet'] = 1;
				
				$data = $this->helper->getUpdateString($pdata);		
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$k;
			
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				
				$history =array();
				$history['product_id'] = $k;
				$history['action'] = 'collet_return';
				$history['party'] = $memoData['party'];		
				$history['narretion'] = $memoData['narretion'];
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Store return in form of collet ";
				//$history['sku'] = $post['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $memoData['entryno'];
				$history['entry_from'] = 'collet';
				$history['entryno'] = $memoData['entryno'];
				$history['for_history'] = 'main';					
				$rs = $this->jhelper->addHistory($history);
		}
		$sql = "UPDATE jew_job SET is_returned = 1 WHERE id=".$memoId;		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		
		return $rs;	
	}
	
	public function getRepairData($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_repair WHERE company=".$_SESSION['companyId']." and id=".$id );
			
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
		else
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_jewelry p WHERE p.id IN (".$data['products'].")" );
			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data['record'][] = $row;
			}
		}		
		return  $data;			
    }
	public function repairToReturn($post)
	{
		//print_r($post);
		$memoId = $post['memo_id'];
		
		$memoData = $this->getRepairData($memoId);
		/* print_r($memoData);
		exit; */
		$cid = $_SESSION['companyId'];
		//$post['company'] = $cid;
		//$post['visibility'] = 1;		
		$rs = 0;
		
		$product = $post['product'];
		unset($post['product']);
		
		foreach($product as $k=>$pd)
		{
			$pData = $this->getProductDetail($k);
			
			$pd['product_id'] = $k;
			$pd['repair_id'] = $memoId;
			$pd['date'] = $post['date'];
			$pd['memo_maker'] = $post['memo_maker'];
			$data = $this->helper->getInsertString($pd);	
			$sql = "INSERT INTO jew_product_repair (". $data[0].") VALUES (".$data[1].")";
				
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				return mysqli_error();
			}
			
			$pData['gold_gram'] += $pd['new_gram'];
			$pData['gold_amount'] += $pd['new_amount'];
			$pData['total_pcs'] += $pd['side_pcs'];
			$pData['total_carat'] += $pd['side_carat'];
			$pData['total_amount'] += $pd['side_amount'];
			$pData['gross_cts'] = $pd['new_gram'] + ( $pd['side_carat'] / 5) - $pd['loss'];
			$pData['labour_fee'] += $pd['labour'];
			$pData['final_cost'] = $pData['final_cost'] + $pd['labour'] + $pd['side_amount'] + $pd['new_amount'];
			$pData['in_repair'] = 0;
			
			$data = $this->helper->getUpdateString($pData);		
			$sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$k;
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				return mysqli_error();
			}
			
		}
		$lid = mysqli_insert_id($this->conn);
		$removeProduct = explode(",",$post['products']);
		$reaminProduct = array();
		foreach( explode(",",$memoData['products']) as $k=>$jid)
		{
			if(!in_array($jid, $removeProduct))
			{
				$reaminProduct[] = $jid;
			}			
		}

		$tmp['return_products'] = 	$memoData['return_products'].','.implode(",",$removeProduct);
		$tmp['products'] = 	implode(",",$reaminProduct);
		if(empty($reaminProduct))
			$tmp['status'] = 0;
			
		$data = $this->helper->getUpdateString($tmp);		
		$sql = "UPDATE jew_repair SET ".$data." WHERE id=".$memoId;
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();
		}
		
		return $rs;	
	}
	
	public function consignToReturn($post)
	{
		$memoId = $post['memo_id'];
		
		$memoData = $this->getSaleData($memoId);
		
		if(empty($memoData))
			return "Consignment is not exiest";
			
		$cid = $_SESSION['companyId'];
		$rs = 0;
		
		$removeProduct = explode(",",$post['products']);
		//unset($post['product']);
		
		foreach($removeProduct as $k=>$pd)
		{
			//$pData = $this->getProductDetail($k);
			
			$sql = "UPDATE ".$this->table." SET visibility = 1,outward='' WHERE id=".$pd;
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				return mysqli_error();
			}
			
		}
		$lid = mysqli_insert_id($this->conn);
		
		$reaminProduct = array();
		foreach( explode(",",$memoData['products']) as $k=>$jid)
		{
			if(!in_array($jid, $removeProduct))
			{
				$reaminProduct[] = $jid;
			}			
		}
		if(!empty($memoData['return_products'])){
			$tmp['return_products'] = $memoData['return_products'].','.implode(",",$removeProduct);
		}
		else{
			$tmp['return_products'] = implode(",",$removeProduct);
		}
		$tmp['products'] = 	implode(",",$reaminProduct);
		if(empty($reaminProduct))
			$tmp['status'] = '0';
			
		$data = $this->helper->getUpdateString($tmp);		
		$sql = "UPDATE jew_sale SET ".$data." WHERE id=".$memoId;
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();
		}
		
		return $rs;	
	}
	
	public function getDataBySku($sku)
    {
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_jewelry p WHERE p.sku='".$sku."' and outward='' and deleted=0" );
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
			break;
		}
		
		return  $data;			
    }
	
	public function getSaleData($id)
    {
	
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_sale." WHERE company=".$_SESSION['companyId']." and id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		if(isset($data['status']) && $data['status'] == '0' )
			return array();
			
		if(empty($data))
		{
			$field = mysqli_num_fields( $rs );
   
			for ( $i = 0; $i < $field; $i++ ) {
		   
				$data[mysqli_fetch_field_direct( $rs, $i )] = "";
		   
			}
			$data['record'] = array();
		}
		else
		{
			//echo "SELECT * FROM ".$this->table." p WHERE p.id IN (".$data['products'].")"; 
			$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table." p WHERE p.id IN (".$data['products'].")" );			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data['record'][] = $row;
			}
		}		
		return  $data;			
    }
	
	public function deleteRepair($id)
	{
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_repair WHERE id =".$id);			
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		if(empty($data))
			return "";
			
		$sql = "UPDATE jew_repair SET deleted = 1 WHERE id=".$id;		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}

		$sql = "UPDATE jew_jewelry SET in_repair = 0 WHERE id=IN (".$data['products'].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		return $rs;
	}
	
	public function updateSale($post)
    {
		$products = $post['products'];
		$sproducts = explode(",",$post['sproducts']);
		unset($post['products']);
		unset($post['sproducts']);
		$post['products'] = implode(",",$products);	
		$post['due_amount'] = $post['final_amount'];
		$record = $post['record'];
		unset($post['record']);
		
		foreach($sproducts as $k=>$pid)
		{
			if(in_array($pid,$products))
				continue;
			$rs = mysqli_query($this->conn,"update jew_jewelry set outward = '',visibility = 1 where id =".$pid);
			if(!$rs)
			{
				$rs = mysqli_error();
				//break;
			}
		}
		
		$data = $this->helper->getUpdateString($post);
		$sql = " UPDATE ".$this->table_sale." SET ".$data." WHERE id=".$post['id'];		
		
	    $rs = mysqli_query($this->conn,$sql);
	    if(!$rs)
		{
			return mysqli_error();
			
		}
		else
		{
			foreach($record as $r)
			{
				if(isset($r['visibility']))
				{
					$r['visibility'] = 0;
					$r['outward'] = $_POST['type'];
					$rdata = $this->helper->getUpdateString($r);	
					$sql = "UPDATE jew_jewelry SET ".$rdata." WHERE id=".$r['id'];		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						//break;
					}
				}
				else
				{
					$rdata = $this->helper->getUpdateString($r);	
					$sql = "UPDATE jew_jewelry SET ".$rdata." WHERE id=".$r['id'];		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						//break;
					}
				}
			}
		}
		return $rs;
    }
	
	public function updatePrice($post)
	{
		$rs = 1;
		foreach($post['product'] as $pid=>$price)
			{
				$data = $this->getData($pid);
				$price1 = $price['price'];
				$oldPrice = $data['selling_price'];
			
				
				$sql = "UPDATE ".$this->table." SET selling_price=$price1 WHERE id=".$pid;		
				
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				//echo $rs;
				$history = array();
				$history['product_id'] = $pid;
				$history['action'] = 'price_change';
				$history['date'] = date("Y-m-d H:i:s");
				$history['narretion'] = "Selling price is changed.";
				$history['description'] = "Old Price :  ".$oldPrice ." , New Price :  ".$price1;
				$history['for_history'] = 'jewelry';	
				$history['sku'] = $data['sku'];	
				$history['party'] = $data['party'];	
				$rs = $this->jhelper->addHistory($history);
			}
			return $rs;
		}
	
	public function getDataSale($id)
    {
		$data = array();
		$r = mysqli_query($this->conn,"select * from jew_sale where id=".$id);
		while($row = mysqli_fetch_array($r))
		{
			$data = $row;
		}
		return $data;
	}
		
	public function deleteSale($post)
    {
		$Lid = $post['id'];
		$rs=0;
		$oData = $this->getDataSale($Lid);
		$products = explode(",",$oData['products']);
		foreach($products as $k=>$pid)
		{
			$rs = mysqli_query($this->conn,"update jew_jewelry set outward = '',visibility = 1 where id =".$pid);
			if(!$rs)
			{
				$rs = mysqli_error();
				//break;
			}
				
		}
		$rs = mysqli_query($this->conn,"update jew_sale set deleted = 1 where id =".$Lid);
		if(!$rs)
		{
			$rs = mysqli_error();
			//break;
		}
		return $rs;
	}
	public function saveCharge($post)
	{
		$id  = $post['sale_id'];
		$data =array();
		
		$data =	$this->getDataSale($id);
		
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
		
		$sql = "UPDATE ".$this->table_sale." SET ".$data1." WHERE id=".$id;
	
		$rs = mysqli_query($this->conn,$sql);		
		if(!$rs)
		{
			return mysqli_error();
		}
		return $rs;		
	}
	public function sendToLab($post)
	{
		/* echo "<pre>";
		print_r($post);
		exit; */
		$oincre_id = $this->getIncrementEntry('outward');
		$iincre_id = $this->getIncrementEntry('invoice');
		
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$post['entryno'] = $oincre_id;	
		$post['invoiceno'] = $iincre_id;
		$products = array();
		$products = $post['products'];
		unset($post['product']);
		$post['products'] = implode(",",$products);
		$post['products_receive'] = $post['products'];
		
		$out = "lab";
		foreach($products as $pid)
		{
			$pdata = $this->getData($pid);
			$sql = "update ".$this->table." set outward ='$out' where id =".$pid;
			$rs = mysqli_query($this->conn,$sql);
			
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'lab';
				$history['party'] = $post['party'];		
				$history['date'] = $post['date'];
				$history['description'] = "jewelry Send to Lab Work. invoice Number :".$iincre_id;
				$history['pcs'] = "";
				$history['carat'] = "";
				$history['amount'] = "";
				$history['price'] = "";
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['invoice'] = $iincre_id;
				$history['entry_from'] ='lab';
				$history['for_history'] = 'jewelry';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
		}
		
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO jewelry_lab (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		$Oid =  mysqli_insert_id($this->conn);
		if(!$rs)			
		{
			$rs = mysqli_error();
			return $rs;
		}
		$iincre_id++;
		$oincre_id++;
		$sql = "UPDATE jew_incrementid  SET outward ='$oincre_id' WHERE company=".$_SESSION['companyId'];		
		$rs = mysqli_query($this->conn,$sql);
		
		$sql = "UPDATE jew_incrementid  SET invoice ='$iincre_id' WHERE company=".$_SESSION['companyId'];	
		$rs = mysqli_query($this->conn,$sql);
		
		$rs = $Oid;
		return $rs;
	}
	
	public function updatesendToLab($post)
    {
		$post['products'] = implode(",",$post['product']);
		$data = $this->helper->getUpdateString($post);	
		$sql = "UPDATE jew_lab SET ".$data." WHERE id=".$edata['id'];		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();				
		}
		return $rs;
	}
	public function getDataLab($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM jewelry_lab WHERE company=".$_SESSION['companyId']." and id=".$id );
			
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
				
		return  $data;			
    }
	public function saveGia($post)
	{
		/* echo "<pre>";
		print_r($post);
		exit;  */
		$date = $post['receive_date'];
		$rs = 0;
		
		$outid = $post['outid'];
		$oData = $this->getDataLab($outid);
		$oProducts = explode(",",$oData['products_receive']);
		foreach($post['record'] as $k=>$v)
		{
			if(($key = array_search($k, $oProducts)) !== false) {
				unset($oProducts[$key]);
			}
			if(isset($v['mrecord']))
			{
				$mrecord = $v['mrecord'];
				unset($v['mrecord']);
				foreach($mrecord as $key=>$value)
				{
					$value['color'] = $value['igi_color'];
					$value['clarity'] = $value['igi_clarity'];
					$data1 = $this->jhelper->getUpdateString($value);	
					$sql = "UPDATE jew_product SET ".$data1." WHERE id=".$key;	
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
						
					}
					$history =array();
					$history['product_id'] = $key;
					$history['action'] = 'lab_return';
					$history['party'] = $oData['party'];		
					$history['date'] = $date;
					$history['description'] = "Clarity & Color updated from Jewelry Return from Lab Work. Lab Number :".$oData['invoiceno'];
					$history['pcs'] = "";
					$history['carat'] = "";
					$history['amount'] ="";
					$history['price'] = "";
					$history['sku'] = $value['sku'];
					$history['type'] = 'cr';
					$history['invoice'] = $oData['invoiceno'];
					$history['entry_from'] ='jew_lab';
					$history['for_history'] = 'main';	
					$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
				}	
			}	
				$pdata = $this->getData($k);
				$v['igi_date'] = $date;
				$v['outward'] = '';
				$v['lab'] = 'IGI';
				$v['total_amount'] = $pdata['total_amount'] + $v['lab_fee'];
				$data = $this->jhelper->getUpdateString($v);		
				$sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$k;	
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
					
				}
				unset($v['igi_date']);
				unset($v['outward']);
				
				$history =array();
				$history['product_id'] = $k;
				$history['action'] = 'lab_return';
				$history['party'] = $oData['party'];		
				$history['date'] = $date;
				$history['description'] = "Jewelry Return from Lab Work. Lab Number :".$oData['invoiceno'];
				$history['pcs'] = "";
				$history['carat'] = "";
				$history['amount'] ="";
				$history['price'] = "";
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $oData['invoiceno'];
				$history['entry_from'] ='lab';
				$history['for_history'] = 'jewelry';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
	
		}
		if(empty($oProducts))
			$sql = "UPDATE jewelry_lab SET products_receive='',is_returned=1 WHERE  id=".$outid;		
		else
			$sql = "UPDATE jewelry_lab SET products_receive='".implode(",",$oProducts)."' WHERE  id=".$outid;		
		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			//break;
		}	
			
		return $rs;
	}
	public function updateLab($post)
    {
		$sproducts = explode(",", $post['sproducts']);
		$products = $post['products'];
		unset($post['products']);
		unset($post['sproducts']);
		$post['products'] = implode(",",$products);	
		$post['products_receive'] = $post['products'];	
		/* echo "<pre>";
		print_r($post);
		exit;   */
		$record = $post['record'];
		unset($post['record']);
		
		foreach($sproducts as $k=>$pid)
		{
			if(in_array($pid,$products))
				continue;
			$pdata = $this->getData($pid);
			$rs = mysqli_query($this->conn,"update jew_jewelry set outward = '' where id =".$pid);
			if(!$rs)
			{
				$rs = mysqli_error();
				//break;
			}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'lab_remove';
				$history['party'] = $post['party'];		
				$history['date'] = $post['date'];
				$history['description'] = "Jewelry remove from Lab Work. Lab Number :".$post['invoiceno'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $post['invoiceno'];
				$history['entry_from'] ='lab';
				$history['for_history'] = 'jewelry';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
		}
		$data = $this->helper->getUpdateString($post);
		$sql = " UPDATE jewelry_lab SET ".$data." WHERE id=".$post['id'];		
	    $rs = mysqli_query($this->conn,$sql);
	    if(!$rs)
		{
			return mysqli_error();
			
		}
		else
		{
			foreach($record as $r)
			{
				unset($r['sku']);
				if(isset($r['lab']))
				{
					$r['outward'] = 'lab';
					$rdata = $this->helper->getUpdateString($r);	
					$sql = "UPDATE jew_jewelry SET ".$rdata." WHERE id=".$r['id'];		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						//break;
					}
					$pdata = $this->getData($r['id']);
					$history =array();
					$history['product_id'] = $r['id'];
					$history['action'] = 'lab';
					$history['party'] = $post['party'];		
					$history['date'] = $post['date'];
					$history['description'] = "Jewelry send to Lab Work. Lab Number :".$post['invoiceno'];
					$history['pcs'] = '';
					$history['carat'] = '';
					$history['amount'] = '';
					$history['price'] = '';
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'dr';
					$history['invoice'] = $post['invoiceno'];
					$history['entry_from'] ='lab';
					$history['for_history'] = 'jewelry';	
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}	
				}
				
			}
		}		
		return $rs;
    }
	public function deleteLab($post)
    {
		$Lid = $post['id'];
		$rs=0;
		$oData = $this->getDataLab($Lid);
		$products = explode(",",$oData['products_receive']);
		foreach($products as $k=>$pid)
		{
			$pdata = $this->getData($pid);
			$rs = mysqli_query($this->conn,"update jew_jewelry set outward = '' where id =".$pid);
			if(!$rs)
			{
				$rs = mysqli_error();
				//break;
			}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'lab_close';
				$history['party'] = $oData['party'];		
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Jewelry remove from Lab Work. Lab Number :".$oData['invoiceno'];
				$history['pcs'] = '';
				$history['carat'] = '';
				$history['amount'] ='';
				$history['price'] = '';
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $oData['invoiceno'];
				$history['entry_from'] ='lab';
				$history['for_history'] = 'jewelry';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
		}
		$rs = mysqli_query($this->conn,"update jewelry_lab set deleted = 1 where id =".$Lid);
		if(!$rs)
		{
			$rs = mysqli_error();
			//break;
		}
		return $rs;
	}
	public function getDataCollet($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_collet WHERE deleted=0 and company=".$_SESSION['companyId']." and product_id=".$id );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		return $data;
	}
}
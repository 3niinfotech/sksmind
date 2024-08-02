<?php
include_once('../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
include_once('../../jHelper.php');
class jobworkModel
{
    public $table;
    public $table_repair;
    public $table_repair_product;
	public $table_product;
	public $table_product_value;
	public $lab;
	public $helper;
	private $conn;
	function __construct($db)
    {
        try {
			$this->conn = $db;
            $this->table  = "jew_job";
            $this->table_repair  = "jew_repair";
            $this->table_repair_product  = "jew_repair_product";
			$this->table_product  = "jew_product";
			
			$this->helper  = new Helper($db);
			$this->jhelper  = new jHelper($db);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getMyInventory()
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']);
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] =  $row;
		}
		
		return  $data;			
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
	
	public function jobWorkParty($no)
	{
		$rs = mysqli_query($this->conn,"select * from create_job where company=".$_SESSION['companyId']." and id = '".$no."'");
		$data=array();
		while($row=mysqli_fetch_array($rs))
		{
			$data = $row;
		}
		return $data;
	}
	public function sendToJob($post)
	{
		/*    echo "<pre>";
		 print_r($post);
		 exit;  */
		//$post['date'] = date("Y-m-d H:i:s");
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$amount = 0;
		//$incre_id = $this->getIncrementEntry('job_no');
		$incre_id = $post['entryno'];
		
		$return = mysqli_query($this->conn,"update create_job set is_returned = 0 where id=".$incre_id);
		
		$jparty = $this->jobWorkParty($incre_id);
		$post['party'] = $jparty['party'];
		if($post['job'] == 'jewelry')
		{
			$out = 'jewelry_making';	
				
		
			$collet_stone  = explode(",",$post['collet_stone']);
			$side_stone  = ($post['side_stone'] != "")?explode(",",$post['side_stone']):array();
			$main_stone  = ($post['main_stone'] != "")?explode(",",$post['main_stone']):array();
			//print_r($collet_stone);
			
			// itterate all product which seleted for outward
				if(!empty($side_stone))
				{
					/* if($pid =='')
						continue; */
					$record = $OutProducts = array();
					if(isset($post['srecord']))
					{
						$record = $post['srecord'];
						unset($post['srecord']);
					}
					/* $pdata = $this->getSideProductDetail($pid);
					//$OutProducts[] =  $pid;
					$sql = "UPDATE jew_loose_product SET visibility=0,outward='$out' WHERE id=".$pid;		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					$history =array();
					//$history =array();
					$history['product_id'] = $pid;
					$history['action'] = 'job_jewelry';
					$history['party'] = $post['party'];		
					//$history['narretion'] = $post['narretion'];
					$history['date'] = $post['date'];
					$history['description'] = "Loose Send to Job Work. Job Number :".$incre_id;
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] =$pdata['amount'];
					$history['price'] = $pdata['price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'dr';
					$history['invoice'] = $incre_id;;
					$history['entry_from'] ='job';
					$history['for_history'] = 'side';	
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}			
					$amount += (float)$pdata['amount']; */
					$i=1;
					foreach($record  as $k=>$rec)
					{
						$pid = $rec['id'];
						$edata = $this->getSideProductDetail($pid);			
						if(isset($record[$i]) && $record[$i]['carat'] !="" && $record[$i]['carat'] != $edata['carat'] )
						{
						
						//$edata = $this->getProductDetail($pid);
						
						if( $record[$i]['price'] =="" || $record[$i]['amount'] =="" )
								return "Please Enter price and amount of carat for SKU : ".$edata['sku'];
					
						if( $record[$i]['pcs'] > $edata['pcs'])
								return  "Pcs is exceed than stock PCS For SKU : ".$edata['sku'];
								
						if($record[$i]['carat'] > $edata['carat'] )
								return  "Carat is exceed than stock carat For SKU : ".$edata['sku'];
							
						}
						$i++;
					}
				
				// itterate all product which seleted for outward
				$i=1;
				foreach($record  as $k=>$rec)
				{
					
					$pid = $rec['id']; 
					$edata = $this->getSideProductDetail($pid);
					
					//check carat and pcs of outward data with exiesting data if both are diferent than create new products
					if(isset($record[$i]) && $record[$i]['carat'] !="" )
					{
						if( $record[$i]['price'] =="" || $record[$i]['amount'] =="" )
							return "Please Enter price and amount of carat";
						
						//create seperation from box / parcel for outward	
						$rid = $this->separateJewelry($record[$i],$edata,$out,$post);
						if(is_numeric($rid))
						{
							$OutProducts[] =  $rid;
							$amount += (float)$record[$i]['amount']; 
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
						$sql = "UPDATE jew_loose_product SET outward='$out',visibility = 0 WHERE id=".$pid;		
						$rs = mysqli_query($this->conn,$sql);
						if(!$rs)
						{
							$rs = mysqli_error();
							return $rs;
						}
						
						$history =array();
						$history['product_id'] = $pid;
						$history['action'] = 'job_jewelry';
						$history['party'] = $post['party'];		
						$history['date'] = $post['date'];
						$history['description'] = "Loose Send to Job Work. Job Number :".$incre_id;
						$history['pcs'] = $pdata['pcs'];
						$history['carat'] = $pdata['carat'];
						$history['amount'] = ($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount'];
						$history['price'] = ($pdata['sell_price'] == 0)?$pdata['price']:$pdata['sell_price'];
						$history['sku'] = $pdata['sku'];
						$history['type'] = 'dr';
						$history['invoice'] = $incre_id;
						$history['entry_from'] ='product';
						$history['for_history'] = 'side';
						$rs = $this->jhelper->addHistory($history);
						if(!is_numeric($rs) && $rs!=1)
						{
							return $rs;	
						}
						$amount += (float)($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount']; 		
					}
					$i++;
				}
				$post['side_stone'] = implode(",",$OutProducts);
			}
			$i=0;
			if(!empty($main_stone))
			{	
				$record = $post['record'];
				unset($post['record']);
				$mrecord = $post['mrecord'];
				unset($post['mrecord']);
			foreach($main_stone as $k=>$pid)
			{
				$i++;
				if($pid =='')
					continue;
				$pdata = $this->getProductDetail($pid);
				//$OutProducts[] =  $pid;
				$sql = "UPDATE ".$this->table_product." SET visibility=0,outward='$out',price=".$mrecord[$pid]['price'].",amount=".$mrecord[$pid]['amount']." WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$history =array();
				//$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'job_jewelry';
				$history['party'] = $post['party'];		
				//$history['narretion'] = $post['narretion'];
				$history['date'] = $post['date'];
				$history['description'] = "Stone Send to Job Work. Job Number :".$incre_id;
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$mrecord[$pid]['amount'];
				$history['price'] = $mrecord[$pid]['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['invoice'] = $incre_id;
				$history['entry_from'] ='job';
				$history['for_history'] = 'main';	
				$record[$i]['pid'] = $pid;
				$record[$i]['sku'] = $pdata['sku'];
				//$history['entryno'] ='job';
				
				
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}			
				$amount += (float)($pdata['sell_amount'] == 0)?$pdata['amount']:$pdata['sell_amount'];
			}
				$colleArray = array();
				foreach($record as $r)
				{
				$colleArray['product_id']= $r['pid'];
				$colleArray['type'] = 'collet_send';
				$colleArray['sku']= $r['sku'];
				$colleArray['collet_kt'] = $r['gold'];
				$colleArray['collet_color'] = $r['gold_color'];
				$colleArray['gross_weight'] = $r['gross_weight'];
				$colleArray['net_weight'] = $r['net_weight'];
				$colleArray['pg_weight'] = $r['pg_weight'];
				$colleArray['collet_rate'] = $r['rate'];
				$colleArray['collet_amount'] = $r['amount'];
				$colleArray['other_code'] = $r['other_code'];
				$colleArray['other_amount'] = $r['other_amount'];
				$colleArray['labour_rate'] = $r['labour_rate'];
				$colleArray['labour_amount'] = $r['labour_amount'];
				$colleArray['total_amount'] = $r['total_amount'];
				$colleArray['date'] = $post['date'];
				$colleArray['user'] = $post['user'];
				$colleArray['company'] = $post['company'];
				$data1 = $this->helper->getInsertString($colleArray);	
				$sql = "INSERT INTO jew_collet (". $data1[0].") VALUES (".$data1[1].")";		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				unset($colleArray);
				
			}
			}
			foreach($collet_stone as $k=>$pid)
			{
				
				if($pid =='')
					continue;
				
				$pdata = $this->getProductDetail($pid);
				//print_r($pdata);
				//$OutProducts[] =  $pid;
				$sql = "UPDATE ".$this->table_product." SET visibility=0,outward='$out' WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$history =array();
				//$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'job_jewelry';
				$history['party'] = $post['party'];		
				//$history['narretion'] = $post['narretion'];
				$history['date'] = $post['date'];
				$history['description'] = "Collet Send to Job Work. Job Number :".$incre_id;
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['invoice'] = $incre_id;;
				$history['entry_from'] ='job';
				$history['for_history'] = 'main';	
				
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}			
				$amount += (float)$pdata['amount']; 							
			}
		}
		else
		{
			$out = 'collet_making';	
			
			
		
			$record = array();
			/* if(isset($post['record']))
			{
				$record = $post['record'];
				unset($post['record']);
			} */
			$balance = array();
		
			//unset($post['on_payment']);		
			
		//$post['main_stone'] = implode(",",$post['main_stone']);
		
			
			$amount = 0;
			
			/* foreach($main_stone as $k=>$pid)
			{ */
			$pid = $post['main_stone'];
					//continue;
			$ms	= explode(',',$post['main_stone']);	
			if(count($ms) > 1)
			{
				return "Please Select only 1 main stone for Collet Making";
			}
				
				$pdata = $this->getProductDetail($pid);
				$sql = "UPDATE ".$this->table_product." SET is_collet=1,visibility=0,outward='$out' WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$history =array();
				//$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'job_collet';
				$history['party'] = $post['party'];		
				//$history['narretion'] = $post['narretion'];
				$history['date'] = $post['date'];
				$history['description'] = "Stone Send to Job Work. Job Number :".$incre_id;
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['invoice'] = $incre_id;
				$history['entry_from'] ='job';
				$history['for_history'] = 'main';	
				//$history['entryno'] ='job';
				
				
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}			
				$amount += (float)$pdata['amount']; 

				$colleArray = array();
				$colleArray['product_id']= $pid;
				$colleArray['type'] = 'collet_send';
				$colleArray['sku']= $pdata['sku'];
				$colleArray['collet_kt'] = $post['gold'];
				$colleArray['collet_color'] = $post['gold_color'];
				$colleArray['gross_weight'] = $post['gross_weight'];
				$colleArray['net_weight'] = $post['net_weight'];
				$colleArray['pg_weight'] = $post['pg_weight'];
				$colleArray['collet_rate'] = $post['rate'];
				$colleArray['collet_amount'] = $post['amount'];
				$colleArray['other_code'] = $post['other_code'];
				$colleArray['other_amount'] = $post['other_amount'];
				$colleArray['labour_rate'] = $post['labour_rate'];
				$colleArray['labour_amount'] = $post['labour_amount'];
				$colleArray['total_amount'] = $post['total_amount'];
				$colleArray['date'] = $post['date'];
				$colleArray['user'] = $post['user'];
				$colleArray['company'] = $post['company'];
				$data1 = $this->helper->getInsertString($colleArray);	
				$sql = "INSERT INTO jew_collet (". $data1[0].") VALUES (".$data1[1].")";		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			/* } */
			
			$post['due_amount'] = $post['total_amount'];
			$post['final_amount'] = $post['total_amount'];
						
		}
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		
		$Oid =  mysqli_insert_id($this->conn);
		//$incre_id++;
		
		
		/* $sql = "UPDATE jew_incrementid  SET job_no='$incre_id' WHERE company=".$_SESSION['companyId'];		
		
		$rs = mysqli_query($this->conn,$sql);
		 */
		$rs = $Oid;				
		
		return $rs;
	}
	public function separateJewelry($v,$edata,$type,$post)
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
		
		
		$v['visibility'] = 0;
	
		
		/* if(isset($v['price']))
		{
			$v['sell_price'] = $v['price'];
			$v['sell_amount'] = $v['amount'];
		} */	
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
			
			$prid = mysqli_insert_id($this->conn);					
			$history =array();
			$history['product_id'] = $prid;
			$history['action'] = 'unboxing';			
			$history['party'] = $post['party'];		
			$history['date'] = $post['date'];
			$history['description'] = $type." with Unboxing from ".$edata['sku']." with  carat :".$v['carat'];
			$history['pcs'] = $v['pcs'];
			$history['carat'] = $v['carat'];
			$history['amount'] = $v['amount'];
			$history['price'] = $v['price'];
			$history['sku'] = $v['sku'];
			$history['type'] = 'cr';
			$history['invoice'] = $post['entryno'];
			$history['entry_from'] = 'sjewelry';
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
			$history['action'] = 'job_jewelry';
			$history['party'] = $post['party'];		
			$history['date'] = $post['date'];
			$history['description'] = $type." with carat :".$tc;
			$history['pcs'] = $v['pcs'];
			$history['carat'] = $v['carat'];
			$history['amount'] = $v['amount'];
			$history['price'] = $v['price'];
			$history['sku'] = $edata['sku'];
			$history['type'] = 'dr';
			$history['invoice'] = $post['entryno'];
			$history['entry_from'] = 'sjewelry';			
			$history['for_history'] = 'side';			
			$rs = $this->jhelper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}	
			return $prid;
		}			
	}
	public function updatesendToJob($post){
		/* echo "<pre>";
		print_r($post);
		exit; */
		$id = $post['id'];
		$jparty = $this->jobWorkParty($post['entryno']);
		$post['party'] = $jparty['party'];
				if($post['job'] == 'jewelry')
				{
					$main_stone  = ($post['main_stone'] != "")?explode(",",$post['main_stone']):array();
		
					$i=0;
					if(!empty($main_stone))
					{	
						$record = $post['record'];
						unset($post['record']);
						$mrecord = $post['mrecord'];
						unset($post['mrecord']);
					foreach($main_stone as $k=>$pid)
					{
						$i++;
						if($pid =='')
							continue;
						$pdata = $this->getProductDetail($pid);
						//$OutProducts[] =  $pid;
						if($pdata['price'] != $mrecord[$pid]['price'])
						{	
							$sql = "UPDATE ".$this->table_product." SET price=".$mrecord[$pid]['price'].",amount=".$mrecord[$pid]['amount']." WHERE id=".$pid;		
							$rs = mysqli_query($this->conn,$sql);
							if(!$rs)
							{
								$rs = mysqli_error();
								return $rs;
							}
							$history =array();
							//$history =array();
							$history['product_id'] = $pid;
							$history['action'] = 'stone_update';
							$history['party'] = $post['party'];		
							//$history['narretion'] = $post['narretion'];
							$history['date'] = $post['date'];
							$history['description'] = "Stone Edit from Job Work. Job Number :".$post['entryno'];
							$history['pcs'] = "";
							$history['carat'] = "";
							$history['amount'] =$mrecord[$pid]['amount'];
							$history['price'] = $mrecord[$pid]['price'];
							$history['sku'] = $pdata['sku'];
							$history['type'] = 'dr';
							$history['invoice'] = $post['entryno'];
							$history['entry_from'] ='job';
							$history['for_history'] = 'main';	
							
							$rs = $this->jhelper->addHistory($history);
							if(!is_numeric($rs) && $rs!=1)
							{
								return $rs;	
							}
						}
						$record[$i]['pid'] = $pid;
					}
						$colleArray = array();
						foreach($record as $r)
						{
							$colleArray['collet_kt'] = $r['gold'];
							$colleArray['collet_color'] = $r['gold_color'];
							$colleArray['gross_weight'] = $r['gross_weight'];
							$colleArray['net_weight'] = $r['net_weight'];
							$colleArray['pg_weight'] = $r['pg_weight'];
							$colleArray['collet_rate'] = $r['rate'];
							$colleArray['collet_amount'] = $r['amount'];
							$colleArray['other_code'] = $r['other_code'];
							$colleArray['other_amount'] = $r['other_amount'];
							$colleArray['labour_rate'] = $r['labour_rate'];
							$colleArray['labour_amount'] = $r['labour_amount'];
							$colleArray['total_amount'] = $r['total_amount'];
							$colleArray['date'] = $post['date'];
							$data = $this->helper->getUpdateString($colleArray);
									
							$sql = "UPDATE jew_collet SET ".$data." WHERE product_id=".$r['pid'];
							$rs = mysqli_query($this->conn,$sql);
							if(!$rs)
							{
								$rs = mysqli_error();
								return $rs;
							}
							unset($colleArray);
						
						}
					}
		$amount = 0.0;
		$temp = array();
		if(isset($post['srecord']))
		{
			$record = $post['srecord'];
			unset($post['srecord']);
			$sside_stone  = ($post['side_stone'] != "")?explode(",",$post['side_stone']):array();
		foreach($record as $k=>$pdata)
		{
			/* if($pdata['price'] == "" && $pdata['carat'] == "")
				continue; */
			
			$Tpcs = 0;
			$Tcarat = 0.0;	
			//$pid  = $pdata['id'];
			
			$amount += (float)($pdata['amount']);
				
			$BaseData = array();
			foreach($sside_stone as $pid)
			{
			/* if(in_array($pid,$sside_stone))
			{ */
				$oldData =  $this->getSideProductDetail($pid);
				$pdata['sku'] = $oldData['sku']; 
				if($oldData['outward_parent']!="" && $oldData['outward_parent']!=0)
				{
					$BaseData =  $this->getSideProductDetail($oldData['outward_parent']);					
						$Tpcs = $oldData['pcs'] - $pdata['pcs'];
						$BaseData['pcs'] += $Tpcs;						
					$Tcarat = $oldData['carat'] - $pdata['carat'];
					$BaseData['carat'] += $Tcarat;				
					$BaseData['amount'] = $BaseData['carat'] * $BaseData['price'];				
				}
			/*}
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
			/*} */	
			
			if(!empty($pdata))
			{	
				
				$pdata['visibility'] = 0;	
				$data = $this->helper->getUpdateString($pdata);		
				$sql = "UPDATE jew_loose_product SET ".$data." WHERE id=".$pid;
				$rs = mysqli_query($this->conn,$sql);				
				if(!$rs)
				{
					return mysqli_error();
				}
				
				$history = array();    
				$history['product_id'] = $pid;				
				$history['action'] = 'job_jewelry';
				$history['type'] = 'dr';
				$history['description'] = "Stone updated";									
				$history['party'] = $post['party'];		
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] = $pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];				
				$history['entry_from'] = 'job';
				$history['invoice'] = $post['entryno'];
				$history['entry_from'] = 'job';
				$history['entryno'] = $post['id'];	
				$history['for_history'] = 'side';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
			}
			/* else
			{
				 $pdata  = $temp;
			} */
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
					$history['action'] = 'job_jewelry';
					$history['type'] = 'dr';
					$history['description'] = "Stone send to job with entry no is ".$post['entryno'];	
				elseif($Tcarat > 0):
					$history['action'] = 'job_return';
					$history['type'] = 'cr';
					$history['description'] = "Stone return with invoice no is ".$post['entryno'];
				else:
					$history['action'] = 'job_jewelry';
					$history['type'] = 'cr';
					$history['description'] = "Stone detail updated";
				endif;
				$pdata['amount'] = $pdata['price'] * abs($Tcarat);
				$history['party'] = $post['party'];		
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = abs($Tpcs);
				$history['carat'] = abs($Tcarat);
				$history['amount'] = $pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $BaseData['sku'];				
				$history['invoice'] = $post['entryno'];
				$history['entry_from'] = 'job'; 
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
		}
					$post['due_amount'] = $post['total_amount'];
					$post['final_amount'] = $post['total_amount'];
					
					$data = $this->helper->getUpdateString($post);
					$sql = "UPDATE jew_job SET ".$data." WHERE id=".$id;
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
				}	
				else
				{
							$colleArray = array();
							$colleArray['collet_kt'] = $post['gold'];
							$colleArray['collet_color'] = $post['gold_color'];
							$colleArray['gross_weight'] = $post['gross_weight'];
							$colleArray['net_weight'] = $post['net_weight'];
							$colleArray['pg_weight'] = $post['pg_weight'];
							$colleArray['collet_rate'] = $post['rate'];
							$colleArray['collet_amount'] = $post['amount'];
							$colleArray['other_code'] = $post['other_code'];
							$colleArray['other_amount'] = $post['other_amount'];
							$colleArray['labour_rate'] = $post['labour_rate'];
							$colleArray['labour_amount'] = $post['labour_amount'];
							$colleArray['total_amount'] = $post['total_amount'];
							$colleArray['date'] = $post['date'];
							
							$data = $this->helper->getUpdateString($colleArray);
							$return="";
							if(isset($post['return']))
							{
								$return = " and type='collet_receive'";
							}	
							
							$sql = "UPDATE jew_collet SET ".$data." WHERE product_id=".$post['main_stone'].$return;
							$rs = mysqli_query($this->conn,$sql);
							if(!$rs)
							{
								$rs = mysqli_error();
								return $rs;
							}
					
					$post['due_amount'] = $post['total_amount'];
					$post['final_amount'] = $post['total_amount'];
					unset($post['return']);
					$data = $this->helper->getUpdateString($post);
					$sql = "UPDATE jew_job SET ".$data." WHERE id=".$id;
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
				}	
		$rs = $id;	
		return $rs;
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
	
	public function colletToReturn($post)
	{
		$memoId = $post['memo_id'];
		unset($post['memo_id']);
		$pid = $post['main_stone'];
		$memoData = $this->getJobworkData($memoId);
		$cid = $_SESSION['companyId'];
		$rs = 0;
		$pdata = array();
				$pdata['outward'] = 'collet';				
				$pdata['visibility'] = 1;
				$pdata['is_collet'] = 1;
				$prodata = $this->getProductDetail($pid);
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
				$history['action'] = 'collet_return';
				$history['party'] = $memoData['party'];		
				$history['narretion'] = $memoData['narretion'];
				$history['date'] = $post['return_date'];
				$history['description'] = "STONE RETURN  AS COLLET FROM JOB WORK. JOB NUMBER :".$memoData['entryno'];
				$history['pcs'] = $prodata['pcs'];
				$history['carat'] = $prodata['carat'];
				$history['amount'] =$prodata['amount'];
				$history['price'] = $prodata['price'];
				$history['sku'] = $prodata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $memoData['entryno'];
				$history['entry_from'] = 'collet';
				$history['entryno'] = $memoId;
				$history['for_history'] = 'main';					
				$rs = $this->jhelper->addHistory($history);
		
		$post['is_returned'] = 1;
		$post['due_amount'] = $post['total_amount'];
		$post['final_amount'] = $post['total_amount'];
		$data = $this->helper->getUpdateString($post);		
		$sql = "UPDATE jew_job SET ".$data." WHERE id=".$memoId;
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
				$colleArray = array();
				$colleArray['product_id']= $pid;
				$colleArray['type'] = 'collet_receive';
				$colleArray['sku']= $prodata['sku'];
				$colleArray['collet_kt'] = $post['gold'];
				$colleArray['collet_color'] = $post['gold_color'];
				$colleArray['gross_weight'] = $post['gross_weight'];
				$colleArray['net_weight'] = $post['net_weight'];
				$colleArray['pg_weight'] = $post['pg_weight'];
				$colleArray['collet_rate'] = $post['rate'];
				$colleArray['collet_amount'] = $post['amount'];
				$colleArray['other_code'] = $post['other_code'];
				$colleArray['other_amount'] = $post['other_amount'];
				$colleArray['labour_rate'] = $post['labour_rate'];
				$colleArray['labour_amount'] = $post['labour_amount'];
				$colleArray['total_amount'] = $post['total_amount'];
				$colleArray['date'] = $post['return_date'];
				$colleArray['user'] = $memoData['user'];
				$colleArray['company'] = $memoData['company'];
				 $data1 = $this->helper->getInsertString($colleArray);	
				$sql = "INSERT INTO jew_collet (". $data1[0].") VALUES (".$data1[1].")";		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				} 
			$return = mysqli_query($this->conn,"select * from jew_job where is_returned = 0 and deleted = 0 and entryno =".$memoData['entryno']);
			$c = mysqli_num_rows($return);
			if($c == 0)
			{
				$rs = mysqli_query($this->conn,"update create_job set is_returned = 1 where id =".$memoData['entryno']);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				} 
			}
		return $rs;	
	}
	
	public function memoToReturn($post)
	{
		/* echo "<pre>";
		print_r($post);
		exit; */
		$date = $post['return_date'];
		unset($post['return_date']);
		$collet_stone  = explode(",",$post['collet_stone']);
		$side_stone  = explode(",",$post['side_stone']);
		$main_stone  = explode(",",$post['main_stone']);
		$memoId = $post['memo_id'];
		$memoData = $this->getJobworkData($memoId);	
		$out = 'jewelry';
		$post['date'] = $date;
		$post['due_amount'] = $post['total_amount'];
		$post['final_amount'] = $post['total_amount'];
		$post['jew_type'] = $memoData['jew_type'];
		$post['jew_design'] = $memoData['jew_design'];
		//$post['memo_maker'] = $memoData['memo_maker'];
		$post['user'] = $memoData['user'];
		$post['party'] = $memoData['party'];
		$post['company'] = $memoData['company'];
		$post['memo_id'] = $memoId;
		$post['sell_price'] = $post['total_amount']/85*100;
		$post['selling_price'] = $post['sell_price']/50*100;
		$post['visibility'] = 1;
		 $spcs = $post['side_pcs'];
		 unset($post['side_pcs']);
		 $scarat = $post['side_carat'];
		 unset($post['side_carat']);
		 $sprice = $post['side_price'];
		 unset($post['side_price']);
		 $samount = $post['side_amount'];
		 unset($post['side_amount']);
		 $record = array();
		if(isset($post['srecord']))
		{
			$record = $post['srecord'];
			unset($post['srecord']);
		}
		$data1 = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO jew_jewelry (". $data1[0].") VALUES (".$data1[1].")";
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		} 
		$Oid =  mysqli_insert_id($this->conn);
		foreach($main_stone as $k=>$pid)
		{
				if($pid =='')
					continue;
			$pdata = $this->getProductDetail($pid);	
			$sql = "UPDATE ".$this->table_product." SET jewelry_id=$Oid,outward='$out' WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'job_return';
				$history['party'] = $post['party'];		
				//$history['narretion'] = $post['narretion'];
				$history['date'] = $date;
				$history['description'] = "Main Stone Return to Job Work. Job Number :".$memoData['entryno'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $memoData['entryno'];
				$history['entryno'] = $memoId;
				$history['entry_from'] ='jewelry';
				$history['for_history'] = 'main';	
				
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}			
		}
		
		foreach($collet_stone as $k=>$pid)
		{
				if($pid =='')
					continue;
			$pdata = $this->getProductDetail($pid);	
			$sql = "UPDATE ".$this->table_product." SET jewelry_id=$Oid,outward='$out' WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'job_return';
				$history['party'] = $post['party'];		
				//$history['narretion'] = $post['narretion'];
				$history['date'] = $date;
				$history['description'] = "Collet Return to Job Work. Job Number :".$memoData['entryno'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $memoData['entryno'];
				$history['entryno'] = $memoId;
				$history['entry_from'] ='jewelry';
				$history['for_history'] = 'main';	
				
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}			
		}
		
		foreach($side_stone as $k=>$pid)
		{
				if($pid =='')
					continue;
			$pdata = $this->getSideProductDetail($pid);
			$parentdata = $this->getSideProductDetail($pdata['outward_parent']);
			if($pdata['carat'] == $record[$pid]['carat'])
			{	
				$sql = "UPDATE jew_loose_product SET jewelry_id=$Oid,outward='$out',carat=".$record[$pid]['carat'].",price=".$record[$pid]['price'].",amount=".$record[$pid]['amount'].",pcs=".$record[$pid]['pcs']." WHERE id=".$pid;
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'job_return';
				$history['party'] = $post['party'];		
				//$history['narretion'] = $post['narretion'];
				$history['date'] = $date;
				$history['description'] = "Side Stone Return to Job Work. Job Number :".$memoData['entryno'];
				$history['pcs'] = $record[$pid]['pcs'];
				$history['carat'] = 0;
				$history['amount'] =$record[$pid]['amount'];
				$history['price'] = $record[$pid]['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $memoData['entryno'];
				$history['entryno'] = $memoId;
				$history['entry_from'] ='jewelry';
				$history['for_history'] = 'side';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}			
			}
			else
			{
				$c = $pdata['carat'] - $record[$pid]['carat'];
				$amt = $c * $record[$pid]['price'];
				$sql = "UPDATE jew_loose_product SET jewelry_id=$Oid,outward='$out',carat=carat-$c,price=".$record[$pid]['price'].",amount=$amt,pcs=".$record[$pid]['pcs']." WHERE id=".$pid;
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'job_return';
				$history['party'] = $post['party'];		
				//$history['narretion'] = $post['narretion'];
				$history['date'] = $date;
				$history['description'] = "Side Stone Return to Job Work. Job Number :".$memoData['entryno'];
				$history['pcs'] = $record[$pid]['pcs'];
				$history['carat'] = $c;
				$history['amount'] =$record[$pid]['amount'];
				$history['price'] = $record[$pid]['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['invoice'] = $memoData['entryno'];
				$history['entryno'] = $memoId;
				$history['entry_from'] ='jewelry';
				$history['for_history'] = 'side';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}			
				$pamt = $c * $parentdata['price'];
				$sql = "UPDATE jew_loose_product SET carat=carat+$c,amount=$pamt WHERE id=".$parentdata['id'];
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$history =array();
				$history['product_id'] = $parentdata['id'];
				$history['action'] = 'job_return';
				$history['party'] = $post['party'];		
				//$history['narretion'] = $post['narretion'];
				$history['date'] = $date;
				$history['description'] = "Side Stone Return to Job Work. Job Number :".$memoData['entryno'];
				$history['pcs'] = $parentdata['pcs'];
				$history['carat'] = $c;
				$history['amount'] =$pamt;
				$history['price'] = $parentdata['price'];
				$history['sku'] = $parentdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $memoData['entryno'];
				$history['entryno'] = $memoId;
				$history['entry_from'] ='jewelry';
				$history['for_history'] = 'side';	
				
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
			}	
		}
		unset($post['memo_id']);
		unset($post['visibility']);
		$sku = $post['sku'];
		unset($post['sku']);
		unset($post['date']);
		unset($post['sell_price']);
		unset($post['selling_price']);
		$post['jewelry_id'] = $Oid;
		$post['is_returned'] = 1;
		$post['return_date'] = $date;
		$post['side_pcs']=$spcs;
		$post['side_carat']=$scarat;
		$post['side_price']=$sprice;
		$post['side_amount']=$samount;
		$data = $this->helper->getUpdateString($post);		
		$sql = "UPDATE jew_job SET ".$data." WHERE id=".$memoId;
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
				$history =array();
				$history['action'] = 'job_return';
				$history['product_id'] = $Oid;
				$history['party'] = $memoData['party'];		
				//$history['narretion'] = $post['narretion'];
				$history['date'] = $date;
				$history['description'] = "Job Return to Job Work. Job Number :".$memoData['entryno'];
				$history['type'] = 'cr';
				$history['carat'] = $post['gross_weight'];
				$history['amount'] =$post['total_amount'];
				$history['sku'] = $sku;
				$history['invoice'] = $memoData['entryno'];
				$history['entryno'] = $memoId;
				$history['entry_from'] ='jewelry';
				$history['for_history'] = 'jewelry';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}			
		$rs = $Oid;	
		$return = mysqli_query($this->conn,"select * from jew_job where is_returned = 0 and deleted = 0 and entryno =".$memoData['entryno']);
			$c = mysqli_num_rows($return);
			if($c == 0)
			{
				$rs = mysqli_query($this->conn,"update create_job set is_returned = 1 where id =".$memoData['entryno']);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				} 
			}
		return $rs;	
	}
	
	public function colletRepair($post)
	{
		$post['date'] = date("Y-m-d H:i:s");
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$amount = 0;
		
		$incre_id = $this->getIncrementEntry('repair');
		$post['entryno'] = $incre_id;	
	
	
		$out = 'collet_repair';	
		
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
		$post['main_stone'] = implode(",",$post['main_stone']);
		if(isset($post['side_stone']))
			$post['side_stone'] = implode(",",$post['side_stone']);
		else
			$post['side_stone'] ='';
		$OutProducts = array();
		$amount = 0;
		
		// itterate all product which seleted for outward
		foreach($products as $k=>$pid)
		{
			
			$pdata = $this->getProductDetail($pid);
			$OutProducts[] =  $pid;
			$sql = "UPDATE ".$this->table_product." SET in_repair=1,visibility=0,outward='$out' WHERE id=".$pid;		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
			$history =array();
			
			$history['product_id'] = $pid;
			$history['action'] = 'collet_repairr';
			$history['party'] = $post['party'];		
			$history['narretion'] = $post['narretion'];
			$history['date'] = $post['date'];
			$history['description'] = "Collet Send in Repair, Job Number :".$incre_id;
			$history['pcs'] = $pdata['pcs'];
			$history['carat'] = $pdata['carat'];
			$history['amount'] =$pdata['amount'];
			$history['price'] = $pdata['price'];
			$history['sku'] = $pdata['sku'];
			$history['type'] = 'dr';
			$history['invoice'] = $incre_id;;
			$history['entry_from'] ='collet_repair';
			$history['for_history'] = 'main';	
			
			$rs = $this->jhelper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}			
			$amount += (float)$pdata['amount']; 							
		}
		
		$post['due_amount'] = $amount;
		$post['final_amount'] = $amount;
		
		if(empty($OutProducts))
			return "Please select item or enter required value for box/parcel ";
			
		$post['products'] = implode(",",$OutProducts);			
		
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		
		$Oid =  mysqli_insert_id($this->conn);
		$incre_id++;
		
		$sql = "UPDATE jew_incrementid  SET repair='$incre_id' WHERE company=".$_SESSION['companyId'];		

		$rs = mysqli_query($this->conn,$sql);
		
		$rs = $Oid;				
		
		return $rs;
	}
	
	
	public function sendToRepair($post)
	{
		
		//$post['date'] = date("Y-m-d H:i:s");
		$incre_id = $this->getIncrementEntry('repair');
		
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$post['entryno'] = $incre_id;	
		$post['status'] = '1';	
		
		$products = $post['products'];
		
		$record = array();
		if(isset($post['product']))
		{
			$record = $post['product'];
			unset($post['product']);
		}
		
	
		//unset($post['on_payment']);		
		$post['products'] = implode(",",$products);			
		//$post['products'] = implode(",",$OutProducts);
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table_repair ." (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		
		$Oid =  mysqli_insert_id($this->conn);
		$incre_id++;
		
		$sql = "UPDATE jew_incrementid  SET repair='$incre_id' WHERE company=".$_SESSION['companyId'];		
		$rs = mysqli_query($this->conn,$sql);
		
		$rs = $Oid;				
		// itterate all product which seleted for repairing
		foreach($record as $k=>$pr)
		{
			$pr['repair_id'] = $Oid;
			$pr['product_id'] = $k;
			$data = $this->helper->getInsertString($pr);	
			$sql = "INSERT INTO ".$this->table_repair_product ." (". $data[0].") VALUES (".$data[1].")";		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
			$sql = "UPDATE jew_jewelry  SET in_repair=1 WHERE id=".$k;		
			$rs = mysqli_query($this->conn,$sql);	
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
			$pdata = $this->getProductDetail($pid);
			$sql = "update ".$this->table_product ." set outward ='$out' where id =".$pid;
			$rs = mysqli_query($this->conn,$sql);
			
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = 'lab';
				$history['party'] = $post['party'];		
				$history['date'] = $post['date'];
				$history['description'] = "Stone Send to Lab Work. Lab Number :".$iincre_id;
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'dr';
				$history['invoice'] = $iincre_id;
				$history['entry_from'] ='product';
				$history['for_history'] = 'main';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
		}
		
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO jew_lab (". $data[0].") VALUES (".$data[1].")";		
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
		
		
			
		if($edata['group_type']  =='box' )
			$edata['pcs']  -= $v['pcs'];
		
		$edata['carat']  -= $v['carat'];
		

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
		$v['site_upload'] = 1;
		$v['rapnet_upload'] = 1;
		$v['is_uploadsite'] = 0;
		$v['is_uploadrapnet'] = 0;
		$v['outward_parent'] = $edata['id'];
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
		$sql = "INSERT INTO jew_product (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)			
		{
			$rs = mysqli_error();
			return $rs;
		}
		else
		{
			$prid = mysqli_insert_id($this->conn);					
			$attr['product_id'] = $prid;
				unset($attr['pid']);
			$data = $this->helper->getInsertString($attr);	
			$sql = "INSERT INTO jew_product_value (". $data[0].") VALUES (".$data[1].")";		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)			
			{
				$rs = mysqli_error();
				return $rs;
			}
			
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
			
			$rs = $this->helper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}
			
			//$record = $edata['record'];
			unset($edata['record']);
				
				
			$data = $this->helper->getUpdateString($edata);	
			$sql = "UPDATE jew_product SET ".$data." WHERE id=".$edata['id'];		
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
	
	public function getOptionListEntryno($job)
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM create_job WHERE job='$job' and deleted=0 and company=".$_SESSION['companyId']." ORDER BY entryno");			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row['entryno'];
		}	
		return  $data;
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
	
	public function getDataLab($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_lab WHERE company=".$_SESSION['companyId']." and id=".$id );
			
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
	
	public function getData($id,$job='jewelry')
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
		else
		{
			if($job =='collet')
			{
				$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p  WHERE p.id IN (".$data['main_stone'].")" );
				
				while($row = mysqli_fetch_array($rs))
				{
					$data['record'][] = $row;
				}
			}
		}		
				
		return  $data;			
    }
	
	public function getRecordData($id)
    {
		$row = $this->getData($id);
		/* echo "<pre>";
		print_r($row);
		exit; */
		$main_stone =  $row['main_stone'];
		$side_stone =  $row['side_stone'];
		$collet_stone =  $row['collet_stone'];
	
		
		$temp = (array)$row;
		$temp['main_stone'] = array();
		$temp['side_stone'] = array();
		$temp['collet_stone'] = array();
		if($main_stone != "")
		{	
			foreach(explode(',',$main_stone) as $k=>$id )
			{
				if($id == '')
					continue;
				
				$rs1 = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." p  LEFT JOIN jew_collet c ON p.id = c.product_id WHERE c.deleted=0 and p.company=".$_SESSION['companyId']." and p.id=".$id);
				
				while($row1 = mysqli_fetch_assoc($rs1))
				{ 
					$temp['main_stone'][$id] =   (array)$row1;					
				}
			}
		}
		if($collet_stone != "")
		{
			foreach(explode(',',$collet_stone) as $k=>$id )
			{
				$rs1 = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." p  LEFT JOIN jew_collet c ON p.id = c.product_id WHERE c.deleted=0 and p.company=".$_SESSION['companyId']." and p.id=".$id);
			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					$temp['collet_stone'][$id] =   (array)$row1;					
				}
			}
		}
		if($side_stone != "")
		{
			foreach(explode(',',$side_stone) as $k=>$id )
			{
				$rs1 = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE id=".$id);
			
				while($row1 = mysqli_fetch_assoc($rs1))
				{
					$temp['side_stone'][$id] =   (array)$row1;					
				}
			}
		}
		return  $temp;			
    }
	public function getRepairData($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_repair ." WHERE company=".$_SESSION['companyId']." and id=".$id );
			
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
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_jewelry p  WHERE p.id IN (".$data['products'].")" );
			
			while($row = mysqli_fetch_assoc($rs))
			{
				$data['record'][] = $row;
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
						$edata['pcs']  += $pdata['pcs'];
					
					$edata['carat']  += $pdata['carat'];
					
					$edata['outward'] = "";
					unset($edata['record']);
					$data = $this->helper->getUpdateString($edata);	
					
					$sql = "UPDATE jew_product SET ".$data." WHERE id=".$edata['id'];		
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
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
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
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
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
					$Oid =  mysqli_insert_id($this->conn);
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
				/*$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
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
		$rs = mysqli_query($this->conn,"SELECT amount FROM jew_product WHERE id=".$id);
			
		$data = "0";
		while($row = mysqli_fetch_assoc($rs))
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
					$edata['pcs']  += $pdata['pcs'];
				
				$edata['carat']  += $pdata['carat'];
				
				$edata['outward'] = "";
				unset($edata['record']);
				$data = $this->helper->getUpdateString($edata);	
				
				$sql = "UPDATE jew_product SET ".$data." WHERE id=".$edata['id'];		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();				
				}
			
				$history =array();
				$history['product_id'] = $edata['id'];
				$history['action'] = $otype.'_return';
				$history['party'] = $post['party'];		
				$history['narretion'] = $post['narretion'];
				$history['date'] = date("Y-m-d H:i:s");;
				$history['description'] = "Stone ".$otype." return with reference no is ".$post['reference'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
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
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
				}				
			}
			else
			{
				$sql = "UPDATE ".$this->table_product." SET outward='',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
				
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();				
				}
				$history =array();
				$history['product_id'] = $pid;
				$history['action'] = $otype.'_return';
				$history['party'] = $post['party'];		
				$history['narretion'] = $post['narretion'];
				$history['date'] = date("Y-m-d H:i:s");;
				$history['description'] = "Stone ".$otype." return with reference no is ".$post['reference'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
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
			if($pdata['sku'] == "" && $pdata['carat'] == "")
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
						$Tpcs = $oldData['pcs'] - $pdata['pcs'];
						$BaseData['pcs'] += $Tpcs;						
					}
					$Tcarat = $oldData['carat'] - $pdata['carat'];
					$BaseData['carat'] += $Tcarat;				
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
						 $BaseData['pcs'] += ($oldData['pcs'] - $pdata['pcs']);
						
					$BaseData['carat'] += ($oldData['carat'] - $pdata['carat']);					*/
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
				$history['narretion'] = $post['narretion'];
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
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
		//echo "SELECT * FROM jew_product p LEFT JOIN jew_product_value  pv ON p.id = pv.product_id WHERE p.sku='".$sku."' and outward=''";	
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE p.sku='".$sku."'" );
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
			break;
		}
		
		return  $data;			
    }
	public function getjewData($id)
	{
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_jewelry WHERE id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		
		return  $data;	
	}
	public function deleteJob($post)
    {
		$Jid = $post['id'];
		$rs=0;
		$row = $this->getData($Jid);
		$collet_stone = ($row['collet_stone'] != "")?explode(",",$row['collet_stone']):array();
		$main_stone = ($row['main_stone'] != "")?explode(",",$row['main_stone']):array();
		$side_stone = ($row['side_stone'] != "")?explode(",",$row['side_stone']):array();
		
		if(isset($row['jewelry_id']) && $row['jewelry_id'] != 0)
		{
			mysqli_query($this->conn,"update jew_jewelry set deleted=1 WHERE id=".$row['jewelry_id']);
			$jdata = $this->getjewData($row['jewelry_id']);
				$history =array();
				$history['action'] = 'close_'.$row['job'];
				$history['product_id'] = $row['jewelry_id'];
				$history['party'] = $row['party'];		
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "jewelry deleted Job Number :".$row['entryno'];
				$history['type'] = 'dr';
				$history['carat'] = $jdata['gross_weight'];
				$history['amount'] =$jdata['total_amount'];
				$history['sku'] = $jdata['sku'];
				$history['invoice'] = $row['entryno'];
				$history['entry_from'] ='jewelry';
				$history['for_history'] = 'jewelry';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}			
		}
		
		if($main_stone != "")
		{	
			foreach($main_stone as $k=>$id )
			{
				$pdata = $this->getProductDetail($id);
				$rs = mysqli_query($this->conn,"update jew_collet set deleted=1 WHERE product_id=".$id);
				$rs = mysqli_query($this->conn,"update jew_product set visibility=1,outward='',is_collet=0,jewelry_id=0 WHERE id=".$id);
				$history =array();
				$history['product_id'] = $id;
				$history['action'] = 'close_'.$row['job'];
				$history['party'] = $row['party'];		
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Stone Deleted from Job Work. Job Number :".$row['entryno'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $row['entryno'];
				$history['entry_from'] ='job';
				$history['for_history'] = 'main';	
				//$history['entryno'] ='job';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
			}
		}
		if($collet_stone != "")
		{
			foreach($collet_stone as $k=>$id )
			{
				$pdata = $this->getProductDetail($id);
				$rs = mysqli_query($this->conn,"update jew_product set visibility=1,outward='collet',jewelry_id=0 WHERE id=".$id);
				$history =array();
				$history['product_id'] = $id;
				$history['action'] = 'close_'.$row['job'];
				$history['party'] = $row['party'];		
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Collet Deleted from Job Work. Job Number :".$row['entryno'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $row['entryno'];
				$history['entry_from'] ='job';
				$history['for_history'] = 'main';	
				$history['entryno'] = $Jid;
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
			}
		}
		if($side_stone != "")
		{
			foreach($side_stone as $k=>$pid )
			{
				/* $pdata = $this->getSideProductDetail($id);
				$rs = mysqli_query($this->conn,"update jew_loose_product p set visibility=1,outward='' WHERE id=".$id);
				$history =array();
				$history['product_id'] = $id;
				$history['action'] = 'close_'.$row['job'];
				$history['party'] = $row['party'];		
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Stone Deleted from Job Work. Job Number :".$row['entryno'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $row['entryno'];
				$history['entry_from'] ='job';
				$history['for_history'] = 'side';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}			 */
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
				$history['party'] = $row['party'];		
				$history['date'] = date("Y-m-d H:i:s");;
				$history['action'] = 'close_'.$row['job'];
				$history['type'] = 'cr';
				$history['description'] = "Stone Deleted from Job Work. Job Number :".$row['entryno'];	
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $edata['sku'];
				$history['invoice'] = $row['entryno'];
				$history['entry_from'] = 'job';
				$history['entryno'] = $Jid;		
				$history['for_history'] = 'side';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				$history =array();
				$history['product_id'] = $pdata['id'];
				$history['party'] = $row['party'];		
				$history['date'] = date("Y-m-d H:i:s");;
				$history['action'] = 'close_'.$row['job'];
				$history['type'] = 'dr';
				$history['description'] = "Stone Deleted from Job Work. Job Number :".$row['entryno'];	
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $pdata['sku'];
				$history['invoice'] = $row['entryno'];
				$history['entry_from'] = 'job';
				$history['entryno'] = $Jid;		
				$history['for_history'] = 'side';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
				$sql = "UPDATE jew_loose_product SET outward='',visibility=0,outward_parent=0,jewelry_id=0 WHERE  id=".$pid;		
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
				$history['party'] = $row['party'];		
				$history['date'] = date("Y-m-d H:i:s");
				$history['action'] = 'close_'.$row['job'];
				$history['type'] = 'cr';
				$history['description'] = "Stone Deleted from Job Work. Job Number :".$row['entryno'];;	
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] = ($pdata['sell_amount'] ==0) ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] ==0) ? $pdata['price'] : $pdata['sell_price'];
				$history['sku'] = $pdata['sku'];
				$history['invoice'] = $Odata['invoiceno'];
				$history['entry_from'] = 'outward';
				$history['entryno'] = $Jid;
				$history['for_history'] = 'side';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}				
			}
			}
		}
		$rs = mysqli_query($this->conn,"update jew_job set deleted=1 WHERE id=".$Jid);
		if(!$rs)
		{
			return mysqli_error();
		}
		$return = mysqli_query($this->conn,"select * from jew_job where is_returned = 0 and deleted = 0 and entryno =".$row['entryno']);
			$c = mysqli_num_rows($return);
			if($c == 0)
			{
				$rs = mysqli_query($this->conn,"update create_job set is_returned = 1 where id =".$row['entryno']);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				} 
			}
		return $rs;
    }
	
	public function deleteColletMaking($post)
    {
		$id = $post['id'];
		$rs=0;
		$Odata = $this->getData($id,'collet');
		
		$otype = $Odata['type'];
		foreach($Odata['record'] as $k=>$pdata)
		{
			
			$pid  = $pdata['id'];			
			$BaseData = array();
			
			if(!empty($pdata))
			{	
				$tdata['outward'] = '';				
				$tdata['visibility'] = 1;				
				
				
				//$sql = "UPDATE ".$this->table_product." SET outward='sale',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
				$data = $this->helper->getUpdateString($tdata);		
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;
				$rs = mysqli_query($this->conn,$sql);
				
				if(!$rs)
				{
					return mysqli_error();
				}
				
				$history = array();
				$history['product_id'] = $pid;				
				$history['sku'] = $pdata['sku'];				
				$history['action'] = "collet_close";
				$history['type'] = 'cr';
				$history['description'] = "Collet Closing / deleting  ";							
				$history['party'] = $Odata['party'];						
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = $pdata['pcs'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] = ($pdata['sell_amount'] == 0 || $pdata['sell_amount'] == '') ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] == 0 || $pdata['sell_price'] == '') ? $pdata['price'] : $pdata['sell_price'];
				$history['entry_from'] = 'collet_delete';
				$history['entryno'] = $Odata['id'];							
				$history['for_history'] = 'main';							
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
			}
			
			
		}
		$close = "collet_close";
		$sql = "UPDATE ".$this->table." SET deleted=1, status='".$close."' WHERE id=".$id;
		$rs = mysqli_query($this->conn,$sql);
		
		if(!$rs)
		{
			return mysqli_error();
		}
		return $rs;
    }
	
	public function getDataJOB($id)
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM create_job WHERE company=".$_SESSION['companyId']." and id=".$id );
			
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
		  }
		return $data;
	}
	
	public function getAllJobData()
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM create_job WHERE company=".$_SESSION['companyId']." and deleted=0");
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		return $data;
	}
	
	public function saveJob($post)
	{
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;	
		$post['user'] = $_SESSION['userid'];
		$incre_id = $this->getIncrementEntry('job_no');
		$post['entryno'] = $incre_id;
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO create_job (".$data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		$incre_id++;
		$sql = "UPDATE jew_incrementid  SET job_no='$incre_id' WHERE company=".$_SESSION['companyId'];		
		$rs = mysqli_query($this->conn,$sql);
		return $rs;
	}
	
	public function updateJob($post)
	{
		$id = $post['id'];
		$data = $this->helper->getUpdateString($post);		
				$sql = "UPDATE create_job SET ".$data." WHERE id=".$id;
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
				}
		return $rs;
	}
	
	public function deleteColletRepair($post)
    {
		$id = $post['id'];
		$rs=0;
		$Odata = $this->getData($id,'collet');
		
		$otype = $Odata['type'];
		foreach($Odata['record'] as $k=>$pdata)
		{
			
			$pid  = $pdata['id'];			
			$BaseData = array();
			
			if(!empty($pdata))
			{	
				$tdata['in_repair'] = 0;				
					$tdata['outward'] = "collet";				
					$tdata['visibility'] = 1;								
				
				
				//$sql = "UPDATE ".$this->table_product." SET outward='sale',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
				$data = $this->helper->getUpdateString($tdata);		
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;
				$rs = mysqli_query($this->conn,$sql);
				
				if(!$rs)
				{
					return mysqli_error();
				}
				
				$history = array();
				$history['product_id'] = $pid;				
				$history['sku'] = $pdata['sku'];				
				$history['action'] = "collet_repair_close";
				$history['type'] = 'cr';
				$history['description'] = "Collet Repairing Closing / Deleting  ";							
				$history['party'] = $Odata['party'];						
				$history['date'] = date("Y-m-d H:i:s");				
				$history['pcs'] = $pdata['pcs'];
				
				$history['carat'] = $pdata['carat'];
				$history['amount'] = ($pdata['sell_amount'] == 0 || $pdata['sell_amount'] == '') ? $pdata['amount'] : $pdata['sell_amount'];
				$history['price'] = ($pdata['sell_price'] == 0 || $pdata['sell_price'] == '') ? $pdata['price'] : $pdata['sell_price'];
				$history['entry_from'] = 'collet_repairing_delete';
				$history['entryno'] = $Odata['id'];							
				$history['for_history'] = 'main';							
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}
			}
			
			
		}
		$close = "collet_repair_close";
		$sql = "UPDATE ".$this->table." SET deleted=1, status='".$close."' WHERE id=".$id;
		$rs = mysqli_query($this->conn,$sql);
		
		if(!$rs)
		{
			return mysqli_error();
		}
		return $rs;
    }
	public function getInwardData($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_inward WHERE company=".$_SESSION['companyId']." and id=".$id );
			
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
			$sql = "UPDATE dai_inward SET ".$data1." WHERE id=".$id;
		$rs = mysqli_query($this->conn,$sql);		
		if(!$rs)
		{
			return mysqli_error();
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
			$edata = $this->getProductDetail($pid);
			
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
				$pdata = $this->getProductDetail($pid);
				$OutProducts[] =  $pid;
				$sql = "UPDATE ".$this->table_product." SET outward='$type',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
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
				$history['description'] = " Stone ".$type."  with reference no is ".$post['reference'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
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
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
			return $rs;
		}
		
		$Oid =  mysqli_insert_id($this->conn);
		$temp = explode("-",$incre_id);
		$temp[1] = $temp[1] + 1;
		$setNewid = $temp[0]."-".$temp[1];
		$invoice++;
		$sql = "UPDATE dai_incrementid  SET outward='$setNewid',invoice='$invoice' WHERE company=".$_SESSION['companyId'];		
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
				
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
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
		$tp += (float)$v['pcs'];
		$tc += (float)$v['carat'];
		$fromcarat = $edata['carat'];
		
			
		if($edata['group_type']  =='box' )
			$edata['pcs']  -= $v['pcs'];
		
		$edata['carat']  -= $v['carat'];
		

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
		$sql = "INSERT INTO jew_product (". $data[0].") VALUES (".$data[1].")";		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)			
		{
			$rs = mysqli_error();
			return $rs;
		}
		else
		{
			$prid = mysqli_insert_id($this->conn);					
			$attr['product_id'] = $prid;
				unset($attr['pid']);
			$data = $this->helper->getInsertString($attr);	
			$sql = "INSERT INTO jew_product_value (". $data[0].") VALUES (".$data[1].")";		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)			
			{
				$rs = mysqli_error();
				return $rs;
			}
			
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
			
			$rs = $this->helper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}
			
			//$record = $edata['record'];
			unset($edata['record']);
				
				
			$data = $this->helper->getUpdateString($edata);	
			$sql = "UPDATE jew_product SET ".$data." WHERE id=".$edata['id'];		
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
			$rs = $this->helper->addHistory($history);
			if(!is_numeric($rs) && $rs!=1)
			{
				return $rs;	
			}	
			return $prid;
		}			
	}
	
		
	public function removeCollet($post)
	{
		$rs = 1;
			// itterate all product which seleted for outward
			foreach($post['ids'] as $k=>$pid)
			{
				
				$pdata = $this->getProductDetail($pid);
				$OutProducts[] =  $pid;
				$sql = "UPDATE ".$this->table_product." SET visibility=1,outward='',is_collet=0 WHERE id=".$pid;		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
				$history =array();
				
				$history['product_id'] = $pid;
				$history['action'] = 'collet_return';
				$history['date'] = date("Y-m-d H:i:s");
				$history['description'] = "Remove stone from Collet Inventory ";
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				
				$history['entry_from'] ='collet';
				$history['for_history'] = 'main';	
				
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}			
											
			}
		return $rs;		
	}		
	public function editJob($post)
	{
		/* print_r($post);
		exit; */
		$products = array();
		
		if(isset($post['products']))
		{
			$products = $post['products'];
		}
		
		
		$mdata =  $this->getData($post['id'],'collet');
		
		$amount = $mdata['final_amount'];
		$rs = '';
		try{
	
			$products = $post['products'];
			$returnProduct = array();
			if($mdata['return_products'] != '')
			{	
				$returnProduct = explode(",",$mdata['return_products']);
			}
			
			
			$mp = array();
			foreach(explode(",",$mdata['products']) as $k=>$pid)
			{
				
				if(in_array($pid,$products ))
				{
					$mp[] = $pid;
					continue;
				}
				
				$pdata = $this->getProductDetail($pid);
				if(!empty($pdata))
				{	
					$tdata['outward'] = '';				
					$tdata['visibility'] = 1;				
					
					
					//$sql = "UPDATE ".$this->table_product." SET outward='sale',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
					$data = $this->jhelper->getUpdateString($tdata);		
					$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;
					$rs = mysqli_query($this->conn,$sql);
					
					if(!$rs)
					{
						return mysqli_error();
					}
					
					$history = array();
					$history['product_id'] = $pid;				
					$history['sku'] = $pdata['sku'];				
					$history['action'] = "collet_close";
					$history['type'] = 'cr';
					$history['description'] = "Main Stone remove from editing collet ";							
					$history['party'] = $mdata['party'];						
					$history['date'] = date("Y-m-d H:i:s");				
					$history['pcs'] = $pdata['pcs'];
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = ($pdata['sell_amount'] == 0 || $pdata['sell_amount'] == '') ? $pdata['amount'] : $pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] == 0 || $pdata['sell_price'] == '') ? $pdata['price'] : $pdata['sell_price'];
					$history['entry_from'] = 'collet_delete';
					$history['entryno'] = $mdata['id'];							
					$history['for_history'] = 'main';							
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
				}
				
				$amount = $amount - $pdata['amount'];
				$returnProduct[] = $pid;
			}
			
			$record = $post['record'];
			$newSku = array();
			foreach($record as $r)
			{
			
				if(isset($r['sku']) && $r['sku'] !='')
				{
					
					$pdata = $this->getDataBySku(trim($r['sku']));
					if(empty($pdata))
						continue;
					
					$sql = "UPDATE ".$this->table_product." SET visibility=0,outward='collet_making' WHERE id=".$pdata['id'];		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						return $rs;
					}
					$history =array();
					
					$history['product_id'] = $pdata['id'];
					$history['action'] = 'job_collet';
					$history['party'] = $post['party'];		
					$history['narretion'] = $post['narretion'];
					$history['date'] =  date("Y-m-d H:i:s");		
					$history['description'] = "Stone Send to Job Work. Job Number :".$post['entryno'];
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] =$pdata['amount'];
					$history['price'] = $pdata['price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'dr';
					
					$history['entry_from'] ='job';
					$history['for_history'] = 'main';	
					
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}			
					$amount += (float)$pdata['amount'];
				
					$mp[] = $pdata['id'];
					
				}
			}
			
				unset($post['record']);
				unset($post['products']);
				$returnProduct	= array_unique($returnProduct);
				$post['products'] = implode(",",$mp);
				$post['return_products'] = implode(",",$returnProduct);
				
				if(empty($mp)):
					$post['status']='close_collet';
					$post['deleted']=1;		
				endif;
				
				$data1 = $this->jhelper->getUpdateString($post);		
				$sql = "UPDATE ".$this->table." SET ".$data1." WHERE id=".$post['id'];
					
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
					
				}
				
		}
		catch(Exception $e)
		{
			$rs = $e->getMessage();	
		}		
	
		return $rs;
		
	}
	public function editRepair($post)
	{
		/* print_r($post);
		exit; */
		$products = array();
		
		if(isset($post['products']))
		{
			$products = $post['products'];
		}
		
		
		$mdata =  $this->getData($post['id'],'collet');
		
		$amount = $mdata['final_amount'];
		$rs = '';
		try{
	
		
			$products = $post['products'];
			$returnProduct = array();
			if($mdata['return_products'] != '')
			{	
				$returnProduct = explode(",",$mdata['return_products']);
			}
			
			
			$mp = array();
			foreach(explode(",",$mdata['products']) as $k=>$pid)
			{
				
				if(in_array($pid,$products ))
				{
					$mp[] = $pid;
					continue;
				}
				
				$pdata = $this->getProductDetail($pid);
				if(!empty($pdata))
				{	
					$tdata['in_repair'] = 0;				
					$tdata['outward'] = "collet";				
					$tdata['visibility'] = 1;				
					
					
					//$sql = "UPDATE ".$this->table_product." SET outward='sale',site_upload=0,rapnet_upload=0 WHERE id=".$pid;		
					$data = $this->jhelper->getUpdateString($tdata);		
					$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$pid;
					$rs = mysqli_query($this->conn,$sql);
					
					if(!$rs)
					{
						return mysqli_error();
					}
					
					$history = array();
					$history['product_id'] = $pid;				
					$history['sku'] = $pdata['sku'];				
					$history['action'] = "collet_repair_close";
					$history['type'] = 'cr';
					$history['description'] = "Collet Remove from editing collet Repair ";							
					$history['party'] = $mdata['party'];						
					$history['date'] = date("Y-m-d H:i:s");				
					$history['pcs'] = $pdata['pcs'];
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] = ($pdata['sell_amount'] == 0 || $pdata['sell_amount'] == '') ? $pdata['amount'] : $pdata['sell_amount'];
					$history['price'] = ($pdata['sell_price'] == 0 || $pdata['sell_price'] == '') ? $pdata['price'] : $pdata['sell_price'];
					$history['entry_from'] = 'collet_repair_close';
					$history['entryno'] = $mdata['id'];							
					$history['for_history'] = 'main';							
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}
				}
				
				$amount = $amount - $pdata['amount'];
				$returnProduct[] = $pid;
			}
			
			$record = $post['record'];
						
			$newSku = array();
			foreach($record as $r)
			{
			
				if(isset($r['sku']) && $r['sku'] !='')
				{
				
					$pdata = $this->getDataBySku(trim($r['sku']));
					if(empty($pdata))
						continue;
					
					$sql = "UPDATE ".$this->table_product." SET in_repair=1,outward='collet_repair' WHERE id=".$pdata['id'];		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();						
						return $rs;
					}
				
					$history =array();					
					$history['product_id'] = $pdata['id'];
					$history['action'] = 'collet_repair';
					$history['party'] = $post['party'];		
					$history['narretion'] = $post['narretion'];
					$history['date'] = date("Y-m-d H:i:s");
					$history['description'] = "Collet Send in Repair, Job Number :".$post['entryno'];
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] =$pdata['amount'];
					$history['price'] = $pdata['price'];
					$history['sku'] = $pdata['sku'];
					$history['type'] = 'dr';					
					$history['entry_from'] ='collet_repair';
					$history['for_history'] = 'main';	
			
					$rs = $this->jhelper->addHistory($history);
					if(!is_numeric($rs) && $rs!=1)
					{
						return $rs;	
					}			
					$amount += (float)$pdata['amount'];				
					$mp[] = $pdata['id'];					
				}
			}
			
				unset($post['record']);
				unset($post['products']);
				$returnProduct	= array_unique($returnProduct);
				$post['products'] = implode(",",$mp);
				$post['return_products'] = implode(",",$returnProduct);
				
				if(empty($mp)):
					$post['status']='close_collet_repair';
					$post['deleted']=1;		
				endif;
				
				$data1 = $this->jhelper->getUpdateString($post);		
				$sql = "UPDATE ".$this->table." SET ".$data1." WHERE id=".$post['id'];
					
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
					
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
		/* echo "<pre>";
		print_r($post);
		exit; */
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
				$pdata = $this->getProductDetail($k);
				$v['igi_date'] = $date;
				$v['outward'] = '';
				$v['lab'] = 'IGI';
				$v['color'] = $v['igi_color'];
				$v['clarity'] = $v['igi_clarity'];
				$v['report_no'] = $v['igi_code'];
				$data = $this->jhelper->getUpdateString($v);		
				$sql = "UPDATE ".$this->table_product." SET ".$data." WHERE id=".$k;	
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
				$history['description'] = "Stone Return to Lab Work. Lab Number :".$oData['invoiceno'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $oData['invoiceno'];
				$history['entry_from'] ='product';
				$history['for_history'] = 'main';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
		}
		if(empty($oProducts))
			$sql = "UPDATE jew_lab SET products_receive='',is_returned=1 WHERE  id=".$outid;		
		else
			$sql = "UPDATE jew_lab SET products_receive='".implode(",",$oProducts)."' WHERE  id=".$outid;		
		
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
			$pdata = $this->getProductDetail($pid);
			$rs = mysqli_query($this->conn,"update jew_product set outward = '' where id =".$pid);
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
				$history['description'] = "Stone remove from Lab Work. Lab Number :".$post['invoiceno'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $post['invoiceno'];
				$history['entry_from'] ='product';
				$history['for_history'] = 'main';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
		}
		$data = $this->helper->getUpdateString($post);
		$sql = " UPDATE jew_lab SET ".$data." WHERE id=".$post['id'];		
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
					$sql = "UPDATE jew_product SET ".$rdata." WHERE id=".$r['id'];		
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						$rs = mysqli_error();
						//break;
					}
					$pdata = $this->getProductDetail($r['id']);
					$history =array();
					$history['product_id'] = $r['id'];
					$history['action'] = 'lab';
					$history['party'] = $post['party'];		
					$history['date'] = $post['date'];
					$history['description'] = "Stone send to Lab Work. Lab Number :".$post['invoiceno'];
					$history['pcs'] = $pdata['pcs'];
					$history['carat'] = $pdata['carat'];
					$history['amount'] =$pdata['amount'];
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
			$pdata = $this->getProductDetail($pid);
			$rs = mysqli_query($this->conn,"update jew_product set outward = '' where id =".$pid);
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
				$history['description'] = "Stone remove from Lab Work. Lab Number :".$oData['invoiceno'];
				$history['pcs'] = $pdata['pcs'];
				$history['carat'] = $pdata['carat'];
				$history['amount'] =$pdata['amount'];
				$history['price'] = $pdata['price'];
				$history['sku'] = $pdata['sku'];
				$history['type'] = 'cr';
				$history['invoice'] = $oData['invoiceno'];
				$history['entry_from'] ='product';
				$history['for_history'] = 'main';	
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
		}
		$rs = mysqli_query($this->conn,"update jew_lab set deleted = 1 where id =".$Lid);
		if(!$rs)
		{
			$rs = mysqli_error();
			//break;
		}
		return $rs;
	}
	function delete($id)
	{
		$rs = mysqli_query($this->conn,"update create_job set deleted=1 where id=".$id);
		if(!$rs)
			{
				$rs = mysqli_error();
				//break;
			}
		return $rs;	
	}
	function getColorList()
	{
		$rs = mysqli_query($this->conn,"select * from jew_design");
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] =  $row['name'];
		}
		
		return  $data;		
	}
	function getClarityList()
	{
		$rs = mysqli_query($this->conn,"select * from dai_clarity");
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] =  $row['name'];
		}
		
		return  $data;		
	}
}	
<?php
include('../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');

class expenseModel
{
    public $table;
	public $table_product;
	public $table_product_value;
	public $helper;
	function __construct($db)
    {
        try {
            $this->table = "acc_transaction";
			$this->conn = $db;
			$this->helper  = new Helper($db);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getAllData()
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." and deleted = 0 ORDER BY date desc");
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] =  $row;
		}		
		return  $data;			
    }
	// get single Ledger Data
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
		   
				$data[mysqli_field_name( $rs, $i )] = "";
		   
			}
		}	
		return  $data;			
    }
	public function getPartyByName($name)
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_party WHERE name = '".$name."'" );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
			break;
		}
		return $data;
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
	public function getBalance($book)
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_book WHERE  id=".$book);
			
		$data = "0";
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row['balance'];
		}
		return $data;
	}
	public function getRecordData($id)
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." WHERE  id = '".$id."'" );
		
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		return  $data;			
    }
	
	public function getIncrementEntry($of = "")
	{
		if($of=="")
			return "";
		$rs = mysqli_query($this->conn,"SELECT ".$of." FROM dai_incrementid WHERE company=".$_SESSION['companyId'] );
			
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
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;		
		$balance = $this->getBalance($post['book']);
		
		$party = $this->getParty($post['party']);
		$post['under_group'] = $party['under_group']; 
		$post['under_subgroup'] = $party['under_subgroup']; 
		if($post['type'] == "cr")
		{
			$balance = (float)$balance + (float)$post['amount'];
		}
		else
		{
			$balance = (float)$balance - (float)$post['amount'];
		}
		$post['balance'] = $balance;
		if(isset($post['description']))
		{
			$post['description'] = strtoupper($post['description']);
		}	
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
		}
		else
		{
			$book = "balance = ".$balance;
			$sql = "UPDATE dai_book SET ".$book." WHERE id=".$post['book'];			
			$rs = mysqli_query($this->conn,$sql);
		}
		return $rs;
    }
	
	
	 public function savePart($post)
    {
		$o_type = $post['out_type'];
		unset($post['out_type']);
		$OData = $this->getSaleData($post['sale_id'],$o_type);
		$paid =  (float)$OData['paid_amount'] + (float)$post['amount'];
		if($post['amount'] > $OData['due_amount'])
		{
			return "Amount are Exceed than due amount.";
		}	
		$remaing = (float)$OData['due_amount'] - (float)$post['amount'];
		if($o_type == 'inward')
		{
			$post['purchase_id'] = $post['sale_id'];
			unset($post['sale_id']);
		}
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;
		//$incre_id = $this->getIncrementEntry('inward');
		//$post['entryno'] = $incre_id;
		$balance = $this->getBalance($post['book']);
		$party = $this->getPartyByName('PAYMENT');
		if(!empty($party))
		{
			$post['under_group'] = $party['under_group']; 
			$post['under_subgroup'] = $party['under_subgroup']; 
		}
		if($post['type'] == "cr")
		{
			$balance = (float)$balance + (float)$post['amount'];
		}
		else
		{
			$balance = (float)$balance - (float)$post['amount'];
		}
		$post['balance'] = $balance;
		if(isset($post['description']))
		{
			$post['description'] = strtoupper($post['description']);
		}	
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
		}
		else
		{
			$book ="";
			$book = "balance = ".$balance;
			$sql = "UPDATE dai_book SET ".$book." WHERE id=".$post['book'];			
			$rs = mysqli_query($this->conn,$sql);
			if($o_type == 'outward')
				$sql = "UPDATE jew_outward SET paid_amount=".$paid.", due_amount=".$remaing." WHERE id=".$post['sale_id'];
			else	
				$sql = "UPDATE jew_inward SET paid_amount=".$paid.", due_amount=".$remaing." WHERE id=".$post['purchase_id'];
			
			$rs = mysqli_query($this->conn,$sql);
		}
		return $rs;
    }
	
	public function getSaleData($id,$type)
	{
		if($type == 'outward')
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_outward WHERE id = '".$id."'" );
		else
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_inward WHERE id = '".$id."'" );
		
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		return  $data;	
	}
	public function savePartJew($post)
    {
		$OData = $this->getJewSaleData($post['jew_sale_id']);
		$paid =  (float)$OData['paid_amount'] + (float)$post['amount'];
		if($post['amount'] > $OData['due_amount'])
		{
			return "Amount are Exceed than due amount.";
		}	
		$remaing = (float)$OData['due_amount'] - (float)$post['amount'];
		
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;
		//$incre_id = $this->getIncrementEntry('inward');
		//$post['entryno'] = $incre_id;
		$balance = $this->getBalance($post['book']);
		$party = $this->getPartyByName('PAYMENT');
		if(!empty($party))
		{
			$post['under_group'] = $party['under_group']; 
			$post['under_subgroup'] = $party['under_subgroup']; 
		}
		if($post['type'] == "cr")
		{
			$balance = (float)$balance + (float)$post['amount'];
		}
		$post['balance'] = $balance;
		if(isset($post['description']))
		{
			$post['description'] = strtoupper($post['description']);
		}	
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
		}
		else
		{
			$book ="";
			$book = "balance = ".$balance;
			$sql = "UPDATE dai_book SET ".$book." WHERE id=".$post['book'];			
			$rs = mysqli_query($this->conn,$sql);
		
			$sql = "UPDATE jew_sale SET paid_amount=".$paid.", due_amount=".$remaing." WHERE id=".$post['jew_sale_id'];
			$rs = mysqli_query($this->conn,$sql);
		}
		return $rs;
    }
	public function getJewSaleData($id)
	{
	   $rs = mysqli_query($this->conn,"SELECT * FROM jew_sale WHERE id = '".$id."'" );
	   $data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		return  $data;	
	}
	public function updateData($post)
    {
		$realData = $this->getData($post['id']);
		
		if($realData['type'] == 'dr' && $realData['purchase_id'] != '' )
		{
			$saleData = $this->getSaleData($realData['purchase_id'],'inward');
			
			$paidAmount = (float)($saleData['paid_amount'] - $realData['amount']) +  $post['amount'];
			
			$dueAmount = ($saleData['due_amount'] + $realData['amount']) -  $post['amount'];
			
			if((float)$paidAmount > (float)$saleData['final_amount'] )
			{
				return "Paid Amount is Greter Than the final amount";
			}
			$sql = "UPDATE jew_inward SET paid_amount=".$paidAmount.", due_amount=".$dueAmount." WHERE id=".$realData['purchase_id'];
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				return mysqli_error();
			}
		}
		if($realData['type'] == 'cr' && $realData['sale_id'] != '' )
		{
			$saleData = $this->getSaleData($realData['sale_id'],'outward');
			
			$paidAmount = (float)($saleData['paid_amount'] - (float)$realData['amount']) + (float) $post['amount'];
			
			$dueAmount = ($saleData['due_amount'] + $realData['amount']) -  $post['amount'];
			
			if((float)$paidAmount > (float)$saleData['final_amount'] )
			{
				return $paidAmount." Paid Amount is Greter Than the final amounts ".$saleData['final_amount'];
			}
			
			$sql = "UPDATE jew_outward SET paid_amount=".$paidAmount.", due_amount=".$dueAmount." WHERE id=".$realData['sale_id'];
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				return mysqli_error();
			}
		}
		
		
		$diff  = $realData['amount']  -  $post['amount'];		
		$balance = $this->getBalance($realData['book']);
		if($realData['type'] == 'dr')
		{
			$balance = $balance  + $diff;
		}
		else
		{
			$balance = $balance  - $diff;	
		}
		
		if(isset($post['description']))
		{
			$post['description'] = strtoupper($post['description']);
		}	
		if($post['party'] != $realData['party'] )
		{
			$party = $this->getParty($post['party']);
			$post['under_group'] = $party['under_group']; 
			$post['under_subgroup'] = $party['under_subgroup']; 
		}
		$data = $this->helper->getUpdateString($post);	
	    $sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$realData['id'];		
	    $rs = mysqli_query($this->conn,$sql);
	    if(!$rs)
		{
			$rs = mysqli_error();
		}
		else
		{			
			$book = "balance = ".$balance;			
			$sql = "UPDATE dai_book SET ".$book." WHERE id=".$realData['book'];			
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
			}
		}		
		return $rs;
    }
	
	
	public function changeBook($post)
    {
		$realData = $this->getData($post['id']);
		
		if($realData['book'] != $post['book'] )
		{
			
			$data = $this->helper->getUpdateString($post);
		
			$sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$post['id'];		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
			}
			else
			{
				$oldBal = $this->getBalance($realData['book']);
				
				if($realData['type'] == 'cr')
				{
					$balance = $oldBal - $realData['amount'];
				}
				else
				{
					$balance = $oldBal + $realData['amount'];
				}
				
				$book = "balance = ".$balance;
				$sql = "UPDATE dai_book SET ".$book." WHERE id=".$realData['book'];			
				$rs = mysqli_query($this->conn,$sql);
				
				
				$newBal = $this->getBalance($post['book']);	

				if($realData['type'] == 'dr')
				{
					$balance = $newBal - $realData['amount'];
				}
				else
				{
					$balance = $newBal + $realData['amount'];
				}	
								
				$book = "balance = ".$balance;
				$sql = "UPDATE dai_book SET ".$book." WHERE id=".$post['book'];			
				$rs = mysqli_query($this->conn,$sql);
			}	
			
		}		
		return $rs;
    }
	
	
	public function changeType($post)
    {
		$realData = $this->getData($post['id']);
		
		if($realData['type'] != $post['type'] )
		{
			$data = $this->helper->getUpdateString($post);
		
			$sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$post['id'];		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				return  mysqli_error();
			}
			else
			{
				$oldBal = $this->getBalance($realData['book']);
				if($post['type'] == 'cr')
				{
					$balance = $oldBal + $realData['amount'] + $realData['amount'];
				}
				else
				{
					$balance = $oldBal - $realData['amount'] - $realData['amount'];
				}	

					$book = "balance = ".$balance;
					$sql = "UPDATE dai_book SET ".$book." WHERE id=".$realData['book'];			
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						return mysqli_error();
					}				
								
			}				
		}		
		return $rs;
    }
   
    public function delete($id)
    {
		$realData = $this->getData($id);
		
		if($realData['deleted'])
		{
			return "This Transaction already Deleted !!!";
		}		
			
		//$sql = "DELETE FROM ".$this->table ." WHERE id = ".$id;
		$sql = "UPDATE ".$this->table ." SET deleted=1 WHERE id=".$id;
        $rs = mysqli_query($this->conn,$sql);
		if($rs != 1 )
		{
			return mysqli_error();
		}
		else
		{
			$oldBal = $this->getBalance($realData['book']);
			if($realData['type'] == 'cr')
			{
				$balance = $oldBal - $realData['amount'];
			}
			else
			{
				$balance = $oldBal + $realData['amount']; 
			}	
			
			//$balance = $oldBal - $realData['amount'];
			
			$book = "balance = ".$balance;
			$sql = "UPDATE dai_book SET ".$book." WHERE id=".$realData['book'];			
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				return mysqli_error();
			}
			if($realData['sale_id'] !='')
			{
				$saleId = $realData['sale_id'];
				$saleData = $this->getSaleData($saleId,'outward');
				
				$paid = $saleData['paid_amount'] - $realData['amount'];
				$due = $saleData['due_amount'] + $realData['amount'];
				
				$sql = "UPDATE dai_outward SET paid_amount='".$paid."', due_amount='".$due."' WHERE id=".$saleId;			
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
				}
			}
			if($realData['purchase_id'] !='')
			{
				$saleId = $realData['purchase_id'];
				$saleData = $this->getSaleData($saleId,'inward');
				
				$paid = $saleData['paid_amount'] - $realData['amount'];
				$due = $saleData['due_amount'] + $realData['amount'];
				
				$sql = "UPDATE dai_inward SET paid_amount=".$paid.", due_amount=".$due." WHERE id=".$saleId;			
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();
				}
			}
			
			if($realData['cross_id'] !='')
			{
				$cross_id = $realData['cross_id'];
				
				$realData = $this->getData($cross_id );
		
				if($realData['deleted'])
				{
					return "This Transaction already Deleted !!!";
				}
				
					
				//$sql = "DELETE FROM ".$this->table ." WHERE id = ".$id;
				$sql = "UPDATE ".$this->table ." SET deleted=1 WHERE id=".$cross_id;
				$rs = mysqli_query($this->conn,$sql);
				if($rs != 1 )
				{
					return mysqli_error();
				}
				else
				{
					$oldBal = $this->getBalance($realData['book']);
					if($realData['type'] == 'cr')
					{
						$balance = $oldBal - $realData['amount'];
					}
					else
					{
						$balance = $oldBal + $realData['amount']; 
					}	
					
					//$balance = $oldBal - $realData['amount'];
					
					$book = "balance = ".$balance;
					$sql = "UPDATE dai_book SET ".$book." WHERE id=".$realData['book'];			
					$rs = mysqli_query($this->conn,$sql);
					if(!$rs)
					{
						return mysqli_error();
					}
				}
			}
		}
		return $rs;
	    
    }
}
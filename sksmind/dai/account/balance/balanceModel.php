<?php
//include('../../../../../variable.php');
include('../../../database.php');
include_once('../../Helper.php');
class balanceModel
{
    public $table;
	public $helper;
	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn=$db;
            $this->table  = "dai_book";
			$this->helper  = new Helper($db);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	public function getAllData()
    {
		$data = array();			
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		return  $data;			
    }
	
	public function getAllCurrency()
    {
		$data = array();			
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_currencyrate");
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['currency']] = $row;
		}
		return  $data;			
    }
    

  
	public function saveCurrency($post)
    {
		unset($post['fn']);
		foreach($post as $k=>$value)
		{
			if(isset($value['id']))
			{
				if(!isset($value['USD']))
					continue;
				$data = $this->helper->getUpdateString($value);		
				$sql = "UPDATE dai_currencyrate SET ".$data." WHERE id=".$value['id'];
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			}
			else
			{
				$value['currency'] = $k;
				
				$data = $this->helper->getInsertString($value);
			
				$sql = "INSERT INTO dai_currencyrate (". $data[0].") VALUES (".$data[1].")";		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			}
			
		}
		
	    return $rs;
    }
	
	public function saveBook($post)
    {
		$rs = "No data found for update !!!";
		foreach($post['record'] as $r)
		{
			if($r['book'] == "" || $r['currency'] == "" || !isset($r['book']) || !isset($r['currency']))
					continue;
			
			if(isset($r['id']))
			{
				$data = $this->helper->getUpdateString($r);
				$sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$r['id'];
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			}
			else
			{	
				$r['company'] = $_SESSION['companyId'];				
				$data = $this->helper->getInsertString($r);
			
				$sql = "INSERT INTO ".$this->table." (". $data[0].") VALUES (".$data[1].")";		
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					$rs = mysqli_error();
					return $rs;
				}
			}
		}
	    return $rs;
    }
	public function getData($id)
    {
		$data = array();			
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table." WHERE id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
		}
		return  $data;			
    }
	public function getParty($name)
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_party WHERE name like '%".$name."%'" );
			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
			break;
		}
		return $data;
	}
	public function bankTransfer($post)
    {
	
		$fromData = $this->getData($post['frombook']);
		$toData = $this->getData($post['tobook']);
		
		//from book
		$FBalance = $fromData['balance'];
		if($post['amount']>$FBalance)
			return "Amount is greter than Balance";
			
		$RFBalance = (float)$FBalance  - (float)$post['amount'];
		
		$cid = $_SESSION['companyId'];
		$ftrasaction['company'] = $cid;			
		$party = $this->getParty('CURRENCY CONVERT');
		
		if(!empty($party))
		{
			$ftrasaction['under_group'] = $party['under_group']; 
			$ftrasaction['party'] = $party['id']; 
			$ftrasaction['under_subgroup'] = $party['under_subgroup']; 		
		}
		
		$ftrasaction['balance'] = $RFBalance;
		$ftrasaction['book'] = $post['frombook'];
		$ftrasaction['date'] = $post['date'];
		$ftrasaction['amount'] =  $post['amount'];
		$ftrasaction['type'] =  'dr';
		$ftrasaction['description'] =  strtoupper($fromData['book'].' TO ' . $toData['book'] .' TRANSFER WITH '.$post['amount'].' * '.$post['rate']);
		
		$data = $this->helper->getInsertString($ftrasaction);	
		$sql = "INSERT INTO acc_transaction (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
		}
		else
		{
			$fid =  mysqli_insert_id($this->conn);
			$book = "balance = ".$RFBalance;
			$sql = "UPDATE dai_book SET ".$book." WHERE id=".$post['frombook'];			
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
		}
		
		
		// to Bank
		
		$TBalance = $toData['balance'];
		$addBalance = (float)$post['amount'] * (float)$post['rate'];
		$RTBalance = (float)$TBalance + (float)$addBalance;
		
		$ftrasaction['amount'] =  $addBalance;		
		$ftrasaction['balance'] = $RTBalance;
		$ftrasaction['book'] = $post['tobook'];
		$ftrasaction['type'] =  'cr';
	//	$ftrasaction['description'] =  'Book transfer with '.$post['amount'].' * '.$post['rate'];
		$ftrasaction['cross_id'] = $fid;
		$data = $this->helper->getInsertString($ftrasaction);	
		$sql = "INSERT INTO acc_transaction (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			$rs = mysqli_error();
		}
		else
		{
			$tid =  mysqli_insert_id($this->conn);
			$book = "balance = ".$RTBalance;
			$sql = "UPDATE dai_book SET ".$book." WHERE id=".$post['tobook'];			
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
			
			$sql = "UPDATE acc_transaction SET cross_id='".$tid."' WHERE id=".$fid;			
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
		}		
		
	    return $rs;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM ".$this->table ." WHERE id = ".$id;
        $rs = mysqli_query($this->conn,$sql);
	    return $rs;
    }	
  
}
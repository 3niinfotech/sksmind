<?php
include('../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');

class advanceModel
{
    public $table;
	public $table_product;
	public $table_product_value;
	public $helper;
	 function __construct()
    {
        try {
            $this->table  = "acc_advance";
			$this->helper  = new Helper;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getAllData()
    {
		$rs = mysql_query("SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." and deleted = 0  ORDER BY date desc");
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data[] =  $row;
		}		
		return  $data;			
    }
	// get single Ledger Data
	public function getData($id)
    {
		$rs = mysql_query("SELECT * FROM ".$this->table ." WHERE id=".$id );
			
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
		}	
		return  $data;			
    }
	public function getInvoiceData($invoice)
	{
		$rs = mysql_query("SELECT * FROM dai_outward WHERE   invoiceno='".$invoice."' ");
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data['paid_amount'] =  $row['paid_amount'];
			$data['due_amount'] =  $row['due_amount'];	
			$data['final_amount'] =  $row['final_amount'];	
			$data['id'] =  $row['id'];	
		}		
		return  $data;
	}
	public function getPartyByName($name)
	{
		$rs = mysql_query("SELECT * FROM dai_party WHERE name like '%".$name."%'" );
			
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data = $row;
			break;
		}
		return $data;
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
	
	public function getTransaction($id)
	{
		$rs = mysql_query("SELECT * FROM acc_transaction WHERE id=".$id );
			
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data = $row;
		}
		return $data;
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
	
	
	// ADD new Ledger
    public function saveData($post)
    {
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;
		$post['user'] = $_SESSION['userid'];
		
		$post['type'] = 'advance';	
		$post['balance_amount'] = $post['amount'];
		
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
		}
		else
		{
			$adid = mysql_insert_id();
			$balance = $this->getBalance($post['book']);
			unset($post['balance_amount']);
			$transaction = $post;
			
			$party = $this->getParty($post['party']);
			$transaction['under_group'] = $party['under_group']; 
			$transaction['under_subgroup'] = $party['under_subgroup']; 
			$balance = (float)$balance + (float)$post['amount'];
		 
			$transaction['type'] = "cr";					
			$transaction['balance'] = $balance; 
			$transaction['description'] = 'ADVANCE PAYMENT RECEIVED'; 
			
			$data = $this->helper->getInsertString($transaction);	
			$sql = "INSERT INTO acc_transaction (". $data[0].") VALUES (".$data[1].")";
				
			$rs = mysql_query($sql);
			if(!$rs)
			{
				return mysql_error();
			}
			$trid = mysql_insert_id();
			
			$book = "balance = ".$balance;
			$sql = "UPDATE dai_book SET ".$book." WHERE id=".$post['book'];			
			$rs = mysql_query($sql);
			
		
			$sql = "UPDATE ".$this->table." SET transaction_id = ".$trid." WHERE id=".$adid;		
			$rs = mysql_query($sql);
		}
		return $rs;
    }
	
	public function updateData($post)
    {
		$realData = $this->getData($post['id']);
		$invoiceData = array();
		
		/* $diff  = $realData['amount']  -  $post['amount'];
		
		$balance = $this->getBalance($realData['book']);
		
		$balance = $balance  - $diff; */
		
		if(isset($post['invoice']) && $post['invoice'] != '' && $realData['invoice'] == ''  )
		{
			//$inArray = explode(",",$realData['invoice']);
			//$inArray[] = $post['invoice'];
			
			$invoiceData  = $this->getInvoiceData($post['invoice']);
			if($invoiceData['due_amount'] > $realData['balance_amount'] )
			{
				
				$due = $invoiceData['due_amount'] - $realData['balance_amount'];
				$paid = $invoiceData['paid_amount'] + $realData['balance_amount'];
				$post['use_amount'] = $post['use_amount'] + $realData['balance_amount'];
				$post['balance_amount'] = 0;
			}
			else
			{
				$paid = $invoiceData['paid_amount'] + $invoiceData['due_amount'];
				$post['use_amount'] = $realData['use_amount'] + $invoiceData['due_amount'];
				$post['balance_amount'] = $realData['amount'] - $post['use_amount'];
				$due = 0;	
			}
			//$post['invoice'] = implode(",",$inArray);
		}
			
		$data = $this->helper->getUpdateString($post);
	
	    $sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$post['id'];
	
	    $rs = mysql_query($sql);
	    if(!$rs)
		{
			return mysql_error();
		}
		else
		{
			/* $book = " balance = ".$balance;
			$sql = "UPDATE dai_book SET ".$book." WHERE id=".$realData['book'];			
			$rs = mysql_query($sql);
			 if(!$rs)
			{
				return mysql_error();
			}	 */	
			
			$sale_id = '';
			if(!empty($invoiceData))
				$sale_id = $invoiceData['id'];
			
			$tansactions = $this->getTransaction($realData['transaction_id']);
			
			$descri = $tansactions['description'].' :: PAYMENT OF INVOICE NO: '.$post['invoice']; 	
			$sql = "UPDATE acc_transaction SET description='".$descri."',sale_id='".$sale_id."' WHERE id=".$realData['transaction_id'];		
			$rs = mysql_query($sql);
			if(!$rs)
			{
				return mysql_error();
			}
			
			if(isset($post['invoice']) && $post['invoice'] != '' &&  $realData['invoice']=='')
			{
				$sql = "UPDATE dai_outward SET paid_amount = ".$paid.", due_amount = ".$due." WHERE id=".$invoiceData['id'] ;		
			
				$rs = mysql_query($sql);
				 if(!$rs)
				{
					return mysql_error();
				}
			}
		}
	
		return $rs;
    }
	
    public function delete($id)
    {
		
		$realData = $this->getData($id);
		
		$sql = "UPDATE ".$this->table ." SET deleted=1 WHERE id = ".$id;
        $rs = mysql_query($sql);
		if($rs != 1 )
		{
			$rs = mysql_error();
		}
		else
		{
			$oldBal = $this->getBalance($realData['book']);			
			$balance = $oldBal - $realData['amount'];		
			
			$book = "balance = ".$balance;
			$sql = "UPDATE dai_book SET ".$book." WHERE id=".$realData['book'];			
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
			}
			
			$sql = "UPDATE acc_transaction SET deleted=1 WHERE id=".$realData['transaction_id'] ;
			$rs = mysql_query($sql);
		}
	    
    }
}
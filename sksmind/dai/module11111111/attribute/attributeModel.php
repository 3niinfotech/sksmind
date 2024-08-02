<?php
//include('../../../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
class attributeModel
{
    public $table;
	public $table_record;
	public $helper;
	 function __construct()
    {
        try {
            $this->table  = "dai_attribute";
			$this->table_record  = "dai_attribute_value";
			$this->helper  = new Helper;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getAllData()
    {
		$rs = mysql_query("SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." ORDER BY short_order");
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
		}	
		return  $data;			
    }
	
	public function getDataRecord($id)
    {
		$rs = mysql_query("SELECT * FROM ".$this->table_record ." WHERE attribute_id=".$id );
			
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		return  $data;			
    }
	
	// ADD new Ledger
    public function addData($post)
    {
		
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;
		$record = $post['record'];
		unset($post['record']);
		$data = $this->helper->getInsertString($post);
	    $sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";		
			
	    $rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
		}
		else
		{
			$rs = mysql_query("SELECT id FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." and code= '".$post['code']."'" );			
			$aid = "";
			while($row = mysql_fetch_assoc($rs))
			{
				$aid = $row['id'];
			}
			
			foreach($record as $r)
			{
				if($r['code']="" && $r['value']=="")	
					continue;
				$r['attribute_id'] = $aid;
				$rs = $this->InsertRecord($r);
				if(!$rs)
				{
					$rs = mysql_error();
					break;
				}
			}
			
		}	
		return $rs;
    }
	
	// get ledger of Account Group by Id
	public function getOptionList()
	{
		$rs = mysql_query("SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId'] );
			
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data[$row['code']] = $row['value'];
		}
	
		return  $data;
	}
	
	
	
	public function updateData($post)
    {
		$record = $post['record'];
		unset($post['record']);
		
		foreach($record as $r)
		{
			if(isset($r['id']))
			{
				if(isset($r['code']) && isset($r['label']) )
				{
					$rs = $this->UpdateRecord($r);
					if(!$rs)
						break;
				}
				else
				{
					$this->DeleteRecord($r['id']);
				}
			}
			else
			{
				$r['attribute_id'] = $post['id'];
				$rs = $this->InsertRecord($r);
				if(!$rs)
				{
					$rs = mysql_error();
					break;
				}
			}
		}
		$data = $this->helper->getUpdateString($post);
	
	    $sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$post['id'];		
	    $rs = mysql_query($sql);
	    if(!$rs)
			$rs = mysql_error();
			
		return $rs; 		
    }

   
    public function deleteData($id)
    {
        $sql = "DELETE FROM ".$this->table ." WHERE id = ".$id;
        $rs = mysql_query($sql);
	    return $rs;
    }
	public function InsertRecord($data)
	{
		$dr = $this->helper->getInsertString($data);
		$sql = "INSERT INTO ".$this->table_record ." (". $dr[0].") VALUES (".$dr[1].")";				
		$rs = mysql_query($sql);		
		return $rs;
	}
	public function UpdateRecord($data)
	{
		$data = $this->helper->getUpdateString($data);
	
	    $sql = "UPDATE ".$this->table_record." SET ".$data." WHERE attribute_id=".$data['id'];		
	    $rs = mysql_query($sql);
	    if(!$rs)
			$rs = mysql_error();
			
		return $rs;
	}
	public function DeleteRecord($id)
	{
		$sql = "DELETE FROM ".$this->table_record ." WHERE attribute_id = ".$id;
        $rs = mysql_query($sql);
		if(!$rs)
			$rs = mysql_error();
	    return $rs;
	}
	
}


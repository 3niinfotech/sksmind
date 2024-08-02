<?php
//include('../../../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
class partyModel
{
    public $table;
	public $helper;
	 function __construct($db)
    {
        try {
            $this->table  = "dai_party";
			$this->conn  = $db;
			$this->helper  = new Helper($db);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getAllData()
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." ORDER BY name");
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
	// ADD new Ledger
    public function addData($post)
    {
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;
		
		$data = $this->helper->getInsertString($post);	
			
		
	    $sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";		
			
	    $rs = mysqli_query($this->conn,$sql);
		if(!$rs)
			$rs = mysqli_error();
			
		return $rs;
    }
	
	// get ledger of Account Group by Id
	public function getOptionList()
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE company=".$_SESSION['companyId']." ORDER BY name");			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row['name'];
		}	
		return  $data;
	}
	
	
	
	public function updateData($post)
    {
		
		$data = $this->helper->getUpdateString($post);
	
	    $sql = "UPDATE ".$this->table." SET ".$data." WHERE id=".$post['id'];		
	    $rs = mysqli_query($this->conn,$sql);
	    if(!$rs)
			$rs = mysqli_error();
			
		return $rs;
    }

   
    public function deleteData($id)
    {
        $sql = "DELETE FROM ".$this->table ." WHERE id = ".$id;
        $rs = mysqli_query($this->conn,$sql);
	    return $rs;
    }
}


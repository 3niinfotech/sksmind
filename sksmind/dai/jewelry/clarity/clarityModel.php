<?php
//include('../../../../../variable.php');
include('../../../database.php');
class clarityModel
{
    public $table;
	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn = $db;
            $this->table  = "dai_clarity";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getAllData()
    {	
		$data = array();		
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE deleted=0 ORDER BY name");
		while($row = mysqli_fetch_assoc($rs)){

			$data[] = $row;
		}
		return  $data;			
    }
	public function getOptionList()
	{
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table." WHERE deleted=0");			
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row['name'];
		}	
		return  $data;
	}

    public function saveData($post)
    {
		$name = $post['name'];		
	    $sql = "INSERT INTO ".$this->table ." (name) VALUES ('$name')";		
	    $rs = mysqli_query($this->conn,$sql);
	    return $rs;
    }
	
	public function updateData($post)	
    {
		$name = $post['name'];		
	    $sql = "UPDATE ".$this->table." SET name='$name' WHERE id=".$post['id'];		
	    $rs = mysqli_query($this->conn,$sql);
	    return $rs;
    }
	public function loadPrice($id)
    {	
		$data = '';		
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table ." WHERE id='".$id."'");
		while($row = mysqli_fetch_assoc($rs)){

			$data = $row['price'];
			break;
		}
		return  $data;			
    }
   
    public function delete($id)
    {
        $sql = "Update ".$this->table ." SET deleted = 1 WHERE id = ".$id;
        $rs = mysqli_query($this->conn,$sql);
	    return $rs;
    } 
}

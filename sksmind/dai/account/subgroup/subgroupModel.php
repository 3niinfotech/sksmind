<?php
//include('../../../../../variable.php');
include('../../../database.php');
class subgroupModel
{
    public $table;
	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn=$db;
            $this->table  = "acc_subgroup";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	public function getOption()
    {
		$data = array();			
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['id']] = $row['name'];
		}
		return  $data;			
    }
    public function getData()
    {
		$data = array();			
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		return  $data;			
    }

    public function save($post)
    {
		$name = $post['name'];
		$under = $post['under'];
					
	    $sql = "INSERT INTO ".$this->table ." (name,under) VALUES ('$name','$under')";		
	    $rs = mysqli_query($this->conn,$sql);
	    return $rs;
    }
	
	 public function update($post)
    {
		$name = $post['name'];
		$under = $post['under'];
		
	    $sql = "UPDATE ".$this->table." SET name='$name',under='$under' WHERE id=".$post['id'];		
	    $rs = mysqli_query($this->conn,$sql);
	    return $rs;
    }

   
    public function delete($id)
    {
        $sql = "DELETE FROM ".$this->table ." WHERE id = ".$id;
        $rs = mysqli_query($this->conn,$sql);
	    return $rs;
    }	
  
}

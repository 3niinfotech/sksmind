<?php
//include('../../../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
class noteModel
{
    public $table;
	public $helper;
	 function __construct()
    {
        try {
            $this->table  = "dai_note";
			$this->helper  = new Helper;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	function changeNote($post)
	{
		$pid = $post['id'];
		$tval = $post['note'];
		
		$sql = "UPDATE ".$this->table." SET content='$tval' WHERE id=".$pid;		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
			return $rs;
		}
		return $rs;
		
	}
	function changeStatus($post)
	{
		$pid = $post['id'];
		$tval = $post['status'];
		
		$sql = "UPDATE ".$this->table." SET status=$tval WHERE id=".$pid;		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
			return $rs;
		}
		return $rs;
		
	}
	
	function addNewNote($post)
	{
		$cid = $_SESSION['companyId'];
		$post['company'] = $cid;
		$post['user'] = $_SESSION['userid'];
		$post['date'] = date("Y-m-d H:i:s");
		
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";
			
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
		}
		
		return $rs;
		
	}
	
	function deleteNote($post)
	{
		$pid = $post['id'];
		
		$sql = "DELETE FROM ".$this->table." WHERE id=".$pid;		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			$rs = mysql_error();
			return $rs;
		}
		return $rs;
		
	}	
}


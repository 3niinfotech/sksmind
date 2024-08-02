<?php
//include('../../../../../variable.php');
//include_once('../../../database.php');
//include_once('../../Helper.php');
class rapModel
{
    public $table;
	public $helper;
	 function __construct()
    {
        try {
            $this->table  = "dai_rapnetprice";			
			$this->helper  = new Helper;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getAllRapnetPrice()
    {
		$rs = mysql_query("SELECT * FROM ".$this->table);
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{	
			$data[] =  $row;
		}
		
		return  $data;			
    }	
	public function rapnetPrice()
	{
		$header = array('username' => '91552','password' => '@73538148');
		$body = array('shape' => 'round,pear');

		//print_r (json_encode(array('request' => array('header' => $header,'body' => $body))));


		$auth_url = "https://technet.rapaport.com/HTTP/JSON/Prices/GetPriceSheet.aspx";
		$post_string = "username=91552&password=" . urlencode("@73538148");


		$request = curl_init($auth_url);
		curl_setopt($request, CURLOPT_HEADER, 0); 
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($request, CURLOPT_POSTFIELDS, json_encode(array('request' => array('header' => $header,'body' => $body)))); 
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); 
		$auth_ticket = curl_exec($request); 
		curl_close ($request);

		$data = json_decode($auth_ticket, true);

		$sql = "TRUNCATE ".$this->table;		
		$rs = mysql_query($sql);

		foreach($data as $row) {
			foreach($row['body'] as $rapnetprices) {
				foreach($rapnetprices as $rapnetprice) {
					//echo count($rapnetprice);
					$data = $this->helper->getInsertString($rapnetprice);	
					$sql = "INSERT INTO ".$this->table ." (". $data[0].") VALUES (".$data[1].")";		
					$rs = mysql_query($sql);
					if(!$rs)
					{
						$rs = mysql_error();
						return $rs;
					}
				}	
			} 
		}
		return $rs;
	}
	
}


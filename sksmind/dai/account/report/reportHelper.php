<?php class reportHelper{	public function getExportField()	{		$data = array();				$data['date'] ='Date'; 				$data['account'] ='Account'; 
		$data['party'] ='Party';		$data['cheque'] ='Cheque'; 		$data['description'] ='Description'; 		$data['credit'] ='Credit'; 		$data['debit'] ='Debit'; 
		$data['balance'] ='Balance'; 		return  $data;
	}
}
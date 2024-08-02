<?php 

//include_once("../database.php");
//include_once("../variable.php");

class reportHelper
{
	
	public function getAttribute($all = 0)
	{
		$data = array();
		if($all)
		{
			$data['mfg_code'] ='Mfg.Code'; 
			$data['diamond_no'] ='D.No';  
			$data['sku'] ='Sku'; 
			$data['rought_pcs'] ='R.Pcs'; 
			$data['rought_carat'] ='R.Carat'; 
			$data['polish_pcs'] ='P.Pcs'; 
			$data['polish_carat'] ='P.Carat'; 
			$data['cost'] ='Cost'; 
			$data['price'] ='Price'; 
			$data['amount'] ='Amount'; 
			$data['location'] ='LOC'; 
			$data['remark'] ='Remark';  
			$data['lab'] ='Lab'; 
			
		}
		$rs = mysql_query("SELECT code,name FROM dai_attribute WHERE company=".$_SESSION['companyId']." ORDER BY short_order" );			
		
		while($row = mysql_fetch_assoc($rs))
		{
			$data[$row['code']] = $row['name'];
		}	
		return  $data;
	}
	
	public function getMemoPackage()
	{
		$data = array();
					
		$data['party'] ='Company';
		$data['out_date'] ='Date'; 		
		$data['sku'] ='Sku'; 
		$data['lab'] ='Lab'; 
		$data['report_no'] ='Report No.'; 
		$data['rought_pcs'] ='R.Pcs'; 
		$data['rought_carat'] ='R.Carat'; 
		$data['polish_pcs'] ='P.Pcs'; 
		$data['polish_carat'] ='P.Carat'; 
		$data['cost'] ='Cost'; 
		$data['price'] ='Price'; 
		$data['amount'] ='Amount'; 
		$data['shape'] ='Shape';
		$data['color'] ='Color';			
		$data['intensity'] ='Intensity';			
		$data['overtone'] ='Overtone';
		$data['clarity'] ='Clarity';
		$data['polish'] ='Polish';
		$data['symmentry'] ='Symm';
		$data['cut'] ='Cut';
		$data['mesurment'] ='Msurmnt';
		$data['table_pc'] ='Table%';
		$data['depth_pc'] ='Depth%';
		$data['gridle'] ='Gridle';
		$data['location'] ='LOC'; 
		$data['remark'] ='Remark';
		return  $data;
	}
	public function getMemoParty()
	{
		$data = array();
					
		$data['entryno'] ='Entry No';
		$data['party'] ='Company'; 		
		$data['invoicedate'] ='Date'; 		
		$data['reference'] ='Reference'; 
		$data['invoiceno'] ='Invoice'; 		
		$data['tpp'] ='Pcs'; 
		$data['tpc'] ='Carat'; 		 
		$data['tp'] ='Price'; 
		$data['final_amount'] ='Amount'; 
		$data['terms'] ='Term';
		$data['duedate'] ='Due Date';
		return  $data;
	}
	
}
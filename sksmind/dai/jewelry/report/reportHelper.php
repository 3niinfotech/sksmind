<?php 

//include_once("../database.php");
//include_once("../variable.php");

class reportHelper
{
	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn = $db;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	
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
		$rs = mysqli_query($this->conn,"SELECT code,name FROM dai_attribute WHERE company=".$_SESSION['companyId']." ORDER BY short_order" );			
		
		while($row = mysqli_fetch_assoc($rs))
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
		$data['mfg_code'] ='Mfg Code'; 
		$data['sku'] ='Sku'; 
		$data['lab'] ='Lab'; 
	 /*	$data['report_no'] ='Report No.'; 
		$data['rought_pcs'] ='R.Pcs';  
		$data['rought_carat'] ='R.Carat';*/ 
		$data['pcs'] ='Pcs'; 
		$data['carat'] ='Carat'; 
		$data['price'] ='Price'; 
		$data['amount'] ='Amount';
		$data['igi_code'] ='IGI Code';
		$data['igi_color'] ='IGI Color';
		$data['igi_clarity'] ='IGI Clarity';
		$data['igi_amount'] ='IGI Amount';
		$data['shape'] ='Shape';
		$data['color'] ='Color';			
		$data['clarity'] ='Clarity';
		$data['color_code'] ='Color Code';
		$data['group_type'] ='Group Type';
		/* $data['polish'] ='Polish';
		$data['intensity'] ='Intensity';			
		$data['overtone'] ='Overtone';
		$data['symmentry'] ='Symm';
		$data['cut'] ='Cut';
		$data['mesurment'] ='Msurmnt';
		$data['table_pc'] ='Table%';
		$data['depth_pc'] ='Depth%';
		$data['gridle'] ='Gridle'; 
		$data['location'] ='LOC'; */
		$data['remark'] ='Remark';
		return  $data;
	}
	public function getMemoPackageJew()
	{
		$data = array();
					
		$data['party'] ='Company';
		$data['out_date'] ='Date'; 
		$data['sku'] ='Sku'; 		
		$data['jew_design'] ='Design'; 
		$data['jew_type'] ='Type'; 
		$data['gold'] ='Gold'; 
		$data['metal'] ='Metal'; 
		$data['gold_color'] ='Gold Color'; 
		$data['gross_weight'] ='Gross Weight'; 
		$data['pg_weight'] ='Pg Weight'; 
		$data['net_weight'] ='Net Weight'; 
		$data['rate'] ='Rate'; 
		$data['amount'] ='Amount';
		$data['other_code'] ='Other Code';
		$data['other_amount'] ='Other Amount';
		$data['labour_rate'] ='Labour Rate';
		$data['labour_amount'] ='Other Amount';
		$data['total_amount'] ='Total Amount';
		$data['sell_price'] ='Sell Price';
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
	public function getJewMemoParty()
	{
		$data = array();
					
		$data['sku'] ='Sku'; 		
		$data['party'] ='Company'; 		
		$data['date'] ='Date'; 		
		$data['tgross_weight'] ='Gross Weight'; 
		$data['tpg_weight'] ='Pg Weight'; 		 
		$data['tnet_weight'] ='Net Weight'; 
		$data['final_amount'] ='Amount'; 
		//$data['terms'] ='Term';
		//$data['duedate'] ='Due Date';
		return  $data;
	}
	
}
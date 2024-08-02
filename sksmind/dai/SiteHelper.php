<?php 
//include_once("../database.php");
//include_once("../variable.php");

class SiteHelper
{

	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn=$db;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	
	public function getRapnetCSValue($stockData)
	{
		$csvString = "";
		$count = count($stockData);
		$i=1;
		foreach($stockData as $sd)
		{
			//$csvString = (implode(",",$data));
			if($count != $i)
				$csvString = "\n";
				
			$i++;
		}
		return  $csvString;
	}
	public function getShape($s)
	{
		$shape = ucfirst(strtolower($s));
		
		$shapeArray = array('Round','Cushion','Oval','Heart','Marquise','Emerald','Radiant','Pear','Asscher','Princess','Trilliant','Rose','Shield','Square');
		foreach($shapeArray as $k=>$v )
		{
			if (strpos($shape, $v) !== false) {
				return $v;
			}
		}
		return "";
	}
	public function getAtrributeSet($lab,$col,$inten)
	{
		$col = strtolower($col);
		if($lab == "")
		{	
		
			if ($inten == '') 
			{
				return 'NON-GIA Fancy Diamond';
			}
			else
			{
				return 'NON-GIA White Diamond';
			}
		}
		else
		{
			if ($inten != '') 
			{
			
				return 'Fancy Diamond';
			}
			else
			{
				return 'White Diamond';
			}
		}	
		return "";
	}
	public function getCategory($col,$inten)
	{
		$col = strtolower($col);
		$return = "";
		if ($inten != '')
		{
			$return = 'Fancy Diamond';
			$colorArray = array('Black','Blue','Brown','Champagne','Cognac','Gray','Green','Orange','Pink','Purple','Red','Violet','White','Yellow');
			foreach($colorArray as $k=>$v )
			{
				if (strpos($col, strtolower($v)) !== false) {
					$return .= ','.$v;
				}
			}
		}
		else
		{
			$return = 'White Diamond';
		}
			
		return $return;
	}
	
	public function getColor($col,$inten)
	{
		if ($inten == '') 
		{
			return $col[0];
		}
		else
		{
			return ucfirst($col);
		}
		
	}
	public function getFancyColor($col,$inten)
	{
		/*$col1 = strtolower($col);
		$return = "";
		if (strpos($col1, 'fancy') !== false) 
		{
			$colorArray = array('Black','Blue','Brown','Brown Pink','Champagne','Cognac','Gray','Green','Orange','Pink','Pink Brown','Purple','Red','Violet','White','Yellow');
			foreach($colorArray as $k=>$v )
			{
				if (strpos($col1, strtolower($v)) !== false) {
					$return = $v;
				}
			}
		}*/
		if ($inten == '') 
		{
			return '';
		}
		else
		{
			return ucfirst($col);
		}
	}
	public function getDetail($id)
    {
		$data = array();		
		$rs = mysqli_query($this->conn,"SELECT * FROM dai_product  p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
		}				
		return  $data;			
    }
	
	public function isInStock($out,$hold,$hide,$visibility)
	{
//echo "Out:".$out." Hold:".$hold." hide:".$hide." Vis:".$visibility ;
		if($visibility==0 || $hold==1 || $hide==1 || $out =='sale' || $out =='export')
		{
			return 0;
		}
		else 
		{
			return 1;
		}
			
	}
	
	public function status($out,$hold,$hide,$visibility)
	{
//echo "Out:".$out." Hold:".$hold." hide:".$hide." Vis:".$visibility ;
		if($visibility==0 || $hold==1 || $hide==1 || $out =='sale' || $out =='export')
		{
			return 2;
		}
		else 
		{
			return 1;
		}
			
	}
	public function createData($data)
	{
		//echo $this->getAtrributeSet($data['lab'],$color,$data['intensity']);
		
		$color = ($data['color']=='') ? $data['main_color'] : $data['color'];
		//echo $data['color']."-->".$this->getCategory($data['color'],$data['intensity']);
		$stage = (isset($data['outward']) && ( $data['outward']=='memo' || $data['outward']=='consign') ) ? 0 : 1;
		$FData =  array(
		'sku'=>$data['sku'],
		'attributeset' => $this->getAtrributeSet($data['lab'],$color,$data['intensity']),
		'categories' => $this->getCategory($color,$data['intensity']),		
		'name' => $data['main_color'],
		'description' => $color,
		'short_description' => $color,				
		'base_price' => $data['cost'],
		'price' => $data['price'],		
		'is_in_stock' => $this->isInStock($data['outward'],$data['hold'],$data['hide'],$data['visibility']),
		'status' => $this->status($data['outward'],$data['hold'],$data['hide'],$data['visibility']),
		'packet_number' => $data['mfg_code'],
		'lab' => $data['lab'],
		'certificate' => $data['report_no'],
		'shape' => $this->getShape($data['shape']),
		'carat' => $data['polish_carat'],
		'fancy_color_intensity' => $data['intensity'],
		'overtone' => $data['overtone'],
		'fancy_color' => $this->getFancyColor($color,$data['intensity']),	
		'color' => $this->getColor($color,$data['intensity']),	
		'clarity' => $data['clarity'],
		'polish' =>  $data['polish'],
		'symmetry' =>  $data['symmentry'],
		'fluorescence_intensity' =>  $data['f_intensity'],
		'measurements' =>  trim($data['mesurment']),
		'table' =>  $data['table_pc'],
		'depth' =>  $data['depth_pc'],
		'cut_grade' =>  $data['cut'],	
		'location' =>  $data['location'],
		'stage' => $stage,
		);		
		return $FData;
	}
	public function uploadData($id)
	{
		//$data = array('data'=>array(array('sku'=>'3ni-G0281','attributeset' => '10','categories' => array('Fancy Diamond'),'websites' => array(1),'name' => 'Product name21',	'description' => 'Product description','short_description' => 'Product short description','weight' => '10','status' => '1','visibility' => '4','base_price' => '100',
		//	'price' => '100','qty' => 2026,'is_in_stock' => 0,'packet_number' => '2016/2','lab' => 'GIA','certificate' => '5213895348',	'shape' => 'Pear','carat' => '0.3','fancy_color_intensity' => 'Fancy',
		//	'overtone' => 'Grayish','fancy_color' => 'Blue','color' => 'D','clarity' => 'I1','polish' => 'VG','symmetry' => 'G','fluorescence_intensity' => 'MB','measurements' => '10.07*8.57*3.84','table' => '74','depth' => '44.8','cut_grade' => 'VG','callforquote' => 'Yes')));
		$data = $this->getDetail($id);	
		if($data['company'] !=1)
			return false;
		if($data['group_type'] == "single" &&  $data['is_uploadsite'] == 1)
		{
			
			$Fdata = $this->createData($data);
		 	 /* echo "<pre>";
			print_r($Fdata);
			exit;  */
			$url = 'https://www.shreehk.com/index.php/sksm/index/addSoftProduct/';
			$str_data = json_encode($Fdata);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$str_data);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
			$result = curl_exec($ch);
			curl_close($ch);  // Seems like good practice
			
			$jResult = (array)json_decode($result);
	
			if($jResult['status'] == 1)
			{
				$sql = "UPDATE dai_product SET site_upload=1 WHERE id=".$id;
				$rs = mysqli_query($this->conn,$sql);
			} 
		}
		else
		{		
				$sql = "UPDATE dai_product SET site_upload=1 WHERE id=".$id;
				$rs = mysqli_query($this->conn,$sql);
		}	
	}
	
	

}
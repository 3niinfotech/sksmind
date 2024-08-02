<?php 
//include_once("../database.php");
//include_once("../variable.php");

class RapHelper
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
	
	public function getRapnetCSVHeader()
	{
		$data = array();
		$data[0] = 'Stock #';
		$data[1] = 'Availability';
		$data[2] = 'Shape';
		$data[3] = 'Weight';
		$data[4] = 'Color';
		$data[5] = 'Clarity';
		$data[6] = 'Cut Grade';
		$data[7] = 'Polish';
		$data[8] = 'Symmetry';
		$data[9] = 'Fluorescence Intensity';
		$data[10] = 'Fluorescence Color';
		$data[11] = 'Measurements';
		$data[12] = 'Lab';
		$data[13] = 'Report #';
		$data[14] = 'Treatment';
		$data[15] = 'RapNet Price';
		$data[16] = 'Rapnet Discount %';
		$data[17] = 'Cash Price';
		$data[18] = 'Cash Price Discount %';
		$data[19] = 'Fancy Color';
		$data[20] = 'Fancy Color Intensity';
		$data[21] = 'Fancy Color Overtone';
		$data[22] = 'Depth %';
		$data[23] = 'Table %';
		$data[24] = 'Girdle Thin';
		$data[25] = 'Girdle Thick';
		$data[26] = 'Girdle %';
		$data[27] = 'Girdle Condition';
		$data[28] = 'Culet Size';
		$data[29] = 'Culet Condition';
		$data[30] = 'Crown Height';
		$data[31] = 'Crown Angle';
		$data[32] = 'Pavilion Depth';
		$data[33] = 'Pavilion Angle';
		$data[34] = 'Laser Inscription';
		$data[35] = 'Cert comment';
		$data[36] = 'Country';
		$data[37] = 'State';
		$data[38] = 'City';
		$data[39] = 'Is Matched Pair Separable';
		$data[40] = 'Pair Stock #';
		$data[41] = 'Allow RapLink Feed';
		$data[42] = 'Parcel Stones';
		$data[43] = 'Report Filename';
		$data[44] = 'Diamond Image';
		$data[45] = 'Sarine Loupe';
		$data[46] = 'Trade Show';
		$data[47] = 'Key to symbols';
		$data[48] = 'Shade';
		$data[49] = 'Star Length';
		$data[50] = 'Center Inclusion';
		$data[51] = 'Black Inclusion';
		$data[52] = 'Milky';
		$data[53] = 'Open Inclusion';
		$data[54] = 'Member Comment';
		$data[55] = 'Report Issue Date';
		$data[56] = 'Report Type';
		$data[57] = 'Lab Location';
		$data[58] = 'Brand';
		$data[59] = 'Seller Spec';
		$data[60] = 'Eye Clean';
		
		

		/* $csvString = "Stock #,Availability,Shape,Weight,Color,Clarity,Cut Grade,Polish,Symmetry,Fluorescence Intensity,Fluorescence Color,Measurements,Lab,Certificate #,Treatment,RapNet Price,Rapnet Discount %,Cash Price,Cash Price Discount %,Fancy Color,Fancy Color Intensity,Fancy Color Overtone,Depth %,Table %,Girdle Thin,Girdle Thick,Girdle %,Girdle Condition,Culet Size,Culet Condition,Crown Height,Crown Angle,Pavilion Depth,Pavilion Angle,Laser Inscription,Cert comment,Country,State,City,Time to location,Is Matched Pair Separable,Pair Stock #,Allow RapLink Feed,Parcel Stones,Certificate Filename,Diamond Image,Sarine Loupe,Trade Show,Key to symbols,Shade,Star Length,Center Inclusion,Black Inclusion,Member Comment,Report Issue Date,Report Type,Lab Location,Brand,Milky"; */
		
		
		$csvString = "Stock #,Availability,Shape,Weight,Color,Clarity,Cut Grade,Polish,Symmetry,Fluorescence Intensity,Fluorescence Color,Measurements,Lab,Report #,Treatment,RapNet Price,Rapnet  Discount %,Cash Price,Cash Price Discount %,Fancy Color,Fancy Color Intensity,Fancy Color Overtone,Depth %,Table %,Girdle Thin,Girdle Thick,Girdle %,Girdle Condition,Culet Size,Culet Condition,Crown Height,Crown Angle,Pavilion Depth,Pavilion Angle,Laser Inscription,Cert comment,Country,State,City,Is Matched Pair Separable,Pair Stock #,Allow RapLink Feed,Parcel Stones,Report Filename,Diamond Image,Sarine Loupe,Trade Show,Key to symbols,Shade,Star Length,Center Inclusion,Black Inclusion,Milky,Open Inclusion,Member Comment,Report Issue Date,Report Type,Lab Location,Brand,Seller Spec,Eye Clean";
		
		return  $csvString;
	}
	
	public function getAvailability($a,$h,$hide,$visibility)
	{
		if($h==1 || $hide == 1 || $visibility == 0)
		{return 'NA';}
		
		if($a =='memo' || $a == 'consign')
			return 'M';
		elseif($a =='sale' || $a =='export')	
			return 'NA';
		elseif($a =='') 
			return 'G';
	}
	public function getShape($s)
	{
		$shape = ucfirst(strtolower($s));
		
		$shapeArray = array('Round','Cushion','Oval','Heart','Marquise','Emerald','Radiant','Pear','Asscher','Princess','Trilliant','Rose','Shield','Square','Other');
		foreach($shapeArray as $k=>$v )
		{
			
			if (strpos($shape, $v) !== false) {
				return $v;
			}
		}
		return "";
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
		$col1 = strtolower($col);
		$return = "";
		if ($inten == '') 
		{
			return '';
		}
		else
		{
			$colorArray = array('Black','Blue','Brown','Brown Pink','Champagne','Cognac','Gray','Green','Orange','Pink','Pink Brown','Purple','Red','Violet','White','Yellow');
			foreach($colorArray as $k=>$v )
			{
				if (strpos($col1, strtolower($v)) !== false) {
					$return = $v;
				}
			}					
		}
		return $return;
	}
	public function createData($data)
	{
		
		$color = ($data['main_color'] =='' )?$data['color']:$data['main_color'];
		$price =0;
		if($data['company'] == 3)
		{
			$price = $data['rap_price'];
		}
		else
		{
			$price = $data['price'];
		}
		$data1[0] = $data['sku'];
		$data1[1] = $this->getAvailability($data['outward'],$data['hold'],$data['hide'],$data['visibility']);
		$data1[2] = $this->getShape($data['shape']);
		$data1[3] = $data['polish_carat'];
		$data1[4] = $this->getColor($color,$data['intensity']);
		$data1[5] = $data['clarity'];
		$data1[6] = $data['cut'];
		$data1[7] = $data['polish'];
		$data1[8] = $data['symmentry'];
		$data1[9] = $data['f_intensity'];
		$data1[10] = '';
		$data1[11] = $data['mesurment'];
		$data1[12] = $data['lab'];
		$data1[13] = $data['report_no'];
		$data1[14] = '';
		$data1[15] = $price;
		$data1[16] = '';
		$data1[17] = '';
		$data1[18] = '';
		$data1[19] = $this->getFancyColor($color,trim($data['intensity']));
		$data1[20] = trim($data['intensity']);
		$data1[21] = trim($data['overtone']);
		$data1[22] = trim($data['depth_pc']);
		$data1[23] = trim($data['table_pc']);
		$data1[24] = '';
		$data1[25] = '';
		$data1[26] = '';
		$data1[27] = '';
		$data1[28] = '';
		$data1[29] = '';
		$data1[30] = '';
		$data1[31] = '';
		$data1[32] = '';
		$data1[33] = '';
		$data1[34] = '';
		$data1[35] = '';
		$data1[36] = $data['location'];
		$data1[37] = '';
		$data1[38] = $data['location'];
		$data1[39] = '';
		$data1[40] = TRUE;
		$data1[41] = '';
		$data1[42] = 1;
		$data1[43] = '';
		$data1[44] = 'http://shreehk.com/rapnet.php?sku='.$data['sku'];
		$data1[45] = ''; 
		$data1[46] = '';
		$data1[47] = '';
		$data1[48] = '';
		$data1[49] = '';
		$data1[50] = '';
		$data1[51] = '';
		$data1[52] = '';
		$data1[53] = '';
		$data1[54] = '';
		$data1[55] = '';
		$data1[56] = '';
		$data1[57] = '';
		$data1[58] = '';	
		$data1[59] = '';
		$data1[60] = '';	
		/* echo "<pre>";
		print_r($data);
		print_r($data1);
		exit; 
		$string = $this->getRapnetCSVHeader();	
		$string .="\n".implode(",",$data1);
		return $string; */
		
		$string ="\n".implode(",",$data1);
		return $string;
	}
	public function getRapDetail($id)
	{	
		$data = array();
		$rs = mysqli_query($cn,"SELECT * FROM company WHERE id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data['rapnet_id'] =  $row['rapnet_id'];
			$data['rapnet_password'] =  $row['rapnet_password'];
		}
		return $data;
	}
	public function getDetail($id)
    {
		$data = array();		
		$rs = mysqli_query($cn,"SELECT * FROM dai_product  p LEFT JOIN dai_product_value  pv ON p.id = pv.product_id WHERE id=".$id);
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
		}				
		return  $data;			
    }
	public function uploadData($ids)
	{
	
		try{
		//$data = array('data'=>array(array('sku'=>'3ni-G0281','attributeset' => '10','categories' => array('Fancy Diamond'),'websites' => array(1),'name' => 'Product name21',	'description' => 'Product description','short_description' => 'Product short description','weight' => '10','status' => '1','visibility' => '4','base_price' => '100',
		//	'price' => '100','qty' => 2026,'is_in_stock' => 0,'packet_number' => '2016/2','lab' => 'GIA','certificate' => '5213895348',	'shape' => 'Pear','carat' => '0.3','fancy_color_intensity' => 'Fancy',
		//	'overtone' => 'Grayish','fancy_color' => 'Blue','color' => 'D','clarity' => 'I1','polish' => 'VG','symmetry' => 'G','fluorescence_intensity' => 'MB','measurements' => '10.07*8.57*3.84','table' => '74','depth' => '44.8','cut_grade' => 'VG','callforquote' => 'Yes')));
		
		$temp ='';
		if(empty($ids))
			return false;
		$i=0;
		foreach($ids as $k=>$id)
		{
			$i++;
			if($i == 200)
				break;
			$data = $this->getDetail($id);	
			
			if( $data['visibility'] == 1 && $data['lab'] == 'GIA' && strtolower($data['group_type']) == 'single' && $data['is_uploadrapnet'] == 1 )
			{
				
				$temp .= $this->createData($data);
				
			}
			$sql = "UPDATE dai_product SET rapnet_upload =1 WHERE id=".$id;
			$rs = mysqli_query($cn,$sql);
			
		}
		
		$UploadCSVString = $this->getRapnetCSVHeader();
		$UploadCSVString .= $temp;
			
			//$UploadCSVString = "Stock #,Availability,Shape,Weight,Color,Clarity,Cut Grade,Polish,Symmetry,Fluorescence Intensity,Fluorescence Color,Measurements,Lab,Certificate #,Treatment,RapNet Price,Rapnet  Discount %,Cash Price,Cash Price Discount %,Fancy Color,Fancy Color Intensity,Fancy Color Overtone,Depth %,Table %,Girdle Thin,Girdle Thick,Girdle %,Girdle Condition,Culet Size,Culet Condition,Crown Height,Crown Angle,Pavilion Depth,Pavilion Angle,Laser Inscription,Cert comment,Country,State,City,Time to location,Is Matched Pair Separable,Pair Stock #,Allow RapLink Feed,Parcel Stones,Certificate Filename,Diamond Image,Sarine Loupe,Trade Show,Key to symbols,Shade,Star Length,Center Inclusion,Black Inclusion,Member Comment,Report Issue Date,Report Type,Lab Location,Brand,Milky\nSP-021,G,Cushion,6.06,Y,VVS2,,VG,VG,F,,10.13x9.30x6.89,GIA,6173951233,,5500,,,,,,,74.1,60,,,,,,,,,,,,,Hong Kong,,Hong Kong,,TRUE,,1,,6173951233.pdf,http://shreehk.com/rapnet.php?sku=HAF-G478,,,,,,,,,,,,,";
			$URLAuth = "https://technet.rapaport.com/HTTP/Authenticate.aspx";
			$rapData = $this->getRapDetail(1);
			$formData["Username"] = $rapData['rapnet_id'];
			$formData["Password"] = $rapData['rapnet_password'];


			// call Authantication Url

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URLAuth);
			curl_setopt($ch, CURLOPT_HEADER, 0);           
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);
			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
				
			// Printing Authantication String	
			//echo "Authantication String : ";	
			//echo $response."<br>";

			$URL = "http://technet.rapaport.com/HTTP/Upload/Upload.aspx?Method=string";
			unset($formData);
			$formData = array();
			$formData["UploadCSVString"] = $UploadCSVString;
			$formData["ticket"] = $response;			
			$formData["ReplaceAll"] = "false";

			//echo "<br> Data Passed: ";
			//print_r($formData);

			//Callng upload string Url
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL);
			curl_setopt($ch, CURLOPT_HEADER, 0); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			//echo "<br> Response : ";
			//print_r( $httpCode);	
			//echo "<br>";	
			//print_r( $response);
			//exit;
			
			$error_message = $response;
			$sku = implode(",",$ids);
		   $id = 0;
		   if(strpos($response,"failed"))
		   {
				$date = date("Y-m-d H:i:s");
				$sqlerrors = "INSERT INTO dai_errors (product_id,sku,error_message,date) VALUES (".$ids.",'".$sku."','$error_message','$date')"; 
				$rserrors = mysqli_query($cn,$sqlerrors);
		   }		   
		    
			
			
		}
		   catch(Exception $e)
		   {
			echo $e->getMessage();
		   }
	}

}
<?php
//error_reporting(E_ALL); 
//ini_set("error_reporting", E_ALL);

require_once('../variable.php');
require_once('../database.php');
require_once('../dai/Helper.php');
global $cn;

if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{
	$_POST['fn']($cn,$mainUrl);
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	
	$_GET['fn']($cn,$mainUrl);
}

function getStockList($cn,$mainUrl)
{
	
	$post = $_POST;
	
	$location = $sku = $carat = $type = $package = $shape = $color =  $intensity = $overtone = $clarity = $f_intensity = $memo = $symmentry = $cut = $polish =  "";
	
	// Packat - SKU - Report Search
	$page = $post['page_no'];
	
	if(isset($post['packet_number']) and $post['packet_number']!="")
	{ 
		$i=0;
		$post['packet_number'] = str_replace(' ', '', $post['packet_number']);
		//$tem= explode(',', $post['packet_number']);
	
		/* if(count($tem) != 1)
		{
			$skua = implode("','",$tem);
			$sku .= " and ( p.mfg_code IN ('".$skua."') or p.sku IN ('".$skua."') or pv.report_no IN ('".$skua."') )";				
		}
		else
		{
			 */
			 $sku = " and ( p.mfg_code LIKE '%".$post['packet_number']."%' or p.sku LIKE '%".$post['packet_number']."%' or pv.report_no LIKE '%".$post['packet_number']."%' ) ";
		//}
	}		
	//carat from and to search
	if( (isset($post['carat']) && $post['carat']!="0,0"))
	{ 
		$tmp = explode(",",$post['carat']);			
		$carat = " and p.polish_carat BETWEEN ".$tmp[0]." and ".$tmp[1];
	}

	//type of diamond package
	
	$type .= " and p.group_type = 'single'"; 
	
	
	
	if(isset($post['shape']) && $post['shape']!='')
	{
		
		$shape = " and (";
		$temp = explode(',',$post['shape']);
		$count = count($temp) - 1;
		
		foreach( $temp as $k=>$v)
		{
			if($count == $k )
				$shape .= " pv.shape Like '%".$v."%'"; 
			else
				$shape .= " pv.shape Like '%".$v."%' ||"; 
		}
		$shape .= ") ";
	}
	
	if(isset($post['color']) && $post['color']=="white" && $post['white']!='')
	{
		$color = " and (";
		$temp = explode(',',$post['white']);
		$count = count($temp) - 1;
		foreach($temp as $k=>$v)
		{
			if($count == $k )
				$color .= " pv.color LIKE '".$v."' || pv.color LIKE '".$v."-%'"; 
			else
				$color .= " pv.color LIKE '".$v."' || pv.color LIKE '".$v."-%' || "; 
		}
		$color .= ") ";
	}
	if(isset($post['color']) && $post['color']=="fancy" && $post['fancy']!='')
	{
		$color = " and (";
		$temp = explode(',',$post['fancy']);
		$count = count($temp) - 1;
		foreach($temp as $k=>$v)
		{
			if($count == $k )
				$color .= " pv.color = '".$v."'"; 
			else
				$color .= " pv.color = '".$v."' ||"; 
		}
		$color .= ") ";
	}
	
	if(isset($post['fluorescence_intensity']) && $post['fluorescence_intensity']!='')
	{
		$intensity = " and (";
		$temp = explode(',',$post['fluorescence_intensity']);
		$count = count($temp) - 1;
		foreach($temp as $k=>$v)
		{
			if($count == $k )
				$intensity .= " pv.intensity = '".$v."'"; 
			else
				$intensity .= " pv.intensity = '".$v."' ||"; 
		}
		$intensity .= ") ";
	}
	
	
	if(isset($post['fancy_color_intensity']) && $post['fancy_color_intensity']!='' )
	{
		$f_intensity = " and (";
		$temp = explode(',',$post['fancy_color_intensity']);
		$count = count($temp) - 1;
		foreach($temp as $k=>$v)
		{
			if($count == $k )
				$f_intensity .= " pv.f_intensity = '".$v."'"; 
			else
				$f_intensity .= " pv.f_intensity = '".$v."' ||"; 
		}
		$f_intensity .= ") ";
	}
	if(isset($post['clarity']) && $post['clarity'] !='')
	{
		$clarity = " and (";
		$temp = explode(',',$post['clarity']);
		$count = count($temp) - 1;
		foreach($temp as $k=>$v)
		{
			if($count == $k )
				$clarity .= " pv.clarity = '".$v."'"; 
			else
				$clarity .= " pv.clarity = '".$v."' ||"; 
		}
		$clarity .= ") ";
	}
	if(isset($post['overtone']) && $post['overtone'])
	{
		$overtone = " and (";			
		$temp = explode(',',$post['overtone']);
		$count = count($temp) - 1;
		foreach($temp as $k=>$v)
		{
			if($count == $k )
				$overtone .= " pv.overtone = '".$v."'"; 
			else
				$overtone .= " pv.overtone = '".$v."' ||"; 
		}
		$overtone .= ") ";
	}
	
	if(isset($post['cut_grade']) && $post['cut_grade']!='')
	{
		$cut = " and (";
		$temp = explode(',',$post['cut_grade']);
		$count = count($temp) - 1;
		foreach($temp as $k=>$v)
		{
			if($count == $k )
				$cut .= " pv.cut = '".$v."'"; 
			else
				$cut .= " pv.cut = '".$v."' ||"; 
		}
		$cut .= ") ";
	}
	$lab = '';
	if(isset($post['lab']) && $post['cut_grade'] !='' )
	{
		$lab = " and (";
		$temp = explode(',',$post['lab']);
		$count = count($temp) - 1;
		foreach($temp as $k=>$v)
		{
			if($count == $k )
				$lab .= " p.lab = '".$v."'"; 
			else
				$lab .= " p.lab = '".$v."' ||"; 
		}
		$lab .= ") ";
	}
	
	$query = "SELECT * FROM dai_product p LEFT JOIN dai_product_value pv ON p.id = pv.product_id WHERE p.company=1 and visibility=1 and polish_carat <>0 and p.box_id='' and p.parcel_id='' and p.outward <> 'sale' and p.outward <> 'export' ".$sku.$carat.$type.$shape.$package.$color.$intensity.$f_intensity.$overtone.$clarity.$symmentry.$cut." ORDER BY sku";	
	//echo $query." LIMIT ".$page."0,10";
	
	


	//$p = $page - 1;
	$rs = mysql_query($query." LIMIT ".$page."0,10");
	
	
	$file = 'log.txt';
	// Open the file to get existing content
	$current = file_get_contents($file);
	// Append a new person to the file
	$current .= $query." LIMIT ".$p."0,10";
	// Write the contents back to the file
	
	
	$data = array();
	while($row = mysql_fetch_assoc($rs))
	{
		
		$current .= $row['sku'];
		if(($row['polish_carat'] == "0" && $row['group_type']=="box" && $row['box_products'] =="" ) || ($row['group_type']=="parcel" && $row['parcel_products'] =="" && $row['polish_carat'] == "0" ))
				continue;
		$temp = array();		
		
		$temp['name'] = $row['sku'];//$name;
		$temp['id'] =    $row['id'];;								
		$temp['packet_number'] =    $row['mfg_code'];
		$temp['clarity'] =   $row['clarity'];
		$temp['carat'] =   $row['polish_carat'];		
		$temp['image'] = $mainUrl.'diaimg/ni.jpg';
		$temp['status'] = 2;	
		$data[] = (array) $temp;
	}
	
	$page++;
	$rs = mysql_query($query);
	$num_rows = mysql_num_rows($rs);
	
	if($num_rows >= 1)
		$total_page = $num_rows / 10;
	else
		$total_page = 0;
	
	if(is_float($total_page))
	{
		$total_page = (int)$total_page;
		$total_page++;
	}		
	$response = array();
	 if($page > $total_page )
	 {
		$response = array('status' => '2','message' => "No More Data",'data' => $data,'page_no'=>$page,'total_page' => $total_page);			
	 }
	 else
	 {
		$response = array('status' => '1','message' => "Success",'data' => $data,'page_no'=>$page,'total_page' => $total_page);			
	 }  
	file_put_contents($file, json_encode($response));
	echo json_encode($response);
}

function getDiamondDetail($cn,$mainUrl)
{
	$post = $_POST;
	$query = "SELECT * FROM dai_product p LEFT JOIN dai_product_value pv ON p.id = pv.product_id WHERE id=".$post['id'];	

	$rs = mysql_query($query);
	
	$array = array();
	while($row = mysql_fetch_assoc($rs))
	{
	
		$array['id'] =    $row['id'];
		$sku = $row['sku'];					
		$array['name'] =  $sku;					
		$array['packet_number'] = $row['mfg_code'];	
		$array['size'] =    $row['size'];	
		$array['base_price'] = $row['price'];	
		$array['price'] =  $row['sell_price']; //	 	
		$array['shape'] = $row['shape'];
		$array['color'] = $row['color'];
		$array['fancy_color'] = '';
		$array['fancy_color_intensity'] = $row['f_intensity'];
		$array['overtone'] = $row['overtone'];
		$array['clarity'] = $row['clarity'];
		$array['cut_grade'] = $row['cut'];
		$array['lab'] = $row['lab'];					
		$array['url'] =  ''; //Mage::getBaseUrl().$_product->getUrlKey().".html";
		$array['gia_report'] =  "http://www.gia.edu//report-check?reportno=".$row['report_no'];
		$array['image'] =  $mainUrl.'diaimg/ni.jpg';
		
		$array['broker_url'] = '';
		
		$array['gia'] = $row['report_no'];
		$array['table'] = $row['table_pc'];
		$array['depth'] = $row['depth_pc'];
		$array['measurements'] = $row['mesurment'];
		
		$array['polish'] = $row['polish'];
		$array['symmetry'] = $row['symmentry'];
		$array['fluorescence_intensity'] = $row['f_intensity'];
		
					
		$name = $row['polish_carat'] .' carat, '. $row['color'].' '. $row['shape']. ' Shape, ' . $row['clarity'] . ' Clarity';
		$array['share_name'] =	$sku." ".$name;
		$array['video_url'] = '';
		$array['stock'] = 2;
		$array['status'] = 2;		
		$array['location'] =  $row['location'];
	}
	echo json_encode(array('status' => '1','message' => "Success",'data' => $array));						
}

function getDiamondImg($cn,$mainUrl)
{
	$post = $_POST;
	$query = "SELECT * FROM dai_product p LEFT JOIN dai_product_value pv ON p.id = pv.product_id WHERE id=".$post['id'];	

	$rs = mysql_query($query);
	
	$array = array();
	while($row = mysql_fetch_assoc($rs))
	{
	
		//$array['id'] =    $row['id'];
		$sku = $row['sku'];							
	}
	$t['name'] =  'Niti Gems';
	$t['image'] =  $mainUrl.'diaimg/ni.jpg';
	$array[] = $t;
	echo json_encode(array('status' => '1','message' => "Success",'data' => $array));						
}
<?php
echo "<pre>";
$url = 'http://ugamexport.com/niti/api/snjcall.php';

	$postField = array();
	$postField['fn']= 'getStockList';
	//$postField['fn']= 'getDiamondDetail';
	//$postField['id']= 335;
	$postField['page_no']= 0;
	$postField['shape']= '';
    $postField['cut_grade']= '';	
    $postField['lab']= '';
    $postField['fluorescence_intensity']= '';
    $postField['clarity']= '';
    $postField['packet_number']= '107-1101';
    $postField['white']= '';
    $postField['price']= '0,0';
    $postField['overtone']= '';
    $postField['color']= 'white';
    $postField['carat']= '0,0';
    $postField['fancy_color_intensity']= '';
    $postField['fancy']='';

	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$postField);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
echo "<pre>";
curl_close ($ch);
echo $server_output;
$test = json_decode($server_output);
echo "<br>";
//print_r($server_output );
print_r($test );

?>
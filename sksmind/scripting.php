<?php
//echo phpinfo();
//include 'app/Mage.php';
//Mage::app();
echo '<pre>';

//error_reporting(E_ALL | E_STRICT);	
//ini_set('display_errors', 1);

try{

	$url = "https://www.gia.edu/otmm_wcs_int/proxy-report/?ReportNumber=6222326638&url=https://myapps.gia.edu/ReportCheckPOC/pocservlet?ReportNumber=6222326638";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);           
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
	//print_r($httpCode);
	print_r( $response);
	$oXML = new SimpleXMLElement($response);	
	$temp = (array)$oXML->REPORT_DTLS->REPORT_DTL;
	
	$message = $oXML->REPORT_DTLS->REPORT_DTL->MESSAGE;
	$data = array();
	if($message == '')
	{
		foreach($temp as $key=>$t){
			if($t=="")
				continue;
			if(strtolower($key) =="length")	
				$data['measurement'] = $t ;
			else
				$data[strtolower($key)] = $t ;
		}
	}
	else
	{
		$data['message'] = 'Please check your entries and try again.';
	}

	print_r($data);
	
	
}
catch(Exception $e){
    echo $e->getMessage();
}

/*
$api_url = "http://mobicomm.dove-sms.com/mobicomm//submitsms.jsp?user=SACHIN&key=d4c5c9993fXX&mobile=8866241186&message=OTP+to+trasnfer+money+to+8866184347+is+65254.+do+not+share+it&senderid=Samson&accusage=1" ;
//$api_url = "http://www.sambsms.com/app/smsapi/index.php?key=3585105A0D1E9F&campaign=0&routeid=26&type=text&contacts=9726521621&senderid=SMBSMS&msg=OTP+to+trasnfer+money+to+8866241186+is+1923.+do+not+share+it"; 
echo $api_url;

try{

	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);           
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
	
	echo "<br>";
	print_r($response);
	echo "<br>";
	print_r($httpCode);
	
	
}
catch(Exception $e){
    echo $e->getMessage();
}*/
?>
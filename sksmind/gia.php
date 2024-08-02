<?php

//echo phpinfo();

//include 'app/Mage.php';

//Mage::app();

echo 'testing<pre>';



//error_reporting(E_ALL | E_STRICT);	

//ini_set('display_errors', 1);


echo dirname( __FILE__ );
require_once( dirname( __FILE__ ).'/database.php');
require_once( dirname( __FILE__ ).'/variable.php');
require_once( $daiDir.'/Helper.php');
echo '<pre>';

$helper = new Helper($cn);
$rn = $_GET['rn'];	
echo $rn;
print_r($helper->getGiaReport(trim($rn)));
?>
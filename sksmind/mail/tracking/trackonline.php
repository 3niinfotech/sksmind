<?php

include("../database.php");
date_default_timezone_set('Asia/Kolkata');
$time =  date("Y-m-d H:i:s");

if (!empty($_GET['exe'])) {

	$eid = substr(base64_decode($_GET['exe']),3);
	$tempid = substr(base64_decode($_GET['tmt']),3);
	$cat_id = substr(base64_decode($_GET['cde']),3);
	$user_id = substr(base64_decode($_GET['usd']),3);
	$comp_id = substr(base64_decode($_GET['csd']),3);
	

	
	$sql = "INSERT INTO track_report(email_id,template_id,cat_id,user_id,date,comp_id) VALUES (".$eid.",".$tempid.",'$cat_id',".$user_id.",'$time',".$comp_id.")";
					 
	if (mysql_query($sql)) {
		echo "ok";
	}
}
			
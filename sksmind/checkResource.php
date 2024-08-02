<?php
include_once("database.php");

$rs=mysqli_query($cn,"SELECT * from roll where id = ".$_SESSION['roll']);

$userResource = array();
$companyResource = array();
while ($row =  mysqli_fetch_assoc($rs))
{				
	$userResource =  ($row['resource']) ? json_decode($row['resource']) : array();							
	$companyResource = ($row['company'] != "" && $row['company'] != "null" )? json_decode($row['company']) : array();							
	break;				
}			

			

function checkResource($cn,$resource)
{
	$rs=mysqli_query($cn,"SELECT * from roll where id = ".$_SESSION['roll']);
 
	$userResource = array();
	while ($row =  mysqli_fetch_assoc($rs))
	{				
		$userResource =  ($row['resource']) ? json_decode($row['resource']) : array();							
		break;				
	}	
	if(in_array('all',$userResource) || in_array($resource,$userResource))
		return 1;	
	
	return 0;	
}

function checkCompany($cn,$company)
{
	$rs=mysqli_query($cn,"SELECT * from roll where id = ".$_SESSION['roll']);
 
	$userResource = array();
	while ($row =  mysqli_fetch_assoc($rs))
	{				
		$userResource = ($row['company'] != "" && $row['company'] != "null" )? json_decode($row['company']) : array();							
		break;				
	}	
	if(in_array($company,$userResource))
		return 1;	
	
	return 0;	
}


?>

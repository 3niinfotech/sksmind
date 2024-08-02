<?php
include_once("database.php");

$resourceArray = array();


	$rs=mysqli_query($cn,"select * from resource");
	while ($row =  mysqli_fetch_assoc($rs))
	{
		$resourceArray[$row['code']] = $row['value'];
	}
	
/*$resourceArray = array(
'all'=>"All",
'user'=>"User",
'company'=>"Company",
'roll'=>"Roll",
'ems'=>"EMS",
'master'=>"Master",
'party'=>"Party",
'attribute'=>"Attribute",
'bulk'=>"Bulk",
);
*/
?>

<?php
$rs=mysqli_query($cn,"SELECT * from roll where id = ".$_SESSION['roll']);
 
$userResource = array();
while ($row =  mysqli_fetch_assoc($rs))
{				
	$userResource =  json_decode($row['resource']);							
	break;				
}
?>

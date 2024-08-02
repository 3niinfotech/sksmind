<?php
session_start();
include ('database.php');
include ('variable.php'); 
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
	exit;
endif;
$tables = "*";
if($tables == '*')
{
	$tables = array();
	$result = mysqli_query($cn,'SHOW TABLES');
	while($row = mysqli_fetch_row($result))
	{
		$tables[] = $row[0];
	}
}
else
{
	$tables = is_array($tables) ? $tables : explode(',',$tables);
}
$return ="";
//cycle through each table and format the data
foreach($tables as $table)
{
	$result = mysqli_query($cn,'SELECT * FROM '.$table);
	$num_fields = mysqli_num_fields($result);

	$return.= 'DROP TABLE '.$table.';';
	$row2 = mysqli_fetch_row(mysqli_query($cn,'SHOW CREATE TABLE '.$table));
	$return.= "\n".$row2[1].";\n";

	for ($i = 0; $i < $num_fields; $i++)
	{
		while($row = mysqli_fetch_row($result))
		{
			$return.= 'INSERT INTO '.$table.' VALUES(';
			for($j=0; $j<$num_fields; $j++)
			{
				$row[$j] = addslashes($row[$j]);
				//$row[$j] = preg_replace("/n/","\n",$row[$j]);
				if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
				if ($j<($num_fields-1)) { $return.= ','; }
			}
			$return.= ");\n";
		}
	}
	$return.="nnn";
}

//save the file
$return = str_replace("nnn"," ",$return);

$dateT = Date('Y-m-d-H-i'); 

$handle = fopen($dbPath .'src.sql','w+');
fwrite($handle,$return);
fclose($handle);


?>

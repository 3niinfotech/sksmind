<?php
session_start();
include("database.php");
 
if (isset($_SESSION['login_user']))
{
	$user_id = $_GET['id'];
	
	
	$user_count_rs = mysql_query("select user_name from user where user_id = ".$user_id);
	$user_count_list =  mysql_fetch_assoc($user_count_rs);
	$user_name = $user_count_list['user_name']; 
	
	//echo $user_name;
	
										
	$sql = "select * from email where user_id= ".$user_id;
	  
	$file_ending = "xls";
	$filename = $user_name."_clients";
	header("Content-Type: application/xls");    
	header("Content-Disposition: attachment; filename=$filename.xls");  
	header("Pragma: no-cache"); 
	header("Expires: 0");  
	$result = @mysql_query($sql); 
	
	for ($i = 1; $i < mysql_num_fields($result); $i++) {
	echo mysql_field_name($result,$i) . "\t";
	
	}
	
	//echo "email status \t";
	
	
	print("\n");    
		while($row = mysql_fetch_row($result))
		{
			$schema_insert = "";
			for($j=1; $j<mysql_num_fields($result);$j++)
			{
				if(!isset($row[$j]))
					$schema_insert .= "NULL".$sep;
				elseif ($row[$j] != "")
					
					if($j == 8)
					{
						$cat_ids = explode(',', $row[$j]);
						$total_count = " ";
						foreach($cat_ids as $cat_id)
						{
							$cat_count_rs = mysql_query("select cat_name from catalog where id = ".$cat_id);
							$cat_count_list =  mysql_fetch_assoc($cat_count_rs);
							$total_count .= ", ".$cat_count_list['cat_name'];
						}	
						$total_count = substr($total_count,3);
						$schema_insert .= "$total_count".$sep."\t";
					}
					elseif($j == 9)
					{	
						$user_name_rs = mysql_query("select user_name from user where user_id = ".$row[$j]);
						$user_name_list =  mysql_fetch_assoc($user_name_rs);
						$user_name = $user_name_list['user_name'];
						$schema_insert .= "$user_name".$sep."\t";
					}
					else
					{
						$schema_insert .= "$row[$j]".$sep."\t";
					}		
				else
					$schema_insert .= "".$sep."\t";
			}
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			//$schema_insert .= "\t";
			print(trim($schema_insert));
			print "\n";
		}
	
	//header("Location: ".$_SERVER['HTTP_REFERER']);	
}
else
{
  header("Location: index.php");
}
?>

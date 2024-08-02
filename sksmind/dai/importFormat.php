<?php
session_start();
include("../database.php");
include("Helper.php");
 	
		$helper = new Helper();
	
		/*$file_ending = "xlsx";
		$filename = "Import_Format";
		header("Content-Type: application/vnd.ms-excel");    
		header("Content-Disposition: attachment; filename=$filename.xlsx");  
		header("Pragma: no-cache"); 
		header("Expires: 0");  
		
		foreach($helper->getAttribute(1) as $k=>$v)		
		{		
		 echo $v . "\t";		
		}
*/

require_once('Classes/PHPExcel.php');


$filename = "Import_Format.xlsx";
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

$column = 'A';
foreach($helper->getImportAttribute(1) as $k=>$v)		
{
     $objPHPExcel->getActiveSheet()->SetCellValue($column.'1', $v);
	 $column++;
} 
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
$objWriter->save('php://output');



?>

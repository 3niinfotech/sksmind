<?php
session_start();
include("../database.php");
include("Helper.php");
include("jHelper.php");
 	
$helper = new Helper($cn);
	
$jhelper = new jHelper($cn);
		

require_once('Classes/PHPExcel.php');


$filename = "jewelry_Loose_diamond.xlsx";
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

$column = 'A';
foreach($jhelper->getLooseImportAttribute() as $k=>$v)		
{
     $objPHPExcel->getActiveSheet()->SetCellValue($column.'1', $v);
	 $column++;
} 
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
$objWriter->save('php://output');



?>

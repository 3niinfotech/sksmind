<?php
session_start();
include('../../../database.php');
require_once('../../Helper.php');
require_once('reportHelper.php');
require_once('../../Classes/PHPExcel.php');
include_once('../party/partyModel.php');
if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
}
function reportExport()
{
	$partyList = $pmodel->getOptionList();
	$helper = new Helper();
	$reportHelper = new reportHelper();

	$dateT = Date('Y_m_d_H_i');
	$filename = ucFirst($book[$_POST['book']])."_Report_".$dateT.".xlsx";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	try{
		$column = 'A';
		foreach($field as $k=>$v)
		{
			 
		} 
		$i=5;
		foreach($data as $d)
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i,date( 'd-m-Y', $phpdate ));
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, isset($subgroup[$d['under_subgroup']])?$subgroup[$d['under_subgroup']]:"");
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, isset($party[$d['party']])?$party[$d['party']]:"");
			$i++;
		}
	$objPHPExcel->getActiveSheet()->freezePane('A5');
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	$objWriter->save('php://output');
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
}

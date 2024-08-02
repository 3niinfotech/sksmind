<?php
//include('../../../../../variable.php');
session_start();

include('../../../database.php');
require_once('../../Helper.php');
require_once('reportHelper.php');
require_once('../../Classes/PHPExcel.php');
include_once('../party/partyModel.php');
if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{	$_POST['fn']();
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	$_GET['fn']();}


function reportExport()
{
	$pmodel  = new partyModel();
	$partyList = $pmodel->getOptionList();	
	if($_POST['type']=="")
		return;
	$helper = new Helper();
	$reportHelper = new reportHelper();
	$data = $helper->getReportMemo($_POST);
	$dateT = Date('YmdHis');
	$filename = ucFirst($_POST['report'])."_Report_".$dateT.".xlsx";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	
	try{
	if($_POST['type']=="packet")
	{	
		$column = 'A';
		$packet = $reportHelper->getMemoPackage();
		foreach($packet as $k=>$v)		
		{
			 $objPHPExcel->getActiveSheet()->SetCellValue($column.'1', $v);
			 $column++;
		} 
		$i=2;
		foreach($data as $d)
		{
			$column = 'A';
			foreach($packet as $k=>$v)		
			{
				if($k =="party")
				{
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $partyList[$d[$k]]);
				}				
				else
				{
				 $objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
				} 
				 $column++;
			} 
			$i++;
		}
	}
	else
	{
		$party = $reportHelper->getMemoParty();	
		
		$column = 'A';
		foreach( $party as $k=>$v)		
		{
			 $objPHPExcel->getActiveSheet()->SetCellValue($column.'1', $v);
			 $column++;
		} 
		$i=2;
		foreach($data as $d)
		{
			$column = 'A';
			foreach($party as $k=>$v)		
			{
				if($k == "party")
				{
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $partyList[$d[$k]]);
				}
				else
				{
				 $objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
				} 
				 $column++;
			} 
			$i++;
		}	
	}
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	$objWriter->save('php://output');
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
}function outreportExport(){	$pmodel  = new partyModel();	$partyList = $pmodel->getOptionList();		if($_POST['type']=="")		return;			$reportHelper  = new reportHelper();	$attribute = $reportHelper->getMemoParty();			$attribute['paid_amount'] = "Paid Amt.";	$attribute['due_amount'] = "Due Amt.";			$helper = new Helper();		$data = $helper->getReportMemo($_POST);	$dateT = Date('YmdHis');	$filename = ucFirst($_POST['report'])."_Report_".$dateT.".xlsx";	$objPHPExcel = new PHPExcel();	$objPHPExcel->setActiveSheetIndex(0);		try{		$column = 'A';		foreach($attribute as $k=>$v)		{			 $objPHPExcel->getActiveSheet()->SetCellValue($column.'1', $v);			 $column++;		} 		$i=2;		foreach($data as $d)		{			if($d['due_amount'] <= 0)					continue;							$column = 'A';			foreach($attribute as $k=>$v)					{							if($k =="party")				{					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $partyList[$d[$k]]);				}				elseif($k =="invoicedate")				{										$phpdate = strtotime( $d[$k] );							 					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, date( 'd-m-Y', $phpdate ));				}				else				{				 $objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);				} 				 $column++;			} 			$i++;		}		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 	header('Content-type: application/vnd.ms-excel');	header('Content-Disposition: attachment; filename="'.$filename.'"');	$objWriter->save('php://output');	}	catch(Exception $e)	{		echo $e->getMessage();	}}


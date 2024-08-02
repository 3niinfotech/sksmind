<?php
session_start();include('../../../variable.php');
include('../../../database.php');
require_once('../../Helper.php');
require_once('reportHelper.php');include_once($daiDir.'account/party/partyModel.php');include_once($daiDir.'account/balance/balanceModel.php');include_once($daiDir.'account/subgroup/subgroupModel.php');
require_once('../../Classes/PHPExcel.php');
include_once('../party/partyModel.php');
if(isset($_POST['fn']) && function_exists($_POST['fn'])) {	$_POST['fn']();}if(isset($_GET['fn']) && function_exists($_GET['fn'])) {	$_GET['fn']();
}
function reportExport()
{		if($_POST['book'] == '')		header('Location: ' . $_SERVER['HTTP_REFERER']);		$helper  = new Helper();	$data = $helper->getBookTransaction($_POST);		$pmodel  = new partyModel();	$sgmodel  = new subgroupModel();	$party = $pmodel->getOptionList();		$subgroup = $sgmodel->getOption();		$i=1;	$Bmodel  = new balanceModel();	$cData = $Bmodel->getAllCurrency();	$book = $helper->getAllBook();	$currency = $helper->getAllCurrency('all');		$pmodel  = new partyModel();
	$partyList = $pmodel->getOptionList();
	$helper = new Helper();
	$reportHelper = new reportHelper();	$field = $reportHelper->getExportField();

	$dateT = Date('Y_m_d_H_i');
	$filename = ucFirst($book[$_POST['book']])."_Report_".$dateT.".xlsx";
	$objPHPExcel = new PHPExcel();	$sheet = $objPHPExcel->getActiveSheet();	
	$objPHPExcel->setActiveSheetIndex(0);		$center = array(        'alignment' => array(            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,        ));		$border = array(				'borders' => array(					'allborders' => array(						'style' => PHPExcel_Style_Border::BORDER_THIN,						'color' => array('rgb' => '033955')					)				));
	try{
		$column = 'A';		$objPHPExcel->getActiveSheet()->SetCellValue('A2', $book[$_POST['book']].' Report');		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray(array(					'font'  => array(						'bold'  => true,						'size'  => 18,							'color' => array('rgb' => 'A4D2DF'),					)));		$sheet->getStyle('A2')->applyFromArray($center);		
		foreach($field as $k=>$v)
		{			$row =4;			$objPHPExcel->getActiveSheet()->SetCellValue($column.$row, $v);			$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray(array(					'font'  => array(						'bold'  => true,											)));								$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray(array(			'font'  => array( 				'size'	=> '12',				'color' => array('rgb' => '033955')			)));						$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray(			array(				'fill' => 				array(						'type' => PHPExcel_Style_Fill::FILL_SOLID,						'color' => array('rgb' => 'ECABCB')					)				));						$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray($border);							$sheet->getStyle($column.$row)->applyFromArray($center);		 			$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(14); 						if($v == 'Description')			{			$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(50); 
			 			}			if($v == 'Party')			{			$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(35); 			 			}			if($v == 'Account')			{			$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(25); 			 			}			$column++;
		} 
		$i=5;		$balance = 0;		
		foreach($data as $d)
		{						if($d['type'] == 'cr')				$balance += $d['amount'];			else				$balance -= $d['amount'];									$phpdate = strtotime( $d['date'] );				
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i,date( 'd-m-Y', $phpdate ));
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, isset($subgroup[$d['under_subgroup']])?$subgroup[$d['under_subgroup']]:"");
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, isset($party[$d['party']])?$party[$d['party']]:"");			$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setWrapText(true);			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, $d['cheque']);			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, $d['description']);			$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setWrapText(true);			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, ($d['type'] == 'cr' )? $d['amount']:"" );			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, ($d['type'] == 'dr' )? $d['amount']:"" );						$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, $balance );			
			$i++;
		}				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, 'Total Balance');		$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray(array(					'font'  => array(						'bold'  => true,						'size'  => 14,							'color' => array('rgb' => 'A4D2DF'),					)));		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, $balance );		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray(array(					'font'  => array(						'bold'  => true,						'size'  => 12,							'color' => array('rgb' => 'A4D2DF'),					)));					$sheet->mergeCells('A2:H2');
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


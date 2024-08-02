<?php
//include('../../../../../variable.php');
session_start();

include('../../../database.php');
require_once('../../Helper.php');
require_once('../../jHelper.php');
require_once('reportHelper.php');
require_once('../../Classes/PHPExcel.php');
include_once('../party/partyModel.php');
global $cn;

if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{	$_POST['fn']($cn);
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	$_GET['fn']($cn);}


function reportExport($cn)
{
	$pmodel  = new partyModel($cn);
	$partyList = $pmodel->getOptionList();	
	if($_POST['type']=="")
		return;
	$helper = new Helper($cn);
	$reportHelper = new reportHelper($cn);
	$data = $helper->getReportMemo($_POST);
	$dateT = Date('YmdHis');
	$filename = ucFirst($_POST['report'])."_Report_".$dateT.".xlsx";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$sheet = $objPHPExcel->getActiveSheet();
	try{
		
	$center = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ));
	
	$border = array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				));	
	if($_POST['type']=="packet")
	{	
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Stock Report');
	$sheet->mergeCells('A3:M3');
		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '16',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
			
		$column = 'A';
		$packet = $reportHelper->getMemoPackage();
		foreach($packet as $k=>$v)		
		{
			 $objPHPExcel->getActiveSheet()->SetCellValue($column.'5', $v);
			 
			  $objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '13',
				'color' => array('rgb' => 'ffffff')
			)));
			
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(
			array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '000000')
					)
				));
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($border);
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($center);
			 $column++;
		} 
		$i=6;
		$trp = $trc = $tpp = $tpc = $tc = $tp = $ta = $tta = $ttp = $aprice = 0.0; 
		foreach($data as $d)
		{
			$column = 'A';
			if($_POST['report'] == 'purchase')
			{	
				$carat = ($d['purchase_carat'] ==0) ? $d['carat'] : $d['purchase_carat'];	
				$d['pcs'] = ($d['purchase_pcs'] ==0) ? $d['pcs'] : $d['purchase_pcs'];
				$d['price'] = ($d['purchase_price'] ==0) ? $d['price'] : $d['purchase_price'];
				$d['amount'] = ($d['purchase_amount'] ==0) ? $d['amount'] : $d['purchase_amount'];
			}
			else
			{
				$d['carat'] = $d['carat'];
				$d['pcs'] = $d['pcs'];
				$d['price'] = ($d['sell_price'] ==0)?$d['price']:$d['sell_price'];
				$d['amount'] = ($d['sell_amount'] ==0)?$d['amount']:$d['sell_amount'];
			}
			$tp += (float)$d['pcs'];
			$aprice += (float)$d['price'];
			$tc += (float)$d['carat'];
			$ta += (float)$d['amount'];
			foreach($packet as $k=>$v)		
			{
				if($k =="party")
				{
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $partyList[$d[$k]]);
				}
				elseif($k =='out_date')
				{
					$phpdate = strtotime( $d['out_date'] );											
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i,  date( 'd-m-Y', $phpdate ));
				}	
				else
				{
				 $objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
				} 
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($center);
			
				$column++;
			} 
			$i++;
		}
		
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, $tp);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, $tc);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, round($ta/$tc,4));
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i, $ta);
		
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		
		foreach(range('A','M') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
	}
	else
	{
		$sheet->mergeCells('A3:J3');
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Company Report');
	
		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '16',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
			
		$party = $reportHelper->getMemoParty();	
		
		$column = 'A';
		foreach( $party as $k=>$v)		
		{
			$objPHPExcel->getActiveSheet()->SetCellValue($column.'5', $v);
			 
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '13',
				'color' => array('rgb' => 'ffffff')
			)));
			
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(
			array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '000000')
					)
				));
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($border);
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($center);
			 $column++;
		} 
		
		 $i=6;
		 $tp = $tc = $ta = 0.0;
		foreach($data as $d)
		{
			$tp += (float)$d['tpp'];
			$tc += (float)$d['tpc'];
			$ta += (float)$d['ta'];
			$d['tp'] = round($d['ta']/$d['tpc'],2);
			$column = 'A';
			foreach($party as $k=>$v)		
			{
				if($k == "party")
				{
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $partyList[$d[$k]]);
				}
				elseif($k =='invoicedate')
				{
					$phpdate = strtotime( $d['invoicedate'] );											
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i,  date( 'd-m-Y', $phpdate ));
				}
				elseif($k =='duedate')
				{
					$phpdate = strtotime( $d['duedate'] );											
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i,  date( 'd-m-Y', $phpdate ));
				}
				else
				{
				 $objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
				} 
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($center);
			
				 $column++;
			} 
			$i++;
			
			
		}	
		
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, $tp);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, $tc);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, round($ta/$tc,4));
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i, $ta);
		
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		foreach(range('A','J') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
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
}
function reportExportJewOld($cn)
{
	$pmodel  = new partyModel($cn);
	$partyList = $pmodel->getOptionList();	
	if($_POST['type']=="")
		return;
	$helper = new Helper($cn);
	$jhelper = new jHelper($cn);
	$reportHelper = new reportHelper($cn);
	$data = $helper->getReportMemoJew($_POST);
	$jewType = $jhelper->getJewelryType();
	$design = $jhelper->getAllDesign();
	$dateT = Date('YmdHis');
	$filename = ucFirst($_POST['report'])."_Report_".$dateT.".xlsx";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$sheet = $objPHPExcel->getActiveSheet();
	
	try{
		
	$center = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ));
	
	$border = array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				));	
	if($_POST['type']=="packet")
	{	
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Stock Report');
	$sheet->mergeCells('A3:M3');
		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '16',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
			
		$column = 'A';
		$packet = $reportHelper->getMemoPackageJew();
		foreach($packet as $k=>$v)		
		{
			 $objPHPExcel->getActiveSheet()->SetCellValue($column.'5', $v);
			 
			  $objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '13',
				'color' => array('rgb' => 'ffffff')
			)));
			
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(
			array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '000000')
					)
				));
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($border);
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($center);
			 $column++;
		} 
		$i=6;
		$tsp = $tnw = $tpw = $tc = $ta = 0.0; 
		foreach($data as $d)
		{
			$column = 'A';
			$tc += (float) $d['gross_weight'];
			$tpw += (float) $d['pg_weight'];
			$tnw += (float) $d['net_weight'];
			$tsp += (float) $d['sell_price'];
			$ta += (float) $d['total_amount'];
			foreach($packet as $k=>$v)		
			{
				if($k =="party")
				{
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $partyList[$d[$k]]);
				}
				elseif($k =='jew_type')
				{										
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $jewType[$d[$k]]);
				}
				elseif($k =='jew_design')
				{										
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $design[$d[$k]]);
				}
				elseif($k =='out_date')
				{
					$phpdate = strtotime( $d['out_date'] );											
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i,  date( 'd-m-Y', $phpdate ));
				}	
				else
				{
				 $objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
				} 
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($center);
			
				$column++;
			} 
			$i++;
		}
		
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, $tc);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i, $tpw);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i, $tnw);
		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$i, $tsp);
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$i, $ta);
		
		$objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
			$objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		foreach(range('A','P') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
	}
	else
	{
		$sheet->mergeCells('A3:J3');
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Company Report');
	
		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '16',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
			
		$party = $reportHelper->getJewMemoParty();	
		
		$column = 'A';
		foreach( $party as $k=>$v)		
		{
			$objPHPExcel->getActiveSheet()->SetCellValue($column.'5', $v);
			 
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '13',
				'color' => array('rgb' => 'ffffff')
			)));
			
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(
			array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '000000')
					)
				));
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($border);
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($center);
			 $column++;
		} 
		
		 $i=6;
		 $tp = $tc = $ta = $fa = 0.0;
		foreach($data as $d)
		{
			$tp += (float)$d['tgross_weight'];
			$tc += (float)$d['tpg_weight'];
			$ta += (float)$d['tnet_weight'];
			$fa += (float) $d['final_amount'];	
			$column = 'A';
			foreach($party as $k=>$v)		
			{
				if($k == "party")
				{
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $partyList[$d[$k]]);
				}
				elseif($k =='date')
				{
					$phpdate = strtotime( $d['date'] );											
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i,  date( 'd-m-Y', $phpdate ));
				}
				else
				{
				 $objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
				} 
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($center);
			
				 $column++;
			} 
			$i++;
			
			
		}	
		
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, $tp);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, $tc);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, $ta);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, $fa);
		
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
			$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		foreach(range('A','P') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
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
}
function outreportExport($cn){	$pmodel  = new partyModel($cn);	$partyList = $pmodel->getOptionList();		if($_POST['type']=="")		return;			$reportHelper  = new reportHelper($cn);	$attribute = $reportHelper->getMemoParty();			$attribute['paid_amount'] = "Paid Amt.";	$attribute['due_amount'] = "Due Amt.";			$helper = new Helper($cn);		$data = $helper->getReportMemo($_POST);	$dateT = Date('YmdHis');	$filename = ucFirst($_POST['report'])."_Report_".$dateT.".xlsx";	$objPHPExcel = new PHPExcel();	$objPHPExcel->setActiveSheetIndex(0);		try{		$column = 'A';		foreach($attribute as $k=>$v)		{			 $objPHPExcel->getActiveSheet()->SetCellValue($column.'1', $v);			 $column++;		} 		$i=2;		foreach($data as $d)		{			if($d['due_amount'] <= 0)					continue;							$column = 'A';			foreach($attribute as $k=>$v)					{							if($k =="party")				{					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $partyList[$d[$k]]);				}				elseif($k =="invoicedate")				{										$phpdate = strtotime( $d[$k] );							 					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, date( 'd-m-Y', $phpdate ));				}				else				{				 $objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);				} 				 $column++;			} 			$i++;		}		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 	header('Content-type: application/vnd.ms-excel');	header('Content-Disposition: attachment; filename="'.$filename.'"');	$objWriter->save('php://output');	}	catch(Exception $e)	{		echo $e->getMessage();	}}

function reportExportJew($cn)
{
	$pmodel  = new partyModel($cn);
	$partyList = $pmodel->getOptionList();	
	if($_POST['type']=="")
		return;
	$helper = new Helper($cn);
	$jhelper = new jHelper($cn);
	$reportHelper = new reportHelper($cn);
	$data = $helper->getReportMemoJew($_POST);
	$jewType = $jhelper->getJewelryType();
	$design = $jhelper->getAllDesign();
	$dateT = Date('YmdHis');
	$filename = ucFirst($_POST['report'])."_Report_".$dateT.".xlsx";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$sheet = $objPHPExcel->getActiveSheet();
	
	try{
		
	$center = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ));
		
	$middle = array(
        'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        ));	
	
	$border = array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => 'cccccc')
					)
				));	
	$bold  = array(
					'font'  => array(
						'bold'  => true,
						'size'  => 12,							
						'color' => array('rgb' => '9C0006')			
					));			
	if($_POST['type']=="packet")
	{

		if($_POST['report']=="sale")
	{	
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Stock Report');
	$sheet->mergeCells('A3:M3');
		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '16',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
			
		$column = 'A';
		$packet = $reportHelper->getMemoPackageJew();
		foreach($packet as $k=>$v)		
		{
			 $objPHPExcel->getActiveSheet()->SetCellValue($column.'5', $v);
			 
			  $objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '13',
				'color' => array('rgb' => 'ffffff')
			)));
			
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(
			array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '000000')
					)
				));
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($border);
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($center);
			 $column++;
		} 
		$i=6;
		$tsp = $tnw = $tpw = $tc = $ta = 0.0; 
		foreach($data as $d)
		{
			$column = 'A';
			$tc += (float) $d['gross_weight'];
			$tpw += (float) $d['pg_weight'];
			$tnw += (float) $d['net_weight'];
			$tsp += (float) $d['sell_price'];
			$ta += (float) $d['total_amount'];
			foreach($packet as $k=>$v)		
			{
				if($k =="party")
				{
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $partyList[$d[$k]]);
				}
				elseif($k =='jew_type')
				{										
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $jewType[$d[$k]]);
				}
				elseif($k =='out_date')
				{
					$phpdate = strtotime( $d['out_date'] );											
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i,  date( 'd-m-Y', $phpdate ));
				}	
				else
				{
				 $objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
				} 
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($center);
			
				$column++;
			} 
			$i++;
		}
		
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i, $tc);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i, $tpw);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$i, $tnw);
		$objPHPExcel->getActiveSheet()->SetCellValue('S'.$i, $tsp);
		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$i, $ta);
		
		$objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
			$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('S'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		foreach(range('A','P') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
	}
	else{
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Stock Report');
	$sheet->mergeCells('A3:M3');
		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '16',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
			
		$column = 'A';
		$i=5;
		$j=6;
		$columnHeader = array('SR NO.','PRODUCT IMAGE','NAME & CODE','IGI NO.');
		$columnHeader2 = array('IGI NO.','Lot No.','Color','Clarity','PCS','CTS','ASKING PRICE',' ');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15); 
		foreach($columnHeader as $k=>$v)
		{
			$sheet->mergeCells($column.$i.':'.$column.$j);
			 $objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $v);
			 $objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($bold);
			 $objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($middle);
			 $objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($center);
			 $objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);
			 $objPHPExcel->getActiveSheet()->getStyle($column.$j)->applyFromArray($border);
			 $objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(
			 array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFCC')
					)
				));
			 $column++;
		}
		foreach($columnHeader2 as $k=>$v)
		{
			 $objPHPExcel->getActiveSheet()->SetCellValue($column.$j, $v);
			 $objPHPExcel->getActiveSheet()->getStyle($column.$j)->applyFromArray($bold);
			 $objPHPExcel->getActiveSheet()->getStyle($column.$j)->applyFromArray($middle);
			 $objPHPExcel->getActiveSheet()->getStyle($column.$j)->applyFromArray($center);
			 $objPHPExcel->getActiveSheet()->getStyle($column.$j)->applyFromArray($border);
			 $objPHPExcel->getActiveSheet()->getStyle($column.$j)->applyFromArray(
			 array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFCC')
					)
				));
			 $column++;
		}
		$sheet->mergeCells('E5:L5');
		$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'DIMONDS');
		$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($center);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($bold);
		$objPHPExcel->getActiveSheet()->getStyle('E5:L5')->applyFromArray($border);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray(
			 array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFCC')
					)
				));
		$i=$q=7;
		$tsp = $tnw = $tpw = $tc = $ta = 0.0; 
		$s=1;
		foreach($data as $d)
		{
			$tc += (float) $d['gross_weight'];
			$tpw += (float) $d['pg_weight'];
			$tnw += (float) $d['net_weight'];
			$tsp += (float) $d['sell_price'];
			$ta += (float) $d['total_amount'];
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i,$s);
			
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName('Logo');
			$objDrawing->setDescription('Logo');
			$fn = "../../../pdf/file/jewels/".$d['sku'].".jpg";
			if(file_exists($fn)):
				$logo = "../../../pdf/file/jewels/".$d['sku'].".jpg";
			else:
				$logo = "../../../pdf/file/jewels/logo-new.png";
			endif;
			$objDrawing->setPath($logo);
			$objDrawing->setCoordinates('B'.$i);
			$objDrawing->setHeight(140); // logo height
			$objDrawing->setWidth(140); // logo height
			$objDrawing->setWorksheet($sheet);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i,$d['name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i,$d['igi_code']);
			if($d['collet'] != ""){
				foreach($d['collet'] as $key=>$record){
					
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,$record['report_no']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i,$record['sku']);
					
					$mco = strpos($record['color'],'(');
					if($mco === false)
						$myco = $record['color'];
					else
					$myco = trim(substr($record['color'],0,strpos($record['color'],'(')));
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,$myco);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i,$record['clarity']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i,$record['pcs']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i,$record['carat']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i,'');
					$i++;
				}
				
			}
			if($d['main'] != ""){
				foreach($d['main'] as $key=>$record){
					
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,$record['report_no']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i,$record['sku']);
					
					$mco = strpos($record['color'],'(');
					if($mco === false)
						$myco = $record['color'];
					else
					$myco = trim(substr($record['color'],0,strpos($record['color'],'(')));
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,$myco);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i,$record['clarity']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i,$record['pcs']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i,$record['carat']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$i,'');
					$i++;
				}
				
			}	
			if($d['side'] != ""){
				foreach($d['side'] as $key=>$record){
					
					if($record['outward_parent'] == 0)
					{
						$sku = $record['sku'];
					}
					else
					{
						$parentData = $jhelper->getSideProductDetail($record['outward_parent']);
						$sku = $parentData['sku'];
						if($record['color'] == '')
						$record['color'] = $parentData['color'];
						if($record['clarity'] == '')
						$record['clarity'] = $parentData['clarity'];
					}
					
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,$record['report_no']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i,$sku);
					
					$mco = strpos($record['color'],'(');
					if($mco === false)
						$myco = $record['color'];
					else
					$myco = trim(substr($record['color'],0,strpos($record['color'],'(')));
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,$myco);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i,$record['clarity']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i,$record['pcs']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i,$record['carat']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$i,'');
					$i++;
				}
			}
			if($d['side_carat'] != 0 || $d['side_carat'] != 0.00){
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i,'Extra');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i,$record['clarity']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i,$d['side_pcs']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i,$d['side_carat']);
					
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$i,'');
					$i++;
			}
		
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i,'');
					
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$i,'');
					$m=$i;
					$i++;
					
					$sheet->mergeCells('L'.$m.':'.'L'.$i);
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$m,$jewType[$d['jew_type']]);
					$objPHPExcel->getActiveSheet()->getStyle('L'.$m)->applyFromArray($middle);
					$objPHPExcel->getActiveSheet()->getStyle('L'.$m)->applyFromArray($center);
					$i++;
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$i,'');
					$i++;
					
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i,(isset($d['gold']))?$d['gold']."K Gold":'');
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i,$d['net_weight']);
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$i,'');
					$i++;
					
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$i,'');
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$i,'');
					$m=$i;
					$i++;
					$sheet->mergeCells('I'.$m.':'.'J'.$i);
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$m,'TOTAL USD');
					$objPHPExcel->getActiveSheet()->getStyle('I'.$m)->applyFromArray($middle);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$m)->applyFromArray($center);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$m)->applyFromArray($bold);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$m.':'.'J'.$i)->applyFromArray($border);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$m)->applyFromArray(
					 array(
						'fill' => 
						array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => 'FFFFCC')
							)
						));
					
					$sheet->mergeCells('K'.$m.':'.'K'.$i);
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$m,round($d['selling_price']));
					$objPHPExcel->getActiveSheet()->getStyle('K'.$m)->applyFromArray($bold);
					$objPHPExcel->getActiveSheet()->getStyle('K'.$m)->applyFromArray($middle);
					$objPHPExcel->getActiveSheet()->getStyle('K'.$m)->applyFromArray($center);
					$objPHPExcel->getActiveSheet()->getStyle('K'.$m.':'.'K'.$i)->applyFromArray($border);
					$objPHPExcel->getActiveSheet()->getStyle('K'.$m)->applyFromArray(
					 array(
						'fill' => 
						array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => 'FFFFCC')
							)
						));
					
					$sheet->mergeCells('A'.$q.':'.'A'.$i);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$q)->applyFromArray($middle);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$q)->applyFromArray($center);
					$sheet->mergeCells('B'.$q.':'.'B'.$i);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$q)->applyFromArray($middle);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$q)->applyFromArray($center);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$q)->getAlignment()->setWrapText(true);
					
					$n=$i-2;
					$p=$i-1;
					$sheet->mergeCells('C'.$p.':'.'C'.$i);
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$p,$d['sku']);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($bold);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$p.':'.'C'.$i)->applyFromArray($border);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($center);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($middle);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray(
					 array(
						'fill' => 
						array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => 'FFFFCC')
							)
						));
					$sheet->mergeCells('D'.$p.':'.'D'.$i);	
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$p,$d['gross_weight']);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($bold);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$p.':'.'D'.$i)->applyFromArray($border);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($center);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($middle);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray(
					 array(
						'fill' => 
						array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => 'FFFFCC')
							)
						));
					$sheet->mergeCells('C'.$q.':'.'C'.$n);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$q)->applyFromArray($middle);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$q)->applyFromArray($center);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$q)->getAlignment()->setWrapText(true);
					$sheet->mergeCells('D'.$q.':'.'D'.$n);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$q)->applyFromArray($middle);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$q)->applyFromArray($center);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$q)->getAlignment()->setWrapText(true);
					
			$i++;
			$s++;
			$q=$i;
		}
		
		/* foreach(range('A','P') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		} */
		$objPHPExcel->getActiveSheet()->freezePane('A7');
	}
	}
	else
	{
		$sheet->mergeCells('A3:G3');
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Company Report');
	
		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '16',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
			
		$party = $reportHelper->getJewMemoParty();	
		
		$column = 'A';
		foreach( $party as $k=>$v)		
		{
			$objPHPExcel->getActiveSheet()->SetCellValue($column.'5', $v);
			 
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(array(
			'font'  => array( 
				'size'	=> '13',
				'color' => array('rgb' => 'ffffff')
			)));
			
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray(
			array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '000000')
					)
				));
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($border);
			$objPHPExcel->getActiveSheet()->getStyle($column.'5')->applyFromArray($center);
			 $column++;
		} 
		
		 $i=6;
		 $tp = $tc = $ta = $fa = 0.0;
		foreach($data as $d)
		{
			$tp += (float)$d['tgross_weight'];
			$tc += (float)$d['tpg_weight'];
			$ta += (float)$d['tnet_weight'];
			$fa += (float) $d['final_amount'];	
			$column = 'A';
			foreach($party as $k=>$v)		
			{
				if($k == "party")
				{
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $partyList[$d[$k]]);
				}
				elseif($k =='date')
				{
					$phpdate = strtotime( $d['date'] );											
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i,  date( 'd-m-Y', $phpdate ));
				}
				else
				{
				 $objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
				} 
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($center);
			
				 $column++;
			} 
			$i++;
			
			
		}	
		
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, $tp);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, $tc);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, $ta);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, $fa);
		
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
			$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($center);
		  $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'bold'	=> true,				
				'color' => array('rgb' => '000000')
			)));
		foreach(range('A','P') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
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
}


<?php
session_start();
include('../../../variable.php');

include('../../../database.php');
require_once('../../Helper.php');
include('inventoryModel.php');
require_once('../../Classes/PHPExcel.php');
require("../../../PhpMailer/PHPMailer_5.2.0/class.phpmailer.php");

if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{
	$_POST['fn']();
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	
	$_GET['fn']();
}

function saveStock()
{
	$error =0;
	$message = "";
		
		$model = new inventoryModel();
		$rs = $model->saveStock($_POST);
		
		if($rs)
		{
			$error = 0;
			$message = "Successfully Saved !!!";
		}
		else
		{
			$error = 1;
			$message = $rs;
		}		
		
	
	if ($error == 0) 	
		$_SESSION['success']= $message;		 
	else
		$_SESSION['error'] = $message;			
	
	//echo $_SESSION['error'];
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}



	function exportToExcel()
	{
		if($_SESSION['companyId'] == 1):
		
		$model = new inventoryModel();
		$helper = new Helper();
		$data =  $model->getMyExportInventory($_POST['exportProducts']);
		$dateT = Date('Y-m-d-H-i-s');
		$filename = "Export ".$dateT.".xlsx";
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();
		

		// --------------- Defaule Configuration ----------------
		$center = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			));
		
		$border = array(
					'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '033955')
						)
					));
		$sheet->getStyle("A1")->applyFromArray($center);
		$sheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		// ---------- Header field -------------------------
		$column = 'A';
		$row = 6;
		foreach($helper->getExportAttribute() as $k=>$v)		
		{
			 $objPHPExcel->getActiveSheet()->SetCellValue($column.$row, $v);
			 
			 $objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray(array(
				'font'  => array( 
					'size'	=> '12',
					'color' => array('rgb' => '033955')
				)));
				
				$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray(
				array(
					'fill' => 
					array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => 'ECABCB')
						)
					));
				
				$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray($border);	
				
				$sheet->getStyle($column.$row)->applyFromArray($center);
			 $column++;
		} 
		$objPHPExcel->getActiveSheet()->setAutoFilter('A6:W6');
		
		// ---------- Record Data -------------------------
		$i= $firstid = $lastid = 7;
		$tp = $tc = $tprice = $ta = 0.0;
		foreach($data as $d)
		{
			
			$column = 'A';
			$tp = $tp +  $d['polish_pcs'];
			$tc =  $tc + $d['polish_carat'];
			$tprice = $tprice +  $d['price'];
			$ta = $ta +  $d['amount']; 
		
			foreach($helper->getExportAttribute() as $k=>$v)		
			{
				
				$rapnetprice = array();
				$rcolor = ($d['main_color'] !='')?$d['main_color']:$d['color'];
				$rapnetprice = $helper->RapnetPrice($d['shape'],$rcolor,$d['clarity'],$d['polish_carat'],$d['price']); 
						
				if($k == 'sku'):			
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
					 if($d[$k] !=""):
					$objPHPExcel->getActiveSheet()->getCell($column.$i)
						->getHyperlink()
						->setUrl('http://shreehk.com/rapnet.php?sku='.$d[$k]);
						$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(array(
						'font'  => array(
							'bold'  => false,
							'color' => array('rgb' => '0000FF'),
						)));
					endif; 	
				elseif($k == 'report_no'):	
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
					if($d[$k] !="" ):				
						$objPHPExcel->getActiveSheet()->getCell($column.$i)
							->getHyperlink()
							->setUrl('https://www.gia.edu/report-check?reportno='.$d[$k]);
							$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(
							array(
							'font'  => array( 
								'bold'  => false,
								'color' => array('rgb' => '0000FF'),
							)));
					endif; 	
				elseif($k == 'rapnet'):			
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $rapnetprice['price']);	
				elseif($k == 'discount'):			
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $rapnetprice['discount']);		
				else:	
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);	
				endif;
				
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);	
				 
				 $column++;
				 
				 
			}  
			$column--;
			if($d['outward'] == 'memo' )
			{
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.$column.$i)->applyFromArray(
			array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'f1f1f1')
					)
				));
			}		
			$lastid  = $i;
			$i++;
		}
		
		// ---------- Footer Total -------------------------
		/* $column = 'A';
		
		foreach($helper->getExportAttribute() as $k=>$v)		
		{
			$flag = 0 ;
			if($k == 'shape')
			{			
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, 'TOTAL');			
				$flag = 1;
			}
			if($k == 'polish_pcs')	
			{
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $tp);
				$flag = 1;
			}
			if($k == 'polish_carat')	
			{
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $tc);
				$flag = 1;
			}
			if($k == 'price')	
			{
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $tprice);
				$flag = 1;
			}
			if($k == 'amount')	
			{
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $ta);
				$flag = 1;
			}
			if($flag)
			{
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(array(
				'font'  => array( 
					'bold'  => true,				
				)));
				
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(
				array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'CCCCCC')
					)));
					
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);			
			}
			$column++;
		} */
		
		// ---------- Freeze Header -------------------------
		
		$fill = array('fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'A4D2DF')
					));
		
		$sheet->SetCellValue('F2', 'PCS');
		$sheet->getStyle('F2')->applyFromArray($fill);			
		$objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($center);

		$sheet->SetCellValue('G2', 'CARAT');
		$sheet->getStyle('G2')->applyFromArray($fill);			
		$objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($center);

		$sheet->SetCellValue('H2', 'RATE');
		$sheet->getStyle('H2')->applyFromArray($fill);			
		$objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($center);

		$sheet->SetCellValue('I2', 'AMOUNT');
		$sheet->getStyle('I2')->applyFromArray($fill);			
		$objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($center);
		
		
		$sheet->SetCellValue('E3', 'TOTAL');
		$sheet->getStyle('E3')->applyFromArray($fill);			
		$objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($center);
		
		
		$sheet->SetCellValue('F3', $tp);	
		$objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($center);

		$sheet->SetCellValue('G3', $tc);	
		$objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($center);

		$sheet->SetCellValue('H3', ($ta / $tc ) );	
		$objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($center);

		$sheet->SetCellValue('I3', $ta);
		$objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($center);
		
		
		
		$sheet->SetCellValue('E4', 'SELECTED');
		$sheet->getStyle('E4')->applyFromArray($fill);			
		$objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray($center);
		
		
		$sheet->SetCellValue('F4', '=SUBTOTAL(109,E'.$firstid.':E'.$lastid.')');	
		$objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($center);

		$sheet->SetCellValue('G4', '=SUBTOTAL(109,F'.$firstid.':F'.$lastid.')');	
		$objPHPExcel->getActiveSheet()->getStyle('G4')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('G4')->applyFromArray($center);

		$sheet->SetCellValue('I4', '=SUBTOTAL(109,L'.$firstid.':L'.$lastid.')');
		$objPHPExcel->getActiveSheet()->getStyle('I4')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('I4')->applyFromArray($center);
	 
		$sheet->SetCellValue('H4', '=ROUND(I4/G4,2)');	
		$objPHPExcel->getActiveSheet()->getStyle('H4')->applyFromArray($border);			
		$objPHPExcel->getActiveSheet()->getStyle('H4')->applyFromArray($center);

		
			// -------------- freez header ---------------------------------------------
		 $sheet->mergeCells('A1:D5');
		$sheet->freezePane('A7');
		
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$logo = dirname(__FILE__).'/logo.png'; // Provide path to your logo file
		$objDrawing->setPath($logo);
		//$objDrawing->setOffsetX(8);    // setOffsetX works properly
		//$objDrawing->setOffsetY(300);  //setOffsetY has no effect
		$objDrawing->setCoordinates('A1');
		$objDrawing->setHeight(100); // logo height
		$objDrawing->setWorksheet($sheet); 

		 
		 
		$address = 	array('font'  => array( 
					'size'	=> '12',
					'color' => array('rgb' => 'cc0066')
				));
				
		$sheet->mergeCells('L1:T1');
		$sheet->SetCellValue('L1', '206, 2/F, Chevalier House,45-51 Chatham Road South,Tsim Sha Tsui, Kowloon, Hong Kong.');
		$sheet->getStyle('L1')->applyFromArray($address );
		
		$sheet->mergeCells('L2:P2');
		$sheet->SetCellValue('L2', 'Website : www.shreehk.com');
		$sheet->getStyle('L2')->applyFromArray($address );
			
		$sheet->mergeCells('Q2:T2');
		$sheet->SetCellValue('Q2', 'Email : info@shreehk.com');
		$sheet->getStyle('Q2')->applyFromArray($address );
		
		//------
		$sheet->mergeCells('L3:P3');
		$sheet->SetCellValue('L3', 'Contact No : +852 23666047');
		$sheet->getStyle('L3')->applyFromArray($address );
			
		$sheet->mergeCells('Q3:T3');
		$sheet->SetCellValue('Q3', 'WhatsAPP : +852 60404708');
		$sheet->getStyle('Q3')->applyFromArray($address );
		
		$sheet->mergeCells('L4:P4');
		$sheet->SetCellValue('L4', 'Rapnet : 91552 (shreehk) ');
		$sheet->getStyle('L4')->applyFromArray($address );
			
		$sheet->mergeCells('Q4:T4');
		$sheet->SetCellValue('Q4', 'Skype : shreeintl.hk ');
		$sheet->getStyle('Q4')->applyFromArray($address );
		
		$objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setWrapText(true);
		//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
		$objWriter->setPreCalculateFormulas(false);
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		$objWriter->save('php://output');
		
		else:
	
	
	$model = new inventoryModel();
	$helper = new Helper();
	$data =  $model->getMyExportInventory($_POST['exportProducts']);
	$dateT = Date('Y-m-d-H-i-s');
	$filename = "Export ".$dateT.".xlsx";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$sheet = $objPHPExcel->getActiveSheet();
	

	// --------------- Defaule Configuration ----------------
	$center = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ));
	
	$border = array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '033955')
					)
				));
    $sheet->getStyle("A1")->applyFromArray($center);
	$sheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
	// ---------- Header field -------------------------
	$column = 'A';
	$row = 6;
	foreach($helper->getExportAttribute() as $k=>$v)		
	{
		 $objPHPExcel->getActiveSheet()->SetCellValue($column.$row, $v);
		 
		 $objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray(array(
			'font'  => array( 
				'size'	=> '12',
				'color' => array('rgb' => '033955')
			)));
			
			$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray(
			array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'ECABCB')
					)
				));
			
			$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray($border);	
			
			$sheet->getStyle($column.$row)->applyFromArray($center);
		 $column++;
	} 
	$objPHPExcel->getActiveSheet()->setAutoFilter('A6:W6');
	
	// ---------- Record Data -------------------------
	$i= $firstid = $lastid = 7;
	$tp = $tc = $tprice = $ta = 0.0;
	foreach($data as $d)
	{
		
		$column = 'A';
		$tp = $tp +  $d['polish_pcs'];
		$tc =  $tc + $d['polish_carat'];
		$tprice = $tprice +  $d['price'];
		$ta = $ta +  $d['amount']; 
	
		foreach($helper->getExportAttribute() as $k=>$v)		
		{
			
			$rapnetprice = array();
			$rcolor = ($d['main_color'] !='')?$d['main_color']:$d['color'];
			$rapnetprice = $helper->RapnetPrice($d['shape'],$rcolor,$d['clarity'],$d['polish_carat'],$d['price']); 
					
			if($k == 'sku'):			
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
				 if($d[$k] !=""):
				$objPHPExcel->getActiveSheet()->getCell($column.$i)
					->getHyperlink()
					->setUrl('http://shreehk.com/rapnet.php?sku='.$d[$k]);
					$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(array(
					'font'  => array(
						'bold'  => false,
						'color' => array('rgb' => '0000FF'),
					)));
				endif; 	
			elseif($k == 'report_no'):	
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);
				if($d[$k] !="" ):				
					$objPHPExcel->getActiveSheet()->getCell($column.$i)
						->getHyperlink()
						->setUrl('https://www.gia.edu/report-check?reportno='.$d[$k]);
						$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(
						array(
						'font'  => array( 
							'bold'  => false,
							'color' => array('rgb' => '0000FF'),
						)));
				endif; 	
			elseif($k == 'rapnet'):			
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $rapnetprice['price']);	
			elseif($k == 'discount'):			
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $rapnetprice['discount']);		
			else:	
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);	
			endif;
			
			$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);	
			 
			 $column++;
			 
			 
		}  
		$column--;
		if($d['outward'] == 'memo' )
		{
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.$column.$i)->applyFromArray(
		array(
			'fill' => 
			array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'f1f1f1')
				)
			));
		}		
		$lastid  = $i;
		$i++;
	}
	
	// ---------- Footer Total -------------------------
	/* $column = 'A';
	
	foreach($helper->getExportAttribute() as $k=>$v)		
	{
		$flag = 0 ;
		if($k == 'shape')
		{			
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, 'TOTAL');			
			$flag = 1;
		}
		if($k == 'polish_pcs')	
		{
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $tp);
			$flag = 1;
		}
		if($k == 'polish_carat')	
		{
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $tc);
			$flag = 1;
		}
		if($k == 'price')	
		{
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $tprice);
			$flag = 1;
		}
		if($k == 'amount')	
		{
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $ta);
			$flag = 1;
		}
		if($flag)
		{
			$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(array(
			'font'  => array( 
				'bold'  => true,				
			)));
			
			$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(
			array(
			'fill' => 
			array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'CCCCCC')
				)));
				
			$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);			
		}
		$column++;
	} */
	
	// ---------- Freeze Header -------------------------
	
	$fill = array('fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'A4D2DF')
				));
	
	$sheet->SetCellValue('F2', 'PCS');
	$sheet->getStyle('F2')->applyFromArray($fill);			
	$objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($center);

	$sheet->SetCellValue('G2', 'CARAT');
	$sheet->getStyle('G2')->applyFromArray($fill);			
	$objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($center);

	$sheet->SetCellValue('H2', 'RATE');
	$sheet->getStyle('H2')->applyFromArray($fill);			
	$objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($center);

	$sheet->SetCellValue('I2', 'AMOUNT');
	$sheet->getStyle('I2')->applyFromArray($fill);			
	$objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($center);
	
	
	$sheet->SetCellValue('E3', 'TOTAL');
	$sheet->getStyle('E3')->applyFromArray($fill);			
	$objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($center);
	
	
	$sheet->SetCellValue('F3', $tp);	
	$objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($center);

	$sheet->SetCellValue('G3', $tc);	
	$objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($center);

	$sheet->SetCellValue('H3', ($ta / $tc ) );	
	$objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($center);

	$sheet->SetCellValue('I3', $ta);
	$objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($center);
	
	
	
	$sheet->SetCellValue('E4', 'SELECTED');
	$sheet->getStyle('E4')->applyFromArray($fill);			
	$objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray($center);
	
	
	$sheet->SetCellValue('F4', '=SUBTOTAL(109,E'.$firstid.':E'.$lastid.')');	
	$objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($center);

 	$sheet->SetCellValue('G4', '=SUBTOTAL(109,F'.$firstid.':F'.$lastid.')');	
	$objPHPExcel->getActiveSheet()->getStyle('G4')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('G4')->applyFromArray($center);

	$sheet->SetCellValue('I4', '=SUBTOTAL(109,L'.$firstid.':L'.$lastid.')');
	$objPHPExcel->getActiveSheet()->getStyle('I4')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('I4')->applyFromArray($center);
 
	$sheet->SetCellValue('H4', '=ROUND(I4/G4,2)');	
	$objPHPExcel->getActiveSheet()->getStyle('H4')->applyFromArray($border);			
	$objPHPExcel->getActiveSheet()->getStyle('H4')->applyFromArray($center);

	
		// -------------- freez header ---------------------------------------------
	 $sheet->mergeCells('A1:D5');
	$sheet->freezePane('A7');
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setDescription('Logo');
	$logo = dirname(__FILE__).'/logo.gif'; // Provide path to your logo file
	$objDrawing->setPath($logo);
	//$objDrawing->setOffsetX(8);    // setOffsetX works properly
	//$objDrawing->setOffsetY(300);  //setOffsetY has no effect
	$objDrawing->setCoordinates('A1');
	$objDrawing->setHeight(100); // logo height
	$objDrawing->setWorksheet($sheet); 

	 
	 
	$address = 	array('font'  => array( 
				'size'	=> '12',
				'color' => array('rgb' => 'cc0066')
			));
			
	$sheet->mergeCells('L1:T1');
	$sheet->SetCellValue('L1', '206, 2/F, Chevalier House,45-51 Chatham Road South,Tsim Sha Tsui, Kowloon, Hong Kong.');
	$sheet->getStyle('L1')->applyFromArray($address );
	
	$sheet->mergeCells('L2:P2');
	$sheet->SetCellValue('L2', 'Website : www.shreeintl.com');
	$sheet->getStyle('L2')->applyFromArray($address );
		
	$sheet->mergeCells('Q2:T2');
	$sheet->SetCellValue('Q2', 'Email : shreeintlhk@gmail.com');
	$sheet->getStyle('Q2')->applyFromArray($address );
	
	//------
	$sheet->mergeCells('L3:P3');
	$sheet->SetCellValue('L3', 'Contact No : +852 55334793');
	$sheet->getStyle('L3')->applyFromArray($address );
		
	$sheet->mergeCells('Q3:T3');
	$sheet->SetCellValue('Q3', 'WhatsAPP : +852 55334793');
	$sheet->getStyle('Q3')->applyFromArray($address );
	
	$sheet->mergeCells('L4:P4');
	$sheet->SetCellValue('L4', 'Rapnet : sksm105224 (shreehk) ');
	$sheet->getStyle('L4')->applyFromArray($address );
		
	$sheet->mergeCells('Q4:T4');
	$sheet->SetCellValue('Q4', 'Skype : shreeintl.hk ');
	$sheet->getStyle('Q4')->applyFromArray($address );
	
	$objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setWrapText(true);
	//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->setPreCalculateFormulas(false);
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	$objWriter->save('php://output');

endif;
	}
	

function updateTextValue()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if( empty($_POST["id"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new inventoryModel();
		
		$rs = $model->updateTextValue($_POST);
		
		if($rs == 1)
		{
			$error = 0;
			$message = "Successfully  Updated !!!";			
		}
		else
		{
			$error = 1;
			$message = $rs;
		}		
		
	}
	
	
	if ($error == 0)		
		$response['status'] = 1; 
	else
		$response['status'] = 0;
		
	
	$response['message'] = $message;
	echo json_encode($response);
	//header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function mailTo()
{
	$response['status'] = 1;
	$response['message'] = "";
	$model = new inventoryModel();
	$helper = new Helper();
	if(!isset($_POST['email']) || $_POST['email']== '')
	{
		$response['status'] = 0;	
		$response['message'] = 'Please enter E-Mail address';
	}
	else
	{
		$data =  $model->getMyExportInventory($_POST['exportProducts']);
		
		$to = trim($_POST['email']);
		
		if($_POST['subject'] == "")
			$subject = 'Stone Proposal';
		else
			$subject = $_POST['subject'];
		
		$message = getEmailHtml($data,$_POST['content']); 
		$mail = new PHPMailer();
		$mail->IsSMTP();                                      // set mailer to use SMTP
		$mail->Host = "mail.shreehk.com";  // specify main and backup server
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		$mail->Username = "info@shreehk.com";  // SMTP username
		$mail->Password = "sp73538148"; // SMTP password
		$mail->From = "info@shreehk.com";
		$mail->FromName = "Shree International HK Ltd.";
		$mail->AddAddress($to);               // name is optional
		$mail->Priority = 1; 
		$mail->WordWrap = 50;                                 // set word wrap to 50 characters
		$mail->IsHTML(true);     
		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
		if(!$mail->Send())
		{
			$response['status'] = 0;	
			$response['message'] = 'Unable to send email. Please try again.';
   
		}else{
			
			$response['status'] = 1;	
			$response['message'] = 'Your mail has been sent successfully.';
			$model->setMailData($_POST);			
		}
		
			
		/* $from = 'info@shreehk.com';
		$name = "Shree International HK Ltd.";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$name.'<'.$from.'>'."\r\n".
		'Reply-To: '.$from."\r\n" .
		'X-Mailer: PHP/' . phpversion(); */

		// Compose a simple HTML email message
		/* 
		$message = getEmailHtml($data,$_POST['content']); 
		
		if(mail($to, $subject, $message, $headers))
		{
			
			$response['status'] = 1;	
			$response['message'] = 'Your mail has been sent successfully.';
			$model->setMailData($_POST);
			
			
		} 
		else
		{
			$response['status'] = 0;	
			$response['message'] = 'Unable to send email. Please try again.';
			
		} */
	}
	echo json_encode($response);	
}

function getEmailHtml($data,$content)
{
	
	$html = '<table style="background:#f1f1f1; width:100%;font-family:sans-serif;" cellpadding="20" border="0" cellspacing="0">
		<tr style="background:#ccc;width:100%;height:50px;text-align:center;">
			<td><img alt="Shree Hk logo" style="width:40%;" src="http://shreehk.com/skin/frontend/smartwave/porto/images/logo-shreehk.png" /></td>		
		</tr>
		<tr>
			<td style="background:#f1f1f1; width:100%;">
			
				<table style="width:100%;" border="0" cellspacing="0">
					<tr style="width:100%;">
						<td style="padding-bottom:20px;">
							<h3>Thanks for contacting us.</h3>
							<p>'.$content.'</p>
						</td>		
					</tr>
					<tr>
						<td style="background:#fff; width:100%; padding:20px; text-align:center;">
						<table  style="font-size:12px;border:1px solid #999;border-collapse:collapse;width:100%;" cellpadding="5" cellspacing="0" >
							<tr style="border:1px solid #999;border-collapse:collapse;background:#666; color:#fff;" >
								<th style="border:1px solid #999;border-collapse:collapse;">SKU</th>
								<th style="border:1px solid #999;border-collapse:collapse;">LAB</th>
								<th style="border:1px solid #999;border-collapse:collapse;">REPORT</th>
								<th style="border:1px solid #999;border-collapse:collapse;">SHAPE</th>
								<th style="border:1px solid #999;border-collapse:collapse;">PCS</th>
								<th style="border:1px solid #999;border-collapse:collapse;">CARAT</th>
								<th style="border:1px solid #999;border-collapse:collapse;">COLOR</th>
								<th style="border:1px solid #999;border-collapse:collapse;">CLARITY</th>
								<th style="border:1px solid #999;border-collapse:collapse;">PRICE</th>
								<th style="border:1px solid #999;border-collapse:collapse;">AMOUNT</th>
							</tr>';
							 foreach($data as $d) :
							$html .= '<tr style="border:1px solid #999;border-collapse:collapse;">
								<td style="border:1px solid #999;border-collapse:collapse;"><a href="https://goo.gl/WmkAyU" target="_blank" >'.$d['sku'].'</a></td>
								<td style="border:1px solid #999;border-collapse:collapse;">'.$d['lab'].'</td>
								<td style="border:1px solid #999;border-collapse:collapse;"><a href="https://goo.gl/LNoVSS" target="_blank">'.$d['report_no'].'</a></td>
								<td style="border:1px solid #999;border-collapse:collapse;">'.$d['shape'].'</td>
								<td style="border:1px solid #999;border-collapse:collapse;">'.$d['polish_pcs'].'</td>
								<td style="border:1px solid #999;border-collapse:collapse;">'.$d['polish_carat'].'</td>
								<td style="border:1px solid #999;border-collapse:collapse;">'.$d['main_color'].'</td>
								<td style="border:1px solid #999;border-collapse:collapse;">'.$d['clarity'].'</td>
								<td style="border:1px solid #999;border-collapse:collapse;">'.$d['price'].'</td>
								<td style="border:1px solid #999;border-collapse:collapse;">'.$d['amount'].'</td>		
							</tr>';
							endforeach; 
							
			$html .= '</table>
						</td>		
					</tr>
				</table>
			</td>		
		</tr>
		<tr>
			<td style="width:100%;height:50px; "></td>
		</tr>
		<tr>
		
	</tr>
	<tr>
		<td style="width:100%;height:50px;background:#cc0066; color:#fff; text-align:center;">
		<h1> Shree International (HK) Ltd.</h1>
		<p style="font-size:12px;">
		Its more than a decade since Shree International (HK) Ltd began dealing with natural colored fancy diamonds from the Argyle Diamond Mine. We began for the most obvious reasons: We were truly impressed by the Australian diamonds’ incredible ability to reflect light and by their deep, intense play of colors. At the same time we were captivated by the likelihood that the mine will only produce fancy diamonds for a limited time.</p>
		</td>
	</tr>
	</table>';
	
	return $html;
}


function updateProduct()
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if( empty($_POST["id"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new inventoryModel();
		
		$rs = $model->updateProduct($_POST);
		
		if($rs == 1)
		{
			$error = 0;
			$message = "Successfully  Updated !!!";			
		}
		else
		{
			$error = 1;
			$message = $rs;
		}		
		
	}
	
	
	if ($error == 0)		
		$response['status'] = 1; 
	else
		$response['status'] = 0;
		
	
	$response['message'] = $message;
	echo json_encode($response);
}
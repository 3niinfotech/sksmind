<?php
//include('../../../../../variable.php');

include('../../../database.php');
require_once('../../Helper.php');
include('inventoryModel.php');
require_once('../../Classes/PHPExcel.php');
session_start();
global $cn;

if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
{
	$_POST['fn']($cn);
}

if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	
	$_GET['fn']($cn);
}

function save($cn)
{

	$error =0;
	$message = "";
	if(empty($_POST["reference"]) || empty($_POST["party"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new inwardModel($cn);
		
		if(isset($_POST["id"]))
		{
			$rs = $model->updateData($_POST);
		}
		else
		{
			$rs = $model->saveData($_POST);
		}		
		
		if($rs == 1 )
		{
			$error = 0;
			$message = "Successfully Saved !!!";
		}
		else
		{
			$error = 1;
			$message = $rs;
		}		
		
	}
	
	
	if ($error == 0) 	
		$_SESSION['success']= $message;		 
	else
		$_SESSION['error'] = $message;	
		
	
	//echo $_SESSION['error'];
	header('Location: ' . $_SERVER['HTTP_REFERER']);	
}

function exportToExcel($cn)
{
	
	$model = new inventoryModel($cn);
	$helper = new Helper($cn);
	
	$dateT = Date('Y-m-d-H-i-s');
	$filename = "Export ".$dateT.".xlsx";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$data =  $model->getMyInventory($_POST);
	
	$column = 'A';
	foreach($helper->getExportAttribute() as $k=>$v)		
	{
		 $objPHPExcel->getActiveSheet()->SetCellValue($column.'1', $v);
		 $column++;
	} 
	$i=2;
	foreach($data as $d)
	{
		$column = 'A';
		foreach($helper->getExportAttribute() as $k=>$v)		
		{
			
			$rapnetprice = array();
			$rapnetprice = $helper->RapnetPrice($d['shape'],$d['color'],$d['clarity'],$d['polish_carat'],$d['price']); 
					
			if($k == 'sku'):			
				$objPHPExcel->setActiveSheetIndex(0)->SetCellValue($column.$i, $d[$k]);
				$objPHPExcel->setActiveSheetIndex(0)->getCell($column.$i)
					->getHyperlink()
					->setUrl('http://shreehk.com/rapnet.php?sku='.$d[$k]);
					$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(array(
					'font'  => array(
						'bold'  => false,
						'color' => array('rgb' => '0000FF'),
					)));
					
			elseif($k == 'report_no'):	
				$objPHPExcel->setActiveSheetIndex(0)->SetCellValue($column.$i, $d[$k]);
				$objPHPExcel->setActiveSheetIndex(0)->getCell($column.$i)
					->getHyperlink()
					->setUrl('https://www.gia.edu/report-check?reportno='.$d[$k]);
					$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(array(
					'font'  => array( 
						'bold'  => false,
						'color' => array('rgb' => '0000FF'),
					)));
					
			elseif($k == 'rapnet'):			
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $rapnetprice['price']);	
			elseif($k == 'discount'):			
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $rapnetprice['discount']);		
			else:	
				$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);	
			endif;
			 $column++;
		} 
		$i++;
	}
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	$objWriter->save('php://output');
}



function delete($cn)
{
	$error =0;
	$message = "";
	
	if(empty($_GET["id"]) )
	{
		$message = "Record Can't found.";
		$error = 1;
	}	
	else	
	{	
		$model = new inwardModel($cn);	
		$rs = $model->delete($_GET['id'],$_GET['eid']);
		if($rs)
		{
			$error = 0;
			$message = "Delete Record Successfully !!!";
		}
		else
		{
			$error = 1;
			$message = mysql_error($cn);
		}		
		
	}
	
	
	if ($error == 0) 	
		$_SESSION['success']= $message;		 
	else
		$_SESSION['error'] = $message;	
		
	
	//echo $_SESSION['error'];
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}


<?php
session_start();
//include_once('../../../variable.php');

include('../../../database.php');
require_once('../../jHelper.php');
include('inventoryModel.php');
require_once('../../Classes/PHPExcel.php');
global $cn; 

if(isset($_POST['fn']) && $_POST['fn']=="stockTransfer") 
{
	include_once('../../../variable.php');
	stockTransfer($cn,$TransURL);
}
else
{
	if(isset($_POST['fn']) && function_exists($_POST['fn'])) 
	{
		$_POST['fn']($cn);
	}
}
if(isset($_GET['fn']) && function_exists($_GET['fn'])) 
{
	$_GET['fn']($cn);
}

function saveStock($cn)
{
	$error =0;
	$message = "";
		
		$model = new inventoryModel($cn);
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
function mexportToExcel($cn)
{
	
	$model = new inventoryModel($cn);
	$helper = new jHelper($cn);
	$data =  $model->getMyExportInventory($_POST['exportProducts'],'main');
	
	$dateT = Date('Y-m-d-H-i-s');
	$filename = "Main Stone Export ".$dateT.".xls";
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
	$objPHPExcel->getActiveSheet()->setAutoFilter('A6:J6');
	
	// ---------- Record Data -------------------------
	$i= $firstid = $lastid = 7;
	$tp = $tc = $tprice = $ta = 0.0;
	foreach($data as $d)
	{
		$column = 'A';
		$tp = $tp +  $d['pcs'];
		$tc =  $tc + $d['carat'];
		$tprice = $tprice +  $d['price'];
		$ta = $ta +  $d['amount']; 
	
		foreach($helper->getExportAttribute() as $k=>$v)		
		{
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);	
			
			if($d['outward'] == 'memo' || $d['outward'] == 'consign')
			{	
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(array(
				'font'  => array( 
					'color' => array('rgb' => 'FFFFFF')	
				)));
				
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray(
				array(
				'fill' => 
				array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'D53F40')
					)));
					
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);	
				$column++;
			}
			else
			{	
				$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);	
				$column++;
			}
			 
			 
		}  
		$column--;
		
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
	
	/* $sheet->SetCellValue('F2', 'PCS');
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
 */
	
		// -------------- freez header ---------------------------------------------
	 $sheet->mergeCells('A1:D5');
	$sheet->freezePane('A7');
	
	
	 
	 
	$address = 	array('font'  => array( 
				'size'	=> '12',
				'color' => array('rgb' => 'cc0066')
			));
			
/* 	$sheet->mergeCells('L1:T1');
	$sheet->SetCellValue('L1', 'Unit- 1808, 18/F, Multifield Plaza, 3-7A Prat Avenue, Tsim Sha Tsui, Kowloon, Hong Kong.');
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
	$sheet->getStyle('Q4')->applyFromArray($address ); */
	
	$objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setWrapText(true);
	//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->setPreCalculateFormulas(false);
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	$objWriter->save('php://output');
}


function updateTextValue($cn)
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
		$model = new inventoryModel($cn);
		
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

function mailTo($cn)
{
	$response['status'] = 1;
	$response['message'] = "";
	$model = new inventoryModel($cn);
	$helper = new Helper($cn);
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

		$from = 'info@shreehk.com';
		$name = "Shree International HK Ltd.";
		$headers  = 'MIME-Version: 1.0' . "\r\n";

		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: '.$name.'<'.$from.'>'."\r\n".

		'Reply-To: '.$from."\r\n" .

		'X-Mailer: PHP/' . phpversion();

		// Compose a simple HTML email message
		
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
			
		}
	}
	echo json_encode($response);	
}

function getEmailHtml($cn,$data,$content)
{
	
	$html = '<table style="background:#f1f1f1; width:100%;font-family:sans-serif;" cellpadding="20" border="0" cellspacing="0">
		<tr style="background:#ccc;width:100%;height:50px;text-align:center;">
			<td><img style="width:40%;" src="http://shreehk.com/skin/frontend/smartwave/porto/images/logo-shreehk.png" /></td>		
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
								<td style="border:1px solid #999;border-collapse:collapse;"><a href="http://shreehk.com/rapnet.php?sku='.$d['sku'].'" target="_blank" >'.$d['sku'].'</a></td>
								<td style="border:1px solid #999;border-collapse:collapse;">'.$d['lab'].'</td>
								<td style="border:1px solid #999;border-collapse:collapse;"><a href="https://www.gia.edu/report-check?reportno='.$d['report_no'].'" target="_blank">'.$d['report_no'].'</a></td>
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
		<td style="width:100%;height:50px; background:#e1e1e1; ">
		
		<div dir="ltr"><div style="text-align:left;font-family:arial"><span style="color:gray;font-size:10pt"><span><table style="color:rgb(136,136,136);font-family:arial,sans-serif;font-size:12.8px" width="480" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td style="vertical-align:top;padding-right:12px" valign="top"><b>Mr. Sachin Ghevariya,</b></td><td style="vertical-align:top" valign="top"></td></tr></tbody></table></span></span></div><div style="text-align:left;font-family:arial"><span style="font-size:small"><img src="https://ci3.googleusercontent.com/proxy/JEXOdrtqQFLn8QmsK3ByahqV7g704SiJOhvnWmFN9r0-YOBDeog21FE8kZZ6I37qxkcIQYSX9rnT7-Ux8DFTXhKWIWO8gxrIKmRrcbDuLiPMIgtHmHusEFnMvwm2D4gJJjwe6SSKErDJnWUBLNkKCmAQby8H3AQNI5y5MfTUzkqgmEKZ1_qby6lhI5NHV0cghgfoO-6B3_Rlx7w=s0-d-e1-ft#https://docs.google.com/uc?export=download&amp;id=0ByOYG9JAvbZ4UHBrX0I2OTk4YTg&amp;revid=0ByOYG9JAvbZ4VG5ySmphVm1GdWJSNFJ1ZVdDYlhjWFVBajA4PQ" class="CToWUd" width="200" height="66">&nbsp; </span><font size="1">&nbsp; &nbsp;</font></div><div style="text-align:left"><font size="2"><font face="verdana, sans-serif">&nbsp;</font><font face="arial, helvetica, sans-serif"><img src="https://ci6.googleusercontent.com/proxy/XKm24Qwh1xLPi3cQt1Fn1ywCFRLLc9mm6OSCJcFU0j1wHiaTWArzq6DRNJ_QJVseQM_e0mD5WiLYCmB32tPgh--pHKIeJGaisb-b3AIKG5IH4a9NOziqFaxjZtW4ZVOrZRfo_ZHC1KUFSvurmsWQcPKl6k9Ga3VaOKgt2RDSBmaJehvAz4r-XZqYi99L7Bz9koPxYWlMkdNCkxc=s0-d-e1-ft#https://docs.google.com/uc?export=download&amp;id=0ByOYG9JAvbZ4aHJVdmRVWEp0Ykk&amp;revid=0ByOYG9JAvbZ4Tm1YQ3ZYdXF1WUtoWDB2M2JEQm5SbUgwS1Y4PQ" class="CToWUd">&nbsp;<font color="#999999">Unit :1808,18/F, Multifield Plaza, 3-7A Prat Avenue, Tsim Sha Tsui, Kowloon, Hong Kong.</font></font></font></div><div style="text-align:left"><font size="2" face="arial, helvetica, sans-serif"><font color="#999999">&nbsp;</font><img src="https://ci6.googleusercontent.com/proxy/VTMY7UdUrpGaV6QY_f_yYghMapD98-C-7xvSzWdrrzVlqdb6n023865kqt3mWuz_fGuxieI-noNyby8wAd_WkqFLfG8UFgf0oSqslUDJMFy81iB_PbxlEdCl1ZpmkaYmO9wTwH_owVnKfOjyYMf_jFQIKS9m5z_4udyDUavlJZqkVWA7UgI2Vedl8HKqSPpYaqhcg65xlOefxM4=s0-d-e1-ft#https://docs.google.com/uc?export=download&amp;id=0ByOYG9JAvbZ4U2tPTVJvazBVYjg&amp;revid=0ByOYG9JAvbZ4b3kzb2NiOC9NWWdZL25ZRFU3cjdvNU81U0k0PQ" style="color:rgb(153,153,153)" class="CToWUd"><span style="color:rgb(153,153,153)">&nbsp;<a href="tel:+852%202366%206047" value="+85223666047" target="_blank">+852 23666047</a>&nbsp;</span></font></div><div style="text-align:left"><font size="2" face="arial, helvetica, sans-serif">&nbsp;<img src="https://ci3.googleusercontent.com/proxy/_Tgj4cRwHiYbAggLx-MnwxtCGpClkwE2hkZz0-MMhEOBgoGnw374RS7I1ca21-8PM0ja_Fv00nboKb1sTS0tZALl4THaiudGR6S3XB504QX0kO0MJGr1Ng4iPa-tRp0FaVJwt3gWTJtG01XKH0QRjfrEk8lKFyuXfH-UPH_C_Y0hU2vKvxAJ2RMLy629uz8-g_AA4NLxRWXL5v0=s0-d-e1-ft#https://docs.google.com/uc?export=download&amp;id=0ByOYG9JAvbZ4aS1naDdjVXBYbWs&amp;revid=0ByOYG9JAvbZ4YnNSanpLcWtQQlJBRWdBVzh0TGZuK2lncytJPQ" style="color:rgb(153,153,153)" class="CToWUd"><span style="color:rgb(153,153,153)">&nbsp;+</span><font color="#999999">852 60404708&nbsp;</font></font></div><div style="text-align:left"><font size="2" color="#999999" face="arial, helvetica, sans-serif">&nbsp;<img src="https://ci6.googleusercontent.com/proxy/dgxZ1SZWCIQkh4VPn7LeGJsOwuEktWIzQyOB4VIjYRLGl34oLnMtiUmjO5qirVLhTeSo70ZKWfFrLtZE-EqfX7LvN6mwU8EP7b2plkmP8C00oy5x9PL3UHfdqRHd6SELI4Dga55lDIW0iWw1tGNVAMF0v_Cb2mu11tZzcn_G0vkcFAzaYwAhONSZlWz2nS7fTXvy0XwfUiO_9sA=s0-d-e1-ft#https://docs.google.com/uc?export=download&amp;id=0ByOYG9JAvbZ4cTlxSDhWVG80M1E&amp;revid=0ByOYG9JAvbZ4L09TcHp4cVdrV2YzY0hCMTdVMTd5U2lVM05ZPQ" class="CToWUd">&nbsp;<a href="mailto:shreeintlhk@gmail.com" target="_blank"><span class="il">shreeintlhk@gmail.com</span></a>&nbsp;</font></div><div style="text-align:left"><font size="2" color="#999999" face="arial, helvetica, sans-serif"><font>&nbsp;<img src="https://ci5.googleusercontent.com/proxy/6Vw16gYPpghXusEWvzAGo5XXnCtbx14a9-JxPtdJIu_iZXNu8rAixbzaCuBgdPawDBzZBq14bIItjYfiDTwReGGd6Xrp5dSamxqzgcrUHdoy26gPk_Aqb_DV0MqcKDqiAPRsrnogUWoZ15qhJlI2Wys-2m24fluddOg6_au1cqUPzQkjk_jFvD0qYjx-NvgZ-_rTqienzPr9nXQ=s0-d-e1-ft#https://docs.google.com/uc?export=download&amp;id=0ByOYG9JAvbZ4VXduZm1oaWhwWUE&amp;revid=0ByOYG9JAvbZ4Si9PLzFUeC9kMWUvYlY1U0Iyb2JGa3dhRGdNPQ" class="CToWUd">&nbsp;<a href="http://shreeintl.hk" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://shreeintl.hk&amp;source=gmail&amp;ust=1490277330994000&amp;usg=AFQjCNEThTW7f8mpJmBGIW8DRD3TZoD9lQ">shreeintl.hk</a></font><br></font></div><div style="text-align:left"><font size="2" color="#999999" face="arial, helvetica, sans-serif">&nbsp;<img src="https://ci6.googleusercontent.com/proxy/xjoWKVXVCvwPyBlNi3qjuilT1O-hDfEFstHlVUI6k46BmireRpAIpCf--cm8_-nPfOzDcgLTgjb1e975yK0_IahNgfmmjgiSB549PW1ZMUHJHrJWb3e1_t2iCTlnOwr5CQs0BDC8gbKqNfGuO-SufCj53Ry674KmGxIMLn7zyLP7ECSVXmxUuNp76hFB9untCAnnzsmfQEZqdPA=s0-d-e1-ft#https://docs.google.com/uc?export=download&amp;id=0ByOYG9JAvbZ4TzRxdExFeV95ZDQ&amp;revid=0ByOYG9JAvbZ4VGIrNUU2YlBjb0RmYXhLZldRbXVCQ2Z1eHd3PQ" class="CToWUd">&nbsp;<a href="http://www.shreehk.com" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://www.shreehk.com&amp;source=gmail&amp;ust=1490277330994000&amp;usg=AFQjCNHCQs8Q8nwVy7gZ-fDMUXTpbdFweg">www.shreehk.com</a></font></div><div style="text-align:left"><font size="2" color="#999999" face="arial, helvetica, sans-serif">&nbsp;<img src="https://ci4.googleusercontent.com/proxy/s8pc2giBrr1hm46UVBYg8I6L730rvyxZhnov6vJG9PF0IE20Qhe23Qw_EhL8n2hSyC1p5LDYxedySZE6432-y60cCRy1X1vPf9lvOi7O6cQ8yj7Yhim2CoSbTZPsupiceWsrpcE4fgDmwSGptYxKO9pomnWln5Ma36LvPvfjAzooSamXCeHu_fI0zY5VixUghSL1d6pSJ2k_QG8=s0-d-e1-ft#https://docs.google.com/uc?export=download&amp;id=0ByOYG9JAvbZ4MG05SE5xbnlPNlU&amp;revid=0ByOYG9JAvbZ4RGFReWhxMVE5UzlNVUhMNnlFV1JsNldzMzNrPQ" class="CToWUd">&nbsp;91552 (shreehk)</font></div><div style="text-align:left"><table style="color:rgb(136,136,136);font-family:arial,sans-serif;font-size:12.8px" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td style="padding-top:6px;padding-bottom:7px;line-height:16px"><table style="color:rgb(68,68,68);padding-left:2px;width:401px" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td></td></tr></tbody></table></td></tr><tr><td><table style="line-height:1.6;font-family:sans-serif;font-size:11px;color:rgb(78,75,76);padding-left:2px;font-weight:bold;width:401px" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td><font size="2" face="arial, helvetica, sans-serif"><a href="https://www.facebook.com/Shreeintlhk/" style="color:rgb(17,85,204)" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=https://www.facebook.com/Shreeintlhk/&amp;source=gmail&amp;ust=1490277330994000&amp;usg=AFQjCNGND6Heyb2P2yxgQH2zDlOQUMNesg"><img src="https://ci5.googleusercontent.com/proxy/3ysP8__nmWC9GvjgQNYVHuKPnV4833DimOf3V-9WwOjwWDwaMMJkSo3gzm8VX1rwE6U1FQXgPgrIqK9T4LfTRK7Fspwn4S4t3EqI218VBRs6bhaAb0cZUXU=s0-d-e1-ft#https://s3.amazonaws.com/images.wisestamp.com/icons_32/facebook.png" style="border-radius:0px;border:0px;width:16px;height:16px" alt="" class="CToWUd" width="16" height="16"></a>&nbsp;<a href="http://www.linkedin.com" style="color:rgb(17,85,204)" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://www.linkedin.com&amp;source=gmail&amp;ust=1490277330994000&amp;usg=AFQjCNG6PwFAtz8ufdcqrDyWQ8JIiTfe2A"><img src="https://ci4.googleusercontent.com/proxy/SOGJyqe3XDVibRzjS7kIHMn0wxxN3gs6crnc5Tyx_rwYx-zapJaZ4W51mHXzz9XLp8D81kuRx4tU16AE-zG90B7FYe1huMd2_6tJGBkGoKZ7AwNkRe4w9zk=s0-d-e1-ft#https://s3.amazonaws.com/images.wisestamp.com/icons_32/linkedin.png" style="border-radius:0px;border:0px;width:16px;height:16px" alt="http://www.linkedin.com" class="CToWUd" width="16" height="16"></a>&nbsp;<a href="http://twitter.com" style="color:rgb(17,85,204)" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://twitter.com&amp;source=gmail&amp;ust=1490277330994000&amp;usg=AFQjCNFsiTGd7do40BBnSkjB5YNtihWClw"><img src="https://ci5.googleusercontent.com/proxy/wfND7YmOcmmmhOcWnAPwzldxiMKDQ0ZEH6B-aWTFmrHOM7uUt_zexgy5VYhiDKUmN7gAtJgoPUDvSICfZQKQOBkQqNGLKd6PQ2BgY9m4Yof9YUb5x3JnYA=s0-d-e1-ft#https://s3.amazonaws.com/images.wisestamp.com/icons_32/twitter.png" style="border-radius:0px;border:0px;width:16px;height:16px" alt="" class="CToWUd" width="16" height="16"></a>&nbsp;&nbsp;</font><br></td></tr></tbody></table></td></tr></tbody></table></div><div style="text-align:left;font-family:arial;font-size:small"><br></div></div>
		<p style="font-family:arial,sans-serif;font-size:11px">This message (and any associated files) is intended only for the use of the individual or entity to which it is addressed and may contain information that is confidential, subject to copyright or constitutes a trade secret. If you are not the intended recipient you are hereby notified that any dissemination, copying or distribution of this message, or files associated with this message, is strictly prohibited. If you have received this message in error, please notify us immediately by replying to the message and deleting it from your computer. Messages sent to and from us may be monitored</p>
		</td>
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


function updateProduct($cn)
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
		$model = new inventoryModel($cn);
		
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
function updateProductLoose($cn)
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
		$model = new inventoryModel($cn);
		
		$rs = $model->updateProductLoose($_POST);
		
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
function lexportToExcel($cn)
{
	$model = new inventoryModel($cn);
	$helper = new jHelper($cn);
	$data =  $model->getMyExportInventory($_POST['exportProducts'],'loose');
	$dateT = Date('Y-m-d-H-i-s');
	$filename = "Loose Stone Export ".$dateT.".xls";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$sheet = $objPHPExcel->getActiveSheet();
	

	// --------------- Default Configuration ----------------
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
	$attribute = $helper->getExportAttribute();
	unset($attribute['measurement']);
	unset($attribute['cost']);
	unset($attribute['igi_code']);
	foreach($attribute as $k=>$v)		
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
	$objPHPExcel->getActiveSheet()->setAutoFilter('A6:J6');
	
	// ---------- Record Data -------------------------
	$i= $firstid = $lastid = 7;
	$tp = $tc = $tprice = $ta = 0.0;
	foreach($data as $d)
	{
		
		$column = 'A';
		$tp = $tp +  $d['pcs'];
		$tc =  $tc + $d['carat'];
		$tprice = $tprice +  $d['price'];
		$ta = $ta +  $d['amount']; 
		foreach($attribute as $k=>$v)		
		{
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);	
			
			
			$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);	
			 
			 $column++;
			 
			 
		}  
		$column--;
		
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
	
	/* $sheet->SetCellValue('F2', 'PCS');
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
 */
	
		// -------------- freez header ---------------------------------------------
	 $sheet->mergeCells('A1:D5');
	$sheet->freezePane('A7');
	
	
	 
	 
	$address = 	array('font'  => array( 
				'size'	=> '12',
				'color' => array('rgb' => 'cc0066')
			));
			
/* 	$sheet->mergeCells('L1:T1');
	$sheet->SetCellValue('L1', 'Unit- 1808, 18/F, Multifield Plaza, 3-7A Prat Avenue, Tsim Sha Tsui, Kowloon, Hong Kong.');
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
	$sheet->getStyle('Q4')->applyFromArray($address ); */
	
	$objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setWrapText(true);
	//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->setPreCalculateFormulas(false);
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	$objWriter->save('php://output');
}

function cexportToExcel($cn)
{
	$model = new inventoryModel($cn);
	$helper = new jHelper($cn);
	$data =  $model->getMyExportInventory($_POST['exportProducts'],'collet');
	$dateT = Date('Y-m-d-H-i-s');
	$filename = "Collet Inventory Export ".$dateT.".xls";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$sheet = $objPHPExcel->getActiveSheet();
	

	// --------------- Default Configuration ----------------
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
	foreach($helper->getColletInventoryAttribute() as $k=>$v)		
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
	$objPHPExcel->getActiveSheet()->setAutoFilter('A6:J6');
	
	// ---------- Record Data -------------------------
	$i= $firstid = $lastid = 7;
	$tp = $tc = $tprice = $ta = 0.0;
	foreach($data as $d)
	{
		
		$column = 'A';
		$tp = $tp +  $d['pcs'];
		$tc =  $tc + $d['carat'];
		$tprice = $tprice +  $d['price'];
		$ta = $ta +  $d['amount']; 
	
		foreach($helper->getColletInventoryAttribute() as $k=>$v)		
		{
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$i, $d[$k]);	
			
			
			$objPHPExcel->getActiveSheet()->getStyle($column.$i)->applyFromArray($border);	
			 
			 $column++;
			 
			 
		}  
		$column--;
		
		$lastid  = $i;
		$i++;
	}
	
	
	// ---------- Freeze Header -------------------------
	
	$fill = array('fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'A4D2DF')
				));
	
	
		// -------------- freez header ---------------------------------------------
	 $sheet->mergeCells('A1:D5');
	$sheet->freezePane('A7');
	
	
	 
	 
	$address = 	array('font'  => array( 
				'size'	=> '12',
				'color' => array('rgb' => 'cc0066')
			));
			
	
	$objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setWrapText(true);
	//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->setPreCalculateFormulas(false);
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	$objWriter->save('php://output');
}

function stockTransfer($cn,$url)
{
	$response['status'] = 1;
	$response['message'] = "";
	
	$error =0;
	$message = "";
	if( empty($_POST["tofirm"]) || empty($_POST["date"]))
	{
		$message = "Value can't be Blank.";
		$error = 1;
	}	
	else	
	{	
		$model = new inventoryModel($cn);
		
		
		//echo "<pre>";
		//$url = 'http://192.168.1.101:8080/transfer/transfer.php';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$_POST);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);
		$rs = 4;
		//echo "<pre>";
		curl_close ($ch);
		//echo $server_output;
		$test = json_decode($server_output);
		//echo "<br>";
		print_r($server_output);
		//print_r($test ); 

		/* if($rs == 1)
		{
			$error = 0;
			$message = "Successfully  Transfer !!!";			
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
	echo json_encode($response); */
	}
}
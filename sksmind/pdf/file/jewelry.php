<?php
session_start();
require_once '../../database.php';
//require_once '../../variable.php'; 
require_once '../../dai/jHelper.php';
  
require_once '../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
$id = 0;
if(isset($_POST['ids']))
	$id = $_POST['ids'];
else
	return 0;
try {
	$jhelper = new jHelper($cn);
	$data = $jhelper->getAllJewelry($id);
	$jewType = $jhelper->getJewelryType();
	ob_start();
	
	include dirname(__FILE__).'/jewelry_html.php';
		
	
    $content = ob_get_clean();
	$dateT = Date('YmdHis');
    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    
	//$html2pdf->Output('Jobwork-.pdf');
	$html2pdf->Output('Jewelry.pdf');
}
catch (Html2PdfException $e) {
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}

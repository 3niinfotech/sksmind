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
if(isset($_GET['id']))
	$id = $_GET['id'];
else
	return 0;
	
try {
	$helper = new jHelper($cn);
	$data = $helper->getMemoDetail($id);
/* 	echo "<pre>";
	print_r($data);
	exit;
	 */
    // get the HTML
    ob_start();
	if(count($data['record']) >= 14 )
		include dirname(__FILE__).'/m_invoice_html.php';
	else
		include dirname(__FILE__).'/invoice_html.php';
	
    $content = ob_get_clean();
	$dateT = Date('YmdHis'); 
    $html2pdf = new Html2Pdf('P', 'A4', 'fr',true, 'UTF-8', array(5, 5, 5, 1));
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->Output('Invoice-'.$data['invoiceno'].'.pdf');
} catch (Html2PdfException $e) {
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}
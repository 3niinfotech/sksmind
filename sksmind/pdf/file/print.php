<?php

session_start();
require_once '../../database.php';
require_once '../../variable.php'; 
require_once $daiDir.'jHelper.php';  
require_once '../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

$jid = 0;
if(isset($_GET['id']))
	$jid = $_GET['id'];
else
	return 0;
try {
	
	$jhelper = new jHelper($cn);
    //ob_start();
	include dirname(__FILE__).'/print_html.php';
	//include dirname(__FILE__).'/print_htmla4.php';
	
    $content = ob_get_clean();
	$dateT = Date('YmdHis');
    $html2pdf = new Html2Pdf('L', array(75,30), 'fr',true, 'UTF-8', array(0, 5, 1, 1));
    //$html2pdf = new Html2Pdf('P', 'A4', 'fr',true, 'UTF-8', array(5, 5, 5, 5));
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    
	$html2pdf->Output('Print.pdf');
} catch (Html2PdfException $e) {
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}



/* session_start();
require_once '../../database.php';
require_once '../../variable.php'; 
require_once $daiDir.'Helper.php';   

require_once '../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


	
try {
	$helper = new Helper();
	$data = $helper->getProductDetail($_GET['id']);
	
	include dirname(__FILE__).'/print_html.php';
	
    $content = ob_get_clean();
	$dateT = Date('YmdHis'); 
    $html2pdf = new Html2Pdf('L', array(60,30), 'fr',true, 'UTF-8', array(2, 1, 2, 1));
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->Output('Invoice-'.$dateT.'.pdf');
} catch (Html2PdfException $e) {
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}
 */

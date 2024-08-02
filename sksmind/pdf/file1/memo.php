<?php
session_start();
require_once '../../database.php';
require_once '../../variable.php'; 
require_once $daiDir.'Helper.php';  

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
	$helper = new Helper;
	$data = $helper->getMemoDetail($id);


    // get the HTML
    ob_start();
      
    if(count($data['record']) >= 13 )
		include dirname(__FILE__).'/m_memo_html.php';
	else
		include dirname(__FILE__).'/memo_html.php';
	
    $content = ob_get_clean();
	$dateT = Date('YmdHis');
    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    
	$html2pdf->Output('Memo-'.$dateT.'.pdf');
} catch (Html2PdfException $e) {
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}

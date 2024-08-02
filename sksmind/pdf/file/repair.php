<?php
session_start();
require_once '../../database.php';
require_once '../../variable.php'; 
require_once $daiDir.'jHelper.php';  

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
	$jhelper = new jHelper($cn);
	//$data = $jhelper->getMemoDetail($id);
	$data = $jhelper->getRepairDetail($id);
	/*  echo "<pre>";
	print_r($data);
	exit;  */
	$design = $jhelper->getAllDesign();
	$mmaker = $jhelper->getAllMemoMaker();
	//$mainStone = explode(",",$data['main_stone']);
	//$sideStone = explode(",",$data['side_stone']);
    // get the HTML
    ob_start();
      
    
	include dirname(__FILE__).'/repair_html.php';
	
    $content = ob_get_clean();
	$dateT = Date('YmdHis');
    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    
	$html2pdf->Output('Jobwork-'.$data['entryno'].'.pdf');
} catch (Html2PdfException $e) {
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}

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
	$jhelper = new jHelper;
	//$data = $jhelper->getMemoDetail($id);
	$data = $jhelper->getJobworkDetail($id);
	$design = $jhelper->getAllDesign();
	$jewType = $jhelper->getJewelryType();
	$mmaker = $jhelper->getAllMemoMaker();
	//$mainStone = explode(",",$data['main_stone']);
	//$sideStone = explode(",",$data['side_stone']);
    // get the HTML
    ob_start();
	$imgUrl = $mainUrl.'sksm/';
	if($data['jew_design'] != 0)
		$jd = $design[$data['jew_design']].'.jpg';
	
	/* echo $imgUrl.'jewelry/'.$jd;
	exit; */
	/* echo "<pre>";
	print_r($data);
	exit; */
	
    if($data['job'] =='jewelry'):		
		include dirname(__FILE__).'/jobwork_jewelry_html.php';
	else:
		include dirname(__FILE__).'/jobwork_collet_html.php';
	endif;
	
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

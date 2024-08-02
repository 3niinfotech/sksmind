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
$type = $_GET['type'];

try {
	$jhelper = new jHelper($cn);
	//$data = $jhelper->getMemoDetail($id);
	$data = $jhelper->getJobworkDetail($id);
	/*  echo "<pre>";
	print_r($data);
	echo "</pre>";
	exit; */ 
	$party = $jhelper->getPartyDetail($data['party']);
	
	$design = $jhelper->getAllDesign();
	$jewType = $jhelper->getJewelryType();
	$mmaker = $jhelper->getAllMemoMaker();
	//$mainStone = explode(",",$data['main_stone']);
	//$sideStone = explode(",",$data['side_stone']);
    // get the HTML
    $phpdate = strtotime( $data['date'] );
	$date =  date( 'd-m-Y', $phpdate );
	/* $imgUrl = $mainUrl.'sksm/';
	if($data['jew_design'] != 0)
		$jd = $design[$data['jew_design']].'.jpg'; */
	
	
	ob_start();
	if($data['job'] =='collet'):
	
		if($type == 'original')
			include dirname(__FILE__).'/jobwork_collet_html_O.php';
		else
			include dirname(__FILE__).'/jobwork_collet_html_C.php';
	else:
		if($type == 'original')
			include dirname(__FILE__).'/jobwork_jewelry_html_O.php';
		else
			include dirname(__FILE__).'/jobwork_jewelry_html_C.php';
		
	endif;	
	
    $content = ob_get_clean();
	$dateT = Date('YmdHis');
    $html2pdf = new Html2Pdf('L', 'A4', 'fr');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    
	//$html2pdf->Output('Jobwork-.pdf');
	$html2pdf->Output('Jobwork-'.$type.'_'.$data['entryno'].'.pdf');
}
catch (Html2PdfException $e) {
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}

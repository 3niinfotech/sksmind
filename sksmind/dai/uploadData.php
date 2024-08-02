<?php
session_start();
include_once('../variable.php');
include("../database.php");
include_once('Helper.php');
include_once('uploadHelper.php');



$Phelper  = new Helper($cn);
$uploadHelper  = new uploadHelper($cn);
$importData = $Phelper->getToImportData();	
$countSite = count($importData);
$TotalsiteUpdate = $_SESSION['importData'];
$RemainingSite = $TotalsiteUpdate - $countSite;
if($TotalsiteUpdate)
	$sitePc = number_format(($RemainingSite * 100) / (int)$TotalsiteUpdate,0);

	
?>
<i class="ace-icon red fa fa-close"  onClick="closeProgress()" id="close-progress" ></i>

<div class="upload-process">
<?php if($TotalsiteUpdate): ?>
	<?php if($countSite): ?>
	<div ><img src="<?php echo $daiUrl.'assets\images\ajax-loader.gif' ?> " />  We Are Uploading Your Data (<?php echo $RemainingSite?> / <?php echo $TotalsiteUpdate?>) . . . </div>
	<?php else: 
	unset($_SESSION['last_inward']);
	?>
	
	<div class="green"><i class="ace-icon fa fa-check"></i> Uploading Done</div>
	<?php endif; ?>
	<br style="clear:both">
	
	<div class="progress progress-striped pos-rel active" data-percent="<?php echo $sitePc ?>%">
		<div class="progress-bar progress-bar-success" style="width: <?php echo $sitePc ?>%;"></div>
	</div>
	
	<br>
	<p class="red">Note : Please do not close Browser or Tab untill stock upload.</p>
	<?php if(!$countSite): ?>
	<button class="btn btn-success" type="button" style=" margin: 0px;" onclick="location.reload()">
			<i class="ace-icon fa fa-refresh bigger-110"></i> Realod
		</button>
	<?php endif; ?>	
	


<?php endif; ?>


<?php 
 if($countSite){
	foreach($importData as $data)	{
		$rs = $uploadHelper->importData($data);
		if($rs !=1 )
		{	
		echo "<p>".$rs.'</p>';
		}
		break;
	}	
}
else
{
	unset($_SESSION['last_inward']);
}
?>


<?php
session_start($cn);
include_once('../variable.php');
include("../database.php");
include_once('Helper.php');
include_once('SiteHelper.php');
include_once('RapHelper.php');


$Phelper  = new Helper($cn);
$SiteHelper  = new SiteHelper($cn);
$siteUpdate = $Phelper->getSiteUpdated($cn);	
$countSite = count($siteUpdate);
$TotalsiteUpdate = $_SESSION['TotalsiteUpdate'];
$RemainingSite = $TotalsiteUpdate - $countSite;
if($TotalsiteUpdate)
	$sitePc = number_format(($RemainingSite * 100) / (int)$TotalsiteUpdate,0);

	
$RapHelper  = new RapHelper($cn);
$rapUpdate = $Phelper->getRapUpdated($cn);	

$countRap = count($rapUpdate);

$TotalRapUpdate = $_SESSION['TotalRapUpdate'];

$RemainingRap = $TotalRapUpdate - $countRap;
echo $RemainingRap;
if($TotalRapUpdate)
	$rapPc = number_format(($RemainingRap * 100) / (int) $TotalRapUpdate,0);
	
?>
<i class="ace-icon red fa fa-close"  onClick="closeProgress()" id="close-progress" ></i>
<?php if($TotalsiteUpdate): ?>
<div class="upload-process">
	<?php if($countSite): ?>
	<div ><img src="<?php echo $daiUrl.'assets/images/ajax-loader.gif' ?> " /> Site Synchronizing (<?php echo $RemainingSite?> / <?php echo $TotalsiteUpdate?>) . . . </div>
	<?php else: ?>
	<div class="green"><i class="ace-icon fa fa-check"></i> Site Synchronizing Done</div>
	<?php endif; ?>
	<br style="clear:both">
	
	<div class="progress progress-striped pos-rel active" data-percent="<?php echo $sitePc ?>%">
		<div class="progress-bar progress-bar-success" style="width: <?php echo $sitePc ?>%;"></div>
	</div>
</div>
<?php endif; ?>

<?php if($TotalRapUpdate): ?>
<div class="upload-process">
	<?php if($countSite): ?>
	<div> <img src="<?php echo $daiUrl.'assets/images/ajax-loader.gif' ?> " /> Rapnet Synchronizing (<?php echo $RemainingRap?> / <?php echo $TotalRapUpdate?>) . . .</div>
	<?php else: ?>
	<div class="green"><i class="ace-icon fa fa-check"></i> Rapnet Synchronizing Done</div>
	<?php endif; ?>
	<br style="clear:both">
	<div class="progress pos-rel progress-striped active" data-percent="<?php echo $rapPc ?>%">
		<div class="progress-bar progress-bar-warning" style="width: <?php echo $rapPc ?>%;"></div>
	</div>
</div>
<?php endif; ?>
<?php 
/* if($countSite)
{
	foreach($siteUpdate as $k=>$id)
	{
		$SiteHelper->uploadData($id);
		break;
	}	
}
if($countRap)
{
	foreach($rapUpdate as $k=>$id)
	{
		$RapHelper->uploadData($id);
		break;
	}	
} */
?>
<script>

</script>
 
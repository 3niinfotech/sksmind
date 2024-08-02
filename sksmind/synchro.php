<?php
//session_start();
include_once('variable.php');
include("database.php");
include_once('dai/Helper.php');
include_once('dai/SiteHelper.php');
include_once('dai/RapHelper.php');


$Phelper  = new Helper();
$SiteHelper  = new SiteHelper();
$siteUpdate = $Phelper->getSiteUpdated();	
$countSite = count($siteUpdate);
/* $TotalsiteUpdate = $_SESSION['TotalsiteUpdate'];
$RemainingSite = $TotalsiteUpdate - $countSite;
if($TotalsiteUpdate)
	$sitePc = number_format(($RemainingSite * 100) / (int)$TotalsiteUpdate,0);
 */
	
$RapHelper  = new RapHelper();
$rapUpdate = $Phelper->getRapUpdated();	

$countRap = count($rapUpdate);
/* $TotalRapUpdate = $_SESSION['TotalRapUpdate'];
$RemainingRap = $TotalRapUpdate - $countRap;
if($TotalRapUpdate)
	$rapPc = number_format(($RemainingRap * 100) / (int) $TotalRapUpdate,0);
 */
if($countSite)
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
}
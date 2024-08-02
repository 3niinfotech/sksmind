<?php
session_start();

include("../../../database.php");
include("../../../variable.php");

include_once($daiDir.'module/party/partyModel.php');

$pmodel  = new partyModel();
$party = $pmodel->getOptionList();

?>
<option value="">Select Party</option>
<?php 
foreach($party as $key => $value):
?>						
<option value="<?php echo $key?>" ><?php echo $value?></option>
<?php endforeach; ?>

<?php 
session_start();
include("../../../database.php");
include("../../../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 	
include_once('partyModel.php');
$model  = new partyModel($cn);

$BData = $model->getData($_GET['id']);														

?>	

<div class="page-header">							
	<h1 style="float:left">
		<?php echo $BData['name'] ?>								
	</h1>
	<button id="close-box" onclick="closeBox()" style="float:right" class="btn grid-btn btn-danger" type="button">
		<i class="ace-icon fa fa-close bigger-110"></i>
		Close
	</button>
</div>


<div class="col-xs-12 col-sm-12 report-content " >
<div class="col-xs-12 col-sm-12 " >

	<div class="history-attribute">
		<div class="attribute-label">Address</div>
		<div class="attribute-value"><?php echo $BData['address'];?></div>
	</div>
	<div class="history-attribute">
		<div class="attribute-label">Email</div>
		<div class="attribute-value"><?php echo $BData['email'];?></div>
	</div>

	<div class="history-attribute">
		<div class="attribute-label">Country</div>
		<div class="attribute-value"><?php echo $BData['country'];?></div>
	</div>
	
	<div class="history-attribute">
		<div class="attribute-label">Contact Number</div>
		<div class="attribute-value"><?php echo $BData['contact_number'];?></div>
	</div>
	<div class="history-attribute">
		<div class="attribute-label">Contact Person</div>
		<div class="attribute-value"><?php echo $BData['contact_person'];?></div>
	</div>

	<div class="history-attribute">
		<div class="attribute-label">Web Site</div>
		<div class="attribute-value"><?php echo $BData['website'];?></div>
	</div>
	
</div>
</div>			

			
<style>
.history-block {
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  border-color: #9abc32 #ccc #ccc;
  border-image: none;
  border-radius: 2px;
  border-style: solid;
  border-width: 5px 1px 1px;
  margin: 10px;
  padding: 0;
  width: 100%;
}
.history-head {
  border-bottom: 1px dashed #ccc;
  float: left;
  padding: 7px;
  width: 100%;
}
.history-type {
  color: #2679b5;
  float: left;
  font-size: 14px;
  font-weight: bold;
  letter-spacing: 1px;
  width: 50%;
}
.history-date {
  color: #666;
  float: left;
  font-size: 14px;
  letter-spacing: 1px;
  text-align: right;
  width: 50%;
}
.history-decription {
  float: left;
  padding: 15px 10px;
  width: 100%;
  height:100px;
}
.history-attribute {
  border-bottom: 1px solid #ccc;
  float: left;
  margin-bottom: 20px;
  margin-right: 3%;
  padding-bottom: 5px;
  width: 46%;
}
.attribute-label {
  color: #2679b5;
  float: left;
  font-size: 14px;
  width: 50%;
}
.attribute-value {
  color: #333;
  float: left;
  text-align: right;
  font-size: 14px;
  width: 50%;
}
.history-decription p {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>			

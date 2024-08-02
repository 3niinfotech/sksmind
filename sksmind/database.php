<?php
error_reporting(0);
$cn=mysqli_connect("localhost","shreehkw_snjadmin","_Bsrq1FV(9V.") or die("Could not Connect My Sql");
mysqli_select_db($cn,"shreehkw_sksmind")  or die("Could not connect to Database");
mysqli_query($cn,"SET SESSION sql_mode = ''");
?>

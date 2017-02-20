<!DOCTYPE html>
<?php
	session_start();
	header('Content-Type: text/html; charset=CP1255');
	
	ob_start();
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	
	include 'DBConect.php';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1255">
<?php

	$UWFixID = $_SESSION['UWFixID'];
	$date = $_POST['month'];
	$month = substr($date,0,2);
	$year = substr($date,-4);

	$sql = "INSERT into ReportsRequests (";
	$sql = $sql . "WFixID, ";
	$sql = $sql . "Year, ";
	$sql = $sql . "Month, ";
	$sql = $sql . "ReportType, ";
	$sql = $sql . "ReportName, ReportTypeNumber)";
	$sql = $sql . "values (";
	$sql = $sql . "" . $UWFixID . "" . ",";
	$sql = $sql . "'" . $year . "'" . ",";
	$sql = $sql . "'" . $month . "'" . ",";
	$sql = $sql . "'" . "Shaon" . "'" . ",";
	$sql = $sql . "'" . "כרטיס שעון כללי גמיש" . "'" . ",";
	$sql = $sql . "" . 3 . ")";

	$sth = $dbh->query($sql);
	$dbh = null;
	$sth = null;
?>
<script>
	alert("ההזמנה הושלמה בהצלחה./nפירוט הנוכחויות ישלח לכתובת המייל השמורה במערכת.");
</script>
<?php
	if($_SESSION['mng'] == 0){
		header("Location: SWWeeks.php");
		exit();
	}else{
		header("Location: Workers.php");
		exit();
	}
	

?>

</head>
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
	<meta charset="windows-1255">
	<link rel="stylesheet" href="css/psos.css" type="text/css">
<?php
        if (!isset($_SESSION['UWorkerNum'])){
		header("Location: login.php?stat=3");
		exit();
	}
	if($_SESSION['mng'] != 2){
		header("Location: login.php");
		exit();
	}

	$WrkId=$_SESSION['UWorkerName'];
	$WrkNm=$_SESSION['UWorkerNum'];
	$IniId = $_POST['IniId'];
	$monthOrWeek = $_POST['monthOrWeek'];
	if($monthOrWeek == "month"){
		$sql = " Update IniFile set Parameter = 'StartOfPreviousMonth' Where ID = 28";
	}
	else if($monthOrWeek == "week" && isset($_POST['NumOfWeeks'])){
		$NumOfWeeks = $_POST['NumOfWeeks'];
		$sql = " Update IniFile set Parameter = 'ByDepthOfInternetOnlineDisplay' Where ID = 28";
		$sql1 = " Update IniFile set NumOfWeeks = " . $NumOfWeeks . " Where ID = 1";
	}
	if(isset($_POST['NumberOfManualChangesInInternetOnline'])){
		$NumberOfManualChangesInInternetOnline = $_POST['NumberOfManualChangesInInternetOnline'];
		$sql2 = " Update IniFile set Parameter = " . $NumberOfManualChangesInInternetOnline . " Where ID = 68";
	}
	
	echo $vbCrLf . "<!--" . $sql . "-->" . $vbCrLf;
	echo $vbCrLf . "<!--" . $sql1 . "-->" . $vbCrLf;
	echo $vbCrLf . "<!--" . $sql2 . "-->" . $vbCrLf;
	
	if(isset($sql)){
		$sthIni = $dbh->query($sql);
	}
	if(isset($sql1)){
		$sthIni = $dbh->query($sql1);
		$_SESSION['WeeksPerPage'] = $NumOfWeeks;
	}
	if(isset($sql2)){
		$sthIni = $dbh->query($sql2);
		$_SESSION['NumberOfManualChangesIOL'] = $NumberOfManualChangesInInternetOnline;
	}
	$dbh = null;
	$sth = null;
	header("Location: Workers.php");
	exit();
?>
</head>
</html>
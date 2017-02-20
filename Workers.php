<!DOCTYPE html>
<?php
	session_start();
	header('Content-Type: text/html; charset=CP1255');
	
	ob_start();
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	
	include 'DBConect.php';
	include 'Funcs.php';
	
	if (empty($_SESSION['un'])){
		header("Location: login.php?stat=3");
		exit();
	}
	if($_SESSION['mng'] == 0){
		header("Location: SWWeeks.php");
		exit();
	}
	
	$_SESSION['WorkerNum'] = $_SESSION['UWorkerNum'];
	$_SESSION['WorkerName'] = $_SESSION['UWorkerName'];
	$_SESSION['WFixID'] = $_SESSION['UWFixID'];
	$_SESSION['DepMail'] = $_SESSION['UDepMail'];
	$_SESSION['IncldInMadan'] = $_SESSION['UIncldInMadan'];
	$_SESSION['IncldInTmhir'] = $_SESSION['UIncldInTmhir'];
?>

<html>
<head>
	<title>psos - malram</title>
	<META charset="windows-1255">
	<META HTTP-EQUIV="expires" CONTENT="0">
	<META HTTP-EQUIV="pragma" CONTENT="no-cache">
	<script src="Funcs.js"></script>
	<link href="css/psos.css" rel="stylesheet" type="text/css">
	<link href="css/login.css" rel="stylesheet" type="text/css">
</head>

<body CLASS="background">

	<table CLASS="page">
		<tr>
			<td colspan=2><?php include 'toplogo.php';?></td>
		</tr>
		<tr>
			<td CLASS="leftmenu">				
				<?php include 'leftmenu.php';?>
			</td>
			<td style="vertical-align:top; width:auto;">
				<form ID=Worker Name=Worker method=post>
					<table CLASS="data">
						<?php include 'ShowInfo.php';?>
						<?php include 'ShowWorkers.php';?>
					</table>
				</form>
			</td>
		</tr>
	</table>
</body>
</html>
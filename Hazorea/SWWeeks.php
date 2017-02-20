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
	$un = $_SESSION['un'];
	if (isset($_GET['WFixID'])){
		if(isset($_GET['fromWorkersPage'])){
			$sql = "SELECT * FROM Select_Worker_Permitions WHERE WFixID = ".$_GET['WFixID'];
			$sth = $dbh->query($sql);
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			if (empty($result)){
				$sth = null;
				$dbh = null;
				header("Location: login.php");
				exit();
			}else{
				$_SESSION['WorkerNum'] = trim($result["WorkerNum"]);
				$_SESSION['WorkerName'] = trim($result["LastName"]." ".$result["FirstName"]);
				$_SESSION['WFixID'] = $_GET['WFixID'];
				$_SESSION['DepMail'] = $result["DepMail"];
				$_SESSION['IncldInMadan'] = $result['IncldInMaden'];
				$_SESSION['IncldInTmhir'] = $result['IncldInTmhir'];
				$_SESSION['DefaultDep'] = $result["TDprtmnt"];
				$_SESSION['DefaultJob'] = $result["TJob"];
			}
		}else{
			$_SESSION['WorkerNum'] = $_GET['WorkerNum'];
			$_SESSION['WorkerName'] = trim($_GET['WorkerName']);
			$_SESSION['WFixID'] = $_GET['WFixID'];
			$_SESSION['DepMail'] = $_GET['DepMail'];
			$_SESSION['IncldInMadan'] = $_GET['IncldInMadan'];
			$_SESSION['IncldInTmhir'] = $_GET['IncldInTmhir'];
			$_SESSION['DefaultDep'] = $_GET['defDep'];
			$_SESSION['DefaultJob'] = $_GET['defJob'];
		}
	}
?>

<html>
<head>
	<title>psos - malram</title>
	<meta charset="windows-1255">
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
				<table CLASS="data">
					<?php include 'ShowInfo.php';
					include 'SWShowWeek.php';?>
				</table>
			</td>
			<td style="vertical-align:top;">
				<?php
					$note1 = "מספר שינויים המותר לביצוע בכל חודש: ";
					$note2 = "מספר שינויים שכבר בוצעו בחודש זה: ";
					$note3 = "מספר שינויים שכבר בוצעו בחודש קודם: ";
					$thisMonth = $result2[0]['ManualUpdatesNumber'] / 1;
					$lastMonth = $result2[0]['ManualUpdatesNumberPM'] / 1;
				?>
				<fieldset>
					<legend style="text-align:right;font-weight:bold;color:red;">:שים לב</legend>
					<div style="color:red; width:150px; text-align:right;">
						<p> <?= $note1 ?> &nbsp; <?= $_SESSION['NumberOfManualChangesIOL'] ?> </p>
						<p> <?= $note2 ?> &nbsp; <?= $thisMonth ?> </p>
						<p> <?= $note3 ?> &nbsp; <?= $lastMonth ?> </p>
					</div>
				</fieldset>
			</td>
		</tr>
	</table>
</body>
</html>
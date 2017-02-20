<!DOCTYPE html>
<?php
	session_start();
	header('Content-Type: text/html; charset=CP1255');
	
	ob_start();
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	
	include 'DBConect.php';
	include 'Funcs.php';
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

<?php
	$RecID = $_GET['RecID'];
	$UWFixID = $_SESSION['UWFixID'];
	$WeekNum = "";
	
	$sql = "SELECT * FROM WeekStatusPerWorker WHERE RecID = " . $RecID . "";
	echo "<!--" . $sql . "-->";
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);

	if (empty($result)){
		$Status = "O";
		$CalculatedStatus = "O";
	}else{
		$WeekNum = $result['WeekNumber'];
		echo "<!--" . $result['WeekStatus'] . "==" . "==" . $result['CalculatedWeekStatus'] . "-->";
		if (trim($result['CalculatedWeekStatus']) == "F"){
			$Status = "F";
		}else{
			$Status = trim($result['WeekStatus']);
		}
	}

	if ($Status == " " or $Status == ""){
		$Status = "O";
	}
	$dbh = null;
	$sth = null;
?>

</head>

<body style="text-align:center; margin-top:5%;">

		<form name="ChWStatus" action="ChWeekStatus-sql.php" method="post">

			<?php if ($Status == "O" and ($_SESSION['mng'] == "0" or $_SESSION['mng'] == "2" or $_SESSION['UWFixID'] == $_SESSION['WFixID'])):?>
				<input type="submit" ID="Release" Name="Release" value="לחץ כאן כדי לשחרר את השבוע לאישור"><br>
			<?php endif?>

			<?php if ($Status == "C" and $_SESSION['mng'] == "2"):?>
				<input type="submit" ID="Open" Name="Open" value="לחץ כאן כדי לפתוח את השבוע לדיווח נוסף"><br>
			<?php endif?>

			<?php if ($Status == "R" and ($_SESSION['mng'] == "1" or $_SESSION['mng'] == "2")):?>
				<input type="submit" ID="Close" Name="Close" value="לחץ כאן כדי לסגור את השבוע "><br>
				<input type="submit" ID="Open" Name="Open" value="לחץ כאן כדי לפתוח את השבוע לדיווח נוסף"><br>
			<?php endif?>

			<input type="button" ID="Cancel" Name="Cancel" value="סגור ללא פעולה" OnClick="javascript:window.close();"><br>
			<input type="HIDDEN" name="RecID" value="<?=$RecID?>">
			<input type="HIDDEN" name="WeekNum" value="<?=$WeekNum?>">
		</form>

</body>
</html>
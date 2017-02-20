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
	<link rel="stylesheet" href="css/psos.css" type="text/css">
	<link rel="stylesheet" href="css/login.css" type="text/css">
<?php
	$WeekNum = $_POST['WeekNum'];
	$RecID = $_POST['RecID'];
	$newStatusImg = "";
	$StatusAlt = "";

	if (isset($_POST['Release'])){
		$sql = "UPDATE WeekStatusPerWorker SET WeekStatus ='R' WHERE RecID = " . $RecID;
		echo "<!--" . $sql . "-->";
		$sth = $dbh->query($sql);
		$newStatusImg = "images/R_md_clr.gif";
		$StatusAlt = "Released";
	}

	else if (isset($_POST['Open'])){
		$sql = "UPDATE WeekStatusPerWorker SET WeekStatus ='O' WHERE RecID = " . $RecID;
		echo "<!--" . $sql . "-->";
		$sth = $dbh->query($sql);
		$newStatusImg = "images/O_md_clr.gif";
		$StatusAlt = "Open";
	}

	else if (isset($_POST['Close'])){
		$sql = "UPDATE WeekStatusPerWorker SET WeekStatus = 'C', ApprovedByWFixID = " . $_SESSION['UWFixID'] . ", ApprovedByDate = '" . date('Y/m/d H:i') . "' WHERE RecID = " . $RecID . "";
		echo "<!--" . $sql . "-->";
		$sth = $dbh->query($sql);
		$newStatusImg = "images/C_md_clr.gif";
		$StatusAlt = "Closed";
	}
	$dbh = null;
	$sth = null;
?>

<SCRIPT>

	window.opener.document.getElementById("status<?=$WeekNum?>").src = "<?=$newStatusImg?>";
	window.opener.document.getElementById("status<?=$WeekNum?>").alt = "<?=$StatusAlt?>";
	var buttons = window.opener.document.getElementsByClassName("buttons<?=$WeekNum?>");
	for(var i = 0; i < buttons.length; i++) {
		var id = buttons[i].id;
	    if("<?=$StatusAlt?>" == "Open" && id != "1"){
	    	buttons[i].disabled = false;
	    }else{
	    	buttons[i].disabled = true;
	    }
	}
	window.close();
</SCRIPT>
</head>
</html>
<!DOCTYPE html>
<?php
	session_start();
	header('Content-Type: text/html; charset=CP1255');
	
	ob_start();
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	
	include 'DBConect.php';
	include 'Funcs.php';
	$FullDate = changeFormat("Y/m/d",$_GET['FullDate'],"d/m/Y"); // used inside <script> section
?>

<html>
<head>
	<title>psos - malram</title>
	<META charset="windows-1255">
	<META HTTP-EQUIV="expires" CONTENT="0">
	<META HTTP-EQUIV="pragma" CONTENT="no-cache">
	<script src="Funcs.js"></script>
	<script src="DatePickerLibraries/jquery-1.6.min.js"></script>
	<script src="DatePickerLibraries/jquery-ui.min.js"></script>
	<script src="DatePickerLibraries/jquery-ui-timepicker-addon.min.js"></script>
	<link href="css/psos.css" rel="stylesheet" type="text/css">
	<link href="css/login.css" rel="stylesheet" type="text/css">
	<link href="DatePickerLibraries/jquery-ui.min.css" rel="stylesheet" type="text/css">
	<link href="DatePickerLibraries/jquery-ui-timepicker-addon.min.css" rel="stylesheet" type="text/css">
	<script>
		var param1var = getQueryVariable("FullDate",2);
		var a = new Date(param1var);
		var b = new Date(param1var);
		b.setDate(b.getDate()+2);
		$(function() {
			$( "#fromTime" ).timepicker({
				hour: 8
			});
			$( "#endDate" ).datetimepicker({
				dateFormat: "dd/mm/yy",
				altField: "#endTime",
				defaultDate: a,
				minDate: a,
				maxDate: b,
				hour: 17
			});
		});
	</script>
	
<?php
	if (empty($_SESSION['un'])){
		header("Location: NotlogedIn.php");
		exit();
	}

	$WorkerNum = $_SESSION['WorkerNum'];
	$WorkerName = $_SESSION['WorkerName'];
	$WFixID = $_SESSION['WFixID'];
	$PresentID = $_GET['PresentID'];
	
	$FullDateA = date_create_from_format("Y/m/d", $_GET['FullDate']);
	$FullDateB = date_create();
	$interval = date_diff($FullDateB, $FullDateA);
	$interval = $interval->format('%R%a');
	
	$WeekStatus = strtoupper($_GET['WeekS']);
	$FromDate = $FullDate;
	$EndDate = $FullDate;
	$OriginalPresentID = 0;
	$OriginalTransacionId = 0;
	$PresentID = 0;
	if($_SESSION['IncldInTmhir'] OR $_SESSION['IncldInMadan']){$strDprt = BuildDepSList(" ",$dbh);}
	if($_SESSION['IncldInTmhir']){$strJobs = BuildJobSList(" ",$dbh);}
	$PresentCd =  "רגילה";
	$strSugeiNochechut = BuildSugeiNochechutList($PresentCd,$dbh);
	//this file was included. seems expandable.
	//include 'depNjobList.php';
	$dbh = null;
	$sth = null;
?>
</head>
<body CLASS="popup-background">
	<form name=calform action="fulrep-w1.php" method="post" enctype="multipart/form-data">
		<table CLASS=iniFile dir=rtl>
			<tr>
				<td style="vertical-align:top;">
					<br>
					<div>
						  <div dir=rtl class="fill2"><h2>&nbsp;פתיחת דיווח מלא ל<?=$WorkerName?>&nbsp;<?=$WorkerNum?>&nbsp;</h2></div>
					</div>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:top;">
					<table style="text-align:right; padding:2; border:0;">
						<tr>
							<td style="text-align: right;">סוג נוכחות</td>
							<td style="width:100px; text-align: right;">
								<select name="strSugeiNochechutDtl" dir="rtl">
									<?=$strSugeiNochechut?>
								</select>
							</td>
						</tr>
						<tr>
							<td>תאריך כניסה:</td>
							<td>
								<input type="text" id="fromDate" name="fromDate" size="30" value="<?=$FullDate?>" disabled>
								<input type="HIDDEN" name="fromDate1" value="<?=$FullDate?>">
							</td>
						</tr>
						<tr>
							<td>שעת כניסה:</td>
							<td>
								<input type="text" name="fromTime" ID="fromTime" size="30" value="" required>
							</td>
						</tr>
						<tr>
							<td>תאריך יציאה:</td>
							<td>
								<input type="text" id="endDate" name="endDate" size="30" value="">
							</td>
						</tr>
						<tr>
							<td>שעת יציאה:</td>
							<td>
								<input type="text" name="endTime" ID="endTime" size="30" value="">
							</td>
						</tr>
						<?php if ($_SESSION['IncldInTmhir'] or $_SESSION['IncldInMadan']):?>
							<tr>
								<td style="text-align: right;">פרטי מחלקה / פרוייקט</td>
								<td style="text-align: right;">
									<select name="DepDtl" dir="rtl">
										<?=$strDprt?>
									</select>
								</td>
							</tr>
						<?php endif?>
						<?php if ($_SESSION['IncldInTmhir']):?>
							<tr>
								<td style="text-align: right;">פרטי ג'וב / משימה</td>
								<td style="text-align: right;">
									<select name="JobDtl" dir="rtl">
										<?=$strJobs?>
									</select>
								</td>
							</tr>
						<?php endif?>
						<tr>
							<td>צרף מסמך:</td>
							<td>
								<input type="file" accept=".pdf,.jpg,.png,.gif,.jpeg" name="fileToUpload" id="fileToUpload" size="16">
							</td>
						</tr>
						<tr>
							<td colspan=2 style="text-align: center;">
								<input type="Submit" name="submit" value="אישור" style="width: 100px;">
								<input type="Submit" name="cancel" value="חזרה" style="width: 100px;" OnClick="window.close();return false;">
								<input type="HIDDEN" name="PresentID" value="<?=$PresentID?>">
								<input type="HIDDEN" name="OriginalPresentID" value="<?=$OriginalPresentID?>">
								<input type="HIDDEN" name="OriginalTransacionId" value="<?=$OriginalTransacionId?>">
								<input type="HIDDEN" name="interval" value="<?=$interval?>">
								<input type="HIDDEN" name="FormType" value="N">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
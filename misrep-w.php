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
	<script src="DatePickerLibraries/jquery-1.6.min.js"></script>
	<script src="DatePickerLibraries/jquery-ui.min.js"></script>
	<script src="DatePickerLibraries/jquery-ui-timepicker-addon.min.js"></script>
	<link href="css/psos.css" rel="stylesheet" type="text/css">
	<link href="css/login.css" rel="stylesheet" type="text/css">
	<link href="DatePickerLibraries/jquery-ui.min.css" rel="stylesheet" type="text/css">
	<link href="DatePickerLibraries/jquery-ui-timepicker-addon.min.css" rel="stylesheet" type="text/css">
	<script>
	$(function() {
		var dateFormat = "dd/mm/yy";
		from = $( "#fromDate" ).datepicker({
			dateFormat: "dd/mm/yy"
		})
		.change(function() {
      		to.datepicker( "option", "minDate", getDate( this ) );
    	});
    	
		to = $( "#endDate" ).datepicker({
			dateFormat: "dd/mm/yy"
		})
		.change(function() {
      		from.datepicker( "option", "maxDate", getDate( this ) );
    	});
    	
		function getDate( element ) {
	        var date;
	        try {
	          date = $.datepicker.parseDate( dateFormat, element.value );
	        } catch( error ) {
	          date = null;
	        }
	        return date;
	    }
	});
	</script>

<?php
	if (empty($_SESSION['un'])){
		header("Location: NotlogedIn.php");
		exit();
	}
	if($_SESSION['ini_array']['EnableManagerMissingReporting']){
		$WorkerNum = $_SESSION['WorkerNum'];
		$WorkerName = $_SESSION['WorkerName'];
		$WFixID = $_SESSION['WFixID'];
	}else{
		$WorkerNum = $_SESSION['UWorkerNum'];
		$WorkerName = $_SESSION['UWorkerName'];
		$WFixID = $_SESSION['UWFixID'];
	}
	$strMissing1 = BuildMissingList(" ",$dbh);
	$FromDate = date('d/m/Y');
	$EndDate = $FromDate;
	$dbh = null;
	$sth = null;
?>

</head>
<body CLASS="popup-background">
	<form name=calform action="misrep-w1.php" method="post" enctype="multipart/form-data">
		<table CLASS=iniFile dir=rtl>
			<tr>
				<td style="vertical-align:top;">
					<br>
					<div>
						<div dir=rtl class="fill2"><h2>קליטת היעדרות עבור&nbsp;<?=$WorkerName?>&nbsp;<?=$WorkerNum?>&nbsp;</h2></div>
					</div>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:top;">
					<table style="text-align:right; padding:2; border:0;">
						<tr>
							<td style="text-align: right;">סוג היעדרות</td>
							<td style="width:100px; text-align: right;">
								<select name="strMissing1" dir="rtl">
									<?=$strMissing1?>
								</select>
							</td>
						</tr>
						<tr>
							<td>מתאריך:</td>
							<td>
								<input type="text" id="fromDate" name="fromDate" size="30" value="<?=$FromDate?>" required>
							</td>
						</tr>
						<tr>
							<td>עד תאריך:</td>
							<td>
								<input type="text" id="endDate" name="endDate" size="30" required>
							</td>
						</tr>
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
								<input type="HIDDEN" name="WFixID" value="<?=$WFixID?>">
								<input type="HIDDEN" name="WorkerName" value="<?=$WorkerName?>">
								<input type="HIDDEN" name="WorkerNum" value="<?=$WorkerNum?>">
							</td>
						</tr>
					</table>	
				</td>
			</tr>
		</table>
	</form>
</body>
</html>

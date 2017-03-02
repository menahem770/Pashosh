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
		$.datepicker.setDefaults({ beforeShow: function (i) { if ($(i).attr('readonly')) { return false; } } });
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
	$FullDate = changeFormat("Y/m/d",$_GET['FullDate'],"d/m/Y");
	$WeekStatus = strtoupper($_GET['WeekS']);

	$sql= "SELECT * FROM SelectWorkerPresentWithMadanVer03 WHERE PresentID = " . $PresentID;
	echo "<!-- " . $sql . " -->" . $vbCrLf;
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	if (empty($result)){
		header('Location: fulnew-w.php?PresentID="0"&FullDate="<?=$FullDate?>"&WorkerNum="<?=$WorkerNum?>"&WeekS="<?=$WeekStatus?>"');
		exit();
	}
	if (empty($result['StartDate'])){
		$FromDate = $FullDate;
	}else{
		$FromDate = $result['StartDate'];
	}
	if (!checkdate(getMonth("d/m/Y",$FromDate),getDay("d/m/Y",$FromDate),getYear("d/m/Y",$FromDate))){
		echo "תאריך לא מוגדר כנדרש, אנא פנה למלר''ם";
	}
	$FromTime = $result['StartTime'];
	$EndDate = $result['EndDate'];
	$EndTime = $result['EndTime'];
	$OriginalPresentID = $result['OriginalPresentID'];
	$OriginalTransacionId = $result['OriginalTransacionId'];
	$DataSourceEnter = $result['DataSourceEnter'];
	$DataSourceExit = $result['DataSourceExit'];
	$ManualUpdatesNumber = $result['ManualUpdatesNumber'];
	$tamhir = $result['IncldInTmhir'];
	$madan = $result['IncldInMaden'];
	if ($tamhir == true or $madan == true){
		$strDprt = BuildDepSList($result['TDprtmnt'],$dbh);
	}
	if ($tamhir == true){
		$strJobs = BuildJobSList($result['TJob'],$dbh);
	}

	$PresentCd = trim($result['PresentCd']);
	$strSugeiNochechut = BuildSugeiNochechutList($PresentCd,$dbh);

	$ExistingCurrFile = $result['DocumentFileFullPatName'];
	if(!empty($ExistingCurrFile)){
		$ExistingCurrFile = "../".$ExistingCurrFile; //files in DB are regisered as if PHP placed Directly in wwwroot. this is a correction to the path.
	}

	$thisDate = changeFormat("d/m/Y",$FullDate,"m/Y");
	$lastMonth = date("m/Y",strtotime('first day of previous month')); //for the following condition
	
	$EnableEnter = "";
	$EnableExit = "";
	$EnableUpdate = "";
	$dis = "";
	$required = "required";
	
	if ($result['WeekStatus'] != "O" OR
		(($thisDate == date("m/Y")) AND ($ManualUpdatesNumber >= $_SESSION['NumberOfManualChangesIOL'])) OR
		(($thisDate == $lastMonth) AND ($result['ManualUpdatesNumberPM'] >= $_SESSION['NumberOfManualChangesIOL'])))
			{ $EnableUpdate = " DISABLED "; }
	if ((!$_SESSION['ini_array']['EnableManagerReporting']) AND ($_SESSION['UWFixID'] != $_SESSION['WFixID']))
			{ $EnableUpdate = " DISABLED "; }
	if((!$_SESSION['ini_array']['EnableClockTransEditing']) AND 
	  	((substr($DataSourceEnter,0,5) == "SB100") OR 
  		(substr($DataSourceEnter,0,5) == "Synel") OR 
	  	(substr($DataSourceEnter,0,9) == "Manually,") OR 
	  	($EnableUpdate == " DISABLED ")))
			{ $EnableEnter = " DISABLED "; }
	if((!$_SESSION['ini_array']['EnableClockTransEditing']) AND
		((substr($DataSourceExit,0,5) == "SB100") OR
		(substr($DataSourceExit,0,5) == "Synel") OR
	  	(substr($DataSourceExit,0,9) == "Manually,") OR
	  	($EnableUpdate == " DISABLED ")))
			{ $EnableExit = " DISABLED "; }

	if ($PresentID == 0 OR (empty($result['StartDate']) AND empty($result['EndDate']))){
		$dis = " DISABLED ";
	}

	if($PresentCd == "חופשה"){
		$required = "";
	}
	$dbh = null;
	$sth = null;

?>
</head>
<body CLASS="popup-background">
	<form name=calform action="fulrep-w1.php" method="post" enctype="multipart/form-data" onsubmit="return Form_Validator(this)">
		<table CLASS=iniFile dir=rtl>
			<tr>
				<td style="vertical-align:top;">
					<br>
					<div>
						  <div dir=rtl class="fill2"><h2>עדכון דיווח מלא ל<?=$WorkerName?>&nbsp;<?=$WorkerNum?>&nbsp;</h2></div>
					</div>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:top;">
					<table style="text-align:right; padding:2; border:0;">
						<tr>
							<td style="text-align: right;">סוג נוכחות</td>
							<td style="width:100px; text-align: right;">
								<select name="strSugeiNochechutDtl" dir="rtl" <?=$EnableUpdate?>>
									<?=$strSugeiNochechut?>
								</select>
							</td>
						</tr>
						<tr>
							<td>תאריך כניסה:</td>
							<td>
								<input type="text" name="fromDate" ID="fromDate" size="30" value="<?=$FromDate?>" disabled >
								<input type="HIDDEN" name="fromDate1" value="<?=$FromDate?>">
							</td>
						</tr>
						<tr>
							<td>שעת כניסה:</td>
							<td>
								<input type="text" name="fromTime" ID="fromTime" size="30" value="<?=$FromTime?>" <?=$EnableEnter." ".$required?>>
								<input type="HIDDEN" name="fromTime1" value="<?=$FromTime?>"> <!-- in case of disabled field, for the value to be posted-->
							</td>
						</tr>
						<tr>
							<td>תאריך יציאה:</td>
							<td>
								<input type="text" id="endDate" name="endDate" size="30" value="<?=$EndDate?>"<?=$EnableExit?>>
								<input type="HIDDEN" name="endDate1" value="<?=$EndDate?>"> <!-- in case of disabled field, for the value to be posted-->
							</td>
						</tr>
						<tr>
							<td>שעת יציאה:</td>
							<td>
								<input type="text" name="endTime" ID="endTime" size="30" value="<?=$EndTime?>"<?=$EnableExit?>>
								<input type="HIDDEN" name="endTime1" value="<?=$EndTime?>"> <!-- in case of disabled field, for the value to be posted-->
							</td>
						</tr>
						<?php if ($tamhir == true or $madan == true):?>
							<tr>
								<td style="text-align: right;">פרטי מחלקה / פרוייקט</td>
								<td style="text-align: right;">
									<select name="DepDtl" dir="rtl" <?=$EnableUpdate?>>
										<?=$strDprt?>
									</select>
								</td>
							</tr>
						<?php endif?>
						<?php if ($tamhir == true):?>
							<tr>
								<td style="text-align: right;">פרטי ג'וב / משימה</td>
								<td style="text-align: right;">
									<select name="JobDtl" dir="rtl" <?=$EnableUpdate?>>
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
						<?php if(!empty($ExistingCurrFile)):?>
							<tr>
								<td>מסמך קיים:</td>
								<td>
									<a href="<?=$ExistingCurrFile?>">מסמך</a>
								</td>
							</tr>
						<?php endif?>
						<tr>
							<td colspan=2 style="text-align: center;">
								<input type="Submit" name="delete" value="מחיקת הנוכחות" style="width: auto;" <?=$dis?>>
								<input type="Submit" name="submit" value="אישור" style="width: auto;">
								<input type="Submit" name="cancel" value="חזרה" style="width: auto;" OnClick="window.close();return false;">
								<input type="HIDDEN" name="PresentID" value="<?=$PresentID?>">
								<input type="HIDDEN" name="OriginalPresentID" value="<?=$OriginalPresentID?>">
								<input type="HIDDEN" name="OriginalTransacionId" value="<?=$OriginalTransacionId?>">
								<input type="HIDDEN" name="DataSourceEnter" value="<?=$DataSourceEnter?>">
								<input type="HIDDEN" name="DataSourceExit" value="<?=$DataSourceExit?>">
								<input type="HIDDEN" name="ManualUpdatesNumber" value="<?=$ManualUpdatesNumber?>">
								<input type="HIDDEN" name="ExistingCurrFile" value="<?=$ExistingCurrFile?>">
								<input type="HIDDEN" name="FormType" value="U">
								<input type="HIDDEN" name="PresNumber" value="<?=$result['PresNumber']?>">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
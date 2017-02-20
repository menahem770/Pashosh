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
	$PresentID = $_POST['PresentID'];
	$OriginalPresentID = $_POST['OriginalPresentID'];
	$OriginalTransacionId = $_POST['OriginalTransacionId'];
	$ExistingCurrFile = "";
	$PresNumber = "";
	$WorkerNum = $_SESSION['WorkerNum'];
	$WorkerName = $_SESSION['WorkerName'];
	$WFixID = $_SESSION['WFixID'];
	$accDate = date("d/m/Y H:i");
	$fromDate = $_POST['fromDate1'];
	$formType = $_POST['FormType'];
	$fromTime = "";
	$endDate = "";
	$endTime = "";
	if (!empty($_POST['fromTime'])){
		$fromTime = $_POST['fromTime'];
	}else if(!empty($_POST['fromTime1'])){ //in case the field in previous page is disabled
		$fromTime = $_POST['fromTime1'];
	}
	if (!empty($_POST['endDate'])){
		$endDate = $_POST['endDate'];
	}else if(!empty($_POST['endDate1'])){ //in case the field in previous page is disabled
		$endDate = $_POST['endDate1'];
	}
	if (!empty($_POST['endTime'])){
		$endTime = $_POST['endTime'];
	}else if(!empty($_POST['endTime1'])){ //in case the field in previous page is disabled
		$endTime = $_POST['endTime1'];
	}
	
	// checking for 'madan' requirements - not completed
	$fitMadanRequirements = true;
	$fromDateParam = DateTime::createFromFormat('d/m/Y H:i', $fromDate.$fromTime); //DateTime object
	$endDateParam = DateTime::createFromFormat('d/m/Y H:i', $endDate.$endTime); //DateTime object
	if($_SESSION['IncldInMadan']){
		$origAccDate = new DateTime(); 
		$isTodaySunday = $origAccDate->format('%w') == 0; // 0 is sunday
		$diffFromDate = date_diff($fromDateParam, $origAccDate)->format('%d%r'); //diff from origAccDate to fromDate
		$diffEndDate = date_diff($endDateParam, $origAccDate)->format('%d%r'); //diff from origAccDate to endDate
	
		if((!$isTodaySunday AND ($diffFromDate > 1 OR $diffEndDate > 1)) OR ($isTodaySunday AND ($diffFromDate > 3 OR $diffEndDate > 3))){
			$fitMadanRequirements = false;
		}
		// else if($difffromdate < 0 or $diffenddate < 0){		
		// }
	}
	
	$SugeiNochechut = $_POST['strSugeiNochechutDtl'];
	$fActionCode = GetActionCode($SugeiNochechut,$dbh);

	if (isset($_POST['JobDtl'])){
		$job = $_POST['JobDtl'];
	}
	if (isset($_POST['DepDtl'])){
		$dep = $_POST['DepDtl'];
	}
	if (isset($_POST['PresNumber'])){ // if $PresNumber stays empty after this, then this pres is a new present. else it's an update to existing pres
		$PresNumber = $_POST['PresNumber'];
	}
	if (isset($_POST['ExistingCurrFile'])){
		$ExistingCurrFile = $_POST['ExistingCurrFile'];
	}
	if ($PresentID == 0){
		$OperationType = "פתיחה";
		$OperTitle = "לקלוט";
	}else{
		if (isset($_POST['submit'])){
			$OperationType = "עדכון";
			$OperTitle = "לעדכן";
		}else{
			$OperationType = "Deleted";
			$OperTitle = "למחוק";
			$formType = "D";
		}
	}
	$ErrorTitle = "";
	if ($fActionCode != "העדרות"){
		if ($fromDate == "" or $fromTime == ""){
			$ErrorTitle = 1;
		}
		if (isset($_POST['endDate'])){
			$presentLengthInHoures = date_diff($fromDateParam, $endDateParam)->format('%h%r');
			if($_SESSION['attendMaxLength'] != "&" AND $presentLengthInHoures >= $_SESSION['attendMaxLength']){
				$ErrorTitle = 2;
			}
			else if($fromDateParam > $endDateParam){
				$ErrorTitle = 4;
			}
		}
	}else{
		$fromTime = "00:00";
		$endDate = $_POST['fromDate1'];
		$endTime = "00:00";
	}

	$formatDate = $fromDateParam->format('Y/m/d');
	// this sql statement is equal to: "SELECT * FROM WorkPrsnt WHERE WFixID = ".$WFixID." AND FullDate = #".$shortdate."#", but keeps from dates converting problems
	//$sql = "SELECT * FROM WorkPrsnt WHERE WFixID = ".$WFixID." AND (DateDiff('d',#".$formatDate."#,[workPrsnt]![FullDate])=0)";
	$sql = "SELECT WorkPrsnt.* FROM WorkPrsnt WHERE (((WorkPrsnt.WFixID)= ".$WFixID.") AND ((Format([WorkPrsnt]![FullDate],'yyyy/mm/dd'))= '".$formatDate."')) ORDER BY WorkPrsnt.PresNumber";
	echo $vbCrLf . "<!--" . $sql . "-->" . $vbCrLf;
	$sth = $dbh->query($sql);
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
	$NewPresNumber = LegalPresence($result, $formType, $PresNumber, $fromDate, $fromTime, $endDate, $endTime);
	if(!is_numeric($NewPresNumber) AND $NewPresNumber == "f"){
		$ErrorTitle = 3;
	}

	if ($ErrorTitle == 1):
?>
		<script>
			Mssg = "הכנס מועד כניסה";
		</script>
<?php endif;
	if ($ErrorTitle == 2):?>
		<script>
			Mssg = "נוכחות ארוכה מהמותר!";
		</script>
<?php endif;
	if ($ErrorTitle == 3):?>
		<script>
			Mssg = "קיימת התנגשות בין נוכחות זו לנוכחויות קודמות שהתקבלו!";
		</script>
<?php endif;
	if ($ErrorTitle == 4):?>
		<script>
			Mssg = "תאריך ושעת כניסה אינם קודמים לתאריך ושעת יציאה!";
		</script>
<?php endif;


	//moving file to a temp folder
	$CurrFileName = "לא שוייך מסמך לנוכחות זו!";
	$tempTargetFile = "";
	if (isset($_FILES["fileToUpload"]) AND $_FILES["fileToUpload"]["name"] != ""){
		$CurrFileName = basename($ExistingCurrFile);
		$uploadOk = 1;
		$CurrFileName = str_replace(array(" ","[","]","'","+","!"),"_",basename($_FILES["fileToUpload"]["name"]));
		$tempTargetDir = $_SESSION['ini_array']['UserFolder']."temp/";
		$tempTargetFile = $tempTargetDir.$CurrFileName;
		$FileType = strtolower(pathinfo($tempTargetFile,PATHINFO_EXTENSION));
		// creating temp folder
		if (!is_dir($tempTargetDir) and !mkdir($tempTargetDir,null,true)){
			echo 'כשל ביצירת תיקייה';
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 2000000) {
			echo "המסמך שהועלה גדול מידי!";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "gif" && $FileType != "pdf") {
			echo "ניתן להעלות מסמך בפורמט תמונה או PDF בלבד";
			$uploadOk = 0;
		}
		if ($uploadOk == 0) {
			echo "מצטערים, המסמך לא הועלה!";
			$tempTargetFile = "";
			// if everything is ok, try to upload file
		} else {
			if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $tempTargetFile)){
				$tempTargetFile = "";
				echo "מצטערים, קרתה שגיאה שאינה מאפשרת העלאת המסמך!";
			}
		}
	}
	$dbh = null;
	$sth = null;
?>
</head>
<body CLASS="popup-background">
	<form name=calform action="fulrep-wsql.php" method="post">
		<table CLASS=iniFile dir=rtl>
			<tr>
				<td style="vertical-align:top;">
					<br>
					<div>
						  <div dir=rtl class="fill2"><h2>&nbsp;האם <?=$OperTitle?> דיווח ל<?=$WorkerName?>&nbsp;<?=$WorkerNum?> ?&nbsp;</h2></div>
					</div>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:top;">
					<table style="text-align:right; padding:2; border:0; margin:0px auto;">
						<tr>
							<td>סוג נוכחות</td>
							<td>
								<input type="HIDDEN" name="strSugeiNochechutDtl" value="<?=$SugeiNochechut?>"><?=$SugeiNochechut?>
							</td>
						</tr>
						<tr>
							<td>מתאריך:</td>
							<td>
								<input type="HIDDEN" size=10 name="fromDate" ID="fromDate" value="<?=$fromDate?>"><?=$fromDate?>
							</td>
						</tr>
						<tr>
							<td>משעה:</td>
							<td>
								<input type="HIDDEN" size=10 name="fromTime" ID="fromTime" value="<?=$fromTime?>"><?=$fromTime?>
							</td>
						</tr>
						<tr>
							<td>עד תאריך:</td>
							<td>
								<input type="HIDDEN" size=10 name="endDate" ID="endDate" value="<?=$endDate?>"><?=$endDate?>
							</td>
						</tr>
						<tr>
							<td>עד שעה:</td>
							<td>
								<input type="HIDDEN" size=10 name="endTime" ID="endTime" value="<?=$endTime?>"><?=$endTime?>
							</td>
						</tr>
						<?php if(isset($dep)):?>
							<tr>
								<td>פרטי מחלקה / פרוייקט</td>
								<td>
									<input type="HIDDEN" name="DepDtl" value="<?=$dep?>"><?=$dep?>
								</td>
							</tr>
						<?php endif?>
						<?php if(isset($job)):?>
							<tr>
								<td>פרטי ג'וב / משימה</td>
								<td>
									<input type="HIDDEN" name="JobDtl" value="<?=$job?>"><?=$job?>
								</td>
							</tr>
						<?php endif?>
						<tr>
							<td>מסמך מצורף:</td>
							<td>
								<input type="HIDDEN" size=50 name="CurrFileName" id="CurrFileName" value="<?=$CurrFileName?>"><?=$CurrFileName?>
							</td>
						</tr>
						<tr>
							<td>זמן קליטה</td>
							<td>
								<input type="HIDDEN" name="accDate" size=50 value="<?=$accDate?>"><?=$accDate?>
								<input type="HIDDEN" name="WFixID" value="<?=$WFixID?>">
								<input type="HIDDEN" name="WorkerNum" value="<?=$WorkerNum?>">
								<input type="HIDDEN" name="PresentID" value="<?=$PresentID?>">
								<input type="HIDDEN" name="OriginalPresentID" value="<?=$OriginalPresentID?>">
								<input type="HIDDEN" name="OriginalTransacionId" value="<?=$OriginalTransacionId?>">
								<input type="HIDDEN" name="OperationType" value="<?=$OperationType?>">
								<input type="HIDDEN" name="ActionCode" value="<?=$fActionCode?>">
								<input type="HIDDEN" name="tempTargetFile" value="<?=$tempTargetFile?>">
								<input type="HIDDEN" name="ExistingCurrFile" value="<?=$ExistingCurrFile?>">
								<input type="HIDDEN" name="FormType" value="<?=$formType?>">
								<input type="HIDDEN" name="PresNumber" value="<?=$PresNumber?>">
								<input type="HIDDEN" name="NewPresNumber" value="<?=$NewPresNumber?>">
							</td>
						</tr>
						<?php if(!$fitMadanRequirements):?>
							<tr>
								<td colspan=2>שים לב! ע"פ הוראות המדען יש לימנע מהכנסת או עדכון נוכחות שקודמת ליום עבודה הקודם</td>
							</tr>
						<?php endif?>
						<tr>
							<td colspan = 2 style="text-align: center;">
								<?php if($ErrorTitle != 0): ?>
									<input type="button" name="submit" value="אישור" style="width: 100px;" OnClick="alert(Mssg);history.back();return false;">
								<?php else: ?>
									<input type="submit" name="submit" value="אישור" style="width: 100px;">
								<?php endif; ?>
								<input type="Submit" name="cancel" value="חזרה" style="width: 100px;" OnClick="history.back();return false;">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
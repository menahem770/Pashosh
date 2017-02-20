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
	<script>
		dis1Mssg = "לא ניתן להשלים את הפעולה! \n במהלך התקופה שנבחרה ישנו שבוע אחד או יותר הסגור לשינויים.";
		dis2Mssg = "לא ניתן להשלים את הפעולה! \n במהלך התקופה שנבחרה קיים יום אחד או יותר שבו הוגדרה נוכחות.";
	</script>
	<link href="css/psos.css" rel="stylesheet" type="text/css">
	<link href="css/login.css" rel="stylesheet" type="text/css">
<?php
	
	$WorkerNum = $_POST['WorkerNum'];
	$WorkerName = $_POST['WorkerName'];
	$WFixID = $_POST['WFixID'];
	$MissingType = $_POST['strMissing1'];
	$OperationType = "פתיחה";
	$CurrFile = "";
	$FromDate = $_POST['fromDate'];
	$EndDate = $_POST['endDate'];
	$accDate = date('d/m/Y H:i:s');
	
	$Dis1 = AllWeekStatus($WFixID,$FromDate,$EndDate,$dbh);
	$Dis2 = FindPresenceInTimePeriod($WFixID,$FromDate,$EndDate,$dbh);

	//moving file to a temp folder
	$CurrFileName = "";
	$tempTargetFile = "";
	if (isset($_FILES["fileToUpload"]) and $_FILES["fileToUpload"]["name"] != ""){
		$uploadOk = 1;
		$CurrFileName = str_replace(array(" ","[","]","'","+","!"),"_",basename($_FILES["fileToUpload"]["name"]));
		$tempTargetDir = $_SESSION['ini_array']['UserFolder']."temp/";
		$tempTargetFile = $tempTargetDir.$CurrFileName;
		$FileType = strtolower(pathinfo($tempTargetFile,PATHINFO_EXTENSION));
		// creating temp folder
		if (!is_dir($tempTargetDir) and !mkdir($tempTargetDir,null,true)){
			echo ('כשל ביצירת תיקייה.');
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 2000000) {
			echo "המסמך שהועלה גדול מידי!";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "gif" && $FileType != "pdf") {
			echo "ניתן להעלות רק מסמכים בפורמט תמונה או PDF בלבד!";
			$uploadOk = 0;
		}
		if ($uploadOk == 0) {
			echo "מצטערים, המסמך לא הועלה!";
			$tempTargetFile = "";
			// if everything is ok, try to upload file
		} else {
			if(!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $tempTargetFile)){
				$tempTargetFile = "";
				echo "קרתה שגיאה שלא מאפשרת העלאת המסמך";
			}/* else{
				chmod($tempTargetFile, 0755);// read, write and execute permissions for owner, read and execute for others
			} */
		}
	}
	$dbh = null;
	$sth = null;
?>

</head>
<body CLASS="popup-background">
	<form name=calform action="misrep-wsql.php" method="post">
		<table CLASS=iniFile>
			<tr>
				<td style="vertical-align:top;">
					<br>
					<div>
						<div dir=rtl class="fill2" style="text-align:center;"><h2>&nbsp;האם לקלוט דיווח ל<?=$WorkerName?>&nbsp;<?=$WorkerNum?> ?&nbsp;</h2></div>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<table dir=rtl style="text-align:right; margin:0px auto; padding:2; border:0;">
						<tr>
							<td>סוג העדרות</td>
							<td>
								<input type="HIDDEN" name="strMissingDtl" value="<?=$MissingType?>"><?=$MissingType?>
							</td>
						</tr>
						<tr>
							<td>מתאריך:</td>
							<td>
								<input type="HIDDEN" name="FromDate" ID="FromDate" value="<?=$FromDate?>"><?=$FromDate?>
							</td>
						</tr>
						<tr>
							<td>עד תאריך:</td>
							<td>
								<input type="HIDDEN" name="EndDate" ID="EndDate" value="<?=$EndDate?>"><?=$EndDate?>
							</td>
						</tr>
						<tr>
							<td>מסמך מצורף:</td>
							<td>
								<input type="HIDDEN" name="CurrFileName" id="CurrFileName" value="<?=$CurrFileName?>"><?=$CurrFileName?>
							</td>
						</tr>
						<tr>
							<td>זמן קליטה</td>
							<td>
								<input type="HIDDEN" name="accDate" value="<?=$accDate?>"><?=$accDate?>
							</td>
						</tr>
						<tr>
							<td colspan = 2>
								<?php if(strlen($Dis1) > 0): ?>
									<input type="button" name="submit" value="אישור" style="width: 100px;" OnClick="alert(dis1Mssg);history.back();return false">
								<?php elseif(strlen($Dis2) > 0): ?>
									<input type="button" name="submit" value="אישור" style="width: 100px;" OnClick="alert(dis2Mssg);history.back();return false">
								<?php else: ?>
									<input type="Submit" name="submit" value="אישור" style="width: 100px;">
								<?php endif; ?>
								<input type="Submit" name="cancel" value="חזרה" style="width: 100px;" OnClick="history.back();return false;">
								<input type="HIDDEN" name="WFixID" value="<?=$WFixID?>">
								<input type="HIDDEN" name="OperationType" value="<?=$OperationType?>">
								<input type="HIDDEN" name="tempTargetFile" value="<?=$tempTargetFile?>">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
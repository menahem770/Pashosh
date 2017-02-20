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
	<?php
		$fCardNumber = $_SESSION['CardNumber'];
		$fWFixID = $_POST['WFixID'];
		$fEnterDate = $_POST['fromDate'];
		$fEnterTime = $_POST['fromTime'];
		$fExitDate = $_POST['endDate'];
		$fExitTime = $_POST['endTime'];
		$formatEnterDate = changeFormat("d/m/Y",$fEnterDate,"Y/m/d");
		$formatExitDate = changeFormat("d/m/Y",$fExitDate,"Y/m/d");
		$fPresNumber = $_POST['PresNumber'];
		$fNewPresNumber = $_POST['NewPresNumber'];
		$SugeiNochechut = $_POST['strSugeiNochechutDtl'];
		$fActionCode = $_POST['ActionCode'];
		$fAction = GetSugPeula($SugeiNochechut,$dbh);
		$fPresentCd = $_POST['strSugeiNochechutDtl'];
		$fOperationType = $_POST['OperationType'];
		$fFullEnterDate = $fEnterDate . " " . $fEnterTime;
		$fFullExitDate = $fExitDate . " " . $fExitTime;
		$fInputOrder = " ";
		$fPresentID = $_POST['PresentID'];
		$accDate = $_POST['accDate'];
		$fRegistrationDate = changeFormat("d/m/Y H:i:s",$accDate,"Y/m/d H:i:s");
		$fFullDateForSort = $formatEnterDate." ".$fEnterTime;
		$formType = $_POST['FormType'];
		$fYear = getYear("d/m/Y",$fEnterDate);
		$fMonth = getMonth("d/m/Y",$fEnterDate);
		$fDay = getDay("d/m/Y",$fEnterDate);
		$enter = ' :כ ';
		$exit = ' :י ';
		$fTjob = "";
		$fTDprtmnt = "";
		if(isset($_POST['DepDtl'])){ // if IncldInMadan OR IncldInTamhir
			$fTDprtmnt = $_POST['DepDtl'];
			if($fTDprtmnt == "" AND !$_SESSION['IncldInMadan']){ // if IncldInTamhir and NOT IncldInMadan
				$fTDprtmnt = $_SESSION['DefaultDep'];
			}
		}
		if(isset($_POST['JobDtl'])){ // if IncldInTamhir
			$fTjob = $_POST['JobDtl'];
			if($fTjob == ""){
				$fTjob = $_SESSION['DefaultJob'];
			}
		}

		$resultCount = 0;

		if(is_numeric($fNewPresNumber)){
			//$sql = "SELECT * FROM WorkPrsnt WHERE WFixID=" . $fWFixID . " AND (DateDiff('d',#".$formatEnterDate."#,[workPrsnt]![FullDate])=0)";
			$sql = "SELECT WorkPrsnt.* FROM WorkPrsnt WHERE (((WorkPrsnt.WFixID)= ".$fWFixID.") AND ((Format([WorkPrsnt]![FullDate],'yyyy/mm/dd'))= '".$formatEnterDate."')) ORDER BY WorkPrsnt.PresNumber";
			$sth = $dbh->query($sql);
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			$fPresNumber = AdvancePresNumber($result,$fNewPresNumber,$formType,$dbh);
			$resultCount = count($result);
		}
		$fDayOfWeek = GetHebrewDayInWeek($formatEnterDate);

		$fOriginalPresentID = $_POST['OriginalPresentID'];
		$fOriginalTransacionId = $_POST['OriginalTransacionId'];
		$fClockType = "Internet";
		if ($_SESSION['companyIncldInTmhirMadan'] AND ($_SESSION['IncldInMadan'] OR $_SESSION['IncldInTmhir'])){
			$fActionType = "Costing";
		}else{
			$fActionType = "Regular";
		}

		// file upload
		$target_file = "";
		$tempTargetFile = $_POST["tempTargetFile"];
		$fDocumentFileFullPathName = $_POST['ExistingCurrFile'];
		if ($tempTargetFile != ""){
			$uploadOk = 1;
			$target_dir = $_SESSION['ini_array']['UserFolder'].$_SESSION['WorkerNum']."/".changeFormat('d/m/Y',$fEnterDate,'Ymd');
			// creating folder
			if (!is_dir($target_dir) AND !mkdir($target_dir,null,true)){
				unlink($tempTargetFile);
				echo 'Failed to create folder.';
				$uploadOk = 0;
			}
			$target_file = $target_dir ."/". $_POST['CurrFileName'];
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				unlink($tempTargetFile);
				echo "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file
			} else {
				if (rename($tempTargetFile, $target_file)) {
					echo chmod($target_file, 0755);// read, write and execute permissions for owner, read and execute for others
					$fDocumentFileFullPathName = $target_file;
				} else {
					echo "Sorry, there was an error uploading your file.";
					unlink($tempTargetFile);
					$uploadOk = 0;
				}
			}
		}

		// send email notification
		if ($_SESSION['ini_array']['sendMail'] AND strlen($_SESSION['DepMail']) > 0){
			$to = $_SESSION['DepMail'];
			$message = "התקבל עדכון נוכחות עבור: ".$_SESSION['WorkerName']."\nהדיווח בוצע על ידי: ".$_SESSION['UWorkerName']."\nהנוכחות המעודכנת הינה מסוג: ".$fActionCode."\nמועד כניסה ".$fFullEnterDate." מועד יציאה ".$fFullExitDate;
			if (strlen($fDocumentFileFullPathName) > 0 AND $uploadOk != 0){
				$message = $message."\nהמסמך שצורף לנוכחות, צורף גם להודעה זו.";
			}
			include 'sendEmail.php';
		}
		$fDocumentFileFullPathName = str_ireplace("../","",$fDocumentFileFullPathName);//to register the right path to the attached file in the DB, as if PHP is placed directly in wwwroot.

		$sql = "INSERT into ClockTransNew (";
		$sql = $sql . "CardNumber,";
		$sql = $sql . "WFixID,";
		$sql = $sql . "PresentID,";
		$sql = $sql . "RegistrationDate,";
		$sql = $sql . "IpAddress,";
		$sql = $sql . "UserCode,";
		$sql = $sql . "FullEnterDate,";
		$sql = $sql . "FullExitDate,";
		$sql = $sql . "FullDateForSort,";
		$sql = $sql . "ActionCode,";
		$sql = $sql . "Action,";
		$sql = $sql . "ActionType,";
		$sql = $sql . "OperationType,";
		$sql = $sql . "EnterDate,";
		$sql = $sql . "EnterTime,";
		$sql = $sql . "ExitDate,";
		$sql = $sql . "ExitTime,";
		$sql = $sql . "PresentCd,";
		$sql = $sql . "TDprtmnt,";
		$sql = $sql . "TJob,";

		$sql = $sql . "PassPhase1,PassPhase2,";
		$sql = $sql . "ClockName,";
		$sql = $sql . "CompanyNum,";

		$sql = $sql . "ClockType,";
		$sql = $sql . "FullDate,";
		$sql = $sql . "SourceForm,";
		$sql = $sql . "OriginalPresentID,";
		$sql = $sql . "DocumentFileFullPatName,";
		$sql = $sql . "OriginalTransacionId)";

		$sql = $sql . "values (";

		$sql = $sql . "'" . $fCardNumber . "'" . ",";
		$sql = $sql . "" . $fWFixID . "" . ",";
		$sql = $sql . "" . $fPresentID . "" . ",";
		$sql = $sql . LeftDateSep  . $fRegistrationDate . sRightDateSep . ",";
		$sql = $sql . "'" . $_SESSION['UserIp'] . "'" . ",";
		$sql = $sql . "'" . $_SESSION['un'] . "'" . ",";
		$sql = $sql . LeftDateSep . $fFullEnterDate . RightDateSep . ",";
		$sql = $sql . LeftDateSep . $fFullExitDate . RightDateSep . ",";
		$sql = $sql . LeftDateSep . $fFullDateForSort . RightDateSep . ",";
		$sql = $sql . "'" . $fActionCode . "'" . ",";
		$sql = $sql . "'" . $fAction . "'" . ",";
		$sql = $sql . "'" . $fActionType . "'" . ",";
		$sql = $sql . "'" . $fOperationType . "'" . ",";
		$sql = $sql . LeftDateSep . $fEnterDate . RightDateSep . ",";
		$sql = $sql . LeftTimeSep . $fEnterTime . RightTimeSep . ",";
		$sql = $sql . LeftDateSep . $fExitDate . RightDateSep . ",";
		$sql = $sql . LeftTimeSep . $fExitTime . RightTimeSep . ",";
		$sql = $sql . "'" . $fPresentCd . "'" . ",";
		$sql = $sql . "'" . $fTDprtmnt . "'" . ",";
		$sql = $sql . "'" . $fTjob . "'" . ",";

		$sql = $sql . "'" . True . "',";
		$sql = $sql . "'" . True . "',";
		$sql = $sql . "'" . "IOLVER03" . "'" . ",";
		$sql = $sql . "'" . "001" . "'" . ",";

		$sql = $sql . "'" . $fClockType . "'" . ",";
		$sql = $sql . "'" . $formatEnterDate . "'" . ",";
		$sql = $sql . "'" . "Online" . "'" . ",";
		$sql = $sql . $fOriginalPresentID . ",";
		$sql = $sql . "'"  . $fDocumentFileFullPathName . "'" .  ",";
		$sql = $sql . $fOriginalTransacionId . ")";

		echo $vbCrLf . "<!--" . $sql . "-->" . $vbCrLf;
		$sth = $dbh->query($sql);

		if($formType == "U"){ //update

			$sql = "UPDATE WorkPrsnt SET ";
			$sql = $sql . "StartDate = ". Iif(empty($fEnterDate),'Null',LeftDateSep . $fEnterDate. RightDateSep)  .", ";
			$sql = $sql . "StartTime = ". Iif(empty($fEnterTime),'Null',LeftDateSep .$fEnterTime. RightDateSep)  .", ";
			$sql = $sql . "EndDate = ". Iif(empty($fExitDate),'Null',LeftDateSep .$fExitDate. RightDateSep)  .", ";
			$sql = $sql . "EndTime = ". Iif(empty($fExitTime),'Null',LeftDateSep .$fExitTime. RightDateSep)  .", ";
			$sql = $sql . "PresentCd = '" . $fPresentCd . "', ";
			$sql = $sql . "ChangePrsnt =  True, ";
			$sql = $sql . "TDprtmnt = " . Iif(empty($fTDprtmnt),'Null',"'" . $fTDprtmnt . "'")  . ",";
			$sql = $sql . "TJob = ". Iif(empty($fTjob),'Null',"'" . $fTjob . "'")  . ",";
			$sql = $sql . "DataSourceEnter = 'IOLVER03 " . $_SESSION['un'] . " " . $_SESSION['UserIp'] . "', ";
			$sql = $sql . "DataSourceExit = 'IOLVER03 " . $_SESSION['un'] . " " . $_SESSION['UserIp'] . "', ";
			//$sql = $sql . "RegistrationEnterDate = ". LeftDateSep  . $fRegistrationDate . sRightDateSep . ", ";
			//$sql = $sql . "RegistrationExitDate = ". LeftDateSep  . $fRegistrationDate . sRightDateSep . ", ";
			$sql = $sql . "DocumentFileFullPatName = '"  . $fDocumentFileFullPathName . "' ";
			$sql = $sql . "WHERE WFixID = " . $fWFixID . "  AND Year = '" . $fYear . "' AND Month = '" . $fMonth . "'  AND DayNumber = '" . $fDay . "' AND PresNumber = " . $fPresNumber . " ";

		}else if($formType == "N"){ // new present

			$sql = "INSERT into WorkPrsnt (";
			$sql = $sql . "WFixID,";
			$sql = $sql . "Year,";
			$sql = $sql . "Month,";
			$sql = $sql . "DayNumber,";
			$sql = $sql . "PresNumber,";
			$sql = $sql . "FullDate,";

			$sql = $sql . "DayOfWeek,";

			$sql = $sql . "StartDate,";
			$sql = $sql . "StartTime,";
			$sql = $sql . "EndDate,";
			$sql = $sql . "EndTime,";
			$sql = $sql . "PresentCd,";
			$sql = $sql . "ChangePrsnt,";
			$sql = $sql . "TDprtmnt,";
			$sql = $sql . "TJob,";
			$sql = $sql . "DataSourceEnter,";
			$sql = $sql . "DataSourceExit,";
			$sql = $sql . "RegistrationEnterDate,";
			$sql = $sql . "RegistrationExitDate,";
			$sql = $sql . "InternetOnlineRemark,";
			$sql = $sql . "DocumentFileFullPatName)";
			//
			$sql = $sql . "values (";
			//
			$sql = $sql . "" . $fWFixID . "" . ",";
			$sql = $sql . "'" . $fYear . "'" . ",";
			$sql = $sql . "'" . $fMonth . "'" . ",";
			$sql = $sql . "'" . $fDay . "'" . ",";
			$sql = $sql . "'" . $fPresNumber . "'" . ",";
			$sql = $sql . Iif(empty($formatEnterDate),'Null',LeftDateSep . $formatEnterDate. RightDateSep)  . ",";

			$sql = $sql . "'" . $fDayOfWeek . "'" . ",";

			$sql = $sql . Iif(empty($formatEnterDate),'Null',LeftDateSep . $formatEnterDate. RightDateSep)  . ",";
			$sql = $sql . Iif(empty($fEnterTime),'Null',LeftDateSep .$fEnterTime. RightDateSep)  . ",";
			$sql = $sql . Iif(empty($formatExitDate),'Null',LeftDateSep .$formatExitDate. RightDateSep)  . ",";
			$sql = $sql . Iif(empty($fExitTime),'Null',LeftDateSep .$fExitTime. RightDateSep)  . ",";
			$sql = $sql . "'" . $fPresentCd . "'" . ",";
			$sql = $sql . "True " . ",";
			$sql = $sql . Iif(empty($fTDprtmnt),'Null',"'" . $fTDprtmnt . "'")  . ",";
			$sql = $sql . Iif(empty($fTjob),'Null',"'" . $fTjob . "'")  . ",";
			$sql = $sql . "'" . "IOLVER03 " . $_SESSION['un'] . " " . $_SESSION['UserIp'] . "'" . ",";
			$sql = $sql . "'" . "IOLVER03 " . $_SESSION['un'] . " " . $_SESSION['UserIp'] . "'" . ",";
			$sql = $sql . LeftDateSep  . $fRegistrationDate . sRightDateSep . ",";
			$sql = $sql . LeftDateSep  . $fRegistrationDate . sRightDateSep . ",";
			$sql = $sql . "Null " . ",";//InternetOnlineRemark
			$sql = $sql . "'"  . $fDocumentFileFullPathName . "'" . ")";

		}else if($formType == "D"){ //deleting present

			if($resultCount == 1){
				//in case of single presente in current day
				$sql = "UPDATE WorkPrsnt SET ";
				$sql = $sql . "StartDate = Null, ";
				$sql = $sql . "StartTime = Null, ";
				$sql = $sql . "EndDate = Null, ";
				$sql = $sql . "EndTime = Null, ";
				$sql = $sql . "PresentCd = Null, ";
				$sql = $sql . "ChangePrsnt =  True, ";
				$sql = $sql . "TDprtmnt = Null, ";
				$sql = $sql . "TJob = Null, ";
				$sql = $sql . "DataSourceEnter = Null, ";
				$sql = $sql . "DataSourceExit = Null, ";
				$sql = $sql . "RegistrationEnterDate = Null, ";
				$sql = $sql . "RegistrationExitDate = Null, ";
				$sql = $sql . "DocumentFileFullPatName = Null ";
				$sql = $sql . "WHERE WFixID = " . $fWFixID . "  AND Year = '" . $fYear . "' AND Month = '" . $fMonth . "'  AND DayNumber = '" . $fDay . "' AND PresNumber = 1";
			}else{
				// more then 1 present in current day
				$sql = "DELETE WorkPrsnt.* ";
				$sql = $sql . "FROM WorkPrsnt ";
				$sql = $sql . "WHERE WFixID = " . $fWFixID . "  AND Year = '" . $fYear . "' AND Month = '" . $fMonth . "'  AND DayNumber = '" . $fDay . "' AND PresNumber = " . $fPresNumber . " ";
			}
		}
		
		echo $vbCrLf . "<!--" . $sql . "-->" . $vbCrLf;
		$sth = $dbh->query($sql);
		if(! $sth) {
			$date = date('Y-m-d H:i:s');
			$error = var_export($dbh->errorinfo(),true);
			file_put_contents('PDOErrors.txt', $date." ".$error , FILE_APPEND);
		}

		echo "<!--" . $tempTargetFile . "-->";
		echo "<!--" . $fDocumentFileFullPathName . "-->";
		$dbh = null;
		$sth = null;

	?>
	<script>
		window.opener.location.reload();
		window.close();
	</script>
</head>
</html>
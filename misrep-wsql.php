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
	<META charset="windows-1255">
	<META HTTP-EQUIV="expires" CONTENT="0">
	<META HTTP-EQUIV="pragma" CONTENT="no-cache">
	<script src="Funcs.js"></script>
	<?php
		$fCardNumber = $_SESSION['CardNumber'];
		$fWFixID = $_POST['WFixID'];
		$fEnterDate = $_POST['FromDate'];
		$fInputOrder = " ";
		$fPresentID = 0;
		$accDate = $_POST['accDate'];
		$fRegistrationDate = $accDate;
		$fExitDate = $_POST['EndDate'];
		$MissingType = $_POST['strMissingDtl'];
		$fActionCode = GetActionCode($MissingType,$dbh);
		$fAction = GetSugPeula($MissingType,$dbh);
		$fClockType = "Internet";
		$fPresentCd = $_POST['strMissingDtl'];
		$fActionType = "Missing";
		$fOperationType = $_POST['OperationType'];
		$fYear = getYear("d/m/Y",$fEnterDate);
		$fMonth = getMonth("d/m/Y",$fEnterDate);
		$fDay = getDay("d/m/Y",$fEnterDate);
		if (strlen($fOperationType) == 0){
			$fOperationType = "open";
		}

		// file upload
		$fDocumentFileFullPathName = "";
		$tempTargetFile = $_POST["tempTargetFile"];
		if ($tempTargetFile != ""){
			$uploadOk = 1;
			$target_dir = $_SESSION['ini_array']['UserFolder'].$_SESSION['UWorkerNum']."/".changeFormat('d/m/Y',$fEnterDate,'Ymd');
			// creating folder
			if (!is_dir($target_dir) AND !mkdir($target_dir,null,true)){
					unlink($tempTargetFile);
					echo 'Failed to create folder.';
					$uploadOk = 0;
			}
			$fDocumentFileFullPathName = $target_dir ."/". $_POST['CurrFileName'];
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				unlink($tempTargetFile);
				echo "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file
			} else {
				if (rename($tempTargetFile, $fDocumentFileFullPathName)) {
					echo chmod($fDocumentFileFullPathName, 0755);// read, write and execute permissions for owner, read and execute for others
					
				} else {
					echo "Sorry, there was an error uploading your file.";
					unlink($tempTargetFile);
					$uploadOk = 0;
				}
			}
		}
		

		// send email notification
		if (strlen($_SESSION['DepMail']) > 0){
			$to = $_SESSION['DepMail'];
			$message = "התקבל דיווח היעדרות מסוג '".$MissingType."' עבור ".$_SESSION['UWorkerName']."\r\n בין התאריכים: ".$fEnterDate." - ".$fExitDate."\r\n הדיווח בוצע על ידי: ".$_SESSION['UWorkerName'];
			if (strlen($fDocumentFileFullPathName) > 0 AND $uploadOk != 0){
				$message = $message."\r\n המסמך שצורף לנוכחות צורף גם להודעה זו";
			}
			include 'sendEmail.php';
		}
		$fDocumentFileFullPathName = str_ireplace("../","",$fDocumentFileFullPathName);//to register the right path to the attached file in the DB, as if PHP is placed directly in wwwroot.

		$startDate = $fEnterDate;
		while (strDateDiff($startDate,$fExitDate)){
			$fFullEnterDate = $startDate . " " . "00:00";
			$fFullDateForSort = changeFormat("d/m/Y",$startDate,"Y/m/d"). " " . "00:00";

			$sql = "INSERT into ClockTransNew (";
			$sql = $sql . "CardNumber,WFixID,PresentID,RegistrationDate,";
			$sql = $sql . "IpAddress,UserCode,FullEnterDate,FullExitDate,FullDateForSort,";
			$sql = $sql . "ActionCode,Action,ActionType,OperationType,EnterDate,EnterTime,";
			$sql = $sql . "ExitDate,ExitTime,PresentCd,";
			$sql = $sql . "TDprtmnt,DprtmntNm,TJob,JobNm,";
			$sql = $sql . "PassPhase1,PassPhase2,";
			$sql = $sql . "ClockName,";
			$sql = $sql . "CompanyNum,";
			$sql = $sql . "ClockType,";
			$sql = $sql . "FullDate,";
			$sql = $sql . "SourceForm,OriginalPresentID,";
			$sql = $sql . "DocumentFileFullPatName,";
			$sql = $sql . "OriginalTransacionId) ";

			$sql = $sql . "values (";

			$sql = $sql . "'" . $fCardNumber . "'" . ",";
			$sql = $sql . "" . $fWFixID . "" . ",";
			$sql = $sql . "" . $fPresentID . "" . ",";
			$sql = $sql . LeftDateSep  . $fRegistrationDate . sRightDateSep  . ",";
			$sql = $sql . "'" . $_SESSION["UserIp"] . "'" . ",";
			$sql = $sql . "'" . $_SESSION["un"] . "'" . ",";
			$sql = $sql . "'" . $fFullEnterDate . "'" . ",";
			$sql = $sql . "'" . $fFullEnterDate . "'" . ",";
			$sql = $sql . "'" . $fFullDateForSort . "'" . ",";
			$sql = $sql . "'" . $fActionCode . "'" . ",";
			$sql = $sql . "'" . $fAction . "'" . ",";
			$sql = $sql . "'" . $fActionType . "'" . ",";
			$sql = $sql . "'" . $fOperationType . "'" . ",";
			$sql = $sql . LeftDateSep  . $startDate . eRightDateSep  . ",";
			$sql = $sql . LeftDateSep  . "00:00" . eRightDateSep  . ",";
			$sql = $sql . LeftDateSep  . $startDate . eRightDateSep  . ",";
			$sql = $sql . LeftDateSep  . "00:00" . eRightDateSep  . ",";
			$sql = $sql . "'" . $fPresentCd . "'" . ",";

			$sql = $sql . "' '" . ",";
			$sql = $sql . "' '" . ",";
			$sql = $sql . "' '" . ",";
			$sql = $sql . "' '" . ",";

			$sql = $sql . "'" . True . "',";
			$sql = $sql . "'" . True . "',";

			$sql = $sql . "'" . "IOLVER03" . "'" . ",";
			$sql = $sql . "'" . "001" . "'" . ",";

			$sql = $sql . "'" . $fClockType . "'" . ",";
			$sql = $sql . "'" . $fFullDateForSort . "'" . ",";
			$sql = $sql . "'" . "Batch" . "'" . ",";
			$sql = $sql . "'" . "0" . "'" . ",";
			$sql = $sql . "'"  . $fDocumentFileFullPathName . "'" .  ",";
			$sql = $sql . "'" . "0" . "'" . ")";

			$sth = $dbh->query($sql);
			echo $vbCrLf . "<!--" . $sql . "-->" . $vbCrLf;

			$sql = "UPDATE WorkPrsnt SET ";
			$sql = $sql . "StartDate = ". LeftDateSep . $startDate . RightDateSep .", ";
			$sql = $sql . "StartTime = Null, ";
			$sql = $sql . "EndDate = ". LeftDateSep . $startDate . RightDateSep .", ";
			$sql = $sql . "EndTime = Null, ";
			$sql = $sql . "PresentCd = '" . $fPresentCd . "', ";
			$sql = $sql . "ChangePrsnt =  True, ";
			$sql = $sql . "TDprtmnt = Null, ";
			$sql = $sql . "TJob = Null, ";
			$sql = $sql . "DataSourceEnter = 'IOLVER03 " . $_SESSION['un'] . " " . $_SESSION['UserIp'] . "', ";
			$sql = $sql . "DataSourceExit = 'IOLVER03 " . $_SESSION['un'] . " " . $_SESSION['UserIp'] . "', ";
			$sql = $sql . "RegistrationEnterDate = ". LeftDateSep  . $fRegistrationDate . sRightDateSep . ", ";
			$sql = $sql . "RegistrationExitDate = ". LeftDateSep  . $fRegistrationDate . sRightDateSep . ", ";
			$sql = $sql . "DocumentFileFullPatName = '"  . $fDocumentFileFullPathName . "' ";

			$sql = $sql . "WHERE WFixID = " . $fWFixID . "  AND Year = '" . $fYear . "' AND Month = '" . $fMonth . "'  AND DayNumber = '" . $fDay . "' AND PresNumber = " . 1 . " ";

			$sth = $dbh->query($sql);
			echo $vbCrLf . "<!--" . $sql . "-->" . $vbCrLf;
			$startDate = addDays(1,$startDate);
			$fYear = getYear("d/m/Y",$startDate);
			$fMonth = getMonth("d/m/Y",$startDate);
			$fDay = getDay("d/m/Y",$startDate);
		}


		echo "<!--" . $tempTargetFile . "-->";
		echo "<!--" . $fDocumentFileFullPathName . "-->";
		$dbh = null;
		$sth = null;

	?>
	<SCRIPT>
		window.opener.location.reload();
		window.close();
	</SCRIPT>
</head>
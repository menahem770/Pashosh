<?php

//-----------------------------------------------------------------------------------------------------------
function BuildMissingList($currs,$dbh){

	$strMissing1 = "";
	//for whole list of missing
	//$sql ="SELECT ClockActionCodes.PrsntName FROM ClockActionCodes WHERE (ClockActionCodes.ClockName='Internet' AND ClockActionCodes.OperationType='Missing') ORDER BY ClockActionCodes.PrsntName";
	//for list of missing full day only
	$sql = "SELECT PrCalInd.CalcDes FROM PrCalInd WHERE (((PrCalInd.Active)=True) AND ((PrCalInd.WholeDayPresent)=True) AND ((PrCalInd.ReportedPresent)=True)) ORDER BY PrCalInd.CalcDes";

	$sth = $dbh->query($sql);
	$i = 0;
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
	while (isset($result,$result[$i])) {
		if ($result[$i]['CalcDes'] == $currs){
			$SL= " SELECTED ";
		}else{
			$SL = "";
		}
		$strMissing1 = $strMissing1 . '<option value="' . $result[$i]['CalcDes'] . '"' . $SL . '>' .  $result[$i]['CalcDes'] . '</option>';
		$i++;
	}
	return $strMissing1;
}

//-----------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------
function GetActionCode($strSugeiNochechutDtl,$dbh){

	$sql= "SELECT ClockActionCodes.ClockName, ClockActionCodes.PrsntName, ClockActionCodes.OperationType FROM ClockActionCodes WHERE (((ClockActionCodes.ClockName)='Internet') AND ((ClockActionCodes.PrsntName)='" . trim($strSugeiNochechutDtl) . "'))";

	echo "<!--" . $sql . "-->";
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	if ($result){
		if ($result['OperationType'] == "Missing"){
			return "העדרות";
		}else{
			return "דווח מלא";
		}
	}else{
		return "דווח מלא";
	}

}


//-----------------------------------------------------------------------------------------------------------
function GetSugPeula($strSugeiNochechutDtl,$dbh){

	$sql= "SELECT ClockActionCodes.ClockName, ClockActionCodes.PrsntName, ClockActionCodes.Action FROM ClockActionCodes WHERE (((ClockActionCodes.ClockName)='Internet') AND ((ClockActionCodes.PrsntName)='" . trim($strSugeiNochechutDtl) . "'))";

	echo "<!--" . $sql . "-->";
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	if ($result){
		return $result['Action'];
	}else{
		return "X";
	}

}


//-----------------------------------------------------------------------------------------------------------
//function BuildSugeiNochechutListOld($currs,$dbh){
//
//	$strSugeiNochechut1 = "";
//
//	$sql = "SELECT First(ClockActionCodes.PrsntName) AS PrsntName FROM ClockActionCodes GROUP BY ClockActionCodes.ClockName, ClockActionCodes.PrsntName HAVING (((ClockActionCodes.ClockName)='Internet')) ORDER BY First(IIf([ClockActionCodes]![PrsntName]='רגילה','A','B') & [ClockActionCodes]![PrsntName]), First(ClockActionCodes.PrsntName)";
//
//	$sth = $dbh->query($sql);
//	$result = $sth->fetch(PDO::FETCH_ASSOC);
//	while (isset($result)){
//		if ($result['PrsntName'] == $currs){
//			$SL= " SELECTED ";
//		}else{
//			$SL = "";
//		}
//		$strSugeiNochechut1 = $strSugeiNochechut1 . '<option value="' . $result['PrsntName'] . '"' . $SL . '>' .  $result['PrsntName'] . '</option>';
//		$result = $sth->fetch(PDO::FETCH_ASSOC);
//	}
//return $strSugeiNochechut1;
//
//}
//
//-----------------------------------------------------------------------------------------------------------
function BuildSugeiNochechutList($currs,$dbh){

	$strSugeiNochechut1 = "";
	$i = 0;
    //$sql = "SELECT PrCalInd.PresentCd FROM PrCalInd WHERE (((PrCalInd.Active)=True) AND ((PrCalInd.ReportedPresent)=True) AND ((PrCalInd.WholeDayPresent)=False))";
	$sql = "SELECT PrCalInd.PresentCd FROM PrCalInd WHERE (((PrCalInd.Active)=True) AND ((PrCalInd.ReportedPresent)=True) AND ((PrCalInd.WholeDayPresent)=False) AND ((PrCalInd.ToBrutto)=True) AND ((PrCalInd.IncludeInStandardBase)=True)) OR (((PrCalInd.CalcCode)=23)) ORDER BY IIf([CalcDes]='רגילה','A' & [CalcDes],IIf([CalcDes]='חופשה','B' & [CalcDes],IIf([CalcDes]='מחלה','C' & [CalcDes],IIf([CalcDes]='מילואים','D' & [CalcDes],[CalcDes]))))";
	//$sql = "SELECT PrCalInd.PresentCd FROM PrCalInd WHERE (((PrCalInd.Active)=True) AND ((PrCalInd.ReportedPresent)=True) AND ((PrCalInd.WholeDayPresent)=False) AND ((PrCalInd.ToBrutto)=True) AND ((PrCalInd.IncludeInStandardBase)=True) AND ((PrCalInd.PartialMissing)=True)) OR (((PrCalInd.CalcCode)=23)) ORDER BY IIf([CalcDes]='רגילה','A' & [CalcDes],IIf([CalcDes]='חופשה','B' & [CalcDes],IIf([CalcDes]='מחלה','C' & [CalcDes],IIf([CalcDes]='מילואים','D' & [CalcDes],[CalcDes]))))";
	
	$sth = $dbh->query($sql);
	$result = $sth->fetchALL(PDO::FETCH_ASSOC);
	if($currs == "ללא דווח"){
		$currs = "רגילה";
	}
	while (isset($result,$result[$i])){
		if ($result[$i]['PresentCd'] == $currs){
			$SL= " SELECTED ";
		}else{
			$SL = "";
		}
		$strSugeiNochechut1 = $strSugeiNochechut1 . '<option value="' . $result[$i]['PresentCd'] . '"' . $SL . '>' .  $result[$i]['PresentCd'] . '</option>';
		$i++;
	}
	if(!(strstr($strSugeiNochechut1,"SELECTED"))){
		$SL= " SELECTED ";
		$strSugeiNochechut1 = $strSugeiNochechut1 . '<option value="' . $currs . '"' . $SL . '>' .  $currs . '</option>';
	}
	return $strSugeiNochechut1;

}
//-----------------------------------------------------------------------------------------------------------
function BuildDepSList($currs,$dbh){

	$strDprt1 = "";

	$sql= "SELECT * FROM TDprtmnt WHERE DepGrop='1' order by TDprtmnt";
	//$sql= "SELECT * FROM TDprtmnt order by TDprtmnt";
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	while ($result){
		if ($result['TDprtmnt'] == $currs){
			$SL= " SELECTED ";
		}else{
			$SL = "";
		}
		$strDprt1 = $strDprt1 . '<option value="' . $result['TDprtmnt'] . '"' . $SL . '>' .  $result['TDprtmnt'] . "</option>";
		$result = $sth->fetch(PDO::FETCH_ASSOC);
	}

return $strDprt1;

}
//-----------------------------------------------------------------------------------------------------------

function BuildJobSList($currs,$dbh){

$strJobs1 = "";

    $sql= "SELECT * FROM TJob order by TJob";
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	while ($result){
		if ($result['TJob'] == $currs){
			$SL= "  SELECTED  ";
		}else{
			$SL = "";
		}
        $strJobs1 = $strJobs1 . '<option value="' . $result['TJob'] . '"' .  $SL . '>' . $result['TJob'] . '</option>' ;
		$result = $sth->fetch(PDO::FETCH_ASSOC);
	}

return $strJobs1;

}
//-----------------------------------------------------------------------------------------------------------
function GetDepName($DepCode,$dbh){

	$sql= "SELECT * FROM TDprtmnt WHERE DprtmntNm = '" . trim($DepCode) . "'";
	echo "<!--" . $sql . "-->";
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	if ($result){
		return $result['TDprtmnt'];
	}else{
		return "_ללא מחלקה";
	}

}
//-----------------------------------------------------------------------------------------------------------
function GetJobName($JobCode,$dbh){

	$sql= "SELECT * FROM TJob WHERE JobNm = '" . trim($JobCode) . "'";
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	if ($result){
		return $result['TJob'];
	}else{
		return "_ללא גוב";
	}

}
//-----------------------------------------------------------------------------------------------------------

//function ZmanTimeFormat($FormatS,$mispar,$dbh){

//$ZmanTimeFormat = $mispar;
//	if ($mispar>0){
//		$mm = MINUTE($mispar);
//		$hh = HOUR($mispar);
//		return substr("0".$hh,-2) . ":" . substr("0".$mm,-2);
//	}else{
//		return "&nbsp; ";
//	}

//}
//-----------------------------------------------------------------------------------------------------------

function MisparTimeFormat($mispar){

	if ($mispar>0){
		$mm = $mispar % 60;
		$hh = ($mispar-$mm)/60;
		return substr("0".$hh,-2) . ":" . substr("0".$mm,-2);
	}else{
    	return "&nbsp;";
	}
}
//-----------------------------------------------------------------------------------------------------------

function MisparTimeFormatEE($mispar){

	if ($mispar>0){
		$mm = $mispar % 60;
		$hh = ($mispar-$mm)/60;
		return substr("0".$hh,-2) . ":" . substr("0".$mm,-2);
	}else{
		if ($mispar == -1){
			return "NoExit";
		}elseif ($mispar == -2){
			return "NoEnter";
		}else{
			return "&nbsp;";
		}
	}

}

//-----------------------------------------------------------------------------------------------------------
// check for a week closed for changes within given period of time
function AllWeekStatus($WFixId, $startDate, $EndDate, $dbh){

	$start = changeFormat('d/m/Y',$startDate,'Y/m/d');
	$end = changeFormat('d/m/Y',$EndDate,'Y/m/d');
	$Wstart = date("w", strtotime($start)); //day number of the week
	$Wend = date("w", strtotime($end));
	if($Wstart > $Wend){ //end-date's day of the week comes before start-day's day of the week.
		$end = addDays($Wstart - $Wend,$end);
	}
	$EndDate = changeFormat('Y/m/d',$end,'d/m/Y');
        //$sql= "SELECT * FROM CHDay  WHERE WFixID =" . $WFixId . " AND ((((DateDiff('d',#".$startDate."#,[CHDay]![Date])>=0 And DateDiff('d',[CHDay]![Date],#".$EndDate."#)>=0)=True)=True))";
 	$sql= "SELECT * FROM CHDay WHERE WFixID =" . $WFixId . " AND format([CHDay]![Date],'yyyy/MM/dd')>='" .$start."' and format([CHDay]![Date],'yyyy/MM/dd') <= '".$end."'";
	echo "<!-- " . $sql . "-->" ;
	$sth = $dbh->query($sql);
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
	$i = 0;
	while(isset($result[$i])){
		$year = $result[$i]['Year'];
		$weekN = $result[$i]['WeekNumberInYear'];
		$sql= "SELECT * FROM WeekStatusPerWorker Where WFixID = " . $WFixId . " AND InYear = '" . $year . "' AND WeekNumber = " . $weekN;
		echo "<!-- " . $sql . "-->" ;
		$sth = $dbh->query($sql);
		$result1 = $sth->fetch(PDO::FETCH_ASSOC);
		if ($result1){
			if (trim(strtoupper($result1['CalculatedWeekStatus'])) == "F"){
				return "Disable";
			}else if(trim(strtoupper($result1['WeekStatus'])) != "O"){
				return "Disable";
			}
		}
		$i += 7;
	}
	return "";
}
//----------------------------------------------------------------------------------------------------------
// checks for existance of a day that contains any attendance entry within given time period
function FindPresenceInTimePeriod($WFixId, $startDate, $EndDate, $dbh){

	$Dis = "";
    //$sql= "SELECT * FROM WorkPrsnt Where WFixID=" . $WFixId . " AND ((((DateDiff('d',#".$startDate."#,[workPrsnt]![FullDate])>=0 And DateDiff('d',[workPrsnt]![FullDate],#".$EndDate."#)>=0)=True)=True)) AND PresNumber = 1";
	$sql= "SELECT * FROM WorkPrsnt Where WFixID=".$WFixId." AND format([workPrsnt]![FullDate],'yyyy/MM/dd')>='" .$startDate."' And format([workPrsnt]![FullDate],'yyyy/MM/dd') <= '".$EndDate. "' AND PresNumber = 1";
	$sth = $dbh->query($sql);
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
	$i = 0;
	while (isset($result[$i])){
		if (strlen("" . $result[$i]['PresentCd']) > 0){
			return "Disable";
		}
		$i++;
	}
	return "";
}

//-----------------------------------------------------------------------------------------------------------

function GetFirstDayInYear($WfixId, $cYear, $WeekN, $dbh){

	$sql= "SELECT * FROM WeekStatusPerWorker Where WFixID=" . $WfixId . " AND InYear = '" . $cYear  . "' AND WeekNumber = " . $WeekN;

	echo "<!-- " . $sql . "-->" ;
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);

	if ($result){
		return $result['FirstDayInYear'];
	}else{
		return "01/01/" . $cYear;
	}

}
//-----------------------------------------------------------------------------------------------------------

function Iif ($blnExpression, $vTrueResult, $vFalseResult){
  if ($blnExpression){
    return $vTrueResult;
  }else{
    return $vFalseResult;
  }
}
//------mendy fridman----------------------------------------------------------------------------------------


//---returns pieces of the date and time of date() object.
//---$format is the format of the date received.
function getYear($format,$pdate) {
	$date = DateTime::createFromFormat($format, $pdate);
	if ($date){
		return $date->format("Y");//4 digit format
	}else{
		return "";
	}
}
//-----------------------------------------------------------------------------------------------------------
function getMonth($format,$pdate) {
	$date = DateTime::createFromFormat($format, $pdate);
	if ($date){
		return $date->format("m");//2 digit format
	}else{
		return "";
	}
}
//-----------------------------------------------------------------------------------------------------------
function getDay($format,$pdate) {
	$date = DateTime::createFromFormat($format, $pdate);
	if ($date){
		return $date->format("d");//2 digit format
	}else{
		return "";
	}
}
//-----------------------------------------------------------------------------------------------------------
function getHour($format,$pdate) {
	$date = DateTime::createFromFormat($format, $pdate);
	if ($date){
		return $date->format("H");//24 hour format
	}else{
		return "";
	}
}
//-----------------------------------------------------------------------------------------------------------
function getMinute($format,$pdate) {
	$date = DateTime::createFromFormat($format, $pdate);
	if ($date){
		return $date->format("i");//2 digit format
	}else{
		return "";
	}
}
//-----------------------------------------------------------------------------------------------------------
function getFullTime($format,$pdate) {
	$date = DateTime::createFromFormat($format, $pdate);
	if ($date){
		return $date->format("H:i");//24 hour format : 2 digit format
	}else{
		return "";
	}
}
//-----------------------------------------------------------------------------------------------------------
//--- $newFormat according to date() obj supported formats
function changeFormat($format,$pdate,$newFormat) {
	$date = DateTime::createFromFormat($format, $pdate);
	if ($date){
		return $date->format($newFormat);
	}else{
		return "";
	}
}
//-----------------------------------------------------------------------------------------------------------
//--- returns 'true' if $date1 is earlier than $date2. format 'd/m/Y' accepted
function strDateDiff($date1,$date2){
	$dateA = strtotime(str_replace('/', '-', $date1));
	$dateB = strtotime(str_replace('/', '-', $date2));
	if($dateA <= $dateB){return 1;}else{return 0;}
}
//-----------------------------------------------------------------------------------------------------------
//--- return the diff in days between two dates. format 'd/m/Y' accepted
function dateDiff($date1, $date2){
	$datetime1 = new DateTime(str_replace('/', '-', $date1));
	$datetime2 = new DateTime(str_replace('/', '-', $date2));
	$interval = date_diff($datetime1, $datetime2);
	return $interval->format('%a');
}
//-----------------------------------------------------------------------------------------------------------
//--- add $num number of days to the date() type $date. format 'd/m/Y' accepted
function addDays($num, $date){
	$newDate = str_replace("/","-",$date);
	$newDate = strtotime($newDate);
	$newDate = date('d/m/Y',$newDate + (60*60*24*$num));
	return $newDate;
}
//-----------------------------------------------------------------------------------------------------------
//--- session log-out
function log_out_current_user() {
	// remove all session variables
	session_unset();
	// destroy the session
	session_destroy();
	if (isset($_SERVER['HTTP_COOKIE'])) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', 1, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
	}

}
//-----------------------------------------------------------------------------------------------------------
function numOfWeeksInYear($year){

	$numOfWeeks = 0;
	$numOfDays = dateDiff("01/01/".$year, "01/01/".($year+1));
	$fd = jddayofweek(gregoriantojd(1,1,$year),0);
	$ld = jddayofweek(gregoriantojd(12,31,$year),0);
	if ($fd != 0){ //year did not begin on sunday
		$numOfDays -= (7-$fd);
		$numOfWeeks ++;
	}
	if ($ld != 6){
		$numOfDays -= ($ld+1);
		$numOfWeeks ++;
	}
	if ($numOfDays % 7 != 0){
		echo "<!-- something's wrong with numOfWeeksInYear() !-->";
		return false;
	}
	$numOfWeeks += $numOfDays/7;
	return $numOfWeeks;
}
//-----------------------------------------------------------------------------------------------------------
function LegalPresence($result, $FormType, $PresNumber, $fromDate, $fromTime, $endDate, $endTime){

	if($endDate == ""){
		$endDate = $fromDate;
		$endTime = $fromTime;
	}

	if(count($result) == 1 AND empty($result[0]['StartDate']) AND empty($result[0]['EndDate'])){
		return "t";
	}else{
		$tempDate = 0;
		$tempDate1 = 0;
		$i = 0;
		while(isset($result[$i])){
			if($result[$i]['StartDate'] == ""){
				$result[$i]['StartDate'] = $result[$i]['EndDate'];
				$result[$i]['StartTime'] = $result[$i]['EndTime'];
			}else if($result[$i]['EndDate'] == ""){
				$result[$i]['EndDate'] = $result[$i]['StartDate'];
				$result[$i]['EndTime'] = $result[$i]['StartTime'];
			}
			$i++;
		}
		if($FormType == 'D'){
			return $PresNumber;
		}
		if($FormType == 'U'){
			$i = 0;
			while($result[$i]['PresNumber'] != $PresNumber){
				$i++;
			}
			if($i > 0){
				$tempDate = changeFormat("Y-m-d",substr($result[$i-1]['EndDate'],0,10),"d/m/Y")." ".substr($result[$i-1]['EndTime'],-8,5);
				if(!strDateDiff($tempDate,$fromDate." ".$fromTime)){
					return "f";
				}
			}
			if($i < count($result)-1){
				$tempDate = changeFormat("Y-m-d",substr($result[$i+1]['StartDate'],0,10),"d/m/Y")." ".substr($result[$i+1]['StartTime'],-8,5);
				if(!strDateDiff($endDate." ".$endTime,$tempDate)){
					return "f";
				}
			}
			return "t";
		}else if($FormType == 'N'){
			$i = 0;
			while(isset($result[$i])){
				$tempDate = changeFormat("Y-m-d",substr($result[$i]['StartDate'],0,10),"d/m/Y")." ".substr($result[$i]['StartTime'],-8,5);
				$tempDate1 = changeFormat("Y-m-d",substr($result[$i]['EndDate'],0,10),"d/m/Y")." ".substr($result[$i]['EndTime'],-8,5);
				if(strDateDiff($endDate." ".$endTime, $tempDate)){
					return $i+1;
				}
				else if(strDateDiff($tempDate1, $fromDate." ".$fromTime)){
					if($i < count($result)-1){
						$i++;
					}else{
						return $i+2;
					}
				}
				else{
					return "f";
				}
			}
		}
	}
}
//-----------------------------------------------------------------------------------------------------------
// returns the hebrew letter of the day of the week. 'א' for sunday...
function GetHebrewDayInWeek($formatDate) {
	$fDayNumberInWeek = date("w", strtotime($formatDate));
	switch ($fDayNumberInWeek) {
		case 0:
			$fDayOfWeek = 'א';
			break;
		case 1:
			$fDayOfWeek = 'ב';
			break;
		case 2:
			$fDayOfWeek = 'ג';
			break;
		case 3:
			$fDayOfWeek = 'ד';
			break;
		case 4:
			$fDayOfWeek = 'ה';
			break;
		case 5:
			$fDayOfWeek = 'ו';
			break;
		case 6:
			$fDayOfWeek = 'ש';
			break;
		default:
			$fDayOfWeek = 'X';
			break;
	}
	return $fDayOfWeek;
}
//-----------------------------------------------------------------------------------------------------------
// advancing presNumbers in the DB to make room for the new present - if needed (new present, not update).
// returns the final presNumber for this presence updated or new.

function AdvancePresNumber($result, $fNewPresNumber, $formType, $dbh){
	if($formType == "D"){
		$i = 0;
		while(isset($result[$i]) AND $result[$i]['PresNumber'] < $fNewPresNumber){
			$i++;
		}
		if(isset($result[$i]) AND $result[$i]['PresNumber'] == $fNewPresNumber){
			$sql = "UPDATE WorkPrsnt SET PresNumber = 100 WHERE PresentID = ".$result[$i]['PresentID']." AND PresNumber = ".$fNewPresNumber;
			$sth = $dbh->query($sql);
			$i = $fNewPresNumber; //the line next to $fNewPresNumber line.
			while(isset($result[$i])){
				$currentPresNumber = $result[$i]['PresNumber'];
				$sql = "UPDATE WorkPrsnt SET WorkPrsnt.PresNumber = ".($currentPresNumber-1)." WHERE (((WorkPrsnt.PresentID)= ".$result[$i]['PresentID'].") AND ((WorkPrsnt.PresNumber)= ".$currentPresNumber." ))";
				$sth = $dbh->query($sql);
				$i++;
			}
			return 100; //random unreachable number is now the presNumber for the presence to be deleted
		}
	}else{ // $formType = "N" = new present
		$i = count($result)-1;
		while(isset($result[$i]) AND $result[$i]['PresNumber'] >= $fNewPresNumber){
			$currentPresNumber = $result[$i]['PresNumber'];
			$sql = "UPDATE WorkPrsnt SET WorkPrsnt.PresNumber = ".($currentPresNumber+1)." WHERE (((WorkPrsnt.PresentID)= ".$result[$i]['PresentID'].") AND ((WorkPrsnt.PresNumber)= ".$currentPresNumber." ))";
			$sth = $dbh->query($sql);
			$i--;
		}
		return $fNewPresNumber;
	}
}


function AdvancePresNumberOld($result, $fNewPresNumber, $dbh){

	$i = 0;
	while(isset($result[$i]) AND $result[$i]['PresNumber'] < $fNewPresNumber){
		$i++;
	}
	if(isset($result[$i]) AND $result[$i]['PresNumber'] == $fNewPresNumber){
		while(isset($result[$i])){
			$currentPresNumber = $result[$i]['PresNumber'];
			$sql = "UPDATE WorkPrsnt SET WorkPrsnt.PresNumber = ".($currentPresNumber+1)." WHERE (((WorkPrsnt.PresentID)= ".$result[$i]['PresentID'].") AND ((WorkPrsnt.PresNumber)= ".$currentPresNumber." ))";
			$sth = $dbh->query($sql);
			$i++;
		}
	}
	return $fNewPresNumber;
}




?>
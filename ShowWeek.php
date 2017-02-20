
<?php
	$lvl = 1;
	$i=0;
	
	$sql = "SELECT * FROM SelectCHDayPerWeek_Total_Minutes WHERE WorkerNum = '".$_SESSION['WorkerNum']."'";
	echo "<!--" . $sql . "-->";	
	$sth = $dbh->query($sql);
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$j=0;
	$sql = "SELECT * FROM SelectCHDayPerWeek_Daily_Minutes AS SelectCHDPWDM WHERE SelectCHDPWDM.WorkerNum = '".$_SESSION['WorkerNum']."'";
	echo "<!--" . $sql . "-->";
	$sth = $dbh->query($sql);
	$result1 = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$k=0;
	$sql = "SELECT * FROM SelectWorkerPresentWithMadanVer03 AS SelectWPM2 WHERE SelectWPM2.WorkerNum ='".$_SESSION['WorkerNum']."'";
	echo "<!--" . $sql . "-->";
	$sth = $dbh->query($sql);
	$result2 = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$status = "סטטוס";
	$familySickness = "מחלת בן-משפחה";
	$army = "מילואים";
	$vacation = "חופשה";
	$sickness = "מחלה";
	$holyday = "חג";
	$missing = "היעדרות";
	$extraHours = "שעות נוספות";
	$regular = "נוכחות רגילה";
	$sumAllHours = "סה''כ נוכחות";
	$standerd = "תקן";
	$dayType = "סוג יום";
	$toDay = "עד יום";
	$fromDay = "מיום";
	$weekNumber = "מס' שבוע";
		
?>
		<tr CLASS="tableHeader">
			<td>
				<?=$status?>
			</td>
			<td>
				<?=$missing?>
			</td>
			<td>
				<?=$extraHours?>
			</td>
			<td>
				<?=$regular?>
			</td>
			<td>
				<?=$sumAllHours?>
			</td>
			<td>
				<?=$standerd?>
			</td>
			<td>
				<?=$dayType?>
			</td>
			<td>
				<?=$toDay?>
			</td>
			<td>
				<?=$fromDay?>
			</td>
			<td>
				<?=$weekNumber?>
			</td>
		</tr>
<?php
		//correcting for the year-end-week which splits to 2 weeks (end, and begining of the next year)
		if ($result[$i]['WeekNumberInYear'] < $_SESSION['WeeksPerPage']){
			// checking if year begun in the middle of the week
			$jd=gregoriantojd(1,1,date("Y",strtotime("first day of last year"))); //julian representaion of last year number - for jddayofweek()
			if (jddayofweek($jd,2) != "Sun"){
				$_SESSION['WeeksPerPage']++; 
			}
		}
		
		while (isset($result[$i]) AND $i < $_SESSION['WeeksPerPage']){
			$totalMissing = $result[$i]['ChildSickness']+$result[$i]['Army']+$result[$i]['Vacation']+$result[$i]['Sickness']+$result[$i]['Holiday']+$result[$i]['Missing'];
			if (trim($result[$i]['WeekStatus']) == "L"){
				$StatusImg = "images/L_md_clr.gif";
				$StatusAlt = "Locked";
			}elseif (trim($result[$i]['WeekStatus']) == "R"){
				$StatusImg = "images/R_md_clr.gif";
				$StatusAlt = "Released";
			}elseif (trim($result[$i]['WeekStatus']) == "C"){
				$StatusImg = "images/C_md_clr.gif";
				$StatusAlt = "Closed";
			}elseif (trim($result[$i]['WeekStatus']) == "F"){
				$StatusImg = "images/F_md_clr.gif";
				$StatusAlt = "Finished";
			}else{
				$StatusImg = "images/O_md_clr.gif";
				$StatusAlt = "Open";
			}
?>
			<tr>
				<td>
					<img id="status<?=$result[$i]['WeekNumberInYear']?>" src="<?=$StatusImg?>" width=24 height=24 border=0 alt="<?=$StatusAlt?>" Style="cursor: pointer;" OnClick='openChSW("ChWeekStatus.php","0<?=$result[$i]['RecID']?>")'>
				</td>
				<td>
					<?=MisparTimeFormat($totalMissing)?>
				</td>
				<td>
					<?=MisparTimeFormat($result[$i]['StiiatTeken'])?>
				</td>
				<td>
					<?=MisparTimeFormat($result[$i]['Regular'])?>
				</td>
				<td>
					<?=MisparTimeFormat($result[$i]['Brutto'])?>
				</td>
				<td>
					<?=MisparTimeFormat($result[$i]['Standard'])?>
				</td>
				<td>
					&nbsp;
				</td>
				<td>
					<?=$result[$i]['LastDay']?>
				</td>
				<td>
					<?=$result[$i]['FirstDay']?>
				</td>
				<td>
					<?=$result[$i]['WeekNumberInYear']?>&nbsp;<IMG SRC="images/plus.jpg" style="cursor: pointer;" WIDTH=12 HEIGHT=12 ID="$lvl<?=$i+1?>Btn" ONCLICK='open_close_group("$lvl<?=$i+1?>")' BORDER="0">
				</td>
			</tr>
<?php
			include 'ShowDay.php';
			$i++;
		}
		$sth = null;
		$dbh = null;
?>

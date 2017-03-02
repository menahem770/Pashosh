<?php
	$i=1;
	$lvl = 1;
	
	if(isset($_GET['weekInYearNum'])){
		$sql = "SELECT * FROM SelectCHDayPerWeek_Total_Minutes WHERE WorkerNum = '".$_SESSION['WorkerNum']."' AND WeekNumberInYear = ".$_GET['weekInYearNum'];
	}else{
		$sql = "SELECT * FROM SelectCHDayPerWeek_Total_Minutes WHERE WorkerNum = '".$_SESSION['WorkerNum']."'";
	}
	echo "<!--" . $sql . "-->";
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	
	if(isset($_GET['numWeekInPage'])){
		$i = $_GET['numWeekInPage'];
	}
	
	$j=0;
	$sql = "SELECT * FROM SelectCHDayPerWeek_Daily_Minutes WHERE WorkerNum = '".$_SESSION['WorkerNum']."' AND WeekNumberInYear = ".$result['WeekNumberInYear'];
	echo "<!--" . $sql . "-->";
	$sth = $dbh->query($sql);
	$result1 = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$k=0;
	$sql = "SELECT * FROM SelectWorkerPresentWithMadanVer03 WHERE WorkerNum ='".$_SESSION['WorkerNum']."' AND WeekNumberInYear = ".$result['WeekNumberInYear'];
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
		if ($result){
			$totalMisssing = $result['ChildSickness']+$result['Army']+$result['Vacation']+$result['Sickness']+$result['Holiday']+$result['Missing'];
			if (trim($result['WeekStatus']) == "L"){
				$StatusImg = "images/L_md_clr.gif";
				$StatusAlt = "Locked";
			}elseif (TRIM($result['WeekStatus']) == "R"){
				$StatusImg = "images/R_md_clr.gif";
				$StatusAlt = "Released";
			}elseif (TRIM($result['WeekStatus']) == "C"){
				$StatusImg = "images/C_md_clr.gif";
				$StatusAlt = "Closed";
			}elseif (TRIM($result['WeekStatus']) == "F"){
				$StatusImg = "images/F_md_clr.gif";
				$StatusAlt = "Finished";
			}else{
				$StatusImg = "images/O_md_clr.gif";
				$StatusAlt = "Open";
			}
	?>
			<tr>
				<td>
					<img id="status<?=$result['WeekNumberInYear']?>" src="<?=$StatusImg?>" width=24 height=24 border=0 alt="<?=$StatusAlt?>" Style="cursor: pointer;" OnClick='openChSW("ChWeekStatus.php","0<?=$result['RecID']?>")'>
				</td>
				<td>
					<?=MisparTimeFormat($totalMisssing)?>
				</td>
				<td>
					<?=MisparTimeFormat($result['StiiatTeken'])?>
				</td>
				<td>
					<?=MisparTimeFormat($result['Regular'])?>
				</td>
				<td>
					<?=MisparTimeFormat($result['Brutto'])?>
				</td>
				<td>
					<?=MisparTimeFormat($result['Standard'])?>
				</td>
				<td>
					&nbsp;
				</td>
				<td>
					<?=$result['LastDay']?>
				</td>
				<td>
					<?=$result['FirstDay']?>
				</td>
				<td>
					<?=$result['WeekNumberInYear']?>
				</td>
			</tr>
		<?php
			include 'SWShowDay.php';
			
			$nextWeek = "";
			$lastWeek = "";
			if($i <= 1){
				$nextWeek = " Disabled ";
			}
			if($i >= $_SESSION['WeeksPerPage']){
				$lastWeek = " Disabled ";
			}
			
			//correcting URL for last week of the year and the first.
			$pYear = $result['InYear']; //for the URL
			$nYear = $result['InYear']; //for the URL
			$nextWeekInYear = $result['WeekNumberInYear']+1; //for the URL
			$prevWeekInYear = $result['WeekNumberInYear']-1; //for the URL
			if($result['WeekNumberInYear']-1 <= 0){
				$prevWeekInYear = numOfWeeksInYear($result['InYear']-1);
				$pYear = $result['InYear']-1;
			}else if($result['WeekNumberInYear']+1 > numOfWeeksInYear($result['InYear'])){
				$nextWeekInYear = 1;
				$nYear = $result['InYear']+1;
			}
		?>
			<tr>
				<td colspan=10>
					<table style="width:100%;">
						<tr>
							<td style="width:25%;">
								<input name="lastW" type=button onClick="location.href='SWWeeks.php?weekInYearNum=<?=$prevWeekInYear?>&numWeekInPage=<?=$i+1?>&year=<?=$pYear?>&WorkerNum=<?=$_SESSION['WorkerNum']?>&WFixID=<?=$_SESSION['WFixID']?>&WorkerName=<?=$_SESSION['WorkerName']?>&DepMail=<?=$_SESSION['DepMail']?>&IncldInMadan=<?=$_SESSION['IncldInMadan']?>&IncldInTmhir=<?=$_SESSION['IncldInTmhir']?>&defDep=<?=$_SESSION['DefaultDep']?>&defJob=<?=$_SESSION['DefaultJob']?>&CardNumber=<?=$_SESSION['CardNumber']?>'" value='שבוע קודם' <?=$lastWeek?> style="width:100%; height:25px; cursor: pointer;">
							</td>
							<td>&nbsp;</td>
							<td style="width:25%;">
								<input name="nextW" type=button onClick="location.href='SWWeeks.php?weekInYearNum=<?=$nextWeekInYear?>&numWeekInPage=<?=$i-1?>&year=<?=$nYear?>&WorkerNum=<?=$_SESSION['WorkerNum']?>&WFixID=<?=$_SESSION['WFixID']?>&WorkerName=<?=$_SESSION['WorkerName']?>&DepMail=<?=$_SESSION['DepMail']?>&IncldInMadan=<?=$_SESSION['IncldInMadan']?>&IncldInTmhir=<?=$_SESSION['IncldInTmhir']?>&defDep=<?=$_SESSION['DefaultDep']?>&defJob=<?=$_SESSION['DefaultJob']?>&CardNumber=<?=$_SESSION['CardNumber']?>'" value='שבוע הבא' <?=$nextWeek?> style="width:100%; height:25px; align:right; cursor: pointer;">
							</td>
						</tr>
					</table>
				</td>
			</tr>
	<?php
		}
		$sth = null;
		$dbh = null;
	?>

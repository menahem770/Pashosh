<?php
	
	$currentWeekNum = $result1[$j]['WeekNumberInYear'];
	while (isset($result1[$j]) AND $result1[$j]['WeekNumberInYear'] == $currentWeekNum){
		$totalMisssing = $result1[$j]['ChildSickness']+$result1[$j]['Army']+$result1[$j]['Vacation']+$result1[$j]['Sickness']+$result1[$j]['Holiday']+$result1[$j]['Missing'];
?>
		<tr>
			<td colspan=2>
				<?=MisparTimeFormat($totalMisssing)?>
			</td>
			<td>
				<?=MisparTimeFormat($result1[$j]['StiiatTeken'])?>
			</td>
			<td>
				<?=MisparTimeFormat($result1[$j]['Regular'])?>
			</td>
			<td>
				<?=MisparTimeFormatEE($result1[$j]['Brutto'])?>
			</td>
			<td>
				<?=MisparTimeFormat($result1[$j]['Standard'])?>
			</td>
			<td>
				<?=$result1[$j]['DayType']?>
			</td>
			<td colspan=3>
				<?=date("d/m/Y",strtotime($result1[$j]["LastOfDate"]))?>
			</td>
		</tr>

<?php
		include 'SWDayDetail.php';
		$j++;
	}
?>

<tr>
	<td colspan=8 style="border-width: 1px;">
		<table id="<?=date("d/m/Y",strtotime($result1[$j]["LastOfDate"]))?>" CLASS="prsnt">
		
			<?php
				
				$sum = ' :סה"כ ';
				$enter = ' :כ ';
				$exit = ' :י ';
				$currentDayNumInYear = $result2[$k]['DayNumberInYear'];
				
				$x = $k;
				$count = 0; //to count number of presents for this day. for the 'new' button ID field
				while (isset($result2[$x]) AND $result2[$x]['DayNumberInYear'] == $currentDayNumInYear){
					$count++;
					$x++;
				}
			
				while (isset($result2[$k]) AND $result2[$k]['DayNumberInYear'] == $currentDayNumInYear){
						
					$FullDate = changeFormat("Y/m/d",$result2[$k]["FullDate"],"m/Y");
					$lastMonth = date("m/Y",strtotime('first day of previous month'));
					$DataSourceEnter = $result2[$k]['DataSourceEnter'];
					$DataSourceExit = $result2[$k]['DataSourceExit'];
					
					// enable and disable the button "NEW" for new present
					// enable and disable the button "EDIT" for editing a present
					$EnableNewRecord = "";
					$EnableUpdateRecord = "";
					if(($result2[$k]['PresentCd'] == "ללא דווח") OR 
					  (($result2[$k]['PresentCd'] != "רגילה") AND (strpos($result2[$k]['PresentCd'],"חלק") === false)) OR 
					  (!((strtotime('$result2[$k]["RuntimeStartDate"]') <= strtotime('$result2[$k]["FullDate"]')) AND (strtotime('$result2[$k]["FullDate"]') <= strtotime('$result2[$k]["RuntimeEndDate"]')))) OR 
					  (($FullDate == date("m/Y")) AND ($result2[$k]['ManualUpdatesNumber'] >= $_SESSION['NumberOfManualChangesIOL'])) OR 
					  (($FullDate == $lastMonth) AND ($result2[$k]['ManualUpdatesNumberPM'] >= $_SESSION['NumberOfManualChangesIOL'])))
						{ $EnableNewRecord = " Disabled "; }
					if((!$_SESSION['ini_array']['EnableWeeksClosedEditing']) AND ($result2[$k]['WeekStatus'] != "O")) {
						$EnableNewRecord = " Disabled ";
						$EnableUpdateRecord = " Disabled ";
					}
					if((!$_SESSION['ini_array']['EnableManagerReporting']) AND ($_SESSION['UWFixID'] != $_SESSION['WFixID']))
						{ $EnableNewRecord = " Disabled "; }
					if((!$_SESSION['ini_array']['EnableClockTransEditing']) AND 
					  ((substr($DataSourceEnter,0,5) == "SB100" AND substr($DataSourceExit,0,5) == "SB100") OR (substr($DataSourceEnter,0,5) == "Synel" AND substr($DataSourceExit,0,5) == "Synel")))
						{ $EnableUpdateRecord = " Disabled "; }
			
					$fCurrFile = $result2[$k]['DocumentFileFullPatName'];
					
					$style = "";
					if($result2[$k]['PresentCd'] != "רגילה" AND $result2[$k]['PresentCd'] != "ללא דווח"){
						$style = "background-color:burlywood; color:red;";
					}
			?>

					<tr id="<?=$result2[$k]['FullDate'].'_'.$result2[$k]['PresNumber']?>" style="background-color: rgba(114, 106, 144, 0.38);">
					
						<td style="width:95px;">
							<?=$result2[$k]['TDprtmnt']?>
						</td>
			
						<td style="width:105px; <?=$style?>">
							<?=$result2[$k]['PresentCd']?>
						</td>
			
						<td style="text-align:right; padding-right:5px; width:90px;">
							<?=MisparTimeFormat($result2[$k]['length']).$sum?>
						</td>
			
						<td style="text-align:right; padding-right:5px; width:65px;">
							<?=$result2[$k]['EndTime'].$exit?>
						</td>
			
						<td style="text-align:right; padding-right:5px; width:65px;">
							<?=$result2[$k]['StartTime'].$enter?>
						</td>
			
						<td style="width:30px;">
							<input type=button value=עדכון <?=$EnableUpdateRecord?> style="cursor: pointer;" ONCLICK='openUpdW("fulrep-w.php",<?=$result2[$k]['PresentID']?>,"<?=trim($result[$i]['WeekStatus'])?>","<?=$result2[$k]['FullDate']?>","<?=$fCurrFile?>")'>
						</td>
			
						<td style="width:30px;">
							<input class="buttons<?=$result[$i]['WeekNumberInYear']?>" id="<?=$count?>" type=button value=חדש <?=$EnableNewRecord?> style="cursor: pointer;" ONCLICK='openUpdW("fulnew-w.php",<?=$result2[$k]['PresentID']?>,"<?=Trim($result[$i]['WeekStatus'])?>","<?=$result2[$k]['FullDate']?>")'>
						</td>
					
					</tr>


			<?php
					$k++;
				}
			?>
		</table>
	</td>
	<td colspan=2>
		&nbsp;
	</td>
</tr>




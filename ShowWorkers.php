
	<tr>
		<td>
			פתוחים
		</td>
		<td>
			ממתין לאישור
		</td>
		<td>
			סגורים
		</td>
		<td>
			נעולים
		</td>
		<td>
			שינויים ידניים
		</td>
		<td>
			מחלקה
		</td>
		<td>
			שם פרטי
		</td>
		<td>
			שם משפחה
		</td>
		<td>
			מספר עובד
		</td>
		<td>
			&nbsp;
		</td>
	</tr>

<?php

	$lvl = 1;
	$i=0;
	
	$sql = "SELECT WDetailF.*, SelectWeekStatusPerWorker.*, Dprtmnts.DepMail ";
	$sql = $sql . "FROM (WDetailF INNER JOIN SelectWeekStatusPerWorker ON WDetailF.WFixID = SelectWeekStatusPerWorker.WFixID) INNER JOIN Dprtmnts ON WDetailF.DepNum = Dprtmnts.DepNum ";
	$sql = $sql . " WHERE WDetailF.DepNum between '" . $_SESSION['fDepNum'] . "' AND '" . $_SESSION['tDepNum'] . "' or WDetailF.WFixID = " . $_SESSION['UWFixID'] . " ORDER BY FullName ";

	echo "<!-- " . $sql . " -->";

	$sth = $dbh->query($sql);
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);

	while (isset($result[$i])){
		
?>
		<tr>
			<td>
				<?=$result[$i]['NumberOfOpenWeeks'] /1 ?>
			</td>

			<td>
				<?=$result[$i]['NumberOfReleasedWeeks'] /1 ?>
			</td>

			<td>
				<?=$result[$i]['NumberOfClosedWeeks'] /1 ?>
			</td>

			<td>
				<?=$result[$i]['NumberOfLockedWeeks'] /1 ?>
			</td>

			<td>
				<?=$result[$i]['ManualUpdatesNumber'] /1 ?>
			</td>

			<td>
				<?=$result[$i]['Department']?>
			</td>
			<td>
				<?=$result[$i]['FirstName']?>
			</td>
			<td>
				<?=$result[$i]['LastName']?>
			</td>
			<td>
				<?=$result[$i]['WorkerNum']?>
			</td>
			<td>
				<a href="SWWeeks.php?worker=<?=$i?>&WFixID=<?=$result[$i]['WFixID']?>&fromWorkersPage=1"><IMG SRC="images/plus.jpg" style="cursor: pointer;" WIDTH=12 HEIGHT=12 BORDER="0"></a>
			</td>
		</tr>
<?php
		$i++;
	}
	$sth = null;
	$dbh = null;
?>

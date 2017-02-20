<!DOCTYPE html>
<?php
	session_start();
	header('Content-Type: text/html; charset=CP1255');
	
	ob_start();
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	
	include 'Funcs.php';
	include 'DBConect.php';
        
	if (!isset($_SESSION['UWorkerNum'])){
		header("Location: login.php?stat=3");
		exit();
	}
?>
<html>
<head>
	<title>psos - malram</title>
	<meta charset="windows-1255">
	<link href="css/psos.css" rel="stylesheet" type="text/css">
	<link href="css/login.css" rel="stylesheet" type="text/css">
<?php

	$sql = "SELECT * FROM IniFile Where (ID = 1 or ID = 28 or ID = 68)";
	$sthIni = $dbh->query($sql);
	$resultIni = $sthIni->fetchAll(PDO::FETCH_ASSOC);
	if (isset($resultIni) AND count($resultIni) == 3){
		$NumOfWeeks = $resultIni[0]['NumOfWeeks'];
		$IOLDisplayDepth = $resultIni[1]['Parameter'];
		$NumberOfManualChangesInInternetOnline = $resultIni[2]['Parameter'];
	}else{
		$alert = "some Database Parameters are Missing./nplease call Malram.";
		$NumOfWeeks = 8;
		$IOLDisplayDepth = "StartOfPreviousMonth";
		$NumberOfManualChangesInInternetOnline = 2;
	}
		
	if($_SESSION['mng'] != 2){
		$alert = "You are not authorized to change parameters in this page!";
	}
	if(isset($alert)){
?>
		<script>
			alert(<?=$alert?>);
		</script> 
<?php
		header("Location: login.php");
		exit();
	}	
	
	$sthIni = null;
	$resultIn = null;
	$WrkNm = $_SESSION['WorkerNum'];
	$WrkId = $_SESSION['WorkerName'];
	
	$default1 = "";
	$default2 = "";
	$disable = "";
	if($IOLDisplayDepth == "StartOfPreviousMonth"){
		$default1 = "CHECKED";
		$disable = "DISABLED";
	}else{
		$default2 = "CHECKED";
	}
	$displeySet1 = "תחילת חודש קודם";
	$displaySet2 = "מספר שבועות קבוע";
	$dbh = null;
	$sth = null;
?>

</head>

<body CLASS="background">
<script>
	function disable()
	{
		if(document.getElementById('month').checked){
			document.getElementById('NumOfWeeks').disabled = true;
		}
	}
	function enable()
	{
		if(document.getElementById('week').checked){
			document.getElementById('NumOfWeeks').disabled = false;
		}
	}
</script>
<table CLASS="page">
		<tr>
			<td colspan=2><?php include 'toplogo.php';?></td>
		</tr>
		<tr>
			<td CLASS="leftmenu">				
				<?php include 'leftmenu.php';?>
			</td>
			<td style="vertical-align:top; width:auto;">
				<br><br>
				<div>
				  <div dir=rtl CLASS="fill2"><h2 style="margin:auto;">עדכון פרמטרים לפשוש אינטרנט אונליין</h2></div>
				</div>
				<form name=iniform action="inifile-sql.php" method="post">
					<table CLASS=iniFile dir=rtl>
						<tr>
							<td style="backgroung-color:#CAC6DD; vertical-align:top;">
								<table style="align:right; padding: 2px;">
									<tr>
										<td>הגדרת עומק תצוגה:</td>
										<td>
											<input type="radio" name="monthOrWeek" id="month" value="month" onchange="disable()" <?=$default1?>> <?=$displeySet1?><br>
											<input type="radio" name="monthOrWeek" id="week" value="week" onchange="enable()" <?=$default2?>> <?=$displaySet2?>
										</td>
									</tr>
									<tr>
										<td>מספר השבועות להצגה:</td>
										<td>
											<input type="text" size=1 style="text-align: center;" name="NumOfWeeks" ID="NumOfWeeks" value="<?=$NumOfWeeks?>" <?=$disable?>>
										</td>
									</tr>
									<tr>
                                        <td>מספר עדכונים מותר:</td>
                                        <td>
                                            <input type="text" size=1 name="NumberOfManualChangesInInternetOnline" ID="NumberOfManualChangesInInternetOnline" value="<?=$NumberOfManualChangesInInternetOnline?>" style="text-align: center;">
                                        </td>
                                    </tr>
									<tr>
										<td colspan=2 style="text-align:center;">
											<input type="Submit" name="submit" value="עדכן" style="width:100%; height:25px; padding:2px; font-size:15px; margin-top:5px;">
											<input type="HIDDEN" name="WrkId" value="<?=$WrkId?>">
											<input type="HIDDEN" name="IOLDisplayDepth" value="<?=$IOLDisplayDepth?>">
										</td>
									</tr>
							   </table>
							</td>
						</tr>
					</table>
				</form>
			</td>
        </tr>
</table>
</body>
</html>

<!DOCTYPE html>
<?php
	session_start();
	header('Content-Type: text/html; charset=CP1255');
	
	ob_start();
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	
	//include 'DBConect.php';
	
?>

<html>
<head>
	<title>psos - malram</title>
	<META charset="windows-1255">
	<META HTTP-EQUIV="expires" CONTENT="0">
	<META HTTP-EQUIV="pragma" CONTENT="no-cache">
	<script src="Funcs.js"></script>
	<script src="DatePickerLibraries/jquery-1.6.min.js"></script>
	<script src="DatePickerLibraries/jquery-ui.min.js"></script>
	<script src="DatePickerLibraries/jquery-ui-timepicker-addon.min.js"></script>
	<link href="css/psos.css" rel="stylesheet" type="text/css">
	<link href="css/login.css" rel="stylesheet" type="text/css">
	<link href="DatePickerLibraries/jquery-ui.min.css" rel="stylesheet" type="text/css">
	<link href="DatePickerLibraries/jquery-ui-timepicker-addon.min.css" rel="stylesheet" type="text/css">
	<script>
		// checking if current month is >= 03 then reports can be requested for current year
		// if current month < 03 then reports can be requested for current and previous year
		var d = new Date();
		var n = d.getMonth();
		var monthBack = "";
		if(n >= 2){
			monthBack = "-"+n+"m";
		}else{
			monthBack = "-"+(n+12)+"m";
		}
		$(function() {
			$('#month').datepicker( {
				minDate: monthBack,
				maxDate: "+0m",
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat: 'mm/yy',
				onClose: function(dateText, inst) { 
					var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
					var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
					$(this).datepicker('setDate', new Date(year, month, 1));
				}
			});
		});
	</script>
	<style>
		.ui-datepicker-calendar {
			display: none;
		}
	</style>
</head>

<body CLASS="background">
	<form name=iniform action="AskReport-sql.php" method="post">
		<table CLASS="page">
			<tr>
				<td colspan=2><?php include 'toplogo.php';?></td>
			</tr>
			<tr>
				<td CLASS="leftmenu">				
					<?php include 'leftmenu.php';?>
				</td>
				<td style="vertical-align:top; width:auto;">
					<table CLASS=iniFile dir="rtl">
						<tr>
							<td colspan=2 style="text-align:center;">
								<div>
								  <div dir="rtl" CLASS="fill2"><h2 style="margin:auto;">טופס הזמנת דו"ח נוכחות</h2></div>
								</div>
							</td>
						</tr>
						<tr>
							<td>הזמנת דו"ח נוכחות לחודש:</td>
							<td>
								<input type="text" name="month" id="month" size="10" required>
							</td>
						</tr>
						<tr>
							<td colspan=2 style="text-align:center;">
								<input type="Submit" name="submit" value="אישור" style="width:75%; height:30px; padding:2px; margin-top:5px;">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</form>
</body>
</html>

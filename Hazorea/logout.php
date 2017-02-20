<!DOCTYPE html>
<?php
	session_start();
	set_time_limit(20000);
	header('Content-Type: text/html; charset=CP1255');
	ob_start();
	include "funcs.php";
	log_out_current_user();
?>

<html>
	<head>
		<title>psos - malram</title>
		<meta charset="windows-1255">
		<link href="css/psos.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<table CLASS="page">
			<tr>
				<td colspan=2><?php include 'toplogo.php';?></td>
			</tr>
			<tr>
				<td CLASS="leftmenu">				
					<?php include 'leftmenu.php';?>
				</td>
				<td style="width:604; vertical-align:top;">
					<div>
						<div dir=rtl class="fill2">
							<h2>יצאת בהצלחה <br>המשך יום נעים!</h2>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>
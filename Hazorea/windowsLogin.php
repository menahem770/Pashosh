<!DOCTYPE html>
<?php
	session_start();
	header('Content-Type: text/html; charset=CP1255');
	ob_start();
	include 'Funcs.php';
	$un = substr(strstr($_SERVER['LOGON_USER'], 92),1);
	$error = "";
	if (isset($_GET['stat']) and $_GET['stat'] == 1){
		$error = "משתמש אינו קיים, אנא פנה למנהל המערכת";
	}
	else if(isset($_GET['stat']) and $_GET['stat'] == 3){
		$error = "יצאת מהמערכת, נא להכנס מחדש";
	}
?>

<html>
	<head>
		<title>psos - malram</title>
		<meta charset="windows-1255">
		<meta http-equiv="X-UA-Compatible" content="IE=11" />
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<link href="css/psos.css" rel="stylesheet" type="text/css">
		<link href="css/login.css" rel="stylesheet" type="text/css">
	</head>

	<body CLASS="background">
		
		<table CLASS="page">
			<tr>
				<td colspan=2><?php include 'toplogo.php';?></td>
			</tr>
			<tr>
				<td CLASS="leftmenu">				
					<?php include 'leftmenu.php';?>
				</td>
				<td style="vertical-align:top;">
					<div id="login">
						<h2 style="border-radius:8px 8px 0 0;">מלר"ם אונליין</h2>
						<form action="windowslogin1.php" method="post">
							<input id="username" name="username" value="<?=$un?>" type="text" disabled>
							<label for="username">:שם משתמש</label>
							<span><?=$error?></span>
							<input name="submit" type="submit" value=" כניסה ">
						</form>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>
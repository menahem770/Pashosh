<?php
	session_start();
	header('Content-Type: text/html; charset=CP1255');
	ob_start();
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	include 'DBConect.php';
	include 'SessionLoader.php';

	if(isset($_POST["username"])){
		$un = $_POST["username"];
		if ($un == ""){
			header("Location: windowslogin.php?stat=2");
			exit();
		}
		// checking login info
		$sql = "SELECT * FROM Select_Worker_Permitions WHERE UserCode = '$un'";
		$sth = $dbh->query($sql);
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		if (empty($result)){
			$sth = null;
			$dbh = null;
			header("Location: windowslogin.php?stat=1");
			exit();
		}else{
			$_SESSION['un'] = $un;
			$_SESSION['UWorkerNum'] = trim($result["WorkerNum"]);
			$_SESSION['UCardNumber'] = trim($result["CardNumber"]);
			$_SESSION['UWorkerName'] = $result["LastName"]." ".$result["FirstName"];
			$_SESSION['UWFixID'] = $result["WFixID"];
			$_SESSION['UDepMail'] = $result["DepMail"];
			$_SESSION['UIncldInMadan'] = $result['IncldInMaden'];
			$_SESSION['UIncldInTmhir'] = $result['IncldInTmhir'];
			$_SESSION['WorkerNum'] = $_SESSION['UWorkerNum'];
			$_SESSION['CardNumber'] = $_SESSION['UCardNumber'];
			$_SESSION['WorkerName'] = $_SESSION['UWorkerName'];
			$_SESSION['WFixID'] = $_SESSION['UWFixID'];
			$_SESSION['DepMail'] = $_SESSION['UDepMail'];
			$_SESSION['IncldInMadan'] = $_SESSION['UIncldInMadan'];
			$_SESSION['IncldInTmhir'] = $_SESSION['UIncldInTmhir'];
			$_SESSION['fDepNum'] = $result["AllowedFromDep"];
			$_SESSION['tDepNum'] = $result["AllowedToDep"];
			$_SESSION['DefaultDep'] = $result["TDprtmnt"];
			$_SESSION['DefaultJob'] = $result["TJob"];
			$_SESSION['UDefaultDep'] = $result["TDprtmnt"];
			$_SESSION['UDefaultJob'] = $result["TJob"];
			$_SESSION['UserIp'] = $_SERVER['REMOTE_ADDR'];
			if ($result["JobDescription"] == "DepartmentManager"){
				$_SESSION['mng'] = 1;
			}else if($result["JobDescription"] == "GeneralManager"){
				$_SESSION['mng'] = 2;
			}else{
				$_SESSION['mng'] = 0;
			}
			
			if ($_SESSION['mng'] == 1 or $_SESSION['mng'] == 2){
				header("Location: Workers.php");
				exit();
			}else{
				header("Location: SWWeeks.php");
				exit();
			}
		}
	}
?>
<?php
	session_start();
	include "funcs.php";
	log_out_current_user();
	session_start();

	$page = "";
	if (isset($_GET['stat']) and $_GET['stat'] == 3){
		$page = "?stat=3";
	}
	$_SESSION['ini_array'] = parse_ini_file("psos.ini",false,INI_SCANNER_TYPED);
	if ($_SESSION['ini_array']['loginType'] == "nameAndPassword"){
		header("Location: nameAndPasslogin.php".$page);
		session_regenerate_id(true);
		exit();
	}else if($_SESSION['ini_array']['loginType'] == "windowsCred"){
		header("Location: windowslogin.php".$page);
		session_regenerate_id(true);
		exit();
	}
	else{
		exit();
	}
?>
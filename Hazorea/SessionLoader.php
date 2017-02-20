<?php
	$sql = "SELECT ID,Parameter,NumOfWeeks FROM IniFile WHERE (ID = 1 OR ID = 68 OR (ID >= 201 AND ID <= 213))";
	$sth = $dbh->query($sql);
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$i = 0;
	while(isset($result,$result[$i])){
		if ($result[$i]['ID'] == 1){
			$_SESSION['WeeksPerPage'] = $result[$i]['NumOfWeeks'];
		}
		else if ($result[$i]['ID'] == 68){
			$_SESSION['NumberOfManualChangesIOL'] = $result[$i]['Parameter'];
		}
		else if ($result[$i]['ID'] == 201){
			$_SESSION['EmailSettings'] = array_slice($result, $i, 13);
		}

		$i++;
	}
	
	if(empty($_SESSION['WeeksPerPage'])){
		$_SESSION['WeeksPerPage'] = 8;
	}
	if(empty($_SESSION['NumberOfManualChangesIOL']) AND !is_numeric($_SESSION['NumberOfManualChangesIOL'])){
		$_SESSION['NumberOfManualChangesIOL'] = 2;
	}
	//---------------------------------------------------
	//max attendance length
	$sql = "SELECT TimeBetweenPress,MonthSDay FROM GenCalPr";
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	if (isset($result) and $result['TimeBetweenPress'] > 0){
		$_SESSION['attendMaxLength'] = substr($result['TimeBetweenPress'],11,2);
	}else{
		$_SESSION['attendMaxLength'] = "&";
	}
	//first day of the year
	if (isset($result) and $result['MonthSDay'] != 1){
		$_SESSION['FirstDayOfYear'] = $result['MonthSDay']."/12/";
	}else{
		$_SESSION['FirstDayOfYear'] = "01/01/";
	}
	//---------------------------------------------------
	//company include in tamhir/madan
	$sql = "SELECT TamExist,Maden FROM GenIntPr";
	$sth = $dbh->query($sql);
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	if($result){
		$_SESSION['companyIncldInTmhirMadan'] = ($result['TamExist'] AND $result['Maden']);
	}


?>
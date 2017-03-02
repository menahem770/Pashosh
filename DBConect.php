<?PHP

$vbCrLf = Chr(13) & Chr(10);

//---------------------------------------------------
// create connection to MDB file.
try {
	//type of DATABASE;
	//MS SQL: $dbh = new PDO("mssql:host=$host;dbname=$dbname, $user, $pass");
	//MySQL: $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
	//MS Access:
	//$dsn = "odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=D:\Data\k.hazorea\Data2017.mdb;charset=utf8";
  	$dsn = "odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=".$_SESSION['ini_array']['DBLocation'].";charset=utf8";
  	$dbh = new PDO($dsn);
	// set attribute:
	//PDO::ERRMODE_WARNING; warning continue execution.
	//PDO::ERRMODE_EXCEPTION; throws exception, stop.
	//PDO::ERRMODE_SILENT; no errors throwing. check errors in file.
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(Exception $e){
	echo "i'm sorry, something's wrong...";
	$date = new DateTime();
	file_put_contents('PDOErrors.txt', $date->format('d/m/Y H:i')." ".$e->getMessage().PHP_EOL, FILE_APPEND);
}
//---------------------------------------------------

//---------------------------------------------------
//  this section is for use with mdb files
//define("EnableFullReporting",true);
define("DateSep",             "'");
define("LeftDateSep",         "'");
define("RightDateSep",        "'");
define("dRightDateSep",       "'");
define("eRightDateSep",       "'");
define("sRightDateSep",       "'");
define("seRightDateSep",      "'");
define("LeftTimeSep",         "'");
define("RightTimeSep",        "'");

//------------------------------------------------------
//closing database connection
//$dbh = null;
//$sth = null;
//------------------------------------------------------
?>
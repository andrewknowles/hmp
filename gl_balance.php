<?php
include ('includes/db_Ifxlib.php');
include ('includes/db_Mylib.php');
require ('includes/table_lib.php');

set_time_limit ( 0 );

$myIniFile = parse_ini_file ( "includes/idb.ini", TRUE );

// create the MySQL connection
$Myconfig = new Myconfig( $myIniFile ['IDBMYSQL'] ['server'], $myIniFile ['IDBMYSQL'] ['login'], $myIniFile ['IDBMYSQL'] ['password'], $myIniFile ['IDBMYSQL'] ['database'], $myIniFile ['IDBMYSQL'] ['extension'], $myIniFile ['IDBMYSQL'] ['mysqlformat'] );
$Mydb = new Mydb($Myconfig);
$Mydb->openConnection();

// ----Loading from Excel sheet----
if (isset ( $_POST ['loadexcel'] )) {
//create file and header record
$myfile = 'C:\tmp\test.txt';
$fh = fopen($myfile, 'w') or die("can't open file");
$string = "00|HMUK|GL|GBP\r\n";
fwrite($fh, $string);

//Load the branch table
//several old branches can map to 1 current branch therefore old column must be unique
$branchlist = $Mydb->load_values('branch');

// create the Informix connection
$Ifxconfig = new Ifxconfig ( $myIniFile ['IDBIFX'] ['odbc'], $myIniFile ['IDBIFX'] ['login'], $myIniFile ['IDBIFX'] ['password'] );
$Ifxdb = new Ifxdb ( $Ifxconfig );
if (odbc_error ()) {
	echo "Informix connexion failed";
}


	$result = $Ifxdb->query1("select * from reg_gl");
	$balance = 0;
	while ( $row = odbc_fetch_array ( $result ) )
	{


	$string = "01|".$row ['bra_id']."|".$row ['dpr_id']."|COA|".$row ['gla_id']."|".trim($row ['desc'])."|".$row ['debit']."|".$row ['credit']."|".trim($row ['desc1'])."|0|0||||||||1|06-15-2014|06-15-2014|06|||".$row ['debit']."|".$row ['credit']."|1|1||||0|0\r\n";
	fwrite($fh, $string);
	if (strlen($row ['gca_id'])<>0)
	{
		$string1 = '';
		$string1 = "03|".$row ['debit']."|".$row ['credit']."|".trim($row ['gca_id'])."|".trim($row ['anal'])."|".trim($row ['desc'])."||||1\r\n";
		fwrite($fh, $string1);
	}
	$balance = $balance + $row ['debit'] - $row ['credit'];
	}
	
	if ($balance < 0){
		$balance = $balance * -1;
		$string = "01|".$row ['bra_id']."|100|COA|999998|Conv Balance|".$balance."|0|Conversion OB Bal|0|0||||||||1|06-15-2014|06-15-2014|06|||".$balance."|0|1|1||||0|0\r\n";
	} else {
	$string = "01|".$row ['bra_id']."|100|COA|999998|Conv Balance|0|".$balance."|Conversion OB Bal|0|0||||||||1|06-15-2014|06-15-2014|06|||0|".$balance."|1|1||||0|0\r\n";
	}
	
	fwrite($fh, $string);
	fclose($fh);
}

?>
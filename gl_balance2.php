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

// create the Informix connection
$Ifxconfig = new Ifxconfig ( $myIniFile ['IDBIFX'] ['odbc'], $myIniFile ['IDBIFX'] ['login'], $myIniFile ['IDBIFX'] ['password'] );
$Ifxdb = new Ifxdb ( $Ifxconfig );
if (odbc_error ()) {
	echo "Informix connexion failed";
}

$Ifxsql1 = $Ifxdb->query1("
		drop table if exists reg_gl;
		
create table reg_gl (
gla_id char(6),
bra_id char(4),
dpr_id char(3),
debit decimal(18,2),
credit decimal(18,2),
desc char(50),
desc1 char(50),
gca_id char(3),
anal char(10))");

// ----Loading from Excel sheet----
if (isset ( $_POST ['loadexcel'] )) {


//Load the branch table
//several old branches can map to 1 current branch therefore old column must be unique
$branchlist = $Mydb->load_values('branch');

// create the Informix connection
$Ifxconfig = new Ifxconfig ( $myIniFile ['IDBIFX'] ['odbc'], $myIniFile ['IDBIFX'] ['login'], $myIniFile ['IDBIFX'] ['password'] );
$Ifxdb = new Ifxdb ( $Ifxconfig );
if (odbc_error ()) {
	echo "Informix connexion failed";
}

	$rows = 0;
	$excelFile = realpath ( $_POST ['fileexcel'] );
	$excelSheet = $_POST ['sheetexcel'];
	$excelDir = dirname ( $excelFile );
	$connection = odbc_connect ( "Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=$excelFile;DefaultDir=$excelDir", '', '' );
	$result = odbc_exec ( $connection, "select * from [" . $excelSheet . "$]" );
	$balance = 0;
	while ( $row = odbc_fetch_array ( $result ) )
	{
//		echo strlen(trim($row ['Irium_GL_Code']));
		if (strlen(trim($row ['Irium_GL_Code']<>0)))
		{
		// get branch code
/*		$branch='1HEB';
		foreach ($branchlist as $int)
		{
			if ($int['old'] == $row ['Irium_Depot'])
			{
				$branch = $int['new'];
				break;
			}
		} 
		
		// get department code
		$dept = '100';
		if (strlen($row ['Irium_Depart']) <> 0 )
		{
			$dept = trim($row ['Irium_Depart']);
		} */
		
	$account = round($row ['Irium_GL_Code'],0);
//	$debit = $row ['Dr_GBP'] * 100;
//	$credit = $row ['Cr_GBP'] * 100;
	$debit = $row ['DR_GBP1'];
	$credit = $row ['CR_GBP1'];
	$desc = trim($row ['Description']);
	$desc1 = trim($row ['Description1']);
	
	$Ifxsql1 = $Ifxdb->query1("insert into reg_gl(
		        gla_id,
				bra_id,
		        dpr_id,
				debit,
				credit,
				desc,
				desc1
				)
				values (
				'" . $account . "',
				'" . trim($row ['Irium_Depot']) . "',
				'" . trim($row ['Irium_Depart']) . "',
				'" . $debit . "',
				'" . $credit . "',
				'" . $desc . "',
				'" . $desc1 . "'
				)");
	}

	

	}

}

odbc_close($connection);

?>
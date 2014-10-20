<?php
include ('includes/db_Ifxlib.php');
require ('includes/table_lib.php');

set_time_limit ( 0 );

$myIniFile = parse_ini_file ( "includes/idb.ini", TRUE );

// create the Informix connection
$Ifxconfig = new Ifxconfig ( $myIniFile ['IDBIFX'] ['odbc'], $myIniFile ['IDBIFX'] ['login'], $myIniFile ['IDBIFX'] ['password'] );
$Ifxdb = new Ifxdb ( $Ifxconfig );
if (odbc_error ()) {
	echo "Informix connexion failed";
}

// ----Loading from Excel sheet----
if (isset ( $_POST ['loadexcel'] )) {

	$rows = 0;
	$excelFile = realpath ( $_POST ['fileexcel'] );
	$excelSheet = $_POST ['sheetexcel'];
	$excelDir = dirname ( $excelFile );
	$connection = odbc_connect ( "Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=$excelFile;DefaultDir=$excelDir", '', '' );
	$result = odbc_exec ( $connection, "select * from [" . $excelSheet . "$]" );
	while ( $row = odbc_fetch_array ( $result ) )
	{
		
	$actno = round($row ['gla_id'],0);
	$matchdesc = strtoupper($row ['gld_desc']);
	$lng = round($row ['lng_id'],0);

		$Ifxsql1 = $Ifxdb->query1 ("
		insert into gla (
		gla_id,
		gca_id,
		usr_id,
		gla_eoyear,
		gla_cash_acct,
		gla_third,
		gla_active,
		gla_d_cre,
		gla_d_upd,
		gla_type,
		gla_balance,
		gla_category,
		gla_pageline,
		gla_analytic,
		gla_regroup,
		gla_duedate,
		gla_valuedate,
		gla_qty,
		gla_efo	       
		) values (
		'" . $actno . "',
		'" . $row ['gca_id'] . "',
		'" . $row ['usr_id'] . "',
		'" . $row ['gla_eoyear'] . "',
		'" . $row ['gla_cash_acct'] . "',
		'" . $row ['gla_third'] . "',
		'" . $row ['gla_active'] . "',
		'" . $row ['gla_d_cre'] . "',
		'" . $row ['gla_d_upd'] . "',
		'" . $row ['gla_type'] . "',
		'" . $row ['gla_balance'] . "',
		'" . $row ['gla_category'] . "',
		'" . $row ['gla_pageline'] . "',
		'" . $row ['gla_analytic'] . "',
		'" . $row ['gla_regroup'] . "',
		'" . $row ['gla_duedate'] . "',
		'" . $row ['gla_valuedate'] . "',
		'" . $row ['gla_qty'] . "',
		'" . $row ['gla_efo'] . "'
		)");
		
		$Ifxsql2 = $Ifxdb->query1 ("
		insert into glc (
		gca_id,
		gla_id,
		cpy_id,
		glc_d_cre,
		glc_mat_letter
		) values (
		'" . $row ['gca_id'] . "',
		'" . $actno . "',
		'" . $row ['cpy_id'] . "',
		'" . $row ['gla_d_cre'] . "',
		'" . $row ['glc_mat_letter'] . "'
		)");
		
		$Ifxsql3 = $Ifxdb->query1 ("
		insert into gld (
		gca_id,
		gla_id,
		lng_id,
		gld_desc,
		gld_matchname
		) values (
		'" . $row ['gca_id'] . "',
		'" . $actno . "',
		'" . $lng . "',
		'" . $row ['gld_desc'] . "',
		'" . $matchdesc . "'
		)");
				
		$rows ++;
	}
	echo $rows . '  Rows loaded</BR>';
	


}



?>
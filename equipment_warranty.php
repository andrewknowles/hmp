<?php
include ('includes/db_Ifxlib.php');
include ('includes/db_Mylib.php');
require ('includes/table_lib.php');

set_time_limit ( 0 );

$myIniFile = parse_ini_file ( "includes/idb.ini", TRUE );

// create the Informix connection
$Ifxconfig = new Ifxconfig ( $myIniFile ['IDBIFX'] ['odbc'], $myIniFile ['IDBIFX'] ['login'], $myIniFile ['IDBIFX'] ['password'] );
$Ifxdb = new Ifxdb ( $Ifxconfig );
if (odbc_error ()) 
	{
		echo "Informix connexion failed";
	}

if (isset ( $_POST ['reset'] ) && $_POST ['confirm'] == 'confirm') 
	{
		$Ifxsql1 = $Ifxdb->query1 ("
		drop table if exists reg_ewr;

		create table reg_ewr
		(
		equ_id_dealer        char(20),
		ewr_d_start          char(20),
		ewr_d_exp            char(20),
		ewr_std              char(10),
		ewr_desc             char(50),
		ewr_counter_value    char(20),
		ewr_qtytype          char(20),
		ewr_maximum          char(20),
		ewr_days             char(20),
		ewr_global_idx       char(50),
		ewr_free1            char(50),
		ewr_free2            char(50),
		ewr_free3            char(50),
		ewr_free4            char(50)
		)" );
	
		echo 'reg_ewr created';
		$Ifxdb->closeConnection ();
		unset ( $_POST );
	}

// ----Loading from Excel sheet----
if (isset ( $_POST ['loadexcel'] )) 
	{
	$rows = 0;
	$excelFile = realpath ( $_POST ['fileexcel'] );
	$excelSheet = $_POST ['sheetexcel'];
	$excelDir = dirname ( $excelFile );
	$connection = odbc_connect ( "Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=$excelFile;DefaultDir=$excelDir", '', '' );
	$result = odbc_exec ( $connection, "select * from [" . $excelSheet . "$]" );

	while ( $row = odbc_fetch_array ( $result ) )
		{
//convert datetime to date
		$warstart = date("m/d/Y",strtotime($row ['MW_START_DATE_1']) );
		$warend = date("m/d/Y",strtotime($row ['MW_EXPIRE_DATE_1']) );
//		$comment = $row ['MW_DURATION_YRS_1'].' yr - '.$row ['MW_COMMENTS_1'];
		
		$Ifxsql1 = $Ifxdb->query1 ( 
		"insert into reg_ewr(
       	equ_id_dealer,
		ewr_d_start,
		ewr_d_exp,
		ewr_std,
		ewr_desc,
		ewr_counter_value,
		ewr_qtytype
				        
		) values(

		'" . $row ['EM_EQUIPMENT'] . "',
		'" . $warstart . "',
		'" . $warend . "',			
		'STD2',
		'-',	
		0,
		0
		)" );
		$rows ++;
		}
	echo $rows . '  Rows loaded</BR>';

	unset ( $_POST );

	}
$Ifxdb->closeConnection();

?>
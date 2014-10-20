<?php
include('includes/db_Ifxlib.php');
require('includes/table_lib.php');

set_time_limit(0);

$myIniFile = parse_ini_file("includes/idb.ini", TRUE);

$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb     = new Ifxdb($Ifxconfig);

if (isset($_POST['reset']) && $_POST['confirm'] == 'confirm')
  {
    $Ifxsql1 = $Ifxdb->query1("
	   
    drop table if exists reg_csc_full;
    
    create table reg_csc_full (
    
    tad_cpy_name		char(50),
    csc_id				char(10))");
    
    echo 'reg_csc_full created';
    $Ifxdb->closeConnection();
    unset($_POST);
  }


if (isset($_POST['loadexcel']))
  {
    $rows        = 0;
    $loaded_rows = 0;
    $excelFile   = $_POST['fileexcel'];
    $excelSheet  = $_POST['sheetexcel'];
    $excelDir    = dirname($excelFile);
    $drv         = "Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=" . $excelFile . ";DefaultDir=" . $excelDir;
    $connection  = odbc_connect($drv, 'informix', 'informix');
//    $result      = odbc_exec($connection, "select * from [Sheet11$]");
    
    $dd          = odbc_connect("HMPFULLCUST", "user", "password");
    $result      = odbc_exec($dd, "select * from [Names]");
    
    while ($row = odbc_fetch_array($result))
      { 

      	$custno = round($row['NA_CUSTOMER'], 0);
      	
      	// customer name - strip bad characters
      	$custname = trim($row['NA_NAME']);
      	$custname = str_replace("'", "", $custname);
      	$custname = str_replace("*", "-", $custname);
      	$custname = ucwords(strtolower($custname));
      	
      	
            $Ifxsql1 = $Ifxdb->query1("insert into reg_csc_full(		
		        tad_cpy_name,
		        csc_id
				)										
				values (
				'" . $custname . "',
				'" . $custno . "'
				)");        
      }
 		
    $Ifxdb->closeConnection();
    unset($_POST);
  }

?>
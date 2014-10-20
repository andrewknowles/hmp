<?php
//this program rebuilds the MySQL customer table to refresh the mapping data
//first insert all csc records to set mapping old = new
//second insert customer number changes from the latest Excel data
include('includes/db_Ifxlib.php');
include('includes/db_Mylib.php');
require('includes/table_lib.php');

set_time_limit(0);

$myIniFile = parse_ini_file("includes/idb.ini", TRUE);

// create the MySQL connection
$Myconfig = new Myconfig($myIniFile['IDBMYSQL']['server'], $myIniFile['IDBMYSQL']['login'], $myIniFile['IDBMYSQL']['password'], $myIniFile['IDBMYSQL']['database'], $myIniFile['IDBMYSQL']['extension'], $myIniFile['IDBMYSQL']['mysqlformat']);
$Mydb     = new Mydb($Myconfig);
$Mydb->openConnection();
//empty customer table
$Mysql = $Mydb->query("truncate table customer");

//read in the data from latest Excel sheet where column NA_CARRIER = 1
//insert old customer number (column NA_CUSTOMER) in old column and Plant_Sales_Merges_To in new column
//if Plant_Sales_Merges_To is null fill the new column with NA_CUSTOMER
$excelFile  = $_POST['fileexcel'];
$excelSheet = $_POST['sheetexcel'];
$excelDir   = dirname($excelFile);
$drv        = "Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=" . $excelFile . ";DefaultDir=" . $excelDir;
$connection = odbc_connect($drv, 'informix', 'informix');
$result     = odbc_exec($connection, "select * from [" . $excelSheet . "$]");
while ($row = odbc_fetch_array($result))
  {
    if ($row['NA_CARRIER'] == 1)
      {
      	if (strlen($row['Plant_Sales_Merges_To']) > 0) {
      		$newcust = round($row['Plant_Sales_Merges_To'], 0);
      	} else {
      		$newcust = round($row['NA_CUSTOMER'], 0); 
      	}
        $Mydb->insert_values('customer', round($row['NA_CUSTOMER'], 0), $newcust, 2);
      }
  }

echo 'Records updated  '.$updated;  
?>
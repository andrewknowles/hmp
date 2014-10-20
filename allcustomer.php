<?php
include('includes/db_Ifxlib.php');
require('includes/table_lib.php');
include ('includes/db_Mylib.php');

set_time_limit(0);

$myIniFile = parse_ini_file("includes/idb.ini", TRUE);
$fileout = fopen("c:/tmp/allcust.txt", "a");

if (isset($_POST['loadexcel']))
  {
    $rows        = 0;
    $loaded_rows = 0;
    $excelFile   = $_POST['fileexcel'];
    $excelSheet  = $_POST['sheetexcel'];
    $excelDir    = dirname($excelFile);
    $drv         = "Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=" . $excelFile . ";DefaultDir=" . $excelDir;
    $connection  = odbc_connect($drv, 'informix', 'informix');
    $result      = odbc_exec($connection, "select * from [" . $excelSheet . "$]");
    while ($row = odbc_fetch_array($result))
      {
      	if (strlen($row['Plant_Sales_Merges_To']) < 1)
      	{
      		$merge = '0';
      	} else {
      		$merge = $row['Plant_Sales_Merges_To'];
      	}
      	$outlinet = "insert into ak values ('".$row['NA_CUSTOMER']."', '".$merge."');";
      	fwrite($fileout, $outlinet."\r\n");

      }	

    unset($_POST);
  }

?>
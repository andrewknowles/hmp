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
//$Mysql = $Mydb->query("truncate table customer");

//create Informix connection
$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb     = new Ifxdb($Ifxconfig);
//select all customer records except internal and default customer £XXXX£
//insert these values in both old and new columns of customer table
//set flag to 1 to indicate they come directly from csc table
//$roxcount1 is the number of records inserted
/*$result2 = $Ifxdb->query1("select csc_id from csc where csc_accrec = 1 and csc_id <> '£XXXX£'");
$rowcount1    = 0;
while ($row = odbc_fetch_array($result2))
  {
  	$cust = odbc_result($result2, 1);
  	if (strlen($cust)<6)
  	{
  		$cust = '0'.$cust;
  	}
  	$cust = (string)$cust;
  	echo $cust.'</BR>';
    $Mydb->insert_values('customer', $cust, $cust, 1);
    $rowcount1++;
  }
  
  exit;
//check number of rows in customer
//exit;
$Mysql = $Mydb->query("select * from customer");
$rowcount2  = $Mydb->countRows($Mysql); */
//$Mydb->freeResult($Mysql);
//read in the data from latest Excel sheet where column Plant_Sales_Merges_To is filled
//insert old customer number (column NA_CUSTOMER) in old column and Plant_Sales_Merges_To in new column, set flag to 2
$fileout = fopen("c:/tmp/custchange1.txt", "a");
$excelFile  = $_POST['fileexcel'];
$excelSheet = $_POST['sheetexcel'];
$excelDir   = dirname($excelFile);
$drv        = "Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=" . $excelFile . ";DefaultDir=" . $excelDir;
//$connection = odbc_connect($drv, 'informix', 'informix');
//$result     = odbc_exec($connection, "select * from [" . $excelSheet . "$]");
$dd          = odbc_connect("HMPEXCELAP", "user", "password");
$result      = odbc_exec($dd, "select * from [AR_Names_2$]");
while ($row = odbc_fetch_array($result))
  {
    if (strlen($row['Plant_Sales_Merges_To']) > 0)
      {
      	$custold = round($row['NA_CUSTOMER'], 0);
      	if (strlen($custold)<6)
      	{
      		$custold = '0'.$custold;
      	}
      	$custnew = round($row['Plant_Sales_Merges_To'], 0);
      	if (strlen($custnew)<6)
      	{
      		$custnew = '0'.$custnew;
      	}
//        $Mydb->insert_values('customer', $custold, $custnew, 2);
$outlinet = 'insert into customer values ('.$custold.', '.$custnew.',2);';
        fwrite($fileout, $outlinet."\r\n");
      }
  }
  exit;
//count records inserted into customer from this process ie where flag = 2
$Mysql = $Mydb->query("select * from customer where flag =2");
$rowcount3  = $Mydb->countRows($Mysql);
//check to make sure no duplicates in customer.old
$Mysql = $Mydb->query("SELECT old, count(*) from customer group by old having count(*) <> 1");
$rowcount4  = $Mydb->countRows($Mysql);
$Mydb->freeResult($Mysql);

//now check that all records with flag = 2 exist in the csc table
$errorcount = 0;
$Mysql      = $Mydb->query("select new from customer where flag = 2");
$errors     = array();
foreach ($Mysql as $int)
  {
    $ret = $Ifxdb->query1("select count(*) from csc where csc_id = '" . $int['new'] . "'");
    while ($row = odbc_fetch_array($ret))
        $xxx = odbc_result($ret, 1);
    if ($xxx != 1)
      {
        $errorcount++;
        array_push($errors, $int['new']);
      }
  }
//$rowcount1 and $rowcount2 should be equal
echo 'Direct insersion from csc table  ' . $rowcount1 . '  rows in csc and   ' . $rowcount2.'  rows in customer';
echo '</BR>';
echo 'Records inserted from Excel data  ' . $rowcount3;
echo '</BR>';
echo 'Records with duplicates  ' . $rowcount4;
echo '</BR></BR>';
echo 'Inserted records not found in csc  ' . $errorcount;
echo '</BR></BR>';
//loop through the errors to display
$updated = 0;
foreach ($errors as $val)
  {
    echo $val . '</BR>';
    $Mydb->update_values('customer','new','£XXXX£',$val);
//    $query = "update customer set new = '£XXXX£' where new = '$val'";
//    echo $query;
//$Mysql      =     $Mydb->query("update customer set new = '£XXXX£' where new = '$val'");
    $updated++;
  }
echo 'Records updated  '.$updated;

$errorcount = 0;
$Mysql      = $Mydb->query("select new from customer where flag = 2");
$errors     = array();
foreach ($Mysql as $int)
{
	$ret = $Ifxdb->query1("select count(*) from csc where csc_id = '" . $int['new'] . "'");
	while ($row = odbc_fetch_array($ret))
		$xxx = odbc_result($ret, 1);
	if ($xxx != 1)
	{
		$errorcount++;
		array_push($errors, $int['new']);
	}
}
echo 'Remaining errors' . $errorcount;  
odbc_close($dd);
?>



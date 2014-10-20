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
		drop table if exists tarifbase;

		create table tarifbase(
		pmf_id char(4),
		pro_id char(20),
		action char(1),
		creation char(8),
		description char(30),
		price_group char(2),
		returnable char(1),
		replaced char(1),
		pack char(5),
		net_weight char(9),
		gross_weight char(9),
		length char(5),
		width char(5),
		height char(5),
		unit char(2);
		hazard char(1);
		made_as_order char(1);
		activity char(1);
		reman char(1);
		hose char(1)
		");
	 
		echo 'tarifbase created';
		$Ifxdb->closeConnection();
		unset($_POST);
}
		
if (isset($_POST['loadexcel']))
  {
  	$excelFile   = $_POST['fileexcel'];
    $myfile = fopen("$excelFile", "r") or die("Unable to open file!");
	while(!feof($myfile)) {	
		$line = fgets($myfile);
		$linetype = substr($line,5,2);
		if ($linetype == '01' || $linetype == '02')
		{
		$nextline = fgets($myfile);
		$nextlinetype = substr($nextline,5,2);
		if ($linetype <> $nextlinetype && $nextlinetype == '02')
		{
		$mfr = substr($line,0,3);
		$part = substr($line,4,7);
		$price = substr($nextline,11,13);
		} else {
			$mfr = substr($line,0,4);
			$part = substr($line,4,7);
			$price = 0;
		}


		
		$Ifxsql1 = $Ifxdb->query1("insert into tarif(
		        pmf_id,
		        pro_id,
				ppd_list
				)
				values (
				'" . $mfr . "',
				'" . $part . "',
				'" . $price . "'
				)");
	}
	}
	fclose($myfile);
      	
      	
//           $Ifxsql1 = $Ifxdb->query1("insert into reg_csc_full(		
//		        tad_cpy_name,
//		        csc_id
//				)										
//				values (
//				'" . $custname . "',
//				'" . $custno . "'
//				)");        
//      }
 		
//    $Ifxdb->closeConnection();
//    unset($_POST);
  }

?>
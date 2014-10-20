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
		drop table if exists reg_price;

		create table reg_price(
		pmf_id char(4),
		pas_pmf_id char(4),
		pro_id char(20),
		pas_pro_id char(20),
		ppd_list char(20),
		pdi_desc char(50),
		cry_id char(3),
		dup  int);
		");
	 
		echo 'reg_price & reg_price_dup created';
		$Ifxdb->closeConnection();
		unset($_POST);
}
		
if (isset($_POST['loadexcel']))
  {
  	$excelFile   = $_POST['fileexcel'];
    $myfile = fopen("$excelFile", "r") or die("Unable to open file!");
	while(!feof($myfile)) {
		$line = fgets($myfile);
		$mfr = trim(substr($line,0,4));
		$part = trim(substr($line,4,15));
		$pasmfr = trim(substr($line,95,4));
		$paspart = trim(substr($line,80,15));
		$price = substr($line,64,11);
		$country = substr($line,77,3); 
		$partdesc = trim(substr($line,26,15));
		$partdesc = str_replace("'", " ", $partdesc);
		$partdesc = str_replace('"', ' IN. ', $partdesc);
		$partdesc = str_replace('”', ' IN. ', $partdesc);
		$partdesc = str_replace("#", " ", $partdesc);
		$partdesc = str_replace("$", " ", $partdesc);
		$partdesc = str_replace(";", " ", $partdesc);
		$partdesc = str_replace("|", " ", $partdesc);
		
		$Ifxsql1 = $Ifxdb->query1("insert into reg_price(
		        pmf_id,
				pas_pmf_id,
		        pro_id,
				pas_pro_id,
				ppd_list,
				pdi_desc,
				cry_id
				)
				values (
				'" . $mfr . "',
				'" . $pasmfr . "',
				'" . $part . "',
				'" . $paspart . "',
				'" . $price . "',
				'" . $partdesc . "',
				'" . $country . "'
				)");
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
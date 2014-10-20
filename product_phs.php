<?php
//Product history load for HCMUK
//A. Knowles 06/2014
include('includes/db_Ifxlib.php');
require('includes/table_lib.php');
include('includes/db_Mylib.php');

set_time_limit(0);

$myIniFile = parse_ini_file("includes/idb.ini", TRUE);
// create the MySQL connection
$Myconfig  = new Myconfig($myIniFile['IDBMYSQL']['server'], $myIniFile['IDBMYSQL']['login'], $myIniFile['IDBMYSQL']['password'], $myIniFile['IDBMYSQL']['database'], $myIniFile['IDBMYSQL']['extension'], $myIniFile['IDBMYSQL']['mysqlformat']);
$Mydb      = new Mydb($Myconfig);
$Mydb->openConnection();

//Load the branch table
//several old branches can map to 1 current branch therefore old column must be unique
$branchlist = $Mydb->load_values('branch');

$myIniFile = parse_ini_file("includes/idb.ini", TRUE);
$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb     = new Ifxdb($Ifxconfig);

if (isset($_POST['reset']) && $_POST['confirm'] == 'confirm')
  {
    $Ifxsql1 = $Ifxdb->query1("
drop table if exists reg_phs ;

create table reg_phs
(
odh_id 			char(20),
ofh_id 			char(20),
ofl_id 			char(20),
oft_id 			char(10),
oih_id 			char(20),
opl_id 			char(20),
phs_cost 		char(20),
phs_counter1 		char(20),
phs_cpy_id 		char(10),
phs_csc_id 		char(10),
phs_cur_id_cur 		char(10),
phs_cur_id_local 	char(10),
phs_d 			char(20),
phs_doc_id 		char(20),
phs_doc_id_seq 		char(10),
phs_equ_id_dealer 	char(20),
phs_id 			char(20),
phs_matchname 		char(50),
phs_movement 		char(10),
phs_odh_srh_doc 	char(20),
phs_odh_srh_seq 	char(10),
phs_odometer1 		char(20),
phs_ofh_sfh_doc 	char(20),
phs_oih_sih_doc 	char(20),
phs_oih_sih_seq 	char(10),
phs_origin 		char(10),
phs_plate 		char(20),
phs_pmf_id 		char(10),
phs_pnet_cur 		char(20),
phs_pnet_euro 		char(20),
phs_pnet_local 		char(20),
phs_pro_desc 		char(50),
phs_pro_id 		char(30),
phs_proc_id 		char(10),
phs_psk_bra_id 		char(10),
phs_psk_dpr_id 		char(10),
phs_psk_id 		char(20),
phs_psk_stktype 	char(10),
phs_q 			char(20),
phs_serialnb 		char(30),
phs_spp_id 		char(10),
phs_usr_id 		char(10),
sfh_id 			char(20),
sft_id 			char(10),
sih_id 			char(20),
srh_id 			char(20),
srl_id 			char(20),
phs_process		char(5),
regphs_free1		char(50),
regphs_free2		char(50),
regphs_free3		char(50),
regphs_free4		char(50),
regphs_free5		char(50))");
    
    echo 'reg_phs created';
    $Ifxdb->closeConnection();
    unset($_POST);
  }

if (isset($_POST['loadexcel']))
  {
    $rows    = 0;
    $result2 = $Ifxdb->query1("select reg_pro.pmf_id, reg_pro.pro_id, reg_pro.pdi_desc, reg_pro_total.branch, reg_pro_total.onhand, 
    reg_pro_total.avgcost from reg_pro, reg_pro_total
	where reg_pro.pro_id = reg_pro_total.part and reg_pro.pmf_id = reg_pro_total.newmfr
	and reg_pro_total.stock = 1 and reg_pro_total.onhand <> 0 and reg_pro_total.branch in (1,2,3,4,5,6,7,9,11,12,14)");
    while ($row = odbc_fetch_array($result2))
      {
        // get branch code
        $branch = '£XXXX£';
        foreach ($branchlist as $int)
          {
            
            if ($int['old'] == trim(odbc_result($result2, 4)))
              {
                $branch = $int['new'];
                break;
              }
          }
        
		$mvtdate = '2014-09-30 00:00:00';
        
        $Ifxsql1 = $Ifxdb->query1("insert into reg_phs(
        phs_cost,
        phs_cpy_id,
        phs_cur_id_local,
        phs_d,
        phs_doc_id,
        phs_id,
        phs_movement,
        phs_origin,
        phs_pmf_id,
        phs_pro_desc,
        phs_pro_id,
        phs_psk_bra_id,
        phs_psk_dpr_id,
        phs_psk_stktype,
        phs_q,
        phs_usr_id      
		) values (
       	'" . round(odbc_result($result2, 6),2) . "',
        'HMUK',
        'GBP',
        '" . $mvtdate . "',
        'CONVERSION',
        0,
        '04',
        '90',
        '" . trim(odbc_result($result2, 1)) . "',
        '" . trim(odbc_result($result2, 3)) . "',	
        '" . trim(odbc_result($result2, 2)) . "',
        '" . $branch . "',
        '300',
        0,
        '" . odbc_result($result2, 5) . "',
        '0000'      				     		
		)");
        $rows++;
        
      }
    echo $rows . '  Rows loaded';
    $Ifxdb->closeConnection();
    unset($_POST);
        
  }
?>
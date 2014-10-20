<?php
//Product stock load for HCMUK
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
	drop table if exists reg_psk;

	create table reg_psk
	(
	bra_id 			char(50),
	dpr_id 			char(50),
	pmf_id 			char(50),
	pro_id 			char(50),
	psk_d_cre 		char(50),
	psk_d_in 		char(50),
	psk_d_in_int 		char(50),
	psk_d_inv_last 		char(50),
	psk_d_inv_prv 		char(50),
	psk_d_out 		char(50),
	psk_d_out_int 		char(50),
	psk_d_upd 		char(50),
	psk_d_last_ord 		char(50),
	psk_id 			char(50),
	psk_inv_last_d 		char(50),
	psk_inv_last_p 		char(50),
	psk_inv_last_q 		char(50),
	psk_inv_prv_d 		char(50),
	psk_inv_prv_p 		char(50),
	psk_inv_prv_q 		char(50),
	psk_lock 		char(50),
	psk_maxi 		char(50),
	psk_mini 		char(50),
	psk_q 			decimal(10,2),
	psk_q_call 		char(50),
	psk_q_cboc 		char(50),
	psk_q_cwait 		char(50),
	psk_q_lost 		char(50),
	psk_q_mkip 		char(50),
	psk_q_rec 		char(50),
	psk_q_sboc 		char(50),
	psk_q_to_del 		char(50),
	psk_q_to_pic 		char(50),
	psk_q_trs 		char(50),
	psk_stktype 		char(50),
	regpsk_free1		char(50),
	regpsk_free2		char(50),
	regpsk_free3		char(50),
	regpsk_free4		char(50),
	regpsk_free5		char(50)			
	)");
    
    echo 'reg_psk created';
    $Ifxdb->closeConnection();
    unset($_POST);
  }



if (isset($_POST['loadexcel']))
  {
    $rows    = 0;
    $result2 = $Ifxdb->query1("select reg_pro.pmf_id, reg_pro.pro_id, reg_pro_total.branch, reg_pro_total.lastrec, 
    reg_pro_total.lastact, reg_pro_total.lastsale,
	reg_pro_total.bin, reg_pro_total.onhand from reg_pro, reg_pro_total
	where reg_pro.pro_id = reg_pro_total.part and reg_pro.regpro_free2 = reg_pro_total.newmfr
	and reg_pro_total.stock = 1 and reg_pro_total.branch in (1,2,3,4,5,6,7,9,11,12,14)");
    while ($row = odbc_fetch_array($result2))
      {
        
// get branch code
        $branch = '£XXXX£';
        foreach ($branchlist as $int)
          {
            
            if ($int['old'] == trim(odbc_result($result2, 3)))
              {
                $branch = $int['new'];
                break;
              }
          }
        
//Manage missing bin
//        if (strlen(trim(odbc_result($result2, 7)) == 0) || (trim(odbc_result($result2, 7)) == ''))
//          {
//            $bin = 'No Bin';
//          }
//        else
//          {
            $bin = trim(odbc_result($result2, 7));
//          }
        
//Manage missing dates
        if (strlen(trim(odbc_result($result2, 4))) == 0)
          {
            $lastrec = '1900-01-01 00:00:00';
          }
        else
          {
            $lastrec = trim(odbc_result($result2, 4));
          }
        
        if (strlen(trim(odbc_result($result2, 5))) == 0)
          {
            $lastact = '1900-01-01 00:00:00';
          }
        else
          {
            $lastact = trim(odbc_result($result2, 5));
          }
        
        if (strlen(trim(odbc_result($result2, 6))) == 0)
          {
            $lastsale = '1900-01-01 00:00:00';
          }
        else
          {
            $lastsale = trim(odbc_result($result2, 6));
          }
        
        $Ifxsql1 = $Ifxdb->query1("insert into reg_psk(
        bra_id,
        dpr_id,
        pmf_id,
        pro_id,
        psk_d_cre,
        psk_d_in,
        psk_d_out,
        psk_d_upd,
        psk_id,
        psk_q,
        psk_q_call,
        psk_q_cboc,
        psk_q_cwait,
        psk_q_lost,
        psk_q_mkip,
        psk_q_rec,
        psk_q_sboc,
        psk_q_to_del,
        psk_q_to_pic,
        psk_q_trs,
        psk_stktype        
		) values (
     	'" . $branch . "',
        '300',
        '" . trim(odbc_result($result2, 1)) . "',
       	'" . trim(odbc_result($result2, 2)) . "',
        '" . $lastrec . "',
        '" . $lastrec . "',
        '" . $lastsale . "',		
        '" . $lastact . "',
        '" . $bin . "',
        '" . trim(odbc_result($result2, 8)) . "',
       	0,
        0,
        0,
        0,
        0,
        0,
        0,
        0,
        0,
        0,
        0     		
		)");
        $rows++;
        
      }
    echo $rows . '  Rows loaded';
    $Ifxdb->closeConnection();
    unset($_POST);
    
  }

?>
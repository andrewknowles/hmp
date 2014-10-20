<?php
//Product data load for HCMUK
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
	drop table if exists reg_pdc;

	create table reg_pdc
    (
    bra_id			char(10),
    pdc_amt_del		char(20),
    pdc_amt_del_oth		char(20),
    pdc_amt_exc		char(20),
    pdc_amt_lost		char(20),
    pdc_amt_ord		char(20),
    pdc_amt_req		char(20),
    pdc_amt_ret		char(20),
    pdc_amt_cpy		char(20),
    pdc_amt_trf		char(20),
    pdc_cos_del		char(20),
    pdc_cos_del_oth		char(20),
    pdc_cos_exc		char(20),
    pdc_cos_lost		char(20),
    pdc_cos_ord		char(20),
    pdc_cos_req		char(20),
    pdc_cos_ret		char(20),
    pdc_cos_cpy		char(20),
    pdc_cos_trf		char(20),
    pdc_dem_del		char(10),
    pdc_dem_del_oth		char(10),
    pdc_dem_exc		char(10),
    pdc_dem_lost		char(10),
    pdc_dem_ord		char(10),
    pdc_dem_req		char(10),
    pdc_dem_ret		char(10),
    pdc_dem_cpy		char(10),
    pdc_dem_trf		char(10),
    pdc_period		char(10),
    pdc_q_del		char(20),
    pdc_q_del_oth		char(20),
    pdc_q_exc		char(20),
    pdc_q_lost		char(20),
    pdc_q_ord		char(20),
    pdc_q_req		char(20),
    pdc_q_ret		char(20),
    pdc_q_cpy		char(20),
    pdc_q_trf		char(20),
    pdc_yyyy		char(10),
    pdc_yyyymm		char(10),
    pmf_id			char(10),
    pro_id			char(30),
    pdc_proc_id		char(10),
    regpdc_free1		char(50),
    regpdc_free2		char(50),
    regpdc_free3		char(50),
    regpdc_free4		char(50),
    regpdc_free5		char(50))");
    
    echo 'reg_pdc created';
    $Ifxdb->closeConnection();
    unset($_POST);
  }



if (isset($_POST['loadexcel']))
  {
    $rows    = 0;
    $result2 = $Ifxdb->query1("select reg_pro.pmf_id, reg_pro.pro_id, reg_pro_total.branch,
    demand1, demand2, demand3, demand4, demand5, demand6, demand7, demand8, demand9, demand10, demand11, demand12,
    call1, call2, call3, call4, call5, call6, call7, call8, call9, call10, call11, call12
    from reg_pro_total, reg_pro
	where 
    reg_pro.pro_id = reg_pro_total.part and reg_pro.pmf_id = reg_pro_total.newmfr and
    reg_pro_total.stock = 1 and reg_pro_total.branch in (1,2,3,4,5,6,7,9,11,12,14)");
    
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
        $pmf       = "'" . odbc_result($result2, 1) . "'";
        $pro       = "'" . odbc_result($result2, 2) . "'";
        
//initialise start date
        $date      = date_create($_POST['reference_date']);
//first column for demands
//        $demandcol = 4;
        $demandcol = 10;
//first column for calls
//        $callcol   = 16;
        $callcol   = 22;
//loop to process 12 months        
//        for ($i = 1; $i <= 6; $i++)
        for ($i = 7; $i <= 12; $i++)
          {
//            $yearmonth = date_sub($date, date_interval_create_from_date_string('1 month'));
            $yearmonth = date_sub($date, date_interval_create_from_date_string('1 month'));
            $year      = date_format($yearmonth, 'Y');
            $year1 = strval($year);
            $period    = strval(date_format($yearmonth, 'm'));
            $period1 = strval($period);
//            if (strlen($period1 == 1)){
//			if ($period < 10){
//            	$period1 = '0'.$period1;
//            }
            $yearperiod = $year1.$period1;
//            echo $yearperiod.'  '.$year1.'  '.$period1;
//            exit;
            $call      = odbc_result($result2, $callcol);
            $demand    = odbc_result($result2, $demandcol);
            
//            if ($demand <> 0)
//            {
	            $Ifxsql1 = $Ifxdb->query1("insert into reg_pdc(
	            bra_id,
	            pdc_amt_del,
	            pdc_amt_del_oth,
	            pdc_amt_exc,
	            pdc_amt_lost,
	            pdc_amt_ord,
	            pdc_amt_req,
	            pdc_amt_ret,
	            pdc_amt_cpy,
	            pdc_amt_trf,
	            pdc_cos_del,
	            pdc_cos_del_oth,
	            pdc_cos_exc,
	            pdc_cos_lost,
	            pdc_cos_ord,
	            pdc_cos_req,
	            pdc_cos_ret,
	            pdc_cos_cpy,
	            pdc_cos_trf,
	            pdc_dem_del,
	            pdc_dem_del_oth,
	            pdc_dem_exc,
	            pdc_dem_lost,
	            pdc_dem_ord,
	            pdc_dem_req,
	            pdc_dem_ret,
	            pdc_dem_cpy,
	            pdc_dem_trf,
	            pdc_period,
	            pdc_q_del,
	            pdc_q_del_oth,
	            pdc_q_exc,
	            pdc_q_lost,
	            pdc_q_ord,
	            pdc_q_req,
	            pdc_q_ret,
	            pdc_q_cpy,
	            pdc_q_trf,
	            pdc_yyyy,
	            pdc_yyyymm,
	            pmf_id,
	            pro_id,
	            pdc_proc_id      
	            ) values (
	     	      '" . $branch . "',
	            0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
	            $call,
	            0,0,0,0,
	            $period,
	            0,0,0,0,
	            $demand,
	            0,0,0,0,
	            $year,
	            $yearperiod,
	            $pmf,
	            $pro,
				0)");
	            $demandcol++;
	            $callcol++;
//            }
          }
        $rows++;
        
      }
      $Ifxsql1 = $Ifxdb->query1("delete from reg_pdc where pdc_q_ord = 0");
    echo $rows . '  Rows loaded';
    $Ifxdb->closeConnection();
    unset($_POST);
  }


?>
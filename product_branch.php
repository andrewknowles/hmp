<?php
//Product branch data load for HCMUK
//A. Knowles 06/2014
include('includes/db_Ifxlib.php');
require('includes/table_lib.php');
include('includes/db_Mylib.php');
$myIniFile = parse_ini_file("includes/idb.ini", TRUE);
set_time_limit(0);

// create the MySQL connection
$Myconfig = new Myconfig($myIniFile['IDBMYSQL']['server'], $myIniFile['IDBMYSQL']['login'], $myIniFile['IDBMYSQL']['password'], $myIniFile['IDBMYSQL']['database'], $myIniFile['IDBMYSQL']['extension'], $myIniFile['IDBMYSQL']['mysqlformat']);
$Mydb     = new Mydb($Myconfig);
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
	drop table if exists reg_prb;

	create table reg_prb (
	bra_id			CHAR(50),
	cpy_id			CHAR(50),
	pmf_id			CHAR(50),
	prb_abc_amount		CHAR(50),
	prb_abc_qty		CHAR(50),
	prb_d_abc		CHAR(50),
	prb_d_cre			CHAR(50),
	prb_d_lifo		CHAR(50),
	prb_d_minmax		CHAR(50),
	prb_d_prot		CHAR(50),
	prb_d_upd		CHAR(50),
	prb_index_cost		CHAR(50),
	prb_index_qty		CHAR(50),
	prb_inv_cycle		CHAR(50),
	prb_lifo_coef		CHAR(50),
	prb_lifo_cost		CHAR(50),
	prb_managed		CHAR(50),
	prb_maxi_days		CHAR(50),
	prb_maxi_freeze		CHAR(50),
	prb_mini_days		CHAR(50),
	prb_mini_emer		CHAR(50),
	prb_mini_freeze		CHAR(50),
	prb_qty_maxi		CHAR(50),
	prb_qty_mini		CHAR(50),
	prb_restocking		CHAR(50),
	prb_std_cost		CHAR(50),
	prb_wap_inprogr		CHAR(50),
	prb_wap_invoice		CHAR(50),
	pro_id			CHAR(50),
	prb_ic_free1		CHAR(10),
	prb_bypass		CHAR(10),
	regprb_free1		CHAR(50),
	regprb_free2		CHAR(50),
	regprb_free3		CHAR(50),
	regprb_free4		CHAR(50),
	regprb_free5		CHAR(50)
	)");
    
    echo 'reg_prb created';
    $Ifxdb->closeConnection();
    unset($_POST);
  }

if (isset($_POST['loadtext']))
  {
    $file = file($_POST['filetext']);
    $rows = 0;
    foreach ($file as $val)
      {
        list($part1, $part2) = explode(';', $val);
        $Ifxsql1 = $Ifxdb->query1("insert into reg_csc(thr_1099, thr_active) values('" . $part1 . "','" . $part2 . "')");
        $rows++;
      }
    echo $rows . '  Rows loaded';
    $Ifxdb->closeConnection();
    unset($_POST);
  }

if (isset($_POST['loadexcel']))
  {
    $rows    = 0;
    $result2 = $Ifxdb->query1("select reg_pro.pmf_id, reg_pro.pro_id, reg_pro_total.branch, reg_pro_total.avgcost,
    reg_pro_total.lastact from reg_pro, reg_pro_total 
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
        
        $Ifxsql1 = $Ifxdb->query1("insert into reg_prb(
        bra_id,
        cpy_id,
        pmf_id,
        prb_d_cre,
        prb_d_upd,
        prb_managed,
        prb_wap_inprogr,
        prb_wap_invoice,
        pro_id
            
		) values (
     	'" . $branch . "',
        'HMUK',		
        '" . odbc_result($result2, 1) . "',
        '" . odbc_result($result2, 5) . "',
        '" . odbc_result($result2, 5) . "',
        1,		
        '" . odbc_result($result2, 4) . "',
        '" . odbc_result($result2, 4) . "',	
        '" . odbc_result($result2, 2) . "'
		)");
        $rows++;   
      }
    echo $rows . '  Rows loaded';
    $Ifxdb->closeConnection();
    unset($_POST);
    
  }


?>
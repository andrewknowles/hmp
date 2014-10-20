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
	drop table if exists reg_pff;

	create table reg_pff
	(
	pmf_id 			char(10),
	pro_id 			char(30),
	bra_id 			char(10),
	pff_q	 		char(20),
	pff_d_input 		char(20),
	pff_cost 		char(20),
	regpff_free1		char(50),
	regpff_free2		char(50),
	regpff_free3		char(50),
	regpff_free4		char(50),
	regpff_free5		char(50)			
	)");
    
    echo 'reg_pff created';
    $Ifxdb->closeConnection();
    unset($_POST);
  }

if (isset($_POST['loadexcel']))
  {
    $rows    = 0;
    $result2 = $Ifxdb->query1("select reg_pro.pmf_id, reg_pro.pro_id, reg_pro_total.branch, reg_pro_total.onhand, 
    reg_pro_total.lastrec, reg_pro_total.avgcost from reg_pro, reg_pro_total
	where reg_pro.pro_id = reg_pro_total.part and reg_pro.regpro_free2 = reg_pro_total.newmfr
	and reg_pro_total.stock = 1 and reg_pro_total.onhand <> 0 and reg_pro_total.branch in (1,2,3,4,5,6,7,9,11,12,14)");
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
        
        //Manage missing dates
        if (strlen(trim(odbc_result($result2, 5))) == 0)
          {
            $lastrec = '09-30-2014';
          }
        else
          {
            //            $lastrec = trim(odbc_result($result2, 5));
            //            date_create(trim(odbc_result($result2, 5)))
            $lastrec = date_format((date_create(trim(odbc_result($result2, 5)))), 'm-d-Y');
          }
        
        $Ifxsql1 = $Ifxdb->query1("insert into reg_pff(
        pmf_id,
        pro_id,
        bra_id,
        pff_q,
		pff_d_input,
		pff_cost      
		) values (
       	'" . trim(odbc_result($result2, 1)) . "',
        '" . trim(odbc_result($result2, 2)) . "',
        '" . $branch . "',
        '" . trim(odbc_result($result2, 4)) . "',
        '" . $lastrec . "',
        '" . trim(odbc_result($result2, 6)) . "'        				     		
		)");
        $rows++;
        
      }
    echo $rows . '  Rows loaded';

    unset($_POST);
//    calculate_pna($Ifxsql1, $Ifxdb);
//    echo 'PNA calculation done';
    
    $Ifxdb->closeConnection();
        
  }
  
  function calculate_pna($res, $obj)
  {
  	$obj->query1("drop table if exists pwc");
  	$obj->query1("
	CREATE TABLE pwc
	(pwc_id  SERIAL,
	cpy_id CHAR(4),
	bra_id CHAR(4),
	pmf_id CHAR(4),
	pro_id CHAR(25),
	pwc_cost DECIMAL(18,4),
	pwc_q DECIMAL(8,2),
	ofh_id INTEGER,
	ofl_id INTEGER,
	opl_id INTEGER,
	pwc_ibt DECIMAL(1,0) DEFAULT 0)");
  	$obj->query1("CREATE INDEX i_pwcpro ON pwc(pmf_id, pro_id)");
  	$obj->query1("CREATE INDEX i_pwccpy ON pwc(cpy_id)");
  	$obj->query1("INSERT INTO pwc(cpy_id,bra_id, pmf_id, pro_id, pwc_cost, pwc_q) 
	SELECT cpy_id, pff.bra_id, pmf_id, pro_id, pff_cost, pff_q
	FROM pff, bra 
	WHERE pff.bra_id = bra.bra_id
	AND pff_q > 0
	AND pff.equ_id IS NULL");
  	$obj->query1("
  	INSERT INTO pna(cpy_id, pmf_id, pro_id, pna_q, pna_q_transit, pna_current)
	SELECT cpy_id, pmf_id, pro_id, 
	(SELECT NVL(SUM(p2.pwc_q),0) 
	FROM pwc p2 
	WHERE pwc.cpy_id = p2.cpy_id 
	AND pwc.pmf_id = p2.pmf_id 
	AND  pwc.pro_id = p2.pro_id 
	AND p2.pwc_ibt = 0
	AND p2.pwc_q > 0),
	(SELECT NVL(SUM(p2.pwc_q),0) 
	FROM pwc p2 
	WHERE pwc.cpy_id = p2.cpy_id 
	AND pwc.pmf_id = p2.pmf_id 
	AND  pwc.pro_id = p2.pro_id 
	AND p2.pwc_ibt = 1
	AND p2.pwc_q > 0), 
	(SELECT ROUND(SUM(p2.pwc_q * p2.pwc_cost)/SUM(p2.pwc_q),2) 
	FROM pwc p2 
	WHERE pwc.cpy_id = p2.cpy_id 
	AND pwc.pmf_id = p2.pmf_id 
	AND  pwc.pro_id = p2.pro_id
	AND p2.pwc_q > 0)
	FROM pwc
  	WHERE pwc.pwc_q > 0
	GROUP BY 1,2,3,4,5,6");
  	$obj->query1("
INSERT INTO pnh(pnh_pmf_id, pnh_pro_id, pnh_cpy_id , pnh_q, pnh_cost, pnh_ibt, pnh_usr_id, pnh_q_pna, pnh_q_transit_pna, pnh_current_pna, pnh_previous_pna, pnh_d_cre)
SELECT pna.pmf_id, pna.pro_id, pna.cpy_id, pna.pna_q + pna.pna_q_transit, pna.pna_current, 0, 'CONVERSION', 0,0,pna_current,0, (SELECT MAX(psk_d_in) FROM psk, bra WHERE psk.pmf_id = pna.pmf_id AND psk.pro_id = pna.pro_id AND bra.bra_id = psk.bra_id AND bra.cpy_id = pna.cpy_id)
FROM pna");
  	$obj->query1("
INSERT INTO pne (pne_cpy_id , pne_pmf_id, pne_pro_id, pne_rounding, pne_integrated, pne_usr_id, pne_d_cre, pne_bra_id, pne_dpr_id)
SELECT cpy_id, pmf_id, pro_id,	
	(pna_q + pna_q_transit) * pna_current - (SELECT SUM(pwc_q * pwc_cost) 
					FROM pwc
					WHERE pwc.cpy_id = pna.cpy_id 
					AND pwc.pmf_id = pna.pmf_id
					AND pwc.pro_id = pna.pro_id
AND pwc.pwc_q > 0),
	0, 'CONVERSION', MDY(1,1,2013), 'HO', '300'
FROM pna 
WHERE (pna_q + pna_q_transit) * pna_current <> (SELECT SUM(pwc_q * pwc_cost) 
					FROM pwc
					WHERE pwc.cpy_id = pna.cpy_id 
					AND pwc.pmf_id = pna.pmf_id
					AND pwc.pro_id = pna.pro_id
AND pwc.pwc_q > 0)");
  			  			
  }
?>
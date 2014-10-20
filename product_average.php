<?php
//Calculate national avergae prices HCMUK
//A. Knowles 06/2014
include('includes/db_Ifxlib.php');
include('includes/db_Mylib.php');
require('includes/table_lib.php');

set_time_limit(0);

$myIniFile = parse_ini_file("includes/idb.ini", TRUE);

// create the Informix connection
$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb     = new Ifxdb($Ifxconfig);

if (isset($_POST['reset']) && $_POST['confirm'] == 'confirm')
  {
    $Ifxsql1 = $Ifxdb->query1("
	drop table if exists pwc;

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
    
    echo 'pwc table created';
    $Ifxdb->closeConnection();
    unset($_POST);
  }


if (isset($_POST['loadexcel']))
  {
//    $Ifxdb->closeConnection();
//    unset($_POST);
   
    create_index($Ifxsql1, $Ifxdb);
    echo 'Indexes created';
    echo'</BR>';
    insert_pff($Ifxsql1, $Ifxdb);
    echo 'Pff Inserted';
    echo'</BR>';
    insert_pna($Ifxsql1, $Ifxdb);
    echo 'Pna Inserted';
    echo'</BR>';
    insert_pnh($Ifxsql1, $Ifxdb);
    echo 'Pnh Inserted';
    echo'</BR>';
    update_descriptions($Ifxsql1, $Ifxdb);
    echo 'Descriptions updated';
    echo'</BR>';
    update_bin($Ifxsql1, $Ifxdb);
    echo 'Bin No updated';
    echo'</BR>';
    echo 'Program complete';
        
  }

  
 function create_index($res, $obj)
  {
  	$obj->query1("CREATE INDEX i_pwcpro ON pwc(pmf_id, pro_id)");
  	$obj->query1("CREATE INDEX i_pwccpy ON pwc(cpy_id)");
  }

  function insert_pff($res, $obj)
  {
  	$obj->query1("INSERT INTO pwc(cpy_id,bra_id, pmf_id, pro_id, pwc_cost, pwc_q) 
SELECT cpy_id, pff.bra_id, pmf_id, pro_id, pff_cost, pff_q
FROM pff, bra 
WHERE pff.bra_id = bra.bra_id
AND pff_q > 0
AND pff.equ_id IS NULL 
  			");
  }
  
  function insert_pna($res, $obj)
  {
  	$obj->query1("INSERT INTO pna(cpy_id, pmf_id, pro_id, pna_q, pna_q_transit, pna_current)
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
  }
  
  function insert_pnh($res, $obj)
  {
  	$obj->query1("INSERT INTO pnh(pnh_pmf_id, pnh_pro_id, pnh_cpy_id , pnh_q, pnh_cost, pnh_ibt, pnh_usr_id, pnh_q_pna, pnh_q_transit_pna, pnh_current_pna, pnh_previous_pna, pnh_d_cre)
SELECT pna.pmf_id, pna.pro_id, pna.cpy_id, pna.pna_q + pna.pna_q_transit, pna.pna_current, 0, '0000', 0,0,pna_current,0, (SELECT MAX(psk_d_in) FROM psk, bra WHERE psk.pmf_id = pna.pmf_id AND psk.pro_id = pna.pro_id AND bra.bra_id = psk.bra_id AND bra.cpy_id = pna.cpy_id)
FROM pna");
  }
  
  function insert_pne($res, $obj)
  {
  	$obj->query1("INSERT INTO pne (pne_cpy_id , pne_pmf_id, pne_pro_id, pne_rounding, pne_integrated, pne_usr_id, pne_d_cre, pne_bra_id, pne_dpr_id)
SELECT cpy_id, pmf_id, pro_id,	
	(pna_q + pna_q_transit) * pna_current - (SELECT SUM(pwc_q * pwc_cost) 
					FROM pwc
					WHERE pwc.cpy_id = pna.cpy_id 
					AND pwc.pmf_id = pna.pmf_id
					AND pwc.pro_id = pna.pro_id
AND pwc.pwc_q > 0),
	0, '0000', MDY(9,20,2014), 'HO', '300'
FROM pna 
WHERE (pna_q + pna_q_transit) * pna_current <> (SELECT SUM(pwc_q * pwc_cost) 
					FROM pwc
					WHERE pwc.cpy_id = pna.cpy_id 
					AND pwc.pmf_id = pna.pmf_id
					AND pwc.pro_id = pna.pro_id
AND pwc.pwc_q > 0)
  			");
  }
  
  function update_bin($res, $obj)
  {
  	$obj->query1("update reg_pro_total set bin = 'No Bin' where bin = '' and  branch in (1,2,3,4,5,6,7,9,11,12,14) and stock = 1");
  }
  
  function check_duplicate($res, $obj)
  {
  	$obj->query1("update reg_pro_total set bin = 'No Bin' where bin = '' and  branch in (1,2,3,4,5,6,7,9,11,12,14) and stock = 1");
  }


?>
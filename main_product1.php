<?php
include('includes/db_Ifxlib.php');
require('includes/table_lib.php');
set_time_limit ( 0 );
$myIniFile = parse_ini_file ("includes/idb.ini", TRUE);
$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb = new Ifxdb($Ifxconfig);

if (isset($_POST['reset']) && $_POST['confirm'] == 'confirm'){    
	$Ifxsql1 = $Ifxdb->query1("
			
	  drop table if exists reg_pro_inter;
    		
    create table reg_pro_inter (
	pmf_id    	char(5),
	pro_id     	char(30),
	pdi_desc	char(50));	
			
	drop table if exists reg_pro;

	create table reg_pro (
	afm_id			CHAR(10),
	cry_id			CHAR(10),
	olt_id			CHAR(10),
	pcc_id			CHAR(10),
	pmf_id			CHAR(10),
	pmf_id_stored		CHAR(10),
	pro_active		CHAR(10),
	pro_campaign		CHAR(10),
	pro_commission		CHAR(10),
	pro_competing		CHAR(10),
	pro_consump		CHAR(10),
	pro_core_days		CHAR(10),
	pro_cxp_exempt		CHAR(10),
	pro_d_cre			CHAR(20),
	pro_d_upd		CHAR(20),
	pro_desc_upd		CHAR(10),
	pro_dis_exempt		CHAR(10),
	pro_eec_partlist		CHAR(10),
	pro_findis_exempt		CHAR(10),
	pro_free1			CHAR(10),
	pro_free2			CHAR(10),
	pro_free3			CHAR(10),
	pro_free4			CHAR(10),
	pro_height_mt		CHAR(20),
	pro_hmvt		CHAR(10),
	pro_hro			CHAR(10),
	pro_id			CHAR(30),
	pro_id_spaces		CHAR(30),
	pro_id_stored		CHAR(30),
	pro_length_mt		CHAR(20),
	pro_managed		CHAR(10),
	pro_margin		CHAR(10),
	pro_mat_sheet		CHAR(10),
	pro_packsale		CHAR(20),
	pro_packstore		CHAR(20),
	pro_pelmo		CHAR(10),
	pro_pid			CHAR(10),
	pro_power		CHAR(10),
	pro_power_unit		CHAR(10),
	pro_pprice		CHAR(10),
	pro_qtydec		CHAR(10),
	pro_recondition		CHAR(10),
	pro_returnable		CHAR(10),
	pro_rounded		CHAR(10),
	pro_season		CHAR(10),
	pro_serialized		CHAR(10),
	pro_track_stat		CHAR(10),
	pro_ucstore		CHAR(20),
	pro_unit_usage		CHAR(10),
	pro_upcoef		CHAR(20),
	pro_usale		CHAR(10),
	pro_ustore		CHAR(10),
	pro_volume		CHAR(50),
	pro_weight_mt		CHAR(20),
	pro_weight_unit		CHAR(10),
	pro_weight_us		CHAR(20),
	pro_width_mt		CHAR(20),
	psa_id			CHAR(10),
	psf_id			CHAR(10),
	txr_id			CHAR(10),
	pro_ic_free1		CHAR(10),
	pro_ic_free2		CHAR(10),
	pdi_lng_id		CHAR(10),
	pdi_desc			CHAR(50),
	pdi_instr			CHAR(50),
	pdi_matchdesc		CHAR(50),
	pdi_lng_id2		CHAR(10),
	pdi_desc2			CHAR(50),
	pdi_instr2		CHAR(50),
	pdi_matchdesc2		CHAR(50),
	cur_id			CHAR(10),
	ppd_d_apply		CHAR(20),
	ppd_d_upd		CHAR(20),
	ppd_fleet			CHAR(20),
	ppd_list			CHAR(20),
	ppd_net			CHAR(20),
	ppd_origupd		CHAR(10),
	ppd_packpurch		CHAR(20),
	ppd_set			CHAR(10),
	usr_id			CHAR(10),
	pno_lng_id		CHAR(10),
	pno_notes		CHAR(50),
	pno_lng_id2		CHAR(10),
	pno_notes2		CHAR(50),
	regpro_free1		CHAR(50),
	regpro_free2		CHAR(50),
	regpro_free3		CHAR(50),
	regpro_free4		CHAR(50),
	regpro_free5		CHAR(50),
	regpro_free6		CHAR(50),
	regpro_free7		CHAR(50),
	regpro_free8		CHAR(50),
	regpro_free9		CHAR(50),
	regpro_free10		CHAR(50)
)");
	
echo 'reg_pro created';
//$Ifxdb->closeConnection();
unset($_POST);
}

if (isset($_POST['loadtext']))
{

	$Ifxsql1 = $Ifxdb->query1("insert into reg_pro_inter select newmfr, part, '--' from reg_pro_total where stock = 1
  	and branch in (1,2,3,4,5,6,7,9,11,12,14) group by 1,2");
	$Ifxsql1 = $Ifxdb->query1("Update reg_pro_inter set pdi_desc = null where 1=1");
	$Ifxsql1 = $Ifxdb->query1("Update reg_pro_inter i set pdi_desc = (select desc from reg_pro_summary s where
	i.pmf_id = s.newmfr and i.pro_id = s.part and stock = 'Yes')");
	
	$Ifxdb->closeConnection();
	unset($_POST);
}

if (isset($_POST['loadexcel'])){
	
	

	$rows = 0;
	$result2 = $Ifxdb->query1("select pmf_id, pro_id, pdi_desc from reg_pro_inter  group by 1,2,3");
    
    while( $row = odbc_fetch_array($result2))    	
    {
   	
// Handle null part numbers
    	if (strlen(odbc_result($result2, 2)) == 0) {
			$partno = 'No part no';
		} else {
			$partno = odbc_result($result2, 2);
		}
		$partno =  str_replace ( "'", " ", $partno);
//		$partno =  str_replace ( '"', ' in. ', $partno);
//		$partno =  str_replace ( '”', ' in. ', $partno);
		
//Strip quotes
		if (strlen(odbc_result($result2, 3)) == 0){
			$partdesc = '$No Description$';
		}else{
			$partdesc = odbc_result($result2, 3);
		} 
		$partdesc = str_replace ( "'", " ", $partdesc );
//		$partdesc =  str_replace ( '"', ' in. ', $partdesc );
//		$partdesc =  str_replace ( '”', ' in. ', $partdesc );
		
// GL Access Code
		$glaccess = 'PARTS';
		if (odbc_result($result2, 1)=='GEIB')
		{
			$glaccess = 'BUCK';
			break;
		} elseif (odbc_result($result2, 1)=='HILB')
		 {
		 	$glaccess = 'BUCK';
		 	break;
		} elseif (odbc_result($result2, 1)=='MILB')
		 {
		 	$glaccess = 'BUCK';
		 	break;
		} elseif (odbc_result($result2, 1)=='STRB')
		 {
		 	$glaccess = 'BUCK';	
		 	break;
		} elseif (odbc_result($result2, 1)=='WHIB')
		 {
		 	$glaccess = 'BUCK';
		} 
		
//Insert query    	
        $Ifxsql1 = $Ifxdb->query1("insert into reg_pro(
        afm_id,
        pmf_id,
        pro_active,
        pro_competing,
        pro_consump,
        pro_cxp_exempt,
        pro_d_cre,
        pro_d_upd,
        pro_desc_upd,
        pro_dis_exempt,
        pro_findis_exempt,
        pro_hmvt,
        pro_hro,
        pro_id,
        pro_id_spaces,
        pro_managed,
        pro_packsale,
        pro_packstore,
        pro_pelmo,
        pro_pid,
        pro_qtydec,
        pro_recondition,
        pro_returnable,
        pro_rounded,
        pro_serialized,
        pro_track_stat,
        pro_ucstore,
        txr_id,
        pdi_lng_id,
        pdi_desc,
        pdi_matchdesc,
        cur_id,
		ppd_d_apply,
		ppd_d_upd,
		ppd_fleet,
		ppd_list,
		ppd_net,
		ppd_origupd,
		ppd_packpurch,
		ppd_set,
        pro_free1,
        pro_free2,
        regpro_free1,
		regpro_free2
          
) values(        		
     	'".$glaccess."',
        '".odbc_result($result2, 1)."',
        1,
        0,
        1,
        0,
        '2014-06-03 00:00:00',
        '2014-06-03 00:00:00',
        1,
        0,
        0,
        1,
        1,
        '".$partno."',
        '".$partno."',
        1,
        1,
        1,
        1,
        1,
        0,
        0,
        0,
        0,
        0,
        1,
        1,
        0,
        0,
        '".$partdesc."',
        '".$partdesc."',	
        'GBP',
        '01/01/1900',
        '1900-01-01 00:00:00',
        0,
        0,
        0,
        0,
        1,
        1,
        '".odbc_result($result2, 1)."',
        '".odbc_result($result2, 1)."',
        '".odbc_result($result2, 1)."',
        '".odbc_result($result2, 1)."'
        	
)");
        $rows++;
    }
    
    echo $rows. '  Rows loaded';
    $Ifxdb->closeConnection();
    unset($_POST);
}
//    clean_up1($Ifxsql1, $Ifxdb);
//    clean_up2($Ifxsql1, $Ifxdb);
//    display_rows($Ifxsql1, $Ifxdb);
    
    


if (isset($_POST['check'])){
    clean_up2($Ifxsql1, $Ifxdb);
}

function clean_up1($res, $obj)
{
//	odbc_exec($obj,"delete from reg_pro where pro_id in ('###############','################','##################','**************','****************')");
	$res = $obj->query1("delete from reg_pro where pro_id in (
'###############',
'################',
'##################',
'**************',
'****************')");
	while(odbc_fetch_row($res)){
		echo 'deleted';
	}		
}

function clean_up2($res, $obj)
{
$res = $obj->query1("select pro_id, rowid regproid
	from reg_pro t1
	where rowid > ( select min(rowid) from reg_pro where pro_id = t1.pro_id ) into temp regprodup");
	while(odbc_fetch_row($res)){
		echo 'deleted';
	}	

	$res = $obj->query1("delete from reg_pro where rowid  in (select regproid from regprodup)");
	while(odbc_fetch_row($res)){
		echo 'deleted';
	}

	$res = $obj->query1("drop table regprodup");
	while(odbc_fetch_row($res)){
		echo 'deleted';
	}
}

function display_rows($res, $obj)
{
    $res = $obj->query1("select * from reg_pro");
    $tbl3 = new HTML_Table('', 'Product Data', 1, array('cellpadding'=>4, 'cellspacing'=>0) );
    $tbl3->addCaption('<b>Product Data</b>', 'cap', array('id'=> 'tblCap') );

    $tbl3->addRow();
    $tbl3->addCell('Mfr', 'first', 'header');
    $tbl3->addCell('Part No', '', 'header');
    $rowindex = 0;
    $parts_stock1_total = 0;
    while(odbc_fetch_row($res)){
        $tbl3->addRow();
        $tbl3->addCell (odbc_result($res, 5));
        $tbl3->addCell (odbc_result($res, 27));
        $rowindex++;
    }
    
    echo $tbl3->display();
}

function insert_reg_pro_inter($res, $obj)
{
	$obj->query1("insert into reg_pro_inter select newmfr, part, desc from reg_pro_total where stock = 1
  	and branch in (1,2,3,4,5,6,7,9,11,12,14) group by 1,2,3");
}

function update_descriptions($res, $obj)
{
	$obj->query1("Update reg_pro_inter set pdi_desc = null where 1=1");
	$obj->query1("Update reg_pro_inter i set pdi_desc = (select desc from reg_pro_summary s where
	i.pmf_id = s.mfr and i.pro_id = s.part and stock = 'Yes')");
}

?>
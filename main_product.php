<?php
include('includes/db_Ifxlib.php');
require('includes/table_lib.php');
set_time_limit ( 0 );
$myIniFile = parse_ini_file ("includes/idb.ini", TRUE);
$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb = new Ifxdb($Ifxconfig);

if (isset($_POST['reset']) && $_POST['confirm'] == 'confirm'){    
	$Ifxsql1 = $Ifxdb->query1("
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
$Ifxdb->closeConnection();
unset($_POST);
}

if (isset($_POST['loadtext'])){
    $file = file($_POST['filetext']);
    $rows = 0;
    foreach($file as $val)    
    {
        list($part1, $part2) = explode(';',$val);
        $Ifxsql1 = $Ifxdb->query1("insert into reg_csc(thr_1099, thr_active) values('".$part1."','".$part2."')");
        $rows++;
    }
    echo $rows. '  Rows loaded';
    $Ifxdb->closeConnection();
    unset($_POST);
}

if (isset($_POST['loadexcel'])){
    $rows = 0;
    $excelFile = realpath($_POST['fileexcel']);
    $excelSheet = $_POST['sheetexcel'];
    $excelDir = dirname($excelFile);
    $connection = odbc_connect("Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=$excelFile;DefaultDir=$excelDir" , '', '');
    $result = odbc_exec($connection, "select * from [" . $excelSheet . "$]"); 
    while( $row = odbc_fetch_array($result))    	
    {
    	if ($row ['Current_Stock_Item'] == 'Yes'){
    	

//Generate manufacturer id
    	if (($row ['Manufacture'] == 'Hitachi construction Machinery Europe' ) && (($row ['Mfr'] == 'HI') || ($row ['Mfr'] == 'HM'))){
    		$mfr = 'HCM';
    	} elseif($row ['Manufacture'] == 'M&M Plant') {
    		$mfr = 'FHCM';
    	} elseif($row ['Manufacture'] == 'Hill Engineering') {
    			$mfr = 'HILL';   	 
    	} else {
    		$mfr = 'ZZZZ';
    	}
    	
    	 
    	
// Handle null part numbers 
    	if (strlen ( $row ['PartNo'] ) == 0) {
			$partno = 'No part no';
		} else {
			$partno = $row['PartNo'];
		}
		$partno =  str_replace ( "'", " ", $partno);
		$partno =  str_replace ( '"', ' in. ', $partno);
		$partno =  str_replace ( '”', ' in. ', $partno);
		
//Strip quotes
		$partdesc = $row ['Stock_Description'] ;
		if (strlen($partdesc) == 0){
			$partdesc = 'No Description';
		} 
		$partdesc = str_replace ( "'", " ", $partdesc );
		$partdesc =  str_replace ( '"', ' in. ', $partdesc );
		$partdesc =  str_replace ( '”', ' in. ', $partdesc );
//Insert query    	
        $Ifxsql1 = $Ifxdb->query1("insert into reg_pro(
        afm_id,
        pmf_id,
        pro_active,
        pro_competing,
        pro_consump,
        pro_d_cre,
        pro_d_upd,
        pro_desc_upd,
        pro_findis_exempt,
        pro_hmvt,
        pro_hro,
        pro_id,
        pro_id_spaces,
        pro_managed,
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
        ppd_origupd,
        regpro_free1,
        regpro_free2,
        regpro_free3
          
) values(        		
     	'PARTS',
        '".$mfr."',
        1,
        0,
        1,
        '2014-06-03 00:00:00',
        '2014-06-03 00:00:00',
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
        0,
        '" . $row ['Mfr'] . "',
        '" . $row ['Manufacture'] . "',	
        '" . $row ['Current_Stock_Item'] . "'	
)");
        $rows++;
    	}
    }
    echo $rows. '  Rows loaded';
    $Ifxdb->closeConnection();
    unset($_POST);
    clean_up1($Ifxsql1, $Ifxdb);
//    clean_up2($Ifxsql1, $Ifxdb);
//    display_rows($Ifxsql1, $Ifxdb);
    
    
}

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

?>
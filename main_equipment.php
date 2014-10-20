<?php
include('includes/db_Ifxlib.php');
require('includes/table_lib.php');
$myIniFile = parse_ini_file ("includes/idb.ini", TRUE);
$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb = new Ifxdb($Ifxconfig);

if (isset($_POST['reset']) && $_POST['confirm'] == 'confirm'){    
	$Ifxsql1 = $Ifxdb->query1("
	drop table if exists reg_equ;

	create table reg_equ (
	afm_id			char(10),
	cry_id			char(10),
	olt_id			char(10),
	pcc_id			char(10),
	pmf_id			char(10),
	pmf_id_stored		char(10),
	pro_active		char(10),
	pro_campaign		char(10),
	pro_commission		char(10),
	pro_competing		char(10),
	pro_consump		char(10),
	pro_core_days		char(10),
	pro_cxp_exempt		char(10),
	pro_d_cre		char(20),
	pro_d_upd		char(20),
	pro_desc_upd		char(10),
	pro_dis_exempt		char(10),
	pro_eec_partlist	char(10),
	pro_findis_exempt	char(10),
	pro_free1		char(10),
	pro_free2		char(10),
	pro_free3		char(10),
	pro_free4		char(10),
	pro_height		char(20),
	pro_hmvt		char(10),
	pro_hro			char(10),
	pro_id			char(30),
	pro_id_spaces		char(30),
	pro_id_stored		char(30),
	pro_length		char(20),
	pro_managed		char(10),
	pro_margin		char(10),
	pro_mat_sheet		char(10),
	pro_packsale		char(20),
	pro_packstore		char(20),
	pro_pelmo		char(10),
	pro_pid			char(10),
	pro_power		char(10),
	pro_power_unit		char(10),
	pro_pprice		char(10),
	pro_qtydec		char(10),
	pro_recondition		char(10),
	pro_returnable		char(10),
	pro_rounded		char(10),
	pro_season		char(10),
	pro_serialized		char(10),
	pro_track_stat		char(10),
	pro_ucstore		char(20),
	pro_unit_usage		char(10),
	pro_upcoef		char(20),
	pro_usale		char(10),
	pro_ustore		char(10),
	pro_volume		char(50),
	pro_weight_mt		char(20),
	pro_weight_unit		char(10),
	pro_weight_us		char(20),
	pro_width		char(20),
	psa_id			char(10),
	psf_id			char(10),
	txr_id			char(10),
	
	pdi_lng_id		char(10),
	pdi_desc		char(50),
	pdi_instr		char(50),
	pdi_matchdesc		char(50),
	pdi_lng_id2		char(10),
	pdi_desc2		char(50),
	pdi_instr2		char(50),
	pdi_matchdesc2		char(50),
	
	bra_id			char(10),
	cur_id			char(10),
	dpr_id_track		char(10),
	egr_id			char(10),
	emk_id			char(20),
	equ_active		char(10),
	equ_counter1		char(10),
	equ_csc_eqid		char(20),
	equ_d_cre		char(20),
	equ_d_firstused		char(20),
	equ_d_newused		char(20),
	equ_d_plate		char(20),
	equ_d_purch		char(20),
	equ_d_reg		char(20),
	equ_d_upd		char(20),
	equ_desc		char(50),
	equ_free_status		char(10),
	equ_howacquired		char(10),
	equ_id_dealer		char(20),
	equ_instr_sv		char(50),
	equ_matchname		char(50),
	equ_mrkserie		char(20),
	equ_new_used		char(10),
	equ_notes		char(50),
	equ_odometer1		char(10),
	equ_origin		char(10),
	equ_ownertype		char(10),
	equ_plate		char(20),
	equ_power		char(10),
	equ_power_unit		char(10),
	equ_reg_id		char(20),
	equ_reg_status		char(10),
	equ_seller		char(20),
	equ_serialnb		char(30),
	equ_sort		char(10),
	equ_trade_value		char(20),
	equ_trk_body		char(10),
	equ_trk_cu		char(10),
	equ_trk_ptac		char(10),
	equ_trk_ptra		char(10),
	equ_trk_pv		char(10),
	equ_y_model		char(10),
	equ_y_purch		char(10),
	esr_id			char(20),
	owner_id		char(10),
	usr_id			char(10),
	
	eqm_asset		char(10),
	eqm_assign		char(10),
	eqm_basic		char(10),
	eqm_cdel		char(10),
	eqm_cinv		char(10),
	eqm_cord		char(10),
	eqm_cpaid		char(10),
	eqm_d_acq		char(20),
	eqm_d_avail		char(20),
	eqm_d_cre		char(20),
	eqm_d_upd		char(20),
	eqm_gl_acc		char(20),
	eqm_gl_sub		char(20),
	eqm_price_01		char(20),
	eqm_price_02		char(20),
	eqm_price_03		char(20),
	eqm_price_04		char(20),
	eqm_price_05		char(20),
	eqm_price_06		char(20),
	eqm_price_07		char(20),
	eqm_price_08		char(20),
	eqm_price_09		char(20),
	eqm_price_10		char(20),
	eqm_price_11		char(20),
	eqm_price_12		char(20),
	eqm_price_13		char(20),
	eqm_price_14		char(20),
	eqm_price_15		char(20),
	eqm_price_16		char(20),
	eqm_price_17		char(20),
	eqm_price_18		char(20),
	eqm_price_19		char(20),
	eqm_price_20		char(20),
	eqm_rent_status		char(10),
	eqm_sinv		char(10),
	eqm_sord		char(10),
	eqm_spaid		char(10),
	eqm_srec		char(10),
	eqm_tank		char(10),
	eqm_ucc_filing		char(20),
	eqm_unav_reason		char(10),
	eqm_wo_status		char(10),
	psk_bra_id		char(10),
	psk_dpr_id		char(10),
	eqm_psk_id			char(10),
	psk_pmf_id		char(10),
	psk_pro_id		char(30),
	eqm_psk_stktype		char(10),
	
	cpy_id 			char(10),
	prb_abc_amount 		char(10),
	prb_abc_qty 		char(10),
	prb_d_abc 		char(20),
	prb_d_cre 		char(20),
	prb_d_lifo 		char(20),
	prb_d_minmax 		char(20),
	prb_d_prot 		char(20),
	prb_d_upd 		char(20),
	prb_index_cost 		char(20),
	prb_index_qty 		char(10),
	prb_inv_cycle 		char(10),
	prb_lifo_coef 		char(10),
	prb_lifo_cost 		char(20),
	prb_managed 		char(10),
	prb_maxi_days 		char(10),
	prb_maxi_freeze 	char(10),
	prb_mini_days 		char(10),
	prb_mini_emer 		char(10),
	prb_mini_freeze 	char(10),
	prb_qty_maxi 		char(10),
	prb_qty_mini 		char(10),
	prb_restocking 		char(10),
	prb_std_cost 		char(20),
	prb_wap_inprogr 	char(20),
	prb_wap_invoice 	char(20),
	
	psk_d_cre 		char(20),
	psk_d_in 		char(20),
	psk_d_in_int 		char(20),
	psk_d_inv_last 		char(20),
	psk_d_inv_prv 		char(20),
	psk_d_out 		char(20),
	psk_d_out_int 		char(20),
	psk_d_upd 		char(20),
	psk_id 			char(10),
	psk_inv_last_d 		char(10),
	psk_inv_last_p 		char(20),
	psk_inv_last_q 		char(10),
	psk_inv_prv_d 		char(10),
	psk_inv_prv_p 		char(20),
	psk_inv_prv_q 		char(10),
	psk_lock 		char(10),
	psk_maxi 		char(10),
	psk_mini 		char(10),
	psk_q 			char(10),
	psk_q_call 		char(10),
	psk_q_cboc 		char(10),
	psk_q_cwait 		char(10),
	psk_q_lost 		char(10),
	psk_q_mkip 		char(10),
	psk_q_rec 		char(10),
	psk_q_sboc 		char(10),
	psk_q_to_del 		char(10),
	psk_q_to_pic 		char(10),
	psk_q_trs 		char(10),
	psk_stktype 		char(10),
	
	regequ_free1		char(50),
	regequ_free2		char(50),
	regequ_free3		char(50),
	regequ_free4		char(50),
	regequ_free5		char(50),
	regequ_free6		char(50),
	regequ_free7		char(50),
	regequ_free8		char(50),
	regequ_free9		char(50),
	regequ_free10		char(50)
)");
	
echo 'reg_equ created';
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
    $result = odbc_exec($connection, "select * from [$]"); 
    while( $row = odbc_fetch_array($result))    	
    {
    	switch ($row['IF_MFR']){
    		case 'AS':
    			$mfr = 'HCM';
    			break;
    		default:
    			$mfr = 'HCM';   			
    	}
    	
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
        lng_id,
        
        
) values(
     	'PARTS',
        '".$mfr."',
        1,
        0,
        1,
        '".$row['IF_ADDED']."',
        '".$row['IF_ADDED']."',
        '".$row['IF_ADDED']."',
        0,
        1,
        1,
        '".$row['IF_PART']."',
        '".$row['IF_PART']."',
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
        '".$row['IF_DESC']."',
        9,
        '".$row['IF_DESC']."'
)");
        $rows++;

    }
    echo $rows. '  Rows loaded';
    $Ifxdb->closeConnection();
    unset($_POST);
    display_rows($Ifxsql1,$Ifxdb);
    
    
}

if (isset($_POST['check'])){
    echo 'zzz';
}

function display_rows($res, $obj)
{
    $res = $obj->query1("select * from reg_pro");
    $tbl3 = new HTML_Table('', 'Product Data', 1, array('cellpadding'=>4, 'cellspacing'=>0) );
    $tbl3->addCaption('<b>Product Data</b>', 'cap', array('id'=> 'tblCap') );

    $tbl3->addRow();
    $tbl3->addCell('Cst. Name', 'first', 'header');
    $tbl3->addCell('Cst. City', '', 'header');
    $rowindex = 0;
    $parts_stock1_total = 0;
    while(odbc_fetch_row($res)){
        $tbl3->addRow();
        $tbl3->addCell (odbc_result($res, 26));
        $tbl3->addCell (odbc_result($res, 25));
        $rowindex++;
    }
    
    echo $tbl3->display();
}

?>
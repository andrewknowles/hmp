<?php
include ('includes/db_Ifxlib.php');
include ('includes/db_Mylib.php');
require ('includes/table_lib.php');

set_time_limit ( 0 );

$myIniFile = parse_ini_file ( "includes/idb.ini", TRUE );

// create the MySQL connection
$Myconfig = new Myconfig( $myIniFile ['IDBMYSQL'] ['server'], $myIniFile ['IDBMYSQL'] ['login'], $myIniFile ['IDBMYSQL'] ['password'], $myIniFile ['IDBMYSQL'] ['database'], $myIniFile ['IDBMYSQL'] ['extension'], $myIniFile ['IDBMYSQL'] ['mysqlformat'] );
$Mydb = new Mydb($Myconfig);
$Mydb->openConnection();

//Load the model table
//this table can map several old models to 1 new model therefore the old column must be unique
$modellist = $Mydb->load_values_model('model');

//Load the branch table
//several old branches can map to 1 current branch therefore old column must be unique
$branchlist = $Mydb->load_values('branch');


// create the Informix connection
$Ifxconfig = new Ifxconfig ( $myIniFile ['IDBIFX'] ['odbc'], $myIniFile ['IDBIFX'] ['login'], $myIniFile ['IDBIFX'] ['password'] );
$Ifxdb = new Ifxdb ( $Ifxconfig );
if (odbc_error ()) {
	echo "Informix connexion failed";
}

if (isset ( $_POST ['reset'] ) && $_POST ['confirm'] == 'confirm') {
	$Ifxsql1 = $Ifxdb->query1 ( "
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
	prb_bypass	 		char(10),		
	
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
	regequ_free10		char(50),
	Pro_height_mt		char(10),
	Pro_length_mt		char(10),
	Pro_width_mt		char(10));
 	
	drop table if exists reg_edi;
			
	create table reg_edi (
	sft_id			char(10),
	usr_id			char(10),
	oft_id			char(10),
	efh_doc_main	char(20),
	efh_d_event		char(20),
	efh_d_cre		char(20),
	efh_desc		char(50),
	efh_origin		char(10),
	efh_process		char(10),
	efh_cpy_id		char(10),
	efh_csc_id		char(10),
	efh_spp_id		char(10),
	efh_matchname	char(50),
	efh_notes		char(50),
	efh_counter1	char(20),
	efh_odometer1	char(20),
	efh_day_rnt		char(20),
	equ_id_dealer	char(20),
	bra_id			char(10),
	dpr_id			char(10),
	cur_id			char(10),
	esu_id			char(20),
	ean_cl_id		char(10),
	ean_sc_id		char(10),
	edi_d_cre		char(20),
	edi_amt_loc		char(20),
	regedi_free1	char(50),
	regedi_free2	char(50),
	regedi_free3	char(50),
	regedi_free4	char(50),
	regedi_free5	char(50)
	);		
						
	");
	
	echo 'reg_equ created';
	$Ifxdb->closeConnection ();
	unset ( $_POST );
}

// ----Loading from text file----
if (isset ( $_POST ['loadtext'] )) {
	$file = file ( $_POST ['filetext'] );
	$rows = 0;
	foreach ( $file as $val ) {
		list ( $part1, $part2 ) = explode ( ';', $val );
		$Ifxsql1 = $Ifxdb->query1 ( "insert into reg_csc(thr_1099, thr_active) values('" . $part1 . "','" . $part2 . "')" );
		$rows ++;
	}
	echo $rows . '  Rows loaded';
	$Ifxdb->closeConnection ();
	unset ( $_POST );
}

// ----Loading from Excel sheet----
if (isset ( $_POST ['loadexcel'] )) {

	$rows = 0;
	$excelFile = realpath ( $_POST ['fileexcel'] );
	$excelSheet = $_POST ['sheetexcel'];
	$excelDir = dirname ( $excelFile );
	$connection = odbc_connect ( "Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=$excelFile;DefaultDir=$excelDir", '', '' );
	$result = odbc_exec ( $connection, "select * from [" . $excelSheet . "$]" );
	while ( $row = odbc_fetch_array ( $result ) )
	{

/* get equipment model
//		$model=trim($row ['MODEL']);
		$group = 'OTHE';
		$series = 'xxx';
		
		foreach ($modellist as $int)
		{
			if (trim($int['old']) == trim($row ['MODEL']))
			{
				$model = $int['new'];
				$group = $int['groupe'];
				$series = $int['series'];
				break;
			}
		} */
	
// get branch code
		$branch='1HEB';
		foreach ($branchlist as $int)
		{
			if ($int['old'] == $row ['EM_BRANCH'])
			{
			$branch = $int['new'];
			break;
			}
		}
		
// get equipment manufacturer
//		$mfr = trim($row ['Irium_Make']);
//		$mfr='£XXXX£';
//		foreach ($modmfrlist as $int)
//		{
//			if (trim($int['old']) == trim($row ['EM3_MAKE']))
//			{
//				$mfr = $int['new'];
//				break;
//			}
//		}
		
// if Japan serial number is missing
//		if (strlen($row ['Full_Serial_No']) == 17)
//		{
//			$longserial = $row ['Full_Serial_No'];
//		} else {
//			$longserial = '00000000'.$row ['EM3_SERIAL'];
//		}
		
//model year
		$modyear = substr($row ['Purchase_Date'],0,4);
		
//Purchase date
		$datepurch = date("d/m/Y",strtotime($row ['Purchase_Date']) );
	
		$Ifxsql1 = $Ifxdb->query1 ( 
		"insert into reg_equ(
        afm_id,
        pmf_id,
        pmf_id_stored,
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
        pro_id_stored,
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
        bra_id,
        cur_id,
        dpr_id_track,
		egr_id,
		equ_active,
        equ_csc_eqid,
        equ_d_cre,
		equ_d_purch,
		equ_desc,
		equ_howacquired,
        equ_id_dealer,
        equ_new_used,
        equ_origin,
        equ_ownertype,
        equ_plate,	
        equ_reg_status,
		equ_serialnb,
		equ_y_purch,
		esr_id,
		owner_id,
		eqm_asset,
		eqm_assign,
		eqm_basic,
		eqm_cdel,
		eqm_cinv,		
		eqm_cord,
		eqm_cpaid,
        eqm_d_cre,
        eqm_d_upd,
        eqm_price_01,
        eqm_price_02,
        eqm_price_03,
        eqm_price_04,
        eqm_price_05,
        eqm_price_06,
        eqm_price_07,
        eqm_price_08,
        eqm_price_09,
        eqm_price_10,
        eqm_price_11,
        eqm_price_12,
        eqm_price_13,
        eqm_price_14,
        eqm_price_15,
        eqm_price_16,
        eqm_price_17,
        eqm_price_18,
        eqm_price_19,
        eqm_price_20,
        eqm_rent_status,
        eqm_sinv,
        eqm_sord,
		eqm_spaid,
        eqm_srec,
        eqm_unav_reason,
        eqm_wo_status,
				
		psk_bra_id,
		psk_dpr_id,
		eqm_psk_id,
		psk_pmf_id,
		psk_pro_id,
		eqm_psk_stktype,		
						
        cpy_id,
		prb_managed,
        psk_d_cre,
		psk_d_in,
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
     	'EQUIP',
        '" . $row ['pmf_id'] . "',
        '" . $row ['pmf_id'] . "',
        1,
        0,
        0,
        '2014-06-12 00:00:00',
        '2014-06-12 00:00:00',
        0,
        0,
        0,
        1,
        '" . $row ['Model'] . "',
        '" . $row ['Model'] . "',
        '" . $row ['Model'] . "',
        1,
        1,
        2,
        1,
        0,
        0,
        0,
        0,
        0,
        0,
        1,
        0,
        0,
        '" . $row ['Model'] . "',
        '" . $branch . "',
        'GBP',
        '500',
		'" . $row ['egr_id'] . "',
		1,
        0,
        '" . $row ['Purchase_Date'] . "',
		'" . $datepurch . "',
		'" . $row ['Model'] . "',
		'Z',
        '" . $row ['LOT'] . "',
        1,
        '91',
        0,
        '" . $row ['Serial'] . "',
        0,
		'" . $row ['Serial'] . "',
		'" . $modyear . "',
		'" . $row ['esr_id'] . "',
		'1HEB',
		0,
		0,
		1,
		1,
		1,
		1,
		1,
        '" . $row ['Purchase_Date'] . "',
        '" . $row ['Purchase_Date'] . "',
        '" . $row ['Machine_Cost'] . "',
        '" . $row ['Freight'] . "',
       	'" . $row ['Cap_Repairs'] . "',
        '" . $row ['Over/under'] . "',	
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
        0,
        0,
        0,
        0,
        0,
        0,
        0,
        1,
		1,
        1,
        1,
        '00',
        0,
				
		'" . $branch . "',
		'500',
		'CONV',	
		'" . $row ['pmf_id'] . "',
		'" . $row ['Model'] . "',
		0,
								
        'HMUK',
		1,
        '" . $row ['Purchase_Date'] . "',
        '" . $row ['Purchase_Date'] . "',
		'" . $row ['Purchase_Date'] . "',
        'CONV',
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
        0,
        0

)");

		for ($i = 1; $i <= 4; $i++)
		{
			switch ($i)
            {
                case '1':
                	$class = '500';
                    $subclass = '100';
                    $value = $row ['Machine_Cost'];
                    break;
                case '2':
                	$class = '520';
                    $subclass = '121';
                    $value = $row ['Freight'];
                    break;
                case '3':
                	$class = '560';
                   	$subclass = '120';
                   	$value = $row ['Cap_Repairs'];
                    break;
               case '4':
               		$class = '160';
                   	$subclass = '110';
                   	$value = $row ['Over/under'];
                    break;
            }


		$Ifxsql2 = $Ifxdb->query1 ( 
		"insert into reg_edi(
		usr_id,
		efh_doc_main,
		efh_origin,
		efh_cpy_id,
		efh_notes,
		equ_id_dealer,
		bra_id,
		dpr_id,
		cur_id,
		esu_id,
		edi_d_cre,
		ean_cl_id,
		ean_sc_id,
		edi_amt_loc
				
		) values (
		'0000',
		'CONV',
		'90',
		'HMUK',
		'Data Conversion',
		'" . $row ['LOT'] . "',
		'" . $branch . "',
		'500',
		'GBP',
		0,
		'" . $row ['Purchase_Date'] . "',
		'" . $class . "',
		'" . $subclass . "',
		'" . $value . "')");
		
}


		$rows ++;
	}
	echo $rows . '  Rows loaded</BR>';
	
	$Ifxsql1 = $Ifxdb->query1 ('select count(*) from reg_equ where pmf_id = "£XXXX£"');
	echo odbc_result($Ifxsql1,1).'  Manufacturers with default value £XXXX£</BR>';
	
	$Ifxsql1 = $Ifxdb->query1 ('select count(*) from reg_equ where pro_id = "£XXXX£"');
	echo odbc_result($Ifxsql1,1).'  Models with default value £XXXX£</BR>';
	
	$Ifxsql1 = $Ifxdb->query1 ('select count(*) from reg_equ where owner_id = "£XXXX£"');
	echo odbc_result($Ifxsql1,1).'  Records with default value for customer £XXXX£</BR>';
	
	$Ifxsql1 = $Ifxdb->query1 ('select count(*) from reg_equ where bra_id = "£XXXX£"');
	echo odbc_result($Ifxsql1,1).'  Branch with default value £XXXX£</BR>';
	
	update_owner($Ifxsql1, $Ifxdb);
	echo 'Owners updated';
	
	unset ( $_POST );
	// display_rows($Ifxsql1,$Ifxdb);

}
$Mydb->closeConnection();

function update_owner($res, $obj)
{
	$obj->query1("update reg_equ set owner_id = '£XXXX£' where not exists (select * from csc where csc_id = owner_id)");
}

?>
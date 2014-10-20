<?php
include('includes/db_Ifxlib.php');
require('includes/table_lib.php');
include ('includes/db_Mylib.php');

set_time_limit(0);

$myIniFile = parse_ini_file("includes/idb.ini", TRUE);

// create the MySQL connection
$Myconfig = new Myconfig( $myIniFile ['IDBMYSQL'] ['server'], $myIniFile ['IDBMYSQL'] ['login'], $myIniFile ['IDBMYSQL'] ['password'], $myIniFile ['IDBMYSQL'] ['database'], $myIniFile ['IDBMYSQL'] ['extension'], $myIniFile ['IDBMYSQL'] ['mysqlformat'] );
$Mydb = new Mydb($Myconfig);
$Mydb->openConnection();

//Load the branch table
//several old branches can map to 1 current branch therefore old column must be unique
$branchlist = $Mydb->load_values('branch');
$countylist = $Mydb->load_values('county');

$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb     = new Ifxdb($Ifxconfig);

if (isset($_POST['reset']) && $_POST['confirm'] == 'confirm')
  {
    $Ifxsql1 = $Ifxdb->query1("
	   
    drop table if exists reg_csc;
    
    create table reg_csc (
    thr_1099			char(10),
    thr_active			char(10),
    thr_d_cre			char(20),
    thr_d_upd			char(20),
    thr_dun_brad		char(20),
    thr_eeccarriage		char(10),
    thr_eecdel			char(10),
    thr_eecstat			char(10),
    thr_fiscal_month	char(10),
    thr_naf				char(20),
    thr_organization	char(10),
    thr_registration	char(30),
    cny_id				char(10),
    cpy_id				char(10),
    cry_id				char(10),
    cty_id				char(10),
    lng_id				char(10),
    spp_id				char(10),
    sta_id				char(10),
    tad_active			char(10),
    tad_addr2			char(50),
    tad_addr3			char(50),
    tad_addr4			char(50),
    tad_car_route		char(10),
    tad_city			char(50),
    tad_cpy_name		char(50),
    tad_delivery		char(10),
    tad_invoice			char(10),
    tad_mail			char(10),
    tad_map_locat		char(10),
    tad_name_first		char(30),
    tad_name_last		char(30),
    tad_name_mid		char(30),
    tad_order			char(10),
    tad_paymnt			char(10),
    tad_suffix			char(10),
    tad_temporary		char(10),
    tad_title			char(10),
    tad_zipcode			char(10),
    tad_zone_loc		char(10),
    txg_id				char(10),
    cgr_id				char(10),
    csc_cpy_id			char(10),
    csc_accrec			char(10),
    csc_axe_1			char(10),
    csc_axe_2			char(10),
    csc_axe_3			char(10),
    csc_axe_4			char(10),
    csc_bo				char(10),
    csc_d_cre			char(20),
    csc_d_upd			char(20),
    csc_del				char(10),
    csc_eqp_limit		char(20),
    csc_gencod			char(20),
    csc_icgrp			char(10),
    csc_icopy			char(10),
    csc_id				char(10),
    csc_id_del			char(10),
    csc_id_inv			char(10),
    csc_id_pays			char(10),
    csc_ins_limit		char(20),
    csc_itgrp			char(10),
    csc_last_invoice	char(20),
    csc_last_payment	char(20),
    csc_lterm			char(10),
    csc_measure			char(10),
    csc_our_acc			char(10),
    csc_pdel			char(10),
    csc_pinv			char(10),
    csc_pord			char(10),
    csc_price_col		char(10),
    csc_rdate			char(10),
    csc_rdaywhen		char(10),
    csc_ref_mandat		char(10),
    csc_reqpt			char(10),
    csc_ret_limit		char(20),
    csc_ret_period		char(10),
    csc_rinsur			char(10),
    csc_rmonth			char(20),
    csc_rnt_limit		char(20),
    csc_rwhen			char(10),
    csc_scolumn			char(10),
    csc_scprt			char(10),
    csc_sday			char(10),
    csc_ship_delay		char(10),
    csc_ship_rules		char(10),
    csc_simeth			char(10),
    csc_sins_value		char(10),
    csc_siprt			char(10),
    csc_sitype			char(10),
    csc_solvency		char(10),
    csc_track_stat		char(10),
    csc_trk_limit		char(20),
    csc_wrk_limit		char(20),
    csc_wrk_limit_ro	char(20),
    csc_year_limit		char(10),
    cur_id				char(10),
    dgr_id				char(10),
    oer_id				char(10),
    oft_id				char(10),
    olt_id				char(10),
    plc_id				char(10),
    cif_id				char(10),
    cbk_active			char(10),
    cbk_bkdigit			char(10),
    cbk_contact			char(50),
    cbk_d_exp			char(20),
    cbk_default			char(10),
    cbk_digit			char(10),
    cbk_id				char(34),
    cbk_limit			char(20),
    cbk_name			char(50),
    cbk_phone			char(50),
    pay_id				char(10),
    cbk_sortcode		char(20),
    cbk_routing			char(20),
    cbk_swift			char(20),
    cbk_cry_id			char(10),
    tph_desc			char(50),
    tph_extension		char(10),
    tph_nb				char(50),
    tph_type			char(10),
    tcm_bra_id			char(10),
    tcm_dpr_id			char(10),
    tcm_d_cre			char(20),
    tcm_d_upd			char(20),
    tcm_id				char(20),
    tcm_notes			char(50),
    tcm_type			char(10),
    tcm_usr_id			char(10),
    tcm_cpy_id    		char(4) ,
    cbr_bra_id			char(10),
    cbr_main			char(10),
    cbr_rank			char(10),
    act_d_lastcutoff	char(20),
    atp_id				char(10),
    gca_id				char(10),
    gla_id				char(20),
    atp_id_st			char(10),
    act_cl_grace		char(10),
    act_st_freq			char(10),
    act_rm_apply		char(10),
    act_rm_grace		char(10),
    act_rm_int			char(10),
    act_rm_rate			char(10),
    act_rm_sc			char(10),
    act_rm_amt			char(20),
    act_rm_maxi			char(10),
    act_rm_lev1			char(10),
    act_rm_lev2			char(10),
    act_rm_lev3			char(10),
    act_solvency_1		char(10),
    act_solvency_2		char(10),
    act_solvency_3		char(10),
    act_avg_days		char(10),
    cfd_id				char(20),
    act_pay_id			char(10),
    act_mat_auto		char(10),
    act_active			char(10),
    act_ddday			char(10),
    act_ddduration		char(10),
    act_ddcalculation	char(10),
    act_ddmd			char(10),
    act_statement		char(20),
    act_cr_limit		char(20),
    act_year_limit		char(10),
    act_high_bal		char(20),
    act_reminder		char(20),
    tph_desc2			char(50),
    tph_nb2				char(50),
    tph_type2			char(10),
    ddr_bra_id			char(10),
    trl_title			char(5),
    trl_name_first		char(20),
    trl_name_mid		char(20),
    trl_name_last		char(20),
    trl_suffix			char(5),
    trl_formula			char(50),
    trl_addr2			char(50),
    trl_addr3			char(50),
    trl_addr4			char(50),
    trl_cry_id			char(50),
    trl_sta_id			char(50),
    trl_cny_id			char(50),
    trl_cty_id			char(50),
    trl_city			char(50),
    trl_zipcode			char(50),
    trl_lng_id			char(50),
    trl_d_birth			char(10),
    trl_free1			char(10),
    trl_free2			char(10),
    trl_free3			char(10),
    trl_nickname		char(20),
    trl_gender			char(10),
    trl_nbchild			char(10),
    trl_notes			char(50),
    trl_spouse_birth	char(10),
    trl_spouse_name		char(20),
    csc_free1			char(50),
    csc_free2			char(50),
    csc_free3			char(50),
    csc_free4			char(50),
    csc_free5			char(50),
    csc_free6			char(50),
    csc_free7			char(50),
    csc_free8			char(50),
    csc_free9			char(50),
    csc_free10			char(50))");
    
    echo 'reg_csc created';
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
    $rows        = 0;
    $loaded_rows = 0;
    $excelFile   = $_POST['fileexcel'];
    $excelSheet  = $_POST['sheetexcel'];
    $excelDir    = dirname($excelFile);
    $drv         = "Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=" . $excelFile . ";DefaultDir=" . $excelDir;
    $connection  = odbc_connect($drv, 'informix', 'informix');
    $result      = odbc_exec($connection, "select * from [" . $excelSheet . "$]");
    while ($row = odbc_fetch_array($result))
      {
// Main select on records from Excel - the column NA_CARRIER is maintained by HCMUK
        if ($row['NA_CARRIER'] == 1)
          {
//			if (($row ['NA_CARRIER'] == 1) || ($row ['Plant Sales Merges To'] == 'Machinery Only')) {
// round customer numbers from Excel to remove decimals
//      		if ($row ['NA_CARRIER'] == 1)
//      			{
//      				$custtype = 'C';
//      			}
//      		else 
//      			{
//      				$custtype = "O";
//      			}
      	
            if ($excelSheet == 'Sheet1')
              {
                $custno = $row['NA_CUSTOMER'];
              }
            else
              {
                $custno = round($row['NA_CUSTOMER'], 0);
                if (strlen($custno)<6)
                {
                	$custno = '0'.$custno;
                }
              }
// Recoding
            if ($row['NA_COUNTRY'] == 'FR')
              {
                $custstate = '06';
              }
            elseif ($row['NA_COUNTRY']=='GB' || $row['NA_COUNTRY']=='IE')
            {
            	$custstate = trim($row['NA_STATE']);
//            	foreach ($countylist as $int)
//            	{
//            		if (trim($int['old']) == trim($row['NA_STATE']))
//            		{
//            			$custstate = $int['old'];
//            			break;
//            		}
//            	}
            }
            else
              {
                $custstate = null;
              }
                                          
              
// customer name - strip bad characters
            $custname = trim($row['NA_NAME']);
            $custname = str_replace("'", "", $custname);
            $custname = str_replace("*", "-", $custname);
//            $custname = ucwords(strtolower($custname)); 
// tad_city cannot be null
            if (strlen(trim($row['NA_CITY'])) == 0)
              {
                $custcity = 'XXX';
              }
            else
              {
                $custcity = $row['NA_CITY'];
                $custcity = str_replace("'", "", $custcity);
              }
            if (strlen($row['NA_ADDRESS_1']) == 0)
              {
                $custadr1 = '.';
              }
            else
              {
                $custadr1 = $row['NA_ADDRESS_1'];
                $custadr1 = str_replace("'", "", $custadr1);
//                $custadr1 = ucwords(strtolower($custadr1));
              }
            if (strlen($row['NA_ADDRESS_2']) == 0)
              {
                $custadr2 = '.';
              }
            else
              {
                $custadr2 = $row['NA_ADDRESS_2'];
                $custadr2 = str_replace("'", "", $custadr2);
//                $custadr2 = ucwords(strtolower($custadr2));
              }
            if (strlen($row['NA_ADDRESS_3']) == 0)
              {
                $custadr3 = '.';
              }
            else
              {
                $custadr3 = $row['NA_ADDRESS_3'];
                $custadr3 = str_replace("'", "", $custadr3);
 //               $custadr3 = ucwords(strtolower($custadr3));
              }
// reformat post codes
            if (strlen($row['NA_ZIP_PREFIX'] . $row['NA_ZIP_SUFFIX']) == 0)
              {
                $custpc = 'NO_PC';
              }
            else
              {
                $custpc = $row['NA_ZIP_PREFIX'] . " " . $row['NA_ZIP_SUFFIX'];
              }
// country codes
            switch ($row['NA_COUNTRY'])
            {
                case 'GU':
                    $countrycode = 'GG';
                    break;
                case 'JA':
                    $countrycode = 'JP';
                    break;
                case '':
                    $countrycode = 'GB';
                    break;
                default:
                    $countrycode = $row['NA_COUNTRY'];
            }
// VAT Code conversions
            switch ($row['NA_TAX_CODE'])
            {
                case 'V1':
                    $taxcode = 'CSTD';
                    break;
                case 'VT':
                    $taxcode = 'INT';
                    break;
                default:
                    $taxcode = 'CEXP';
            }
// Phone Numbers
            if (strlen($row['NA_PHONE']) == 0)
              {
                $custphone = '';
              }
            else
              {
                $custphone = $row['NA_PHONE'];
              }
// Due date conversions
            switch ($row['NA_TERMS'])
            {
//Prepayment NA_TERMS = 1 or ST
            	case '1':
            		$ddduration = 0;
            		$ddcalculation = 1;
            		$ddmd = 0;            		
            		break;
            		case 'ST':
            		$ddduration = 0;
            		$ddcalculation = 1;
            		$ddmd = 0;
            		break;
//??? NA_TERMS = 3
            		case '3':
            		$ddduration = 1;
            		$ddcalculation = 0;
            		$ddmd = 1;
            		break;
//30 days EOM  NA_TERMS = CR - this is converted to 1 Month EOM
            	case 'CR':
                    $ddduration = 1;
                    $ddcalculation = 0;
                    $ddmd = 1;
                    break;
                case '45':
                    $ddduration = 45;
                    $ddcalculation = 0;
                    $ddmd = 0;                 
                    break;
                case '60':
                    $ddduration = 2;
                    $ddcalculation = 0;
                    $ddmd = 1;                    
                    break;
                default:
                    $duedays = $row['NA_TERMS'];
            }
//            $akduedays = 'XX';
// Due date calculation
//			if ($duedays == 0){
//				$ddcalculation = 1;
//			} else {
//				$ddcalculation = 0;
//			}
			
// Solvency
            switch ($row['NA_TERMS'])
            {
                case '1':
                    $custsolvency = 2;
                    break;
                case 'ST':
                    $custsolvency = 5;
                    break;
                default:
                    $custsolvency = 0;
            }
            
// get branch code
            $custbranch='1HEB';
            foreach ($branchlist as $int)
            {
            	if ($int['old'] == $row ['NA_ASSIGNED_BRANCH'])
            	{
            		$custbranch = $int['new'];
            		break;
            	
            	}
            }

//PO Required
            switch ($row['NA_PO_REQUIRED'])
            {
                case 'Y':
                    $custporequired = 2;
                    break;
                default:
                    $custporequired = 0;
            }
            
 //Customert Category
            if (($row['NA_CATEGORY']) == 'W')
            {
            	$category = '';
            }
            else
            {
            	$category = $row['NA_CATEGORY'];
            }
            
            $Ifxsql1 = $Ifxdb->query1("insert into reg_csc(
				thr_1099, 
				thr_active,
		        thr_d_cre,
		        thr_d_upd,
		        thr_organization,
		        thr_registration,
		        cry_id,
				lng_id,
				sta_id,
		        tad_active,
				tad_addr2,
				tad_addr3,
		        tad_addr4,
				tad_city,		
		        tad_cpy_name,
				tad_delivery,
				tad_invoice,
				tad_mail,
				tad_order,
		        tad_paymnt,
				tad_zipcode,
				txg_id,
				csc_cpy_id,
		        csc_accrec,
		        csc_bo,
		        csc_d_cre,
		        csc_d_upd,
		        csc_del,
		        csc_eqp_limit,
		        csc_icgrp,
		        csc_icopy,
		        csc_id,
		        csc_id_del,
		        csc_id_inv,
		        csc_id_pays,
				csc_itgrp,
		        csc_measure,
		        csc_pdel,
		        csc_pinv,
		        csc_pord,
				csc_ref_mandat,		
				csc_ret_limit,
				csc_solvency,
		        csc_track_stat,
		        cur_id,
				plc_id,
				pay_id,
		        tph_nb,
		        tph_type,
				tcm_type,
				cbr_bra_id,
				cbr_main,
				cbr_rank,
				atp_id,
				gca_id,
				gla_id,
				atp_id_st,
				act_st_freq,
				act_ddday,
		        act_ddduration,
		        act_ddcalculation,
		        act_ddmd,
				ddr_bra_id,
				csc_axe_2,
            	csc_free1,
            	csc_free2
				)										
				values (
				0,
				1,
		        '" . $row['NA_NEW_IN_SYSTEM'] . "',
		        '" . $row['NA_LAST_UPDATED'] . "',
		        0,
		        '" . $row['NA_TAX_ID'] . "',
		        '" . $countrycode . "',
		        0,
				'" . $custstate . "',
		        1,
		       	'" . $custadr1 . "',
		       	'" . $custadr2 . "',
		       	'" . $custadr3 . "',
		        '" . $custcity . "',
		        '" . $custname . "',
		        1,
		        1,
		        1,
		        1,
		        1,
		        '" . $custpc . "',
		        '" . $taxcode . "',
		        'HMUK',
		        1,
		        0,
		        '" . $row['NA_NEW_IN_SYSTEM'] . "',
		        '" . $row['NA_LAST_UPDATED'] . "',
		        0,
		        '" . $row['NA_MACHINE_LIMIT'] . "',
		        0,
		        1,
		        '" . $custno . "',
		        '" . $custno . "',
		        '" . $custno . "',
		        '" . $custno . "',
		        0,
		        1,
		        0,
		        0,
		        0,
				'" . $custporequired . "',
		        '" . $row['NA_CREDIT_LIMIT'] . "',
		        '" . $custsolvency . "',
		        1,
		        'GBP',
				'LIST',
				'CRDT',
		        '" . $custphone . "',
		        0,
				0,
				'" . $custbranch . "',
				1,
				1,
		        '01',
				'COA',
				'122010',
				'01',
				2,
		        0,
		        '" . $ddduration . "',
            	'" . $ddcalculation . "',
		        '" . $ddmd. "',
				'1HEB',
				'" . $category . "',
            	'" . $row['NA_TERMS'] . "',
            	'" . $ddduration . "'
				)");
            
            $rows++;
          }
        $loaded_rows++;
      }
    echo $rows . '  Rows loaded</BR>';
    echo $loaded_rows . '  Rows processed</BR>';
//remove old cash accounts
    $Ifxsql1 = $Ifxdb->query1("delete from reg_csc where csc_id in ('340185','340186','340187','340188','350184','350185','350186','350187','350188','360184',
'360185','360186','360187','360188','370184','370185','370186','370187','370188','390125',
'390185','390186','390187','390188','410167','410183','410184','410185','410186','410187',
'410188','420184','420185','420186','420187','420188','440183','440184','440185','440186',
'440187','440188','450001','450002','450003','450004','310184','310185','310186','310187',
'310188','330128','330154','330158','330182','330183','330184','330185','330186','330187',
'330188','320184','320185','320186','320187','320188')");
    		
    $Ifxdb->closeConnection();
    unset($_POST);
  }

?>
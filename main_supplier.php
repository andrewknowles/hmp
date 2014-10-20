<?php
include('includes/db_Ifxlib.php');
require('includes/table_lib.php');
include('includes/db_Mylib.php');
set_time_limit(0);
$myIniFile = parse_ini_file("includes/idb.ini", TRUE);
// create the MySQL connection
$Myconfig = new Myconfig($myIniFile['IDBMYSQL']['server'], $myIniFile['IDBMYSQL']['login'], $myIniFile['IDBMYSQL']['password'], $myIniFile['IDBMYSQL']['database'], $myIniFile['IDBMYSQL']['extension'], $myIniFile['IDBMYSQL']['mysqlformat']);
$Mydb     = new Mydb($Myconfig);
$Mydb->openConnection();
//load manufacturer conversion table from MySQL
$countylist = $Mydb->load_values('county');

$myIniFile = parse_ini_file("includes/idb.ini", TRUE);
$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb     = new Ifxdb($Ifxconfig);

if (isset($_POST['reset']) && $_POST['confirm'] == 'confirm')
  {
    $Ifxsql1 = $Ifxdb->query1("
		drop table if exists reg_spp;
						
		create table reg_spp (
		thr_1099		char(50),
		thr_active		char(50),
		thr_d_cre		char(50),
		thr_d_upd		char(50),
		thr_dun_brad		char(50),
		thr_eeccarriage		char(50),
		thr_eecdel		char(50),
		thr_eecstat		char(50),
		thr_fiscal_month	char(50),
		thr_naf			char(50),
		thr_organization	char(50),
		thr_registration	char(50),
		
		cny_id			char(10),
		cpy_id			char(10),
		cry_id			char(10),
		cty_id			char(10),
		lng_id			char(10),
		spp_id			char(50),
		sta_id			char(10),
		tad_active		char(10),
		tad_addr2		char(50),
		tad_addr3		char(50),
		tad_addr4		char(50),
		tad_car_route		char(50),
		tad_city		char(50),
		tad_cpy_name		char(50),
		tad_delivery		char(10),
		tad_invoice		char(10),
		tad_mail		char(10),
		tad_map_locat		char(50),
		tad_name_first		char(50),
		tad_name_last		char(50),
		tad_name_mid		char(50),
		tad_order		char(50),
		tad_paymnt		char(50),
		tad_suffix		char(50),
		tad_temporary		char(50),
		tad_title		char(50),
		tad_zipcode		char(50),
		tad_zone_loc		char(50),
		txg_id			char(50),
		
		--bra_id			char(50),
		spp_cpy_id		char(50),
		cur_id			char(50),
		--dpr_id			char(50),
		spp_lng_id		char(50),
		spp_active		char(50),
		spp_amount_mini		char(50),
		spp_axe_1		char(50),
		spp_axe_2		char(50),
		spp_axe_3		char(50),
		spp_axe_4		char(50),
		spp_d_cre		char(50),
		spp_d_upd		char(50),
		spp_ddcalculation	char(50),
		spp_ddday		char(50),
		spp_ddduration		char(50),
		spp_ddmd		char(50),
		spp_gencod		char(50),
		spp_spp_id		char(50),
		--spp_id_broker		char(50),
		--spp_id_pays		char(50),
		spp_last_busines	char(50),
		spp_maxi_ip		char(50),
		spp_maxi_visa		char(50),
		spp_measure		char(50),
		spp_mini_ship		char(50),
		spp_ocopy		char(50),
		spp_omethod		char(50),
		--spp_oprogram		char(50),
		spp_oprt		char(50),
		spp_osort		char(50),
		spp_pmethod		char(50),
		spp_pprogra		char(50),
		spp_purl		char(50),
		spp_shp_group		char(50),
		spp_wapmethod		char(50),
		spp_receipt_rules		char(50),
		spp_weight_mini		char(50),
		spp_weight_unit		char(50),
		spp_customs_rules		char(50),
		--spp_mat_letter	char(50),
		
		tph_desc		char(50),
		tph_extension		char(50),
		tph_nb			char(50),
		tph_type		char(50),
		
		tph_desc2		char(50),
		tph_nb2			char(50),
		tph_type2		char(50),
		
		tph_desc3		char(50),
		tph_nb3			char(50),
		tph_type3		char(50),
		
		tcm_bra_id		char(50),
		tcm_dpr_id		char(50),
		tcm_d_cre		char(50),
		tcm_d_upd		char(50),
		tcm_id			char(50),
		tcm_notes		char(50),
		tcm_type		char(50),
		tcm_usr_id		char(50),
		tcm_cpy_id    		char(4),
		
		ocr_id			char(50),
		sor_acct		char(50),
		sor_default		char(50),
		sor_desc		char(50),
		sor_emr_sto		char(50),
		sor_freight_coef	char(50),
		sor_id			char(50),
		sor_priceorigin		char(50),
		sor_ship_delay		char(50),
		sor_ship_rules		char(50),
		
		smn_bra_id		char(50),
		pmf_id			char(50),
		smn_default		char(50),
		smn_rank		char(50),
		smn_tariff		char(50),
		
		spb_bra_id		char(50),
		cfd_id			char(50),
		crt_id			char(50),
		pay_id			char(50),
		spb_count_cur		char(50),
		spb_count_end		char(50),
		spb_count_start		char(50),
		spb_dealer_id		char(50),
		spb_id_letter		char(50),
		
		atp_id			char(50),
		gca_id			char(50),
		gla_id			char(50),
		atp_id_pay		char(50),
		ast_ddday		char(50),
		ast_ddduration		char(50),
		ast_ddcalculation	char(50),
		ast_ddmd		char(50),
		ast_active		char(50),
		ast_seppay		char(50),
		ast_letter		char(50),
		ast_holdpay		char(50),
		ast_attreq		char(50),
		ast_model		char(50),
		
		cbk_active		char(50),
		cbk_bkdigit		char(10),
		cbk_contact		char(50),
		cbk_d_exp		char(20),
		cbk_default		char(10),
		cbk_digit		char(10),
		cbk_id			char(50),
		cbk_limit		char(20),
		cbk_name		char(50),
		cbk_phone		char(50),
		cbk_pay_id		char(10),
		cbk_sortcode		char(20),
		cbk_swift			char(20),
		cbk_routing		char(20),
		cbk_cry_id		char(3),
		
		
		spp_free1		char(50),
		spp_free2		char(50),
		spp_free3		char(50),
		spp_free4		char(50),
		spp_free5		char(50),
		spp_free6		char(50),
		spp_free7		char(50),
		spp_free8		char(50),
		spp_free9		char(50),
		spp_free10		char(50))");
    
    echo 'reg_spp created';
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
        $Ifxsql1 = $Ifxdb->query1("insert into reg_spp(thr_1099, thr_active) values('" . $part1 . "','" . $part2 . "')");
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
//    		$result = odbc_exec ( $connection, "select * from [" . $excelSheet . "$]" );
    $dd          = odbc_connect("HMPEXCELAP", "user", "password");
    $result      = odbc_exec($dd, "select * from [AP_Names_2$]");
    
    while ($row = odbc_fetch_array($result))
      {
        // Main select on records from Excel - the column AP_CARRIER is maintained by HCMUK
        //			if (($row ['AP_CARRIER'] == 1) || ($row ['AP_FLAG'] == 'Mach Only')) {
        if ($row['AP_CARRIER'] == 1)
          {
// round customer numbers from Excel to remove decimals
            $suppno = round($row['AP_VENDOR'], 0);
            if (strlen($suppno)<6)
            {
            	$suppno = '0'.$suppno;
            }
            
            // Recoding County/State
            if ($row['AP_COUNTRY'] == 'FR' && $row['AP_VENDOR'] == 60217)
              {
                $suppstate = '42';
              }
            elseif ($row['AP_COUNTRY'] == 'FR' && $row['AP_VENDOR'] == 90163)
              {
                $suppstate = '17';
              }
            elseif ($row['AP_COUNTRY'] == 'GB' || $row['AP_COUNTRY'] == 'IE')
              {
                $suppstate = '100';
                foreach ($countylist as $int)
                {
                	if (trim($int['new']) == trim($row['AP_COUNTY_CODE']))
                	{
                		$suppstate = $int['old'];
                		break;
                	}
                }
              }
            // customer name
            $suppname = trim($row['AP_NAME']);
            $suppname = str_replace("'", " ", $suppname);
            $suppname = str_replace("*", "-", $suppname);
//            $suppname = ucwords(strtolower($suppname));
            
            // tad_city cannot be null
//            if (strlen(trim($row['AP_CITY'])) == 0)
//              {
//                $custcity = 'XXX';
//              }
//            else
//              {
//                $custcity = $row['AP_CITY'];
//              }
              
            if (strlen($row['AP_ADDRESS_1']) == 0)
              {
                $custadr1 = '.';
              }
            else
              {
                $custadr1 = $row['AP_ADDRESS_1'];
                $custadr1 = str_replace("'", "", $custadr1);
//                $custadr1 = ucwords(strtolower($custadr1));
              }
              
            if (strlen($row['AP_ADDRESS_2']) == 0)
              {
                $custadr2 = '.';
              }
            else
              {
                $custadr2 = $row['AP_ADDRESS_2'];
                $custadr2 = str_replace("'", "", $custadr2);
//                $custadr2 = ucwords(strtolower($custadr2));
              }
              
/*            if (strlen($row['AP_ADDRESS_3']) == 0)
              {
                $custadr3 = '.';
              }
            else
              {
                $custadr3 = $row['AP_ADDRESS_3'];
                $custadr3 = str_replace("'", "", $custadr3);
                $custadr3 = ucwords(strtolower($custadr3));
              }
*/
                            
              if (strlen($row['AP_POST_TOWN']) == 0)
              {
              	$custcity = '.';
              }
              else
              {
              	$custcity = $row['AP_POST_TOWN'];
              	$custcity = str_replace("'", "", $custcity);
//              	$custcity = ucwords(strtolower($custcity));
              }
              
            // reformat post codes
            if (strlen($row['AP_ZIP_PREFIX'] . $row['AP_ZIP_SUFFIX']) == 0)
              {
                $custpc = 'NO_PC';
              }
            else
              {
                $custpc = $row['AP_ZIP_PREFIX'] . " " . $row['AP_ZIP_SUFFIX'];
              }
            // country codes
            switch ($row['AP_COUNTRY'])
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
                    $countrycode = $row['AP_COUNTRY'];
            }
            //				if (strlen($countrycode == 0)){
            //					$countrycode = 'GB';
            //				}			
            // VAT Code conversions
            switch ($row['AP_COUNTRY'])
            {
                case 'GB':
                    $taxcode = 'SSTD';
                    break;
                default:
                    $taxcode = 'SIMEEC';
            }
            // Phone Numbers
            if (strlen($row['AP_PHONE']) == 0)
              {
                $custphone = '.';
              }
            else
              {
                $custphone = $row['AP_PHONE'];
              }
            
            // Fax Numbers
            if (strlen($row['AP_FAX_PHONE']) == 0)
              {
                $custfax = '.';
              }
            else
              {
                $custfax = $row['AP_FAX_PHONE'];
              }
              
            // Due dates
              switch ($row['AP_DUE_DAYS'])
              {
              	case '0':
              		$days = 0;
              		$md = 1;
              		$calc = 1;
              		break;
              	case '30':
              		$days = 1;
              		$md = 1;
              		$calc = 0;
              		break;
              	case '60':
              		$days = 2;
              		$md = 1;
              		$calc = 0;
              		break;
              	case '90':
              		$days = 3;
              		$md = 1;
              		$calc = 0;
              		break;
              	default:
              		$days = $row['AP_DUE_DAYS'];
              		$md = 1;
              		$calc = 0;
              		break;              	
              }

//              echo 	'supstate'.$suppstate .'</BR>';
//		      echo 	'adr1'. $custadr1 .'</BR>';
//		      echo 	'adr2'. $custadr2 .'</BR>';   	
//		      echo 	'city'. $custcity . '</BR>';
//           	  echo 	'adr3'. $custadr3 . '</BR>';
//           	  break;
 // Supplier Codes

 			if (strlen($suppno)<6)
 			{
 				$suppno = '0'.$suppno;
 			}
            
            $Ifxsql1 = $Ifxdb->query1("insert into reg_spp(
				thr_1099, 
				thr_active,
		        thr_d_cre,
		        thr_d_upd,
		        thr_organization,
				thr_registration,
		        cry_id,
				lng_id,
				spp_id,
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
				spp_cpy_id,
		        cur_id,
				spp_lng_id,
				spp_active,
				spp_d_cre,
				spp_d_upd,
				spp_ddcalculation,
				spp_ddday,
				spp_ddduration,
				spp_ddmd,
				spp_spp_id,
				spp_ocopy,
				spp_omethod,
				spp_oprt,
				spp_osort,
				spp_pmethod,
            	spp_shp_group,
				spp_customs_rules,
				tph_nb,
				tph_type,
				tph_nb2,
				tph_type2,
				sor_default,
				sor_desc,
				sor_emr_sto,
				sor_id,
				sor_priceorigin,
            	spb_bra_id,
				pay_id,		
            		
				atp_id,
				gca_id,
				gla_id,
            	atp_id_pay,
				ast_ddday,
				ast_ddduration,
				ast_ddcalculation,
				ast_ddmd,
				ast_active,
            	ast_letter,
            	ast_model

				)										
				values (
				0,
				1,
		        '" . $row['AP_DATE'] . "',
		        '" . $row['AP_DATE'] . "',
		        0,
				'" . $row['AP_TAX_ID'] . "',		
		        '" . $countrycode . "',
		        0,
				'" . $suppno . "',
				'" . $suppstate . "',	
		        1,
		       	'" . $custadr1 . "',
		       	'" . $custadr2 . "',
            	'.',      	
            	'" . $custcity . "',
		        '" . $suppname . "',	
		        1,
		        1,
		        1,
				1,
				1,
		        '" . $custpc . "',
		        '" . $taxcode . "',
		        'HMUK',
		        'GBP',
		        0,
				1,
		        '" . $row['AP_DATE'] . "',
		        '" . $row['AP_DATE'] . "',
		        0,
				0,
		        '" . $row['AP_DUE_DAYS'] . "',
		        0,
		        '" . $suppno . "',
		        1,
		        '00',
		        0,
		        0,
		        '00',
            	2,
				0,
				'" . $row['AP_PHONE'] . "',
				0,
				'" . $row['AP_FAX_PHONE'] . "',
				1,
				1,
				'Emergency',
				1,
				'EMG',
				0,
            	'1HEB',
            	'EFT',	
				'03',
				'COA',
				'151010',
            	'03',
				0,
				'" . $days . "',
				0,
				0,
				1,
            	1,
            	'EOD'
				)");
            
            $rows++;
          }
        $loaded_rows++;
      }
    echo $rows . '  Rows loaded</BR>';
    echo $loaded_rows . '  Rows processed</BR>';
    $Ifxdb->closeConnection();
    unset($_POST);
    
  }


?>
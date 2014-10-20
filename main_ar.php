<?php
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

//Load the customer table
//this table must contain 1=1 maps of all converted customers
//plus mapping for old machine customer accounts to map to new accounts
//old customer accounts must be unique
$customerlist = $Mydb->load_values('customer');

$myIniFile = parse_ini_file ("includes/idb.ini", TRUE);
$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb = new Ifxdb($Ifxconfig);

if (isset($_POST['reset']) && $_POST['confirm'] == 'confirm'){    
	$Ifxsql1 = $Ifxdb->query1("
	drop table if exists reg_ar;

	create table reg_ar (
	cpy_id		char(10),
	gbt_id		char(10),
	cur_id_bra	char(10),
	usr_id		char(10),
	gca_id		char(10),
	gla_id		char(20),
	gtr_d_ent	char(20),
	gtr_d_trs	char(20),
	gtr_entry_id	char(20),
	gtr_doc_id	char(30),
	gtr_cash_id	char(30),
	gtr_desc	char(50),
	gtr_notes	char(50),
	gtr_d_value	char(20),
	gtr_qty		char(10),
	gtr_debit	char(20),
	gtr_credit	char(20),
	gtr_deb_cur	char(20),
	gtr_acc_rate	char(10),
	gtr_rec_letter	char(20),	-- To_Do_1.07.000.149
	gtr_aud_code	char(10),
	gtr_auto_offset	char(10),
	-- gtr_temporary	char(10),	To_Do_1.07.000.149
	gtr_free1	char(30),
	gtr_free2	char(30),
	gtr_amt_oth	char(20),
	gtr_status	char(10),
	gtr_status_prt	char(10),
	gtr_rec_id	char(20),
	gtr_rec_status	char(10),
	-- gtr_ptg_letter	char(10),	To_Do_1.07.000.149
	gtr_pay_assoc	char(10),
	gtr_free3	char(30),
	gtr_ent_type	char(10),
	bra_id		char(10),
	atp_id		char(10),
	cur_id_doc	char(10),
	gtr_cre_cur	char(20),
	gtr_basis	char(20),
	gtr_statement	char(20),
	dpr_id		char(10),
	gtr_posted	char(10),
	gpr_id		char(10),
	gpr_year	char(10),
	cfd_id		char(10),
	gtr_id_master	char(10),
	gtr_accrual	char(10),
	gtr_glarap	char(10),
	gtr_disputed	char(10),
	usr_id_audit	char(20),
	oih_id		char(10),
	gtr_mat_code	char(10),
	gtr_slip_id	char(20),
	gtr_rank	char(10),
	gtr_voucher	char(20),
	gtr_sfh_doc_id	char(20),
	gtr_1099	char(10),
	gtr_attreq	char(10),
	gll_id		char(10),
	csc_id		char(10),
	spp_id		char(10),
	gtr_id_revacc	char(10),
	gtr_recurring	char(10),
	
	gtd_d_due1	char(20),
	gtd_deb_cur1	char(20),
	gtd_debit1	char(20),
	pay_id1	char(10),
	cbk_id1	char(34),
	gtd_amt_oth1	char(20),
	gtd_cre_cur1	char(20),
	gtd_credit1	char(20),
	gtd_seppay1	char(10),
	gtd_holdpay1	char(10),
	gtd_d_disc1	char(20),
	gtd_disc_cur1	char(20),
	gtd_disc_loc1	char(20),
	gtd_disc_rate1	char(10),
	gtd_disc_expt1	char(20),
	gca_id_pay1	char(10),
	gla_id_pay1	char(20),
	
	gtd_d_due2	char(20),
	gtd_deb_cur2	char(20),
	gtd_debit2	char(20),
	pay_id2		char(10),
	cbk_id2		char(34),
	gtd_amt_oth2	char(20),
	gtd_cre_cur2	char(20),
	gtd_credit2	char(20),
	gtd_seppay2	char(10),
	gtd_holdpay2	char(10),
	gtd_d_disc2	char(20),
	gtd_disc_cur2	char(20),
	gtd_disc_loc2	char(20),
	gtd_disc_rate2	char(10),
	gtd_disc_expt2	char(20),
	gca_id_pay2	char(10),
	gla_id_pay2	char(20),
	
	gtd_d_due3	char(20),
	gtd_deb_cur3	char(20),
	gtd_debit3	char(20),
	pay_id3		char(10),
	cbk_id3		char(34),
	gtd_amt_oth3	char(20),
	gtd_cre_cur3	char(20),
	gtd_credit3	char(20),
	gtd_seppay3	char(10),
	gtd_holdpay3	char(10),
	gtd_d_disc3	char(20),
	gtd_disc_cur3	char(20),
	gtd_disc_loc3	char(20),
	gtd_disc_rate3	char(10),
	gtd_disc_expt3	char(20),
	gca_id_pay3	char(10),
	gla_id_pay3	char(20),
	
	gtd_d_due4	char(20),
	gtd_deb_cur4	char(20),
	gtd_debit4	char(20),
	pay_id4		char(10),
	cbk_id4		char(34),
	gtd_amt_oth4	char(20),
	gtd_cre_cur4	char(20),
	gtd_credit4	char(20),
	gtd_seppay4	char(10),
	gtd_holdpay4	char(10),
	gtd_d_disc4	char(20),
	gtd_disc_cur4	char(20),
	gtd_disc_loc4	char(20),
	gtd_disc_rate4	char(10),
	gtd_disc_expt4	char(20),
	gca_id_pay4	char(10),
	gla_id_pay4	char(20)
)");
	
echo 'reg_ar created';
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

    	//calculate debit/credit
    	if ($row['AMS_BALANCE'] == 0){
    		$debit = 0;
    		$credit = 0;
    	} elseif($row['AMS_BALANCE'] > 0){
    		$debit = $row['AMS_BALANCE'] ;
    		$credit = 0;
    	} elseif($row['AMS_BALANCE'] < 0){
    		$debit = 0;
    		$credit = $row['AMS_BALANCE'];
    		$credit = $credit * -1;
    	}
    	
    	// Branch Code Conversion

    	$branch = '�XXXX�';
    	foreach ($branchlist as $int)
    	{
    	
    		if ($int['old'] == $row ['AMS_BRANCH'])
    		{
    			$branch = $int['new'];
    			break;
    		}
    	}

//Department Conversion
    	switch ($row ['SALES_TYPE']) {
    		case 'Parts' :
    			$dept = '300';
    			$subacc = '01';
    			$gl = '122010';
    			break;
    		case 'Service' :
    			$dept = '400';
    			$subacc = '01';
    			$gl = '122010';
    			break;
    		case 'Machine' :
    			$dept = '500';
    			$subacc = '02';
    			$gl = '122010';
    			break;
    		default:
    			$dept = '200';
    			$subacc = '01';
    			$gl = '122010';
    	}
    	
//Manage missing invoice no
		if (strlen($row['AMS1_INVOICE']) == 0){
			$invid = 'No Inv Id';
		} else {
			$invid = $row['AMS1_INVOICE'];
		}
    	
// Calculate accounting period
		$year = substr($row ['AMS1_INVOICE_DATE'],0,4);
		$period = substr($row ['AMS1_INVOICE_DATE'],5,2);
		
// Reformat customer numbers
// if customer number starts with a 0 then remove it
//		$custlen = strlen($row['AMS_CUSTOMER']);
		if (strlen($row['AMS_CUSTOMER']) == '5')
			{
				$customer = '0'.$row['AMS_CUSTOMER'];
			} else {
				$customer = $row['AMS_CUSTOMER'];
			}
			
		foreach ($customerlist as $int)
			{
				if (trim($int['old']) == $customer)
				{
					$custno = $int['new'];
					break;
				} else {
					$custno = $customer;
				}
			}
			
//Reformat date
$docdate = substr($row['AMS1_INVOICE_DATE'],5,2).'-'.substr($row['AMS1_INVOICE_DATE'],8,2).'-'.substr($row['AMS1_INVOICE_DATE'],0,4);
$duedate = substr($row['DUE_DATE'],5,2).'-'.substr($row['DUE_DATE'],8,2).'-'.substr($row['DUE_DATE'],0,4);


        $Ifxsql1 = $Ifxdb->query1("insert into reg_ar(
        cpy_id,
        usr_id,
        gca_id,
        gla_id,
        gtr_d_ent,
        gtr_d_trs,
        gtr_entry_id,
        gtr_doc_id,
        gtr_desc,
        gtr_debit,
        gtr_credit,
        gtr_deb_cur,
        gtr_acc_rate,
        gtr_rec_letter,		
        gtr_ent_type,
        bra_id,
        atp_id,
        cur_id_doc,
        gtr_cre_cur,
        dpr_id,
        gtr_posted,
        gpr_id,
        gpr_year,
        gtr_glarap,
        gll_id,
        csc_id,
        gtd_d_due1,
        gtd_deb_cur1,
        gtd_debit1,
        pay_id1,
        gtd_cre_cur1,
        gtd_credit1        
        
		) values (
     	'HMUK',
        '0000',
        'COA',
        '".$gl."',
        '".$docdate."',
        '".$docdate."',
        80,
        '".$invid."',		
        '".$invid."',	
        '".$debit."',
        '".$credit."',
        '".$debit."',
        1,
        ' ',
        '80',
		'".$branch."',
        '".$subacc."',
        'GBP',		
        '".$credit."',
        '".$dept."',
        0,
        '".$period."',
        '".$year."',
        1,
        'ARAPCO',
        '".$custno."',
        '".$duedate."',
        '".$debit."',
        '".$debit."',
        'CRDT',
        '".$credit."',
        '".$credit."'
)");
       $rows++;

    }
    echo $rows. '  Rows loaded';
    $Ifxdb->closeConnection();
    unset($_POST);
//    display_rows($Ifxsql1,$Ifxdb);
    
    
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
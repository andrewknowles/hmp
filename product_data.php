<?php
//Product data load for HCMUK
//A. Knowles 06/2014
include('includes/db_Ifxlib.php');
include('includes/db_Mylib.php');
require('includes/table_lib.php');

set_time_limit(0);

$myIniFile = parse_ini_file("includes/idb.ini", TRUE);

// create the MySQL connection
$Myconfig = new Myconfig($myIniFile['IDBMYSQL']['server'], $myIniFile['IDBMYSQL']['login'], $myIniFile['IDBMYSQL']['password'], $myIniFile['IDBMYSQL']['database'], $myIniFile['IDBMYSQL']['extension'], $myIniFile['IDBMYSQL']['mysqlformat']);
$Mydb     = new Mydb($Myconfig);
$Mydb->openConnection();
//load manufacturer conversion table from MySQL
$mfrlist = $Mydb->load_values('manufacturerp');

// create the Informix connection
$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb     = new Ifxdb($Ifxconfig);

//create reg_pro_total and reg_pro_summary tables
if (isset($_POST['reset']) && $_POST['confirm'] == 'confirm')
  {
    $Ifxsql1 = $Ifxdb->query1("
	drop table if exists reg_pro_total;

	create table reg_pro_total (
	mfr			CHAR(10),
	part		CHAR(30),
	desc		CHAR(50),
	branch		CHAR(10),
	bin			CHAR(10),
	lastrec		CHAR(20),
	lastact		CHAR(20),
	lastsale	CHAR(20),	
	onhand		DECIMAL(16,2),
	avgcost		DECIMAL(16,2),
	curcost		DECIMAL(16,2),
	list		DECIMAL(16,2),
	stock		INT,
	demand1		INT,
	demand2		INT,
	demand3		INT,
	demand4		INT,
	demand5		INT,
	demand6		INT,
	demand7		INT,
	demand8		INT,
	demand9		INT,
	demand10	INT,
	demand11	INT,
	demand12	INT,
	call1		INT,
	call2		INT,
	call3		INT,
	call4		INT,
	call5		INT,
	call6		INT,
	call7		INT,
	call8		INT,
	call9		INT,
	call10		INT,
	call11		INT,
	call12		INT,
	flag1		CHAR(1),
	flag2		CHAR(1),
	flag3		CHAR(1),
	newmfr		CHAR(30),
	mantar		CHAR(30));
	
	drop table if exists reg_pro_summary;
	
	create table reg_pro_summary (
	mfr			CHAR(10),
	part		CHAR(30),
	desc		CHAR(50),
	qty			DECIMAL(16,2),
	stock		CHAR(10),
	newmfr		CHAR(30),
    mantar		CHAR(30),
	imancode	CHAR(10),
    supplier	CHAR(10));
	");    		
//    drop table if exists reg_pro_inter;
//    		
//    create table reg_pro_inter (
//	pmf_id    	char(5),
//	pro_id     	char(30),
//	pdi_desc	char(50))	

    
    echo 'reg_pro_total/reg_pro_summary/reg_pro_inter created';
    $Ifxdb->closeConnection();
    unset($_POST);
  }

//load reg_pro_summary  
if (isset($_POST['loadtext']))
  {
    $rows       = 0;
    $excelFile  = realpath($_POST['fileexcel1']);
    $excelSheet = $_POST['sheetexcel1'];
    $excelDir   = dirname($excelFile);
    $connection = odbc_connect("Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=$excelFile;DefaultDir=$excelDir", '', '');
    $result     = odbc_exec($connection, "select * from [" . $excelSheet . "$]");
    while ($row = odbc_fetch_array($result))
      {
        
        //Handle null Mfr
        if (strlen($row['Netterm_Supplier_Code']) == 0)
          {
            $mfr = 'No Mfr';
          }
        else
          {
            $mfr = $row['Netterm_Supplier_Code'];
          }
//        $mfr = str_replace("'", " ", $mfr);
//        $mfr = str_replace('"', ' in. ', $mfr);
//        $mfr = str_replace('”', ' in. ', $mfr);
        
        //Get new manufacturer    	
        $newman = 'DIVP';
        foreach ($mfrlist as $int)
          {
            if (trim($int['old']) == trim($row['Irium_Man_Code']))
              {
                $newman = $int['new'];
                break;
              }
          }
        
        // Handle null part numbers 
        if (strlen($row['Netterm_Stock_Code']) == 0)
          {
            $partno = 'No part no';
          }
        else
          {
            $partno = $row['Netterm_Stock_Code'];
          }
          
        $partno   = str_replace("'", " ", $partno);
//        $partno   = str_replace('"', ' IN. ', $partno);
        $partno   = str_replace('”', '"', $partno);
        $partno = trim($partno);
        //Strip quotes
        $partdesc = $row['Netterm_Stock_Description'];
        if (strlen($partdesc) == 0)
          {
            $partdesc = $partno;
          }
        $partdesc = str_replace("'", " ", $partdesc);
//        $partdesc = str_replace('"', ' IN. ', $partdesc);
        $partdesc = str_replace('”', '"', $partdesc);
        $partdesc = str_replace("#", " ", $partdesc);
        $partdesc = str_replace("$", " ", $partdesc);
        $partdesc = str_replace(";", " ", $partdesc);
        $partdesc = str_replace("|", " ", $partdesc);
        $partdesc = str_replace("*", " ", $partdesc);
        
        //Insert query
        $Ifxsql1 = $Ifxdb->query1("insert into reg_pro_summary(
					mfr,
					part,
					desc,
					stock,
					newmfr,
        			imancode,
    				supplier		
		) values (
				
	    '" . $mfr . "',
        '" . $partno . "',
        '" . $partdesc . "',
		'" . $row['Current_Stock_Item'] . "',
		'" . $newman . "',
        '" . $row['Irium_Man_Code'] . "',
        '" . $row['AP_Code'] . "'		
		)");

        $rows++;
      }
    echo $rows . '  Rows loaded';
    $Ifxdb->closeConnection();
    unset($_POST);
  }

//load reg_pro_total  
if (isset($_POST['loadexcel']))
  {
    $rows       = 0;
    $excelFile  = realpath($_POST['fileexcel']);
    $excelSheet = $_POST['sheetexcel'];
    $excelDir   = dirname($excelFile);
    $connection = odbc_connect("Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=416;Dbq=$excelFile;DefaultDir=$excelDir", '', '');
    $result     = odbc_exec($connection, "select * from [" . $excelSheet . "$]");
    while ($row = odbc_fetch_array($result))
      {
//        if ($row['IF_PART']=='WZX7012"WIP' && $row['IF_BRANCH']==1)
//        {
//        	echo $row['IF_BRANCH'].'</BR>';
//        	exit;
//        }
        //Handle null Mfr
        if (strlen($row['IF_MFR']) == 0)
          {
            $mfr = 'OTHE';
          }
        else
          {
            $mfr = $row['IF_MFR'];
          }
//        $mfr = str_replace("'", " ", $mfr);
//        $mfr = str_replace('"', ' in. ', $mfr);
//        $mfr = str_replace('”', '"', $mfr);
        
        // Handle null part numbers 
        if (strlen($row['IF_PART']) == 0)
          {
            $partno = 'No part no';
          }
        else
          {
            $partno = $row['IF_PART'];
          }
        $partno = trim($partno);
        $partno   = str_replace("'", " ", $partno);
//        $partno   = str_replace('"', ' IN. ', $partno);
        $partno   = str_replace('”', '"', $partno);
        
//Strip quotes and other characters
        $partdesc = $row['IF_DESC'];
        if (strlen($partdesc) == 0)
          {
            $partdesc = $partno;
          }
        $partdesc = str_replace("'", " ", $partdesc);
//        $partdesc = str_replace('"', ' IN. ', $partdesc);
        $partdesc = str_replace('”', '"', $partdesc);
        $partdesc = str_replace("#", " ", $partdesc);
        $partdesc = str_replace("$", " ", $partdesc);
        $partdesc = str_replace(";", " ", $partdesc);
        $partdesc = str_replace("|", " ", $partdesc);
        $partdesc = str_replace("*", " ", $partdesc);
        
//Insert query
//Changed last received date as requested by Don now uses column AN on extended data tab    	
        $Ifxsql1  = $Ifxdb->query1("insert into reg_pro_total(
		mfr,
		part,
		desc,
		branch,
		bin,
		lastrec,
		lastact,
		lastsale,	
		onhand,
		avgcost,
		curcost,
		list,
		demand1,
		demand2,
		demand3,
		demand4,
		demand5,
		demand6,
		demand7,
		demand8,
		demand9,
		demand10,
		demand11,
		demand12,
		call1,
		call2,
		call3,
		call4,
		call5,
		call6,
		call7,
		call8,
		call9,
		call10,
		call11,
		call12
          
		) values (
        		        		
        '" . $mfr . "',
        '" . $partno . "',
        '" . $partdesc . "',
		'" . $row['IF_BRANCH'] . "',
        '" . $row['IF_BINLOC'] . "',
        '" . $row['LAST_REC_2'] . "',
        '" . $row['IF_LSTACT'] . "',
        '" . $row['IF_LSTSAL'] . "',
        '" . $row['IF_AVGONHAND'] . "',
        '" . round($row['IF_AVGCOST'], 2) . "',
        '" . $row['IF_CURCOST'] . "',
        '" . $row['IF_LIST'] . "',
        '" . $row['IF_DEMND_1'] . "',
        '" . $row['IF_DEMND_2'] . "',
        '" . $row['IF_DEMND_3'] . "',
        '" . $row['IF_DEMND_4'] . "',
        '" . $row['IF_DEMND_5'] . "',
        '" . $row['IF_DEMND_6'] . "',
        '" . $row['IF_DEMND_7'] . "',
        '" . $row['IF_DEMND_8'] . "',
        '" . $row['IF_DEMND_9'] . "',
        '" . $row['IF_DEMND_10'] . "',
        '" . $row['IF_DEMND_11'] . "',
        '" . $row['IF_DEMND_12'] . "',
        '" . $row['IF_CALLS_1'] . "',
      	'" . $row['IF_CALLS_2'] . "',
      	'" . $row['IF_CALLS_3'] . "',
      	'" . $row['IF_CALLS_4'] . "',
      	'" . $row['IF_CALLS_5'] . "',
      	'" . $row['IF_CALLS_6'] . "',
      	'" . $row['IF_CALLS_7'] . "',
      	'" . $row['IF_CALLS_8'] . "',
      	'" . $row['IF_CALLS_9'] . "',
      	'" . $row['IF_CALLS_10'] . "',
      	'" . $row['IF_CALLS_11'] . "',
      	'" . $row['IF_CALLS_12'] . "'		
      	)");
        $rows++;
      
      }
    echo $rows . '  Rows loaded';
    
//automated checking and reporting
    echo'</BR>';
    $Ifxdb->closeConnection();
    unset($_POST);
    clean_up($Ifxsql1, $Ifxdb);
    echo 'Clean up done';
    echo'</BR>';   
    create_index($Ifxsql1, $Ifxdb);
    echo 'Indexes created';
    echo'</BR>';
    update_stock_flag($Ifxsql1, $Ifxdb);
    echo 'Stock flag updated';
    echo'</BR>';
    update_mfr_code($Ifxsql1, $Ifxdb);
    echo 'Mfr Codes updated';
    echo'</BR>';
//    insert_reg_pro_inter($Ifxsql1, $Ifxdb);
//    echo 'Insertion in reg_pro_inter';
//    echo'</BR>';
//    update_descriptions($Ifxsql1, $Ifxdb);
//    echo 'Descriptions updated';
//    echo'</BR>';
    update_bin($Ifxsql1, $Ifxdb);
    echo 'Bin No updated';
    echo'</BR>';
//Check for parts in reg_pro_total not in reg_pro_summary
    $result2 = $Ifxdb->query1("select mfr, part from reg_pro_total t where not exists(select * from reg_pro_summary s where s.mfr = t.mfr and s.part = t.part)");
    while ($row = odbc_fetch_array($result2))
    {
    	echo 'In reg_pro_total but not in reg_pro_summary '.$row['mfr'].'  '.$row['part'];
    	echo'</BR>';
    }     
//Check blank new Mfr codes
    $result2 = $Ifxdb->query1("select mfr, part, branch from reg_pro_total where newmfr is null and stock = 1");
    while ($row = odbc_fetch_array($result2))
    	{
    	echo 'Missing Newmfr code '.$row['mfr'].'  '.$row['part'].'  '	.$row['branch'];
    	echo'</BR>';
    	}
//--------------------------------------------- 
//Check average cost < 0
    	$result2 = $Ifxdb->query1("select mfr, part, branch, avgcost from reg_pro_total where avgcost < 0 and onhand > 0");
    	while ($row = odbc_fetch_array($result2))
    	{
    		echo 'Ave Cost < 0 '.$row['mfr'].'  '.$row['part'].'  '	.$row['branch'].'  '	.$row['avgcost'];
    		echo'</BR>';
    	}
    	//---------------------------------------------   
    $arr = 0;
    $result2 = $Ifxdb->query1("select count(*) val from reg_pro_summary where (desc matches '*$*' or desc matches '*#*' or desc matches '*|*' or desc matches '*;*')");
    $arr = odbc_fetch_array($result2);

    if ($arr['val'] == 0)
    	{
    		echo 'No bad characters in reg_pro_summary';
    	} else {
    		echo $arr['val']. '  rows with bad characters($,#,|,;)';
    	}
    echo'</BR>';
    $arr = 0;
    $result2 = $Ifxdb->query1("select count(*) val from reg_pro_total where (desc matches '*$*' or desc matches '*#*' or desc matches '*|*' or desc matches '*;*')");
    $arr = odbc_fetch_array($result2);
    if ($arr['val'] == 0)
    {
    	echo 'No bad characters in reg_pro_total';
    } else {
    	echo $arr['val']. '  rows with bad characters($,#,|,;)';
    }
    echo'</BR>';
    $result2 = $Ifxdb->query1("select mfr, part, branch, onhand, avgcost from reg_pro_total where onhand < 0");
//    $arr = odbc_fetch_array($result2);
    while ($row = odbc_fetch_array($result2))
    {
    echo 'Negative quantity on Mfr '.$row['mfr'].' Part No  '.$row['part'].' Branch '	.$row['branch'].' Qty  '.$row['onhand'].' Value  '.$row['avgcost'];
    echo'</BR>';
    }
    echo'</BR>';
    $result2 = $Ifxdb->query1("select count(*) ct from reg_pro_summary where length(imancode) <> 2 and stock = 'Yes'");
    //    $arr = odbc_fetch_array($result2);
    while ($row = odbc_fetch_array($result2))
    {
    	echo 'No Irium_Man_Code on  '.$row['ct'];
    	echo'</BR>';
    }
    echo 'Program complete';
        
  }

  function clean_up($res, $obj)
  {
  	$obj->query1("delete from reg_pro_total where mfr = 'OTHE' and part = 'No part no'");
  	$obj->query1("delete from reg_pro_summary where mfr = 'No Mfr' and part = 'No part no'");
  }
  
 function create_index($res, $obj)
  {
  	$obj->query1("create index ak_reg_pro_summary on reg_pro_summary(mfr,part)");
  	$obj->query1("create index ak_reg_pro_total on reg_pro_total(mfr,part)");
  }
  
  function update_stock_flag($res, $obj)
  {
//  	$obj->query1("Update reg_pro_total t set stock = (select case when stock = 'Yes' Then 1 when stock = 'Move' Then 0 when stock = 'Manual' Then 0 when stock = 'No' then 0 end from reg_pro_summary s where
//	s.mfr = t.mfr and s.part = t.part and stock = 'Yes')");
  	
  	$obj->query1("Update reg_pro_total t set stock = 1 where exists (select * from reg_pro_summary s where stock = 'Yes' 
  			and s.mfr = t.mfr and s.part = t.part)");
  }
  
  function update_mfr_code($res, $obj)
  {
  	$obj->query1("update reg_pro_total t set newmfr = (select newmfr from reg_pro_summary s where t.mfr = s.mfr and t.part = s.part and stock = 'Yes')");
  }
  
//  function insert_reg_pro_inter($res, $obj)
//  {
//  	$obj->query1("insert into reg_pro_inter select newmfr, part, desc from reg_pro_total where stock = 1 
//  	and branch in (1,2,3,4,5,6,7,9,11,12,14) group by 1,2,3");
//  }
  
//  function update_descriptions($res, $obj)
//  {
//  	$obj->query1("Update reg_pro_inter set pdi_desc = null where 1=1");
//  	$obj->query1("Update reg_pro_inter i set pdi_desc = (select desc from reg_pro_summary s where 
//	i.pmf_id = s.mfr and i.pro_id = s.part and stock = 'Yes')");
//  }
  
  function update_bin($res, $obj)
  {
  	$obj->query1("update reg_pro_total set bin = 'No Bin' where bin = '' and  branch in (1,2,3,4,5,6,7,9,11,12,14) and stock = 1");
  }
  
  function check_duplicate($res, $obj)
  {
  	$obj->query1("update reg_pro_total set bin = 'No Bin' where bin = '' and  branch in (1,2,3,4,5,6,7,9,11,12,14) and stock = 1");
  }
  
?>
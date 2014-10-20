<?php
include('includes/db_Ifxlib.php');
$myIniFile = parse_ini_file ("includes/idb.ini", TRUE);
$Ifxconfig = new Ifxconfig($myIniFile['IDBIFX']['odbc'], $myIniFile['IDBIFX']['login'], $myIniFile['IDBIFX']['password']);
$Ifxdb = new Ifxdb($Ifxconfig);
$result2 = $Ifxdb->query1("select count(*) val from reg_pro_summary where (desc matches '*$*' or desc matches '*#*' or desc matches '*|*' or desc matches '*;*')");
$arr = odbc_fetch_array($result2);
echo $arr['val'];
echo odbc_num_rows($result2);

$arr2 = $Ifxdb->countRows($result2);
echo 'xx'.$arr2;
?>
<?php 
$myfile = fopen("c:/tmp/tariftest.txt", "r") or die("Unable to open file!");
while(!feof($myfile)) {
	$line = fgets($myfile);
	$linetype = substr($line,10,2);
	if ($linetype == '01')
	{
		$mfr = substr($line,0,3);
		$part = substr($line,3,7);
		$desc = substr($line,12,5);
		echo $mfr.'  '.$part. '   '.$desc.'</BR>' ;
	}







}

?>
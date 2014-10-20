<?php
//this program produces either a price file or a replacement file depending on the value in line 129>131
//set time limit to avoid timeout in long process
set_time_limit ( 0 );
//open input file in read only mode
//this is full file
$file     = fopen("c:/tmp/CGMPP_CGMH_Test File_04272014.txt", "r");
//$file     = fopen("c:/tmp/cgmtest.txt", "r");
//open output file in append mode
$fileout = fopen("c:/tmp/PareninCGMout.txt", "a");
$lastline = 9;
//read first line from input file file
$line     = fgets($file);
//initial look through all records in the file
while (!feof($file))
  {
//loop to process the 3 different line type s for a part
    while ($line <> $lastline)
      {
//position 46 is line type
//switch based on line type      
        switch (substr($line, 45, 1))
        {
//line type 1 is master parts line        	
            case 1:
//linetype
            	$outline = substr($line, 45, 1) . '|'.
//Vendor Identifier - Manufacturer Code
                trim(substr($line, 0, 10)) . '|'.
//Part Number
              	trim(substr($line, 24, 20)) . '|'.
//Description
                trim(substr($line, 55,30)) . '|'.
//Material Pricing Group
                trim(substr($line, 86,2)) . '|'.
//Non-returnable Indicator
                trim(substr($line, 91,1)) . '|'.
//Replacement Code
                trim(substr($line, 92,1)) . '|'.
//Replacement Type
                trim(substr($line, 93,1)) . '|'.
//Minimum Order Qty
                trim(substr($line, 94,5)) . '|'.
//Net weight
                trim(substr($line, 118,9)) . '|'.
//Gross weight
                trim(substr($line, 127,9)) . '|'.
//Length
                trim(substr($line, 136,5)) . '|'.
//Width
                trim(substr($line, 141,5)) . '|'.
//Height
                trim(substr($line, 146,5)) . '|'.
//Unit of measure
                trim(substr($line, 151,2)) . '|'.
//Hazardous Indicator
                trim(substr($line, 153,1)) . '|'.
//Activity Indicator
                trim(substr($line, 155,1)) . '|'.
//Reman Part
                trim(substr($line, 156,1)) . '|'.
//Hose Assy
                trim(substr($line, 157,1)) . '|';
                $outline2 = '';
                $outline3 = '';

                break;
//line type 2 is price line
            case 2:
 //Implementation Date
                $outline2 = '|' . trim(substr($line, 47, 8)) . '|' .
//Component Unit Cost - Dealer Net
                trim(substr($line, 55, 13)). '|' .
//Component Unit List
                trim(substr($line, 68, 13)). '|'.
//Core Full Unit Cost
                trim(substr($line, 81, 13)). '|' .
//Core Full Unit List
                trim(substr($line, 94, 13)). '|' .
//Core Damaged Unit Cost
                trim(substr($line, 107, 13)). '|' .
//Core Damaged Unit List
                trim(substr($line, 120, 13)). '|' .
//Currency Indicator
                trim(substr($line, 133, 3)). '||';
                $outline3 = '';
                break;
//line type 3 is replacement
 //           case 3:
 //Six positions part numbers with all numeric characters have a zero
//inserted in the first position. Part number 123456 becomes 0123456
//when part type = AA
//            	if(trim(substr($line, 71, 2)) == 'AA')
//            	{
//            		if(!ctype_alpha(trim(substr($line, 47, 20))))
//            		{
//            			$replno = '0'.trim(substr($line, 47, 20));
//            		}
//            	} else {
//            		$replno = trim(substr($line, 47, 20));
//            	}
//replacing part no
//                $outline3 = '|||||||||' . $replno . '|' .
//replacement quantity 
//                trim(substr($line, 67, 4)) . '|'.
//part type
//                trim(substr($line, 71, 2)) . '|';
//                break;
        }

        break;
      }
//echo $outline.' -- '.$outline2.  '</BR>';
//store current line type    
    $lastlinetype = substr($line, 45, 1);
//    echo $outline.' -- '.$outline2. ' -- '.$lastlinetype. '</BR>';
//read next line in file
    $line     = fgets($file);
    
//if the next line in the file is type 1 output the data to file    
   if (trim(substr($line, 45, 1)) == 1 and strlen($outline2) > 2)
      {
        if (strlen($outline2) == 0  || strlen($outline2) =='')
          {
            $outline2 = '||';
          }
        
//        if (strlen($outline3) == 0 || strlen($outline2) =='')
//          {
//            $outline3 = '||';
//          }
//        $outlinet = $outline . $outline2 . $outline3;
          $outlinet = $outline . $outline2;
//switch to process only price records or only replacement records
//$lastlinetype == 3 for replacement, $lastlinetype == 2 for price records
        if ($lastlinetype == 2)
        {
//        	if ($lastlinetype == 3)
//        		{ 
//        			$outlinet = $outline  . $outline3;
//        		} 
        	if ($lastlinetype == 2)
        		{
        			$outlinet = $outline  . $outline2. '||';
        		}
        		
        }
        
        if ($lastlinetype == 3)
        {
        	$outlinet = $outline  . $outline2. '||||';
        }
//Append line to output file
echo 'To file' .$outlinet.'</BR>';
        	fwrite($fileout, $outlinet."\r\n");

       

        $outline2 = '';
        $outline3 = '';
      }
      
//handle case where it is the  last line in the file    
    if (feof($file))
      {
      	echo 'last line'.substr($line, 45, 1);       
        switch (substr($line, 45, 1))
        {
            case 1:
                echo $outline;
                break;
            case 2:
                echo $outline . ' ' . $line;
                break;
            case 3:
                echo $outline . $outline2 . '|' . substr($line, 1, 3) . '|' . trim(substr($line, 4, 7)) . '|' . trim(substr($line, 11, 14));
                break;
        }
      }
      
  }
  

fclose($file);



?>
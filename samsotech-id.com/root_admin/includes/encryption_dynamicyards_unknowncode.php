<?php function Encrypt_dynamicyards($i0){if(strlen($i0)>40){return "Please give word with chars count 40 or less";}else{$u1=$i0;$l2=array('0','@','!','#',')','a','^','&','*','(','0','@','!','#',')','a','^','&','*','(','0','@','!','#',')','a','^','&','*','(','0','@','!','#',')','a','^','&','*','(');$r3=array('0','a','b','c','d','e','f','g','h','i','0','a','b','c','d','e','f','g','h','i','0','a','b','c','d','e','f','g','h','i','0','a','b','c','d','e','f','g','h','i');$t4=array('0','a','k','g','n','f','b','c','d','h','0','a','k','g','n','f','b','c','d','h','0','a','k','g','n','f','b','c','d','h','0','a','k','g','n','f','b','c','d','h');$v5=array('0','k','a','c','m','o','q','x','z','b','0','k','a','c','m','o','q','x','z','b','0','k','a','c','m','o','q','x','z','b','0','k','a','c','m','o','q','x','z','b');$c6="";$y7=strlen($u1);for($u8=0;$u8<$y7;$u8++){$c6=$c6.$l2[$u8+1].$r3[$u8+1].substr($u1,$u8,1).$t4[$u8+1].$v5[$u8+1];}return $c6;}}function Decrypt_dynamicyards($i0){$l9=$i0;$y7=strlen($l9);$d10=$y7/5;$s11="";for($u8=0;$u8<=$d10-1;$u8++){$s11=$s11.substr($l9,($u8*5)+2,1);}return $s11;}?>
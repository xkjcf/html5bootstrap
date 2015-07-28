<?php
	header('Access-Control-Allow-Origin: http://xkjcf.github.io');
	$iplogfile="app/myresume";
	
	if( file_exists($iplogfile) ){
		$iplists = json_decode(file_get_contents($iplogfile), true);
	}else{
		$iplists = array("iplog"=>array());
	}
	print_r($iplists);
?>

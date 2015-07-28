<?php

	$iplogfile=isset($_GET['app']) ? $_GET['app'] : "iploglist";
	$iplogfile="app/".$iplogfile;
	if(isset($_GET['query'])){
		header('Access-Control-Allow-Origin: http://xkjcf.github.io');
		
		if( file_exists($iplogfile) ){
			$iplists = json_decode(file_get_contents($iplogfile), true);
		}else{
			$iplists = array("iplog"=>array());
		}
		//echo json_encode($iplists);
		
		$black = "北京市";
		foreach($iplists['iplog'] as $ip){
			if($ip['city']=="") continue;
			if($ip['city']==$black) continue;
			if(!isset($ips[$ip['city']])){
				$ips[$ip['city']]['no'] = 1;
				$ips[$ip['city']]['city'] = $ip['city'];
				$ips[$ip['city']]['lng'] = $ip['lng'];
				$ips[$ip['city']]['lat'] = $ip['lat'];
			}else{
				$ips[$ip['city']]['no'] += 1;
			}
				
		//	print_r($ip);
		}
		$rets = array("iplog"=>array());
		foreach($ips as $key=>$value){
			array_push($rets['iplog'], $value);	
		}
		
		//print_r($rets);

		echo json_encode($rets);
		
	}else{	
		echo "<center>";
		echo "<h1>I C U</h1>";
		echo "<h3>May you all have a goog Job!</h3>";
		echo file_get_contents("smile.html");
		echo "</center>";

		if( file_exists($iplogfile) ){
			$iplists = json_decode(file_get_contents($iplogfile), true);
		}else{
			$iplists = array("iplog"=>array());
		}
		//print_r($iplists);
		// !!!!!!!
		$reqip = $_SERVER['REMOTE_ADDR'];
		$reqtime = strftime("%Y-%m-%d %H:%M:%S",$_SERVER['REQUEST_TIME']);

		$url="http://ip.taobao.com/service/getIpInfo.php?ip=".$reqip;
		$html = file_get_contents($url);
		//echo $html."\n";
		$info = json_decode($html);

		//print_r($info);
		//echo $info->code;
		//echo $info->data->city."\n";
		// !!!!!!!
		$city = $info->data->city;

		$cityurl="http://api.map.baidu.com/geocoder?address=".$city."&output=json&key=f247cdb592eb43ebac6ccd27f796e2d2"; 

		$html = file_get_contents($cityurl);
		//echo $html."\n";
		//print_r(json_decode($html));
		$cityinfo=json_decode($html);
		// !!!!!!!
		$lng = $cityinfo->result->location->lng;
		$lat = $cityinfo->result->location->lat;

		$iplist = array('ip'=>$reqip,'reqtime'=>$reqtime,'city'=>$city,'lng'=>$lng,'lat'=>$lat);
		array_push($iplists['iplog'], $iplist);
		file_put_contents($iplogfile, json_encode($iplists));
	}
?>

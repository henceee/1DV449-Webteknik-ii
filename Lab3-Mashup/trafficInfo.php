<?php

//SR API
//http://sverigesradio.se/api/documentation/v2/metoder/trafik.html
//http://sverigesradio.se/api/documentation/v2/generella_parametrar.html


$callback = "JSON";

//pagination = true, page = x, size = y

$format = "json";
$pagination="true";
$size=50;
$page=1;
$minutesToCache=10;
$cachefile="trafficInfo.txt";

//If cached file exceeds n minutes, get updated traffic info
//and put it into the cachefile.

if(file_exists($cachefile) && filemtime($cachefile) <time()-60*$minutesToCache)
{
	$url ="http://api.sr.se/api/v2/traffic/messages?format={$format}&pagination={$pagination}&size={$size}page={$page}";
	$ch =curl_init();

	curl_setOpt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

	$trafficInfo = curl_exec($ch);

	curl_close($ch);

	file_put_contents($cachefile, $trafficInfo);


}

//print the contents of the cached file
$handle = fopen($cachefile,"r");
$info = fread($handle, filesize($cachefile));
print_r($info);




<?php

class Curl
{

	/**
	* Get data from an URL
	* @param string url - the url to the site
	* @param bool returnTransferBool
	* @return 
	*/
	public function curlGetReq($url='', $returnTransferBool=1)
	{
		
		$ch =curl_init();

		curl_setOpt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, $returnTransferBool);

		$data = curl_exec($ch);

		curl_close($ch);

		return $data;
	}

	/**
	* Make a post to an URL, with
	* an array faking input
	* @param string url - the url to the sit
	* @param array postArr
	* @return 
	*/
	 public function doPost($url='',$postArr='')
	 {	 
	 	$ch =curl_init();

		curl_setOpt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);

		$postArr=http_build_query($postArr);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postArr);
		$data = curl_exec($ch);

		curl_close($ch);

		return $data;
		

	 	
	 }

	/**
	* Search the DOM document of a page
	* return the result.
	* @return DOMNodeList result 
	*/
	 public function getDOMData($data,$query)
	 {
	 	$DOM = new DomDocument();

	 	if(@$DOM->loadHTML($data))
	 	{
	 		 $xpath = new DOMXPath($DOM);
	 		 $result = $xpath->query($query);
	 		
			return $result;

	 	}
	 	else
	 	{
	 		die("Hoppsan! Fel vid inläsningen");
	 	}
	 }
}
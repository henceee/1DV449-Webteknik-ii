<?php

 $key = "AIzaSyCoFrLQBkS8mCVxIqiWIN8cCteqLcpQ-y8";
 $callback = "Main.initMap";

echo <<<EOD
 <!DOCTYPE html>
<head>
	
		<!-- META DATA -->
		<meta charset="utf-8">
    	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<meta name="author" 		content="Henric Gustafsson" />
		<meta name="copyright"		content="NA" />
		<meta name="keywords"		content="1DV4" />
		<meta name="description"	content="" />
		
		<!-- CSS -->
		
		<!-- <link href=".css" rel="stylesheet" type="text/css" media="screen" title="Default" /> -->
<title>Traffic Mashup</title>

</head>

<body>
	<div>
	<!-- 0 = Vägtrafik, 1 = Kollektivtrafik, 2 = Planerad störning, 3 = Övrigt -->
	<p>Välj traffikinformation att visa:</p>
	<p>Vägtrafik
    <input type="checkbox" class="toggle" id="0"></p>
    <p>Kollektivtrafik
    <input type="checkbox" class="toggle" id="1"></p>
    <p>Planerad störning
    <input type="checkbox" class="toggle" id="2"></p>
    <p>Övrigt
    <input type="checkbox" class="toggle" id="3"></p>
    <input type="button" value="Välj" id="trafficSubmit">


</div>

<ul id="list" style="float:right; margin-right: 100px;">

</ul>
</div>
<div id="map" style="float:left; width: 500px;
    height: 500px; margin-top: 10px">

    </div>

<!-- 0 = Vägtrafik, 1 = Kollektivtrafik, 2 = Planerad störning, 3 = Övrigt -->

<!-- JAVASCRIPT -->
		<script type="text/javascript" language="javascript" src="js/Ajax.js"></script>			
		<script type="text/javascript" language="javascript" src="js/Main.js"></script> 
		<script type="text/javascript" language="javascript" src="https://maps.googleapis.com/maps/api/js?key={$key}&callback={$callback}"></script>

</body>
</html>
EOD;

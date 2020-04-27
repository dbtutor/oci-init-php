<html>
 <head>
   <title>OCI</title>
<link rel="icon" sizes="16x16 32x32 57x57 64x64 120x120 144x144" href="https://console.plugins.oci.oraclecloud.com/1.84.0/favicon.png" />
<style>
html {font-family: Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 14px;}
table { border-collapse: collapse;box-shadow: 1px 2px 3px #ccc;border: 0;width: 50%;font-size: 14px;}
table, td, th { border: 1px solid #666;vertical-align: baseline; padding: 4px 5px;font-size: 14px;}
.header-gradient {height: 4px;width: 100%;background: linear-gradient(90deg,#b4ec51 0,#53a0fd);}
.console-branding-bar {height: 30px;background: #1d2733;color: #fff;}
.wrapper-content {margin:8px;}
</style>
 </head>
 <body style="margin: 0px !important;">
 <div class="header-gradient" aria-hidden="true"></div>
 <header class="console-branding-bar"></header>
 <div class="wrapper-content">
 <p> Welcome to the OCI Web Server - <b> <?php echo strtoupper(gethostname()); ?> </b> <p>
	<?php

	$json = file_get_contents("http://169.254.169.254/opc/v1/instance/");
	$obj = json_decode($json);
	$objArray = json_decode($json,true);
	$displayName = $obj->displayName;
	$shape = $obj->shape;
	$ad = $obj->availabilityDomain;
	$region = $obj->canonicalRegionName;
	$faultDomain = $obj->faultDomain;
	$internal_ip = $_SERVER['SERVER_ADDR'];
	$remote_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	$external_ip = exec('curl http://ipecho.net/plain; echo');
	$timeCreated = $obj->timeCreated;
	$timeCreatedNiceFormat = date(DATE_RFC850,substr($timeCreated, 0, -3));

	print ("<p><b>My Info :- </b><br>");
	echo "<br />";
	print ("<table border = 1>");
	print ("<tr><td>Instance Public IP</td><td>$external_ip</td></tr>");
	print ("<tr><td>Instance Private IP</td><td>$internal_ip</td></tr>");
	print ("<tr><td>Your IPv4 address</td><td>$remote_ip</td></tr>");
	print ("<tr><td>Instance</td><td><b>$displayName</b></td></tr>");
	print ("<tr><td>Shape</td><td>$shape</td></tr>");
	print ("<tr><td>Region</td><td>$region</td></tr>");
	print ("<tr><td>Availability Domain</td><td>$ad</td></tr>");
	print ("<tr><td>Fault Domain</td><td>$faultDomain</tr></tr>");
	print ("<tr><td>Instance Created at </td><td>$timeCreatedNiceFormat</tr></tr>");
	
	print ("</table>");
	
	$json_network = file_get_contents("http://169.254.169.254/opc/v1/vnics/");
	$obj_network = json_decode($json_network);
	$objArray_network = json_decode($json_network,true);
	
	print ("<p><b>Networking details :- </b><br>");
	echo "<br />";
	print ("<table border = 1>");
	
	// Loop through the objects & Printing all the keys and values one by one
	$keys = array_keys($objArray_network);
	for($i = 0; $i < count($objArray_network); $i++) {
		if ($keys[$i] == 0) {
			print ("<tr><td colspan='2'> <b> <i> (Primary VNIC) </i></b></td></tr>");
		}
		else {
			print ("<tr><td colspan='2'> (Secondary VNIC) </td></tr>");
		}
		foreach($objArray_network[$keys[$i]] as $key => $value) {

			print ("<tr><td>" . ucfirst($key) . "</td><td>" . $value . "</td></tr>");
		}
		echo "\n";
	}
	
	print ("</table>");
	
	echo "<br />";
	print ("<p><b>Instance Meta Data</b><br>");
	
	echo "<pre>";
	// Print when necessary
	print_r($objArray);
	echo "</pre>";
	?>
	 </div>
  </body>
</html>

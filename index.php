<?php
	$onlineServer = "http://www.bytesbay.com/";

	//redirect to online server
	$url = urlencode($_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	
	header('Location: '.$onlineServer.'prep-hotspot-login?r='.$url);
?>

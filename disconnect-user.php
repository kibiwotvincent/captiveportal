<?php
/*
 * disconnect-user.php
 *
 * terminate user session
 */

if ($_POST['logout_id']) {
	try {
		require_once("captiveportal.inc");
		$sessionID = $_POST['logout_id'];
		captiveportal_disconnect_client($sessionID, 1, "AUTO DISCONNECT");
		
		header('HTTP/1.0 200 OK', true, 200);
		exit("DISCONNECTED");
	}
	catch(\Exception $ex) {
		header('HTTP/1.0 500 Internal Server Error', true, 500);
		exit("ERROR : ".$ex->getMessage());
	}
}
else {
	header('HTTP/1.0 405 Bad Request', true, 405);
	exit("INVALID REQUEST");
}

?>
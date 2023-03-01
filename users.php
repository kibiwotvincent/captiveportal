<?php
/*
 * users.php
 *
 * get active users
 */
 
/* get connected users */
try {
	require_once("captiveportal.inc");
	
	$connectedUsers = captiveportal_read_db();
	print(json_encode($connectedUsers));
	exit;
}
catch(\Exception $ex) {
	print(json_encode(['message' => $ex->getMessage()]));
	exit;
}

print(json_encode(['message' => "Error!"]));
exit;
?>
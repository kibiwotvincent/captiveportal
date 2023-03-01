<?php
	header("Expires: 0");
	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Connection: close");

	$onlineServer = "http://www.bytesbay.com/";
	
	if(!isset($_GET['u']) || !isset($_GET['p'])) { 
		//redirect to online server
		$url = urlencode($_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		
		header('Location: '.$onlineServer.'prep-hotspot-login?r='.$url);
	}
	else {
		$username = $_GET['u'];
		$password = $_GET['p'];
	}
?>

<!doctype html>
<html class="no-js" lang="en">
    <head>
    	
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>BytesBay</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="icon" href="/favicon.png" type="image/png" />
    </head>

    <body>
	<h2>Please wait as we redirect you to internet...</h2>
	<form method="post" action="$PORTAL_ACTION$" id="login_form" style="display: non;">
		<input name="auth_user" type="text" id="auth-user" value="<?php echo $username; ?>">
		<input name="auth_pass" type="text" id="auth-pass" value="<?php echo $password; ?>">
		<input name="auth_voucher" type="hidden">
		<input name="redirurl" type="hidden" value="$PORTAL_REDIRURL$">
		<input name="zone" type="hidden" value="$PORTAL_ZONE$">
		
		<input type="submit" name="accept" class="btn btn-theme d-non" id="login_form_submit" value="Sign In" >
	</form>
	</body>
	<script>
		window.onload = function(){
		  document.getElementById("login_form_submit").click();
		};
	</script>
</html>

<?php
	$onlineServer = "http://www.bytesbay.com/";
	$localServer = "http://localhost/bytesbay/";
	$username = null;
	$password = null;

	if(! isset($_GET['u']) || ! isset($_GET['p'])) {
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
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_local_server_address" content="<?php echo $localServer; ?>">
        <meta name="_online_server_address" content="<?php echo $onlineServer; ?>">
		
		<link rel="icon" href="/favicon.png" type="image/png" />
        
        <link rel="stylesheet" href="captiveportal-bootstrap.min.css">
        <link rel="stylesheet" href="captiveportal-iconkit.min.css">
        <link rel="stylesheet" href="captiveportal-theme.min.css">
    </head>

    <body>
        <div class="auth-wrapper">
            <div class="container-fluid h-100">
                <div class="row flex-row h-100 bg-white">
                    <div class="col-xl-8 col-lg-6 col-md-5 p-0 d-md-block d-lg-block d-sm-none d-none">
                        <div class="lavalite-bg" style="background-image: url('captiveportal-register-bg.jpg')">
                            <div class="lavalite-overlay">
								<div class="text-center" style="padding-top: 20%;">
									<h4 class="text-white mx-auto pb-2" style="text-transform: uppercase; border-bottom: 5px solid #28a745; width: 35%;">BytesBay WiFi HotSpots</h4>
									<h1 class="text-white font-weight-bold" style="font-size: 3rem;">Unlimited. Reliable. Affordable.</h1>
									<div class="row" style="margin-top: 96px;">
										<div class="col-12">
											<ul class="settings m-0 p-0">
											  
											</ul>
										</div>
										<div class="col-12 mt-20">
											<span class="text-white mx-2">© <span id="year"></span> BytesBay. All rights reserved.</span>
										</div>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-7 my-auto p-0">
                        <!--login panel-->
						<div class="authentication-form mx-auto" style="padding: 50px 0px 50px 0px">
                            <h3>Sign In to BytesBay</h3>
                            <p>Happy to see you again!</p>
							   
                            <form class="ajax" id="login_form" action="$PORTAL_ACTION$" method="post">
								<div class="form-group">
									<input type="text" name="auth_user" id="auth-user" class="form-control" placeholder="Phone Number" value="<?php echo $username; ?>" >
									<i class="ik ik-phone"></i>
									<p class="d-none error" for="phone_number"></p>
								</div>
								<div class="form-group">
									<input type="password" name="auth_pass" id="auth-pass" class="form-control" placeholder="Password" value="<?php echo $password; ?>" >
									<i class="ik ik-lock"></i>
									<p class="d-none error" for="password"></p>
								</div>
								<input name="auth_voucher" type="hidden">
								<input name="redirurl" type="hidden" value="$PORTAL_REDIRURL$">
								<input name="zone" type="hidden" value="$PORTAL_ZONE$">
								<div id="login_form_feedback"></div>
								<div class="text-center mt-3">
									<button type="submit" class="btn btn-theme mr-2" id="login_form_submit">Sign In</button>
								</div>
							</form>
                        </div>
						<!--end login panel-->
                    </div>
                </div>
            </div>
        </div>
        
        <script src="captiveportal-jquery-3.3.1.min.js"></script>
        <script src="captiveportal-bootstrap.min.js"></script>
        <script src="captiveportal-custom.js"></script>
    </body>
</html>
